<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class SkripsiPengajuan extends Model {
    protected $table ='skripsi_pengajuan';

    public function th_akademik(){
		return $this->belongsTo('App\ThAkademik','th_akademik_id');
    }

    public function mahasiswa(){
		return $this->belongsTo('App\Mahasiswa','nim','nim');
    }

    public function judul(){
      return $this->hasMany('App\SkripsiJudul','skripsi_pengajuan_id');
    }
}
