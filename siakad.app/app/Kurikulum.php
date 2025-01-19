<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Kurikulum extends Model{
    protected $table = 'trans_kurikulum';

    public function th_akademik(){
      return $this->belongsTo('App\ThAkademik','th_akademik_id');
    }

    public function prodi(){
      return $this->belongsTo('App\Prodi','prodi_id');
    }

    public function detail(){
      return $this->hasMany('App\KurikulumMataKuliah','kurikulum_id');
    }
}
