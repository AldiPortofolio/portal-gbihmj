<?php namespace digipos\Http\Controllers\Admin;
use Illuminate\Http\request;
use File;
use Validator;
use Carbon\Carbon;

use digipos\models\Member;
use digipos\models\Msmerchant;
use digipos\models\Promo;
use digipos\models\User;

use digipos\Libraries\Alert;

class PromoController extends KyubiController {

	public function __construct()
	{
		parent::__construct();
		$this->middleware($this->auth_guard); 
		$this->middleware($this->role_guard);
		$this->model 		 	= new Promo;
		$this->primary_field	= 'id';
		$this->title			= trans('general.promo');
		$this->root_link		= 'promo';
		$this->bulk_action 		= true;
		$this->bulk_action_data = [2]; 
		$this->image_path 		= 'components/admin/image/promo/';
		$this->data['image_path'] = 'components/admin/image/promo/';

		// Validator::extend("uniques", function($attribute, $value, $parameters) {
		// 	$value = explode("; ", $value);
	 //        $rules = [
	 //            'voucher_code' => 'required|unique:voucher_member',
	 //        ];
	 //        foreach ($value as $voucher_code) {
	 //            $data = [
	 //                'voucher_code' => $voucher_code
	 //            ];
	 //            $validator = Validator::make($data, $rules);
	 //            if ($validator->fails()) {
	 //                return false;
	 //            }
	 //        }
	 //        return true;
	 //    }, 'Voucher code must unique');

	    $this->data['promo_type'] = ['p' => 'Promo', 'v' => 'Voucher'];
	    $this->data['discount_type'] = [ 'p' => 'Percent', 'v' => 'Value'];
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		$this->field = [
			[
				'name' 		=> 'promo_name',
				'label' 	=> 'Promo Name',
				'sorting' 	=> 'y',
				'search' 	=> 'text'
			],[
				'name' 		=> 'start_date',
				'label' 	=> 'Start Date',
				'sorting' 	=> 'y',
				'type' 		=> 'text'
			],[
				'name' 		=> 'end_date',
				'label' 	=> 'End Date',
				'sorting' 	=> 'y',
				'type' 		=> 'text'
			],[
				'name' 		=> 'status',
				'label' 	=> 'Status',
				'sorting' 	=> 'y',
				'search' => 'select',
				'search_data' => ['y' => 'Active', 'n' => 'Not-active'],
				'type' => 'check'
			]
		];
		// $store_id 			= json_decode(auth()->guard($this->guard)->user()->store_id);
		// $merchant_id 		= auth()->guard($this->guard)->user()->merchant_id;
		// $my_store 			= $this->myStore();
		
		// if(is_array($store_id)){
		// 	$catalog_store 	= Voucher_store::whereIn('store_id',$store_id)->groupBy('voucher_id')->pluck('voucher_id')->toArray();
		// 	$this->model 	= $this->model->whereIn('id',$catalog_store);
		// }else if($store_id == 1){
		// 	$this->model 	= $this->model->where('merchant_id',$merchant_id);
		// }

		// if(in_array(auth()->guard($this->guard)->user()->store_id,["0","1"])){
			
		// }else{
		// 	$catalog_store 	= Voucher_store::whereIn('store_id',$my_store)->groupBy('voucher_id')->pluck('voucher_id')->toArray();
		// 	$this->model 	= $this->model->whereIn('id',$catalog_store);
		// }

		return $this->build('index');
	}

	public function create(){
		$this->data['title'] 			= 'Add New '.$this->title;
		$this->data['user'] 			= User::where([['user_access_id', 3],['status','y']])->get();

		return $this->render_view('pages.promo.create');
	}

	public function store(request $request){
		// $this->validate($request,[
		// 	'promo_name' 			=> 'required|unique:product,product_name',
		// 	'price' 		=> 'required',
  //       ]);


		$this->model->promo_name 		= $request->promo_name;
		$this->model->description 		= $request->description;
		$this->model->start_date 		= Carbon::parse($request->start_date)->format('Y-m-d');
		$this->model->end_date 			= Carbon::parse($request->end_date)->format('Y-m-d');
		$this->model->status 			= $request->status;		
		$this->model->discount_type 	= $request->discount_type;
		$this->model->discount_value 	= $request->discount_value;
		$this->model->user_id 			= $request->agent;
		
		$this->model->upd_by 			= auth()->guard($this->guard)->user()->id;
		// $this->model->created_at 		= Carbon::now();
 		
 		// $this->model->voucher_code = '';
		// if($request->promo_type == 'Voucher'){//promo type voucher
		// 	$this->validate($request,[
		// 		'voucher_code' 			=> 'required',
	 //        ]);

		// 	// $explode_vc = explode("; ", $request->voucher_code);
		// 	// $unique_vc = array_unique($explode_vc);
		// 	// $unique_vc = array_values($unique_vc);
		// 	// $this->model->voucher_code = json_encode($unique_vc);
		// 	$this->model->voucher_code = $request->voucher_code;
		// }
		$this->model->voucher_code = $request->voucher_code;

		// dd($this->model);
		$this->model->save();
		return redirect($this->data['path']);
	}

