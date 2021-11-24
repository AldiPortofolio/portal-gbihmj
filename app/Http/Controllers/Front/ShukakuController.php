<?php namespace digipos\Http\Controllers\Front;

use Request;
use Cache;
use Cookie;
use Hash;

use digipos\Libraries\Cart;

use digipos\models\Config;
use digipos\models\Customer_cart;
use digipos\models\Socialmedia;
use digipos\models\Payment_method;
use digipos\models\Product;
use digipos\models\Product_attribute_data;

class ShukakuController extends Controller {

	public function __construct(){
		parent::__construct();        
	}

	public function initData(){
		//Set Global
        $query_config = Config::where('config_type','front')->orwhere('config_type','both');
        $config = $this->cache_query('config',$query_config,'get');
        foreach($config as $c){
            $this->data[$c->name] = $c->value;
            $this->data['email_config'][$c->name] = $c->value;
        }
        $this->data['social_media']		= $this->cache_query('social_media',Socialmedia::where('status','y')->orderBy('order','asc')->select('image','url'),'get');
		$this->data['payment_methods']	= $this->cache_query('payment_method',Payment_method::where('status','y')->orderBy('order','asc')->select('payment_method_name','image'),'get');        
    }

    public function generateCartIdentifier(){
        $identifier                     = Cookie::get(env('APP_CART_KEY'));
        if(!$identifier){
            $identifier                 = Hash::make(env('APP_CART_KEY')).str_random(3);
        }else{
            $identifier                 = decrypt($identifier);
        }
        
        $cek_identifier                 = Customer_cart::where('identifier',$identifier)->first();
        if(!$cek_identifier){
            $customer_cart              = new Customer_cart;
            $customer_cart->identifier  = $identifier;
            $customer_cart->customer_id = auth()->guard($this->guard)->check() ? auth()->guard($this->guard)->user()->id : '';
            $customer_cart->expired     = date('Y-m-d H:i:s',strtotime('+1 years -6 days'));
            $customer_cart->save();
            Cookie::queue(Cookie::make(env('APP_CART_KEY'),$identifier,1440*30*12));
        }
    }

	public function generateMeta(){
        $this->data['meta_full_image']  				= asset('components/both/images/web/'.$this->data['web_logo']);                    
        $this->data['facebook_meta']['og:title']        = $this->data['web_title'].' | '.$this->data['web_name'];
        $this->data['facebook_meta']['og:site_name']    = $this->data['web_name'];
        $this->data['facebook_meta']['og:url']          = Request::url();
        $this->data['facebook_meta']['og:type']         = "article";
        $this->data['facebook_meta']['og:locale']       = "id_ID";
        $this->data['facebook_meta']['og:image']        = $this->data['meta_full_image'];
        $this->data['facebook_meta']['og:description']  = $this->data['web_description'];

        $this->data['twitter_meta']['twitter:card']          = "summary_large_image";
        $this->data['twitter_meta']['twitter:site']          = "@".$this->data['web_name'];
        $this->data['twitter_meta']['twitter:creator']       = "@".$this->data['web_name'];
        $this->data['twitter_meta']['twitter:url']           = Request::url();
        $this->data['twitter_meta']['twitter:title']         = $this->data['web_name'];
        $this->data['twitter_meta']['twitter:image']         = $this->data['meta_full_image'];
        $this->data['twitter_meta']['twitter:description']   = $this->data['web_description'];
    }

    public function cache_query($name,$query,$type='',$time = 60){
        $c = Cache::remember($name, $time, function() use($query,$type){
            if (!empty($type)){
                if ($type == 'first'){
                    $q = $query->first();
                }else{
                    $q = $query->get();
                }
                return $q;
            }else{
                return $query;
            }
        });
        //Cache::flush();
        return $c;
    }

