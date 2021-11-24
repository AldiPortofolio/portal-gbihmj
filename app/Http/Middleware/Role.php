<?php namespace digipos\Http\Middleware;

use Closure;
use DB;
use View;
use App;
use digipos\Libraries\Alert;

class Role{

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard){
		$second = $request->segment(2);
		$url 	= $request->segment(3);
		$seg 	= $request->segment(4);
		if($second != ''){
			$action = ['create','edit','view','sorting','destroy','ext'];
			if((int)$url){
				$url 	= $seg;
			}
			if ($url == '' || in_array($url,$action)){
				$url = $request->segment(2);
				$seg = $request->segment(3);
				if((int)$seg){
					if($request->segment(4) != null){
						$seg 	= $request->segment(4);
					}else{
						$seg 	= 'view';
					}
				}
				$sub_seg 	= $request->segment(4);
			}else{
				if((int)$seg){
					if($request->segment(5) != null){
						$seg 	= $request->segment(5);
					}else{
						if($request->method() == 'GET'){
							$seg 	= 'view';
						}else{
							$seg 	= 'edit';
						}
					}
				}
				$sub_seg 	= $request->segment(5);
			}
		}else{
			$url = '/';
		}
		$role = DB::table('authmenu as a')
				->join('msmenu as b','a.menu_id','=','b.id')
				->where('b.menu_name_alias',$url)
				->where('a.user_access_id',auth()->guard('admin')->user()->user_access_id)
				->where('b.status','y')
				->select('a.*')
				->first();

		App::instance('role', $role);
		View::share('role',$role);
		
		if (!$role){
			Alert::fail("You don't have access");
			return redirect()->back();
		}
		
		if ($seg == 'create' && $role->create == 'n'){
			Alert::fail("You don't have access to create");
			return redirect()->back();
		}else if ($seg == 'edit' && $role->edit == 'n'){
			Alert::fail("You don't have access to edit");
			return redirect()->back();
		}else if ($seg == 'view' && $role->view == 'n'){
			Alert::fail("You don't have access to view");
			return redirect()->back();
		}else if ($seg == 'destroy' && $role->delete == 'n'){
			Alert::fail("You don't have access to delete");
			return redirect()->back();
		}else if ($seg == 'ext'){
			if ($sub_seg == 'sorting' && $role->sorting == 'n'){
				Alert::fail("You don't have access to sorting");
				return redirect()->back();
			}else if ($sub_seg == 'export' && $role->export == 'n'){
				Alert::fail("You don't have access to export data");
				return redirect()->back();
			}
		}
		return $next($request);
	}

}
