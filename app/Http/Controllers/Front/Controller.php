<?php namespace digipos\Http\Controllers\Front;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Request;
use View;
use Cache;
use Cookie;
use Session;
use App;
use DB;
use Image;

use digipos\models\Config;
use digipos\models\Social_media;
use digipos\models\Front_menu;
use digipos\models\Mslanguage;
use digipos\models\Front_seo_setting;
use digipos\models\Store;
use digipos\models\Order_Status;
use digipos\models\Modem_log;
use digipos\models\Notification;
use Carbon\Carbon;

use digipos\Libraries\Breadcrumb;
use digipos\Libraries\Kyubi;

class Controller extends BaseController {

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $data         = [];
    public $root_path    = '/';
    public $view_path    = 'front';
    public $guard        = 'web';
    public $auth_guard   = 'auth:web';
    public $guest_guard  = 'guest:web';

    public function __construct() {
        
        $this->data['guard']        = $this->guard;
        $this->data['root_path']    = $this->root_path;
        $this->data['canonical']    = $this->root_path;
        $this->data['view_path']    = $this->view_path;
        $this->data['meta_robots']  = 'index';
        $this->rajaongkir_key       = env('RAJAONGKIR_KEY');
        //Global Number
        $no = 1;
        if (Request::has('page')){
            $no = Request::input('page') * 10 - 9;
        }
        $this->data['no']                   = $no;
        $this->data['my_ip']                = $_SERVER['REMOTE_ADDR'];
        $this->data['web_name']             = Config::where('name','web_name')->first()->value;
        $this->data['web_logo']             = Config::where('name','web_logo')->first()->value;
        $this->data['web_email']            = Config::where('name','web_email')->first()->value;
        $this->data['web_title']            = Config::where('name','web_title')->first()->value;
        $this->data['web_description']      = Config::where('name','web_description')->first()->value;
        $this->data['web_keywords']         = Config::where('name','web_keywords')->first()->value;
        $this->data['favicon']              = Config::where('name','favicon')->first()->value;
        $this->data['meta']                 = ['title' => 'Jetfi Indonesia', 'keywords' => 'Jetfi Indonesia', 'description' => 'Jetfi Indonesia'];
        // $this->data['social_media']          = Social_media::get();
        // dd($this->data['meta']);

        $id_lang = $this->getSetLang('');
        // $this->data['menus']             = Front_menu::where([['status','y'],['id_lang', $id_lang]])->get();
        $this->data['lang']              = Mslanguage::where('status','y')->get();

        //declaration class kyubi
         $this->kyubi              = new Kyubi;

        // $path = explode('/',Request::path());
        $this->data['path'] = $this->root_path;

        $path = explode('/',Request::path());
        // dd($path);
        $this->data['path'] = $path;
        $this->data['path2'] = $path;
        // dd($this->data);

        $this->setModemType();
    }

    public function render_view($view = ''){
        $data = $this->data;
        // if($view == "pages.pages.cek_alamat"){
        //     return view($this->view_path.'.'.$view, [
        //         'cek_alamat' => $data->appends(Input::except('page'))
        //     ]);
       
        // }else{
        //     return view($this->view_path.'.'.$view,$data);       
        // }
        // dd($data);
        return view($this->view_path.'.'.$view,$data);  
    }

    public function getSetLang($ids = NULL){
        $default = Mslanguage::where('status','y')->first();
        $get_lang = session('lang');

        if($ids != NULL || $get_lang != NULL) {
            if($ids != NULL) {
                session(['lang' => $ids]);
                $lang = $ids;
            } else{
                $lang = $get_lang;
            }
        } else{
            $lang = $default->id;
        }

        $data = Mslanguage::where([['status','y'],['id',$lang]])->first();
        App::setLocale($data->language_name_alias);
        return $lang;   
    }

    public function load_language($lang){
        $default = Mslanguage::where('status','y')->first();
        $get_lang = session('lang_code');
        if($lang != NULL){
            $language   = 'id';
            session(['lang_code' => $language]);
        }elseif($get_lang != NULL){
            $language   = 'id';
            $language = $get_lang;
        }else{
            $language   = $user->language->language_name_alias;
        }

        // $this->data['global_language'] = $this->cache_query('config',Mslanguage::where('status','y'),'get');
        App::setLocale($language);
    }

