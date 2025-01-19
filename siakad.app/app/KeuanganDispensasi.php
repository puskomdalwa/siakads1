<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class KeuanganDispensasi extends Model {
	protected $table = 'keuangan_dispensasi';

	public function th_akademik(){
		return $this->belongsTo('App\ThAkademik','th_akademik_id');
	}

	public function mahasiswa(){
		return $this->belongsTo('App\Mahasiswa','nim','nim');
	}
}
