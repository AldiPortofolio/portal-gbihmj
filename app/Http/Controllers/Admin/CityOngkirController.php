<?php namespace digipos\Http\Controllers\Admin;

use digipos\models\Config;
use digipos\models\Category_modem;
use digipos\models\Modem;
use digipos\models\Modem_status;
use digipos\models\Modem_log;
use digipos\models\City_ongkir;
use digipos\models\City_ongkir_detail;

use Validator;
use Auth;
use Hash;
use DB;
use digipos\Libraries\Alert;
use Illuminate\Http\Request;
use digipos\Libraries\Email;
use Carbon\Carbon;
use File;

class CityOngkirController extends KyubiController {

	public function __construct()
	{
		parent::__construct();
		$this->middleware($this->auth_guard); 
		$this->middleware($this->role_guard);
		$this->title 			= "City Ongkir";
		$this->data['title']	= $this->title;
		$this->root_link 		= "city-ongkir";
		$this->model 			= new city_ongkir;

		$this->bulk_action			= true; 
		$this->bulk_action_data 	= [1];
		$this->image_path 			= 'components/both/images/city_ongkir/';
		$this->data['image_path'] 	= $this->image_path;
		$this->image_path2 			= 'components/both/images/web/';
		$this->data['image_path2'] 	= $this->image_path2;
		// $this->product_type 			= ['Kartu Perdana (KP)','Dompet Pulsa (Dompul)'];

		$this->meta_title = Config::where('name', 'web_title')->first();
        $this->meta_description = Config::where('name', 'web_description')->first();
        $this->meta_keyword = Config::where('name', 'web_keywords')->first();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		// $desc_filter = Order_status::select('desc')->whereIn('id', [1,2,3,4,5,6,11])->get();

		// foreach($desc_filter as $dc){
		// 	$dc_filter[$dc->desc] = $dc->desc;
		// }

		$this->field = [
			// [
			// 	'name' 		=> 'images',
			// 	'label' 	=> 'Image',
			// 	'type' 		=> 'image',
			// 	'file_opt' 	=> ['path' => $this->image_path, 'custom_path_id' => 'y']
			// ],
			[
				'name' 		=> 'city_ongkir_name',
				'label' 	=> 'City Ongkir Name',
				'sorting' 	=> 'y',
				'search' 	=> 'text'
			],
			[
				'name' 		=> 'status',
				'label' 	=> 'Status',
				'sorting' 	=> 'y',
				'search' 	=> 'select',
				'search_data' => ['1' => 'Active', '2' => 'Not-active'],
				'type' 		=> 'check'
			]
		];

		return $this->build('index');
	}

	public function create(){
		
		$this->data['title'] 					= "Create ".$this->title;
		
		/*get city raja ongkir*/
		$city_rajaongkir = $this->getCity_rajaongkir();
		$city_rajaongkir2 = [];
		if($city_rajaongkir['status'] == 'success'){
			$city_rajaongkir2 = $city_rajaongkir['data'];
		}

		$this->data['city']	= $city_rajaongkir2;

		//get city_ongkir_detail has selected from other city_ongkir
		$city_ongkir_detail_selected = City_ongkir_detail::pluck('city_id')->toArray();
		$this->data['city_ongkir_detail_selected'] = $city_ongkir_detail_selected;

		return $this->render_view('pages.city_ongkir.create');
		// $this->field = $this->field_create();
		// return $this->build('create');
	}

	public function store(Request $request){
		// $this->validate($request,[
		// 	'name' 			=> 'required|unique:modem,modem_name',
		// ],
		// [
  //           'name.required' => 'Category Modem Name is Required.',
  //           'name.unique' 	=> 'Category Modem Name has already been taken.',
  //       ]
  //   	);

		$last_id 									= $this->model->orderBy('id', 'desc')->first();
		$curr_id 									= ($last_id ? $last_id->id + 1 : 1);
		$this->model->city_ongkir_name				= $request->name;
		$ongkir 									= $this->decode_rupiah($request->ongkir);
		$this->model->ongkir						= $ongkir;
		$this->model->description					= $request->description;
		$this->model->upd_by 						= auth()->guard($this->guard)->user()->id;
		$this->model->status 						= 'y';
		$this->model->save();

		$city 						= $request->city;
		if($city != null){
			foreach ($city as $key => $val) {
				$this->insertCityOngkirDetail($this->model->id, $val, $ongkir);
			
			}
		}
		// $this->increase_version();

		Alert::success('Successfully add '.$this->title);
		return redirect()->to($this->data['path']);
	}

