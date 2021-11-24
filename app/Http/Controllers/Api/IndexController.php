<?php namespace digipos\Http\Controllers\Api;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use digipos\models\User;
// use digipos\models\Category;
// use digipos\models\Slideshow;
// use digipos\models\Socialmedia;
// use digipos\models\Payment_method;
use digipos\models\Order_hd;
use digipos\models\Order_dt;
use digipos\models\Promo;

class IndexController extends Controller {

	public function __construct(){
		parent::__construct();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function booting(){
		$this->res['global']			= $this->data;
		$this->res['social_media']		= $this->cache_query('social_media',Socialmedia::where('status','y')->orderBy('order','asc')->select('image','url'),'get');
		$this->res['payment_methods']	= $this->cache_query('payment_method',Payment_method::where('status','y')->orderBy('order','asc')->select('payment_method_name','image'),'get');

		try {
			$user 	= JWTAuth::parseToken()->authenticate();
	        if (!$user) {
	        	$this->res['user'] 	= ['status' => 'user_not_found'];
	        }else{
	        	$this->res['user'] 	= ['status' => 'success','user' => $user];
	        }
			return response()->json($this->res);
	    } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
		    $this->res['user']		= ['status' => 'token_expired'];
			return response()->json($this->res);
		} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
		    $this->res['user']		= ['status' => 'token_invalid'];
			return response()->json($this->res);
		} catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
		    $this->res['user']		= ['status' => 'token_absent'];
			return response()->json($this->res);
		}
	}

	public function index(){
		// $this->res['slideshow']	= Slideshow::where('status','y')->orderBy('order','asc')->get();
		$this->res = [ "status" => "error",
					   "message" => "Invalid or Expired Token",
					   "data" => []
					];
		return response()->json($this->res);
	}

	public function updateOrderStatus($order_status_id){
		if($order_status_id == 3){
			$date = date("Y-m-d H:i:s", strtotime('-24 hours'));
			$data = Order_hd::leftJoin('promo', 'promo.id', 'promo_id')->where([['orderhd.created_at', '<=', $date],['orderhd.order_status_id', 1]])->select('orderhd.*', 'promo.user_id as promo_user_id')->get();

			$arrUpdate = [];
			if(count($data) > 0){
				foreach ($data as $key => $v) {
					if($v['promo_user_id'] != null){
						$update = Order_hd::find($v['id']);
						$update->order_status_id = 3;
						$update->save();

						array_push($arrUpdate, $update);
					}	
				}

				if(count($arrUpdate) > 0){
					$return = [
						'code' 		=> 200,
						'message'	=> 'success',
						'data'		=> $arrUpdate
					];
				}else{
					$return = [
						'code' 		=> 200,
						'message'	=> 'success',
						'data'		=> $arrUpdate
					];
				}
			}else{
				$return = [
					'code' 		=> 200,
					'message'	=> 'success',
					'data'		=> $arrUpdate
				];
			}

			return json_encode($return);

		}
		
	}
}
