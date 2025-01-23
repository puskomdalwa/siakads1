<?php

namespace App\Http\Controllers;

use App\Absensi;
use App\AbsensiDetail;
use App\BobotNilai;
use App\Dosen;
use App\Http\Services\ServiceSiswa;
use App\JadwalKuliah;
use App\KeuanganPembayaran;
use App\KRS;
use App\KRSDetail;
use App\KRSDetailNilai;
use App\Mahasiswa;
use App\MataKuliah;
use App\Perwalian;
use App\Ref;
use App\ThAkademik;
use Illuminate\Http\Request;

class TestingController extends Controller
{
  public function tt($data)
  {
    echo '<pre>' . var_export($data, true) . '</pre>';
    die();
  }

  public function index()
  {
    try {
      /*$jadwalKuliah = JadwalKuliah::where('id', 6337)->first();*/
      /*$jadwalKuliah->kelompok_id = 434;*/
      /*$jadwalKuliah->ruang_kelas_id = 465;*/
      /*$jadwalKuliah->save();*/
      /*self::deleteKrsWithJadwalKuliah(6337);*/
      /*return self::storeKrsByJadwalKuliahComplete('20242', 6337, 'MPI23LC', 'RUANG MPI 4A PUTRA');*/
      // self::isiNilaiPersemester("202085140016", "20212", ["B", "B+"]);
      // self::isiNilaiPersemester("202085140016", "20221", ["B", "B+"]);
      // return 'wes';
      // return self::pembayaran();
      // return self::deleteKrs();
      // self::krsMpi('20241', 5999, 'PAI2021L', 'RUANG PAI 7A PUTRA');
      // self::krsMpi('20241', 6000, 'PAI2021LB', 'RUANG PAI 7B PUTRA');
      // return self::krsMpi('20241', 6001, 'PAI2021P', 'RUANG PAI 7 PUTRI');
      // return self::isiNilaiBatch();
      // return self::sinkronPMB();
      // return self::fixKrsKosong();
      // return self::isiNilaiKosong();
      // return self::deleteAbsensiNilai('202085010176', '20201');
      // return self::isiNilaiFixed('201785140101', '20201', [
      //     [
      //         'kode' => '14141065',
      //         'nilai' => 'B'
      //     ],
      //     [
      //         'kode' => '14141066',
      //         'nilai' => 'B'
      //     ],
      //     [
      //         'kode' => '14141067',
      //         'nilai' => 'B'
      //     ],
      // ]);
      // self::krsStoreByNim('202285010068', '20221', 1, false);
      // self::krsStoreByNim('202285010068', '20222', 2, false);
      // self::krsStoreByNim('202285010068', '20231', 3, false);
      // return 'wes';
      // self::krsStoreByNim('201885140020', '20211', 7, true);
      // dd('wes');
      // return self::deleteKrs();
      // return self::krsMpi();
      // self::krsStoreByNim('202285020177', '20222', 2, false);
      // self::krsStoreByNim('202285020177', '20231', 3, false);
      // dd('wes');
      // $mahasiswa = Mahasiswa::join('trans_perwalian_detail', 'trans_perwalian_detail.nim', '=', 'mst_mhs.nim')
      //     ->join('trans_perwalian', 'trans_perwalian.id', '=', 'trans_perwalian_detail.perwalian_id')
      //     ->join('ref as kelompok', 'kelompok.id', '=', 'trans_perwalian.kelompok_id')
      //     ->where('kelompok.kode', 'MPI24LA')
      //     ->select('mst_mhs.nim')
      //     ->get()
      //     ->pluck('nim');
      // $krsDetail = KrsDetail::where('jadwal_kuliah_id', 5996)->select('nim')->get()->pluck('nim');

      // return self::krsMpiWithoutDelete();
      return redirect()->route('root');
      // $krs = [];
      // $krs[] = self::krsStoreByNim('201885290010', '20202', 6, true);
      // $krs[] = self::krsStoreByNim('201885290010', '20211', 7, true);
      // return $krs;
      // return self::deleteKrsWithJadwalKuliah();
      // dd(self::krsAuto());
      // dd("siakad");
      // $array = [
      //     "14131018",
      //     "14131019",
      //     "B8512204",
      //     "DL852204",
      //     "DL852211",
      //     "B8512214",
      //     "14131031",
      //     "14131032",
      //     "14131034",
      //     "14131037",
      //     "14131038",
      //     "14131039",
      //     "14131040",
      //     "B8512215",
      //     "B8512217",
      //     "FD852207",
      //     "14131051",
      //     "14131052",
      //     "14131054",
      //     "14131056",
      //     "14131057",
      //     "14131059",
      //     "B8512226",
      //     "B8512229",
      //     "B8512230",
      //     "DL852213"
      // ];
      // $mk = MataKuliah::whereIn('kode', $array)->with('prodi')->get()->toArray();
      // dd($mk);
      // dd("edit");
      // dd('testing dong');
      // \DB::beginTransaction();
      // $thAkademikId = 15;
      // $kelompokKode = 'PBA2021P';
      // $jadwalId = 1643;
      // $kelompok = Ref::where('kode', $kelompokKode)->first();
      // $ruangKelas = 'RUANG PAI 4B PUTRA';
      // $ruangKelas = Ref::where('nama', $ruangKelas)->first();
      // $jadwal = JadwalKuliah::find($jadwalId);
      // $jadwal->kelompok_id = $kelompok->id;
      // $jadwal->ruang_kelas_id = $ruangKelas->id;
      // $jadwal->save();

      // // delete absensi detail
      // $absensi = Absensi::where('trans_jadwal_kuliah_id', $jadwal->id)->get();
      // foreach ($absensi as $a) {
      //     AbsensiDetail::where('trans_absensi_mhs', $a->id)->delete();
      //     // delete absensi
      //     $a->delete();
      // }
      // // delete nilai
      // $krsDetailDelete = KRSDetail::where('jadwal_kuliah_id', $jadwal->id)->get();
      // foreach ($krsDetailDelete as $kd) {
      //     // delete krs detail
      //     KRSDetailNilai::where('krs_detail_id', $kd->id)->delete();
      //     $kd->delete();
      // }

      // // input krs
      // $mk = $jadwal->kurikulum_matakuliah->matakuliah;
      // // $krsDetail = KRSDetail::where('jadwal_kuliah_id', $jadwal->id)->where('th_akademik_id', $thAkademikId)->delete();

      // $krs = KRS::join('trans_perwalian_detail', 'trans_perwalian_detail.nim', '=', 'trans_krs.nim')
      //     ->join('trans_perwalian', 'trans_perwalian.id', '=', 'trans_perwalian_detail.perwalian_id')
      //     ->join('ref as kelompok', 'kelompok.id', '=', 'trans_perwalian.kelompok_id')
      //     ->where('trans_krs.th_akademik_id', $thAkademikId)
      //     ->where('kelompok.kode', $kelompokKode)
      //     ->select('trans_krs.*')
      //     ->get();

      // foreach ($krs as $key => $value) {
      //     $krsDetail = new KRSDetail();
      //     $krsDetail->krs_id = $value->id;
      //     $krsDetail->th_akademik_id = $thAkademikId;
      //     $krsDetail->jadwal_kuliah_id = $jadwal->id;
      //     $krsDetail->nim = $value->nim;
      //     $krsDetail->nama_mhs = $value->mahasiswa->nama;
      //     $krsDetail->dosen_id = $jadwal->dosen_id;
      //     $krsDetail->kode_mk = $mk->kode;
      //     $krsDetail->nama_mk = $mk->nama;
      //     $krsDetail->sks_mk = $mk->sks;
      //     $krsDetail->smt_mk = $mk->smt;
      //     $krsDetail->nilai_akhir = null;
      //     $krsDetail->nilai_bobot = null;
      //     $krsDetail->nilai_huruf = null;
      //     $krsDetail->user_id = $value->user_id;
      //     $krsDetail->transkrip = 'Y';
      //     $krsDetail->save();
      // }
      // \DB::commit();
      // dd('done');
      // $krs = KRS::where([
      //     ['th_akademik_id', 20],
      //     ['acc_pa', "Baru"]
      // ])->update([
      //     'acc_pa' => "Setujui"
      // ]);
      // \DB::commit();
      // dd("wes");
      // $perwalian = Perwalian::all();
      // $pring = [];
      // foreach ($perwalian as $key => $value) {
      //     $pring[] = @$value->prodi->alias.';'.@$value->dosen->nama;
      // }
      // return response()->json($pring);
      // $thAkademik = ThAkademik::aktif()->first();
      // $this->tt($thAkademik);
      // $mhs = Mahasiswa::find(5744);
      // $inputNilaiKompre = ServiceKompre::inputNilai($mhs, 20);
      // $this->tt($inputNilaiKompre);

      // \DB::beginTransaction();
      // // $where = [
      // //     ['siswa.tahun_pelajaran', '2023/2024'],
      // //     ['prodi_1.strata', 'S1']
      // // ];
      // // $mahasiswa = Mahasiswa::where('th_akademik_id', 19)->get();
      // // $siswa = ServiceSiswa::all(null, null, null, null, null, $where)->data;
      // // $dataSiswa = [];
      // // foreach ($siswa as $key => $value) {
      // //     $dataSiswa[$value->nik] = $value;
      // // }

      // // foreach ($mahasiswa as $key => $value) {
      // //     $getSiswa = $dataSiswa[$value->nik] ?? null;
      // //     if ($getSiswa) {
      // //         Mahasiswa::where('id', $value->id)
      // //             ->update([
      // //                 'nama_ayah' => @$getSiswa->get_orang_tua->nama_ayah,
      // //                 'nama_ibu' => @$getSiswa->get_orang_tua->nama_ibu,
      // //             ]);
      // //     }
      // // }
      // // \DB::commit();
      // // dd("wes");
      // // $listMhs = KRSDetail::orderBy('nim', 'asc')
      // //     ->where('jadwal_kuliah_id', 2646)
      // //     ->get();
      // // dd($listMhs[0]->mahasiswa->kelompok->perwalian->kelompok->nama);
      // // \DB::beginTransaction();

      // $mpi = PerwalianDetail::where('perwalian_id', 85)->get();
      // foreach ($mpi as $key => $value) {
      //     $krs = KRS::where([
      //         ['nim', $value->nim],
      //         ['th_akademik_id', 19]
      //     ])->first();
      //     if ($krs) {
      //         // A1
      //         $jadwalA1 = JadwalKuliah::find(4527);
      //         $check = KRSDetail::where([
      //             ['krs_id', $krs->id],
      //             ['jadwal_kuliah_id', $jadwalA1->id]
      //         ])->first();
      //         if (!$check) {
      //             $mkA = $jadwalA1->kurikulum_matakuliah->matakuliah;
      //             $krsDetailA1 = new KRSDetail;
      //             $krsDetailA1->krs_id = $krs->id;
      //             $krsDetailA1->th_akademik_id = 19;
      //             $krsDetailA1->jadwal_kuliah_id = $jadwalA1->id;
      //             $krsDetailA1->nim = strtoupper($value->nim);
      //             $krsDetailA1->nama_mhs = @$value->mahasiswa->nama;
      //             $krsDetailA1->dosen_id = @$jadwalA1->dosen_id;
      //             $krsDetailA1->kode_mk = @$mkA->kode;
      //             $krsDetailA1->nama_mk = @$mkA->nama;
      //             $krsDetailA1->sks_mk = @$mkA->sks;
      //             $krsDetailA1->smt_mk = @$mkA->smt;
      //             $krsDetailA1->transkrip = 'Y';
      //             $krsDetailA1->user_id = 8;
      //             $krsDetailA1->save();
      //         }
      //     }
      // }

      // $mpi = PerwalianDetail::where('perwalian_id', 84)->get();

      // foreach ($mpi as $key => $value) {
      //     $krs = KRS::where([
      //         ['nim', $value->nim],
      //         ['th_akademik_id', 19]
      //     ])->first();
      //     if ($krs) {
      //         // A1
      //         $jadwalA1 = JadwalKuliah::find(4526);
      //         $check = KRSDetail::where([
      //             ['krs_id', $krs->id],
      //             ['jadwal_kuliah_id', $jadwalA1->id]
      //         ])->first();
      //         if (!$check) {
      //             $mkA = $jadwalA1->kurikulum_matakuliah->matakuliah;
      //             $krsDetailA1 = new KRSDetail;
      //             $krsDetailA1->krs_id = $krs->id;
      //             $krsDetailA1->th_akademik_id = 19;
      //             $krsDetailA1->jadwal_kuliah_id = $jadwalA1->id;
      //             $krsDetailA1->nim = strtoupper($value->nim);
      //             $krsDetailA1->nama_mhs = @$value->mahasiswa->nama;
      //             $krsDetailA1->dosen_id = @$jadwalA1->dosen_id;
      //             $krsDetailA1->kode_mk = @$mkA->kode;
      //             $krsDetailA1->nama_mk = @$mkA->nama;
      //             $krsDetailA1->sks_mk = @$mkA->sks;
      //             $krsDetailA1->smt_mk = @$mkA->smt;
      //             $krsDetailA1->transkrip = 'Y';
      //             $krsDetailA1->user_id = 8;
      //             $krsDetailA1->save();
      //         }
      //     }
      // }

      // // $hkiB = PerwalianDetail::where('perwalian_id', 91)->get();
      // // foreach ($hkiB as $key => $value) {
      // //     $krs = KRS::where([
      // //         ['nim', $value->nim],
      // //         ['th_akademik_id', 19]
      // //     ])->first();
      // //     if ($krs) {
      // //         // A1
      // //         $jadwalA1 = JadwalKuliah::find(4551);
      // //         $mkA = $jadwalA1->kurikulum_matakuliah->matakuliah;
      // //         $krsDetailA1 = new KRSDetail;
      // //         $krsDetailA1->krs_id = $krs->id;
      // //         $krsDetailA1->th_akademik_id = 19;
      // //         $krsDetailA1->jadwal_kuliah_id = $jadwalA1->id;
      // //         $krsDetailA1->nim = strtoupper($value->nim);
      // //         $krsDetailA1->nama_mhs = @$value->mahasiswa->nama;
      // //         $krsDetailA1->dosen_id = @$jadwalA1->dosen_id;
      // //         $krsDetailA1->kode_mk = @$mkA->kode;
      // //         $krsDetailA1->nama_mk = @$mkA->nama;
      // //         $krsDetailA1->sks_mk = @$mkA->sks;
      // //         $krsDetailA1->smt_mk = @$mkA->smt;
      // //         $krsDetailA1->transkrip = 'Y';
      // //         $krsDetailA1->user_id = 8;
      // //         $krsDetailA1->save();

      // //         // A2
      // //         $jadwalA2 = JadwalKuliah::find(4553);
      // //         $mkA = $jadwalA2->kurikulum_matakuliah->matakuliah;
      // //         $krsDetailA2 = new KRSDetail;
      // //         $krsDetailA2->krs_id = $krs->id;
      // //         $krsDetailA2->th_akademik_id = 19;
      // //         $krsDetailA2->jadwal_kuliah_id = $jadwalA2->id;
      // //         $krsDetailA2->nim = strtoupper($value->nim);
      // //         $krsDetailA2->nama_mhs = @$value->mahasiswa->nama;
      // //         $krsDetailA2->dosen_id = @$jadwalA1->dosen_id;
      // //         $krsDetailA2->kode_mk = @$mkA->kode;
      // //         $krsDetailA2->nama_mk = @$mkA->nama;
      // //         $krsDetailA2->sks_mk = @$mkA->sks;
      // //         $krsDetailA2->smt_mk = @$mkA->smt;
      // //         $krsDetailA2->transkrip = 'Y';
      // //         $krsDetailA2->user_id = 8;
      // //         $krsDetailA2->save();

      // //         // A3
      // //         $jadwalA3 = JadwalKuliah::find(4555);
      // //         $mkA = $jadwalA3->kurikulum_matakuliah->matakuliah;
      // //         $krsDetailA3 = new KRSDetail;
      // //         $krsDetailA3->krs_id = $krs->id;
      // //         $krsDetailA3->th_akademik_id = 19;
      // //         $krsDetailA3->jadwal_kuliah_id = $jadwalA3->id;
      // //         $krsDetailA3->nim = strtoupper($value->nim);
      // //         $krsDetailA3->nama_mhs = @$value->mahasiswa->nama;
      // //         $krsDetailA3->dosen_id = @$jadwalA1->dosen_id;
      // //         $krsDetailA3->kode_mk = @$mkA->kode;
      // //         $krsDetailA3->nama_mk = @$mkA->nama;
      // //         $krsDetailA3->sks_mk = @$mkA->sks;
      // //         $krsDetailA3->smt_mk = @$mkA->smt;
      // //         $krsDetailA3->transkrip = 'Y';
      // //         $krsDetailA3->user_id = 8;
      // //         $krsDetailA3->save();
      // //     }
      // // }
      // \DB::commit();
      // $this->tt('uwes');
    } catch (\Throwable $th) {
      // \DB::rollBack();
      \DB::rollback();
      $this->tt($th->getMessage());
    }
  }

