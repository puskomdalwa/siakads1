<?php
namespace App\Http\Services;

use App\KRSDetail;
use App\ThAkademik;
use Illuminate\Support\Facades\Auth;

class ServiceKuesioner
{
    public static function cekIsi()
    {
        $thAkademikAktif = ThAkademik::Aktif()->first()->id;
        $nim = Auth::user()->username;

        $belumKuesioner = KRSDetail::
            select('trans_jadwal_kuliah.dosen_id', 'mst_dosen.nama as dosen_nama', 'mst_dosen.kode as dosen_kode', 'mst_th_akademik.kode as th_akademik_kode', 'mst_th_akademik.id as th_akademik_id', 'kuesioner_jawaban.nim as kuesioner_nim')
            ->join('trans_jadwal_kuliah', 'trans_jadwal_kuliah.id', 'trans_krs_detail.jadwal_kuliah_id')
            ->join('mst_dosen', 'trans_jadwal_kuliah.dosen_id', '=', 'mst_dosen.id')
            ->join('mst_th_akademik', 'trans_krs_detail.th_akademik_id', '=', 'mst_th_akademik.id')
            ->leftJoin('kuesioner_jawaban', function ($join) {
                $join->on('trans_jadwal_kuliah.th_akademik_id', '=', 'kuesioner_jawaban.th_akademik_id');
                $join->on('trans_jadwal_kuliah.dosen_id', '=', 'kuesioner_jawaban.dosen_id');
                $join->on('trans_krs_detail.nim', '=', 'kuesioner_jawaban.nim');
            })
            ->where('trans_krs_detail.nim', $nim)
            ->where('trans_jadwal_kuliah.th_akademik_id', '<', $thAkademikAktif)
            ->whereNull('kuesioner_jawaban.nim')
            ->groupBy('trans_jadwal_kuliah.dosen_id', 'mst_dosen.nama', 'mst_dosen.kode', 'mst_th_akademik.kode', 'mst_th_akademik.id', 'kuesioner_jawaban.nim')
            ->count();

        if ($belumKuesioner > 0) {
            return false;
        } else {
            return true;
        }
    }
}