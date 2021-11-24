<?php namespace digipos\models;

use Illuminate\Database\Eloquent\Model;

class Msmenu extends Model{
	protected $table = 'msmenu';
	protected $msmenu = 'digipos\models\Msmenu';

	public function child_menu(){
		return $this->hasMany($this->msmenu,'menu_id');
	}
}
