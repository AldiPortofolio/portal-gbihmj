<?php namespace digipos\Http\Controllers\Admin;

use digipos\models\Authmenu;
use digipos\models\Useraccess;
use digipos\models\User;
use digipos\models\Province;
use digipos\models\Config;
use digipos\models\City;

// use Request;
use Validator;
use Auth;
use Hash;
use DB;
use digipos\Libraries\Alert;
use Illuminate\Http\Request;
use digipos\Libraries\Email;
use Carbon\Carbon;
use File;


class CityController extends KyubiController{
	public function __construct(){
		parent::__construct();
		$this->middleware($this->auth_guard);
		$this->middleware($this->role_guard);
		$this->primary_field	= 'id';
		$this->title			= 'City';
		$this->root_link		= 'city';
		$this->model			= new City;
		$this->user				= new User;
		
		// $this->delete_relation	= ['store'];
		$this->bulk_action		= true;
		// $this->bulk_action_data = [1];
		$this->image_path 			= 'components/both/image/city/';
		$this->data['image_path'] 	= $this->image_path;
		$this->image_path2 			= 'components/both/images/web/';
		$this->data['image_path2'] 	= $this->image_path2;


		$this->meta_title = Config::where('name', 'web_title')->first();
        $this->meta_description = Config::where('name', 'web_description')->first();
        $this->meta_keyword = Config::where('name', 'web_keywords')->first();
	}

	public function index(){
		$this->field = [
			[
				'name' 		=> 'name',
				'label' 	=> 'City Name',
				'sorting' 	=> 'y',
				'search' 	=> 'text'
			],
			// [
			// 	'name' 		=> 'status',
			// 	'label' 	=> 'Status',
			// 	'sorting' 	=> 'y',
			// 	'search' => 'select',
			// 	'search_data' => ['y' => 'Active', 'n' => 'Not-active'],
			// 	'type' => 'check'
			// ]
		];

		return $this->build('index');
	}

	public function field_create(){
		// $field = [
		// 	[
		// 		'name' => 'service_name',
		// 		'label' => 'Service Name',
		// 		'type' => 'text',
		// 		'attribute' => 'required',
		// 		'validation' => 'required',
		// 		'tab' => 'general',
		// 	]
		// ];
		// return $field;
	}

	public function field_edit(){
		$field = [
			[
				'name' => 'name',
				'label' => 'Province Name',
				'type' => 'text',
				'attribute' => 'required',
				'validation' => 'required',
				'tab' => 'general',
			]
		];
		return $field;
	}

	public function create(){
		
		// $this->data['title'] = "Create Category Mitra";

		// $this->field = $this->field_create();
		// return $this->build('create');

		// return $this->render_view('pages.category-mitra.create');
	}

	public function store(Request $request){
		// $this->validate($request,[
		// 		'category_name' 	=> 'required|unique:category,category_name'
		// ]);

		// $this->model->category_name			= $request->category_name;

		// if($request->input('remove-single-image-image') == 'y'){
		// 	if($this->model->category_image != NULL){
		// 		File::delete($this->image_path.$this->model->category_image);
		// 		$this->model->category_image = '';
		// 	}
		// }

		// if ($request->hasFile('image')){
  //       	// File::delete($path.$user->images);
		// 	$data = [
		// 				'name' => 'image',
		// 				'file_opt' => ['path' => $this->image_path]
		// 			];
		// 	$image = $this->build_image($data);
		// 	$this->model->category_image = $image;
		// }	
		// $this->model->status 				= 'y';
		// // $this->model->meta_title 			= $request->meta_title != NULL ? $request->meta_title : $this->meta_title->value;
		// // $this->model->meta_description 		= $request->meta_title != NULL ? $request->meta_title : $this->meta_description->value;

		// // $this->model->meta_keyword 			= $request->meta_title != NULL ? $request->meta_title : $this->meta_keyword->value;

		// $this->model->upd_by 				= auth()->guard($this->guard)->user()->id;
		// // dd($this->model);
		// $this->model->save();

		// Alert::success('Successfully add Category Mitra');
		// return redirect()->to($this->data['path']);
	}

	public function show($id){
		// $this->data['data1'] = $this->model->find($id);
		// $this->data['title'] = "View Category Mitra ".$this->data['data1']->category_name;

		// // $this->model = $this->model->find($id);
		// // $this->field = $this->field_edit();
		// return $this->render_view('pages.category-mitra.view');
	}

