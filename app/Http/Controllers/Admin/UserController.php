<?php namespace digipos\Http\Controllers\Admin;

use DB;
use Session;
use Hash;
use File;

use digipos\models\User;
use digipos\models\Outlet;
use digipos\models\Msmenu;
use digipos\models\Useraccess;
use digipos\models\Mslanguage;

use digipos\Libraries\Alert;
use Illuminate\Http\Request;

class UserController extends KyubiController {

	public function __construct(){
		parent::__construct();
		$this->middleware($this->auth_guard); 
		$this->middleware($this->role_guard);
		$this->title 			= "User";
		$this->root_url			= "users/user";
		$this->primary_field 	= "name";
		$this->root_link 		= "user";
		$this->model 			= new User;
		$this->restrict_id 		= [1];
		$this->bulk_action 		= true;
		$this->bulk_action_data = [4];
		$this->image_path 		= 'components/both/images/user/';
		$this->data['image_path'] 	= $this->image_path;
		$this->merchant_id		= '';

		$this->data['root_url']		= $this->root_url;
		
		$this->data['baptism_status_arr']	=  array(1=>'BAPTISM DATE', 2=>'TANPA PENEGUHAN BAPTIS HMJ', 3=>'AKAN MENGIKUTI BAPTISAN BERIKUT');
		// $this->data['authmenux'] = Session('authmenux'); 
		// $this->data['msmenu'] = Session('msmenu');
	}

	/**source.
	 *
	 * @return Response
	 * Display a listing of the response
	 */
	public function index(){
		$this->field = [
			[
				'name' => 'email',
				'label' => 'Email',
				'sorting' => 'y',
				'search' => 'text'
			],
			[
				'name' => 'phone',
				'label' => 'Phone',
				'sorting' => 'y',
				'search' => 'text'
			],
			[
				'name' => 'name',
				'label' => 'Name',
				'sorting' => 'y',
				'search' => 'text'
			],
			[
				'name' => 'user_access_id',
				'label' => 'User Access',
				'sorting' => 'y',
				'search' => 'select',
				'search_data' => $this->get_user_access(),
				'belongto' => ['method' => 'useraccess','field' => 'access_name']
			],
			[
				'name' => 'status',
				'label' => 'Status',
				'type' => 'check',
				'data' => ['y' => 'Active','n' => 'Not Active'],
				'tab' => 'general'
			]
		];
		return $this->build('index');

		// global
		// $this->data['user'] = $this->get_user();
		// return $this->render_view('pages.user.index');
	}

