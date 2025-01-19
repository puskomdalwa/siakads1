<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Info extends Model{
    protected $table ='trans_info';

    public function pengguna(){
		return $this->belongsTo('App\User','user_id');
    }

    public function level(){
		return $this->belongsTo('App\Level','user_level_id');
    }
}