	public function edit($id){
		$this->model 						= $this->model->find($id);
		// dd($this->model);
		$this->data['title'] 				= "Edit City Ongkir ".$this->model->modem_name;
		$this->data['data']  				= $this->model;

		/*get city raja ongkir*/
		$city_rajaongkir = $this->getCity_rajaongkir();
		$city_rajaongkir2 = [];
		if($city_rajaongkir['status'] == 'success'){
			$city_rajaongkir2 = $city_rajaongkir['data'];
		}

		$this->data['city']	= $city_rajaongkir2;

		// get city_ongkir_detail from city_ongkir_id
		$city_ongkir_detail = City_ongkir_detail::where('city_ongkir_id', $id)->pluck('city_id')->toArray();
		$this->data['city_ongkir_detail'] = $city_ongkir_detail;
		// dd($this->data['city_ongkir_detail']);

		//get city_ongkir_detail has selected from other city_ongkir
		$city_ongkir_detail_selected = City_ongkir_detail::where('city_ongkir_id', '!=', $id)->pluck('city_id')->toArray();
		$this->data['city_ongkir_detail_selected'] = $city_ongkir_detail_selected;

		return $this->render_view('pages.city_ongkir.edit');
	}

	public function update(Request $request, $id){
		// $this->validate($request,[
		// 	'name' 		=> 'required|unique:modem,modem_name,'.$id,
		// ],[
  //           'name.required' => 'Category Modem Name is Required.',
  //           'name.unique' 	=> 'Category Modem Name has already been taken.',
  //       ]);

		$this->model 								= $this->model->find($id);
		// dd($this->model);
		$this->model->city_ongkir_name				= $request->name;
		$ongkir 									= $this->decode_rupiah($request->ongkir);
		$this->model->ongkir						= $ongkir;
		$this->model->description					= $request->description;
		$this->model->upd_by 						= auth()->guard($this->guard)->user()->id;
		$this->model->save();

		$city 						= $request->city;
		// dd($city);
		$city_ongkir_detail 		= City_ongkir_detail::where('city_ongkir_id', $id)->get();
		$city2 = [];
		if(count($city_ongkir_detail) > 0 && $city != null){
			foreach ($city_ongkir_detail as $key => $val) {
				if(in_array($val->city_id, $city)){
					array_push($city2, $val->city_id);
					City_ongkir_detail::where('id', $val->id)->update(['ongkir' => $ongkir]);
				}else{
					// delete city ongkir detail
					$uc 	= City_ongkir_detail::where('city_id', $val->city_id);
					$uc->delete();
				}
			}
		}

		if($city != null){
			foreach ($city as $key => $val) {
				if(!in_array($val, $city2)){
					$this->insertCityOngkirDetail($id, $val, $ongkir);
				}
				# code...
			}
		}else{
			City_ongkir_detail::where('city_ongkir_id', $id)->delete();
		}

		
		// $this->increase_version();
		
		Alert::success('Successfully add new '.$this->title);
		return redirect()->to($this->data['path']);
	}

	public function show($id){
		$this->model 					= $this->model->find($id);
		$this->data['title'] 			= "View Product ".$this->model->product_name;
		$this->data['unit']  			= $this->unit;
		$this->data['data']  			= $this->model;
		return $this->render_view('pages.product.view');
	}

	public function ext($action){
		return $this->$action();
	}

	public function updateflag(){
		// dd('bulkupda');
		return $this->buildupdateflag();
	}

	public function bulkupdate(){
		return $this->buildbulkedit();
	}

	public function export(){
		return $this->build_export_cus();
	}

	public function insertCityOngkirDetail($city_ongkir_id, $city_id, $ongkir){
		$arr[]			= [
			'city_ongkir_id' 	=> $city_ongkir_id,
			'city_id' 	 		=> $city_id,
			'ongkir' 	 		=> $ongkir,
			'upd_by'	 		=> auth()->guard($this->guard)->user()->id,
			'created_at' 		=> Carbon::now()
		];

		if(count($arr) > 0){
			// dd($temp_product);
			City_ongkir_detail::insert($arr);
		}
	} 
}
