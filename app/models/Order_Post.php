<?php namespace digipos\models;

use Illuminate\Database\Eloquent\Model;

class Order_Post extends Model{
	protected $table = 'order_post';
	protected $order_hd = 'digipos\models\Order_hd';

    public function order_hd(){
        return $this->belongsTo($this->order_hd,'orderhd_id');
    }
}