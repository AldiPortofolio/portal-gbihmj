<?php namespace digipos\Http\Controllers\Admin;

use digipos\models\Msmenu;
use digipos\models\Authmenu;
use digipos\models\Useraccess;
use Request;
use Validator;
use Auth;
use digipos\Libraries\Alert;

class UseraccessController extends KyubiController {

	public function __construct()
	{
		parent::__construct();
		$this->middleware($this->auth_guard); 
		$this->middleware($this->role_guard);
		$this->primary_field	= 'access_name';
		$this->title 			= "User Access";
		$this->root_link 		= "user-access";
		$this->model 			= new Useraccess;
		$this->check_relation 	= ['user'];
		$this->delete_relation 	= ['authmenu'];
		//$this->restrict_id 		= [1];
		$this->restrict_delete 	= [1];
		// $this->check_relation 	= ['user'];
		$this->bulk_action 		= false;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		$this->field = [
			[
				'name' 		=> 'access_name',
				'label' 	=> 'Nama Akses',
				'sorting' 	=> 'y',
				'search' 	=> 'text'
			]
		];
		return $this->build('index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(){
		$prmenu = Msmenu::where('status_parent','y')->where('status','y')->orderBy('order','asc')->get();
		foreach ($prmenu as $pr){
			$chmenu[$pr->id] 	= Msmenu::where('menu_id',$pr->id)->where('status','y')->get();
		}
		$this->data['prmenu'] 	= $prmenu;
		$this->data['chmenu'] 	= $chmenu;
		$this->data['title'] 	= 'Add New '.$this->title;
		return $this->render_view('pages.user-access.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(){
		if ($this->rules()->fails()){
			return redirect()->back()
				->withInput()
				->withErrors($this->rules())
			;
		}else{
			$useraccess 				= new Useraccess;
			$useraccess->access_name 	= Request::input('access_name');
			$valid = Useraccess::where('access_name',$useraccess->access_name)->first();
			if($valid){
				Alert::fail($this->title.' name is not available');
				return redirect()->back()->withInput();
			}else{
				$useraccess->save();
				$access = Request::input('fkuseraccessid');
				for($i = 0;$i < count($access); $i++){
					$data[] = [
						'menu_id' => $access[$i],
						'user_access_id' => $useraccess->id,
						'create' => Request::input($access[$i].'-c') == 'y' ? 'y' : 'n',
						'edit' => Request::input($access[$i].'-e') == 'y' ? 'y' : 'n',
						'view' => Request::input($access[$i].'-v') == 'y' ? 'y' : 'n',
						'delete' => Request::input($access[$i].'-d') == 'y' ? 'y' : 'n',
						'sorting' => Request::input($access[$i].'-s') == 'y' ? 'y' : 'n',
						'export' => Request::input($access[$i].'-ex') == 'y' ? 'y' : 'n',
						'upd_user' => auth()->guard($this->guard)->user()->id,
						'created_at'	=> date('Y-m-d H:i:s'),
						'updated_at'	=> date('Y-m-d H:i:s')
					];
				}
				Authmenu::insert($data);
				Alert::success('Successfully add '.$this->title);
				return redirect()->to($this->data['path']);
			}
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id){
		if(in_array($id,$this->restrict_id)){
			Alert::fail("You don't have access");
			return redirect()->back();
		}
		$prmenu = Msmenu::where('status_parent','y')->where('status','y')->orderBy('order','asc')->get();
		foreach ($prmenu as $pr){
			$chmenu[$pr->id] = Msmenu::where('menu_id',$pr->id)->where('status','y')->get();
		}

		$dtmenu = Authmenu::where('user_access_id',$id)->get();
		foreach($dtmenu as $dt){
			$dataau[$dt->menu_id] = $dt;
		}

		$this->data['useraccess'] = Useraccess::find($id);
		$this->data['prmenu'] = $prmenu;
		$this->data['chmenu'] = $chmenu;
		$this->data['dataau'] = $dataau;
		$this->data['title'] = 'View '.$this->title.' '.$this->data['useraccess']->access_name;

		return $this->render_view('pages.user-access.view');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id){
		if(in_array($id,$this->restrict_id)){
			Alert::fail("You don't have access");
			return redirect()->back();
		}
		$prmenu = Msmenu::where('status_parent','y')->where('status','y')->orderBy('order','asc')->get();
		foreach ($prmenu as $pr){
			$chmenu[$pr->id] = Msmenu::where('menu_id',$pr->id)->where('status','y')->get();
		}
		$dtmenu = Authmenu::where('user_access_id',$id)->get();
		foreach($dtmenu as $dt){
			$dataau[$dt->menu_id] = $dt;
		}
		$this->data['useraccess'] = Useraccess::find($id);
		$this->data['prmenu'] = $prmenu;
		$this->data['chmenu'] = $chmenu;
		$this->data['dataau'] = $dataau;
		$this->data['title'] = 'View '.$this->title.' '.$this->data['useraccess']->access_name;
		return $this->render_view('pages.user-access.edit');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id){
		if ($this->rules()->fails()){
			return redirect()->back()
				->withInput()
				->withErrors($this->rules())
				->except('password')
			;
		}else{
			$useraccess = Useraccess::find($id);
			if ($useraccess->access_name != Request::input('access_name')){
				$valid = Useraccess::where('access_name',Request::input('access_name'))->first();
				if($valid){
					Alert::fail($this->title.' Name is not available');
					return redirect()->back()->withInput();
				}else{
					$useraccess->access_name = Request::Input('access_name');
				}
			}
			$useraccess->access_name = Request::input('access_name');
			$useraccess->save();
			$access = Request::input('fkuseraccessid');
			for($i = 0;$i < count($access); $i++){
				$data[] = [
					'menu_id' => $access[$i],
					'user_access_id' => $useraccess->id,
					'create' => Request::input($access[$i].'-c') == 'y' ? 'y' : 'n',
					'edit' => Request::input($access[$i].'-e') == 'y' ? 'y' : 'n',
					'view' => Request::input($access[$i].'-v') == 'y' ? 'y' : 'n',
					'delete' => Request::input($access[$i].'-d') == 'y' ? 'y' : 'n',
					'sorting' => Request::input($access[$i].'-s') == 'y' ? 'y' : 'n',
					'export' => Request::input($access[$i].'-ex') == 'y' ? 'y' : 'n',
					'upd_user' => auth()->guard($this->guard)->user()->id,
					'created_at'	=> date('Y-m-d H:i:s'),
					'updated_at'	=> date('Y-m-d H:i:s')	
				];
			}
			Authmenu::where('user_access_id',$id)->delete();
			Authmenu::insert($data);
			Alert::success('Successfully update '.$this->title.' '.$useraccess->access_name);
			return redirect()->back();
		}
	}

	public function destroy(){
		$id 	= Request::input('id');
		$uc 	= $this->model->find($id);	
		if(count($uc->user) > 0){
			Alert::fail('User Access has been used by other user');
			return redirect()->back();
		}else{
			$uc->user()->delete();
			$uc->delete();
			Alert::success('User Access has been deleted');
			return redirect()->back();
		}
	}

	public function ext($action){
		return $this->$action();
	}

	public function bulkupdate(){
		return $this->buildbulkedit();
	}

	public function rules(){
		$v = Validator::make(Request::all(), [
	        'access_name' => 'required'
	    ]);
	    return $v;
	}

	public function export(){
		return $this->build_export();
	}
}
