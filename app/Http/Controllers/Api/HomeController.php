<?php namespace digipos\Http\Controllers\Api;

use Hash;
use JWTAuth;
use DB;
use DateTime;
use Input;
use Illuminate\Http\Request;
use digipos\models\User;
use digipos\models\Config;
use digipos\models\Booking;

use digipos\Libraries\Email;
use App\Mail\MailOrder;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller {

	public function __construct(){
		parent::__construct();

		// $this->distance_from_location = 3; //in kilometer
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function home(request $request){
		// dd($request);
		// $phone = $request->header('phone');
		// $token = $request->header('token');
		$latitude = $request->latitude;
		$longitude = $request->longitude;
		$church_id = $request->church_id;

		// dd($church_id);

		//check token valid
		// $check_token = $this->check_token($phone, $token);

		// if($check_token['status_code'] != 1){
		// 	return response()->json($check_token);
		// }
		// $user = User::where('phone', $phone)->first();

		// $config = Config::where('config_type', 'both')->get();
		$article_url = $this->data['image_path'].'/article/';
		$event_url = $this->data['image_path'].'/event/';
		$channel_url = $this->data['image_path'].'/channel/';
		$date = date('Y-m-d');
		$article_banner = DB::table('article')->where([['status','y'],['status_banner','y']])->select('article.*',DB::raw("CONCAT('$article_url',image) as image_url"))->get();
		// $article = DB::table('article')->where([['status','y'],['status_banner','n']])->select('article.*',DB::raw("CONCAT('$article_url',image) as image_url"))->get();
		$url = "https://gbihmj.org/api/getArtikel";
		$article = json_decode($this->curl_get($url));

		//get near church
		$church = DB::table('church')->where('status','y')->get();
		$latitudeFrom = $latitude;
		$longitudeFrom = $longitude;

		$nearest_church_id = ""; 
		$nearest_church_distance;
		$arr_church = [];
		foreach($church as $key => $c){
			$latitudeTo = $c->latitude;
			$longitudeTo = $c->longitude;
			$distance = $this->haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo);
			$c->distance = $distance;
		}

		$church_2 = $church->sortBy('distance');
		foreach ($church_2 as $key => $c2) {
			$c2->name = strtoupper($c2->name);
			array_push($arr_church, $c2);
		}

		$selected_church = $arr_church[0]->id;
		// dd($selected_church, $church_id);
		($church_id == "" ? $church_id = $selected_church : $church_id);
		// dd($selected_church);
		
		$event = DB::table('event')
				->join('room', 'room.id', 'event.room_id')
				->join('church', 'church.id', 'room.church_id');
		
		if($church_id != ""){
			$event	=	$event->where('church.id', $church_id);
		}
		$event	=	$event->where('event.status','y')
				->whereDate('event.date', '>=', $date)
				->select('event.*', 'church.name as church_name', DB::raw("CONCAT('$event_url',image) as image_url"))
				->get();
		// dd($event);
		if(count($event) > 0){
			foreach($event as $e){
				$e->time = json_decode($e->time);
			}
		}

	
		// $channel = DB::table('channel')->where('channel.status','y')->select('channel.*', DB::raw("CONCAT('$channel_url',image) as image_url"))->get();
		$channel = DB::table('channel')->where('channel.status','y')->select('channel.*')->get();
		// $channel = DB::table('channel')->where('channel.status','y')->select('channel.*', DB::raw("CONCAT('https://img.youtube.com/vi/khdGTdOQ5Fc/sddefault.jpg') as image_url"))->get();
		foreach ($channel as $key => $ch) {
			$image_url = str_replace("hqdefault","mqdefault",$ch->image);
			// dd($image_url);
			$ch->image_url = $image_url;
		}
		// $nearest_church = DB::table('church')->get();

		$config = DB::table('config')->where('config_type', 'both')->get();

		$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Success', 'user' => $user, 'article_banner' => $article_banner, 'article' => $article, 'event' => $event, 'channel' => $channel, 'nearest_church' => $arr_church, 'config' => $config);
		// echo json_encode(['07.00','09.00']);
		return response()->json($res);
	}

    public function event(request $request, $id){
        // $phone = $request->header('phone');
		// $token = $request->header('token');
		$event_id = $id;
	

		//check token valid
		// $check_token = $this->check_token($phone, $token);
		// return $check_token['status_code'];
		// if($check_token['status_code'] != 1){
		// 	return response()->json($check_token);
		// }

		$event = DB::table('event')
				->join('room', 'room.id', 'event.room_id')
				->join('church', 'church.id', 'room.church_id')
				->where([['event.id',$event_id], ['event.status','y']])
				->select('event.*', 'church.id as church_id','church.name as church_name', 'room.room_name', 'room.seat_array')
				->first();

				echo($event);
		die();
		
		$church_url = $this->data['image_path'].'/church/';
		$church_images = DB::table('church_images')->where([['status', 'y'],['church_id', $event->church_id]])->select('church_images.*', DB::raw("CONCAT('$church_url',image) as image_url"))->get();
		// dd($church_images);
		$event->church_images = $church_images;
		$event->time = json_decode($event->time);
		$event->seat_array = json_decode($event->seat_array);
		// return json_decode($time);
		$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Success', 'event' => $event);

        return response()->json($res);
    }

	public function get_available_seat(request $request, $event_id, $time){
		$phone = $request->header('phone');
		$token = $request->header('token');

		//check token valid
		$check_token = $this->check_token($phone, $token);
		// return $check_token['status_code'];
		if($check_token['status_code'] != 1){
			return response()->json($check_token);
		}
		$user_id = $check_token['user_id'];

		$available_seat_byTime = count($this->get_availableSeat_2($event_id, $time, $user_id));

		$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Success', 'available_seat' => $available_seat_byTime);

		return response()->json($res);
	}

	public function booking(request $request){
        $phone = $request->header('phone');
		$token = $request->header('token');
		$event_id = $request->event_id;
		$time = $request->time;
		$booked_array = json_decode($request->booked_array);

		//check token valid
		$check_token = $this->check_token($phone, $token);
		if($check_token['status_code'] != 1){
			return response()->json($check_token);
		}

		// return $check_token;
		$user_id = $check_token['user_id'];
		$available_seat = $this->get_availableSeat_2($event_id, $time, $user_id);
		// return $available_seat;
		if(count($available_seat) >= count($booked_array)){
			foreach ($booked_array as $key => $ba) {
				$ba->seat_code = $available_seat[$key];
			}

			$date = date('Y-m-d');
			$event = DB::table('event')->where('id', $event_id)->whereDate('date', '>=', $date)->first();
			if($event){
				$time_now = strtotime(date('Y-m-d H:i:s'));
				$time_target = strtotime($event->date.' '.$time.':00');
				if($time_now <= $time_target){
					//create booking code
					$lastBooking = Booking::OrderBy('id', 'desc')->first();

					$y = date('y');
					$m = date('m');
					$d = date('d');
					$no = '10001';
					if($lastBooking != null){
						
						$booking_code = substr($lastBooking->booking_code, 7);
						$y2 = substr($booking_code, 0, 2);
						$m2 = substr($booking_code, 2, 2);
						$d2 = substr($booking_code, 4, 2);
						$no = substr($booking_code, 6, 5);
						if($y2 == $y && $m2 == $m && $d2 == $d){
							$no = (int)$no + 1;
						}else{
							$no = '10001';
						}
					}
					$bookingCode =  'BOOKING'.$y.$m.$d.$no;

					// return $bookingCode;

					//insert data booking
					$booking = new Booking;
					$booking->user_id = $user_id;
					$booking->time = $time;
					$booking->event_id = $event_id;
					$booking->booking_code = $bookingCode;
					$booking->booked_array = json_encode($booked_array);
					$booking->status = 'y';
					$booking->created_at =  date('Y-m-d H:i:s');
					$booking->created_by =  $user_id;
					$booking->save();

					$booking_id =  $booking->id;
					$event = DB::table('event')
							->join('room', 'room.id', 'event.room_id')
							->join('church', 'church.id', 'room.church_id')
							->join('booking', 'booking.event_id', 'event.id')
							->where('booking.id', $booking_id)
							->select('event.*', 'church.name as church_name', 'church.latitude', 'church.longitude', 'booking.booked_array', 'booking.id as booking_id', 'booking.booking_code as booking_code', 'booking.time as booking_time')
							->first();

					$event->time = json_decode($event->time);
					$event->booked_array = json_decode($event->booked_array);

					$url = 'https%3A%2F%2Fgbuweb.com/api/home/get_booking/'.$event->booking_id.'%2F';
					$event->qr_code = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=qr&chl='.$url.'&choe=UTF-8';
					
					$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Booking Success', 'booking' => $event);
				}else{
					$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Booking time expired !');
				}
			}else{
				$res = array('status' => 'invalid', 'status_code' => 2, 'message' => 'Event date expired, you cannot book this event !');
			}
		}else{
			$res = array('status' => 'invalid', 'status_code' => 2, 'message' => 'Total available seat, not enough !');
		}

		return response()->json($res);
	}

	public function get_booking($booking_id){
		$event = DB::table('event')
				->join('room', 'room.id', 'event.room_id')
				->join('church', 'church.id', 'room.church_id')
				->join('booking', 'booking.event_id', 'event.id')
				->where('booking.id', $booking_id)
				->select('event.*', 'church.name as church_name', 'church.latitude', 'church.longitude', 'booking.booked_array', 'booking.id as booking_id', 'booking.booking_code as booking_code', 'booking.time as booking_time')
				->first();

		$event->time = json_decode($event->time);
		// $event->seat_array = json_decode($event->seat_array);
		$event->booked_array = json_decode($event->booked_array);

		$url = 'https%3A%2F%2Fgbuweb.com/api/home/get_booking/'.$event->booking_id.'%2F';
		$event->qr_code = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=qr&chl='.$url.'&choe=UTF-8';
		
		if($event){
			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Get data booking Success', 'booking' => $event);
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Data booking not found !');
		}

		return response()->json($res);
	}

	public function get_events(request $request, $offset, $limit){
        $phone = $request->header('phone');
		$token = $request->header('token');
		$category_id = $request->category_id;

		//check token valid
		$check_token = $this->check_token($phone, $token);
		// return $check_token['status_code'];
		if($check_token['status_code'] != 1){
			return response()->json($check_token);
		}
		// return 'test';
		$event_url = $this->data['image_path'].'/event/';

		$event;
		// return $category_id;
		if($category_id == 'all'){
			$event = DB::table('event')
					->join('room', 'room.id', 'event.room_id')
					->join('church', 'church.id', 'room.church_id')
					->join('category', 'category.id', 'event.category_id')
					->where([['event.status','y'],['category.parent_id', 1]])
					->select('event.*', 'church.name as church_name', 'room.room_name', 'room.seat_array', 'category.category_name as category_name',DB::raw("CONCAT('$event_url',image) as image_url"))
					->offset($offset)->limit($limit)
					->get();
		}else{
			$event = DB::table('event')
					->join('room', 'room.id', 'event.room_id')
					->join('church', 'church.id', 'room.church_id')
					->join('category', 'category.id', 'event.category_id')
					->where([['event.status','y'],['category_id',$category_id]])
					->select('event.*', 'church.name as church_name', 'room.room_name', 'room.seat_array', 'category.category_name as category_name',DB::raw("CONCAT('$event_url',image) as image_url"))
					->get();
		}

		if($event){
			foreach($event as $e){
				$e->time = json_decode($e->time);
				$e->seat_array = json_decode($e->seat_array);
			}
			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Get data event success', 'event' => $event);
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Data event not found !');
		}

		return response()->json($res);
	}

	public function get_event_category(request $request){
        $phone = $request->header('phone');
		$token = $request->header('token');

		//check token valid
		$check_token = $this->check_token($phone, $token);
		// return $check_token['status_code'];
		if($check_token['status_code'] != 1){
			return response()->json($check_token);
		}
		// return 'test';
		$event = DB::table('category')
				->where([['status', 'y'],['parent_id', 1]])
				->select('category.*')
				->get();

		if($event){
			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Get data event success', 'event_category' => $event);
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Data event not found !');
		}

		return response()->json($res);
	}

	public function get_channels(request $request, $offset, $limit){
        $phone = $request->header('phone');
		$token = $request->header('token');

		//check token valid
		$check_token = $this->check_token($phone, $token);
		// return $check_token['status_code'];
		if($check_token['status_code'] != 1){
			return response()->json($check_token);
		}
		$channel_url = $this->data['image_path'].'/channel/';
		$channel = DB::table('channel')
				->where('channel.status','y')
				->select('channel.*')
				->offset($offset)->limit($limit)
				->select('channel.*', DB::raw("CONCAT('$channel_url',image) as image_url"))
				->get();

		if($channel){
			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Get data event success', 'channel' => $channel);
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Data event not found !');
		}

		return response()->json($res);
	}

	public function get_articles(request $request, $offset, $limit){
        $phone = $request->header('phone');
		$token = $request->header('token');

		//check token valid
		$check_token = $this->check_token($phone, $token);
		// return $check_token['status_code'];
		if($check_token['status_code'] != 1){
			return response()->json($check_token);
		}
		$article_url = $this->data['image_path'].'/article/';
		$article = DB::table('article')
				->where([['article.status','y'],['status_banner','n']])
				->select('article.*', DB::raw("CONCAT('$article_url',image) as image_url"))
				->offset($offset)->limit($limit)
				->get();

		if($article){
			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Get data event success', 'article' => $article);
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Data event not found !');
		}

		return response()->json($res);
	}

	public function config(request $request){
		$phone = $request->header('phone');
		$token = $request->header('token');

		//check token valid
		$check_token = $this->check_token($phone, $token);

		if($check_token['status_code'] != 1){
			return response()->json($check_token);
		}

		$config = Config::where('config_type', 'both')->get();

		if(count($config) > 0){
			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Get data config success', 'config' => $config);
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Data config not found !');
		}

		return response()->json($res);
	}

	public function test(){
		// $this->res['slideshow']	= Slideshow::where('status','y')->orderBy('order','asc')->get();
		// $user = User::where('status','y')->get();
		$user = DB::table('event')->where('id2', 3)->first();
		return $user->time;
		$time = json_decode($user->time);
		return $time;
		$this->res = [ "status" => "success",
						"status_code" => 1,
					   "message" => "Test Success",
					   "data" => $user
					];
		return response()->json($this->res);
	}

	public function get_youtubeVideoData(request $request){
		$url = $request->url_youtube;
		// return $url;
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://www.youtube.com/oembed?url='.$url.'&format=json',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_POSTFIELDS => array(),
		));

		$response = curl_exec($curl);
		

		curl_close($curl);

		//return success
		//{"messageId":3057239,"to":"+628999878046","status":"1","text":"Success","cost":220}
		return $response;
	}

	public function updateOrderStatus($order_status_id){
		if($order_status_id == 3){
			$date = date("Y-m-d H:i:s", strtotime('-24 hours'));
			$data = Order_hd::leftJoin('promo', 'promo.id', 'promo_id')->where([['orderhd.created_at', '<=', $date],['orderhd.order_status_id', 1]])->select('orderhd.*', 'promo.user_id as promo_user_id')->get();

			$arrUpdate = [];
			if(count($data) > 0){
				foreach ($data as $key => $v) {
					if($v['promo_user_id'] != null){
						$update = Order_hd::find($v['id']);
						$update->order_status_id = 3;
						$update->save();

						array_push($arrUpdate, $update);
					}	
				}

				if(count($arrUpdate) > 0){
					$return = [
						'code' 		=> 200,
						'message'	=> 'success',
						'data'		=> $arrUpdate
					];
				}else{
					$return = [
						'code' 		=> 200,
						'message'	=> 'success',
						'data'		=> $arrUpdate
					];
				}
			}else{
				$return = [
					'code' 		=> 200,
					'message'	=> 'success',
					'data'		=> $arrUpdate
				];
			}

			return json_encode($return);

		}
		
	}

	// function get_availableSeat($event_id, $time, $user_id){
	// 	$available_seat_byTime = [];
	// 	$event = DB::table('event')
	// 			->join('room', 'room.id', 'event.room_id')
	// 			->join('church', 'church.id', 'room.church_id')
	// 			->where([['event.id',$event_id], ['event.status','y']])
	// 			->select('event.*', 'church.name as church_name', 'room.room_name', 'room.seat_array')
	// 			->first();
	// 	// var_dump($event);
	// 	//get total available seat from status
	// 	$seat_array = json_decode($event->seat_array);
	// 	// return var_dump($seat_array[0][0]->status);
	// 	foreach ($seat_array as $key => $sa) {
	// 		foreach ($sa as $key2 => $sa2) {
	// 			if($sa2->status == 1){
	// 				array_push($available_seat_byTime, $sa2->seat_code);
	// 			}
	// 		}
	// 	}
	// 	// return $available_seat_byTime;
	// 	$booking = DB::table('booking')
	// 				->join('event', 'event.id', 'booking.event_id')
	// 				->where([['booking.status','y'],['booking.user_id',$user_id],['booking.event_id',$event_id]])
	// 				->select('booking.*')
	// 				->get();
		
	// 	if(count($booking) > 0){
	// 		/*
	// 			"booked_array": [{"no_kaj":"0001","name":"elim","seat_code":"A1"},{"no_kaj":"","name":"alfian","seat_code":"A5"}]
	// 		*/
	// 		foreach ($booking as $key => $b) {
	// 			if($b->time == $time){
	// 				$booked_array = json_decode($b->booked_array);
	// 				$total_seat = count($booked_array);
	// 				if($total_seat > 0){
	// 					foreach ($booked_array as $key2 => $ba) {
	// 						$index = array_search($ba->seat_code, $available_seat_byTime);
	// 						// echo $index;
	// 						if($index > -1){
	// 							\array_splice($available_seat_byTime, $index, 1);
	// 						}
	// 					}
	// 				}
	// 			}
	// 		}
	// 	}

	// 	return $available_seat_byTime;
	// }

	function get_availableSeat_2($event_id, $time, $user_id){
		$available_seat_byTime = [];
		$event = DB::table('event')
				->join('room', 'room.id', 'event.room_id')
				->join('church', 'church.id', 'room.church_id')
				->where([['event.id',$event_id], ['event.status','y']])
				->select('event.*', 'church.name as church_name', 'room.room_name', 'room.seat_array')
				->first();
		// var_dump($event);
		//get total available seat from status
		$seat_array = json_decode($event->seat_array);
		// return var_dump($seat_array[0][0]->status);
		foreach ($seat_array as $key => $sa) {
			if($sa->status == 1){
				array_push($available_seat_byTime, $sa->seat_code);
			}
		}
		// return $available_seat_byTime;
		$booking = DB::table('booking')
					->join('event', 'event.id', 'booking.event_id')
					->where([['booking.status','y'],['booking.user_id',$user_id],['booking.event_id',$event_id]])
					->select('booking.*')
					->get();
		
		if(count($booking) > 0){
			/*
				"booked_array": [{"no_kaj":"0001","name":"elim","seat_code":"A1"},{"no_kaj":"","name":"alfian","seat_code":"A5"}]
			*/
			foreach ($booking as $key => $b) {
				if($b->time == $time){
					$booked_array = json_decode($b->booked_array);
					$total_seat = count($booked_array);
					if($total_seat > 0){
						foreach ($booked_array as $key2 => $ba) {
							$index = array_search($ba->seat_code, $available_seat_byTime);
							// echo $index;
							if($index > -1){
								\array_splice($available_seat_byTime, $index, 1);
							}
						}
					}
				}
			}
		}

		return $available_seat_byTime;
	}

	//The default value is 6371000 meters so the result will be in [m] too
	function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000){
		// convert from degrees to radians
		$latFrom = deg2rad($latitudeFrom);
		$lonFrom = deg2rad($longitudeFrom);
		$latTo = deg2rad($latitudeTo);
		$lonTo = deg2rad($longitudeTo);
	  
		$latDelta = $latTo - $latFrom;
		$lonDelta = $lonTo - $lonFrom;
	  
		$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
		  cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
		return $angle * $earthRadius;
	}
	
	// function create_seats(column, row, interval){
	// 	for ($i=0; $i < column; $i++) { 
	// 		for ($j=0; $j < row; $j++) { 

	// 		}
	// 	}
	// }

	public function curl_get($url){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		// CURLOPT_URL => 'https://console.zenziva.net/wareguler/api/sendWA/',
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		// CURLOPT_POSTFIELDS => array('userkey' => '4ef7d9dca3f0','passkey' => 'fa47554820dac1d6107d96ef','to' => $phone,'message' => $msg),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		return $response;
	}

}
