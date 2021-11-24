<?php namespace digipos\Http\Controllers\Admin;

use digipos\models\Config;
use digipos\models\User;
use digipos\models\Booking;

use Validator;
use Auth;
use Hash;
use DB;
use digipos\Libraries\Alert;
use Illuminate\Http\Request;
use digipos\Libraries\Email;
use Carbon\Carbon;
use File;
use DateTime;
use DatePeriod;
use DateInterval;
use PDF;

class BookingController extends KyubiController {

	public function __construct()
	{
		parent::__construct();
		$this->middleware($this->auth_guard); 
		$this->middleware($this->role_guard);
		$this->title 			= "Booking";
		$this->data['title']	= $this->title;
		$this->root_link 		= "manage-booking";
		$this->model 			= new Booking;

		$this->bulk_action			= false; 
		$this->bulk_action_data 	= [5];
		$this->invoice_pdf 			= true;
		$this->image_path 			= 'components/both/images/booking/';
		$this->data['image_path'] 	= $this->image_path;
		$this->image_path2 			= 'components/both/images/web/';
		$this->data['image_path2'] 	= $this->image_path2;

		$this->meta_title = Config::where('name', 'web_title')->first();
        $this->meta_description = Config::where('name', 'web_description')->first();
        $this->meta_keyword = Config::where('name', 'web_keywords')->first();

        $this->export_pdf = true;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		$this->field = [
			[
				'name' 		=> 'booking_code',
				'label' 	=> 'Booking Code',
				'sorting' 	=> 'y',
				'search' 	=> 'text'
			],
			[
				'name' 		=> 'name',
				'label' 	=> 'Name',
				'sorting' 	=> 'y',
				'search' 	=> 'text'
			],
			[
				'name' 		=> 'phone',
				'label' 	=> 'Phone',
				'sorting' 	=> 'y',
				'search' 	=> 'text'
			],
			[
				'name' 		=> 'booking_time',
				'label' 	=> 'Time',
				'sorting' 	=> 'y',
				'search' 	=> 'text'
			],
			[
				'name' 		=> 'title',
				'label' 	=> 'Event Title',
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

		$this->model = $this->model
			->join('user', 'user.id', 'booking.user_id')
			->join('event', 'event.id', 'booking.event_id')
			->select('booking.*', 'user.name', 'user.phone', 'event.title', 'booking.time as booking_time');
	
		return $this->build('index');
	}

	public function field_create(){
		$field = [
			
		];
		return $field;
	}

	public function create(){
		
	}

	public function store(Request $request){
		
	}

	public function edit($id){	
		$this->model 					= $this->model
											->join('user', 'user.id', 'booking.user_id')
											->join('event', 'event.id', 'booking.event_id')
											->select('booking.*', 'user.name', 'user.phone', 'event.title as event_title', 'booking.time as booking_time')
											->find($id);
		// dd($this->model->toArray());
		$this->data['title'] 			= "Edit Booking ".$this->model->booking_code;
		$this->data['data']  			= $this->model;
		$this->data['time']	=  array();
		return $this->render_view('pages.booking.edit');
	}

	public function update(Request $request, $id){
		$mode = 2;
		// $this->validate($request,[
		// 	'full_name' 		=> 'required:orderhd,full_name',
		// 	'email' 			=> 'required|email:orderhd,email',
		// ],[
        //     'full_name.required' => 'Full Name is Required.',
        //     'full_name.unique' 	=> 'Full Name has already been taken.',
        // ]);

		// // dd($request->modem);
        // if($request->country == NULL){
		// 	Alert::fail('Country must be selected !');
		// 	return redirect()->to($this->data['path'].'/'.$id.'/edit')->withInput($request->input());
		// }

		// // if($request->modem == NULL){
		// // 	Alert::fail('Modem must be selected !');
		// // 	return redirect()->to($this->data['path'].'/'.$id.'/edit')->withInput($request->input());
		// // }


		// $this->model 								= $this->model->find($id);
		// /*order*/
		// $this->model->order_status_id				= $request->order_status;
		// $this->model->order_no						= $request->order_no;
		// $arr_acountry = [];
		// // dd(count($request->country));
		// if(count($request->country) > 0){
		// 	foreach ($request->country as $key => $val) {
		// 		// var_dump($val);
		// 		$val2 = explode('-', $val);
		// 		array_push($arr_acountry, $val2[1]);
		// 	}
		// }
		// // dd($arr_acountry);
		// $this->model->country_id					= json_encode($arr_acountry);
		// $this->model->go_from_indonesia				= date_format(date_create($request->date_from),'Y-m-d');
		// $this->model->arrival_in_indonesia 			= date_format(date_create($request->date_to),'Y-m-d');
		// $this->model->qty_modem 					= $request->qty_modem;

		// if($mode == 1){
		// 	$this->model->delivery_city_id 			= null;
		// 	$this->model->delivery_province_id 		= null;
		// 	$this->model->delivery_address 			= null;
		// 	$this->model->delivery_post_code 		= null;
		// 	$this->model->delivery_area_id 			= null;

		// 	if($request->delivery_method == 2){
		// 		$this->model->delivery_city_id 			= $request->da2_city;
		// 		$this->model->delivery_province_id 		= $request->da2_province;
		// 		$this->model->delivery_address 			= $request->da2_address;
		// 		$this->model->delivery_post_code 		= $request->da2_kodepos;
		// 	}elseif ($request->delivery_method == 3) {
		// 		$this->model->delivery_area_id			= $request->delivery_area;
		// 	}

		// 	$this->model->return_city_id 			= null;
		// 	$this->model->return_province_id 		= null;
		// 	$this->model->return_address 			= null;
		// 	$this->model->return_post_code 			= null;
		// 	$this->model->return_area_id 			= null;

		// 	if($request->return_method == 2){
		// 		$this->model->return_city_id 			= $request->ra2_city;
		// 		$this->model->return_province_id 		= $request->ra2_province;
		// 		$this->model->return_address 			= $request->ra2_address;
		// 		$this->model->return_post_code 			= $request->ra2_kodepos;
		// 	}elseif ($request->return_method == 5) {
		// 		$this->model->return_area_id			= $request->return_area;
		// 	}
			
		// 	$this->model->delivery_method_id 			= $request->delivery_method;
		// 	$this->model->return_method_id 				= $request->return_method;
		// }

		// /*customer*/
		// $this->model->full_name 					= $request->full_name; 
		// $this->model->telephone 					= $request->full_name; 
		// $this->model->email 						= $request->email; 
		// /*customer*/
		// $this->model->bank_name 					= $request->bank_name; 
		// $this->model->bank_account_name 			= $request->bank_account_name; 
		// $this->model->bank_account_no 				= $request->bank_account_no; 
		// $this->model->note 							= $request->note; 

		// $this->model->upd_by 						= auth()->guard($this->guard)->user()->id;

		// if($request->order_status == 4 || $request->order_status == 5){
		// 	$this->model->total_refund = $request->total_refund;
		// }

		// // dd($request->input('remove-single-image-image'));
		// if($request->input('remove-single-image-image') == 'y'){
		// 	if($this->model->transfer_foto != NULL){
		// 		// dd($this->image_path.$this->model->id.'/'.$this->model->image);
		// 		File::delete($this->image_path.'/'.$this->model->transfer_foto);
		// 		$this->model->transfer_foto = '';
		// 	}
		// }

		// if ($request->hasFile('image')){
        // 	// File::delete($path.$user->images);
		// 	$data = [
		// 				'name' => 'image',
		// 				'file_opt' => ['path' => $this->image_path.'/']
		// 			];
		// 	$image = $this->build_image($data);
		// 	$this->model->transfer_foto = $image;
		// }

		// // dd($this->model);
		// $this->model->save();

		// // $arr_modem = [];
		// // if(count($request->modem)){
		// // 	foreach ($request->modem as $key => $val) {
		// // 		array_push($arr_modem, $val);
		// // 	}
		// // }

		// $this->insertOrderLog($this->model->id, $this->model->toArray(), $request->modem);

		// //function modem id
		// $date_from = $this->model->go_from_indonesia;
		// $date_to = $this->model->arrival_in_indonesia;
		// if(count($request->modem)){
		// 	$orderdt = Order_dt::where('orderhd_id',$this->model->id)->pluck('modem_id')->toArray();
		// 	// var_dump($orderdt);
		// 	// var_dump($request->modem);
		// 	//function compare array new or par 1 from old array or par 2
		// 	$arr_diff = $this->kyubi->array_different($request->modem, $orderdt);
		// 	// dd($arr_diff);

		// 	//if find diffrent array then insert to orderdt
		// 	// if(count($arr_diff) > 0){
		// 	// 	$weight = 0;
		// 	// 	$subtotal = 0;
		// 	// 	foreach ($arr_diff as $key => $v) {
		// 	// 		//insert orderdt value from $arr_diff
		// 	// 		$modem 		= Modem::join('category_modem', 'category_modem.id', 'modem.category_modem_id')->where('modem.id', $v)->select('modem.*', 'category_modem.*')->first();
					
		// 	// 		if($modem != null){
		// 	// 			$weight 	+= ((int)$modem->weight_gram / 1000);
		// 	// 			// dd($weight);
		// 	// 			$period = new DatePeriod(
		// 	// 			     new DateTime($date_from),
		// 	// 			     new DateInterval('P1D'),
		// 	// 			     new DateTime($date_to)
		// 	// 			);
		// 	// 			$date1 			= date_create($date_from);
		// 	// 			$date2 			= date_create($date_to);

		// 	// 			$diff 			= date_diff($date1,$date2);
		// 	// 			$interval_day 	= $diff->format("%a");
		// 	// 			// dd($interval_day);
		// 	// 			// echo '$modem->rent_price: '.$modem->rent_price.'<br>';
		// 	// 			// echo '$interval_day: '.$interval_day.'<br>';
		// 	// 			// echo '$modem->deposit_price: '.$modem->deposit_price.'<br>';
		// 	// 			$subtotal 		+=  ((int)$modem->rent_price * (int)$interval_day) + $modem->deposit_price; 
		// 	// 		}

		// 	// 		$this->model = $this->model->find($id);
		// 	// 		$this->model->total_weight_kg = $weight;
		// 	// 		$this->model->sub_total = $subtotal;
		// 	// 		// dd($this->model);
		// 	// 		$this->model->save();

		// 	// 		$order_dt = new Order_dt;
		// 	// 		$order_dt->orderhd_id 		= $this->model->id;
		// 	// 		$order_dt->modem_id 		= $v;
		// 	// 		$order_dt->upd_by 			= auth()->guard($this->guard)->user()->id;
		// 	// 		$order_dt->rent_price 		= $modem->rent_price;
		// 	// 		$order_dt->deposit_price 	= $modem->deposit_price;
		// 	// 		$order_dt->rent_day 		= $interval_day;
		// 	// 		$order_dt->subtotal 		= $subtotal;
		// 	// 		$order_dt->save();

		// 	// 		// $this->insertOrderdtLog($order_dt->id, $order_dt->toArray());
		// 	// 	}
		// 	// }

		// 	// if(count($orderdt) > count($request->modem)){
		// 	// 	$arr_diff2 = $this->kyubi->array_different($orderdt, $request->modem);
				
		// 	// 	if(count($arr_diff2) > 0){
		// 	// 		//delete order dt where modem_id and order_id equal with condition
		// 	// 		// foreach ($arr_diff2 as $v) {
		// 	// 			$delete 	= Order_dt::where('orderhd_id',$this->model->id)->whereIn('modem_id',$arr_diff2);	
		// 	// 			$delete->delete();
		// 	// 		// }
		// 	// 	}
		// 	// }
		// }

		// $this->increase_version();
		
		Alert::success('Successfully edit Order');
		return redirect()->to($this->data['path']);
	}

	public function show($id){
		$this->model 					= $this->model
		->join('user', 'user.id', 'booking.user_id')
		->join('event', 'event.id', 'booking.event_id')
		->select('booking.*', 'user.name', 'user.phone', 'user.email','event.title as event_title', 'booking.time as booking_time')
		->find($id);
		// dd($this->model->toArray());
		$this->data['title'] 			= "View Booking ".$this->model->booking_code;
		$this->data['data']  			= $this->model;
		$this->data['time']	=  array();
		return $this->render_view('pages.booking.view');
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

	public function export(){
		return $this->build_export_cus();
	}

	public function export_pdf(){
		$id 		= $_GET['id'];
		$data       = [];
		$current_date 	= date("Y-m-d");

		$room_payment     = Order_hd::with('order_status')->with('order_dt')->with('delivery_area')->with('delivery_method')->with('return_area')->with('return_method')->with('promo')->where('orderhd.id', $id)->with('delivery_city')->with('delivery_province')->with('return_city')->with('return_province')->first();
		// dd($room_payment);
		// $delivery_area_method = Delivery_area_method::where([['delivery_area_id', $room_payment->delivery_area_id],['delivery_method_id', $room_payment->delivery_method_id]])->first();
		// $return_area_method = Delivery_area_method::where([['delivery_area_id', $room_payment->return_area_id],['delivery_method_id', $room_payment->return_method_id]])->first();
		$country = Country::whereIn('id', json_decode($room_payment->country_id))->get();
		$modem = Modem::join('category_modem', 'category_modem.id', 'modem.category_modem_id')->where('modem_status_id', 1)->select('modem.*', 'category_modem.category_modem_name', 'category_modem.deposit_price', 'category_modem.rent_price')->get();
		// dd($country);
		// $delivery 		  = Delivery_area::with('order_status')->with('order_dt')->where('orderhd.id', $id)->get();
		$data['web_logo'] 						= Config::where('id', 2)->first();
		$data['bank_account'] 					= Config::where('id', 21)->first();
		$data['logo_signature'] 				= Config::where('id', 22)->first();
		$data['order']     						= $room_payment;
		$this->data['order']     				= $room_payment;
		// $this->data['delivery_area_method']     = $delivery_area_method;
		// $this->data['return_area_method']     	= $return_area_method;
		$this->data['country']     				= $country;
		$this->data['modem']     				= $modem;
		$data['country']     					= $country;
		$data['modem']     						= $modem;
 
		$fine_cost_per_day 				= Config::where('id', 19)->first();
		$data['fine_cost_per_day'] 		=  $fine_cost_per_day->value;
		$x = $data['order']->total + $data['order']->fine_cost;

		$this->data['terbilang']				= $this->kyubi->terbilang($x, 4).' Rupiah';
		$this->data['keterangan']				= 'Pelunasan paling lambat tanggal '.date('d-m-Y', strtotime('+17 days', strtotime($data['order']->go_from_indonesia))); 
		$this->data['bank_account'] 			= $data['bank_account']['value'];
		$this->data['logo_signature'] 		= $data['logo_signature']['value'];

		$data['terbilang']				= $this->kyubi->terbilang($x, 3);
		$data['keterangan']				= 'Pelunasan paling lambat tanggal '.date('d-m-Y', strtotime('+17 days', strtotime($data['order']->go_from_indonesia))); 
		$data['bank_account'] 			= $data['bank_account']['value'];
		$data['logo_signature'] 		= $data['logo_signature']['value'];

		// $data['pass_return_date'] = 0;
		// if($room_payment->return_date < $current_date){
		// 	$date_interval = $this->kyubi->getDateIntervalToYearMontDay($current_date, $room_payment->return_date);
		// 	$data['pass_return_date'] =   $date_interval['interval_day'];
		// 	// dd($data['pass_return_date']);
		// }
		
		// dd(count($this->data['country']));
		// return $this->render_view('pdf.invoice_order');
		$view             = $this->view_path.'.pdf.invoice_order';
		$title            = 'Invoice Order #'.$room_payment->order_no.'.pdf';
		$pdf              = PDF::loadView($view, $data);

		return $pdf->setPaper('a4')->download($title);
	}
}
