<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class PT extends Model {
    protected $table ='mst_pt';

    public function kota(){
      return $this->belongsTo('App\Kota','kota_id');
    }
}
