<?php namespace digipos\Http\Controllers\Admin;

use digipos\models\Config;
use digipos\models\Church;
use digipos\models\Room;
use digipos\models\Event;
use digipos\models\Category;
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

class EventController extends KyubiController {

	public function __construct()
	{
		parent::__construct();
		$this->middleware($this->auth_guard);
		$this->middleware($this->role_guard);
		$this->title 			= "Event";
		$this->data['title']	= $this->title;
		$this->root_link 		= "event";
		$this->model 			= new Event;

		$this->bulk_action			= true;
		$this->bulk_action_data 	= [6];
		$this->image_path 			= 'components/both/images/event/';
		$this->data['image_path'] 	= $this->image_path;
		$this->image_path2 			= 'components/both/images/web/';
		$this->data['image_path2'] 	= $this->image_path2;

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
		$this->field = [
            [
				'name' 		=> 'image',
				'label' 	=> 'Image',
                'type' 		=> 'image',
				'sorting' 	=> 'n',
				'search' 	=> 'text',
                'file_opt'  =>  ['path' => $this->image_path]
			],
			[
				'name' 		=> 'title',
				'label' 	=> 'Event Title',
				'sorting' 	=> 'y',
				'search' 	=> 'text'
			],
			[
				'name' 		=> 'church_name',
				'label' 	=> 'Church Name',
				'sorting' 	=> 'y',
				'search' 	=> 'text'
			],
			[
				'name' 		=> 'room_name',
				'label' 	=> 'Room Name',
				'sorting' 	=> 'y',
				'search' 	=> 'text'
			],
            [
				'name' 		=> 'speaker',
				'label' 	=> 'Speaker',
				'sorting' 	=> 'y',
				'search' 	=> 'text'
			],
            [
				'name' 		=> 'date',
				'label' 	=> 'Date',
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
                        ->join('room', 'room.id', 'event.room_id')
                        ->join('category', 'category.id', 'event.category_id')
                        ->join('church', 'church.id', 'room.church_id')
                        ->select('event.*', 'room.room_name', 'category.category_name', 'church.name as church_name');
		// dd($this->model->get());

		// $this->model = $this->model->select(DB::raw('te_category_product.*, (SELECT t.category_product_name FROM te_category_product t WHERE t.id=te_category_product.parent_id) AS parent_name'));
		return $this->build('index');
	}

	public function field_create(){
		$field = [
			// [
			// 	'name' => 'room_name',
			// 	'label' => 'Room Name',
			// 	'type' => 'text',
			// 	'attribute' => 'required',
			// 	'validation' => 'required',
			// 	'tab' => 'general',
			// ]
		];
		return $field;
	}

	public function create(){
		
		$this->data['title'] 						= "Create ".$this->title;
		$this->data['church'] = $this->build_array(Church::where('status','y')->get(),'id','name');
		$this->data['category_event'] = $this->build_array(Category::where([['status','y'],['parent_id', 1]])->get(),'id','category_name');
		$this->data['room'] = Room::where('status','y')->get();
		$this->data['sample_seat_array'] = '[[{"seat_code":"A1","status":1},{"seat_code":"A2","status":0},{"seat_code":"A3","status":0},{"seat_code":"A4","status":0},{"seat_code":"A5","status":1}],[{"seat_code":"B1","status":1},{"seat_code":"B2","status":0},{"seat_code":"B3","status":0},{"seat_code":"B4","status":0},{"seat_code":"B5","status":1}]]';

		return $this->render_view('pages.event.create');
		// $this->field = $this->field_create();
		// return $this->build('create');
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

		// $this->model->church_id				= $request->church_id;
		$this->model->room_id				= $request->room_id;
		$this->model->category_id		= $request->category_event;
		$this->model->title		= $request->title;
		$this->model->sub_title		= $request->sub_title;
		$this->model->content		= $request->content;
		$this->model->speaker		= $request->speaker;
		$this->model->date		= date_format(date_create($request->event_date),'Y-m-d');
		$event_time = $request->event_time;
		$status_time = $request->event_timeStatus;
        $arr = [];
        // dd($status_time);
        foreach ($event_time as $key => $value) {
            $object = array('time' => $value, 'status' => $status_time[$key]); 
            array_push($arr, $object);
        }
        // dd($arr);
        $this->model->time = json_encode($arr);
		// dd($request->hasFile('image'));
		if ($request->hasFile('image')){
        	// File::delete($this->image_path.$this->model->image);
			$image_path = $this->data['image_path'];
			$data = [
						'name' => 'image',
						'file_opt' => ['path' => $image_path.'/']
					];
			$image = $this->build_image($data);
			$this->model->image = $image;
		}

		if($request->input('remove-single-image-images') == 'y'){
			File::delete($this->image_path.$this->model->image);
			$this->model->image = '';
		}
		
		$this->model->created_by			= auth()->guard($this->guard)->user()->id;
		$this->model->created_at			= date('Y-m-d H:i:s');

		// dd($this->model->toArray());
		$this->model->save();

		Alert::success('Successfully add Event');
		return redirect()->to($this->data['path']);
	}

	public function edit($id){
		$date = date('Y-m-d');
		$booked_event = Event::join('booking', 'booking.event_id', 'event.id')->where([['event.id', $id],['booking.status', 'y']])->whereDate('event.date', '>=', $date)->select('event.*', 'booking.id as booking_id', 'booking.event_id as book_event_id')->get();
		if(count($booked_event) > 0){
			Alert::fail('You cannot edit this event, because this event already booked');
			return redirect()->back();
		}
		$this->model 	= $this->model
                        ->join('room', 'room.id', 'event.room_id')
                        ->join('church', 'church.id', 'room.church_id')
                        ->join('category', 'category.id', 'event.category_id')
                        ->select('event.*', 'room.room_name', 'room.total_seat', 'room.seat_array', 'category.category_name', 'church.id as church_id', 'church.name as church_name')
                        ->find($id);
		// dd($this->model);
		// //get time event
		// $time_event = json_decode($this->model->time);

		// //get arr available seat by room
		// $seat_array = json_decode($this->model->seat_array);
		// $available_seat = [];
		// // return var_dump($seat_array[0][0]->status);
		// foreach ($seat_array as $key => $sa) {
		// 	if($sa->status == 1){
		// 		array_push($available_seat, $sa->seat_code);
		// 	}
		// }
		// $total_available_seat = count($available_seat);
		// // dd($available_seat);

		// //get booked data
		// // $booked = Booking::where([['booking.event_id', $id],['booking.time', $tqs-.time]])->select('booked_array')->get();

		// $arr_time = [];
		// foreach ($time_event as $key => $tqs) {
		// 	$total_booked_seat = 0;
		// 	$tqs->total_available_seat = $total_available_seat;
		// 	$booked = Booking::where([['booking.event_id', $id],['booking.time', $tqs->time], ['booking.status','y']])->select('booked_array')->get();
		// 	if(count($booked) > 0){
		// 		foreach($booked as $key2 => $b){
		// 			$booked_array = json_decode($b->booked_array);
		// 			$total_booked = count($booked_array);
		// 			$total_booked_seat += $total_booked;
		// 		}
		// 	}
		// 	$tqs->total_booked_seat = $total_booked_seat;
		// 	// var_dump(json_encode($booked_seat));
		// 	// array_push($arr_time, $tqs->time);
		// }
		// $this->data['$time_event'] = $time_event;
        $this->data['data'] = $this->model;
        $this->data['church'] = $this->build_array(Church::where('status','y')->get(),'id','name');
		$this->data['category_event'] = $this->build_array(Category::where([['status','y'],['parent_id', 1]])->get(),'id','category_name');
		$this->data['room'] = Room::where('status','y')->get();

		// dd($this->data['modem_log']);
		return $this->render_view('pages.event.edit');
	}

	public function update(Request $request, $id){
		// $this->validate($request,[
		// 	'name' 		=> 'required|unique:modem,modem_name,'.$id,
		// ],[
  //           'name.required' => 'Category Modem Name is Required.',
  //           'name.unique' 	=> 'Category Modem Name has already been taken.',
  //       ]);
  		$date = date('Y-m-d');
		$booked_event = Event::join('booking', 'booking.event_id', 'event.id')->where([['event.id', $id],['booking.status', 'y']])->whereDate('event.date', '>=', $date)->select('event.id', 'booking.id')->get();
		if(count($booked_event) > 0){
			Alert::fail('You cannot edit this event, because this event already booked');
			return redirect()->to($this->data['path']);
		}

		$this->model 						= $this->model->find($id);
		$this->model->room_id				= $request->room_id;
		$this->model->category_id		= $request->category_event;
		$this->model->title		= $request->title;
		$this->model->sub_title		= $request->sub_title;
		$this->model->content		= $request->content;
		$this->model->speaker		= $request->speaker;
		$this->model->date		= date_format(date_create($request->event_date),'Y-m-d');
		$event_time = $request->event_time;
		$status_time = $request->event_timeStatus;
        $arr = [];
        // dd($status_time);
        foreach ($event_time as $key => $value) {
            $object = array('time' => $value, 'status' => $status_time[$key]); 
            array_push($arr, $object);
        }
        // dd($arr);
        $this->model->time = json_encode($arr);
		// dd($request->hasFile('image'));
		if ($request->hasFile('image')){
        	// File::delete($this->image_path.$this->model->image);
			$image_path = $this->data['image_path'];
			$data = [
						'name' => 'image',
						'file_opt' => ['path' => $image_path.'/']
					];
			$image = $this->build_image($data);
			$this->model->image = $image;
		}

		if($request->input('remove-single-image-images') == 'y'){
			File::delete($this->image_path.$this->model->image);
			$this->model->image = '';
		}
		
		$this->model->created_by			= auth()->guard($this->guard)->user()->id;
		$this->model->created_at			= date('Y-m-d H:i:s');

		// dd($this->model->toArray());
		$this->model->save();
		
		Alert::success('Successfully edit Event');
		return redirect()->to($this->data['path']);
	}

	public function show($id){
		$this->model 	= $this->model
                        ->join('room', 'room.id', 'event.room_id')
                        ->join('church', 'church.id', 'room.church_id')
                        ->join('category', 'category.id', 'event.category_id')
                        ->select('event.*', 'room.room_name', 'room.total_seat', 'room.seat_array', 'category.category_name', 'church.id as church_id', 'church.name as church_name')
                        ->find($id);
		// dd($this->model);
		//get time event
		$time_event = json_decode($this->model->time);

		//get arr available seat by room
		$seat_array = json_decode($this->model->seat_array);
		$available_seat = [];
		// return var_dump($seat_array[0][0]->status);
		foreach ($seat_array as $key => $sa) {
			if($sa->status == 1){
				array_push($available_seat, $sa->seat_code);
			}
		}
		$total_available_seat = count($available_seat);
		// dd($available_seat);

		//get booked data
		// $booked = Booking::where([['booking.event_id', $id],['booking.time', $tqs-.time]])->select('booked_array')->get();

		$arr_time = [];
		foreach ($time_event as $key => $tqs) {
			$total_booked_seat = 0;
			$tqs->total_available_seat = $total_available_seat;
			$booked = Booking::where([['booking.event_id', $id],['booking.time', $tqs->time], ['booking.status','y']])->select('booked_array')->get();
			if(count($booked) > 0){
				foreach($booked as $key2 => $b){
					$booked_array = json_decode($b->booked_array);
					$total_booked = count($booked_array);
					$total_booked_seat += $total_booked;
				}
			}
			$tqs->total_booked_seat = $total_booked_seat;
			// var_dump(json_encode($booked_seat));
			// array_push($arr_time, $tqs->time);
		}
		$this->data['time_event'] = json_encode($time_event);
        $this->data['data'] = $this->model;
        $this->data['church'] = $this->build_array(Church::where('status','y')->get(),'id','name');
		$this->data['category_event'] = $this->build_array(Category::where([['status','y'],['parent_id', 1]])->get(),'id','category_name');
		$this->data['room'] = Room::where('status','y')->get();

		return $this->render_view('pages.event.view');
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