	public function create(){
		$this->data['title'] 		= 'Create New '.$this->title;
		$this->data['user_access'] 	= $this->get_user_access();
		// dd(count($this->data['user_access']));
		// dd($this->data['user_access']);
		// $this->data['outlet'] 		= Outlet::where('status','y')->get();
		// dd($this->data['user_access']);
		// dd($this->data['baptism_status_arr']);
		return $this->render_view('pages.user.create');

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request){
		$this->validate($request,[
			'phone'					=> 'required|unique:user',
			'email'					=> 'required|unique:user',
			// 'user_access'			=> 'required',
			'name'					=> 'required',
			// 'birth_date'			=> 'required',
			'picture' 				=> 'mimes:jpeg,png,jpg,gif'
		]);

		// $outlet_value = "";
		// if($request->user_access_id != '2'){
		// 	foreach($request->outlet as $key => $o){
		// 		if($key == 0) $outlet_value .= ";";
		// 		$outlet_value .= $o.";";
		// 	}
		// }
		
		$user = $this->model->orderBy('id','desc')->first();
		$this->model->phone 			= $request->phone;
		($request->login_web == 'y' ? $this->model->login_backend = 'y' : $this->model->login_backend = 'n');
		if($request->login_web == 'y'){
			$this->model->password 			= ($request->password ? Hash::make($request->password) : '');
		}
		$this->model->email 			= $request->email;
		$this->model->name 				= $request->name;
		$this->model->user_access_id 	= $request->user_access_id;
		$this->model->status 			= 'y';
		$this->model->birth_date 		= ($request->birth_date ? date_format(date_create($request->birth_date),'Y-m-d') : '');
		$this->model->gender 			= $request->gender;
		$this->model->phone 			= $request->phone;
		$this->model->address 			= $request->address;
		$this->model->no_kaj 			= $request->no_kaj;
		$this->model->join_date 		= ($request->join_date ? date_format(date_create($request->join_date),'Y-m-d') : '');
		if($request->baptism_status == '1'){
			$this->model->baptism_date 		= date_format(date_create($request->baptism_date),'Y-m-d');
		}
		$this->model->baptism_status 	= $request->baptism_status;
		$this->model->baptism_no 		= $request->no_baptism;
		if( $request->status_kaj == 'eu'){
			$this->model->kaj_expired_date = date_format(date_create($request->expired_date),'Y-m-d');
		}
		$this->model->kaj_status 		= $request->status_kaj;
		$this->model->information 		= $request->information;
		
		// dd($request->hasFile('logo'));
		if ($request->hasFile('picture')){
        	// File::delete($this->image_path.$this->model->picture);
			$next_user_id = $user->id + 1;
			$image_path = $this->data['image_path'].$next_user_id;
			$data = [
						'name' => 'picture',
						'file_opt' => ['path' => $image_path.'/']
					];
			$image = $this->build_image($data);
			$this->model->images = $image;
		}

		if($request->input('remove-single-image-images') == 'y'){
			File::delete($this->image_path.$this->model->picture);
			$this->model->images = '';
		}
		
		$this->model->created_by			= auth()->guard($this->guard)->user()->id;
		$this->model->created_at			= date('Y-m-d H:i:s');
		// dd($this->model);
		$this->model->save();

		Alert::success('Successfully create user');
		return redirect()->to($this->data['path']);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id){
		$this->data['user'] = $this->model->find($id);
		$this->data['title'] = 'View '.$this->title.' '.$this->data['user']->username;	
		$this->data['user_access'] = $this->get_user_access();

		$this->data['ouser'] = "";
		$oulet_id = explode(";", $this->data['user']->outlet_id);
		$i = 0;
		foreach($oulet_id as $key => $o){
			if($o != ""){
				$oname = Outlet::find($o)->outlet_name;
				if($i != 0) $this->data['ouser'] .= ", ";
				$this->data['ouser'] .= $oname;

				$i++;
			}
		}

		return $this->render_view('pages.user.view');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id){
		$this->data['user'] = $this->model->find($id);
		$this->data['title'] 	= 'Edit '.$this->title.' '.$this->data['user']->username;	
		$this->data['user_access'] = $this->get_user_access();
		$this->data['image_path'] = $this->data['image_path'].$id.'/';
		
		return $this->render_view('pages.user.edit');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id){
		$this->validate($request,[
			// 'username'			=> 'required|unique:user,username,'.$id,
			'phone'					=> 'required|unique:user,phone,'.$id,
			'email'					=> 'required|unique:user,email,'.$id,
			// 'user_access'			=> 'required',
			'name'					=> 'required',
			// 'birth_date'			=> 'required',
			'picture' 				=> 'mimes:jpeg,png,jpg,gif'
		]);
		
		// $outlet_value = "";
		// if($request->user_access_id != '2'){
			
		// 	foreach($request->outlet as $key => $o){
		// 		if($key == 0) $outlet_value .= ";";
		// 		$outlet_value .= $o.";";
		// 	}
		// }

		$this->model 					= $this->model->find($id);
		$this->model->phone 			= $request->phone;
		($request->login_web == 'y' ? $this->model->login_backend = 'y' : $this->model->login_backend = 'n');
		if($request->login_web == 'y'){
			$this->model->password 			= ($request->password ? Hash::make($request->password) : '');
		}
		$this->model->email 			= $request->email;
		$this->model->name 				= $request->name;
		$this->model->user_access_id 	= $request->user_access_id;
		$this->model->status 			= 'y';
		$this->model->birth_date 		= ($request->birth_date ? date_format(date_create($request->birth_date),'Y-m-d') : '');
		$this->model->gender 			= $request->gender;
		$this->model->phone 			= $request->phone;
		$this->model->address 			= $request->address;
		$this->model->no_kaj 			= $request->no_kaj;
		$this->model->join_date 		= ($request->join_date ? date_format(date_create($request->join_date),'Y-m-d') : '');
		if($request->baptism_status == '1'){
			$this->model->baptism_date 		= date_format(date_create($request->baptism_date),'Y-m-d');
		}
		$this->model->baptism_status 	= $request->baptism_status;
		$this->model->baptism_no 		= $request->no_baptism;
		if( $request->status_kaj == 'eu'){
			$this->model->kaj_expired_date = date_format(date_create($request->expired_date),'Y-m-d');
		}
		$this->model->kaj_status 		= $request->status_kaj;
		$this->model->information 		= $request->information;

		$image_path = $this->data['image_path'].$id;
		if($request->input('remove-single-image-picture') == 'y'){
			if($this->model->images != null){
				File::delete($image_path.'/'.$this->model->images);
				$this->model->images = null;
			}
		}

		if ($request->hasFile('picture')){
			if($this->model->images != null){
				// echo $this->model->images;
				File::delete($image_path.'/'.$this->model->images);
				// $this->model->images = null;
			}

			$data = [
						'name' => 'picture',
						'file_opt' => ['path' => $image_path.'/']
					];
			$image = $this->build_image($data);
			$this->model->images = $image;
		}
		
		$this->model->upd_by			= auth()->guard($this->guard)->user()->id;
		// dd($this->model);
		$this->model->save();

		Alert::success('Successfully update user');
		return redirect()->to($this->data['path']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(){
		$this->field = $this->field_edit();
		return $this->build('delete');
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

	public function get_user_access(){
		$q = $this->build_array(Useraccess::where('id','>',1)->get(),'id','access_name');
		return $q;
	}

	public function get_user(){
		return 1;
		$q = User::where('id', '!=',null)->get();
		return $q;
	}

	public function get_language(){
		$q = Mslanguage::where('status','y')->orderBy('order','asc')->pluck('language_name','id')->toArray();
		return $q;
	}

	public function export(){
		if(in_array(auth()->guard($this->guard)->user()->store_id,["0","1"])){
			$users = '';
		}else{
			$users = $this->get_userId_byStore();
		}
		return $this->build_export($users);
	}
}
