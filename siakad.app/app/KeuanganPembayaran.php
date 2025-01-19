<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class KeuanganPembayaran extends Model {
    protected $table = 'keuangan_pembayaran';

    public function th_akademik(){
		return $this->belongsTo('App\ThAkademik','th_akademik_id');
    }

    public function mahasiswa(){
		return $this->belongsTo('App\Mahasiswa','nim','nim');
    }

    public function tagihan(){
		return $this->belongsTo('App\KeuanganTagihan','tagihan_id');
    }
}
