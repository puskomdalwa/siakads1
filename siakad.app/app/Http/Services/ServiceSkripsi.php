<?php
namespace App\Http\Services;

use App\KRS;
use App\Dosen;
use App\KRSDetail;
use App\ThAkademik;
use App\SkripsiJudul;
use App\SkripsiPengajuan;
use App\SkripsiPembimbing;
use App\SkripsiUjianProposal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ServiceSkripsi
{
    public static function cekPenguji($jenis, $penguji1, $penguji2, $pengajuanId)
    {
        try {

            $dataJenis = ['proposal', 'skripsi'];
            if (!in_array($jenis, $dataJenis)) {
                return abort(500, 'Jenis harus proposal atau skripsi, cek controller kembali');
            }

            if ($penguji1 == $penguji2) {
                return abort(500, 'Penguji 1 dan Penguji 2 harus berbeda');
            }

            $mengajarMax = Dosen::leftJoin('skripsi_ujian_' . $jenis . '_dosen', 'skripsi_ujian_' . $jenis . '_dosen.mst_dosen_id', '=', 'mst_dosen.id')
                ->select('mst_dosen.id', 'mst_dosen.nama', 'mst_dosen.kode')
                ->addSelect(DB::raw('count(skripsi_ujian_' . $jenis . '_dosen.mst_dosen_id) as jumlah'))
                ->groupBy('mst_dosen.id', 'mst_dosen.nama', 'mst_dosen.kode')
                ->orderBy('jumlah', 'desc')
                ->first()->jumlah;

            if ($mengajarMax <= 0) {
                return [
                    'status' => true,
                    'message' => 'success',
                ];
            }

            $mengajarPenguji1 = Dosen::leftJoin('skripsi_ujian_' . $jenis . '_dosen', 'skripsi_ujian_' . $jenis . '_dosen.mst_dosen_id', '=', 'mst_dosen.id')
                ->select('mst_dosen.id', 'mst_dosen.nama', 'mst_dosen.kode')
                ->addSelect(DB::raw('count(skripsi_ujian_' . $jenis . '_dosen.mst_dosen_id) as jumlah'))
                ->where('mst_dosen.id', $penguji1)
                ->groupBy('mst_dosen.id', 'mst_dosen.nama', 'mst_dosen.kode')
                ->first()->jumlah;
            $mengajarPenguji2 = Dosen::leftJoin('skripsi_ujian_' . $jenis . '_dosen', 'skripsi_ujian_' . $jenis . '_dosen.mst_dosen_id', '=', 'mst_dosen.id')
                ->select('mst_dosen.id', 'mst_dosen.nama', 'mst_dosen.kode')
                ->addSelect(DB::raw('count(skripsi_ujian_' . $jenis . '_dosen.mst_dosen_id) as jumlah'))
                ->where('mst_dosen.id', $penguji2)
                ->groupBy('mst_dosen.id', 'mst_dosen.nama', 'mst_dosen.kode')
                ->first()->jumlah;

            $ujian = DB::table('skripsi_ujian_' . $jenis)->where('skripsi_pengajuan_id', $pengajuanId)->first();
            $penguji = $ujian ? DB::table('skripsi_ujian_' . $jenis . '_dosen')->where('ujian_' . $jenis . '_id', $ujian->id)->pluck('mst_dosen_id')->toArray() : [];

            if (!in_array($penguji1, $penguji)) {
                if ($mengajarPenguji1 >= $mengajarMax) {
                    return abort(500, "jumlah mengajar dosen 1 melebihi batas");
                }
            }
            if (!in_array($penguji2, $penguji)) {
                if ($mengajarPenguji2 >= $mengajarMax) {
                    return abort(500, "jumlah mengajar dosen 2 melebihi batas");
                }
            }

            return [
                'status' => true,
                'message' => 'success',
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage(),
                'error' => $th->getMessage(),
            ];
        }

    }
    public static function cekPembimbing($pembimbing1, $pembimbing2, $pengajuanId)
    {
        try {

            if ($pembimbing1 == $pembimbing2) {
                return abort(500, 'Penguji 1 dan Penguji 2 harus berbeda');
            }

            $mengajarMax = Dosen::leftJoin('skripsi_pembimbing', 'skripsi_pembimbing.mst_dosen_id', '=', 'mst_dosen.id')
                ->select('mst_dosen.id', 'mst_dosen.nama', 'mst_dosen.kode')
                ->addSelect(DB::raw('count(skripsi_pembimbing.mst_dosen_id) as jumlah'))
                ->groupBy('mst_dosen.id', 'mst_dosen.nama', 'mst_dosen.kode')
                ->orderBy('jumlah', 'desc')
                ->first()->jumlah;
                
            if ($mengajarMax <= 0) {
                return [
                    'status' => true,
                    'message' => 'success',
                ];
            }
            $mengajarPembimbing1 = Dosen::leftJoin('skripsi_pembimbing', 'skripsi_pembimbing.mst_dosen_id', '=', 'mst_dosen.id')
                ->select('mst_dosen.id', 'mst_dosen.nama', 'mst_dosen.kode')
                ->addSelect(DB::raw('count(skripsi_pembimbing.mst_dosen_id) as jumlah'))
                ->where('mst_dosen.id', $pembimbing1)
                ->groupBy('mst_dosen.id', 'mst_dosen.nama', 'mst_dosen.kode')
                ->first()->jumlah;
            $mengajarPembimbing2 = Dosen::leftJoin('skripsi_pembimbing', 'skripsi_pembimbing.mst_dosen_id', '=', 'mst_dosen.id')
                ->select('mst_dosen.id', 'mst_dosen.nama', 'mst_dosen.kode')
                ->addSelect(DB::raw('count(skripsi_pembimbing.mst_dosen_id) as jumlah'))
                ->where('mst_dosen.id', $pembimbing2)
                ->groupBy('mst_dosen.id', 'mst_dosen.nama', 'mst_dosen.kode')
                ->first()->jumlah;

            $pembimbing = SkripsiPembimbing::where('skripsi_pengajuan_id', $pengajuanId)->get()->pluck('mst_dosen_id')->toArray();

            if (!in_array($pembimbing1, $pembimbing)) {
                if ($mengajarPembimbing1 >= $mengajarMax) {
                    return abort(500, "jumlah mengajar dosen 1 melebihi batas");
                }
            }
            if (!in_array($pembimbing2, $pembimbing)) {
                if ($mengajarPembimbing2 >= $mengajarMax) {
                    return abort(500, "jumlah mengajar dosen 2 melebihi batas");
                }
            }

            return [
                'status' => true,
                'message' => 'success',
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage(),
                'error' => $th->getMessage(),
            ];
        }

    }

    public static function cekMahasiswa()
    {
        try {
            $krs = KRS::where('nim', Auth::user()->mahasiswa->nim)
                ->where('th_akademik_id', ThAkademik::Aktif()->first()->id)
                ->first();

            if (!$krs) {
                return abort(500, 'KRS tidak ditemukan');
            }
            if ($krs->acc_pa != "Setujui") {
                return abort(500, 'KRS belum disetujui');
            }

            $krsDetail = KRSDetail::join('trans_jadwal_kuliah', 'trans_jadwal_kuliah.id', '=', 'trans_krs_detail.jadwal_kuliah_id')
                ->join('trans_kurikulum_matakuliah', 'trans_kurikulum_matakuliah.id', '=', 'trans_jadwal_kuliah.kurikulum_matakuliah_id')
                ->join('mst_matakuliah', 'mst_matakuliah.id', '=', 'trans_kurikulum_matakuliah.matakuliah_id')
                ->where('trans_krs_detail.krs_id', $krs->id)
                ->where('mst_matakuliah.nama', 'LIKE', 'skripsi')
                ->select('trans_krs_detail.*', 'mst_matakuliah.nama as matakuliah_nama')
                ->first();

            if (!$krsDetail) {
                return abort(500, 'Belum mengambil skripsi');
            }
            return [
                'status' => true,
                'message' => 'success'
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    public static function cekJudulMahasiswa($judulId)
    {
        try {
            $judul = SkripsiJudul::findOrFail($judulId);
            if ($judul->pengajuan->nim != Auth::user()->mahasiswa->nim) {
                return abort(500, 'Judul bukan milik mahasiswa ini');
            }
            return [
                'status' => true,
                'message' => 'success'
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
    }
}