	public function edit($id){
		$this->data['data'] 	= $this->model->find($id);
		$this->data['title'] 	= "Edit City ".$this->data['data']->city_ongkir_name;
		$this->data['province'] = Province::get();
		
		// $this->model = $this->model->find($id);
		// $this->field = $this->field_edit();
		// return $this->build('edit');
		return $this->render_view('pages.city.edit');
	}

	public function update(Request $request, $id){
		$this->validate($request,[
				'name' 	=> 'required|unique:city,name,'.$id
		],[
            'name.required' => 'City Name is Required.',
            'name.unique' 	=> 'City Name has already been taken.',
        ]);

		$this->model				= City::find($id);
		$this->model->name 			= $request->name;	
		$this->model->province_id 	= $request->province;
		// dd($this->model);
		$this->model->save();

		Alert::success('Successfully edit City');
		return redirect()->to($this->data['path']);
	}							

	public function destroy(Request $request){
		// return $this->build('delete');

		$id = $request->id;
		$uc = $this->model->find($id);
		
		$uc->delete();
		Alert::success('Cinema has been deleted');
		return redirect()->back();
	}

	public function ext($action){
		return $this->$action();
	}

	public function updateflag(){
		return $this->buildupdateflag();
	}

	public function bulkupdate(){
		return $this->buildbulkedit();
	}

	public function get_user_access(){
		$q = $this->build_array(Useraccess::where('id','>',1)->get(),'id','access_name');
		return $q;
	}

	public function export(){
		return $this->build_export();
	}

	public function sorting(){
		$this->field = [
			[
				'name' 		=> 'merchant_name',
				'label' 	=> 'Name',
				'sorting' 	=> 'y',
				'search' 	=> 'text',
				'type' 		=> 'text'
			],
			[
				'name' 		=> 'status',
				'label' 	=> 'Status',
				'sorting' 	=> 'y',
				'search' 	=> 'select',
				'search_data' => ['y' => 'Active', 'n' => 'Not-active'],
				'type' => 'check'
			]
		];
		$this->model = $this->model->where('id','>','1');
		return $this->build('sorting');
	}

	public function dosorting(){
		return $this->dosorting();
	}

	public function get_merchant_category($par){
		// $data = $this->merchant_category
		// 	->join('msmerchant','msmerchant.id_merchant_category','=','merchant_category.id')
		// 	->join('city','city.id','=','msmerchant.id_city')
		// 	->join('merchant_log','merchant_log.merchant_id','=','msmerchant.id')
		// 	->where('merchant_log.created_at','>=',DB::raw('DATE_SUB(CURDATE(), INTERVAL '.$par.' DAY)'))
		// 	->where('merchant_log.created_at','<=',DB::raw('NOW()')); 
		// $merchant_category 	= $data
		// 					->select('merchant_category.*', 'msmerchant.merchant_name', 'msmerchant.id_merchant_category','city.name as city_name','merchant_log.ip_address', 'merchant_log.created_at', DB::raw('COUNT(*) as visits'))
		// 					->groupBy('msmerchant.id_merchant_category')
		// 					->get();
		// return json_encode($merchant_category);
	}

	public function get_merchant_location($par){
		// $merchant_location 	= $this->merchant_category
		// 					->select('msmerchant.merchant_name', 'msmerchant.id_merchant_category', 'msmerchant.id_city','city.name as city_name','merchant_log.ip_address', 'merchant_log.created_at', DB::raw('COUNT(*) as visits'))
		// 					->join('msmerchant','msmerchant.id_merchant_category','=','merchant_category.id')
		// 					->join('city','city.id','=','msmerchant.id_city')
		// 					->join('merchant_log','merchant_log.merchant_id','=','msmerchant.id')
		// 					->where('merchant_log.created_at','>=',DB::raw('DATE_SUB(CURDATE(), INTERVAL '.$par.' DAY)'))
		// 					->where('merchant_log.created_at','<=',DB::raw('NOW()'))
		// 					->orderBy('msmerchant.id_city')
		// 					->groupBy('city.id', 'msmerchant.id_city')
		// 					->get();
		// return json_encode($merchant_location);
	}
}
?>