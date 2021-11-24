<?php namespace digipos\models;

use Illuminate\Database\Eloquent\Model;

class Useraccess extends Model{
	protected $table = 'useraccess';
	protected $user = 'digipos\models\User';
	protected $authmenu = 'digipos\models\Authmenu';

	public function user(){
		return $this->hasOne($this->user,'user_access_id');
	}

	public function authmenu(){
		return $this->hasMany($this->authmenu,'user_access_id');
	}
}