  public static function fixMpi()
  {
    $dosenKode      = '80243';
    $jadwalKuliahId = [4796, 4798];
    $thAkademikId   = 20; // 2023 genap;

    $dosen        = Dosen::where('kode', $dosenKode)->first();
    $jadwalKuliah = JadwalKuliah::whereIn('id', $jadwalKuliahId)->get();

    foreach ($jadwalKuliah as $jadwal) {
      KrsDetail::where('th_akademik_id', $thAkademikId)
        ->where('jadwal_kuliah_id', $jadwal->id)
        ->update(['dosen_id' => $dosen->id]);

      $jadwal->dosen_id = $dosen->id;
      $jadwal->save();
    }

    return 'done';
  }

  public function krs()
  {
    $thAkademikId    = 21;
    $jumlahMahasiswa = Mahasiswa::where([
      ['th_akademik_id', $thAkademikId],
      ['jk_id', 9],
    ])->count();
    return view('zzz-krs', compact('jumlahMahasiswa'));
  }

  public function krsStore(Request $request)
  {
    try {
      \DB::beginTransaction();

      $dari   = $request->start;
      $sampai = $dari + $request->limit;

      $thAkademikId = 21;
      $smt          = 1;

      $dataMahasiswa = Mahasiswa::where([
        ['th_akademik_id', $thAkademikId],
        ['jk_id', 9],
      ])->get();

      $message = [];
      for ($i = $dari; $i < $sampai; $i++) {
        $mahasiswa = $dataMahasiswa[$i];

        $prodi_id       = $mahasiswa->prodi_id;
        $kelas_id       = $mahasiswa->kelas_id;
        $kelompok_id    = $mahasiswa->kelompok->perwalian->kelompok_id;
        $th_akademik_id = $mahasiswa->th_akademik_id;

        $row = JadwalKuliah::select('trans_jadwal_kuliah.*', 'mst_matakuliah.nama as nama_matkul')
          ->addSelect(\DB::raw("'semester_ini'"))
          ->join('trans_kurikulum_matakuliah', 'trans_kurikulum_matakuliah.id', '=', 'trans_jadwal_kuliah.kurikulum_matakuliah_id')
          ->join('trans_kurikulum', 'trans_kurikulum.id', '=', 'trans_kurikulum_matakuliah.kurikulum_id')
          ->join('trans_kurikulum_angkatan', 'trans_kurikulum_angkatan.kurikulum_id', '=', 'trans_kurikulum.id')
          ->join('mst_matakuliah', 'mst_matakuliah.id', '=', 'trans_kurikulum_matakuliah.matakuliah_id')
          ->where('trans_kurikulum_angkatan.th_akademik_id', $th_akademik_id)
          ->where('trans_jadwal_kuliah.th_akademik_id', $th_akademik_id)
          ->where('trans_jadwal_kuliah.prodi_id', $prodi_id)
          ->where('trans_jadwal_kuliah.kelas_id', $kelas_id)
          ->where('trans_jadwal_kuliah.kelompok_id', $kelompok_id)
          ->with(['kurikulum_matakuliah.matakuliah', 'dosen', 'ruang_kelas', 'hari', 'kelompok', 'jamkul'])
          ->get();

        $krs = KRS::where('th_akademik_id', $thAkademikId)
          ->where('nim', $mahasiswa->nim)->first();

        if (! $krs) {
          $krs = new KRS;
        }

        $krs->th_akademik_id = $thAkademikId;
        $krs->prodi_id       = $mahasiswa->prodi_id;
        $krs->kelas_id       = $mahasiswa->kelas_id;
        $krs->nim            = strtoupper($mahasiswa->nim);
        $krs->smt            = $smt;
        $krs->tanggal        = tgl_sql('23-08-2024');
        $krs->user_id        = 8;
        $krs->acc_pa         = 'Setujui';
        $krs->save();

        KRSDetail::where('th_akademik_id', $mahasiswa->th_akademik_id)
          ->where('nim', $mahasiswa->nim)->whereNull('nilai_huruf')
          ->delete();

        $message[$i]['mahasiswa'] = "$mahasiswa->nama - $mahasiswa->nim";

        foreach ($row as $key => $value) {
          $data = KRSDetail::where('th_akademik_id', $thAkademikId)
            ->where('nim', $mahasiswa->nim)->where('jadwal_kuliah_id', $value->id)
            ->first();

          if (! $data) {
            $data = new KRSDetail;
          }

          $data->krs_id           = $krs->id;
          $data->th_akademik_id   = $thAkademikId;
          $data->jadwal_kuliah_id = $value->id;
          $data->nim              = strtoupper($mahasiswa->nim);

          $data->nama_mhs = @$mahasiswa->nama;

          $data->dosen_id  = @$value->dosen->id;
          $data->kode_mk   = @$value->kurikulum_matakuliah->matakuliah->kode;
          $data->nama_mk   = @$value->kurikulum_matakuliah->matakuliah->nama;
          $data->sks_mk    = @$value->kurikulum_matakuliah->matakuliah->sks;
          $data->smt_mk    = @$value->kurikulum_matakuliah->matakuliah->smt;
          $data->transkrip = 'Y';
          $data->user_id   = 8;
          $data->save();

          $message[$i]['mk'][] = $data->nama_mk;
        }
      }

      \DB::commit();
      return [
        'status'  => true,
        'message' => $message,
      ];
    } catch (\Throwable $th) {
      \DB::rollback();
      return [
        'status'  => false,
        'message' => $th->getMessage(),
      ];
    }
  }

