<?php
namespace App\Http\Controllers\site_admin;

use Alert;
use App\FormSchadule;
use App\Http\Controllers\Controller;
use App\Http\Services\ServiceKompre;
use App\JadwalKuliah;
use App\KompreNilai;
use App\KRS;
use App\KRSDetail;
use App\Kurikulum;
use App\KurikulumMataKuliah;
use App\Mahasiswa;
use App\MataKuliah;
use App\Prodi;
use App\PT;
use App\Ref;
use App\ThAkademik;

//---------------------------------------------

use Auth;
use Illuminate\Http\Request;
use PDF;
use Yajra\Datatables\Datatables;

class KRSController extends Controller
{
    private $title = 'Kartu Rencana Studi (KRS)';
    private $redirect = 'krs';
    private $folder = 'krs';
    private $class = 'krs';

    private $rules = [
        'th_akademik_id' => 'required',
        'tanggal' => 'required|date_format:"d-m-Y"',
        'prodi_id' => 'required',
        'kelas_id' => 'required',
        'nim' => 'required|string|max:20',
        'nama_prodi' => 'required',
        'nama_kelas' => 'required',
        'kelompok' => 'required',
        'keuangan' => 'required',
        'cek_list' => 'required',
    ];

    public function index()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $level = strtolower(Auth::user()->level->level);

        $th_akademik = ThAkademik::Aktif()->first();
        $th_akademik_id = ThAkademik::Aktif()->first()->id;
        $list_thakademik = ThAkademik::orderBy('kode', 'Desc')->get();

        $th_angkatan_id = ThAkademik::Aktif()->first()->id;

        $semester = ThAkademik::Aktif()->first()->semester;
        $ta_thakademik = ThAkademik::orderBy('kode', 'DESC')->get();

        $list_thakademik = ThAkademik::where('semester', 'Ganjil')->orderBy('kode', 'DESC')->get();

        $tgl = date('Y-m-d H:i:s');

        if ($semester == 'Ganjil') {
            $form = FormSchadule::where('kode', 'KRS-1')->first();
        } else {
            $form = FormSchadule::where('kode', 'KRS-2')->first();
        }

