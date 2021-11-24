<?php namespace digipos\models;

use Illuminate\Database\Eloquent\Model;

class Order_hd extends Model{
	protected $table 		= 'orderhd';
	protected $order_status = 'digipos\models\Order_status';
	protected $order_dt 	      = 'digipos\models\Order_dt';
	protected $delivery_area 	 = 'digipos\models\Delivery_area';
    protected $delivery_method  = 'digipos\models\Delivery_method';
    protected $delivery_city    = 'digipos\models\City';
    protected $return_city  = 'digipos\models\City';
    protected $delivery_province    = 'digipos\models\Province';
	protected $return_province 	= 'digipos\models\Province';
	protected $promo 	= 'digipos\models\Promo';

    public function order_status(){
        return $this->belongsTo($this->order_status,'order_status_id');
    }

    public function delivery_area(){
        return $this->belongsTo($this->delivery_area,'delivery_area_id');
    }

    public function delivery_method(){
        return $this->belongsTo($this->delivery_method,'delivery_method_id');
    }

    public function return_area(){
        return $this->belongsTo($this->delivery_area,'return_area_id');
    }

    public function return_method(){
        return $this->belongsTo($this->delivery_method,'return_method_id');
    }

    public function promo(){
        return $this->belongsTo($this->promo,'promo_id');
    }

    public function order_dt(){
        return $this->hasMany($this->order_dt,'orderhd_id');
    }

    public function delivery_city(){
        return $this->belongsTo($this->delivery_city,'delivery_city_id');
    }

    public function delivery_province(){
        return $this->belongsTo($this->delivery_province,'delivery_province_id');
    }

    public function return_city(){
        return $this->belongsTo($this->return_city,'return_city_id');
    }

    public function return_province(){
        return $this->belongsTo($this->return_province,'return_province_id');
    }
}