  public static function deleteKrs()
  {
    try {
      $id = [
        379617,
        379620,
        379623,
        418916,
        379630,
        379633,
        379636,
        379642,
        379644,
        379647,
      ];

      // to do delete krsDetailNilai

      // delete krsDetail
      KRSDetailNilai::whereIn('krs_detail_id', $id)->delete();
      KRSDetail::whereIn('id', $id)->delete();

      return [
        'status'  => true,
        'message' => 'success',
      ];
    } catch (\Throwable $th) {
      return [
        'status'  => false,
        'message' => $th->getMessage(),
      ];
    }
  }

  public static function storeKrsByJadwalKuliahComplete($kodeThAkademik, $jadwalId, $kelompokKode, $ruangKelasNama)
  {
    try {
      //code...
      \DB::beginTransaction();
      $thAkademik   = ThAkademik::where('kode', $kodeThAkademik)->first();
      $thAkademikId = $thAkademik->id;

      $kelompok               = Ref::where('kode', $kelompokKode)->first();
      $ruangKelas             = Ref::where('nama', $ruangKelasNama)->first();
      $jadwal                 = JadwalKuliah::find($jadwalId);
      $jadwal->kelompok_id    = $kelompok->id;
      $jadwal->ruang_kelas_id = $ruangKelas->id;
      $jadwal->save();

      /*// delete absensi detail*/
      /*$absensi = Absensi::where('trans_jadwal_kuliah_id', $jadwal->id)->get();*/
      /*foreach ($absensi as $a) {*/
      /*  AbsensiDetail::where('trans_absensi_mhs', $a->id)->delete();*/
      /*  // delete absensi*/
      /*  $a->delete();*/
      /*}*/
      /*// delete nilai*/
      /*$krsDetailDelete = KRSDetail::where('jadwal_kuliah_id', $jadwal->id)->get();*/
      /*foreach ($krsDetailDelete as $kd) {*/
      /*  // delete krs detail*/
      /*  KRSDetailNilai::where('krs_detail_id', $kd->id)->delete();*/
      /*  $kd->delete();*/
      /*}*/

      // input krs
      $mk  = $jadwal->kurikulum_matakuliah->matakuliah;
      $krs = KRS::join('trans_perwalian_detail', 'trans_perwalian_detail.nim', '=', 'trans_krs.nim')
        ->join('trans_perwalian', 'trans_perwalian.id', '=', 'trans_perwalian_detail.perwalian_id')
        ->join('ref as kelompok', 'kelompok.id', '=', 'trans_perwalian.kelompok_id')
        ->where('trans_krs.th_akademik_id', $thAkademikId)
        ->where('kelompok.kode', $kelompokKode)
        ->select('trans_krs.*')
        ->get();

      foreach ($krs as $key => $value) {
        $krsDetail                   = new KRSDetail();
        $krsDetail->krs_id           = $value->id;
        $krsDetail->th_akademik_id   = $thAkademikId;
        $krsDetail->jadwal_kuliah_id = $jadwal->id;
        $krsDetail->nim              = $value->nim;
        $krsDetail->nama_mhs         = $value->mahasiswa->nama;
        $krsDetail->dosen_id         = $jadwal->dosen_id;
        $krsDetail->kode_mk          = $mk->kode;
        $krsDetail->nama_mk          = $mk->nama;
        $krsDetail->sks_mk           = $mk->sks;
        $krsDetail->smt_mk           = $mk->smt;
        $krsDetail->nilai_akhir      = null;
        $krsDetail->nilai_bobot      = null;
        $krsDetail->nilai_huruf      = null;
        $krsDetail->user_id          = $value->user_id;
        $krsDetail->transkrip        = 'Y';
        $krsDetail->save();
      }
      \DB::commit();
      dd('done');
    } catch (\Throwable $th) {
      //throw $th;
      \DB::rollback();
      dd($th->getMessage());
    }
  }