    public function getOrderStatus(){
        $data = Order_Status::pluck('order_status_name', 'id')->toArray();

        return $data;
    }

    public function formatArrayToTitikKoma($arr){
        $push_data2     = '';
        foreach($arr as $pn){
            $push_data2 .= ';'.$pn;
        }

        if($push_data2 != ''){
            $push_data2     .=  ';';    
        }
        return $push_data2;
    }

    public function formatTitikKomaToArray($data){
        $data = explode(";",$data);
        $push_data2 = [];
        for($i=0; $i< count($data); $i++){
            if($data[$i] != '' && isset($data[$i])){
                $push_data2[] = $data[$i];
            }
        }
        return $push_data2;
    }

    public function decode_rupiah($price){
        if($price != 'Nan'){
            $price = str_replace(',', '', $price);
            $price = substr($price, 0, strpos($price, '.'));
            return $price;
        }else{
             return null;
        }
    }

    public function increase_version(){
        $config                   = Config::where('name', 'product_version')->first();
        $config->value            += 1; 
        $config->save();
    }

    public function create_modem_log($modem_id, $action, $data){
        if(count($data) > 0){
            if($data['id']){
                $data['id'] = '';
            }
            $data['action'] = $action;
            $data['modem_id'] = $modem_id;
            // dd($data);
            Modem_log::insert($data);
        }
    }

    public function getProvince_rajaongkir($id = ''){
        if($id != ''){
            $url = "https://api.rajaongkir.com/starter/province?id=".$id;
        }else{
            $url = "https://api.rajaongkir.com/starter/province";
        }
        // dd($this->rajaongkir_key);
        $curl = curl_init();

          curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "key: ".$this->rajaongkir_key
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);


        if ($err) {
            $res = array(
                "status" => "error",
                "message" => "cURL Error #:" . $err
            );

          // echo "cURL Error #:" . $err;
        } else {
            $data = json_decode($response);
            $data = $data->rajaongkir;
            $data = json_encode($data->results);
          $res = array(
                "status" => "success",
                "message" => 'OK',
                "data"   => $data
            );
        }

