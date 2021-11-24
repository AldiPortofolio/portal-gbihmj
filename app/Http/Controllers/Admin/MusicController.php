<?php namespace digipos\Http\Controllers\Admin;

use digipos\models\Config;
use digipos\models\Music;

use Validator;
use Auth;
use Hash;
use DB;
use digipos\Libraries\Alert;
use Illuminate\Http\Request;
use digipos\Libraries\Email;
use Carbon\Carbon;
use File;

class MusicController extends KyubiController {

	public function __construct()
	{
		parent::__construct();
		$this->middleware($this->auth_guard);
		$this->middleware($this->role_guard);
		$this->title 			= "Music";
		$this->data['title']	= $this->title;
		$this->root_link 		= "music";
		$this->model 			= new Music;

		$this->bulk_action			= true;
		$this->bulk_action_data 	= [2];

        $this->image_path 			= 'components/both/images/music/';
		$this->data['image_path'] 	= $this->image_path;
		$this->image_path2 			= 'components/both/images/';
		$this->data['image_path2'] 	= $this->image_path2;

		$this->audio_path 			= 'components/both/audio/';
		$this->data['audio_path'] 	= $this->audio_path;
		$this->audio_path2 			= 'components/both/audio/';
		$this->data['audio_path2'] 	= $this->audio_path2;

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
				'label' 	=> 'Title',
				'sorting' 	=> 'n',
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

		// $this->model = $this->model;
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
		
		$this->data['title'] 						= "Create Music";
		// $this->data['church'] = $this->build_array(Church::where('status','y')->get(),'id','name');
		// $this->data['sample_seat_array'] = '[[{"seat_code":"A1","status":1},{"seat_code":"A2","status":0},{"seat_code":"A3","status":0},{"seat_code":"A4","status":0},{"seat_code":"A5","status":1}],[{"seat_code":"B1","status":1},{"seat_code":"B2","status":0},{"seat_code":"B3","status":0},{"seat_code":"B4","status":0},{"seat_code":"B5","status":1}]]';

		return $this->render_view('pages.music.create');
		// $this->field = $this->field_create();
		// return $this->build('create');
	}

	public function store(Request $request){
		// $this->validate($request,[
		// 	'file' 			=> 'required'
		// ],
		// [
        //     'file.required' => 'Warta file is Required.'
        // ]
    	// );

		$this->model->title				= $request->title;
	
        // dd($request->hasFile('logo'));
        //image music
		if ($request->hasFile('image')){
        	// File::delete($this->image_path.$this->model->image);
			$image_path = $this->data['image_path'];
			$data = [
						'name' => 'image',
						'file_opt' => ['path' => $image_path.'/']
					];
			$image = $this->build_image($data);
			$this->model->image = $image;
		}else{
            Alert::fail('Music image required');
            return redirect()->back();
        }

		if($request->input('remove-single-image-image') == 'y'){
			File::delete($this->image_path.$this->model->image);
			$this->model->image = '';
		}
        
        //file music
        if ($request->hasFile('music_name')){
            $filename = $request->music_name->getClientOriginalName();
            $data_found = Music::where('music_name', $filename)->get();
            
            if(count($data_found) > 0){
                Alert::fail('music file already upload');
                return redirect()->back();
            }

        	// File::delete($this->image_path.$this->model->image);
			$image_path = $this->data['audio_path'];
			$data = [
						'name' => 'music_name',
						'file_opt' => ['path' => $image_path.'/']
					];
			$image = $this->build_image($data);
			$this->model->music_name = $image;
		}else{
            Alert::fail('Music file required');
            return redirect()->back();
        }

		if($request->input('remove-single-image-music_name') == 'y'){
			File::delete($this->audio_path.$this->model->music_name);
			// $this->model->file = '';
		}
		
		$this->model->created_by			= auth()->guard($this->guard)->user()->id;
		$this->model->created_at			= date('Y-m-d H:i:s');

		// dd($this->model->toArray());
		$this->model->save();

		Alert::success('Successfully add Music');
		return redirect()->to($this->data['path']);
	}

	public function edit($id){
		$this->model 					= $this->model->find($id);
		// dd($this->model);
		$this->data['title'] 			= "Edit Music ".$this->model->id;
		// $this->data['church'] 			= $this->build_array(Church::where('status','y')->get(),'id','name');
		$this->data['data']  			= $this->model;
		// $this->data['sample_seat_array'] = '[[{"seat_code":"A1","status":1},{"seat_code":"A2","status":0},{"seat_code":"A3","status":0},{"seat_code":"A4","status":0},{"seat_code":"A5","status":1}],[{"seat_code":"B1","status":1},{"seat_code":"B2","status":0},{"seat_code":"B3","status":0},{"seat_code":"B4","status":0},{"seat_code":"B5","status":1}]]';

		// dd($this->data['modem_log']);
		return $this->render_view('pages.music.edit');
	}

	public function update(Request $request, $id){
		// $this->validate($request,[
		// 	'file' 		=> 'required|unique:warta,file,'.$id,
		// ],[
        //     'file.required' => 'Warta file is Required.',
        //     'file.unique' 	=> 'Warta file has already been taken.',
		// ]);
		$this->model 	= $this->model->find($id);
		$this->model->title				= $request->title;
	
        // dd($request->hasFile('logo'));
        //image music
		if ($request->hasFile('image')){
        	// File::delete($this->image_path.$this->model->image);
			$image_path = $this->data['image_path'];
			$data = [
						'name' => 'image',
						'file_opt' => ['path' => $image_path.'/']
					];
			$image = $this->build_image($data);
			$this->model->image = $image;
		}else{
            if($this->model->image == null){
                Alert::fail('Music image required');
                return redirect()->back();
            }
        }

		if($request->input('remove-single-image-image') == 'y'){
			File::delete($this->image_path.$this->model->image);
			$this->model->image = '';
		}
        
        //file music
        if ($request->hasFile('music_name')){
            $filename = $request->music_name->getClientOriginalName();
            $data_found = Music::where([['music_name', $filename],['id','!=',$id]])->get();
            
            if(count($data_found) > 0){
                Alert::fail('music file already upload');
                return redirect()->back();
            }

        	// File::delete($this->image_path.$this->model->image);
			$image_path = $this->data['audio_path'];
			$data = [
						'name' => 'music_name',
						'file_opt' => ['path' => $image_path.'/']
					];
			$image = $this->build_image($data);
			$this->model->music_name = $image;
		}else{
            if($this->model->music_name == null){
                Alert::fail('Music file required');
                return redirect()->back();
            }
        }

		if($request->input('remove-single-image-music_name') == 'y'){
			File::delete($this->audio_path.$this->model->music_name);
			// $this->model->file = '';
		}

		$this->model->upd_by = auth()->guard($this->guard)->user()->id;

		// dd($this->model->toArray());
		$this->model->save();
		
		Alert::success('Successfully edit Article');
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
