<?php namespace digipos\Http\Controllers\Admin;

use digipos\models\Config;
use digipos\models\Church;
use digipos\models\Church_images;
use digipos\models\Room;
use digipos\models\Event;

use Validator;
use Auth;
use Hash;
use DB;
use digipos\Libraries\Alert;
use Illuminate\Http\Request;
use digipos\Libraries\Email;
use Carbon\Carbon;
use File;

class ChurchController extends KyubiController {

	public function __construct()
	{
		parent::__construct();
		$this->middleware($this->auth_guard);
		$this->middleware($this->role_guard);
		$this->title 			= "Church";
		$this->data['title']	= $this->title;
		$this->root_link 		= "manage-church";
		$this->model 			= new Church;

		$this->bulk_action			= true;
		$this->bulk_action_data 	= [2];
		$this->image_path 			= 'components/both/images/church/';
		$this->data['image_path'] 	= $this->image_path;
		$this->image_path2 			= 'components/both/images/web/';
		$this->data['image_path2'] 	= $this->image_path2;

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
		$this->field = [
			[
				'name' 		=> 'name',
				'label' 	=> 'Church Name',
				'sorting' 	=> 'y',
				'search' 	=> 'text'
			],
			[
				'name' 		=> 'address',
				'label' 	=> 'Address',
				'sorting' 	=> 'y',
				'search' 	=> 'text'
			],
			[
				'name' 		=> 'status',
				'label' 	=> 'Status',
				'type' 		=> 'check',
				'data' 		=> ['y' => 'Active','n' => 'Not Active'],
				'tab' 		=> 'general'
			]
		];

		// $this->model = $this->model->join('modem_status', 'modem_status.id', 'modem.modem_status_id')->select('modem.*', 'modem_status.modem_status_name');


		// $this->model = $this->model->select(DB::raw('te_category_product.*, (SELECT t.category_product_name FROM te_category_product t WHERE t.id=te_category_product.parent_id) AS parent_name'));
		return $this->build('index');
	}

	public function field_create(){
		$field = [
			[
				'name' => 'name',
				'label' => 'Church Name',
				'type' => 'text',
				'attribute' => 'required',
				'validation' => 'required',
				'tab' => 'general',
			],
			[
				'name' => 'address',
				'label' => 'Address',
				'type' => 'text',
				'attribute' => 'required',
				'validation' => 'required',
				'tab' => 'general',
			],
			[
				'name' => 'province',
				'label' => 'Provinsi',
				'type' => 'text',
				'attribute' => 'required',
				'validation' => 'required',
				'tab' => 'general',
			],
			[
				'name' => 'city',
				'label' => 'City',
				'type' => 'text',
				'attribute' => 'required',
				'validation' => 'required',
				'tab' => 'general',
			],
			[
				'name' => 'region_district',
				'label' => 'Region District',
				'type' => 'text',
				'attribute' => 'required',
				'validation' => 'required',
				'tab' => 'general',
			],
			[
				'name' => 'latitude',
				'label' => 'Latitude',
				'type' => 'number',
				'attribute' => 'required step=.0000000001',
				'validation' => 'required',
				'tab' => 'general',
			],
			[
				'name' => 'longitude',
				'label' => 'Longitude',
				'type' => 'number',
				'attribute' => 'required step=.0000000001',
				'validation' => 'required',
				'tab' => 'general',
			]
		];
		return $field;
	}

	public function field_edit(){
		$field = [
			[
				'name' => 'name',
				'label' => 'Church Name',
				'type' => 'text',
				'attribute' => 'required',
				'validation' => 'required',
				'tab' => 'general',
			],
			[
				'name' => 'address',
				'label' => 'Address',
				'type' => 'text',
				'attribute' => 'required',
				'validation' => 'required',
				'tab' => 'general',
			],
			[
				'name' => 'province',
				'label' => 'Provinsi',
				'type' => 'text',
				'attribute' => 'required',
				'validation' => 'required',
				'tab' => 'general',
			],
			[
				'name' => 'city',
				'label' => 'City',
				'type' => 'text',
				'attribute' => 'required',
				'validation' => 'required',
				'tab' => 'general',
			],
			[
				'name' => 'region_district',
				'label' => 'Region District',
				'type' => 'text',
				'attribute' => 'required',
				'validation' => 'required',
				'tab' => 'general',
			],
			[
				'name' => 'latitude',
				'label' => 'Latitude',
				'type' => 'number',
				'attribute' => 'required step=.0000000001',
				'validation' => 'required',
				'tab' => 'general',
			],
			[
				'name' => 'longitude',
				'label' => 'Longitude',
				'type' => 'number',
				'attribute' => 'required step=.0000000001',
				'validation' => 'required',
				'tab' => 'general',
			]
		];
		return $field;
	}

