<?php namespace digipos\Http\Controllers\Admin;

use digipos\models\Config;
use digipos\models\Category_modem;
use digipos\models\Modem;
use digipos\models\Modem_status;
use digipos\models\Order_hd;
use digipos\models\Order_dt;
use digipos\models\Order_status;
use digipos\models\Country;
use digipos\models\Delivery_area;
use digipos\models\Delivery_method;
use digipos\models\Delivery_area_method;
use digipos\models\City;
use digipos\models\Province;
use digipos\models\City_ongkir;
use digipos\models\Orderhd_log;
use digipos\models\User;

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

class OrderController extends KyubiController {

	public function __construct()
	{
		parent::__construct();
		$this->middleware($this->auth_guard); 
		$this->middleware($this->role_guard);
		$this->title 			= "Order";
		$this->data['title']	= $this->title;
		$this->root_link 		= "manage-order";
		$this->model 			= new Order_hd;

		$this->bulk_action			= true; 
		$this->bulk_action_data 	= [1];
		$this->invoice_pdf 			= true;
		$this->image_path 			= 'components/both/images/order/';
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

		// $desc_filter = Order_status::select('desc')->whereIn('id', [1,2,3,4,5,6,11])->get();

		// foreach($desc_filter as $dc){
		// 	$dc_filter[$dc->desc] = $dc->desc;
		// }
		// dd($this->getOrderStatus());
		$this->field = [
			// [
			// 	'name' 		=> 'images',
			// 	'label' 	=> 'Image',
			// 	'type' 		=> 'image',
			// 	'file_opt' 	=> ['path' => $this->image_path, 'custom_path_id' => 'y']
			// ],
			[
				'name' 		=> 'order_no',
				'label' 	=> 'Order No',
				'sorting' 	=> 'y',
				'search' 	=> 'text'
			],
			[
				'name' 		=> 'go_from_indonesia',
				'label' 	=> 'From',
				'sorting' 	=> 'y',
				'search' 	=> 'text'
			],
			[
				'name' 		=> 'arrival_in_indonesia',
				'label' 	=> 'To',
				// 'sorting' 	=> 'y',
				// 'search' => 'select',
				// 'search_data' => ['1' => 'Active', '2' => 'Not-active'],
				'type' => 'text'
			],
			[
				'name' 		=> 'order_status_name',
				'label' 	=> 'Order Status',
				// 'sorting' 	=> 'y',
				// 'search' => 'select',
				// 'search_data' => ['1' => 'Active', '2' => 'Not-active'],
				'type' => 'text'
			],

		];

		$this->model = $this->model->join('order_status', 'order_status.id', 'orderhd.order_status_id')->select('orderhd.*', 'order_status.order_status_name');
		//check function modem rent pass day
		$this->checkOrderPassDay();
		$this->checkOrderMustReturn();
		$this->checkOrderMustDelivery();

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
		if($this->is_connected()){
			$province_rajaongkir 	= json_decode($this->getProvince_rajaongkir());
			$province_rajaongkir2 	= [];

			/*get province raja ongkir*/
			if($province_rajaongkir->status == 'success'){
				$province_rajaongkir2 = json_decode($province_rajaongkir->data);
			}

			/*get city raja ongkir*/
			$city_rajaongkir = $this->getCity_rajaongkir();
			$city_rajaongkir2 = [];
			if($city_rajaongkir['status'] == 'success'){
				$city_rajaongkir2 = $city_rajaongkir['data'];
			}

			// dd(json_encode($city_rajaongkir2));
			// $this->data['data']  			= $this->model->find($id);
			
			$cost_rajaongkir2 = 0;
			$cost_rajaongkir3 = 0;
			$this->data['cost_ongkir'] = [];
			// if($this->data['data']->delivery_city_id != null){
			// 	$city_ongkir  					= City_ongkir::where('status', 'y')->where('city_id', $this->data['data']->delivery_city_id)->first();
			// 	if($city_ongkir != null){
			// 		$cost_rajaongkir2 = 'Gojek,Price: '.$city_ongkir->ongkir.',ETD: 1-1';
			// 		$cost_rajaongkir3 = $city_ongkir->ongkir;
			// 	}else{
			// 		// 151, jakarta barat
			// 		// 152, jakarta pusat
			// 		// 153, jakarta selatan
			// 		// 154, jakarta timur
			// 		// 155, jakarta utara
			// 		$city_from = 155;
			// 		$city_dest = $this->data['data']->delivery_city;
			// 		$weight = $this->data['data']->total_weight_gram;
			// 		$cost_rajaongkir = $this->getCost_rajaongkir($city_from, $city_dest, $weight);
			// 		// dd($cost_rajaongkir);
			// 		if($cost_rajaongkir['status'] == 'success'){
			// 			$cost_rajaongkir2 = $cost_rajaongkir['data']->results;
			// 			if(count($cost_rajaongkir2) > 0 ){
			// 				$cost_rajaongkir2 = $cost_rajaongkir2[0]->costs;
	 	// 					foreach ($cost_rajaongkir2 as $key => $val) {
			// 					// if($val->)
			// 				}
							
			// 			}
			// 		}else{
			// 			echo $cost_rajaongkir['message'];
			// 		}
			// 	}
				
			// }

			
			$this->data['cost_ongkir'] 		= $cost_rajaongkir3;
			$this->data['service_ongkir'] 	= $cost_rajaongkir2;
			$this->data['return_ongkir'] 	= $cost_rajaongkir2;

			// dd($cost_rajaongkir2);
			// $this->model 					= $this->model->join('order_status', 'order_status.id', 'orderhd.order_status_id')->find($id);
			// dd($this->model);
			$this->data['title'] 			= "Add Order";
			
			//get category modem by continent
			// $modem_category = $this->getModemCategoryByContinent($this->data['data']->country_id);

			$this->data['category_modem']	= Category_modem::where('status', 'y')->get();
			$this->data['order_status']		= Order_status::get();
			$this->data['country']			= Country::where('status', 'y')->get();
			$this->data['delivery_area']	= Delivery_area::where('status', 'y')->get();
			// dd($this->data['delivery_area']);
			$this->data['delivery_method']	= Delivery_method::where('status', 'y')->whereIn('id', [2,3])->get();
			$this->data['delivery_method2']	= Delivery_method::where('status', 'y')->whereIn('id', [2,5])->get();
			// $this->data['delivery_area_method']	= json_encode(Delivery_area_method::get());
			// $this->data['orderdt'] 			= Order_dt::where('orderhd_id', $id)->get();
			$this->data['modem_available']  = $this->getAvailableModem(); 
			$this->data['modem'] 			= Modem::where([['modem_status_id', '!=', 3]])->get(); //get modem where not status on service
			// dd($this->data['modem']);
			$this->data['weight'] 			= $this->data['modem'][0]->weight_gram;
			// $this->data['city'] 			= City::OrderBy('name')->get();
			$this->data['city'] 			= $city_rajaongkir2;
			// $this->data['province'] 		= Province::OrderBy('name')->get();
			$this->data['province'] 		= $province_rajaongkir2;
			return $this->render_view('pages.order.create');
		}else{
			return "Please connect to internet !";
		}
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

		// dd(count($request->country));
        if($request->country == NULL){
			Alert::fail('Country must be selected !');
			return redirect()->to($this->data['path'].'/create')->withInput($request->input());
		}

		if($request->modem == NULL){
			Alert::fail('Modem must be selected !');
			return redirect()->to($this->data['path'].'/create')->withInput($request->input());
		}

		$date_from 	= date_format(date_create($request->date_from),'Y-m-d');
		$date_to 	= date_format(date_create($request->date_to),'Y-m-d');

		if($date_from > $date_to){
			Alert::fail('Date From greate than Date To !');
			return redirect()->to($this->data['path'].'/create')->withInput($request->input());
		}

		// $this->model 								= $this->model->find($id);
		/*order*/
		$this->model->order_status_id				= $request->order_status;
		$lastOrderHd 								= Order_hd::OrderBy('id', 'desc')->first();

		$y = date('y');
		$m = date('m');
		$d = date('d');
		$no = '10001';
		if($lastOrderHd != null){
			
			$order_no = substr($lastOrderHd->order_no, 5);
			$y2 = substr($order_no, 0, 2);
			$m2 = substr($order_no, 2, 2);
			$d2 = substr($order_no, 4, 2);
			$no = substr($order_no, 6, 5);
			// dd($order_no.'-'.$y2.'-'.$m2.'-'.$d2.'-'.$no);
			if($y2 == $y && $m2 == $m && $d2 == $d){
				$no = (int)$no + 1;
			}else{
				$no = '10001';
			}
		}
		$orderNo =  'JETFI'.$y.$m.$d.$no;
		
		$this->model->order_no						= $orderNo;
		$country 	= [];
		$weight  	= 0;
		$subtotal  	= 0;
		
		if(count($request->country) > 0){
			foreach ($request->country as $key => $ac) {
				$ac = explode('-', $ac);
				// dd($ac[1]);
				array_push($country, $ac[1]);
			}
		}

		$this->model->country_id					= json_encode($country);
		// $modem 										= Modem::whereIn('id', $country)->select(DB::raw('SUM(weight_gram) AS total_weight_gram'))->first();
		// $weight 									= $modem->total_weight_gram;
		$this->model->go_from_indonesia				= date_format(date_create($request->date_from),'Y-m-d');
		$this->model->arrival_in_indonesia 			= date_format(date_create($request->date_to),'Y-m-d');
		$this->model->qty_modem 					= $request->qty_modem;

		$this->model->delivery_city_id 			= null;
		$this->model->delivery_province_id 		= null;
		$this->model->delivery_address 			= null;
		$this->model->delivery_post_code 		= null;
		$this->model->delivery_area_id 			= null;

		$delivery_days = 1;
		$total_shipping_price = 0;
		if($request->delivery_method == 2){
			$this->model->delivery_city_id 			= $request->da2_city;
			$this->model->delivery_province_id 		= $request->da2_province;
			$this->model->delivery_address 			= $request->da2_address;
			$this->model->delivery_post_code 		= $request->da2_kodepos;
			// dd($request->da2_city.' - '.$request->da2_province.' - '.$request->da2_address.' - '.$request->da2_kodepos.' - '.$request->da2_cost_ongkir);
			if($request->da2_city == NULL || $request->da2_province == NULL || $request->da2_address == NULL || $request->da2_kodepos == null || $request->da2_cost_ongkir == NULL){
				Alert::fail('Delivery information must be fiiled');
				return redirect()->to($this->data['path'].'/create')->withInput($request->input());
			}

			$price_per_kg 							= $request->da2_cost_ongkir;
			// $total_shipping_price 					= $price_per_kg * $weight;
			$delivery_days 							= $this->getDeliveryReturnTime('', $request->da2_city);
			$delivery_days 							= $delivery_days->delivery_day;
			
		}elseif($request->delivery_method == 3) {
			$this->model->delivery_area_id			= $request->delivery_area;
		}

		$date  									= date_format(date_create($request->date_from),'Y-m-d');
		// dd($delivery_days->delivery_day);
		$delivery_date 							= date('Y-m-d', strtotime('-'.$delivery_days.' days', strtotime($date)));

		$this->model->return_city_id 			= null;
		$this->model->return_province_id 		= null;
		$this->model->return_address 			= null;
		$this->model->return_post_code 			= null;
		$this->model->return_area_id 			= null;
		$return_days = 0;
		if($request->return_method == 2){
			if($request->checkbox_same_address == 'y'){
				$this->model->return_city_id 			= $request->da2_city;
				$this->model->return_province_id 		= $request->da2_province;
				$this->model->return_address 			= $request->da2_address;
				$this->model->return_post_code 			= $request->da2_kodepos;
				$return_days 							= $this->getDeliveryReturnTime('',$request->da2_city);
				$return_days 							= $return_days->return_day;
			}else{
				$this->model->return_city_id 			= $request->ra2_city;
				$this->model->return_province_id 		= $request->ra2_province;
				$this->model->return_address 			= $request->ra2_address;
				$this->model->return_post_code 			= $request->ra2_kodepos;
				$return_days 							= $this->getDeliveryReturnTime('',$request->ra2_city);
				$return_days 							= $return_days->return_day;
			}
		}elseif ($request->return_method == 5) {
			$this->model->return_area_id			= $request->return_area;
		}

		$date  										= date_format(date_create($request->date_to),'Y-m-d');
		$return_date 								= date('Y-m-d', strtotime('+'.$return_days.' days', strtotime($date)));
		
		$this->model->delivery_method_id 			= $request->delivery_method;
		$this->model->return_method_id 				= $request->return_method;
		/*customer*/
		$this->model->full_name 					= $request->full_name; 
		$this->model->telephone 					= $request->telephone; 
		$this->model->email 						= $request->email; 
		/*customer*/
		$this->model->bank_name 					= $request->bank_name; 
		$this->model->bank_account_name 			= $request->bank_account_name; 
		$this->model->bank_account_no 				= $request->bank_account_no; 
		$this->model->note 							= $request->note; 

		$this->model->upd_by 						= auth()->guard($this->guard)->user()->id;
		$this->model->total_weight_kg 				= $weight;
		// $this->model->total_shipping_price 			= $total_shipping_price;
		// $this->model->total 						= $subtotal + $total_shipping_price;
		$this->model->delivery_date 				= $delivery_date;
		$this->model->return_date 					= $return_date;

		if($request->input('remove-single-image-image') == 'y'){
			if($this->model->image != NULL){
				// dd($this->image_path.$this->model->id.'/'.$this->model->image);
				File::delete($this->image_path.$this->model->id.'/'.$this->model->image);
				$this->model->image = '';
			}
		}

		if ($request->hasFile('image')){
        	// File::delete($path.$user->images);
			$data = [
						'name' => 'image',
						'file_opt' => ['path' => $this->image_path.$this->model->id.'/']
					];
			$image = $this->build_image($data);
			$this->model->transfer_foto = $image;
		}

		// dd($this->model);
		$this->model->save();
		$this->insertOrderLog($this->model->id, $this->model->toArray());

		$weight = 0;
		if(count($request->modem)){
			foreach ($request->modem as $key => $v) {
				$modem 		= Modem::join('category_modem', 'category_modem.id', 'modem.category_modem_id')->where('modem.id', $v)->select('modem.*', 'category_modem.*')->first();
				
				if($modem != null){
					$weight 	+= (int)$modem->weight_gram;
					$period = new DatePeriod(
					     new DateTime($date_from),
					     new DateInterval('P1D'),
					     new DateTime($date_to)
					);
					$date1 			= date_create($date_from);
					$date2 			= date_create($date_to);

					$diff 			= date_diff($date1,$date2);
					$interval_day 	= $diff->format("%a");
					// dd($interval_day);
					// echo '$modem->rent_price: '.$modem->rent_price.'<br>';
					// echo '$interval_day: '.$interval_day.'<br>';
					// echo '$modem->deposit_price: '.$modem->deposit_price.'<br>';
					$subtotal 		+=  ((int)$modem->rent_price * (int)$interval_day) + $modem->deposit_price; 
				}

				$order_dt = new Order_dt;
				$order_dt->orderhd_id 		= $this->model->id;
				$order_dt->modem_id 		= $v;
				$order_dt->upd_by 			= auth()->guard($this->guard)->user()->id;
				$order_dt->rent_price 		= $modem->rent_price;
				$order_dt->deposit_price 	= $modem->deposit_price;
				$order_dt->rent_day 		= $interval_day;
				$order_dt->subtotal 		= $subtotal;
				$order_dt->save();
			}

		}

		$price_per_kg = 0;
		if($request->delivery_method == 2){
			// $weight = 1300;
			// var_dump($weight);
			if($weight < 1000){
				$weight = 1000;
			}else{
				$temp = $weight % 1000;
				$tolerasi_gram = DB::table('config')->where('name', 'toleransi_gram_shipping')->first();
				$tolerasi_gram = $tolerasi_gram->value;
				// var_dump($temp);
				// var_dump($tolerasi_gram);
				if($temp > $tolerasi_gram){
					//if sisa hasil bagi > tolearnsi gram, berat dinaikan 1kg
					$weight = (int)($weight/1000+1);
				}else{
					$weight = (int)$weight;
				}
			}
			// var_dump($weight);
			// var_dump($weight/1000);
			// var_dump(floor($weight/1000));
			// dd($weight_2);
			$price_per_kg 						= $request->da2_cost_ongkir / $weight;
		}
		$weight_kg = $weight/1000;
		// $total_shipping_price 				= $price_per_kg * $weight_kg;
		$total_shipping_price 				= $request->da2_cost_ongkir;
		// var_dump($total_shipping_price);

		$price_return_per_kg = 0;
		if($request->return_method == 2){
			// $weight = 1300;
			// var_dump($weight);
			if($weight < 1000){
				$weight = 1000;
			}else{
				$temp = $weight % 1000;
				$tolerasi_gram = DB::table('config')->where('name', 'toleransi_gram_shipping')->first();
				$tolerasi_gram = $tolerasi_gram->value;
				// var_dump($temp);
				// var_dump($tolerasi_gram);
				if($temp > $tolerasi_gram){
					//if sisa hasil bagi > tolearnsi gram, berat dinaikan 1kg
					$weight = (int)($weight/1000+1);
				}else{
					$weight = (int)$weight;
				}
			}
			// var_dump($weight);
			// var_dump($weight/1000);
			// var_dump(floor($weight/1000));
			$price_return_per_kg 						= $request->ra2_cost_ongkir/$weight;
		}
		$weight_return_kg = floor($weight/1000);
		// $total_return_shipping_price 				= $price_return_per_kg * $weight_return_kg;
		$total_return_shipping_price 				= $request->ra2_cost_ongkir;

		$this->model->shipping_price 				= $price_per_kg;
		$this->model->return_shipping_price 		= $price_return_per_kg;
		$this->model->total_weight_kg 				= $weight_kg;
		$this->model->total_shipping_price 			= $total_shipping_price;
		$this->model->total_return_shipping_price 	= $total_return_shipping_price;
		$this->model->sub_total 					= $subtotal;
		$this->model->total 						= $subtotal + $total_shipping_price + $total_return_shipping_price;
		// dd($this->model);
		$this->model->save();


		$order     = Order_hd::with('order_status')->with('order_dt')->with('delivery_area')->with('delivery_method')->with('return_area')->with('return_method')->with('promo')->where('orderhd.id', $this->model->id)->with('delivery_city')->with('delivery_province')->with('return_city')->with('return_province')->first();
		
		$country = Country::whereIn('id', json_decode($order->country_id))->get();

		$modem = Modem::join('category_modem', 'category_modem.id', 'modem.category_modem_id')->where('modem_status_id', 1)->select('modem.*', 'category_modem.category_modem_name', 'category_modem.deposit_price', 'category_modem.rent_price')->get();

		$this->data['web_logo'] 				= Config::where('id', 2)->first();
		$this->data['order']     				= $order;
		$this->data['country']     				= $country;
		$this->data['modem']     				= $modem;

		//Send email
		Email::to($request->email);
  		Email::subject("Invoice Jetfi");
  		Email::view($this->view_path.'.pdf.invoice_order');
  		Email::email_data($this->data);
  		// Email::attach('components/files/UserDetail-'.$user->id.'.pdf');
  		Email::send();

		// $this->increase_version();
		$title 	= 'New order, Order No: '.$orderNo;
		$desc 	= 'New order, Order No: '.$orderNo.' from admin';
		$order_id = $this->model->id;
		$this->insertNotification($title, $desc, $type = 'order', $order_id);

		Alert::success('Successfully add Order');
		return redirect()->to($this->data['path']);
	}

