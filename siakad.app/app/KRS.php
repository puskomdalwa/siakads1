<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class KRS extends Model{
    protected $table = 'trans_krs';
 
    protected $fillable = ['th_akademik_id','prodi_id','kelas_id','tanggal',
		'nim','smt','acc_pa','ket','user_id','transkrip'
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

    public function mahasiswa(){
		return $this->belongsTo('App\Mahasiswa','nim','nim');
    }
}
