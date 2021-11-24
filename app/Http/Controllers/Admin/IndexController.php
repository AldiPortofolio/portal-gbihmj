<?php namespace digipos\Http\Controllers\Admin;
use digipos\Libraries\Email;

use digipos\models\User;
use digipos\models\Church;
use digipos\models\Room;
use digipos\models\Event;
use digipos\models\Booking;
use digipos\models\Article;
use digipos\models\Music;

use DB;

class IndexController extends Controller {

	public function __construct(){
		parent::__construct();
		$this->middleware($this->auth_guard); 
		$this->middleware($this->role_guard); 
		$this->data['title'] 	= "Dashboard";
		// $this->merchant 		= new Msmerchant;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		$date = date('Y-m-d');
		$oneWeekAgo = date('Y-m-d', strtotime('-7 days'));
		$oneMonthAgo = date('Y-m-d', strtotime('-30 days'));
		$this->data['church']	= Church::where('status', 'y')->select(DB::raw('COUNT(*) as total'))->first();
		$this->data['user'] 	= User::where('status', 'y')->select(DB::raw('COUNT(*) as total'))->first();
		$this->data['event'] 	= Event::where('status', 'y')->select(DB::raw('COUNT(*) as total'))->first();
		$this->data['seat'] 	= Church::join('room', 'room.church_id', 'church.id')->where([['room.status', 'y'],['church.status', 'y']])->select(DB::raw('SUM(room.total_seat) as total_seat'))->first();
		$this->data['room'] 	= Room::join('church', 'church.id', 'room.church_id')->where([['room.status', 'y'],['church.status', 'y']])->select(DB::raw('COUNT(*) as total_room'))->first();
		$this->data['article'] 	= Article::where('status', 'y')->select(DB::raw('COUNT(*) as total'))->first();
		$this->data['music'] 	= Music::where('status', 'y')->select(DB::raw('COUNT(*) as total'))->first();
		// dd($oneMonthAgo);
		$this->data['booking_oneMonthAgo'] 	= Booking::where('status', 'y')->whereDate('created_at', '>', $oneMonthAgo)->select(DB::raw('COUNT(*) as total'))->first();
		$this->data['booking_oneWeekAgo'] 	= Booking::where('status', 'y')->whereDate('created_at', '>', $oneWeekAgo)->select(DB::raw('COUNT(*) as total_booking'), 'booking.*')->groupBy('created_at')->get();
		// dd($this->data['booking_oneWeekAgo']);
		// // dd($this->data['top_order_success']);
		// $this->data['top_mitra_rating']					=	Mitra::select('mitra.*', 'mitradt.total_rating', 'mitradt.total_user_rating')->join('mitradt','mitra.id','mitradt.mitra_id')->orderBy('mitradt.total_rating', 'desc')->orderBy('mitradt.total_user_rating','desc')->limit(5)->get();
		// // dd($this->data['top_mitra_rating']);

		// $this->data['top_customer_order_success']		= Customer::select('customer.*', 'orderhd.customer_id', DB::raw('COUNT(*) as count'))->join('orderhd','orderhd.customer_id','customer.id')->where('order_status', '6')->groupBy('customer.id')->get();
		// // dd($this->data['top_customer_order_success']);
		
		$country_id = 1;
		// return 'test';
		// $order_success				=	Order_hd::select('orderhd.*')->leftjoin('orderdt', 'orderhd.id', 'orderdt.orderhd_id')->where('orderhd.order_status_id', '6')->limit(5)->get();

		$arr_temp = [];
		// foreach ($order_success as $key => $val) {
		// 	$country = json_decode($val->country_id);

		// 	if(count($country) > 0){
		// 		foreach ($country as $key2 => $val2) {
		// 			// var_dump(count($arr_temp));
		// 			if(count($arr_temp) > 0){
		// 				$flagFound = 0;
		// 				$j = 0;
		// 				// var_dump($arr_temp);
		// 				foreach ($arr_temp as $key3 => $val3) {
		// 					if($val3['country_id'] == $val2){
		// 						// var_dump($val3['country_id'].' - '.$val3['count'].' --'.$val2);
		// 						$arr_temp[$j]['count'] += 1;
		// 						$flagFound = 1;
		// 						// var_dump($val3);
		// 						// var_dump($arr_temp);
		// 					}
		// 					$j++;
		// 				}

		// 				if($flagFound == 0){
		// 					$country = DB::table('country')->find($val2);
		// 					$temp_arr = [
		// 						"country_id" => $val2,
		// 						"country_name" => $country->country_name,
		// 						"count" => 1
		// 					];
		// 					array_push($arr_temp, $temp_arr);
		// 					// $arr_temp = asort($arr_temp);
		// 				}
		// 			}else{
		// 				$country = DB::table('country')->find($val2);
		// 				$temp_arr = [
		// 					"country_id" => $val2,
		// 					"country_name" => $country->country_name,
		// 					"count" => 1
		// 				];
		// 				array_push($arr_temp, $temp_arr);
		// 				// $arr_temp = asort($arr_temp);
		// 			}
					
		// 		}
		// 	}
		// }
		// dd($arr_temp);
		$this->data['top_country_order_success'] = [];
		// dd($this->data['top_country_order_success']);

		// check modem rent pass day
		// $this->checkOrderPassDay();
		return $this->render_view('pages.index');
	}

}