	public function edit($id){
		// if($this->is_connected()){
			$weight_gram 			= 600;
			$province_rajaongkir 	= json_decode($this->getProvince_rajaongkir());
			// dd($province_rajaongkir);
			$province_rajaongkir2 	= [];

			/*get province raja ongkir*/
			if($province_rajaongkir->status == 'success'){
				$province_rajaongkir2 = json_decode($province_rajaongkir->data);
			}
			// dd($province_rajaongkir2);
			/*get city raja ongkir*/
			$city_rajaongkir = $this->getCity_rajaongkir();
			$city_rajaongkir2 = [];
			if($city_rajaongkir['status'] == 'success'){
				$city_rajaongkir2 = $city_rajaongkir['data'];
			}

			// dd(json_encode($city_rajaongkir2));
			$this->data['data']  			= $this->model->find($id);
			// dd($this->data['data']);
			$cost_rajaongkir2 = 0;
			$cost_rajaongkir3 = 0;
			$this->data['cost_ongkir'] = [];
			if($this->data['data']->delivery_city_id != null){
				$city_ongkir  					= City_ongkir::join('city_ongkir_detail','city_ongkir_detail.city_ongkir_id','city_ongkir.id')->where('city_ongkir.status', 'y')->where('city_ongkir_id', $this->data['data']->delivery_city_id)->first();
				// dd($city_ongkir);
				if($city_ongkir != null){
					$cost_rajaongkir2 = 'Gojek,Price: '.$city_ongkir->ongkir.',ETD: 1-1';
					$cost_rajaongkir3 = $city_ongkir->ongkir;
				}else{
					// 151, jakarta barat
					// 152, jakarta pusat
					// 153, jakarta selatan
					// 154, jakarta timur
					// 155, jakarta utara
					// $city_from = 155;
					// $city_dest = $this->data['data']->delivery_city_id;
					// $weight_gram = $this->data['data']->total_weight_gram;
					// $cost_rajaongkir = $this->getCost_rajaongkir($city_from, $city_dest, $weight_gram);
					// // dd($cost_rajaongkir);
					// if($cost_rajaongkir['status'] == 'success'){
					// 	$cost_rajaongkir2 = $cost_rajaongkir['data']->results;
					// 	if(count($cost_rajaongkir2) > 0 ){
					// 		$cost_rajaongkir2 = $cost_rajaongkir2[0]->costs;
					// 		// dd($cost_rajaongkir2);
					// 		$temp_service 		= '';
					// 		$temp_cost_ongkit   = 0;
	 			// 			foreach ($cost_rajaongkir2 as $key => $val) {
					// 			if($val->service == "REG"){
					// 				$temp_service 		= $val->cost[0];
					// 				$temp_cost_ongkir 	= $val->service;
					// 			}

					// 			if($val->service == "YES"){
					// 				$temp_service 		= $val->cost[0];
					// 				$temp_cost_ongkir 	= $val->service;
					// 			}	
					// 		}
					// 		$cost_rajaongkir2 = $temp_service;
					// 		$cost_rajaongkir3 = $temp_cost_ongkir;
					// 	}
					// }else{
					// 	echo $cost_rajaongkir['message'];
					// }

					$cost_rajaongkir2 = $this->data['data']->courier.': '.$this->data['data']->courier_price_per_kg.',ETD: '.$this->data['data']->courier_etd;
					$cost_rajaongkir3 = $this->data['data']->total_shipping_price;
					// dd($cost_rajaongkir3);
				}
				
			}

			$this->data['cost_ongkir'] = $cost_rajaongkir3;
			$this->data['service_ongkir'] = $cost_rajaongkir2;


			$cost_return_rajaongkir2 = 0;
			$cost_return_rajaongkir3 = 0;

			if($this->data['data']->return_city_id != null){
				$city_ongkir  					= City_ongkir::join('city_ongkir_detail','city_ongkir_detail.city_ongkir_id','city_ongkir.id')->where('city_ongkir.status', 'y')->where('city_ongkir_id', $this->data['data']->return_city_id)->first();
				// dd($city_ongkir);
				if($city_ongkir != null){
					$cost_return_rajaongkir2 = 'Gojek,Price: '.$city_ongkir->ongkir.',ETD: 1-1';
					$cost_return_rajaongkir3 = $city_ongkir->ongkir;
				}else{
					// 151, jakarta barat
					// 152, jakarta pusat
					// 153, jakarta selatan
					// 154, jakarta timur
					// 155, jakarta utara

					$cost_return_rajaongkir2 = $this->data['data']->return_courier.': '.$this->data['data']->courier_price_per_kg.',ETD: '.$this->data['data']->return_courier_etd;
					$cost_return_rajaongkir3 = $this->data['data']->total_return_shipping_price;
					// dd($cost_rajaongkir3);
				}
			}

			$this->data['cost_return_ongkir'] = $cost_return_rajaongkir3;
			$this->data['service_return_ongkir'] = $cost_return_rajaongkir2;

			
			$this->model 					= $this->model->join('order_status', 'order_status.id', 'orderhd.order_status_id')->select('orderhd.*', 'order_status.order_status_name')->find($id);
			// dd($this->model);
			$this->data['title'] 			= "Edit Order ".$this->model->order_no;
			
			//get category modem by continent
			// $modem_category = $this->getModemCategoryByContinent($this->data['data']->country_id);

			$this->data['category_modem']	= Category_modem::where('status', 'y')->get();
			$this->data['order_status']		= Order_status::get();
			$this->data['country']			= Country::where('status', 'y')->get();
			// dd($this->data['country']);
			$this->data['delivery_area']	= Delivery_area::where('status', 'y')->get();
			// dd($this->data['delivery_area']);
			$this->data['delivery_method']	= Delivery_method::where('status', 'y')->whereIn('id', [2,3])->get();
			$this->data['delivery_method2']	= Delivery_method::where('status', 'y')->whereIn('id', [2,5])->get();
			// $this->data['delivery_area_method']	= json_encode(Delivery_area_method::get());
			$this->data['orderdt'] 			= Order_dt::join('modem','modem.id','orderdt.modem_id')->where('orderhd_id', $id)->select('orderdt.*', 'modem.modem_name', 'modem.category_modem_id')->get();
			$this->data['modem_available']  = $this->getAvailableModem(); 
			// $this->data['modem'] 			= Modem::where([['modem_status_id', 1],['category_modem_id', $modem_category]])->get();
			// $this->data['modem'] 			= Modem::where([['modem_status_id', 1]])->get();
			
			$arr_country			= json_decode($this->data['data']->country_id);
			$arr_country2           = [];
			if(count($arr_country) > 0){
				foreach ($this->data['country'] as $key => $v) {
					if(in_array($v->id, $arr_country)){
						$temp = $v->continent_name.'-'.$v->id;
						array_push($arr_country2, $temp);
					}
				}
			}
			$_POST['arr_country'] 			= $arr_country2;
			$_POST['date_from'] 			= $this->data['data']->go_from_indonesia;
			$_POST['exclude_orderhd_id']    = $id;
			$this->data['modem'] 			= json_decode($this->getModemAvailableByCountry());
			// dd($this->data['modem']);
			$this->data['weight'] 			= $weight_gram;
			// $this->data['city'] 			= City::OrderBy('name')->get();
			$this->data['city'] 			= $city_rajaongkir2;
			// $this->data['province'] 		= Province::OrderBy('name')->get();
			$this->data['province'] 		= $province_rajaongkir2;
			// dd($this->data);
			$this->data['orderhd_log'] 		= Orderhd_log::where('order_id', $id)->get();
			// dd($this->data['orderhd_log']);
			$this->data['user'] 			= User::get();
			$this->data['modem_all'] 			= Modem::get();

			// dd($this->data['data']);
			return $this->render_view('pages.order.edit');
		// }else{
		// 	return "Please connect to internet !";
		// }
	}

