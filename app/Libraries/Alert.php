<?php 

namespace digipos\Libraries;
use Request;
use Session;

class Alert{
	private $data;
	public static function fail($message){
    $content = '<div class="growl-alert" data-type="danger" data-message="'.$message.'"></div>';
		Session::flash('message',$content);
	}

	public static function success($message){
		$content = '<div class="growl-alert" data-type="success" data-message="'.$message.'"></div>';
		Session::flash('message',$content);
	}

  public static function info($message){
    $content = '<div class="growl-alert" data-type="info" data-message="'.$message.'"></div>';
    Session::flash('message',$content);
  }
}