<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class KeuanganTagihan extends Model {
    protected $table = 'keuangan_tagihan';

    public function th_akademik(){
		return $this->belongsTo('App\ThAkademik','th_akademik_id');
    }

    public function th_angkatan(){
		return $this->belongsTo('App\ThAkademik','th_angkatan_id');
    }

    public function prodi(){
		return $this->belongsTo('App\Prodi','prodi_id');
    }

    public function kelas(){
		return $this->belongsTo('App\Ref','kelas_id');
    }

    public function form_schadule(){
		return $this->belongsTo('App\FormSchadule','form_schadule_id');
    }
}
