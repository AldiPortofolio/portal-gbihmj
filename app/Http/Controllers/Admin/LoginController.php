<?php namespace digipos\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Hash;
use Auth;
use File;
use DB;
use Session;
use digipos\models\User;
use digipos\models\Mslanguage;
use digipos\models\Msmenu;
use digipos\models\Useraccess;
use digipos\Libraries\Alert;

class LoginController extends KyubiController {

	public function __construct(){
		parent::__construct();
		$this->middleware($this->guest_guard)->except(['logout','profile','update_profile','change_language']);
		$this->middleware($this->auth_guard)->only(['logout','profile','change_language']);
		$this->data['title'] = trans('general.account');
		$this->user = new User;
		$this->data['path'] = 'components/admin/image/user/';
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		return $this->render_view('pages.login');
	}

	public function cek_login(request $request){
		$email 		= $request->email;
		$password 	= $request->password;
		$remember 	= $request->remember;
		$auth 		= auth()->guard($this->guard);
		$data = [
			'email' 		=> $email,
			'password' 		=> $password,
			'status' 		=> 'y',
			// 'login_web' 	=> 'y'
		];
		$auth = $auth->attempt($data);
		if($auth){
			$authmenux  = [];
            $msmenu     = [];
            $authmenu   = DB::table('authmenu as a')
                            ->leftjoin('useraccess as b','a.user_access_id','=','b.id')
                            ->leftjoin('msmenu as c','a.menu_id','=','c.id')
                            ->where('b.id',auth()->guard($this->guard)->user()->user_access_id)
                            ->where('c.status_parent','y')
                            ->where('c.status','y')
                            ->orderBy('c.order','asc')
                            ->select('c.*','a.menu_id')
                            ->get();
			foreach ($authmenu as $au){
                $cek      = Msmenu::where('menu_id',$au->id)->get();
                $temp     = [];
                if(count($cek) > 0){
                    foreach($cek as $c){
                        $temp[]  = $c->id;
                    }
                    $child_cek = DB::table('useraccess as a')->join('authmenu as b','a.id','=','b.user_access_id')
                                    ->where('a.id',auth()->guard($this->guard)->user()->user_access_id)
                                    ->whereIn('b.menu_id',$temp)
                                    ->count();
                    if($child_cek > 0){
                        $authmenux[]    = $au;
                        $msmenu[] = DB::table('authmenu as a')
                                ->leftjoin('useraccess as b','a.user_access_id','=','b.id')
                                ->leftjoin('msmenu as c','a.menu_id','=','c.id')
                                ->where('c.menu_id',$au->menu_id)
                                ->where('b.id',auth()->guard($this->guard)->user()->user_access_id)
                                ->where('c.status','y')
                                ->orderBy('c.order','asc')
                                ->get();
                    }
                }else{
                    $authmenux[]   = $au;
                    $msmenu[]      = [];
                }
            }

            // dd($msmenu);
            
            if(auth()->guard($this->guard)->user()->user_access_id == '1'){
				$access_merchant = 'all';
				$access_store = 'all';
			}else{
				$access_merchant = auth()->guard($this->guard)->user()->merchant_id;
				$access_store = auth()->guard($this->guard)->user()->store_id;
			}

            $request->session()->put('authmenux',$authmenux);
            $request->session()->put('msmenu',$msmenu);
            $request->session()->put('access_merchant',$access_merchant);
            $request->session()->put('access_store',$access_store);
			// return $this->root_path;
			return redirect()->intended($this->root_path);			
			// return redirect()->to($this->root_path);			
		}else{
			Alert::fail('Email or password is not valid');
			return redirect()->back();
		}
	}				

	public function logout(){
		Alert::info('You are logging out');
		auth()->guard($this->guard)->logout();
		return redirect()->to($this->root_path.'/login');
	}

	public function profile(){
		$this->data['language'] = $this->get_language();
		return $this->render_view('pages.profile');
	}

	public function update_profile(Request $request){
		$this->validate($request,[
			'name' 			=> 'required',
			'email' 		=> 'required|unique:user,email,'.auth()->guard($this->guard)->user()->id,
	        'images' 		=> 'mimes:jpeg,png,jpg,gif',
	        'language_id' 	=> 'required'
		]);
		
		$user = User::find(auth()->guard($this->guard)->user()->id);
		$user->email = $request->email;
		$user->name = $request->name;
		if ($request->has('password')){
			$user->password = Hash::make($request->password);
		}

		//Image
		$path = 'components/admin/image/user/';
		if ($request->hasFile('images')){
        	File::delete($path.$user->images);
			$data = [
						'name' => 'images',
						'file_opt' => ['path' => $path]
					];
			$image = $this->build_image($data);
			$user->images = $image;
		}
		if($request->input('remove-single-image-images') == 'y'){
			File::delete($path.$user->images);
			$user->picture = '';
		}
		$user->language_id 	= $request->language_id;
		// dd($user);
		$user->save();
		Alert::success('Successfully update '.$this->data['title']);
		return redirect()->back();
	}

	public function get_language(){
		$q = Mslanguage::where('status','y')->orderBy('order','asc')->pluck('language_name','id');
		return $q;
	}

	public function change_language($id){
		auth()->guard($this->guard)->user()->language_id	= $id;
		auth()->guard($this->guard)->user()->save();
		return redirect()->back();
	}
}