        $prodi_id = @strtolower(Auth::user()->prodi->id);

        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            $prodi_id = Prodi::orderBy('kode', 'ASC')->first()->id;
            // $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $kelas_id = Ref::where('kode', 'REG')->first()->id;
        $list_kelas = Ref::where('table', 'Kelas')->get();
        return view(
            $folder . '.index',
            compact(
                'title',
                'redirect',
                'folder',
                'th_akademik_id',
                'ta_thakademik',
                'list_thakademik',
                'prodi_id',
                'list_prodi',
                'kelas_id',
                'list_kelas',
                'tgl',
                'form',
                'level'
            )
        );
    }

    public function getData(Request $request)
    {
        $search = $request->search['value'];

        $kelas_id = $request->kelas_id;
        $prodi_id = $request->prodi_id;
        $th_angkatan_id = $request->th_angkatan_id;
        $th_akademik_id = $request->th_akademik_id;
        // dd($request->all());

        $row = KRS::join('mst_prodi as mp', 'mp.id', '=', 'trans_krs.prodi_id')
            ->join('ref as ref_kelas', 'ref_kelas.id', '=', 'trans_krs.kelas_id')
            ->join('mst_mhs', 'mst_mhs.nim', '=', 'trans_krs.nim')
            ->join('trans_perwalian_detail as tpd', 'tpd.nim', '=', 'mst_mhs.nim')
            ->join('trans_perwalian as tp', 'tp.id', '=', 'tpd.perwalian_id')
            ->join('ref as ref_kelompok', 'ref_kelompok.id', '=', 'tp.kelompok_id')
            ->select(
                'trans_krs.*',
                'mst_mhs.nama as nama_mhs',
                'mp.alias as prodi',
                'ref_kelas.nama as kelas',
                'ref_kelompok.kode as kelompok',
                'trans_krs.th_akademik_id as xxxxx'
            )
            ->when($th_angkatan_id, function ($query) use ($th_angkatan_id) {
                $query->where('mst_mhs.th_akademik_id', $th_angkatan_id);
            })
            ->when($th_akademik_id, function ($query) use ($th_akademik_id) {
                $query->where('trans_krs.th_akademik_id', $th_akademik_id);
            })
            ->when($prodi_id, function ($query) use ($prodi_id) {
                $query->where('trans_krs.prodi_id', $prodi_id);
            })
            ->when($kelas_id, function ($query) use ($kelas_id) {
                $query->where('trans_krs.kelas_id', $kelas_id);
            });
        return Datatables::of($row)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('mst_mhs.nama', 'LIKE', "%$search%");
                    $query->orWhere('mst_mhs.nim', 'LIKE', "%$search%");
                    $query->orWhere('ref_kelompok.kode', 'LIKE', "%$search%");
                });
            })
            ->editColumn('tanggal', function ($row) {
                return tgl_str($row->tanggal);
            })
            ->addColumn('sks', function ($row) {
                return sks_total($row->th_akademik_id, $row->nim);
            })
            ->addColumn('action', function ($row) {
                $acc_pa = acc_krs($row->th_akademik_id, $row->nim);

                $content = '<div class="btn-group">
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Klik <span class="caret"></span></button>
                    <ul class="dropdown-menu pull-right">';
                if ($acc_pa == 'Setujui') {
                    $content .= '<li><a href="' . url('/' . $this->class . '/' . $row->id . '/cetak') . '">Cetak</a></li> ';
                }
                $content .= '<li><a href="' . url('/' . $this->class . '/' . $row->id . '/edit') . '">Edit</a></li>
                            <li class="divider"></li>
                            <li><a onclick="deleteForm(' . $row->id . ')">Delete</a></li>
                        </ul>
                    </div>';

                return $content;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // Edit KRS
    //=======================================================================================

    public function edit($id)
    {
        $data = KRS::findOrFail($id);
        $nim = $data->nim;

        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $th_akademik = ThAkademik::where('id', $data->th_akademik_id)
            ->OrderBy('kode', 'DESC')
            ->first();

        $tgl = date('Y-m-d H:i:s');
        $semester = $data->smt;

        if ($semester == 'Ganjil') {
            $form = FormSchadule::where('kode', 'KRS-1')->first();
        } else {
            $form = FormSchadule::where('kode', 'KRS-2')->first();
        }

        $prodi_id = @strtolower(Auth::user()->prodi->id);

        if ($prodi_id) {
            $list_mhs = Mahasiswa::where('status_id', 18)->where('prodi_id', $prodi_id)
                ->orderBy('nim', 'DESC')->get();
        } else {
            $list_mhs = Mahasiswa::where('status_id', 18)->orderBy('nim', 'DESC')->get();
        }

        return view(
            $folder . '.edit',
            compact('data', 'title', 'redirect', 'folder', 'th_akademik', 'list_mhs', 'nim', 'tgl', 'form')
        );
    }

    // Modul Edit (formblade.php)
    //=================================================================================================

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

    // Modul Edit (formblade.php)
    //===============================================================================================================

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

    public function create()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $th_akademik = ThAkademik::Aktif()->first();
        $prodi_id = @strtolower(Auth::user()->prodi->id);

        $tgl = date('Y-m-d H:i:s');
        $semester = $th_akademik->semester;

        if ($semester == 'Ganjil') {
            $form = FormSchadule::where('kode', 'KRS-1')->first();
        } else {
            $form = FormSchadule::where('kode', 'KRS-2')->first();
        }

        if ($prodi_id) {
            $list_mhs = Mahasiswa::where('status_id', 18)
                ->where('prodi_id', $prodi_id)
                ->orderBy('nim', 'DESC')->get();
        } else {
            $list_mhs = Mahasiswa::where('status_id', 18)
                ->orderBy('nim', 'DESC')->get();
        }

        $nim = null;
        return view(
            $folder . '.create',
            compact('title', 'redirect', 'folder', 'th_akademik', 'list_mhs', 'nim', 'tgl', 'form')
        );
    }

    public function store(Request $request)
    { // dd($request->all());
        $this->validate($request, $this->rules);

        $krs = KRS::where('th_akademik_id', $request->th_akademik_id)
            ->where('nim', $request->nim)->first();

        if (!$krs) {
            $krs = new KRS;
        }

        $krs->th_akademik_id = $request->th_akademik_id;
        $krs->prodi_id = $request->prodi_id;
        $krs->kelas_id = $request->kelas_id;
        $krs->nim = strtoupper($request->nim);
        $krs->smt = $request->smt;
        $krs->tanggal = tgl_sql($request->tanggal);
        $krs->user_id = Auth::user()->id;
        $krs->save();

        KRSDetail::where('th_akademik_id', $request->th_akademik_id)
            ->where('nim', $request->nim)->whereNull('nilai_huruf')
            ->delete();

        foreach ($request->cek_list as $key => $value) {
            $data = KRSDetail::where('th_akademik_id', $request->th_akademik_id)
                ->where('nim', $request->nim)->where('jadwal_kuliah_id', $value)
                ->first();

            if (!$data) {
                $data = new KRSDetail;
            }

            $data->krs_id = $krs->id;
            $data->th_akademik_id = $request->th_akademik_id;
            $data->jadwal_kuliah_id = $value;
            $data->nim = strtoupper($request->nim);

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

    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules);

        $krs = KRS::where('th_akademik_id', $request->th_akademik_id)
            ->where('nim', $request->nim)
            ->first();

        if (!$krs) {
            $krs = new KRS;
        }

        $krs->th_akademik_id = $request->th_akademik_id;
        $krs->prodi_id = $request->prodi_id;
        $krs->kelas_id = $request->kelas_id;
        $krs->nim = strtoupper($request->nim);
        $krs->smt = $request->smt;
        $krs->tanggal = tgl_sql($request->tanggal);
        $krs->user_id = Auth::user()->id;
        $krs->save();

        // KRSDetail::where('th_akademik_id', $request->th_akademik_id)
        //     ->where('nim', $request->nim)->whereNull('nilai_huruf')
        //     ->delete();

        foreach ($request->cek_list as $key => $value) {
            $data = KRSDetail::where('th_akademik_id', $request->th_akademik_id)
                ->where('nim', strtoupper($request->nim))->where('jadwal_kuliah_id', $value)
                ->first();

            if (!$data) {
                $data = new KRSDetail;
            } else {
                continue;
            }

            $data->krs_id = $krs->id;
            $data->th_akademik_id = $request->th_akademik_id;
            $data->jadwal_kuliah_id = $value;
            $data->nim = strtoupper($request->nim);

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

        alert()->success('Update KRS Success', $this->title);
        return back()->withInput();
    }

    public function cetak($krs_id)
    {
        $krs = KRS::where('id', $krs_id)
            ->with('mahasiswa', 'th_akademik', 'prodi', 'kelas')->first();

        $th_akademik = ThAkademik::where('id', $krs->th_akademik_id)->first();

        $pt = PT::first();
        $prodi = @Prodi::where('id', $krs->prodi_id)->first();

        $data = KRSDetail::where('krs_id', $krs->id)
            ->with('jadwal_kuliah', 'jamkul')->get();

        $class = 'text-center';

        $pdf = PDF::loadView(
            $this->folder . '.cetak',
            compact('data', 'pt', 'th_akademik', 'krs', 'prodi', 'class')
        );

        return $pdf->setPaper('a4', 'portrait')
            ->stream('KRS ' . $th_akademik->kode . ' ' . $krs->nim . '.pdf');
    }

    public function destroy($id)
    {
        $data = KRS::findOrFail($id);
        $data->delete();

        KRSDetail::where('krs_id', $id)->delete();

        return response()->json([
            'title' => 'Delete Data Success',
            'text' => $this->title . ' ' . $data->nama,
            'type' => 'success',
        ]);
    }
}