<?php 

namespace digipos\Libraries;
use Request;

class Breadcrumb{
	private $data;
	public static function _set($global){
		$path = Request::path();
		$url = explode('/',$path);
		$root = Request::root();
		$data = "";
		/*$data = '<div class="page-head"><div class="page-title">';
		if(isset($url[2]) && $url[1] != ''){
			$data .= '<h1>'.trans('general.'.$url[1]).' <small>'.trans('general.'.$url[2]).'</small></h1>';
		}else{
			$data .= '<h1>'.trans('general./').'</h1>';
		}
		$data .= '</div></div>';*/
		$data .= '<ul class="page-breadcrumb breadcrumb">';
		$data .= '<li><a href="'.$root.'/'.$global['root_path'].'">'.trans('general./').'</a></li>';
		if($path != '_admin' && 'login'){
			$url  = explode('/',$path);
			unset($url[0]);
			$loop	= 1;
			foreach($url as $u){
				if(!(int)$u){
					$u = trans('general.'.strtolower($u));
				}
				if($loop == count($url)){
					$data .= '<li><i class="fa fa-circle"></i><span class="active">'.$u.'</span></li>';
				}else{
					$data .= '<li><i class="fa fa-circle"></i><a>'.$u.'</a></li>';
				}
				$loop++;
			}
		}
		$data .= '</ul>';
		return $data;
	}

	public static function _get($global){
		return self::_set($global);
	}
}