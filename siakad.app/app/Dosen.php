<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model{
	
    protected $table	= 'mst_dosen';
    protected $fillable = ['prodi_id','kode','nidn','nama','jk_id','dosen_status_id','user_id', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'kota_id', 'email', 'hp'];
	
    public function prodi(){
		return $this->belongsTo('App\Prodi','prodi_id');
    }

    public function jk(){
		return $this->belongsTo('App\Ref','jk_id');
    }

    public function status(){
		return $this->belongsTo('App\Ref','dosen_status_id');
    }

    public function kota(){
		return $this->belongsTo('App\Kota','kota_id');
    }
}