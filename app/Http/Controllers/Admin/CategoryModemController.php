<?php namespace digipos\Http\Controllers\Admin;

use digipos\models\Config;
use digipos\models\Product;
use digipos\models\Outlet;
use digipos\models\Province;
use digipos\models\Category_modem;

use Validator;
use Auth;
use Hash;
use DB;
use digipos\Libraries\Alert;
use Illuminate\Http\Request;
use digipos\Libraries\Email;
use Carbon\Carbon;
use File;

class CategoryModemController extends KyubiController {

	public function __construct()
	{
		parent::__construct();
		$this->middleware($this->auth_guard); 
		$this->middleware($this->role_guard);
		$this->title 			= "Category Modem";
		$this->data['title']	= $this->title;
		$this->root_link 		= "category-modem";
		$this->model 			= new Category_modem;

		$this->bulk_action			= true;
		$this->bulk_action_data 	= [1];
		$this->image_path 			= 'components/both/images/category_modem/';
		$this->data['image_path'] 	= $this->image_path;
		// $this->image_path2 			= 'components/both/images/web/';
		// $this->data['image_path2'] 	= $this->image_path2;
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
				'name' 		=> 'category_modem_name',
				'label' 	=> 'Category Name',
				'sorting' 	=> 'y',
				'search' 	=> 'text'
			],
			[
				'name' 		=> 'status',
				'label' 	=> 'Status',
				'sorting' 	=> 'y',
				'search' => 'select',
				'search_data' => ['y' => 'Active', 'n' => 'Not-active'],
				'type' => 'check'
			]
		];

		// $this->model = $this->model->join('order_status', 'order_status.id', 'orderhd.order_status')->where('type_order', 'not like', '%post%')->select('orderhd.*', 'order_status.desc');


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
		
		$this->data['title'] 					= "Create Category Modem";
		// $this->data['unit']  					= $this->unit;
		// $this->data['province']					= Province::get();
		// $this->data['parent']					= Category_product::where('parent_id', null)->get();

		return $this->render_view('pages.category-modem.create');
		// $this->field = $this->field_create();
		// return $this->build('create');
	}

	public function store(Request $request){
		$this->validate($request,[
			'name' 			=> 'required|unique:category_modem,category_modem_name',
		],
		[
            'name.required' => 'Category Modem Name is Required.',
            'name.unique' 	=> 'Category Modem Name has already been taken.',
        ]
    	);

		$rent_price = $this->decode_rupiah($request->rent_price);
    	if(!is_numeric($rent_price)){
			Alert::fail('Rent Price must be number');
			return redirect()->to($this->data['path'].'/create')->withInput($request->input());
		}

		$deposit_price = $this->decode_rupiah($request->deposit_price);
    	if(!is_numeric($deposit_price)){
			Alert::fail('Deposit Price must be number');
			return redirect()->to($this->data['path'].'/create')->withInput($request->input());
		}

		$penalty_rent_price_per_day = $this->decode_rupiah($request->penalty_rent_price_per_day);
    	if(!is_numeric($penalty_rent_price_per_day)){
			Alert::fail('Penalty rent price per day must be number');
			return redirect()->to($this->data['path'].'/create')->withInput($request->input());
		}

		$this->model->category_modem_name			= $request->name;
		$this->model->description					= $request->description;
		$this->model->rent_price					= $rent_price;
		$this->model->deposit_price					= $deposit_price;
		$this->model->penalty_rent_per_day 			= $penalty_rent_price_per_day;
		$this->model->status 						= 'y';
		$this->model->upd_by 						= auth()->guard($this->guard)->user()->id;

		// dd($this->model);
		$this->model->save();

		// $this->increase_version();

		Alert::success('Successfully add new Category Modem');
		return redirect()->to($this->data['path']);
	}

	public function edit($id){
		$this->model 					= $this->model->find($id);
		$this->data['title'] 			= "Edit Category Modem ".$this->model->category_modem_name;
		// $this->data['unit']  			= $this->unit;
		$this->data['data']  			= $this->model;
		// $this->data['province']			= Province::get();
		// $this->data['parent']			= Category_product::where('parent_id', null)->get();
		// $this->data['category_product']	= Category_product::where('status', 'y')->get();

		return $this->render_view('pages.category-modem.edit');
	}

	public function update(Request $request, $id){
		$this->validate($request,[
			'name' 		=> 'required|unique:category_modem,category_modem_name,'.$id,
		],[
            'name.required' => 'Category Modem Name is Required.',
            'name.unique' 	=> 'Category Modem Name has already been taken.',
        ]);

        $rent_price = $this->decode_rupiah($request->rent_price);
    	if(!is_numeric($rent_price)){
			Alert::fail('Rent Price must be number');
			return redirect()->to($this->data['path'].'/create')->withInput($request->input());
		}

		$deposit_price = $this->decode_rupiah($request->deposit_price);
    	if(!is_numeric($deposit_price)){
			Alert::fail('Deposit Price must be number');
			return redirect()->to($this->data['path'].'/create')->withInput($request->input());
		}

		$this->model 						= $this->model->find($id);
		$this->model->category_modem_name			= $request->name;
		$this->model->description					= $request->description;
		$this->model->rent_price					= $rent_price;
		$this->model->deposit_price					= $deposit_price;
		$this->model->status 						= 'y';
		$this->model->upd_by 						= auth()->guard($this->guard)->user()->id;

		// dd($this->model);
		$this->model->save();
		// $this->increase_version();
		
		Alert::success('Successfully add new Product');
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
