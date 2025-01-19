<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class KompreDosen extends Model{
    protected $table = 'kompre_dosen';   

    public function dosen(){
        return $this->belongsTo(Dosen::class);
    }
}
