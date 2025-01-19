<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ThAkademik extends Model{	
    protected $table = 'mst_th_akademik';

    public function scopeAktif($query){
        return $query->where('aktif','Y');
    }
}
