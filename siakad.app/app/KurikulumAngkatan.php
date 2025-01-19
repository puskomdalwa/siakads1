<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class KurikulumAngkatan extends Model{
  protected $table = 'trans_kurikulum_angkatan';

  public function kurikulum(){
    return $this->belongsTo('App\Kurikulum','kurikulum_id');
  }

  public function th_angkatan(){
    return $this->belongsTo('App\ThAkademik','th_akademik_id');
  }
}
