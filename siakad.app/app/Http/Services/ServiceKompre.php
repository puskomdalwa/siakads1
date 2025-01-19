<?php
namespace App\Http\Services;

use App\BobotNilai;
use App\KompreDosen;
use App\KompreNilai;
use App\KRS;
use App\KRSDetail;

class ServiceKompre
{
    public static function inputNilai($mahasiswa, $thAkademik)
    {
        try {
            $jumlahDosen = 6;

            // cek apakah sudah krs
            $krs = KRS::where([
                ['th_akademik_id', $thAkademik],
                ['nim', $mahasiswa->nim],
                ['prodi_id', $mahasiswa->prodi_id]
            ])->first();

            if (!$krs) {
                return [
                    'status' => false,
                    'message' => 'Belum KRSan'
                ];
            }

            // cek apakah ada kompre di krs
            $krsDetail = KRSDetail::join('trans_jadwal_kuliah', 'trans_jadwal_kuliah.id', '=', 'trans_krs_detail.jadwal_kuliah_id')
                ->join('trans_kurikulum_matakuliah', 'trans_kurikulum_matakuliah.id', '=', 'trans_jadwal_kuliah.kurikulum_matakuliah_id')
                ->join('mst_matakuliah', 'mst_matakuliah.id', '=', 'trans_kurikulum_matakuliah.matakuliah_id')
                ->where('mst_matakuliah.nama', 'LIKE', '%kompre%')
                ->where('mst_matakuliah.sks', 0)
                ->where('trans_krs_detail.nim', $mahasiswa->nim)
                ->select('trans_krs_detail.*', 'mst_matakuliah.nama as matakuliah_nama')
                ->first();

            if (!$krsDetail) {
                return [
                    'status' => false,
                    'message' => 'Tidak ada MK kompre'
                ];
            }

            $nilai = [];
            for ($i = 1; $i <= $jumlahDosen; $i++) {
                $kompreDosen = KompreDosen::where('penguji', $i)->where('jenis_kelamin', $mahasiswa->jk->kode)->first();
                if (!$kompreDosen) {
                    $nilai[$i] = 0.0;
                    continue;
                }

                $kompreNilai = KompreNilai::where('mahasiswa_id', $mahasiswa->id)
                    ->where('kompre_dosen_id', $kompreDosen->id)
                    ->first();

                $nilai[$i] = $kompreNilai ? $kompreNilai->nilai : 0.0;
            }

            // input nilai kompre
            $cekLengkap = true;
            if (in_array(0.0, $nilai)) {
                $cekLengkap = false;
            }

            if (!$cekLengkap) {
                $krsDetail->nilai_akhir = null;
                $krsDetail->nilai_bobot = null;
                $krsDetail->nilai_huruf = null;
                $krsDetail->save();
                return [
                    'status' => false,
                    'message' => 'Nilai belum lengkap'
                ];
            }

            $sum = array_sum($nilai);
            $count = count($nilai);
            $average = $sum / $count;
            $average = round($average);

            $bobot = BobotNilai::where('nilai_max', '>=', $average)
                ->where('nilai_min', '<=', ceil($average))
                ->orderBy('nilai_max', 'asc')
                ->first();

            $krsDetail->nilai_akhir = $average;
            $krsDetail->nilai_bobot = $bobot->nilai_bobot;
            $krsDetail->nilai_huruf = $bobot->nilai_huruf;
            $krsDetail->save();

            return [
                'status' => true,
                'message' => 'Berhasil input nilai',
                'nilai' => $average
            ];
        } catch (\Throwable $th) {
            //throw $th;
            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }

    }
}