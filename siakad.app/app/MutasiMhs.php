<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class MutasiMhs extends Model {
    protected $table = 'trans_mutasi_mhs';

    public function mahasiswa(){
		return $this->belongsTo('App\Mahasiswa','nim','nim');
    }

    public function status(){
		return $this->belongsTo('App\Ref','status_id');
    }
}
