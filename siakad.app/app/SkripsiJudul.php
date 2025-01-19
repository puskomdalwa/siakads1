<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class SkripsiJudul extends Model {
	
    protected $table ='skripsi_judul';

    public function pengajuan(){
        return $this->belongsTo('App\SkripsiPengajuan', 'skripsi_pengajuan_id', 'id');
    }
}
