<?php namespace digipos\Http\Controllers\Api;

use digipos\models\Pages;
use digipos\models\Socialmedia;
use digipos\models\Config;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Request;
use View;
use Cache;
use Cookie;
use App;
use DB;
use Image;

class Controller extends BaseController {

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $data         = [];
    public $root_path    = '/';
    // public $base_url     = 'https://gbuweb.com';
    // public $base_url     = 'D://bootcamp/gbi_hmj';
    public $local         = false;
    public $image_path    = '/components/both/images';
    public $music_path    ='/components/both/music';
    public $view_path    = 'api';
    public $guard        = 'web';
    public $auth_guard   = 'auth:api';
    public $guest_guard  = 'guest:api';

    public function __construct() {
        $this->data['guard']        = $this->guard;
        $this->data['root_path']    = url($this->root_path);
        $this->data['view_path']    = $this->view_path;

        if($this->local){
            $this->data['base_url'] = 'D://bootcamp/gbi_hmj';
            $this->data['image_path']   = $this->data['base_url'].'/components/both/images';
            $this->data['music_path']   = $this->data['base_url'].'/components/both/audio';
            $this->data['file_path']   = $this->data['base_url'].'/components/both/file';

            $this->data['image_path_2']   = 'D://bootcamp/gbi_hmj/components/both/images';
            $this->data['audio_path_2']   = 'D://bootcamp/gbi_hmj//components/both/audio';
            $this->data['file_path_2']   = 'D://bootcamp/gbi_hmj//components/both/file';
        }else{
            $this->data['base_url']     = 'https://gbuweb.com';
            $this->data['image_path']   = $this->data['base_url'].'/components/both/images';
            $this->data['audio_path']   = $this->data['base_url'].'/components/both/audio';
            $this->data['file_path']   = $this->data['base_url'].'/components/both/file';

            $this->data['image_path_2']   = '/home/gbuweb/public_html/components/both/images';
            $this->data['audio_path_2']   = '/home/gbuweb/public_html/components/both/audio';
            $this->data['file_path_2']   = '/home/gbuweb/public_html/components/both/file';
        }
      

        //Global Number
        $no = 1;
        if (Request::has('page')){
            $no = Request::input('page') * 10 - 9;
        }
        $this->data['no']       = $no;
        $this->data['my_ip']    = $_SERVER['REMOTE_ADDR'];
        
        //Set Global
        $query_config = Config::where('config_type','front')->orwhere('config_type','both');
        $config = $this->cache_query('config',$query_config,'get');
        foreach($query_config->get() as $c){

            $this->data[$c->name] = $c->value;
            $this->data['email_config'][$c->name] = $c->value;
        }
        //Set Meta
        $this->data['meta_full_image']  = asset('components/both/images/web/'.$this->data['web_logo']);                                      
        $this->set_meta();

        // $this->data['social_media']     = $this->cache_query('social_media',Socialmedia::where('status', 'y')->orderBy('order','asc'),'get');
        // $this->data['helpful_links']    = $this->cache_query('helpful_links',Pages::where('status', 'y')->orderBy('order','asc')->take(8),'get');
        
        // $popup_cookie                   = Cookie::get('alskdj');
        // if(!$popup_cookie){
        //     $popup                      = Popup::where('valid_from', '<=', date('Y-m-d H:i:s'))
        //                                     ->where('valid_to', '>=', date('Y-m-d H:i:s'))
        //                                     ->orderBy('id', 'desc')
        //                                     ->first();
        //     if($popup){
        //         $data_pop               =  [
        //                                     'name'  => $popup->popup_name,
        //                                     'url'   => $popup->url,
        //                                     'image' => $popup->image
        //         ];
        //         Cookie::queue(Cookie::make('alskdj',$data_pop,60*24));
        //         $this->data['popup_cookie'] = $data_pop;
        //     }
        // }

    }

    public function render_view($view = ''){
        $data = $this->data;
        return view($this->view_path.'.'.$view,$data);       
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
        Cache::flush();
        return $c;
    }
   