  public static function krsMpiWithoutDelete()
  {
    try {
      //code...
      \DB::beginTransaction();
      $thAkademikId   = 21;
      $jadwalId       = 5996;
      $kelompokKode   = 'MPI24LA';
      $ruangKelasNama = 'RUANG MPI 1A PUTRA';

      $kelompok               = Ref::where('kode', $kelompokKode)->first();
      $ruangKelas             = Ref::where('nama', $ruangKelasNama)->first();
      $jadwal                 = JadwalKuliah::find($jadwalId);
      $jadwal->kelompok_id    = $kelompok->id;
      $jadwal->ruang_kelas_id = $ruangKelas->id;
      $jadwal->save();

      // input krs
      $mk  = $jadwal->kurikulum_matakuliah->matakuliah;
      $krs = KRS::join('trans_perwalian_detail', 'trans_perwalian_detail.nim', '=', 'trans_krs.nim')
        ->join('trans_perwalian', 'trans_perwalian.id', '=', 'trans_perwalian_detail.perwalian_id')
        ->join('ref as kelompok', 'kelompok.id', '=', 'trans_perwalian.kelompok_id')
        ->where('trans_krs.th_akademik_id', $thAkademikId)
        ->where('kelompok.kode', $kelompokKode)
        ->select('trans_krs.*')
        ->get();
      foreach ($krs as $key => $value) {
        $krsDetail = KrsDetail::where('nim', $value->nim)
          ->where('krs_id', $value->id)
          ->where('jadwal_kuliah_id', $jadwal->id)
          ->first();
        if ($krsDetail) {
          continue;
        }
        $krsDetail                   = new KRSDetail();
        $krsDetail->krs_id           = $value->id;
        $krsDetail->th_akademik_id   = $thAkademikId;
        $krsDetail->jadwal_kuliah_id = $jadwal->id;
        $krsDetail->nim              = $value->nim;
        $krsDetail->nama_mhs         = $value->mahasiswa->nama;
        $krsDetail->dosen_id         = $jadwal->dosen_id;
        $krsDetail->kode_mk          = $mk->kode;
        $krsDetail->nama_mk          = $mk->nama;
        $krsDetail->sks_mk           = $mk->sks;
        $krsDetail->smt_mk           = $mk->smt;
        $krsDetail->nilai_akhir      = null;
        $krsDetail->nilai_bobot      = null;
        $krsDetail->nilai_huruf      = null;
        $krsDetail->user_id          = $value->user_id;
        $krsDetail->transkrip        = 'Y';
        $krsDetail->save();
      }
      \DB::commit();
      dd('done');
    } catch (\Throwable $th) {
      //throw $th;
      \DB::rollback();
      dd($th->getMessage());
    }
  }

