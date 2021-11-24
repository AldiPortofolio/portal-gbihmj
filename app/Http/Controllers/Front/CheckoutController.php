<?php namespace digipos\Http\Controllers\Front;

use Illuminate\Http\request;

class CheckoutController extends ShukakuController {

	public function __construct(){
		parent::__construct();
	}

	public function miniCart(request $request){
		$this->data['cart'] 				= $this->generateCart($request);
		if($this->data['cart'] != 'no_data'){
			$this->data['cart'] 			= $this->generateCart($request)['cart'];
			$this->data['checkout_data'] 	= $this->generateCart($request)['checkout_data'];
		}
		return $this->render_view('pages.parts.mini_cart');
	}

	public function cart(request $request){
		$this->data['cart'] 				= $this->generateCart($request);
		if($this->data['cart'] != 'no_data'){
			$this->data['cart'] 			= $this->generateCart($request)['cart'];
			$this->data['checkout_data'] 	= $this->generateCart($request)['checkout_data'];
		}
		return $this->render_view('pages.cart');
	}

	public function cartParts(request $request){
		$this->data['cart'] 				= $this->generateCart($request);
		if($this->data['cart'] != 'no_data'){
			$this->data['cart'] 			= $this->generateCart($request)['cart'];
			$this->data['checkout_data'] 	= $this->generateCart($request)['checkout_data'];
		}
		return $this->render_view('pages.parts.cart');
	}
}
