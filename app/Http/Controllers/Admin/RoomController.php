<?php namespace digipos\Http\Controllers\Admin;

use digipos\models\Config;
use digipos\models\Church;
use digipos\models\Room;
use digipos\models\Event;

use Validator;
use Auth;
use Hash;
use DB;
use digipos\Libraries\Alert;
use Illuminate\Http\Request;
use digipos\Libraries\Email;
use Carbon\Carbon;
use File;

class RoomController extends KyubiController {

	public function __construct()
	{
		parent::__construct();
		$this->middleware($this->auth_guard);
		$this->middleware($this->role_guard);
		$this->title 			= "Room";
		$this->data['title']	= $this->title;
		$this->root_link 		= "room";
		$this->model 			= new Room;

		$this->bulk_action			= true;
		$this->bulk_action_data 	= [3];
		$this->image_path 			= 'components/both/images/room/';
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
				'name' 		=> 'name',
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
				'name' 		=> 'total_seat',
				'label' 	=> 'Total Seat',
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

		$this->model = $this->model->join('church', 'church.id', 'room.church_id')->select('room.*', 'church.name');
		// dd($this->model->get());

		// $this->model = $this->model->select(DB::raw('te_category_product.*, (SELECT t.category_product_name FROM te_category_product t WHERE t.id=te_category_product.parent_id) AS parent_name'));
		return $this->build('index');
	}

	public function field_create(){
		$field = [
			[
				'name' => 'room_name',
				'label' => 'Room Name',
				'type' => 'text',
				'attribute' => 'required',
				'validation' => 'required',
				'tab' => 'general',
			]
		];
		return $field;
	}

	public function create(){
		
		$this->data['title'] 						= "Create Room";
		$this->data['church'] = $this->build_array(Church::where('status','y')->get(),'id','name');
		$this->data['sample_seat_array'] = '[[{"seat_code":"A1","status":1},{"seat_code":"A2","status":0},{"seat_code":"A3","status":0},{"seat_code":"A4","status":0},{"seat_code":"A5","status":1}],[{"seat_code":"B1","status":1},{"seat_code":"B2","status":0},{"seat_code":"B3","status":0},{"seat_code":"B4","status":0},{"seat_code":"B5","status":1}]]';

		return $this->render_view('pages.room.create');
		// $this->field = $this->field_create();
		// return $this->build('create');
	}

	public function store(Request $request){
		$this->validate($request,[
			'seat_code' 		=> 'required',
		],[
            'seat_code.required' => 'Table seat belum dibuat!',
        ]);

		$this->model->church_id				= $request->church_id;
		$this->model->room_name				= $request->room_name;
		$this->model->total_seat			= $request->total_seat;
		// dd($request->seat_code);
		
		if(count($request->seat_code) > 0){
			$arr = [];
			$seat_status = $request->seat_status_2;
			// dd($seat_status);
			foreach ($request->seat_code as $key => $sc) {
				$object = array('seat_code' => $sc, 'status' => $seat_status[$key]); 
				array_push($arr, $object);
			}

			$this->model->seat_array = json_encode($arr);
			$this->model->total_seat = count($request->seat_code);
		}

		// dd($request->hasFile('denah_image'));
		if ($request->hasFile('denah_image')){
        	// File::delete($this->image_path.$this->model->denah_image);
			$image_path = $this->data['image_path'];
			$data = [
						'name' => 'denah_image',
						'file_opt' => ['path' => $image_path.'/']
					];
			$image = $this->build_image($data);
			$this->model->denah_image = $image;
		}

		if($request->input('remove-single-image-images') == 'y'){
			File::delete($this->image_path.$this->model->denah_image);
			$this->model->denah_image = '';
		}
		
		$this->model->created_by			= auth()->guard($this->guard)->user()->id;
		$this->model->created_at			= date('Y-m-d H:i:s');

		// dd($this->model->toArray());
		$this->model->save();

		Alert::success('Successfully add Room');
		return redirect()->to($this->data['path']);
	}

	public function edit($id){
		$this->model 					= $this->model->join('church', 'church.id', 'room.church_id')->select('room.*')->find($id);
		// dd($this->model);
		$this->data['title'] 			= "Edit Room ".$this->model->room_name;
		$this->data['church'] 			= $this->build_array(Church::where('status','y')->get(),'id','name');
		$this->data['data']  			= $this->model;
		$this->data['sample_seat_array'] = '[[{"seat_code":"A1","status":1},{"seat_code":"A2","status":0},{"seat_code":"A3","status":0},{"seat_code":"A4","status":0},{"seat_code":"A5","status":1}],[{"seat_code":"B1","status":1},{"seat_code":"B2","status":0},{"seat_code":"B3","status":0},{"seat_code":"B4","status":0},{"seat_code":"B5","status":1}]]';

		// dd($this->data['modem_log']);
		return $this->render_view('pages.room.edit');
	}

	public function update(Request $request, $id){
		$this->validate($request,[
			'seat_code' 		=> 'required',
		],[
            'seat_code.required' => 'Table seat belum dibuat!',
        ]);

		$this->model 						= $this->model->find($id);
		$this->model->church_id				= $request->church_id;
		$this->model->room_name				= $request->room_name;
		$this->model->total_seat			= $request->total_seat;
		
		if(count($request->seat_code) > 0){
			$arr = [];
			$seat_status = $request->seat_status_2;
			// dd($seat_status);
			foreach ($request->seat_code as $key => $sc) {
				$object = array('seat_code' => $sc, 'status' => $seat_status[$key]); 
				array_push($arr, $object);
			}

			$this->model->seat_array = json_encode($arr);
			$this->model->total_seat = count($request->seat_code);
		}
	
		// dd($request->hasFile('denah_image'));
		if ($request->hasFile('denah_image')){
			if($this->model->denah_image != null){
        		File::delete($this->image_path.$this->model->denah_image);
			}
			$image_path = $this->data['image_path'];
			$data = [
						'name' => 'denah_image',
						'file_opt' => ['path' => $image_path.'/']
					];
			$image = $this->build_image($data);
			$this->model->denah_image = $image;
		}

		if($request->input('remove-single-image-images') == 'y'){
			File::delete($this->image_path.$this->model->denah_image);
			$this->model->denah_image = '';
		}

		$this->model->upd_by = auth()->guard($this->guard)->user()->id;

		if($request->input('remove-single-image-image') == 'y'){
			if($this->model->image != NULL){
				// dd($this->image_path.$this->model->id.'/'.$this->model->image);
				File::delete($this->image_path.$this->model->id.'/'.$this->model->image);
				$this->model->image = '';
			}
		}

		if($request->hasFile('image')){
        	// File::delete($path.$user->images);
			$data = [
						'name' => 'image',
						'file_opt' => ['path' => $this->image_path.$this->model->id.'/']
					];
			$image = $this->build_image($data);
			$this->model->image = $image;
		}

		// dd($this->model->toArray());
		$this->model->save();

		if($request->complain){
			$this->model->complain = $request->complain;
		}
		
		Alert::success('Successfully edit Product');
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
