<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class KompreNilaiLog extends Model
{
    protected $table = 'kompre_nilai_log';
    protected $guarded = [];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function kompreNilai()
    {
        return $this->belongsTo(KompreNilai::class);
    }
}