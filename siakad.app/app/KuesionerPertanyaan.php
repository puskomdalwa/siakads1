<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class KuesionerPertanyaan extends Model
{
    protected $table = 'kuesioner_pertanyaan';

    public function pilihan()
    {
      return $this->hasMany('App\KuesionerPertanyaanPilihan','kuesioner_pertanyaan_id');
    }
}
