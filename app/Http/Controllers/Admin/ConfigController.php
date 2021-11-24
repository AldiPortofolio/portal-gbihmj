<?php namespace digipos\Http\Controllers\Admin;

use digipos\models\Config;
// use digipos\models\Social_media;

use digipos\Libraries\Alert;
use Request;
use File;
use Cache;

class ConfigController extends KyubiController {

	public function __construct()
	{
		parent::__construct();
		$this->middleware($this->auth_guard); 
		$this->middleware($this->role_guard);
		$this->model 		 	= new Config;
		// $this->social_media 	= new Social_media;
		$this->data['title']	= trans('general.general-settings');
		$this->data['image_path'] = 'components/back/images/admin/';
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		$config = $this->model->where([['config_type', 'both']])->get();
		foreach($config as $c){
			$configs[$c->name] = $c->value;
		}
		$configs = (object)$configs;
		$this->data['configs'] = $configs;
		// $config = $config->toArray();
		$this->data['data'] = $config;

		// $social_media = $this->social_media->get();
		// foreach($social_media as $sm){
		// 	$social_medias[$sm->name] = $sm->value;
		// }
		// $social_medias = (object)$social_medias;
		// $this->data['social_media'] = $social_medias;
		// foreach ($config as $key => $c) {
		// 	echo $c->value;
		// }
		// dd($config);
		return $this->render_view('pages.config.index');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(){
		$all 	= Request::except(['clean_cache']);

		foreach($all as $r => $qr){
			$q = $this->model->where('name',$r)->first();
			if ($q){
				if($q->value != $qr){
					// if($r == 'remove-single-image-favicon' || $r == 'remove-single-image-web-logo'){
					// 	if($request->input($r) == 'y'){
					// 		dd('test');
					// 		if($this->model->images != NULL){
					// 			File::delete($this->data['image_path'].$this->model->value);
					// 			$qr = '';
					// 		}
					// 	}
					// }

					if (Request::file($r)){
						$data = [
								'name' => $r,
								'file_opt' => ['path' => $this->data['image_path']],
								'old_image'	=> $this->model->value
							];
						$image = $this->build_image($data);
						$qr = $image;
					}
					$q->value = $qr;
					$q->save();
				}
			}
		}

		// foreach($all as $r => $qr){
		// 	$q = $this->social_media->where('name',$r)->first();
		// 	if ($q){
		// 		if($q->value != $qr){
		// 			if (Request::file($r)){
		// 				$data = [
		// 						'name' => $r,
		// 						'file_opt' => ['path' => $this->data['image_path']],
		// 						'old_image'	=> $this->model->value
		// 					];
		// 				$image = $this->build_image($data);
		// 				$qr = $image;
		// 			}
		// 			$q->value = $qr;
		// 			$q->save();
		// 		}
		// 	}
		// }
		
		if(Request::input('clean_cache') == 'y'){
			Cache::flush();
		}
		Alert::success('Successfully change '.$this->data['title']);
		return redirect()->back();
	}
}
