<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class KompreNilai extends Model
{
    protected $table = 'kompre_nilai';

    public function kompreDosen()
    {
        return $this->belongsTo(KompreDosen::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}