	public function update(Request $request, $id){
		$mode = 2;
		$this->validate($request,[
			'full_name' 		=> 'required:orderhd,full_name',
			'email' 			=> 'required|email:orderhd,email',
		],[
            'full_name.required' => 'Full Name is Required.',
            'full_name.unique' 	=> 'Full Name has already been taken.',
        ]);

		// dd($request->modem);
        if($request->country == NULL){
			Alert::fail('Country must be selected !');
			return redirect()->to($this->data['path'].'/'.$id.'/edit')->withInput($request->input());
		}

		// if($request->modem == NULL){
		// 	Alert::fail('Modem must be selected !');
		// 	return redirect()->to($this->data['path'].'/'.$id.'/edit')->withInput($request->input());
		// }


		$this->model 								= $this->model->find($id);
		/*order*/
		$this->model->order_status_id				= $request->order_status;
		$this->model->order_no						= $request->order_no;
		$arr_acountry = [];
		// dd(count($request->country));
		if(count($request->country) > 0){
			foreach ($request->country as $key => $val) {
				// var_dump($val);
				$val2 = explode('-', $val);
				array_push($arr_acountry, $val2[1]);
			}
		}
		// dd($arr_acountry);
		$this->model->country_id					= json_encode($arr_acountry);
		$this->model->go_from_indonesia				= date_format(date_create($request->date_from),'Y-m-d');
		$this->model->arrival_in_indonesia 			= date_format(date_create($request->date_to),'Y-m-d');
		$this->model->qty_modem 					= $request->qty_modem;

		if($mode == 1){
			$this->model->delivery_city_id 			= null;
			$this->model->delivery_province_id 		= null;
			$this->model->delivery_address 			= null;
			$this->model->delivery_post_code 		= null;
			$this->model->delivery_area_id 			= null;

			if($request->delivery_method == 2){
				$this->model->delivery_city_id 			= $request->da2_city;
				$this->model->delivery_province_id 		= $request->da2_province;
				$this->model->delivery_address 			= $request->da2_address;
				$this->model->delivery_post_code 		= $request->da2_kodepos;
			}elseif ($request->delivery_method == 3) {
				$this->model->delivery_area_id			= $request->delivery_area;
			}

			$this->model->return_city_id 			= null;
			$this->model->return_province_id 		= null;
			$this->model->return_address 			= null;
			$this->model->return_post_code 			= null;
			$this->model->return_area_id 			= null;

			if($request->return_method == 2){
				$this->model->return_city_id 			= $request->ra2_city;
				$this->model->return_province_id 		= $request->ra2_province;
				$this->model->return_address 			= $request->ra2_address;
				$this->model->return_post_code 			= $request->ra2_kodepos;
			}elseif ($request->return_method == 5) {
				$this->model->return_area_id			= $request->return_area;
			}
			
			$this->model->delivery_method_id 			= $request->delivery_method;
			$this->model->return_method_id 				= $request->return_method;
		}

		/*customer*/
		$this->model->full_name 					= $request->full_name; 
		$this->model->telephone 					= $request->full_name; 
		$this->model->email 						= $request->email; 
		/*customer*/
		$this->model->bank_name 					= $request->bank_name; 
		$this->model->bank_account_name 			= $request->bank_account_name; 
		$this->model->bank_account_no 				= $request->bank_account_no; 
		$this->model->note 							= $request->note; 

		$this->model->upd_by 						= auth()->guard($this->guard)->user()->id;

		if($request->order_status == 4 || $request->order_status == 5){
			$this->model->total_refund = $request->total_refund;
		}

		// dd($request->input('remove-single-image-image'));
		if($request->input('remove-single-image-image') == 'y'){
			if($this->model->transfer_foto != NULL){
				// dd($this->image_path.$this->model->id.'/'.$this->model->image);
				File::delete($this->image_path.'/'.$this->model->transfer_foto);
				$this->model->transfer_foto = '';
			}
		}

		if ($request->hasFile('image')){
        	// File::delete($path.$user->images);
			$data = [
						'name' => 'image',
						'file_opt' => ['path' => $this->image_path.'/']
					];
			$image = $this->build_image($data);
			$this->model->transfer_foto = $image;
		}

		// dd($this->model);
		$this->model->save();

		// $arr_modem = [];
		// if(count($request->modem)){
		// 	foreach ($request->modem as $key => $val) {
		// 		array_push($arr_modem, $val);
		// 	}
		// }

		$this->insertOrderLog($this->model->id, $this->model->toArray(), $request->modem);

		//function modem id
		$date_from = $this->model->go_from_indonesia;
		$date_to = $this->model->arrival_in_indonesia;
		if(count($request->modem)){
			$orderdt = Order_dt::where('orderhd_id',$this->model->id)->pluck('modem_id')->toArray();
			// var_dump($orderdt);
			// var_dump($request->modem);
			//function compare array new or par 1 from old array or par 2
			$arr_diff = $this->kyubi->array_different($request->modem, $orderdt);
			// dd($arr_diff);

			//if find diffrent array then insert to orderdt
			// if(count($arr_diff) > 0){
			// 	$weight = 0;
			// 	$subtotal = 0;
			// 	foreach ($arr_diff as $key => $v) {
			// 		//insert orderdt value from $arr_diff
			// 		$modem 		= Modem::join('category_modem', 'category_modem.id', 'modem.category_modem_id')->where('modem.id', $v)->select('modem.*', 'category_modem.*')->first();
					
			// 		if($modem != null){
			// 			$weight 	+= ((int)$modem->weight_gram / 1000);
			// 			// dd($weight);
			// 			$period = new DatePeriod(
			// 			     new DateTime($date_from),
			// 			     new DateInterval('P1D'),
			// 			     new DateTime($date_to)
			// 			);
			// 			$date1 			= date_create($date_from);
			// 			$date2 			= date_create($date_to);

			// 			$diff 			= date_diff($date1,$date2);
			// 			$interval_day 	= $diff->format("%a");
			// 			// dd($interval_day);
			// 			// echo '$modem->rent_price: '.$modem->rent_price.'<br>';
			// 			// echo '$interval_day: '.$interval_day.'<br>';
			// 			// echo '$modem->deposit_price: '.$modem->deposit_price.'<br>';
			// 			$subtotal 		+=  ((int)$modem->rent_price * (int)$interval_day) + $modem->deposit_price; 
			// 		}

			// 		$this->model = $this->model->find($id);
			// 		$this->model->total_weight_kg = $weight;
			// 		$this->model->sub_total = $subtotal;
			// 		// dd($this->model);
			// 		$this->model->save();

			// 		$order_dt = new Order_dt;
			// 		$order_dt->orderhd_id 		= $this->model->id;
			// 		$order_dt->modem_id 		= $v;
			// 		$order_dt->upd_by 			= auth()->guard($this->guard)->user()->id;
			// 		$order_dt->rent_price 		= $modem->rent_price;
			// 		$order_dt->deposit_price 	= $modem->deposit_price;
			// 		$order_dt->rent_day 		= $interval_day;
			// 		$order_dt->subtotal 		= $subtotal;
			// 		$order_dt->save();

			// 		// $this->insertOrderdtLog($order_dt->id, $order_dt->toArray());
			// 	}
			// }

			// if(count($orderdt) > count($request->modem)){
			// 	$arr_diff2 = $this->kyubi->array_different($orderdt, $request->modem);
				
			// 	if(count($arr_diff2) > 0){
			// 		//delete order dt where modem_id and order_id equal with condition
			// 		// foreach ($arr_diff2 as $v) {
			// 			$delete 	= Order_dt::where('orderhd_id',$this->model->id)->whereIn('modem_id',$arr_diff2);	
			// 			$delete->delete();
			// 		// }
			// 	}
			// }
		}

		// $this->increase_version();
		
		Alert::success('Successfully edit Order');
		return redirect()->to($this->data['path']);
	}

