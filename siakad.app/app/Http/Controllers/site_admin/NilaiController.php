<?php
namespace App\Http\Controllers\site_admin;

use Auth;
use Alert;
use App\Ref;
use App\Prodi;
use App\Absensi;
use App\KRSDetail;
use App\BobotNilai;
//use App\User;
use App\ThAkademik;
use App\FormSchadule;
use App\JadwalKuliah;
use App\AbsensiDetail;
use App\KomponenNilai;
use App\KRSDetailNilai;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Services\ServiceNilai;
use App\Http\Controllers\Controller;

class NilaiController extends Controller
{
    private $title = 'Nilai Mahasiswa';
    private $redirect = 'nilai';
    private $folder = 'nilai';
    private $class = 'nilai';

    private $rules = [
        'th_akademik_id' => 'required',
        'prodi_id' => 'required',
        'kelas_id' => 'required',
        'kelompok_id' => 'required',
        'dosen_id' => 'required',
        'ruang_kelas_id' => 'required',
        'hari_id' => 'required',
        'kurikulum_matakuliah_id' => 'required',
    ];

    public function index()
    {
        $th_akademik = ThAkademik::Aktif()->first();
        $th_akademik_id = $th_akademik->id;

        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $level = strtolower(Auth::user()->level->level);
        $list_thakademik = ThAkademik::orderBy('kode', 'DESC')->get();

        $tgl = date('Y-m-d H:i:s');
        $semester = $th_akademik->semester;

        $form = FormSchadule::where('kode', 'NILAI')->first();
        $prodi_id = @strtolower(Auth::user()->prodi->id);

        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            // $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $list_kelas = Ref::where('table', 'Kelas')->get();

        // $row = JadwalKuliah::join('trans_kurikulum_matakuliah as kurikulum_mat', 'kurikulum_mat.id', '=', 'trans_jadwal_kuliah.kurikulum_matakuliah_id')
        //         ->join('mst_matakuliah as matakuliah', 'matakuliah.id', '=', 'kurikulum_mat.matakuliah_id')
        //         ->join('trans_kurikulum as kurikulum', 'kurikulum.id', '=', 'kurikulum_mat.kurikulum_id')
        //         ->join('mst_th_akademik as th_akademik', 'th_akademik.id', '=', 'kurikulum.th_akademik_id')
        //         ->join('ref as ref_kelompok', 'ref_kelompok.id', '=', 'trans_jadwal_kuliah.kelompok_id')
        //         ->join('ref as ref_hari', 'ref_hari.id', '=', 'trans_jadwal_kuliah.hari_id')
        //         ->join('mst_dosen as dosen', 'dosen.id', '=', 'trans_jadwal_kuliah.dosen_id')
        //         ->join('ref as ref_ruang_kelas', 'ref_ruang_kelas.id', '=', 'trans_jadwal_kuliah.ruang_kelas_id')
        //         ->join('ref as ref_jam_kuliah', 'ref_jam_kuliah.id', '=', 'trans_jadwal_kuliah.jam_kuliah_id')
        //         ->select('trans_jadwal_kuliah.*', 'matakuliah.kode as kd_mk', 'matakuliah.nama as nama_mk',
        //                 'matakuliah.sks as sks_mk', 'matakuliah.smt as smt_mk', 'ref_kelompok.kode as kelompok',
        //                 'th_akademik.kode as kurikulum', 'dosen.nama as nama_dosen', 'dosen.kode as kode_dosen', 'ref_hari.nama as hari',
        //                 'ref_ruang_kelas.kode as ruang_kelas', 'ref_jam_kuliah.nama as jamkul');
        // dd($row);
        return view(
            $folder . '.index',
            compact(
                'title',
                'redirect',
                'folder',
                'list_thakademik',
                'list_prodi',
                'list_kelas',
                'prodi_id',
                'level',
                'form',
                'tgl'
            )
        );
    }

