<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class TranskripCetak extends Model {
    protected $table = 'transkrip_cetak';

    public function th_akademik() {
        return $this->belongsTo('App\ThAkademik', 'th_akademik_id', 'id');
    }

    public function mahasiswa(){
        return $this->belongsTo('App\Mahasiswa', 'nim', 'nim');
    }
}
