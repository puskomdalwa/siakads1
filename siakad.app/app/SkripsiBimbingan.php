<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class SkripsiBimbingan extends Model
{
  protected $table = 'skripsi_bimbingan';

  public function judul()
  {
    return $this->belongsTo('App\SkripsiJudul', 'judul_id');
  }

  public function pembimbing()
  {
    return $this->belongsTo('App\SkripsiPembimbing', 'pembimbing_id');
  }
}
