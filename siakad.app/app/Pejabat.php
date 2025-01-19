<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Pejabat extends Model
{
    protected $table ='mst_pejabat';

    public function jabatan()
    {
      return $this->belongsTo('App\Ref','jabatan_id');
    }
}
