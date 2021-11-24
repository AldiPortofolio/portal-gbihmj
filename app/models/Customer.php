<?php namespace digipos\models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model{
	protected $table 				= 'customer';
	protected $preorder   			= 'digipos\models\Preorder';
  
    public function preorder(){
        return $this->hasMany($this->preorder,'customer_id');
    }
}
