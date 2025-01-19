<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class BobotNilai extends Model{
    protected $table = 'mst_grade_nilai';

    public function th_akademik(){
		return $this->belongsTo('App\ThAkademik','th_akademik_id');
    }
}