    public function getData(Request $request)
    {
        $prodi_id = $request->prodi_id;
        $kelas_id = $request->kelas_id;
        $th_akademik_id = $request->th_akademik_id;

        $search = $request->search['value'];

        // $row = JadwalKuliah::where('th_akademik_id', $thaka_id)
        //     ->when($prodi_id, function ($query) use ($prodi_id) {
        //         return $query->where('prodi_id', $prodi_id);
        //     })
        //     ->when($kelas_id, function ($query) use ($kelas_id) {
        //         return $query->where('kelas_id', $kelas_id);
        //     })
        //     ->with(['kurikulum_matakuliah', 'dosen', 'kelompok', 'hari', 'ruang_kelas', 'jamkul'])->get();
        $row = JadwalKuliah::join('trans_kurikulum_matakuliah as kurikulum_mat', 'kurikulum_mat.id', '=', 'trans_jadwal_kuliah.kurikulum_matakuliah_id')
            ->join('mst_matakuliah as matakuliah', 'matakuliah.id', '=', 'kurikulum_mat.matakuliah_id')
            ->join('trans_kurikulum as kurikulum', 'kurikulum.id', '=', 'kurikulum_mat.kurikulum_id')
            ->join('mst_th_akademik as th_akademik', 'th_akademik.id', '=', 'kurikulum.th_akademik_id')
            ->join('ref as ref_kelompok', 'ref_kelompok.id', '=', 'trans_jadwal_kuliah.kelompok_id')
            ->join('ref as ref_hari', 'ref_hari.id', '=', 'trans_jadwal_kuliah.hari_id')
            ->join('mst_dosen as dosen', 'dosen.id', '=', 'trans_jadwal_kuliah.dosen_id')
            ->join('ref as ref_ruang_kelas', 'ref_ruang_kelas.id', '=', 'trans_jadwal_kuliah.ruang_kelas_id')
            ->join('ref as ref_jam_kuliah', 'ref_jam_kuliah.id', '=', 'trans_jadwal_kuliah.jam_kuliah_id')
            ->select(
                'trans_jadwal_kuliah.*',
                'matakuliah.kode as kd_mk',
                'matakuliah.nama as nama_mk',
                'matakuliah.sks as sks_mk',
                'matakuliah.smt as smt_mk',
                'ref_kelompok.kode as kelompok',
                'th_akademik.kode as kurikulum',
                'dosen.nama as nama_dosen',
                'dosen.kode as kode_dosen',
                'ref_hari.nama as hari',
                'ref_ruang_kelas.kode as ruang_kelas',
                'ref_jam_kuliah.nama as waktu'
            );

        return Datatables::of($row)
            ->filter(function ($query) use ($search, $th_akademik_id, $prodi_id, $kelas_id) {
                $query->where('trans_jadwal_kuliah.th_akademik_id', $th_akademik_id)
                    ->when($prodi_id, function ($query) use ($prodi_id) {
                        return $query->where('trans_jadwal_kuliah.prodi_id', $prodi_id);
                    })
                    ->when($kelas_id, function ($query) use ($kelas_id) {
                        return $query->where('trans_jadwal_kuliah.kelas_id', $kelas_id);
                    });
                $query->where(function ($query) use ($search) {
                    $query->orWhere('matakuliah.kode', 'LIKE', "%$search%")
                        ->orWhere('matakuliah.nama', 'LIKE', "%$search%")
                        ->orWhere('matakuliah.sks', 'LIKE', "%$search%")
                        ->orWhere('matakuliah.smt', 'LIKE', "%$search%")
                        ->orWhere('ref_kelompok.kode', 'LIKE', "%$search%")
                        ->orWhere('th_akademik.kode', 'LIKE', "%$search%")
                        ->orWhere('th_akademik.kode', 'LIKE', "%$search%")
                        ->orWhere('dosen.kode', 'LIKE', "%$search%")
                        ->orWhere('dosen.nama', 'LIKE', "%$search%")
                        ->orWhere('ref_hari.nama', 'LIKE', "%$search%")
                        ->orWhere('ref_ruang_kelas.kode', 'LIKE', "%$search%");
                });
            })
            ->addColumn('dosen', function ($row) {
                return @$row->dosen->kode . ' - ' . $row->dosen->nama;
            })
            ->addColumn('jml_mhs', function ($row) {
                $krs_detail = KRSDetail::join('trans_krs', 'trans_krs.id', '=', 'trans_krs_detail.krs_id')
                    ->join('mst_mhs', 'mst_mhs.nim', '=', 'trans_krs.nim')
                    ->where('trans_krs_detail.jadwal_kuliah_id', $row->id)
                    ->where('trans_krs.acc_pa', 'Setujui');
                return $krs_detail->count();
            })
            ->addColumn('status', function ($row) {
                $krs_detail = KRSDetail::join('trans_krs', 'trans_krs.id', '=', 'trans_krs_detail.krs_id')
                    ->join('mst_mhs', 'mst_mhs.nim', '=', 'trans_krs.nim')
                    ->where('trans_krs.acc_pa', 'Setujui')
                    ->where('trans_krs_detail.jadwal_kuliah_id', $row->id)->count();

                $krs_detail_nilai = KRSDetail::join('trans_krs', 'trans_krs.id', '=', 'trans_krs_detail.krs_id')
                    ->join('mst_mhs', 'mst_mhs.nim', '=', 'trans_krs.nim')
                    ->where('trans_krs.acc_pa', 'Setujui')
                    ->where('trans_krs_detail.jadwal_kuliah_id', $row->id)
                    ->whereNotNull('trans_krs_detail.nilai_akhir')->count();

                if ($krs_detail > 0) {
                    return $krs_detail == $krs_detail_nilai ? '<i class="fa fa-check text-success"></i> ' : '
				<i class="fa fa-times text-danger"></i>';
                }
                return '<i class="fa fa-times text-danger"></i>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Klik <span class="caret"></span></button>
                <ul class="dropdown-menu pull-right">
                    <li><a href="' . url('/' . $this->class . '/' . $row->id . '/edit') . '">Edit</a></li>
                </ul>
            </div>';
            })
            ->addColumn('tutup', function ($row) {
                return '<i class="fa fa-times-circle text-danger"></i>';
            })
            ->rawColumns(['status', 'action', 'tutup'])
            ->make(true);
    }

