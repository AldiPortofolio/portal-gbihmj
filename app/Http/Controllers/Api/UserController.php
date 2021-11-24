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

class UserController extends Controller {

	public function __construct(){
		parent::__construct();

		$this->distance_from_location = 3; //in kilometer
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function get_bookings(request $request){
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
					->join('church', 'church.id', 'event.church_id')
					->where([['booking.status','y'],['booking.user_id', $user_id]])
					->whereDate('event.date', '>=', $date)
					->select('booking.*', 'event.title', 'event.sub_title', 'event.speaker', 'event.date', 'church.name')
					->get();

		if(count($booking) > 0){
			$res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Data booking found', 'booking' => $booking);
		}else{
			$res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'Data booking not found !');
		}
		
		return response()->json($res);
	}
}