  public static function deleteKrsWithJadwalKuliah($jadwalKuliahId)
  {
    try {
      AbsensiDetail::where('trans_jadwal_kuliah_id', $jadwalKuliahId)->delete();
      AbsensiDetail::where('trans_jadwal_kuliah_id', $jadwalKuliahId)->delete();
      KRSDetail::where('jadwal_kuliah_id', $jadwalKuliahId)->delete();
      return [
        'status'  => true,
        'message' => 'success',
      ];
    } catch (\Throwable $th) {
      return [
        'status'  => false,
        'message' => $th->getMessage(),
      ];
    }
  }

  public static function krsStoreByNim($nim, $kodeThAkademik, $smt, $setNilai = false)
  {
    try {
      \DB::beginTransaction();

      $bobotNilai = BobotNilai::where('nilai_huruf', 'B')->first();

      $mahasiswa  = Mahasiswa::where('nim', $nim)->first();
      $thAkademik = ThAkademik::where('kode', $kodeThAkademik)->first();

      $prodi_id    = $mahasiswa->prodi_id;
      $kelas_id    = $mahasiswa->kelas_id;
      $kelompok_id = $mahasiswa->kelompok->perwalian->kelompok_id;
      // $th_akademik_id = $mahasiswa->th_akademik_id;

      $row = JadwalKuliah::select('trans_jadwal_kuliah.*', 'mst_matakuliah.nama as nama_matkul')
        ->addSelect(\DB::raw("'semester_ini'"))
        ->join('trans_kurikulum_matakuliah', 'trans_kurikulum_matakuliah.id', '=', 'trans_jadwal_kuliah.kurikulum_matakuliah_id')
        ->join('trans_kurikulum', 'trans_kurikulum.id', '=', 'trans_kurikulum_matakuliah.kurikulum_id')
        // ->join('trans_kurikulum_angkatan', 'trans_kurikulum_angkatan.kurikulum_id', '=', 'trans_kurikulum.id')
        ->join('mst_matakuliah', 'mst_matakuliah.id', '=', 'trans_kurikulum_matakuliah.matakuliah_id')
        // ->where('trans_kurikulum_angkatan.th_akademik_id', $thAkademik->id)
        ->where('trans_jadwal_kuliah.th_akademik_id', $thAkademik->id)
        ->where('trans_jadwal_kuliah.prodi_id', $prodi_id)
        ->where('trans_jadwal_kuliah.kelas_id', $kelas_id)
        ->where('trans_jadwal_kuliah.kelompok_id', $kelompok_id)
        ->where('trans_jadwal_kuliah.smt', $smt)
        ->with(['kurikulum_matakuliah.matakuliah', 'dosen', 'ruang_kelas', 'hari', 'kelompok', 'jamkul'])
        ->get();

      // $a = [];
      // foreach ($row as $value) {
      //     $a[] = $value->kurikulum_matakuliah->matakuliah->nama . '- ' . $value->kurikulum_matakuliah->matakuliah->smt . ' - ' . $value->id . ' - ' . $value->kelompok->nama . ' - ' . $value->ruang_kelas->nama . ' - ' . $value->kurikulum_matakuliah->id;
      // }
      // dd($a);
      // dd($row, $thAkademik->id, $prodi_id, $kelas_id, $kelompok_id, $smt);
      $krs = KRS::where('th_akademik_id', $thAkademik->id)
        ->where('nim', $mahasiswa->nim)->first();

      if (! $krs) {
        $krs                 = new KRS;
        $krs->th_akademik_id = $thAkademik->id;
        $krs->prodi_id       = $mahasiswa->prodi_id;
        $krs->kelas_id       = $mahasiswa->kelas_id;
        $krs->nim            = strtoupper($mahasiswa->nim);
        $krs->smt            = $smt;
        $krs->tanggal        = tgl_sql('23-08-2024');
        $krs->user_id        = 8;
        $krs->acc_pa         = 'Setujui';
        $krs->save();
      }

      // KRSDetail::where('th_akademik_id', $thAkademik->id)
      //     ->where('nim', $mahasiswa->nim)
      //     ->delete();

      $message['mahasiswa'] = "$mahasiswa->nama - $mahasiswa->nim";

      $i        = 1;
      $countRow = ceil(count($row) / 2) + 2;

      foreach ($row as $key => $value) {
        $data = KRSDetail::where('th_akademik_id', $thAkademik->id)
          ->where('nim', $mahasiswa->nim)->where('jadwal_kuliah_id', $value->id)
          ->first();

        if ($data) {
          continue;
        }

        $data = new KRSDetail;

        $data->krs_id           = $krs->id;
        $data->th_akademik_id   = $thAkademik->id;
        $data->jadwal_kuliah_id = $value->id;
        $data->nim              = strtoupper($mahasiswa->nim);

        $data->nama_mhs = @$mahasiswa->nama;

        $data->dosen_id  = @$value->dosen->id;
        $data->kode_mk   = @$value->kurikulum_matakuliah->matakuliah->kode;
        $data->nama_mk   = @$value->kurikulum_matakuliah->matakuliah->nama;
        $data->sks_mk    = @$value->kurikulum_matakuliah->matakuliah->sks;
        $data->smt_mk    = @$value->kurikulum_matakuliah->matakuliah->smt;
        $data->transkrip = 'Y';
        $data->user_id   = 8;

        $namaMk = $data->nama_mk;
        if ($setNilai && $namaMk != "KKN") {
          if ($i > $countRow) {
            $bobotNilai = BobotNilai::where('nilai_huruf', 'B')->first();
          }

          $data->nilai_akhir = rand($bobotNilai->nilai_min, $bobotNilai->nilai_max);
          $data->nilai_bobot = $bobotNilai->nilai_bobot;
          $data->nilai_huruf = $bobotNilai->nilai_huruf;
          $namaMk .= " ($data->nilai_akhir)";
        }

        $data->save();
        $message['mk'][] = $namaMk;
        $i++;
      }

      \DB::commit();
      return [
        'status'  => true,
        'message' => $message,
      ];
    } catch (\Throwable $th) {
      \DB::rollback();
      return [
        'status'  => false,
        'message' => $th->getMessage(),
      ];
    }
  }