	public function show($id){
		$this->model 					= $this->model->find($id);
		$this->data['title'] 			= "View Product ".$this->model->order_no;
		$this->data['unit']  			= $this->unit;
		$this->data['data']  			= $this->model;
		return $this->render_view('pages.product.view');
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

	public function getAvailableModem(){
		$modem_available  = Order_hd::join('orderdt', 'orderdt.orderhd_id', 'orderhd.id')->whereNotIn('orderhd.order_status_id', [2, 4])->select('orderhd.*', 'orderdt.modem_id')->get();

		return $modem_available;
	}

	public function getModemCategoryByContinent($par){
		// dd('getModemCategoryByContinent');
		$country = json_decode($par);
		$country2 = Country::whereIn('id', $country)->get();
		// dd($country2);
		$flagAsia = 1;
		foreach ($country2 as $key => $val) {
			if($val->continent_name == 'Asia'){

			}else{
				$flagAsia = 0;
				break;
			}
		}

		if($flagAsia == 1){
			$category_modem = 2;
		}else{
			$category_modem = 1;
		}

		return $category_modem;
	} 

	public function getongkir(){
		$from = 151;
		$dest = $_GET['dest'];
		$weight = $_GET['weight'];

		$cost_rajaongkir2 = 0;
		$cost_rajaongkir3 = 0;
		$cost_jne_reg1 = 0;
		$cost_jne_reg2 = 0;
		if($dest != null){
			$city_ongkir  					= City_ongkir::join('city_ongkir_detail','city_ongkir_detail.city_ongkir_id','city_ongkir.id')->where('status', 'y')->where('city_id', $dest)->select('city_ongkir.*', 'city_ongkir_detail.city_id')->first();
			// return $city_ongkir;
			if($city_ongkir != null){
				$cost_rajaongkir2 = 'Gojek,Price: '.$city_ongkir->ongkir.',ETD: 1-1';
				$cost_rajaongkir3 = $city_ongkir->ongkir;
			}else{
				// 151, jakarta barat
				// 152, jakarta pusat
				// 153, jakarta selatan
				// 154, jakarta timur
				// 155, jakarta utara
				$city_from = 155;
				$city_dest = $dest;
				$weight = $weight;
				$cost_rajaongkir = $this->getCost_rajaongkir($city_from, $city_dest, $weight);
				// return $cost_rajaongkir;

				if($cost_rajaongkir['status'] == 'success'){
					$cost_rajaongkir2 = $cost_rajaongkir['data']->results;
					if(count($cost_rajaongkir2) > 0 ){
						$cost_rajaongkir2 = $cost_rajaongkir2[0]->costs;
						$flag = 0;
						foreach ($cost_rajaongkir2 as $key => $val) {
							if($val->service == "YES"){
								$cost_rajaongkir2 = $val->service.',Price: '.$val->cost[0]->value.',ETD: '.$val->cost[0]->etd;
								$cost_rajaongkir3 = $val->cost[0]->value;
								$flag = 1;
							}

							// if($val->service == "REG"){
							// 	$cost_jne_reg1 = $val->service.',Price: '.$val->cost[0]->value.',ETD: '.$val->cost[0]->etd;
							// 	$cost_jne_reg2 = $val->cost[0]->value;
							// }
						}

						if($flag == 0){
							// $cost_rajaongkir2 = $cost_jne_reg1;
							// $cost_rajaongkir3 = $cost_jne_reg2;

							$cost_rajaongkir2 = 'JNE YES Not Found !';
							$cost_rajaongkir3 = 0;
						}
						
					}
				}else{
					// echo $cost_rajaongkir['message'];

					$cost_rajaongkir2 = "Error";
				}
			}
			
		}

		$res = array(
			"cost_ongkir" => $cost_rajaongkir3,
			"service_ongkir" => $cost_rajaongkir2
		);
			
		return $res;

	}

	public function getModemAvailableByCountry(){
		// return 'test';
		// dd($_POST);
		$arr_country 	= $_POST['arr_country'];
		$date_from 		= $_POST['date_from'];

		if($date_from == ""){
			return 'Please select Date From';
		}

		$exclude_orderhd_id = '';
		if(isset($_POST['exclude_orderhd_id'])){
			$exclude_orderhd_id    = $_POST['exclude_orderhd_id'];
		}
		$date  			= date_format(date_create($date_from),'Y-m-d');
		$modem 			= Modem::leftJoin('category_modem', 'category_modem.id', 'modem.category_modem_id')->where('modem_status_id', '!=', 3)->select('modem.*', 'category_modem.category_modem_name')->get();
		
		//get order status, modem except in stock
		$order = Order_hd::join('orderdt', 'orderdt.orderhd_id', 'orderhd.id')
				->leftJoin('promo', 'promo.id', 'orderhd.promo_id')
				->select('orderhd.*', 'orderdt.modem_id')
				->whereNotIn('order_status_id', ['3', '6', '5']);
		if($exclude_orderhd_id != ''){
			$order = $order->where('orderhd.id', '!=', $exclude_orderhd_id);
		}
		$order = $order->get();
		// return $order;
		// var_dump($arr_country);
		if(count($arr_country) > 0){
			//if country china or (china and hongkong) modem Asia or China Roaming
			$flagChina = 0;
			$flagHongkong = 0;
			$flagAsia = 0;
			$flagGlobal = 1;
			$flagChinaRoaming = 0;
			$flagDaypass = 0;
			$flagOnlyGlobal = 0;
			$flagFoundModemGlobal = 0;
			foreach($arr_country as $ac){
				$ac = explode('-', $ac);
				$country = $ac[1];
				$continent = $ac[0];
				if($country == 48){ //found china country
					$flagChina = 1;
				}

				if($country == 95){ //found hongkong country
					$flagHongkong = 1;
				}

				if(in_array($country, $this->data['daypass'])){
					$flagDaypass = 1;
				}

				if($continent == 'Asia' || $continent == 2){
					$flagAsia 	= 1;
				}else{
					$flagOnlyGlobal = 1;
				}
			}
			// echo $flagDaypass;
			/*
				1: global;
				2: asia;
				3: china roaming;
			*/

			$categoryModemArr = [];
			if($flagGlobal == 1){
				array_push($categoryModemArr, 1);
			}

			if($flagChina == 1 || ($flagChina == 1 && $flagHongkong == 1)){
				array_push($categoryModemArr, 1);
				array_push($categoryModemArr, 3);
				$flagChinaRoaming = 1;
			}
			// echo '$flagChinaRoaming: '.$flagChinaRoaming.'<br>';
			// echo '$flagChina: '.$flagChina.'<br>';
			// echo '$flagHongkong: '.$flagHongkong.'<br>';

			// if(count($categoryModemArr) > 0){
				//looping modem available 
				foreach ($modem as $m) {
					//looping order where status
					$m['show'] = 0;
					// dd($order);
					if(count($order) > 0){
						foreach ($order as $o) {
							$return_days = 1;
							// echo $o->return_method_id;
							if($o->return_method_id == 2){
								$return_days 							= $this->getDeliveryReturnTime('',$o->return_city_id);
								$return_days 							= $return_days->return_day;
							}
							// $m['date2'] = $date;
							$return_date  								= date_format(date_create($o->return_date),'Y-m-d');
							// $m['return_date'] = $return_date;
							// $m['return_days'] = $return_days;
							// $return_date 								= date('Y-m-d', strtotime('+'.$return_days.' days', strtotime($return_date)));
							// $m['return_date2'] = $return_date;
							// echo $o->modem_id.' - '.$o->return_date.' - '.$return_date.'-'.$date.'<br>';
							$m['return_date'] = $return_date;
							$m['curr_date_from'] = $date;
							if($flagGlobal == 1){
								$m['flag_type'] = 'global';
								$m['$return_date >= $date'] = $return_date >= $date;
								$m['modem_id - id'] = $o->modem_id.'-'.$m->id;
								//if modem found in order.
								if($m->category_modem_id == 1){			
									if($o->modem_id == $m->id && $return_date >= $date){
										$m['show'] = 0;
										// $deliverReturnTime 		= $this->getDeliveryReturnTime('', 154);
										// $deliveryDay 	= $this->getDeliveryReturnTime('', $delivery_city_id);
										// $returnDay 		= $this->getDeliveryReturnTime('', $delivery_city_id);

										// //get delivery date
										// if($deliveryDay != null){
										// 	$deliveryDate = date('Y-m-d', strtotime('-'.$deliveryDay->delivery_day.' days', strtotime($date)));
										// }else{
										// 	if($modem->user_id != null){
										// 		$deliveryDay = $this->getDeliveryReturnTime(6);
										// 		$deliveryDate = date('Y-m-d', strtotime('-'.$deliveryDay->delivery_day.' days', strtotime($date)));
										// 	}else{
										// 		$deliveryDay = $this->getDeliveryReturnTime(3);
										// 		$deliveryDate = date('Y-m-d', strtotime('-'.$deliveryDay->delivery_day.' days', strtotime($date)));
										// 	}
										// }	

										// //get return date
										// if($returnDay != null){
										// 	$returnDate = date('Y-m-d', strtotime('-'.$returnDay->delivery_day.' days', strtotime($date)));
										// }else{
										// 	if($modem->user_id != null){
										// 		$returnDay = $this->getDeliveryReturnTime(6);
										// 		$returnDate = date('Y-m-d', strtotime('-'.$returnDay->delivery_day.' days', strtotime($date)));
										// 	}else{
										// 		$returnDay = $this->getDeliveryReturnTime(3);
										// 		$returnDate = date('Y-m-d', strtotime('-'.$returnDay->delivery_day.' days', strtotime($date)));
										// 	}
										// }	
										break;
									}else{
										$m['show'] = 1;
									}
								}
							}

							// dd($flagAsia);
							if($flagOnlyGlobal == 0 && $flagAsia == 1){
								$m['flag_type'] = 'asia';
								if($m->category_modem_id == 2){
									if($o->modem_id == $m->id && $return_date >= $date){
										$m['show'] = 0;
										break;
									}else{
										$m['show'] = 1;
									}
								}
							}

							if($flagChinaRoaming == 1){
								$m['flag_type'] = 'ChinaRoaming';
								if($m->category_modem_id == 3){
									if($o->modem_id == $m->id && $return_date >= $date){
										$m['show'] = 0;
										break;
									}elseif($flagOnlyGlobal == 1){
										$m['show'] = 0;
										break;
									}else{
										$m['show'] = 1;
									}
								}
							}

							if($flagDaypass == 1){
								$m['flag_type'] = 'Daypass';
								if($m->category_modem_id == 4){
									if($o->modem_id == $m->id && $return_date >= $date){
										$m['show'] = 0;
										break;
									}else{
										$m['show'] = 1;
									}
								}
							}

						}
					}else{
						if($flagGlobal == 1){
							//if modem found in order.
							if($m->category_modem_id == 1){							
								// if($o->modem_id == $m->id){
								// 	$m['show'] = 0;	
								// }else{
									$m['show'] = 1;
								// }
							}
						}

						// dd($flagAsia);
						if($flagOnlyGlobal == 0 && $flagAsia == 1){
							if($m->category_modem_id == 2){
								// if($o->modem_id == $m->id){
								// 	$m['show'] = 0;
								// }else{
									$m['show'] = 1;
								// }
							}
						}

						if($flagChinaRoaming == 1){
							if($m->category_modem_id == 3){
								// if($o->modem_id == $m->id){
								// 	$m['show'] = 0;
								// }elseif($flagOnlyGlobal == 1){
								// 	$m['show'] = 0;
								// }else{
								// 	$m['show'] = 1;
								// }

								if($flagOnlyGlobal == 1){
									$m['show'] = 0;
									break;
									break;
								}else{
									$m['show'] = 1;
								}
							}
						}

						if($flagDaypass == 1){
							if($m->category_modem_id == 4){
								// if($o->modem_id == $m->id){
								// 	$m['show'] = 0;
								// }else{
									$m['show'] = 1;
								// }
							}
						}
					}
				}
			// }
			return json_encode($modem);
		}else{
			return "Please select country";
		}
	}
}
