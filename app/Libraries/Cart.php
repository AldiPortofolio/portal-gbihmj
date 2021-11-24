<?php namespace digipos\Libraries;

use digipos\models\Customer_cart;
use Cookie;
use Session;

class Cart{
	private static $cek;

	public static function add($request) {
		//$cart 					= Customer_cart::where('identifier',self::identifier())->first();
		$id 								= $request['id'];
		$attr 								= $request['attr'];
		$qty 								= $request['qty'];
		$product_data_attribute_master_id 	= $request['product_data_attribute_master_id'];
		if($request->session()->has('cart.'.$product_data_attribute_master_id)){
			$cur_qty 						= self::get_qty($request);
			$qty 							+= (int)$cur_qty;
			$data 							= ['product_id' => $id,'attr' => $attr,'qty' => $qty];
		}else{
			$data 							= ['product_id' => $id,'attr' => $attr,'qty' => $qty];
		}
  		session(['cart.'.$request->product_data_attribute_master_id => $data]);
	}

	public static function remove($request){
		$id 	= $request->id;
		Session::forget('cart.'.$id);
  	}

  	public static function get_qty($request){
  		$qty 	= session('cart.'.$request->product_data_attribute_master_id)['qty'];
  		return $qty ?? 0;
  	}

  	public static function set_product_data($request){
  		$cart 			= session('cart.'.$request->product_data_attribute_master_id);
  		$cart['data']	= $request->data_product;
  		session(['cart.'.$request->product_data_attribute_master_id => $cart]);
  	}

  	public static function set_checkout_data($checkout_data){
  		session(['checkout_data' => $checkout_data]);
  	}

  	public static function get_cart() {
  		$cart 	= session('cart');
    	return $cart;
  	}

  	public static function get_checkout_data() {
  		$checkout_data 	= session('checkout_data');
    	return $checkout_data;
  	}

  	public static function get_total_item(){
  		$cart 			= session('cart');
  		$qty 			= 0;
  		foreach($cart as $c){
  			$qty 		+= $c['qty']; 
  		}
  		return $qty;
  	}

	public static function identifier() {
		$identifier 	  	  = Cookie::get(env('APP_CART_KEY'));
		return $identifier;
	}
}