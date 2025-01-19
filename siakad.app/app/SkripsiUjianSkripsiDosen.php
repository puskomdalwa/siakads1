<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class SkripsiUjianSkripsiDosen extends Model
{

    protected $table = 'skripsi_ujian_skripsi_dosen';

    public function ujianSkripsi()
    {
        return $this->belongsTo('App\SkripsiUjianSkripsi', 'ujian_skripsi_id', 'id');
    }

    public function dosen()
    {
        return $this->belongsTo('App\Dosen', 'mst_dosen_id', 'id');
    }
}
