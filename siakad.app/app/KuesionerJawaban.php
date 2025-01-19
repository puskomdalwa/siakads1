<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class KuesionerJawaban extends Model
{
    protected $table = 'kuesioner_jawaban';

    public function dosen()
    {
      return $this->belongsTo('App\Dosen','dosen_id');
    }
}
