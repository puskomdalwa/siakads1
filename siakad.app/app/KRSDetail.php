<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class KRSDetail extends Model {
    protected $table = 'trans_krs_detail';

    protected $fillable = ['th_akademik_id','nim','nama_mhs','kode_mk',
		'nama_mk','sks_mk','smt_mk','nilai_bobot','nilai_huruf','user_id', 'transkrip'
    ];

    public function th_akademik(){
      return $this->belongsTo('App\ThAkademik','th_akademik_id');
    }

    public function prodi(){
      return $this->belongsTo('App\Prodi','prodi_id');
    }

    public function kelas(){
      return $this->belongsTo('App\Ref','kelas_id');
    }

    public function jadwal_kuliah(){
      return $this->belongsTo('App\JadwalKuliah','jadwal_kuliah_id');
    }

    public function jamkul(){
      return $this->belongsTo('App\Ref','jadwal_kuliah_id');
    }

    public function mahasiswa(){
      return $this->belongsTo('App\Mahasiswa','nim','nim');
    }

    public function dosen(){
      return $this->belongsTo('App\Dosen','dosen_id');
    }
}
