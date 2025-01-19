<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class JadwalKuliah extends Model
{
    protected $table = 'trans_jadwal_kuliah';

    public function th_akademik()
    {
        return $this->belongsTo('App\ThAkademik', 'th_akademik_id');
    }

    public function prodi()
    {
        return $this->belongsTo('App\Prodi', 'prodi_id');
    }

    public function kelas()
    {
        return $this->belongsTo('App\Ref', 'kelas_id');
    }

    public function kelompok()
    {
        return $this->belongsTo('App\Ref', 'kelompok_id');
    }

    public function hari()
    {
        return $this->belongsTo('App\Ref', 'hari_id');
    }

    public function ruang_kelas()
    {
        return $this->belongsTo('App\Ref', 'ruang_kelas_id');
    }

    public function jamkul()
    {
        return $this->belongsTo('App\Ref', 'jam_kuliah_id');
    }

    public function dosen()
    {
        return $this->belongsTo('App\Dosen', 'dosen_id');
    }

    public function kurikulum_matakuliah()
    {
        return $this->belongsTo('App\KurikulumMataKuliah', 'kurikulum_matakuliah_id');
    }

    public function rps()
    {
        return $this->hasOne('App\RPS', 'jadwal_id');
    }

    public function absensi_detail()
    {
        return $this->hasMany('App\AbsensiDetail', 'trans_jadwal_kuliah_id');
    }
}
