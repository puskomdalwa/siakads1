<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class PerwalianDetail extends Model{
    protected $table = 'trans_perwalian_detail';

    public function perwalian(){
      return $this->belongsTo('App\Perwalian','perwalian_id');
    }

    public function mahasiswa(){
      return $this->belongsTo('App\Mahasiswa','nim','nim');
    }
}
