<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class SkripsiUjianSkripsi extends Model
{

    protected $table = 'skripsi_ujian_skripsi';

    public function pengajuan()
    {
        return $this->belongsTo('App\SkripsiPengajuan', 'skripsi_pengajuan_id', 'id');
    }

    public function ujianSkripsiDosen()
    {
        return $this->hasMany('App\SkripsiUjianSkripsiDosen', 'ujian_skripsi_id', 'id');
    }
}
