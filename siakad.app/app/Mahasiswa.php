<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model {
    protected $table = 'mst_mhs';
	
    protected $fillable = [
      'th_akademik_id','prodi_id','kelas_id','nim','nama','jk_id','status_id','user_id', 'tanggal_masuk', 'nik', 'tempat_lahir', 'tanggal_lahir', 'agama_id', 'alamat', 'kota_id', 'email', 'hp'
    ];

    public function scopeAktif($query,$nim){
		return $query->where('nim',$nim)->where('status_id',18);
    }

    public function nim(){
		return $this->belongsTo('App\ThAkademik','th_akademik_id');
    }

    public function th_akademik(){
		return $this->belongsTo('App\ThAkademik','th_akademik_id');
    }

    public function prodi(){
		return $this->belongsTo('App\Prodi','prodi_id');
    }

    public function kelas(){
		return $this->belongsTo('App\Ref','kelas_id');
    }

    public function jk(){
		return $this->belongsTo('App\Ref','jk_id');
    }

    public function agama(){
		return $this->belongsTo('App\Ref','agama_id');
    }

    public function status(){
		return $this->belongsTo('App\Ref','status_id');
    }

    public function kelompok(){
		return $this->hasOne('App\PerwalianDetail','nim','nim');
    }

    public function user(){
		return $this->hasOne('App\User','username','nim');
    }

    public function kota(){
		return $this->belongsTo('App\Kota','kota_id');
    }
}