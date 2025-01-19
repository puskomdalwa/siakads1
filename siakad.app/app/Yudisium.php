<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Yudisium extends Model
{
    protected $table = 'trans_yudisium';

    public function th_akademik()
    {
      return $this->belongsTo('App\ThAkademik','th_akademik_id');
    }

    public function th_angkatan()
    {
      return $this->belongsTo('App\ThAkademik','th_angkatan_id');
    }

    public function prodi()
    {
      return $this->belongsTo('App\Prodi','prodi_id');
    }

    public function kelas()
    {
      return $this->belongsTo('App\Ref','kelas_id');
    }

    public function kelompok()
    {
      return $this->belongsTo('App\Ref','kelompok_id');
    }

    public function mahasiswa()
    {
      return $this->belongsTo('App\Mahasiswa','nim','nim');
    }
}
