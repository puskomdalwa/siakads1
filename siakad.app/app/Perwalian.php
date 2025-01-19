<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Perwalian extends Model {
    protected $table = 'trans_perwalian';

    public function th_akademik(){
      return $this->belongsTo('App\ThAkademik','th_akademik_id');
    }

    public function prodi(){
      return $this->belongsTo('App\Prodi','prodi_id');
    }

    public function kelas(){
      return $this->belongsTo('App\Ref','kelas_id');
    }

    public function kelompok(){
      return $this->belongsTo('App\Ref','kelompok_id');
    }

    public function dosen(){
      return $this->belongsTo('App\Dosen','dosen_id');
    }  
}