    public function generate_product_attribute($product){
    	$temp_data 				= [];
		foreach($this->data['product']->product_data_attribute_master as $pda){
			foreach($pda->product_data_attribute as $pd){
				$id			= $pd->product_attribute_data->id;
				$value			= $pd->product_attribute_data->value;
				$attribute_name = $pd->product_attribute_data->attribute->attribute_name;
				$attribute_type = $pd->product_attribute_data->attribute->attribute_type;
				if(!array_key_exists($attribute_name, $temp_data)){
					$temp_data[$attribute_name] 		= ['type' 	=> $attribute_type];
                }
				if(array_key_exists('data', $temp_data[$attribute_name])){
					foreach($temp_data[$attribute_name]['data'] as $ada){
						if($ada['value'] != $value){
							$temp_data[$attribute_name]['data'][] 	= ['id' => $id,'value' => $value];
						    break;
                        }
					}
				}else{
					$temp_data[$attribute_name]['data'][] 		= ['id' => $id,'value' => $value];
				}
			}
		}
		return $temp_data;
    }

    public function check_stock($request){
        $id         = $request['id'];
        $attr       = $request['attr'];
        $qty        = $request['qty'];
        // $action     = $request['action'] ?? '';
        $action     = $request['action'] ?: '';

        $product    = Product::where('id',$id)->with('product_data_attribute_master.product_data_attribute')->first();
        if(!$product){
            return ['status' => 'no_product'];
        }
        foreach($product->product_data_attribute_master as $pda){
            $product_data_attribute     = $pda->product_data_attribute->pluck('product_attribute_data_id')->toArray();
            $check                      = array_diff($attr,$product_data_attribute);
            if(count($check) == 0){
                $request->request->add(['product_data_attribute_master_id' => $pda->id]);
                if($action == 'add'){
                    $cur_qty            = Cart::get_qty($request);
                    $qty                += (int)$cur_qty;
                }

                $product_qty            = $pda->stock;
                if($product_qty < $qty){
                    return ['status' => 'out_of_stock'];
                }else{
                    $res    = ['status' => 'available'];
                    return $res;
                }
            }
        }

        return ['status' => 'attr_not_found'];
    }

    public function generateCart($request){
        $checkout_sub_total = 0;
        $cart               = Cart::get_cart($request);
        if($cart){
            foreach($cart as $q => $c){
                $id         = $c['product_id'];
                $attr       = $c['attr'];
                $qty        = $c['qty'];
                $request->request->add(['id' => $id,'attr' => $attr,'qty' => $qty,'action' => 'generate']);
                $result     = $this->check_stock($request);
                if($result['status'] == 'available'){
                    $product    = Product::where('id',$id)
                                    ->with('primary_images','product_data_attribute_master.product_data_attribute.product_attribute_data.attribute')
                                    ->where('status','y')
                                    ->first();
                    $attribute          = Product_attribute_data::whereIn('id',$attr)->with('attribute')->get();
                    $temp_attribute     = [];
                    foreach($attribute as $at){
                        $temp_attribute[]   =  [
                                                'attribute' => $at->attribute->attribute_name,
                                                'type'      => $at->attribute->attribute_type,
                                                'value'     => $at->value
                                            ];
                    }
                    $sub_total          = $product->price*$qty; 
                    $data_product       = [
                        'id'        => $product->id,
                        'images'    => $product->primary_images->images_name,
                        'attribute' => $temp_attribute,
                        'name'      => $product->name,
                        'name_alias'=> $product->name_alias,
                        'price'     => (int)$product->price,
                        'sub_total' => $sub_total
                    ];
                    $checkout_sub_total += $sub_total;
                    $request->request->add(['data_product' => $data_product]);
                    Cart::set_product_data($request);
                }else{
                    Cart::remove($request);
                }
            }
            $checkout_data  = [
                'sub_total'     => $checkout_sub_total
            ];
            Cart::set_checkout_data($checkout_data);

            $cart           = Cart::get_cart();       
            $checkout_data  = Cart::get_checkout_data();   
            return ['cart' => $cart,'checkout_data' => $checkout_data];
        }else{
            return 'no_data';
        }
    }

    public function get_total_cart(){
        $total  = Cart::get_total_item();
        return $total;
    }

    public function add_cart($request){
        Cart::add($request);
    }

    public function remove_cart($request){
        Cart::remove($request);
    }
}