	public function show(request $request,$id){
		$this->data['voucher'] 			= Voucher::find($id);
		$this->data['voucher_store'] 	= $this->data['voucher']->voucher_store->pluck('store_id')->toArray();
		$this->data['store']			= Store::where('merchant_id',auth()->guard($this->guard)->user()->merchant_id)->get();
		$this->data['title'] 			= 'View '.$this->title.' '.$this->data['voucher']->voucher_name;
		// dd($this->data['voucher']);
		return $this->render_view('pages.voucher.view');
	}

	public function edit(request $request,$id){
		$this->data['data'] 			= Promo::find($id);
		$this->data['user'] 			= User::where([['user_access_id', 3],['status','y']])->get();

		$this->data['voucher_code'] 	= '';
		
		$this->data['title'] 			= 'Edit '.$this->title.' '.$this->data['data']->promo_name;
		return $this->render_view('pages.promo.edit');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(request $request,$id){
		$this->model 					= $this->model->find($id);
		$this->model->promo_name 		= $request->promo_name;
		$this->model->description 		= $request->description;
		$this->model->start_date 		= Carbon::parse($request->start_date)->format('Y-m-d');
		$this->model->end_date 			= Carbon::parse($request->end_date)->format('Y-m-d');
		$this->model->status 			= $request->status;		
		$this->model->discount_type 	= $request->discount_type;
		$this->model->discount_value 	= $request->discount_value;
		$this->model->user_id 			= $request->agent;
		
		$this->model->upd_by 			= auth()->guard($this->guard)->user()->id;
		// $this->model->created_at 		= Carbon::now();
 		
 	// 	$this->model->voucher_code = '';
		// if($request->promo_type == 'Voucher'){//promo type voucher
		// 	$this->validate($request,[
		// 		'voucher_code' 			=> 'required',
	 //        ]);

		// 	// $explode_vc = explode("; ", $request->voucher_code);
		// 	// $unique_vc = array_unique($explode_vc);
		// 	// $unique_vc = array_values($unique_vc);
		// 	// $this->model->voucher_code = json_encode($unique_vc);
		// 	$this->model->voucher_code = $request->voucher_code;
		// }
		$this->model->voucher_code = $request->voucher_code;
		// dd($this->model);
		Alert::success('Successfully update '.$this->title);
		$this->model->save();
		return redirect($this->data['path']);
	}

	public function ext(request $request,$action){
		return $this->$action($request);
	}

	public function updateflag(){
		return $this->buildupdateflag();
	}

	public function bulkupdate(){
		return $this->buildbulkedit();
	}

	public function rules($request){
		$condition 	= [
			'voucher_name'			=> 'required',
			'start_date'			=> 'required',
			'end_date'				=> 'required',
			'status'				=> 'required',
		];
		if($request->publish == 'y'){
			$condition['publish']				= 'required';
			//$condition['max_use']				= 'required';
			$condition['discount_type']			= 'required';
			$condition['discount_value']		= 'required';
			$condition['voucher_member_type']	= 'required';
			$condition['store_allowed']			= 'required';
			if($request->voucher_member_type == 1){
				// $condition['voucher_code']	= 'required';
			}else if($request->voucher_member_type == 2){
			// $condition['voucher_code']	= 'required';
				$condition['total_voucher']	= 'required';
			}

			if($request->generate_voucher_code == 'n'){
				$condition['voucher_code']	= 'required|uniques';
			}

			if($request->redeem_flag == 'y'){
				$condition['redeem_point']	= 'required';
			}
		}
		$this->validate($request,$condition);
	}

	public function export(){
		$my_store						= json_decode(auth()->guard($this->guard)->user()->store_id);
		if(in_array(auth()->guard($this->guard)->user()->store_id,["0","1"])){
			$catalog_store = '';
		}else{
			$catalog_store 	= Voucher_store::whereIn('store_id',$my_store)->groupBy('voucher_id')->pluck('voucher_id')->toArray();
		}

		return $this->build_export($catalog_store);
	}
}
