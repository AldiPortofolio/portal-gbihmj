<?php namespace digipos\Http\Controllers\Front;
use Illuminate\Http\request;

use DB;


class PagesController extends ShukakuController {

	public function __construct(){
		parent::__construct();

		$this->menu = $this->data['path'][0];
		$this->data['menu'] 		= $this->menu;
	}

	public function thanks_page(){
		return $this->render_view('pages.thanks_page');
	}
}