    public function cache_query_content($name,$table,$data,$with=[],$time = 60){
        $c  = Cache::remember($name, $time,function() use($table,$data,$with){
                $res    = [];
                $data_with  = $with;
                foreach($data as $fc){
                    $q      = $table->where('id',$fc);
                    if(count($with) > 0){
                        $q  = $q->with($data_with);
                    }
                    $res[]  = $q->first();
                }
                return $res;
            });
        //Cache::flush();
        return $c;
    }

    public function set_meta(){
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

    public function set_dif_time($sum,$dif,$unit,$time){
        $q  =   strftime('%H:%M',strtotime($sum.$dif." ".$unit, strtotime($time)));
        return $q;
    }

    public function get_dif_time($dtime,$atime){
        $nextDay    =$dtime>$atime?1:0;
        $dep        =EXPLODE(':',$dtime);
        $arr        =EXPLODE(':',$atime);
        $diff       =ABS(MKTIME($dep[0],$dep[1],0,DATE('n'),DATE('j'),DATE('y'))-MKTIME($arr[0],$arr[1],0,DATE('n'),DATE('j')+$nextDay,DATE('y')));
        $hours      =FLOOR($diff/(60*60));
        $mins       =FLOOR(($diff-($hours*60*60))/(60));
        $secs       =FLOOR(($diff-(($hours*60*60)+($mins*60))));
        IF(STRLEN($hours)<2){$hours="0".$hours;}
        IF(STRLEN($mins)<2){$mins="0".$mins;}
        IF(STRLEN($secs)<2){$secs="0".$secs;}
        return $hours.':'.$mins;
    }

    public function check_token($phone, $token){
		$user = DB::table('user')->where([['phone', $phone],['token', $token]])->first();
		// return response()->json($user);

		if($user){ //if user token valid
            if($user->status == 'n'){ //if user status not verified
                $res 	= array('status' => 'invalid', 'status_code' => 2, 'message' => 'User status not verififed !');
                return $res;
            }else{
                $res 	= array('status' => 'success', 'status_code' => 1, 'message' => 'Token valid', 'user_id' => $user->id);
                return $res;
            }
		}else{ //user token not valid
            $res = array('status' => 'expired', 'status_code' => 3, 'message' => 'Token invalid !');
			return $res;
		}
	}

    public function build_image($f){
        // return $f['file_opt']['path'].'-'.file_exists($f['file_opt']['path']);
        if (!file_exists($f['file_opt']['path'])) {
            mkdir($f['file_opt']['path'], 0777, true);
        }
        // return 'test';
        $extension  = Request::file($f['name'])->getClientOriginalExtension();
        if(in_array($extension,['pdf','word','zip','xlsx','xls','rar','ppt'])){
            $filename   = Request::file($f['name'])->getClientOriginalName();
            Request::file($f['name'])->move($f['file_opt']['path'], $filename);
        }else{
            if(isset($f['crop']) && $f['crop'] == 'y'){
                $img = Request::input($f['name'].'_crop');
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $thumbnail = base64_decode($img);
                $filename   = str_random(7).'.png';
                file_put_contents($f['file_opt']['path'].$filename, $thumbnail);

            }else{
                $filename = str_random(6) . '_' . str_replace(' ','_',Request::file($f['name'])->getClientOriginalName());   
                $image = Image::make(Request::file($f['name'])->getRealPath());
                if (isset($f['file_opt']['width']) && isset($f['file_opt']['height'])){
                    $image = $image->resize($f['file_opt']['width'],$f['file_opt']['height']);
                }
                // $source = storage_path().'/app/public/cover_images/'.$filename;
                // $target = public_path('cover_images/' . $filename);
                // return Request::file($f['name'])->getRealPath();
                // return 'test 2';
                $image = $image->save($f['file_opt']['path'].$filename);    
                // return 'test';
                
            }
            if(isset($f['old_image'])){
                File::delete($f['file_opt']['path'].$f['old_image']);
            }
        }
        return $filename;
    }

}
