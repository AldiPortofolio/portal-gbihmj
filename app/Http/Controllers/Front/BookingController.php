<?php namespace digipos\Http\Controllers\Front;

use digipos\models\Church;
use digipos\models\Room;
use digipos\models\Event;
use digipos\models\Booking;
use digipos\models\Config;
use digipos\Libraries\Email;
use digipos\Libraries\Alert;
use Carbon\Carbon;

use DB;
use FeedReader;
use Illuminate\Http\Request;
use DatePeriod;
use DateInterval;
use DateTime;
use PDF;

class BookingController extends ShukakuController {

	public function __construct(){
		parent::__construct();
		$this->data['header_info']	= 'Home';
		$this->menu 				= $this->data['path'][0];
		$this->data['menu'] 		= $this->menu;
		$this->data['path'] 		= 'booking';
		$this->data['search'] 		= '';

		$this->image_path 			= 'components/both/images/';
		$this->data['image_path'] 	= $this->image_path;

		$this->model 				= new Booking;
		$this->image_path 			= 'components/both/images/event/';
		$this->data['image_path'] 	= $this->image_path;
		$this->image_path2 			= 'components/both/images/web/';
		$this->data['image_path2'] 	= $this->image_path2;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function get_booking(request $request, $booking_id){
		// dd($booking_id);
		
		$event = DB::table('event')
					->join('room', 'room.id', 'event.room_id')
					->join('church', 'church.id', 'room.church_id')
					->join('booking', 'booking.event_id', 'event.id')
					->where('booking.id', $booking_id)
					->select('event.*', 'church.name as church_name', 'church.latitude', 'church.longitude', 'booking.booked_array', 'booking.id as booking_id', 'booking.booking_code as booking_code', 'booking.time as booking_time')
					->first();
		$this->data['curr_lang'] = 'en';
		$this->data['menus'] = [];
	    $this->data['data']  = $event;
		// dd($event);
		return $this->render_view('pages.pages.booking');
	}
}