  public static function deleteAbsensiNilai($nim, $kodeThAkademik)
  {
    try {
      \DB::beginTransaction();
      $mahasiswa  = Mahasiswa::where('nim', $nim)->first();
      $thAkademik = ThAkademik::where('kode', $kodeThAkademik)->first();

      $prodi_id    = $mahasiswa->prodi_id;
      $kelas_id    = $mahasiswa->kelas_id;
      $kelompok_id = $mahasiswa->kelompok->perwalian->kelompok_id;

      //krs
      $krs = Krs::where('nim', $mahasiswa->nim)
        ->where('th_akademik_id', $thAkademik->id)
        ->first();

      //krs detail ->buat cek matakuliahnya apa aja
      $krsDetail = KRSDetail::where('krs_id', $krs->id)->get();

      $dataNilai = [];
      foreach ($krsDetail as $key => $value) {
        // absensi
        $absensiDetail = AbsensiDetail::where('trans_jadwal_kuliah_id', $value->jadwal_kuliah_id)
          ->where('nim', $mahasiswa->nim)
          ->delete();
        // nilai
        $nilaiDetail = KRSDetailNilai::where('krs_detail_id', $value->id)->delete();

        $value->delete();
      }

      \DB::commit();
      return [
        'status'  => true,
        'message' => 'success',
      ];
    } catch (\Throwable $th) {
      \DB::rollBack();
      return [
        'status'  => false,
        'message' => $th->getMessage(),
      ];
    }
  }

