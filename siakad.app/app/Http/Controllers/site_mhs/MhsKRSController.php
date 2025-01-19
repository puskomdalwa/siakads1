<?php
namespace App\Http\Controllers\site_mhs;

use Alert;
use App\FormSchadule;
use App\Http\Controllers\Controller;
use App\Http\Services\ServiceKompre;
use App\Http\Services\ServiceKuesioner;
use App\JadwalKuliah;
use App\KRS;
use App\KRSDetail;
use App\Kurikulum;
use App\KurikulumMataKuliah;
use App\Mahasiswa;
use App\MataKuliah;
use App\Pejabat;
use App\ThAkademik;
use App\Prodi;
use App\PT;
use Auth;
use PDF;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class MhsKRSController extends Controller
{
    private $title = 'Kartu Rencana Studi (KRS)';
    private $redirect = 'mhs_krs';
    private $folder = 'site_mhs.mhs_krs';
    private $class = 'mhs_krs';

    private $rules = [
        'th_akademik_id' => 'required',
        'nim' => 'required',
        'keuangan' => 'required',
        'cek_list' => 'required',
    ];

    private $msg = [
        'th_akademik_id.required' => 'Tahun Akademik tidak boleh kosong',
        'nim.required' => 'NIM tidak boleh kosong',
        'keuangan.required' => 'Keuangan anda masih kosong',
        'cek_list.required' => 'Anda belum memilih Mata Kuliah',
    ];

    public function index()
    {
        // $cekIsiKuesioner = ServiceKuesioner::cekIsi();
        // if ($cekIsiKuesioner == false) {
        //     alert()->warning('Maaf, Anda belum isi Kuesioner, isi terlebih dahulu semua kuesioner yang ada', $this->title);
        //     return redirect('mhs_kuesioner_dosen');
        // }
        $nim = Auth::user()->username;

        $th_akademik = ThAkademik::Aktif()->first();
        $mhs_aktif = Mahasiswa::Aktif($nim)->first();
        $semester = $th_akademik->kode;

        $acc_krs = KRS::select('acc_pa')
            ->where('th_akademik_id', $th_akademik->id)
            ->where('nim', $nim)
            ->first();

        // dd($acc_krs);

        if ($acc_krs) {
            $acc_pa = $acc_krs->acc_pa;
        } else {
            $acc_pa = '';
        }

        $title = $this->title . ' NIM : ' . $nim . ' Tahun Akademik : ' . $th_akademik->kode;
        $redirect = $this->redirect;
        $folder = $this->folder;

        if ($mhs_aktif) {
            $smt = $th_akademik->semester;
            $tgl = date('Y-m-d H:i:s');

            if ($smt == 'Ganjil') {
                $buka_form = FormSchadule::where('kode', 'KRS-1')->first();
            } else {
                $buka_form = FormSchadule::where('kode', 'KRS-2')->first();
            }
            $buka_form->tgl_selesai = date('Y-m-d 23:59:59', strtotime($buka_form->tgl_selesai));
            if ($buka_form) {
                if ($acc_pa == 'Setujui') {
                    return view(
                        $folder . '.acc_krs',
                        compact('title', 'redirect', 'folder', 'th_akademik', 'nim', 'tgl', 'buka_form')
                    );
                }

                return view(
                    $folder . '.index',
                    compact('title', 'redirect', 'folder', 'th_akademik', 'nim', 'tgl', 'buka_form')
                );
            } else {
                return view(
                    $folder . '.close',
                    compact('title', 'redirect', 'folder', 'th_akademik', 'nim', 'tgl', 'buka_form')
                );
            }
        } else {
            return redirect('home');
        }
    }


    public function getDataMK(Request $request)
    {
        $prodi_id = $request->prodi_id;
        $kelas_id = $request->kelas_id;
        $kelompok_id = $request->kelompok_id;
        $th_akademik_id = $request->th_akademik_id;

        $nim = $request->nim;
        $jk = null;
        if ($nim) {
            $mhs = Mahasiswa::where('nim', $nim)->first();
            if ($mhs->jk->kode == "L") {
                $jk = "Putra";
            } else {
                $jk = "Putri";
            }
            $prodi_id = $mhs->prodi_id;
        }
        $smt = 0;
        if ($nim) {
            $angkatan = substr($nim, 0, 4);
            $thAkademik = ThAkademik::find($th_akademik_id);
            $smt = $this->hitungSemester($angkatan, $thAkademik->kode);
        }

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
            ->with(['kurikulum_matakuliah', 'dosen', 'ruang_kelas', 'hari', 'kelompok', 'jamkul'])
            ->get();

        $cekKompre = false;
        foreach ($row as $key => $value) {
            $mk = @$value->kurikulum_matakuliah->matakuliah;
            if (stripos(@$mk->nama, "kompre") !== false && @$mk->sks == 0) {
                $cekKompre = true;
            }
        }

        $krs = KRS::where([
            ['nim', $nim],
            ['th_akademik_id', $th_akademik_id],
        ])->first();

        $cekKrsWajib = false;
        if ($krs || (count($row) <= 0 && $krs == false) && $nim) {
            $matkulSemesterIni = $row->unique('nama_matkul')->pluck('nama_matkul');
            foreach ($matkulSemesterIni as $key => $value) {
                if (strtoupper($value) == "SKRIPSI") {
                    $matkulSemesterIni->forget($key);
                }
            }
            $cekKrs = $krs ? KRSDetail::join('trans_jadwal_kuliah as tjk', 'tjk.id', '=', 'trans_krs_detail.jadwal_kuliah_id')
                ->join('trans_kurikulum_matakuliah as tkk', 'tkk.id', '=', 'tjk.kurikulum_matakuliah_id')
                ->join('mst_matakuliah as mm', 'mm.id', '=', 'tkk.matakuliah_id')
                ->where('trans_krs_detail.krs_id', $krs->id)
                ->where('mm.nama', '!=', 'SKRIPSI')
                ->count() : 0;
            if ($cekKrs >= count($matkulSemesterIni)) { // jika sudah krs, bisa isi ulang matkul krs
                $cekKrsWajib = true;
                $mkNgulang = KRSDetail::where('nim', $nim)
                    ->where('transkrip', 'Y')
                    ->where(function ($q) {
                        $q->whereIn('nilai_huruf', ['D', 'D+', 'D-', 'E'])
                            ->orWhereNull('nilai_huruf');

                    })
                    ->where('th_akademik_id', '!=', $th_akademik_id)
                    ->orderBy('smt_mk')
                    ->orderBy('nama_mk')
                    ->get();
                    
                foreach ($mkNgulang as $d) {
                    $namaMk = str_replace('*', '', $d->nama_mk);
                    if (substr($namaMk, -1) == " ") {
                        $namaMk = substr_replace($namaMk, "", -1);
                    }
                    $row2 = JadwalKuliah::select('trans_jadwal_kuliah.*', 'mst_matakuliah.nama as nama_matkul')
                        ->addSelect(\DB::raw("'ngulang'"))
                        ->join('trans_kurikulum_matakuliah', 'trans_kurikulum_matakuliah.id', '=', 'trans_jadwal_kuliah.kurikulum_matakuliah_id')
                        ->join('trans_kurikulum', 'trans_kurikulum.id', '=', 'trans_kurikulum_matakuliah.kurikulum_id')
                        ->join('trans_kurikulum_angkatan', 'trans_kurikulum_angkatan.kurikulum_id', '=', 'trans_kurikulum.id')
                        ->join('mst_matakuliah', 'mst_matakuliah.id', '=', 'trans_kurikulum_matakuliah.matakuliah_id')
                        ->join('ref as ref_kelompok', 'ref_kelompok.id', '=', 'trans_jadwal_kuliah.kelompok_id')
                        ->where('trans_kurikulum_angkatan.th_akademik_id', $th_akademik_id)
                        ->where('trans_jadwal_kuliah.th_akademik_id', $th_akademik_id)
                        ->where('trans_jadwal_kuliah.prodi_id', $prodi_id)
                        ->where('trans_jadwal_kuliah.kelas_id', $kelas_id)
                        ->where('mst_matakuliah.nama', 'LIKE', "%$namaMk%")
                        ->where('ref_kelompok.nama', 'LIKE', "%$jk%")
                        ->with(['kurikulum_matakuliah', 'dosen', 'ruang_kelas', 'hari', 'kelompok', 'jamkul'])
                        ->get();
                    $row = $row->merge($row2);
                }

                // mk dari sisa transkrip (dibandingkan dengan kurikulum untuk mk cuti)
                $sisa = KRSDetail::where('nim', $nim)
                    ->where('transkrip', 'Y')
                    ->where(function ($q) {
                        $q->whereNotIn('nilai_huruf', ['D', 'D+', 'E']);
                        $q->whereNotNull('nilai_huruf');
                    })
                    ->where('th_akademik_id', '!=', $th_akademik_id)
                    ->orderBy('smt_mk')
                    ->orderBy('nama_mk')
                    ->get();

                $mkSisa = [];
                foreach ($sisa as $key => $value) {
                    $namaMk = str_replace('*', '', $value->nama_mk);
                    if (substr($namaMk, -1) == " ") {
                        $namaMk = substr_replace($namaMk, "", -1);
                    }
                    $mkSisa[] = $namaMk;
                }

                $kurikulum = Kurikulum::where([
                    ['prodi_id', $prodi_id],
                    ['th_akademik_id', $th_akademik_id],
                ])->first();

                $mkKurikulum = [];
                $kurMatkul = KurikulumMataKuliah::where('kurikulum_id', $kurikulum->id)
                    ->with('matakuliah')
                    ->get();
                foreach ($kurMatkul as $key => $value) {
                    $namaMk = str_replace('*', '', $value->matakuliah->nama);
                    if (substr($namaMk, -1) == " ") {
                        $namaMk = substr_replace($namaMk, "", -1);
                    }
                    $mkKurikulum[] = $namaMk;
                }

                $mkSisa = array_diff($mkKurikulum, $mkSisa);
                foreach ($mkSisa as $key => $value) {
                    $row3 = JadwalKuliah::select('trans_jadwal_kuliah.*', 'mst_matakuliah.nama as nama_matkul')
                        ->addSelect(\DB::raw("'ngulang'"))
                        ->join('trans_kurikulum_matakuliah', 'trans_kurikulum_matakuliah.id', '=', 'trans_jadwal_kuliah.kurikulum_matakuliah_id')
                        ->join('trans_kurikulum', 'trans_kurikulum.id', '=', 'trans_kurikulum_matakuliah.kurikulum_id')
                        ->join('trans_kurikulum_angkatan', 'trans_kurikulum_angkatan.kurikulum_id', '=', 'trans_kurikulum.id')
                        ->join('mst_matakuliah', 'mst_matakuliah.id', '=', 'trans_kurikulum_matakuliah.matakuliah_id')
                        ->join('ref as ref_kelompok', 'ref_kelompok.id', '=', 'trans_jadwal_kuliah.kelompok_id')
                        ->where('trans_kurikulum_angkatan.th_akademik_id', $th_akademik_id)
                        ->where('trans_jadwal_kuliah.th_akademik_id', $th_akademik_id)
                        ->where('trans_jadwal_kuliah.prodi_id', $prodi_id)
                        ->where('trans_jadwal_kuliah.kelas_id', $kelas_id)
                        ->where('mst_matakuliah.nama', 'LIKE', "%$value%")
                        ->where('mst_matakuliah.smt', '<', $smt)
                        ->where('ref_kelompok.nama', 'LIKE', "%$jk%")
                        ->with(['kurikulum_matakuliah', 'dosen', 'ruang_kelas', 'hari', 'kelompok', 'jamkul'])
                        ->get();
                    $row = $row->merge($row3);
                }
            }
        }

        if ($smt >= 2 && $cekKompre == false) {
            // MK kompre
            $mkKompre = MataKuliah::where([
                ['prodi_id', $prodi_id],
                ['sks', 0],
                ['nama', 'LIKE', '%kompre%']
            ])->first();
            $rowKompre = JadwalKuliah::select('trans_jadwal_kuliah.*', 'mst_matakuliah.nama as nama_matkul')
                ->addSelect(\DB::raw("'ngulang'"))
                ->join('trans_kurikulum_matakuliah', 'trans_kurikulum_matakuliah.id', '=', 'trans_jadwal_kuliah.kurikulum_matakuliah_id')
                ->join('trans_kurikulum', 'trans_kurikulum.id', '=', 'trans_kurikulum_matakuliah.kurikulum_id')
                ->join('trans_kurikulum_angkatan', 'trans_kurikulum_angkatan.kurikulum_id', '=', 'trans_kurikulum.id')
                ->join('mst_matakuliah', 'mst_matakuliah.id', '=', 'trans_kurikulum_matakuliah.matakuliah_id')
                ->join('ref as ref_kelompok', 'ref_kelompok.id', '=', 'trans_jadwal_kuliah.kelompok_id')
                ->where('trans_kurikulum_angkatan.th_akademik_id', $th_akademik_id)
                ->where('trans_jadwal_kuliah.th_akademik_id', $th_akademik_id)
                ->where('trans_jadwal_kuliah.prodi_id', $prodi_id)
                ->where('trans_jadwal_kuliah.kelas_id', $kelas_id)
                ->where('ref_kelompok.nama', 'LIKE', "%$jk%")
                ->where('mst_matakuliah.id', $mkKompre->id)
                ->with(['kurikulum_matakuliah', 'dosen', 'ruang_kelas', 'hari', 'kelompok', 'jamkul'])
                ->get();

            $row = $row->merge($rowKompre);
        }

        $dataTable = Datatables::of($row)
            ->addColumn('cek_list', function ($row) use ($th_akademik_id, $nim) {
                $krs = KRSDetail::where('th_akademik_id', $th_akademik_id)
                    ->where('jadwal_kuliah_id', $row->id)
                    ->where('nim', $nim)
                    ->first();

                $jenis = isset($row->ngulang) ? "ngulang" : "semester_ini";
                $valJenis = "$row->nama_matkul;$row->id;" . $row->kurikulum_matakuliah->matakuliah->sks . ";" . $row->hari->nama . ";" . $row->jamkul->nama . ";" . $jenis;

                if ($krs) {
                    $krs_nilai = KRSDetail::where('th_akademik_id', $th_akademik_id)
                        ->where('jadwal_kuliah_id', $row->id)
                        ->where('nim', $nim)
                        ->whereNotNull('nilai_huruf')
                        ->first();

                    if ($krs_nilai) {
                        return '<i class="fa fa-check text-success"></i>';
                    } else {
                        return '<div class="m-chck">
                    <input type="hidden" name="jenis_' . $jenis . '[]" id="jenis_' . $row->id . '" value="' . $valJenis . '" >
                    <label class="px-single">
                    <input type="checkbox" class="px" style="transform: scale(2.0);" name="cek_list[]" id="cek_list_' . $row->id . '" value="' .
                            $row->id . '" onClick="cekList(\'' . $row->id . '\',
                    \'' . $row->kurikulum_matakuliah->matakuliah->sks . '\',
                    \'' . $row->hari->nama . '\',
                    \'' . $row->jamkul->nama . '\')" checked >
                    <span class="lbl"></span></label>
                </div>';
                    }
                } else {
                    $string = @$row->kurikulum_matakuliah->matakuliah;
                    $quota = $row->ruang_kelas->param;
                    $isi = isi_kelas($row->id);
                    $sisa = $quota - $isi;

                    if ($sisa > 0 || (stripos(@$string->nama, "kompre") !== false && @$string->sks == 0)) {
                        return '<div class="m-chck">
                    <input type="hidden" name="jenis_' . $jenis . '[]" id="jenis_' . $row->id . '" value="' . $valJenis . '" >
                    <label class="px-single">
                    <input type="checkbox" class="px" style="transform: scale(2.0);" name="cek_list[]" id="cek_list_' .
                            $row->id . '" value="' . $row->id . '" onClick="cekList(\'' . $row->id . '\',
                    \'' . $row->kurikulum_matakuliah->matakuliah->sks . '\',
                    \'' . $row->hari->nama . '\',
                    \'' . $row->jamkul->nama . '\')">
                    <span class="lbl"></span></label>
                </div>';
                    } else {
                        return '<span class="label label-danger">Ruang<br>Full</span>';
                    }
                }
            })
            ->addColumn('kode_mk', function ($row) {
                return $row->kurikulum_matakuliah->matakuliah->kode;
            })
            ->addColumn('nama_mk', function ($row) {
                return $row->kurikulum_matakuliah->matakuliah->nama;
            })
            ->addColumn('sks_mk', function ($row) {
                return $row->kurikulum_matakuliah->matakuliah->sks;
            })
            ->addColumn('smt_mk', function ($row) {
                return $row->kurikulum_matakuliah->matakuliah->smt;
            })
            ->addColumn('kelompok', function ($row) {
                return $row->kelompok->kode;
            })
            ->addColumn('dosen', function ($row) {
                return $row->dosen->nama;
            })
            ->addColumn('hari', function ($row) {
                return $row->hari->nama;
            })
            ->addColumn('waktu', function ($row) {
                return $row->jamkul->nama;
            })
            ->addColumn('ruang', function ($row) {
                $mk = @$row->kurikulum_matakuliah->matakuliah;
                if (stripos(@$mk->nama, "kompre") !== false && @$mk->sks == 0) {
                    return '-';
                }
                return $row->ruang_kelas->kode . '<br>' . $row->ruang_kelas->param;
            })
            ->addColumn('quota', function ($row) {
                $mk = @$row->kurikulum_matakuliah->matakuliah;
                if (stripos(@$mk->nama, "kompre") !== false && @$mk->sks == 0) {
                    return '-';
                }
                $quota = $row->ruang_kelas->param;
                $isi = isi_kelas($row->id);
                $sisa = $quota - $isi;
                return $isi . '<br>' . $sisa;
            })
            ->rawColumns(['cek_list', 'ruang', 'quota'])
            ->make(true);
        $response = $dataTable->getData(true);
        $response['cek_krs'] = $cekKrsWajib;
        return $response;
    }

    public function hitungSemester($angkatan, $thAkademik)
    {
        if ($thAkademik % 2 != 0) {
            $a = (($thAkademik + 10) - 1) / 10;
            $b = $a - $angkatan;
            $c = ($b * 2) - 1;
        } else {
            $a = (($thAkademik + 10) - 2) / 10;
            $b = $a - $angkatan;
            $c = $b * 2;
        }
        return $c;
    }

    public function getMhs(Request $request)
    {
        $nim = $request->nim;
        $th_akademik = ThAkademik::Aktif()->first();
        //$Semester     = $th_akademik->kode;

        $mhs = Mahasiswa::where('nim', $nim)
            ->with('prodi', 'kelas', 'kelompok', 'jk', 'status')
            ->first();

        $smt = getSemesterMahasiswa($mhs->th_akademik->kode, $mhs->nim);
        $keuanganMhs = KeuanganMhs($mhs->nim, $th_akademik->id);
        if ($smt == 1) {
            $keuanganMhs = "Semester 1 otomatis aktif";
        }

        if ($mhs) {
            $return = [
                'jenis_kelamin' => $mhs->jk->nama,
                'status' => $mhs->status->nama,
                'prodi' => $mhs->prodi,
                'kelas' => $mhs->kelas,
                'kelompok' => @$mhs->kelompok->perwalian->kelompok,
                'keuangan' => $keuanganMhs,
                'sks_total' => sks_total($th_akademik->id, $mhs->nim),
                'smt' => $smt,
                'th_angkatan' => substr(@$mhs->th_akademik->kode, 0, 4),
            ];
        } else {
            $return = [
                'jenis_kelamin' => null,
                'status' => null,
                'prodi' => null,
                'kelas' => null,
                'kelompok' => null,
                'keuangan' => null,
                'sks_total' => 0,
                'smt' => null,
                'th_angkatan' => null,
            ];
        }
        return $return;
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules, $this->msg);
        $krs = KRS::where('th_akademik_id', $request->th_akademik_id)
            ->where('nim', $request->nim)->first();

        if (!$krs) {
            $krs = new KRS;
        }

        $krs->th_akademik_id = $request->th_akademik_id;

        $krs->prodi_id = $request->prodi_id;
        $krs->kelas_id = $request->kelas_id;
        $krs->nim = $request->nim;
        $krs->smt = $request->smt;
        $krs->tanggal = tgl_sql($request->tanggal);
        $krs->user_id = Auth::user()->id;
        $krs->save();

        KRSDetail::where('krs_id', $krs->id)
            ->where('th_akademik_id', $request->th_akademik_id)
            ->where('nim', $request->nim)
            ->whereNull('nilai_akhir')
            ->whereNull('nilai_huruf')
            ->whereNull('nilai_bobot')
            ->delete();

        foreach ($request->cek_list as $key => $value) {
            $data = KRSDetail::where('th_akademik_id', $request->th_akademik_id)
                ->where('nim', $request->nim)
                ->where('jadwal_kuliah_id', $value)
                ->first();

            if (!$data) {
                $data = new KRSDetail;
            }

            $data->krs_id = $krs->id;
            $data->th_akademik_id = $request->th_akademik_id;
            $data->jadwal_kuliah_id = $value;
            $data->nim = $request->nim;

            $mhs = Mahasiswa::where('nim', $request->nim)->first();
            $data->nama_mhs = @$mhs->nama;

            $jadwal = JadwalKuliah::where('id', $value)->with('kurikulum_matakuliah')->first();
            $data->dosen_id = @$jadwal->dosen->id;
            $data->kode_mk = @$jadwal->kurikulum_matakuliah->matakuliah->kode;
            $data->nama_mk = @$jadwal->kurikulum_matakuliah->matakuliah->nama;
            $data->sks_mk = @$jadwal->kurikulum_matakuliah->matakuliah->sks;
            $data->smt_mk = @$jadwal->kurikulum_matakuliah->matakuliah->smt;
            $data->transkrip = 'Y';
            $data->user_id = Auth::user()->id;
            $data->save();

            $jadwalKuliah = JadwalKuliah::find($value);
            $mk = $jadwalKuliah->kurikulum_matakuliah->matakuliah;
            if (stripos($mk->nama, 'KOMPRE') !== false && $mk->sks == 0) {
                ServiceKompre::inputNilai($mhs, $request->th_akademik_id);
            }
        }

        alert()->success('Input KRS Success', $this->title);
        return back()->withInput();
    }

    public function cetak(Request $request)
    {
        $prodi_id = $request->prodi_id;
        $th_akademik_id = $request->th_akademik_id;

        $prod_id = Auth::user()->$prodi_id;

        $prodi = Prodi::where('id', $prod_id)->first();

        $kelas_id = $request->kelas_id;
        $th_akademik = ThAkademik::where('id', $th_akademik_id)->first();

        $pt = PT::first();

        $data = KRS::where('th_akademik_id', $th_akademik_id)
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('prodi_id', $prodi_id);
            })
            ->when($kelas_id, function ($query) use ($kelas_id) {
                return $query->where('kelas_id', $kelas_id);
            })
            ->orderBy('nim', 'asc')
            ->with(['th_akademik', 'prodi', 'kelas', 'mahasiswa'])
            ->get();

        $class = 'text-left';

        $pdf = PDF::loadView($this->folder . '.cetak', compact('data', 'th_akademik', 'pt', 'class', 'prodi'));
        return $pdf->setPaper('a4', 'landscape')->stream('Laporan KRS ' . $th_akademik->kode . '.pdf');

    }

    public function cetakKRS($krs_id)
    {
        $krs = KRS::where('id', $krs_id)
            ->with('mahasiswa', 'th_akademik', 'prodi', 'kelas')
            ->first();

        $th_akademik = ThAkademik::where('id', $krs->th_akademik_id)->first();

        $pt = PT::first();
        $prodi = @Prodi::where('id', Auth::user()->prodi_id)->first();

        $data = KRSDetail::where('krs_id', $krs->id)
            ->with('jadwal_kuliah')
            ->get();

        $biro_id = env('BIRO_AKADEMIK_ID');
        $biro = Pejabat::where('jabatan_id', $biro_id)->first();

        $class = 'text-center';
        $pdf = PDF::loadView(
            $this->folder . '.cetakKHS',
            compact('data', 'pt', 'th_akademik', 'krs', 'biro', 'prodi', 'class')
        );

        return $pdf->setPaper('a4', 'portrait')
            ->stream('KHS ' . $th_akademik->kode . ' ' . $krs->nim . '.pdf');
    }
}