<?php namespace digipos\Http\Controllers\Front;
use Illuminate\Http\Request;

use digipos\models\Subscriber;

class ServicesController extends ShukakuController {

	public function index($id,request $request){
		$this->data['res']	= $request;
		return $this->$id();
	}

	private function subscribe(){
		$request 			= $this->data['res'];
		$cek 				= Subscriber::where('email',$request->email)->first();
		if($cek){
			$response 			= ['status' => 'danger','text' => 'you already subscribe'];
		}else{
			$subscriber 		= new Subscriber;
			$subscriber->email 	= $request->email;
			$subscriber->save();
			$response 			= ['status' => 'success','text' => 'Thank you for subscribe'];
		}
		return response()->json($response);
	}
}