  public static function isiNilaiFixed($nim, $kodeThAkademik, $dataMk)
  {
    try {
      \DB::beginTransaction();

      $mahasiswa  = Mahasiswa::where('nim', $nim)->first();
      $thAkademik = ThAkademik::where('kode', $kodeThAkademik)->first();

      foreach ($dataMk as $key => $mk) {
        $krsDetail = KrsDetail::join('trans_jadwal_kuliah', 'trans_jadwal_kuliah.id', '=', 'trans_krs_detail.jadwal_kuliah_id')
          ->join('trans_kurikulum_matakuliah', 'trans_kurikulum_matakuliah.id', '=', 'trans_jadwal_kuliah.kurikulum_matakuliah_id')
          ->join('mst_matakuliah', 'mst_matakuliah.id', '=', 'trans_kurikulum_matakuliah.matakuliah_id')
          ->where('trans_krs_detail.nim', $mahasiswa->nim)
          ->where('trans_krs_detail.th_akademik_id', $thAkademik->id)
          ->where('mst_matakuliah.kode', $mk['kode'])
          ->select('trans_krs_detail.*')
          ->first();

        if ($krsDetail) {
          $bobotNilai             = BobotNilai::where('nilai_huruf', $mk['nilai'])->first();
          $krsDetail->nilai_akhir = rand($bobotNilai->nilai_min, $bobotNilai->nilai_max);
          $krsDetail->nilai_bobot = $bobotNilai->nilai_bobot;
          $krsDetail->nilai_huruf = $bobotNilai->nilai_huruf;
          $krsDetail->save();
        }
      }

      \DB::commit();
      return [
        'status'  => true,
        'message' => 'success',
        'data'    => $krsDetail,
      ];
    } catch (\Throwable $th) {
      \DB::rollBack();

      return [
        'status'  => false,
        'message' => $th->getMessage(),
      ];
    }
  }

  public static function isiNilaiPersemester($nim, $kodeThAkademik, $dataNilaiHuruf)
  {
    try {
      \DB::beginTransaction();

      $mahasiswa  = Mahasiswa::where('nim', $nim)->first();
      $thAkademik = ThAkademik::where('kode', $kodeThAkademik)->first();

      $krsDetail = KrsDetail::where('trans_krs_detail.nim', $mahasiswa->nim)
        ->where('trans_krs_detail.th_akademik_id', $thAkademik->id)
        ->get();

      foreach ($krsDetail as $key => $value) {
        $nilaiHuruf         = $dataNilaiHuruf[rand(0, count($dataNilaiHuruf) - 1)];
        $bobotNilai         = BobotNilai::where('nilai_huruf', $nilaiHuruf)->first();
        $value->nilai_akhir = rand($bobotNilai->nilai_min, $bobotNilai->nilai_max);
        $value->nilai_bobot = $bobotNilai->nilai_bobot;
        $value->nilai_huruf = $bobotNilai->nilai_huruf;
        $value->save();
      }

      \DB::commit();
      return [
        'status'  => true,
        'message' => 'success',
      ];
    } catch (\Throwable $th) {
      \DB::rollBack();

      return [
        'status'  => false,
        'message' => $th->getMessage(),
      ];
    }
  }

  public static function isiNilaiBatch()
  {
    try {
      \DB::beginTransaction();

      $nim   = '202085020184';
      $nilai = 'B+';
      $max   = 20;

      $found    = [];
      $notFound = [];

      $krsDetail = KRSDetail::where('nim', $nim)->get();
      for ($i = 0; $i < $max; $i++) {
        $index = rand(0, count($krsDetail) - 1);
        if (@$krsDetail[$index]) {

          $bobotNilai                     = BobotNilai::where('nilai_huruf', $nilai)->first();
          $krsDetail[$index]->nilai_akhir = rand($bobotNilai->nilai_min, $bobotNilai->nilai_max);
          $krsDetail[$index]->nilai_bobot = $bobotNilai->nilai_bobot;
          $krsDetail[$index]->nilai_huruf = $bobotNilai->nilai_huruf;
          $krsDetail[$index]->save();
          unset($krsDetail[$index]);
          $found[] = "$i $index";
        } else {
          $notFound[] = $i;
          $i--;
        }
      }

      \DB::commit();
      return [
        'status'  => true,
        'message' => 'success',
      ];
    } catch (\Throwable $th) {
      return [
        'status'  => false,
        'message' => $th->getMessage(),
      ];
    }
  }

  public static function isiNilaiKosong()
  {
    try {
      \DB::beginTransaction();

      $krsDetail = KrsDetail::where('nim', '202085020184')
        ->whereNull('nilai_huruf')
        // ->where('nilai_huruf', 'E')
        // ->whereNotIn('kode_mk', ['DL852461', '14123065', '14123066'])
        // ->where('th_akademik_id', '!=', 21)
        ->get();

      $dataNilai = ['B', 'B+'];
      foreach ($krsDetail as $key => $value) {
        $nilaiHuruf         = $dataNilai[rand(0, count($dataNilai) - 1)];
        $bobotNilai         = BobotNilai::where('nilai_huruf', $nilaiHuruf)->first();
        $value->nilai_akhir = rand($bobotNilai->nilai_min, $bobotNilai->nilai_max);
        $value->nilai_bobot = $bobotNilai->nilai_bobot;
        $value->nilai_huruf = $bobotNilai->nilai_huruf;
        $value->save();
      }

      \DB::commit();
      return [
        'status'  => true,
        'message' => 'success',
      ];
    } catch (\Throwable $th) {
      \DB::rollBack();

      return [
        'status'  => false,
        'message' => $th->getMessage(),
      ];
    }
  }

