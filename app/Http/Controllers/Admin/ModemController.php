<?php namespace digipos\Http\Controllers\Admin;

use digipos\models\Config;
use digipos\models\Category_modem;
use digipos\models\Modem;
use digipos\models\Modem_status;
use digipos\models\Modem_log;

use Validator;
use Auth;
use Hash;
use DB;
use digipos\Libraries\Alert;
use Illuminate\Http\Request;
use digipos\Libraries\Email;
use Carbon\Carbon;
use File;

class ModemController extends KyubiController {

	public function __construct()
	{
		parent::__construct();
		$this->middleware($this->auth_guard); 
		$this->middleware($this->role_guard);
		$this->title 			= "Modem";
		$this->data['title']	= $this->title;
		$this->root_link 		= "manage-modem";
		$this->model 			= new Modem;

		$this->bulk_action			= true; 
		$this->bulk_action_data 	= [1];
		$this->image_path 			= 'components/both/images/modem/';
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
				'name' 		=> 'modem_name',
				'label' 	=> 'Modem Name',
				'sorting' 	=> 'y',
				'search' 	=> 'text'
			],
			[
				'name' 		=> 'serial_no',
				'label' 	=> 'Serial No.',
				'sorting' 	=> 'y',
				'search' 	=> 'text'
			],
			[
				'name' 		=> 'modem_status_name',
				'label' 	=> 'Modem Status',
				// 'sorting' 	=> 'y',
				// 'search' => 'select',
				// 'search_data' => ['1' => 'Active', '2' => 'Not-active'],
				'type' => 'text'
			]
		];

		$this->model = $this->model->join('modem_status', 'modem_status.id', 'modem.modem_status_id')->select('modem.*', 'modem_status.modem_status_name');


		// $this->model = $this->model->select(DB::raw('te_category_product.*, (SELECT t.category_product_name FROM te_category_product t WHERE t.id=te_category_product.parent_id) AS parent_name'));
		// dd($this->model->get());
		return $this->build('index');
	}

	public function field_create(){
		$field = [
			[
				'name' => 'category_modem_name',
				'label' => 'Category Modem Name',
				'type' => 'text',
				'attribute' => 'required',
				'validation' => 'required',
				'tab' => 'general',
			],
			[
				'name' => 'rent_price',
				'label' => 'Rent Price',
				'type' => 'text',
				'attribute' => 'required',
				'validation' => 'required',
				'tab' => 'general',
			],
			[
				'name' => 'decimal_price',
				'label' => 'Decimal Price',
				'type' => 'text',
				'attribute' => 'required',
				'validation' => 'required',
				'tab' => 'general',
			],
			[
				'name' => 'description',
				'label' => 'Description',
				'type' => 'textarea',
				'attribute' => 'required',
				'validation' => 'required',
				'tab' => 'general',
			]
		];
		return $field;
	}

	public function create(){
		
		$this->data['title'] 					= "Create Modem";
		$this->data['category_modem']  			= Category_modem::where('status', 'y')->get();
		$this->data['modem_status']				= Modem_status::get();
		// $this->data['parent']					= Category_product::where('parent_id', null)->get();

		return $this->render_view('pages.modem.create');
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

		$last_id 							= $this->model->orderBy('id', 'desc')->first();
		$curr_id 							= ($last_id ? $last_id->id + 1 : 1);
		$this->model->modem_name					= $request->name;
		$this->model->category_modem_id				= $request->category_modem;
		$this->model->description					= $request->description;
		$this->model->serial_no						= $request->serial_no;
		$this->model->modem_status_id 				= $request->modem_status;
		$this->model->date_of_entry 				= date_format(date_create($request->date_of_entry),'Y-m-d');
		$this->model->weight_gram 					= $request->weight;
		$this->model->upd_by 						= auth()->guard($this->guard)->user()->id;
		// dd(date_create($request->activation_date));
		$this->model->activation_date 				= date_format(date_create($request->activation_date),'Y-m-d H:i:s');
		$this->model->deactivation_date 			= date_format(date_create($request->deactivation_date),'Y-m-d H:i:s');
		$this->model->imei 							= $request->imei;
		$this->model->password 						= $request->password;

		// dd($this->model->toArray());
		$this->model->save();

		if ($request->hasFile('image')){
        	// File::delete($path.$user->images);
			$data = [
						'name' => 'image',
						'file_opt' => ['path' => $this->image_path.$this->model->id.'/']
					];
			$image = $this->build_image($data);
			$this->model->image = $image;
		}
		$this->create_modem_log($this->model->id, 'insert', $this->model->toArray());
		// dd($this->model);
		$this->model->save();

		// $this->increase_version();

		Alert::success('Successfully add Modem');
		return redirect()->to($this->data['path']);
	}

	public function edit($id){
		$this->model 					= $this->model->join('modem_status', 'modem_status.id', 'modem.modem_status_id')->select('modem.*', 'modem_status.modem_status_name')->find($id);
		// dd($this->model);
		$this->data['title'] 			= "Edit Modem ".$this->model->modem_name;
		$this->data['data']  			= $this->model;
		$this->data['category_modem']	= Category_modem::where('status', 'y')->get();
		$this->data['modem_status']		= Modem_status::get();
		$this->data['modem_log']  		= Modem_log::leftJoin('modem_status', 'modem_status.id', 'modem_log.modem_status_id')->leftJoin('category_modem', 'category_modem.id', 'modem_log.category_modem_id')->leftJoin('user', 'user.id', 'modem_log.upd_by')->where('modem_id', $id)->orderBy('id', 'DESC')->select('modem_log.*','modem_status.modem_status_name', 'category_modem.category_modem_name', 'user.username')->get();
		// dd($this->data['modem_log']);
		return $this->render_view('pages.modem.edit');
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
		$this->model->modem_name					= $request->name;
		$this->model->category_modem_id				= $request->category_modem;
		$this->model->description					= $request->description;
		$this->model->serial_no						= $request->serial_no;
		$this->model->modem_status_id 				= $request->modem_status;
		$this->model->date_of_entry 				= date_format(date_create($request->date_of_entry),'Y-m-d');
		$this->model->weight_gram 					= $request->weight;
		$this->model->activation_date 				= date_format(date_create($request->activation_date),'Y-m-d H:i:s');
		$this->model->deactivation_date 			= date_format(date_create($request->deactivation_date),'Y-m-d H:i:s');
		$this->model->imei 							= $request->imei;
		$this->model->password 						= $request->password;

		$this->model->upd_by 						= auth()->guard($this->guard)->user()->id;

		if($request->input('remove-single-image-image') == 'y'){
			if($this->model->image != NULL){
				// dd($this->image_path.$this->model->id.'/'.$this->model->image);
				File::delete($this->image_path.$this->model->id.'/'.$this->model->image);
				$this->model->image = '';
			}
		}

		if($request->hasFile('image')){
        	// File::delete($path.$user->images);
			$data = [
						'name' => 'image',
						'file_opt' => ['path' => $this->image_path.$this->model->id.'/']
					];
			$image = $this->build_image($data);
			$this->model->image = $image;
		}


		// dd($this->model);
		$this->model->save();

		if($request->complain){
			$this->model->complain = $request->complain;
		}

		$this->create_modem_log($id, 'update', $this->model->toArray());

		// $this->increase_version();
		
		Alert::success('Successfully edit Product');
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
}
