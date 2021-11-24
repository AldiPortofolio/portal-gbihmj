<?php namespace digipos\Http\Controllers\Admin;

use digipos\models\Config;
use digipos\models\Church;
use digipos\models\Room;
use digipos\models\Warta;

use Validator;
use Auth;
use Hash;
use DB;
use digipos\Libraries\Alert;
use Illuminate\Http\Request;
use digipos\Libraries\Email;
use Carbon\Carbon;
use File;

class WartaController extends KyubiController {

	public function __construct()
	{
		parent::__construct();
		$this->middleware($this->auth_guard);
		$this->middleware($this->role_guard);
		$this->title 			= "Warta";
		$this->data['title']	= $this->title;
		$this->root_link 		= "warta";
		$this->model 			= new Warta;

		$this->bulk_action			= true;
		$this->bulk_action_data 	= [1];
		$this->file_path 			= 'components/both/file/warta/';
		$this->data['file_path'] 	= $this->file_path;
		$this->file_path2 			= 'components/both/file/';
		$this->data['file_path2'] 	= $this->file_path2;

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
				'name' 		=> 'file',
				'label' 	=> 'File',
                'type' 		=> 'file',
				'sorting' 	=> 'n',
				'search' 	=> 'text',
                'file_opt'  =>  ['path' => $this->data['base_url'].'/'.$this->file_path]
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
		
		$this->data['title'] 						= "Create Warta";
		// $this->data['church'] = $this->build_array(Church::where('status','y')->get(),'id','name');
		// $this->data['sample_seat_array'] = '[[{"seat_code":"A1","status":1},{"seat_code":"A2","status":0},{"seat_code":"A3","status":0},{"seat_code":"A4","status":0},{"seat_code":"A5","status":1}],[{"seat_code":"B1","status":1},{"seat_code":"B2","status":0},{"seat_code":"B3","status":0},{"seat_code":"B4","status":0},{"seat_code":"B5","status":1}]]';

		return $this->render_view('pages.warta.create');
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

		// $this->model->title				= $request->title;
		// $this->model->content			= $request->content;
		// $this->model->status_banner		= ($request->status_banner == 'y' ? $request->status_banner : $request->status_banner = 'n');
		// $this->model->write_by			= $request->write_by;
		// $this->model->url				= $request->url;
		// $this->model->article_date		= date_format(date_create($request->article_date),'Y-m-d');
	
        if ($request->hasFile('file')){
            $filename = $request->file->getClientOriginalName();
            $warta_found = Warta::where('file', $filename)->get();
            
            if(count($warta_found) > 0){
                Alert::fail('Warta file already upload');
                return redirect()->back();
            }

        	// File::delete($this->image_path.$this->model->image);
			$image_path = $this->data['file_path'];
			$data = [
						'name' => 'file',
						'file_opt' => ['path' => $image_path.'/']
					];
			$image = $this->build_image($data);
			$this->model->file = $image;
		}else{
            Alert::fail('Warta file required');
            return redirect()->back();
        }

		if($request->input('remove-single-image-images') == 'y'){
			File::delete($this->file_path.$this->model->file);
			// $this->model->file = '';
		}
		
		$this->model->created_by			= auth()->guard($this->guard)->user()->id;
		$this->model->created_at			= date('Y-m-d H:i:s');

		// dd($this->model->toArray());
		$this->model->save();

		Alert::success('Successfully add Warta');
		return redirect()->to($this->data['path']);
	}

	public function edit($id){
		$this->model 					= $this->model->find($id);
		// dd($this->model);
		$this->data['title'] 			= "Edit Warta ".$this->model->id;
		// $this->data['church'] 			= $this->build_array(Church::where('status','y')->get(),'id','name');
		$this->data['data']  			= $this->model;
		// $this->data['sample_seat_array'] = '[[{"seat_code":"A1","status":1},{"seat_code":"A2","status":0},{"seat_code":"A3","status":0},{"seat_code":"A4","status":0},{"seat_code":"A5","status":1}],[{"seat_code":"B1","status":1},{"seat_code":"B2","status":0},{"seat_code":"B3","status":0},{"seat_code":"B4","status":0},{"seat_code":"B5","status":1}]]';

		// dd($this->data['modem_log']);
		return $this->render_view('pages.warta.edit');
	}

	public function update(Request $request, $id){
		// $this->validate($request,[
		// 	'file' 		=> 'required|unique:warta,file,'.$id,
		// ],[
        //     'file.required' => 'Warta file is Required.',
        //     'file.unique' 	=> 'Warta file has already been taken.',
		// ]);
		$this->model 	= $this->model->find($id);
		// $this->model->title				= $request->title;
		// $this->model->content			= $request->content;
		// $this->model->status_banner		= ($request->status_banner == 'y' ? $request->status_banner : $request->status_banner = 'n');
		// $this->model->write_by			= $request->write_by;
		// $this->model->url				= $request->url;
		// $this->model->article_date		= date_format(date_create($request->article_date),'Y-m-d');
        
        // dd($request->file);
        if($request->input('remove-single-file-file') == 'y' && !$request->hasFile('file')){
            // $filename = $request->file->getClientOriginalName();
            Alert::fail('Warta file is required');
            return redirect()->back();
        }

		// dd($request->hasFile('file'));
		if ($request->hasFile('file')){
            $filename = $request->file->getClientOriginalName();
            $warta_found = Warta::where('file', $filename)->get();
            
            if(count($warta_found) > 0){
                Alert::fail('File name already upload');
                return redirect()->back();
            }
            if($this->model->file != null){
        	    File::delete($this->file_path.$this->model->file);
            }
			$image_path = $this->data['file_path'];
			$data = [
						'name' => 'file',
						'file_opt' => ['path' => $image_path.'/']
					];
			$image = $this->build_image($data);
			$this->model->file = $image;
		}else{

        }
        // dd($request->input('remove-single-file-file'));
		if($request->input('remove-single-file-file') == 'y'){
			File::delete($this->file_path.$this->model->file);
			$this->model->file = '';
		}

		$this->model->upd_by = auth()->guard($this->guard)->user()->id;

		// dd($this->model->toArray());
		$this->model->save();
		
		Alert::success('Successfully edit warta');
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