	public function create(){
		
		$this->data['title'] 						= "Create Church";
		// $this->data['category_modem']  			= Category_modem::where('status', 'y')->get();
		// $this->data['modem_status']				= Modem_status::get();
		// $this->data['parent']					= Category_product::where('parent_id', null)->get();

		return $this->render_view('pages.church.create');
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

		$this->model->name							= $request->name;
		$this->model->address						= $request->address;
		$this->model->province						= $request->province;
		$this->model->city							= $request->city;
		$this->model->region_district 				= $request->region_district;
		$this->model->latitude 						= $request->latitude;
		$this->model->longitude 					= $request->longitude;
		$this->model->created_by					= auth()->guard($this->guard)->user()->id;
		$this->model->created_at					= date('Y-m-d H:i:s');

		// dd($this->model->toArray());
		$this->model->save();

	
		Alert::success('Successfully add Church');
		return redirect()->to($this->data['path']);
	}

	public function edit($id){
		$this->model 					= $this->model->find($id);
		// dd($this->model->toArray());
		$this->data['title'] 			= "Edit Church ".$this->model->name;
		$this->data['data']  			= $this->model;
		$this->data['church_images']  	= Church_images::where([['church_id', $id],['deleted_at', null]])->select('church_images.*')->get();
		
		return $this->render_view('pages.church.edit');
		// $this->field = $this->field_edit();
		// return $this->build('edit');
	}

	public function update(Request $request, $id){
		// $this->validate($request,[
		// 	'name' 		=> 'required|unique:modem,modem_name,'.$id,
		// ],[
        //     'name.required' => 'Category Modem Name is Required.',
        //     'name.unique' 	=> 'Category Modem Name has already been taken.',
        // ]);

		$this->model 								= $this->model->find($id);
		// dd($this->model);
		$this->model->name							= $request->name;
		$this->model->address						= $request->address;
		$this->model->province						= $request->province;
		$this->model->city							= $request->city;
		$this->model->region_district 				= $request->region_district;
		$this->model->latitude 						= $request->latitude;
		$this->model->longitude 					= $request->longitude;

		$this->model->upd_by 						= auth()->guard($this->guard)->user()->id;

		// dd($this->model);
		$this->model->save();
		
		Alert::success('Successfully edit Product');
		return redirect()->to($this->data['path']);
	}

	public function show($id){
		$this->model 					= $this->model->find($id);
		$this->data['title'] 			= "View Church ".$this->model->name;
		return $this->render_view('pages.church.view');
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

	public function upload_image(Request $request){
		$church_id = $request->church_id;

		if ($request->hasFile('image')){
        	// File::delete($this->image_path.$this->model->image);
			$image_path = $this->data['image_path'];
			$data = [
						'name' => 'image',
						'file_opt' => ['path' => $image_path.'/']
					];
			$image = $this->build_image($data);
			
			// echo $image;

			$church_images = new Church_images;
			$church_images->image = $image;
			
			$church_images->church_id = $church_id;
			$church_images->save();

			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Success upload image', 'data' => $church_images);
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Image not found !');
		}

		return json_encode($res);
	}

	// public function edit_status_church_image(Request $request){
	// 	$id = $request->id;
	// 	$status = $request->status;

	// 	$church_images = Church_images::find($id);
	// 	$church_images->status = $status;
	// 	$church_images->save();

	// 	$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Success update data', 'data' => $church_images);
		
	// 	return json_encode($res);
	// }

	// public function delete_church_image(Request $request){
	// 	$id = $request->id;

	// 	$church_images = Church_images::find($id);
	// 	$church_images->delete();

	// 	$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Success delete data');
		
	// 	return json_encode($res);
	// }
}
