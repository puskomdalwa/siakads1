<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model {
    protected $table = 'mst_matakuliah';
    protected $fillable = [
      'prodi_id','kode','nama','sks','smt','aktif','user_id'
    ];

    public function prodi(){
      return $this->belongsTo('App\Prodi','prodi_id');
    }
}