  public static function fixKrsKosong()
  {
    try {
      //code...
      \DB::beginTransaction();
      $thAkademikId = 15;
      $jadwalId     = 1641;
      // $jadwalId = 1675;
      $kelompokKode   = 'PAI2021P';
      $ruangKelasNama = 'RUANG PAI 1 PUTRI';

      $kelompok               = Ref::where('kode', $kelompokKode)->first();
      $ruangKelas             = Ref::where('nama', $ruangKelasNama)->first();
      $jadwal                 = JadwalKuliah::find($jadwalId);
      $jadwal->kelompok_id    = $kelompok->id;
      $jadwal->ruang_kelas_id = $ruangKelas->id;
      $jadwal->save();

      // input krs
      $mk  = $jadwal->kurikulum_matakuliah->matakuliah;
      $krs = KRS::join('trans_perwalian_detail', 'trans_perwalian_detail.nim', '=', 'trans_krs.nim')
        ->join('trans_perwalian', 'trans_perwalian.id', '=', 'trans_perwalian_detail.perwalian_id')
        ->join('ref as kelompok', 'kelompok.id', '=', 'trans_perwalian.kelompok_id')
        ->where('trans_krs.th_akademik_id', $thAkademikId)
        ->where('kelompok.kode', $kelompokKode)
        ->select('trans_krs.*')
        ->get();

      foreach ($krs as $key => $value) {
        $krsDetail = KRSDetail::where('krs_id', $value->id)
          ->where('th_akademik_id', $value->th_akademik_id)
          ->where('jadwal_kuliah_id', $jadwal->id)
          ->first();
        if ($krsDetail) {
          continue;
        }
        if (! $value->mahasiswa) {
          continue;
        }

        $krsDetail                   = new KRSDetail();
        $krsDetail->krs_id           = $value->id;
        $krsDetail->th_akademik_id   = $thAkademikId;
        $krsDetail->jadwal_kuliah_id = $jadwal->id;
        $krsDetail->nim              = $value->nim;
        $krsDetail->nama_mhs         = $value->mahasiswa->nama;
        $krsDetail->dosen_id         = $jadwal->dosen_id;
        $krsDetail->kode_mk          = $mk->kode;
        $krsDetail->nama_mk          = $mk->nama;
        $krsDetail->sks_mk           = $mk->sks;
        $krsDetail->smt_mk           = $mk->smt;
        $krsDetail->nilai_akhir      = null;
        $krsDetail->nilai_bobot      = null;
        $krsDetail->nilai_huruf      = null;
        $krsDetail->user_id          = $value->user_id;
        $krsDetail->transkrip        = 'Y';
        $krsDetail->save();
      }
      \DB::commit();
      return [
        'status'  => true,
        'message' => 'Success',
      ];
    } catch (\Throwable $th) {
      //throw $th;
      \DB::rollback();
      return [
        'status'  => false,
        'message' => $th->getMessage(),
      ];
    }
  }

  public static function sinkronPMB()
  {
    try {
      \DB::beginTransaction();
      $mahasiswa = Mahasiswa::where('th_akademik_id', 21)
        ->where('prodi_id', 9) //mpi
        ->get();

      $siswa = ServiceSiswa::all(null, null, null, null, null, [
        ['siswa.prodi_id', 3], //mpi
        ['siswa.tahun_pelajaran', '2024/2025'],
      ]);

      $siswaModif = [];
      foreach ($siswa->data as $key => $value) {
        $siswaModif[$value->nama] = $value;
      }

      // $namaMhsSiakad = '';
      // foreach ($mahasiswa as $key => $value) {
      //     $cocok = 'NGAWOR';
      //     if ($value->nama == @$siswaModif[$value->nama]->nama) {
      //         $cocok = 'SIP';
      //     }
      //     $namaMhsSiakad .= "$value->nama - " . @$siswaModif[$value->nama]->nama . '-' . $cocok . '<br>';
      // }

      // echo $namaMhsSiakad;
      // dd($namaMhsSiakad);
      foreach ($mahasiswa as $key => $value) {
        if ($value->nama != @$siswaModif[$value->nama]->nama) {
          continue;
        }

        $value->nik           = $siswaModif[$value->nama]->nik;
        $value->email         = $siswaModif[$value->nama]->email;
        $value->hp            = @$siswaModif[$value->nama]->nomor_hp;
        $value->tempat_lahir  = @$siswaModif[$value->nama]->tempat_lahir;
        $value->tanggal_lahir = @$siswaModif[$value->nama]->tanggal_lahir;
        $value->alamat        = @$siswaModif[$value->nama]->alamat;
        $value->nama_ayah     = @$siswaModif[$value->nama]->get_orang_tua->nama_ayah;
        $value->nama_ibu      = @$siswaModif[$value->nama]->get_orang_tua->nama_ibu;
        $value->save();
      }
      \DB::commit();
      return [
        'status'  => true,
        'message' => 'success sinkron pmb',
      ];
    } catch (\Throwable $th) {
      \DB::rollBack();
      return [
        'status'  => false,
        'message' => $th->getMessage(),
      ];
    }
  }

  public static function pembayaran()
  {
    try {
      \DB::beginTransaction();

      Mahasiswa::query()->update(['status_id' => 20]);

      KeuanganPembayaran::where('tanggal', '>=', '2025-01-04 00:00:00')
        ->update(['th_akademik_id' => 22]);
      KeuanganPembayaran::where('tanggal', '>=', '2025-01-04 00:00:00')
        ->increment('smt');

      $nim = KeuanganPembayaran::join('keuangan_tagihan', 'keuangan_tagihan.id', '=', 'keuangan_pembayaran.tagihan_id')
        ->where(function ($q) {
          $q->orWhere('keuangan_tagihan.nama', 'LIKE', '%daftar ulang%');
          $q->orWhere('keuangan_tagihan.nama', 'LIKE', '%regist%');
        })
        ->where('keuangan_pembayaran.th_akademik_id', 22)
        ->select('keuangan_pembayaran.nim')
        ->groupBy('keuangan_pembayaran.nim')
        ->get()
        ->pluck('nim');

      Mahasiswa::whereIn('nim', $nim)->update(['status_id' => 18]);

      \DB::commit();
      return [
        'status' => true,
        'data'   => $nim,
      ];
    } catch (\Throwable $th) {
      \DB::rollback();
      return [
        'status'  => false,
        'message' => $th->getMessage(),
      ];
    }
  }
}
