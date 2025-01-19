<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class KuesionerPertanyaanPilihan extends Model
{
    protected $table = 'kuesioner_pertanyaan_pilihan';

    public function pertanyaan()
    {
      return $this->belongsTo('App\KuesionerPertanyaan','kuesioner_pertanyaan_id');
    }
}