    public function edit($id)
    {
        $level = strtolower(Auth::user()->level->level);
        $userx = @strtolower(Auth::user()->username);
        $data = JadwalKuliah::findOrFail($id);

        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $tgl = date('Y-m-d H:i:s');

        $th_akademik = ThAkademik::aktif()->first();
        $semester = $th_akademik->semester;

        if ($semester == 'Ganjil') {
            $form = FormSchadule::where('kode', 'NIL-1')
                ->first();
        } else {
            $form = FormSchadule::where('kode', 'NIL-2')
                ->first();
        }
        $komponen_nilai = KomponenNilai::get();
        // $list_mhs = KRSDetail::orderBy('nim', 'asc')
        //     ->where('jadwal_kuliah_id', $id)->skip(0)->take(10)->get();

        $namaMatkul = isset($data->kurikulum_matakuliah->matakuliah->nama) ? $data->kurikulum_matakuliah->matakuliah->nama : "";
        $cekSkripsi = strtolower($namaMatkul) == "skripsi" ? true : false;

        $nilaiAccess = ServiceNilai::access();
        return view(
            $folder . '.edit',
            compact('title', 'redirect', 'folder', 'userx', 'data', 'komponen_nilai', 'level', 'form', 'tgl', 'cekSkripsi', 'id', 'nilaiAccess')
        );
    }
    public function getDataNilai($id, Request $request)
    {
        $search = $request->search['value'];
        $data = JadwalKuliah::findOrFail($id);

        // \DB::statement("SET SQL_MODE=''");
        $row = KRSDetail::join('trans_krs', 'trans_krs.id', '=', 'trans_krs_detail.krs_id')
            ->join('mst_mhs', 'mst_mhs.nim', '=', 'trans_krs_detail.nim')
            ->join('ref as ref_jk', 'ref_jk.id', '=', 'mst_mhs.jk_id')
            ->where('trans_krs_detail.jadwal_kuliah_id', $id)
            ->where('trans_krs.acc_pa', 'Setujui')
            ->select('trans_krs_detail.*', 'mst_mhs.nama as mhs_nama', 'ref_jk.kode as mhs_jk', 'mst_mhs.id as mhs_id', 'mst_mhs.spm as mhs_spm', 'mst_mhs.izin as mhs_izin');

        return Datatables::of($row)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('trans_krs_detail.nim', 'LIKE', "%$search%")
                        ->orWhere('mst_mhs.nama', 'LIKE', "%$search%")
                        ->orWhere('ref_jk.nama', 'LIKE', "%$search%");
                });
            })
            ->setRowId(function ($row) {
                return "mhs_$row->id";
            })
            ->setRowClass(function ($row) {
                $cekSudahIsiNilai = $row->nilai_akhir;
                return $cekSudahIsiNilai !== null ? 'alert-success' : 'alert-danger';
            })
            ->addColumn('data_isi_nilai_url', function ($row) use ($id) {
                return url($this->folder . "/$id/getDataIsiNilai/$row->id");
            })
            ->addColumn('action', function ($row) use ($id) {
                return '<button id="action-' . $row->id . '" type="button" class="btn btn-success" style="border-radius:20px;display:inline;padding:2px 8px">
                    Detail
                    </button>';
            })
            ->editColumn('mhs_nama', function ($row) {
                $spm = $row->mhs_spm == "iya" ? " <span class='badge badge-warning'>SPM</span>" : "";
                $izin = $row->mhs_izin != null ? " <span class='badge badge-info'>" . strtoupper($row->mhs_izin) . "</span>" : "";
                return $row->mhs_nama . $spm . $izin;
            })
            // ->editColumn('nim', function ($query) {
            //     return "$query->nim, id:$query->id";
            // })
            ->rawColumns(['data_isi_nilai_url', 'action', 'mhs_nama'])
            ->make(true);
    }
    public function getDataIsiNilai($id, $krsDetailId, Request $request)
    {
        $search = $request->search['value'];
        $komponen_nilai = KomponenNilai::get();
        $data = JadwalKuliah::findOrFail($id);
        $namaMatkul = isset($data->kurikulum_matakuliah->matakuliah->nama) ? $data->kurikulum_matakuliah->matakuliah->nama : "";
        $cekSkripsi = strtolower($namaMatkul) == "skripsi" ? true : false;

        // \DB::statement("SET SQL_MODE=''");
        $row = KRSDetail::join('mst_mhs', 'mst_mhs.nim', '=', 'trans_krs_detail.nim')
            ->join('ref as ref_jk', 'ref_jk.id', '=', 'mst_mhs.jk_id')
            ->where('trans_krs_detail.jadwal_kuliah_id', $id)
            ->where('trans_krs_detail.id', $krsDetailId)
            ->select('trans_krs_detail.*', 'mst_mhs.nama as mhs_nama', 'ref_jk.kode as mhs_jk', 'mst_mhs.id as mhs_id', 'mst_mhs.spm as mhs_spm');

        $dataTable = Datatables::of($row);
        $dataTable->filter(function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('trans_krs_detail.nim', 'LIKE', "%$search%")
                    ->orWhere('mst_mhs.nama', 'LIKE', "%$search%")
                    ->orWhere('ref_jk.nama', 'LIKE', "%$search%");
            });
        });
        $dataTable->addColumn('hadir', function ($row) use ($data) {
            $hadir = jmlabsmhs($data->id, $row->nim) . '/' . jmlabsdos($data->id);
            $return = '<input type="text"
            id=rekap_absensi_' . $row->id . '
            value="' . $hadir . '"
            class="form-control text-center" readonly>';
            $return .= '<input type="hidden"
            name="id"
            id="' . $row->id . '"
            value="' . $row->id . '"
            class="form-control text-center">';
            return $return;
        });

        $rawCol = [];
        foreach ($komponen_nilai as $kn) {
            // yang bikin lama
            $dataTable->addColumn($kn->nama, function ($row) use ($kn, $cekSkripsi, $data) {
                if ($kn->id > 1) {
                    $nilai = getNilai($row->id, $kn->id);
                    $content = '<input type="number"
                    name="' . $kn->nama . '"
                    id="' . $kn->nama . '_' . $row->id . '"
                    value="' . $nilai . '"
                    class="form-control text-center"
                    onkeyup="hitungNilai(' . $row->id . ')"
                    onchange="hitungNilai(' . $row->id . ')">';
                } else {
                    if ($row->mhs_spm == "iya") {
                        $nilai = getNilai($row->id, $kn->id);
                    } else {
                        $nilai = $cekSkripsi ? 100 : getNilaiAbs($data->id, $row->nim);
                    }
                    $content = '<input type="number"
                    name="' . $kn->nama . '"
                    id="' . $kn->nama . '_' . $row->id . '"
                    value="' . $nilai . '"
                    class="form-control text-center"
                    onkeyup="hitungNilai(' . $row->id . ')"
                    onchange="hitungNilai(' . $row->id . ')" readonly>';
                }
                return $content;
            });
            $rawCol[] = $kn->nama;
        }
        $dataTable->editColumn('nilai_akhir', function ($row) {
            return '<input type="text" name="nilai_akhir" id="nilai_akhir_' . $row->id . '"
            value="' . $row->nilai_akhir . '"
            class="form-control text-center" readonly>';
        });
        $dataTable->editColumn('nilai_bobot', function ($row) {
            return '<input type="text" name="nilai_bobot" id="nilai_bobot_' . $row->id . '"
            value="' . $row->nilai_bobot . '"
            class="form-control text-center" readonly>';
        });
        $dataTable->editColumn('nilai_huruf', function ($row) {
            return '<input type="text" name="nilai_huruf" id="nilai_huruf_' . $row->id . '"
            value="' . $row->nilai_huruf . '"
            class="form-control text-center" readonly>';
        });
        $dataTable->editColumn('nim', function ($row) {
            return '<input type="hidden" name="input[' . $row->id . '][id]"
            id="id_' . $row->id . '" value="' . $row->id . '"> ' . $row->nim . '';
        });

        array_push($rawCol, "nilai_akhir", "nilai_bobot", "nilai_huruf", "nim", 'hadir');
        $dataTable->rawColumns($rawCol);
        return $dataTable->make(true);
    }
    public function saveNilai($id, Request $request)
    {
        try {
            DB::beginTransaction();
            if (!ServiceNilai::access()) {
                abort(500, 'Hanya dosen yang bisa isi nilai');
            }
            $krs_detail = KRSDetail::find($request->id);
            $komponen_nilai = KomponenNilai::get();
            $jumlahAbsensi = 0;
            foreach ($komponen_nilai as $kn) {
                if ($kn->id == 1) {
                    $jumlahAbsensi = $request->input('input-absensi');
                }

                $krs_detail_nilai = KRSDetailNilai::where('krs_detail_id', $request->id)
                    ->where('komponen_nilai_id', $kn->id)->first();

                if (!$krs_detail_nilai) {
                    $krs_detail_nilai = new KRSDetailNilai;
                }

                $krs_detail_nilai->jadwal_kuliah_id = $id;
                $krs_detail_nilai->krs_detail_id = $request->id;
                $krs_detail_nilai->komponen_nilai_id = $kn->id;
                $krs_detail_nilai->komponen_nilai = $kn->nama;
                $krs_detail_nilai->bobot_nilai = $kn->bobot;
                $krs_detail_nilai->nilai = $request->input($kn->nama);
                $krs_detail_nilai->user_id = Auth::user()->id;
                $krs_detail_nilai->save();
            }

            $krs_detail = KRSDetail::where('id', $request->id)->first();
            if ($krs_detail) {
                $krs_detail->nilai_akhir = $request->nilai_akhir;
                $krs_detail->nilai_bobot = $request->nilai_bobot;
                $krs_detail->nilai_huruf = $request->nilai_huruf;
                $krs_detail->user_id = Auth::user()->id;
                $krs_detail->save();
            }

            // SPM
            if (@$krs_detail->mahasiswa->spm == "iya") {
                // Delete Absensi Semua SPM
                $absensiDetail = AbsensiDetail::where('nim', $krs_detail->nim)
                    ->where('trans_jadwal_kuliah_id', $id)
                    ->delete();

                // Input Absensi
                $absensi = Absensi::where('trans_jadwal_kuliah_id', $id)->get();
                for ($i = 0; $i < $jumlahAbsensi; $i++) {
                    $absensiDetail = AbsensiDetail::where('nim', $krs_detail->nim)
                        ->where('trans_absensi_mhs', $absensi[$i]->id)
                        ->first();
                    if (!$absensiDetail) {
                        $absensiDetail = new AbsensiDetail();
                    }
                    $absensiDetail->trans_jadwal_kuliah_id = $id;
                    $absensiDetail->trans_absensi_mhs = $absensi[$i]->id;
                    $absensiDetail->nim = $krs_detail->nim;
                    $absensiDetail->status = 'Hadir';
                    $absensiDetail->save();
                }
            }
            DB::commit();
            return [
                'status' => true,
                'title' => 'Sukses',
                'text' => 'Berhasil simpan nilai ' . $krs_detail->nim,
                'type' => 'success',
                'id' => $request->id
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            $krs_detail = KRSDetail::find($request->id);
            return [
                'status' => false,
                'title' => 'Gagal',
                'text' => 'Gagal simpan nilai ' . $krs_detail->nim,
                'type' => 'danger',
                'err' => $th->getMessage()
            ];
        }
    }
    public function getDataNilai2($id, Request $request)
    {
        $search = $request->search['value'];
        $komponen_nilai = KomponenNilai::get();
        $data = JadwalKuliah::findOrFail($id);
        $namaMatkul = isset($data->kurikulum_matakuliah->matakuliah->nama) ? $data->kurikulum_matakuliah->matakuliah->nama : "";
        $cekSkripsi = strtolower($namaMatkul) == "skripsi" ? true : false;

        // \DB::statement("SET SQL_MODE=''");
        $row = KRSDetail::join('mst_mhs', 'mst_mhs.nim', '=', 'trans_krs_detail.nim')
            ->join('ref as ref_jk', 'ref_jk.id', '=', 'mst_mhs.jk_id')
            ->where('trans_krs_detail.jadwal_kuliah_id', $id)
            ->select('trans_krs_detail.*', 'mst_mhs.nama as mhs_nama', 'ref_jk.kode as mhs_jk', 'mst_mhs.id as mhs_id', );

        $dataTable = Datatables::of($row);
        $dataTable->filter(function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('trans_krs_detail.nim', 'LIKE', "%$search")
                    ->orWhere('mst_mhs.nama', 'LIKE', "%$search")
                    ->orWhere('ref_jk.nama', 'LIKE', "%$search");
            });
        });
        $dataTable->addColumn('hadir', function ($row) use ($data) {
            return jmlabsmhs($data->id, $row->nim) . '/' . jmlabsdos($data->id);
        });

        $rawCol = [];
        foreach ($komponen_nilai as $kn) {
            // yang bikin lama
            $dataTable->addColumn($kn->nama, function ($row) use ($kn, $cekSkripsi, $data) {
                if ($kn->id > 1) {
                    $nilai = getNilai($row->id, $kn->id);
                    $content = '<input type="text"
                    name="input[' . $row->id . '][' . $kn->nama . ']"
                    id="' . $kn->nama . '_' . $row->id . '"
                    value="' . $nilai . '"
                    class="form-control text-center"
                    onkeypress="return hanyaAngka(event)"
                    onkeyup="hitungNilai(' . $row->id . ')">';
                } else {
                    $nilai = $cekSkripsi ? 100 : getNilaiAbs($data->id, $row->nim);
                    $content = '<input type="text"
                    name="input[' . $row->id . '][' . $kn->nama . ']"
                    id="' . $kn->nama . '_' . $row->id . '"
                    value="' . $nilai . '"
                    class="form-control text-center"
                    onkeypress="return hanyaAngka(event)"
                    onkeyup="hitungNilai(' . $row->id . ')" readonly>';
                }
                return $content;
            });
            $rawCol[] = $kn->nama;
        }
        $dataTable->editColumn('nilai_akhir', function ($row) {
            return '<input type="text" name="input[' . $row->id . '][nilai_akhir]" id="nilai_akhir_' . $row->id . '"
            value="' . $row->nilai_akhir . '"
            class="form-control text-center" readonly>';
        });
        $dataTable->editColumn('nilai_bobot', function ($row) {
            return '<input type="text" name="input[' . $row->id . '][nilai_bobot]" id="nilai_bobot_' . $row->id . '"
            value="' . $row->nilai_bobot . '"
            class="form-control text-center" readonly>';
        });
        $dataTable->editColumn('nilai_huruf', function ($row) {
            return '<input type="text" name="input[' . $row->id . '][nilai_huruf]" id="nilai_huruf_' . $row->id . '"
            value="' . $row->nilai_huruf . '"
            class="form-control text-center" readonly>';
        });
        $dataTable->editColumn('nim', function ($row) {
            return '<input type="hidden" name="input[' . $row->id . '][id]"
            id="id_' . $row->id . '" value="' . $row->id . '"> ' . $row->nim . '';
        });

        array_push($rawCol, "nilai_akhir", "nilai_bobot", "nilai_huruf", "nim");
        $dataTable->rawColumns($rawCol);
        return $dataTable->make(true);
    }

    public function getBobotNilai(Request $request)
    {
        $nilai_akhir = $request->nilai_akhir;

        $data = BobotNilai::where('nilai_max', '>=', $nilai_akhir)
            ->orderBy('nilai_max', 'asc')
            ->limit(1)->first();

        return response()->json([
            'nilai_huruf' => $data->nilai_huruf,
            'nilai_bobot' => $data->nilai_bobot,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            foreach ($request->input as $key => $value) {
                $komponen_nilai = KomponenNilai::get();
                foreach ($komponen_nilai as $kn) {
                    $krs_detail_nilai = KRSDetailNilai::where('krs_detail_id', $value['id'])
                        ->where('komponen_nilai_id', $kn->id)->first();

                    if (!$krs_detail_nilai) {
                        $krs_detail_nilai = new KRSDetailNilai;
                    }

                    $krs_detail_nilai->jadwal_kuliah_id = $request->jadwal_kuliah_id;
                    $krs_detail_nilai->krs_detail_id = $value['id'];
                    $krs_detail_nilai->komponen_nilai_id = $kn->id;
                    $krs_detail_nilai->komponen_nilai = $kn->nama;
                    $krs_detail_nilai->bobot_nilai = $kn->bobot;
                    $krs_detail_nilai->nilai = $value[$kn->nama];
                    $krs_detail_nilai->user_id = Auth::user()->id;
                    $krs_detail_nilai->save();
                }

                $krs_detail = KRSDetail::where('id', $value['id'])->first();
                if ($krs_detail) {
                    $krs_detail->nilai_akhir = $value['nilai_akhir'];
                    $krs_detail->nilai_bobot = $value['nilai_bobot'];
                    $krs_detail->nilai_huruf = $value['nilai_huruf'];
                    $krs_detail->user_id = Auth::user()->id;
                    $krs_detail->save();
                }
            }
            alert()->success('Simpan Nilai Success', $this->title);
            DB::commit();
            return back()->withInput();
        } catch (\Throwable $th) {
            //throw $th;
            alert()->success('Gagal Simpan Data', $this->title);
            DB::rollback();
            dd($th->getMessage());
            return back()->withInput();
        }
    }
}
