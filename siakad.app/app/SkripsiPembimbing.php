<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class SkripsiPembimbing extends Model {
	
    protected $table ='skripsi_pembimbing';

    public function dosen(){
        return $this->belongsTo('App\Dosen', 'mst_dosen_id', 'id');
    }
}
