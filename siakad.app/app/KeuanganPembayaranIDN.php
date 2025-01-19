<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class KeuanganPembayaranIDN extends Model {
    protected $table = 'idn_pembayaran';

    public function th_akademik(){
		return $this->belongsTo('App\ThAkademik','th_akademik_id');
    }

    public function mahasiswa(){
		return $this->belongsTo('App\Mahasiswa','nim','bill_key');
    }

    public function tagihan(){
		return $this->belongsTo('App\KeuanganTagihan','tagihan_id');
    }
}