        return json_encode($res);
    }

    public function getCity_rajaongkir($city_id = '', $province_id = ''){
        // "https://api.rajaongkir.com/starter/city?id=39&province=5"
        $url = "https://api.rajaongkir.com/starter/city";
        
        if($city_id != ''){
            $url .= "?id=".$city_id;
        }

        if($province_id != ''){
            $url .= "&id=".$province_id;
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => $url,
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "key: ".$this->rajaongkir_key
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $res = array(
                "status" => "error",
                "message" => "cURL Error #:" . $err
            );
        } else {
            $data = json_decode($response);
            $data = $data->rajaongkir;
            $data = $data->results;
            $res = array(
                "status" => "success",
                "message" => "OK",
                "data"   => $data
            );
        }

        return $res;
    }

    public function getCost_rajaongkir($city_from = '', $city_dest = '', $weight = '', $courier = 'jne'){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "origin=".$city_from."&destination=".$city_dest."&weight=".$weight."&courier=".$courier,
          // CURLOPT_POSTFIELDS => "courier=jne",
          CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded",
            "key: ".$this->rajaongkir_key
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        // dd($response);
        if ($err) {
            $res = array(
                "status" => "error",
                "message" => "cURL Error #:" . $err
            );
        } else {
            $data = json_decode($response);
            $data = $data->rajaongkir;
            if($data->status->code == 400){
                $res  = array(
                    "status" => "error",
                    "code" => $data->status->code,
                    "message" => $data->status->description                
                );
            }else{
                 $res = array(
                    "status" => "success",
                    "code" => $data->status->code,
                    "message" => "OK",
                    "data"   => $data
                );
            }
           
        }

        return $res;
    }

    public function is_connected()
    {
        $connected = @fsockopen("www.example.com", 80); 
                                            //website, port  (try 80 or 443)
        if ($connected){
            $is_conn = true; //action when connected
            fclose($connected);
        }else{
            $is_conn = false; //action in connection failure
        }
        return $is_conn;

    }

    public function setModemType(){
        //modem stock khusus
        // 114: japan
        // 122: South Korea
        // 123: North Korea
        // 228: Taiwan
        // china_roaming
        // 13: Australia
        // 233: Australia

        $daypass                = ['48', '114','122', '198', '228', 'china_roaming', '13', '233'];
        $this->data['daypass']  = $daypass; 
    }

    public function getCityOngkir(){
        $data = DB::table('city_ongkir')->join('city_ongkir_detail')->where('city_ongkir.status', 'y')->get();

        return $data;
    }

    /*
        function get delivery return time by delivery_return_time_id or city_id
    */
    public function getDeliveryReturnTime($delivery_return_time_id = '', $city_id = ''){

        $data = DB::table('delivery_return_time')->join('delivery_return_time_city', 'delivery_return_time_city.delivery_return_time_id', 'delivery_return_time.id');

        if($delivery_return_time_id != ''){
            $data = $data->where('delivery_return_time.id', $delivery_return_time_id);
        }

        if($city_id != ''){
            $data = $data->where('delivery_return_time_city.city_id', $city_id);
        }

        $data = $data->select('delivery_return_time.*', 'delivery_return_time_city.city_id')->first();

        //if data not found in database luar jabodetabek delivery h-3 and return h+3
        if($data == NULL){
            $data = (object)[
                "delivery_day" => 3, 
                "return_day"   => 3
            ];
        }
        return $data;
    }

    public function insertNotification($title, $desc, $type = 'order', $order_id){
        $data                       = new Notification;
        $data->notification_title   = $title;
        $data->description          = $desc;
        $data->type                 = $type;
        $data->order_id             = $order_id;
        $dt                         = date('Y-m-d');
        $data->created_at           = Carbon::parse($dt )->format('Y-m-d');
        $data->save();
    } 

    public function insertOrderLog($order_id, $data, $arr_modem = null){
        $data['order_id'] = $order_id;
        $data['modem_id'] = ($arr_modem != null ? json_encode($arr_modem) : null);
        $data['id'] = '';
        // dd($data);
        DB::table('orderhd_log')->insert($data);
    }

    public function checkOrderPassDay(){
        $current_date = date("Y-m-d");
        $order = DB::table('orderhd')->where([['order_status_id',2],['return_date','<',$current_date]])->get();
        
        if(count($order) > 0){
            $notif = DB::table('notification')->where('type', 'fine')->get();
            if(count($notif) > 0){
                foreach ($order as $key => $value) {
                    $find = DB::table('notification')->where([['type', 'fine'],['order_id', $value->id]])->first();

                    if($find == null){

                        $arr_temp = [
                            "notification_title" => "Rent modem has passed return date, Order No: ".$value->order_no,
                            "type" => "fine",
                            "description" => "Rent modem has passed return date, Order No: ".$value->order_no,
                            "status" => "n",
                            "upd_by" => auth()->guard($this->guard)->user()->user_id,
                            "created_at" => Carbon::now(),
                            "order_id" => $value->id
                        ];
                        DB::table('notification')->insert($arr_temp);
                    }


                    //get interval day
                    $date_interval = $this->kyubi->getDateIntervalToYearMontDay($current_date, $value->return_date);
                    $day_interval =   $date_interval['interval_day'];

                    //get fine cost per day
                    $fine_cost_per_day              = Config::where('id', 19)->first();
                    $fine_cost_per_day      =  $fine_cost_per_day->value;

                    $fine_cost = $fine_cost_per_day * $day_interval;
                    $total = $value->total + $fine_cost;

                    DB::table('orderhd')->where('id', $value->id)->update(["pass_rent_day" => $day_interval, "fine_cost" => $fine_cost]);
                }
            }else{
                foreach ($order as $key => $value) {
                     //get interval day
                    $date_interval = $this->kyubi->getDateIntervalToYearMontDay($current_date, $value->return_date);
                    $day_interval =   $date_interval['interval_day'];

                    //get fine cost per day
                    $fine_cost_per_day              = Config::where('id', 19)->first();
                    $fine_cost_per_day      =  $fine_cost_per_day->value;

                    $fine_cost = $fine_cost_per_day * $day_interval;
                    $total = $value->total;

                    DB::table('orderhd')->where('id', $value->id)->update(["pass_rent_day" => $day_interval, "fine_cost" => $fine_cost, "total" => $total]);

                    $arr_temp = [
                        "notification_title" => "Rent modem has passed return date, Order No: ".$value->order_no,
                        "type" => "fine",
                        "description" => "Rent modem has passed return date, Order No: ".$value->order_no,
                        "status" => "n",
                        "upd_by" => auth()->guard($this->guard)->user()->user_id,
                        "created_at" => Carbon::now(),
                        "order_id" => $value->id
                    ];
                    DB::table('notification')->insert($arr_temp);
                }
               
            }
        }
    }

    public function checkOrderMustReturn(){
        $date = date("Y-m-d");
        $date1 = str_replace('-', '/', $date);
        $tomorrow = date('Y-m-d',strtotime($date1 . "+1 days"));
        // var_dump($date);
        // dd($tomorrow);    
        $order = DB::table('orderhd')->where([['order_status_id',2],['return_date',$tomorrow]])->get();
        // dd($order);
        if(count($order) > 0){
            foreach ($order as $key => $value) {
                $find = DB::table('notification')->where([['type', 'reminder_modem_return_tomorrow'],['order_id', $value->id]])->first();
                // dd($find);
                if(!$find){
                    $date2 = date('Y-m-d',strtotime($date1));
                    $arr_temp = [
                        "notification_title" => "Reminder return modem, Order No: ".$value->order_no,
                        "type" => "reminder_modem_return_tomorrow",
                        "description" => "Customer should return modem on ".$date2.", Order No: ".$value->order_no,
                        "status" => "n",
                        "upd_by" => auth()->guard($this->guard)->user()->user_id,
                        "created_at" => Carbon::now(),
                        "order_id" => $value->id
                    ];

                    DB::table('notification')->insert($arr_temp);
                }
            }

        }
    }

    public function checkOrderMustDelivery(){
        $date = date("Y-m-d");
        $date1 = str_replace('-', '/', $date);
        $date_target = date('Y-m-d',strtotime($date1 . "+1 days"));
        // var_dump($date);
        // dd($date_target);    
        $arr_date = [$date,$date_target];
        $order = DB::table('orderhd')->where('order_status_id',7)->whereIn('delivery_date',$arr_date)->get();//get data order status paid and delivery_date tommorow or today
        // dd($order);
        if(count($order) > 0){
            foreach ($order as $key => $value) {
                $find = DB::table('notification')->where([['type', 'reminder_modem_delivery_date'],['order_id', $value->id]])->first();
                // dd($find);
                if(!$find){
                    $date2 = date('Y-m-d',strtotime($date1));
                    $arr_temp = [
                        "notification_title" => "Reminder Delivery modem(s), Order No: ".$value->order_no,
                        "type" => "order",
                        "description" => "Modem(s) with Order No: ".$value->order_no."Tommorow or today must be sent",
                        "status" => "n",
                        "upd_by" => auth()->guard($this->guard)->user()->user_id,
                        "created_at" => Carbon::now(),
                        "order_id" => $value->id
                    ];

                    DB::table('notification')->insert($arr_temp);
                }
            }

        }
    }

    public function pusher(){
        // $options = array(
        //     'cluster' => 'ap1',
        //     'useTLS' => false
        // );

        // $pusher = new Pusher\Pusher(
        // 'aec2592ecc4afa736616',
        // 'f33d94e6631f5d2a1d82',
        // '596870',
        // $options
        // );
        // // dd($pusher);
        // $data['message'] = 'hello world';
        // $pusher->trigger('my-channel', 'my-event', $data);
        // dd(Pusher);
    }

    public function build_image($f){
        if (!file_exists($f['file_opt']['path'])) {
            mkdir($f['file_opt']['path'], 0777, true);
        }
        $extension  = Request::file($f['name'])->getClientOriginalExtension();

        if(in_array($extension,['pdf','word','zip','xlsx','xls','rar','ppt'])){
            $filename   = $f['name'].'.'.$extension;
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
                $image = $image->save($f['file_opt']['path'].$filename);                
            }
            if(isset($f['old_image'])){
                File::delete($f['file_opt']['path'].$f['old_image']);
            }
        }
        return $filename;
    }
}
