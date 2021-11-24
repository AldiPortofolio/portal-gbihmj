<?php namespace digipos\Http\Controllers\Api;

use Hash;
use JWTAuth;
use DB;
use DateTime;
use Illuminate\Http\Request;
use digipos\models\User;
use digipos\models\Config;
use digipos\models\Booking;

use digipos\Libraries\Email;
use App\Mail\MailOrder;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller {

	public function __construct(){
		parent::__construct();

		$this->distance_from_location = 3; //in kilometer
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function get_bookings(request $request, $offset, $limit){
		$phone = $request->header('phone');
		$token = $request->header('token');
		
		//check token valid
		$check_token = $this->check_token($phone, $token);
		// return $check_token;
		if($check_token['status_code'] != 1){
			return response()->json($check_token);
		}
		
		$user_id = $check_token['user_id'];
		$date = date('Y-m-d');
		$booking = Booking::join('event', 'event.id', 'booking.event_id')
					->join('room', 'room.id', 'event.room_id')
					->join('church', 'church.id', 'room.church_id')
					->where([['booking.status','y'],['booking.user_id', $user_id]])
					->whereDate('event.date', '>=', $date)
					->select('booking.*', 'booking.time as booking_time', 'event.title', 'event.sub_title', 'event.speaker', 'event.date', 'church.name')
					->offset($offset)->limit($limit)
					->get();
		
		if(count($booking) > 0){
			foreach($booking as $b){
				$b->booked_array = json_decode($b->booked_array);
			}
			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Data booking found', 'booking' => $booking);
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Data booking not found !');
		}
		
		return response()->json($res);
	}

	public function get_past_bookings(request $request){
		$phone = $request->header('phone');
		$token = $request->header('token');

		//check token valid
		$check_token = $this->check_token($phone, $token);
		// return $check_token;
		if($check_token['status_code'] != 1){
			return response()->json($check_token);
		}
		
		$user_id = $check_token['user_id'];
		$date = date('Y-m-d');
		$booking = Booking::join('event', 'event.id', 'booking.event_id')
					->join('room', 'room.id', 'event.room_id')
					->join('church', 'church.id', 'room.church_id')
					->where([['booking.status','y'],['booking.user_id', $user_id]])
					->where('event.date', '>', DB::raw('DATE_SUB(NOW(), INTERVAL 1 MONTH)'))
					// ->where('event.date', '>', $date)
					->select('booking.*', 'booking.time as booking_time', 'event.title', 'event.sub_title', 'event.speaker', 'event.date', 'church.name')
					->get();

		if(count($booking) > 0){
			foreach($booking as $b){
				$b->booked_array = json_decode($b->booked_array);
			}
			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Data booking found', 'booking' => $booking);
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Data booking not found !');
		}
		
		return response()->json($res);
	}

	public function edit_booking(request $request){
		$booking_id = $request->booking_id;
		$booked_array = json_decode($request->booked_array);

		$phone = $request->header('phone');
		$token = $request->header('token');

		//check token valid
		$check_token = $this->check_token($phone, $token);
		// return $check_token;
		if($check_token['status_code'] != 1){
			return response()->json($check_token);
		}
		$user_id = $check_token['user_id'];

		$booking = Booking::find($booking_id);
		
		//get total seat, booked before
		$booked_array_2 = json_decode($booking->booked_array);
		$total_seat_before = count($booked_array_2);
		$event_id = $booking->event_id;
		$time = $booking->time;

		$date = date('Y-m-d');
		$event = DB::table('event')->where('id', $event_id)->whereDate('date', '>=', $date)->first();
		// return $time;
		if($event){
			$flag_update = 0;
			if($total_seat_before >= count($booked_array)){ //if total seat booked before <= total seat will be booking
				//update booked_array dari paramater
				foreach ($booked_array as $key => $ba) {
					$ba->seat_code = $booked_array_2[$key]->seat_code;
				}

				$booking = Booking::find($booking_id);
				$booking->booked_array = json_encode($booked_array);

				$booking->save();

				$flag_update = 1;
			}else{
				$selisih = count($booked_array) - $total_seat_before;
				$available_seat = $this->get_availableSeat_2($event_id, $time, $user_id);
				if($selisih <= count($available_seat)){
					//pindahkan seat sebelumnya ke seat yang akan dibooking
					foreach ($booked_array_2 as $key => $ba2) {
						$booked_array[$key]->seat_code = $ba2->seat_code;
					}

					//isi sisa seat yang akan dibooking yang belum terisi, dengan available seat 
					$start_index = count($booked_array_2);
					for ($i=0; $i < $selisih; $i++) { 
						$booked_array[($start_index+$i)]->seat_code = $available_seat[$i];
					}

					$booking->booked_array = json_encode($booked_array);
					$booking->save();

					$flag_update = 1;
				}else{
					$flag_update = 0;

					$res = array('status' => 'invalid', 'status_code' => 2, 'message' => 'Total available seat, not enough !');
				}
			}

			if($flag_update == 1){
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
			}
		}else{
			$res = array('status' => 'invalid', 'status_code' => 2, 'message' => 'Event date expired, you cannot book this event !');
		}
		return response()->json($res);
	}

	
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
}
