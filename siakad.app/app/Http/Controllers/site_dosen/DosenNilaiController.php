<?php
namespace App\Http\Controllers\site_dosen;

use PDF;
use Auth;
use Alert;
use App\PT;
use App\KRS;
use App\Ref;
use App\Dosen;
use App\Prodi;
use App\Absensi;
use App\KRSDetail;
use App\BobotNilai;
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

class DosenNilaiController extends Controller
{

    private $title = 'Nilai Mahasiswa';
    private $redirect = 'dosen_nilai';
    private $folder = 'site_dosen/dosen_nilai';
    private $class = 'dosen_nilai';

    private $rules = [
        'th_akademik_id' => 'required',
        'prodi_id' => 'required',
        'kelas_id' => 'required',
        'kelompok_id' => 'required',
        'kurikulum_matakuliah_id' => 'required',
        'dosen_id' => 'required',
        'ruang_kelas_id' => 'required',
        'hari_id' => 'required',
    ];

    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');

        $title = $this->title;
        $folder = $this->folder;
        $redirect = $this->redirect;
        $kode = Auth::user()->username;

        $list_prodi = Prodi::get();
        $list_kelas = Ref::where('table', 'Kelas')->get();

        $th_akademik = ThAkademik::Aktif()->first();
        $th_akademik_id = $th_akademik->id;

        // $list_thakademik = ThAkademik::where('id', $th_akademik_id)->orderBy('kode', 'DESC')->get();
        $list_thakademik = ThAkademik::orderBy('kode', 'DESC')->get();
        // $list_thakademik = ThAkademik::where('id', '!=', 20)->orderBy('kode', 'DESC')->get();

        $semester = $th_akademik->semester;

        if ($semester == 'Ganjil') {
            $form = FormSchadule::where('kode', 'NIL-1')->first();
        } else {
            $form = FormSchadule::where('kode', 'NIL-2')->first();
        }

        $form->tgl_mulai = date('Y-m-d 00:00:00', strtotime($form->tgl_mulai));
        $form->tgl_selesai = date('Y-m-d 23:59:59', strtotime($form->tgl_selesai));
        $tgl = date('Y-m-d H:i:s');
        //$tgl_mulai     = date_format($form->tgl_mulai,'Y-m-d H:i:s');
        //$tgl_selesai = date_format($form->tgl_selesai,'Y-m-d H:i:s');

        return view(
            $folder . '.index',
            compact(
                'title',
                'redirect',
                'folder',
                'list_thakademik',
                'list_prodi',
                'list_kelas',
                'kode',
                'tgl',
                'form'
            )
        );
    }

    public function getData(Request $request)
    {
        // $th_akademik = ThAkademik::Aktif()->first();
        // $semester = $th_akademik->semester;
        $th_akademik_id = $request->th_akademik_id;

        $kode = Auth::user()->username;
        $dosen = Dosen::where('kode', $kode)->first();

        $row = JadwalKuliah::where('dosen_id', $dosen->id)
            ->where('th_akademik_id', $th_akademik_id)
            ->with(['kurikulum_matakuliah', 'dosen', 'kelompok', 'hari', 'jamkul', 'ruang_kelas'])
            ->get();

        //print_r($row->id);

        return Datatables::of($row)
            ->addColumn('th_akademik', function ($row) {
                return @$row->th_akademik->kode;
            })
            ->addColumn('kd_mk', function ($row) {
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
            ->addColumn('kurikulum', function ($row) {
                return $row->kurikulum_matakuliah->kurikulum->th_akademik->kode;
            })
            ->addColumn('dosen', function ($row) {
                return $row->dosen->nama;
            })
            ->addColumn('hari', function ($row) {
                return $row->hari->nama;
            })
            ->addColumn('ruang_kelas', function ($row) {
                return $row->ruang_kelas->kode;
            })
            ->addColumn('waktu', function ($row) {
                return $row->jamkul->nama;
            })

            ->addColumn('jml_mhs', function ($row) {
                $krs_detail = KRSDetail::select('KRSDetail.*')
                    ->join('trans_krs', 'trans_krs.id', '=', 'trans_krs_detail.krs_id')
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
                    ->where('trans_krs_detail.jadwal_kuliah_id', $row->id)
                    ->where('trans_krs.acc_pa', 'Setujui')
                    ->whereNotNull('trans_krs_detail.nilai_akhir')->count();

                return $krs_detail == $krs_detail_nilai ? '<i class="fa fa-check text-success"></i>' :
                    '<i class="fa fa-times" style="font-size:16px;color:red"></i>' . "($krs_detail_nilai/$krs_detail)";
            })
            ->addColumn('nilai', function ($row) {
                $krs_detail = KRSDetail::join('trans_krs', 'trans_krs.id', '=', 'trans_krs_detail.krs_id')
                    ->join('mst_mhs', 'mst_mhs.nim', '=', 'trans_krs.nim')
                    ->where('trans_krs.acc_pa', 'Setujui')
                    ->where('trans_krs_detail.jadwal_kuliah_id', $row->id)->count();

                $krs_detail_nilai = KRSDetail::join('trans_krs', 'trans_krs.id', '=', 'trans_krs_detail.krs_id')
                    ->join('mst_mhs', 'mst_mhs.nim', '=', 'trans_krs.nim')
                    ->where('trans_krs_detail.jadwal_kuliah_id', $row->id)
                    ->where('trans_krs.acc_pa', 'Setujui')
                    ->whereNotNull('trans_krs_detail.nilai_akhir')->count();

                return $krs_detail == $krs_detail_nilai ? "Sukses" : "Bermasalah";
            })

            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group btn-group-xs" id="c-tooltips-demo">';

                $btn = $btn . '<a href="' . url('/' . $this->class . '/' . $row->id . '/edit') . '" class="btn btn-primary" >Edit</a>';
                $btn = $btn . '<a href="' . url('/' . $this->class . '/' . $row->id . '/cetak') . '" target="_blank" class="btn btn-success" >Cetak</a>';
                //     $btn = $btn . '<a href="' . url('/' . $this->class . '/' . $row->id . '/edit') . '"
                // class="btn btn-info btn-xs btn-rounded tooltip-info" data-toggle="tooltip"
                // data-placement="top" data-original-title="Edit"><i class="fa fa-edit"></i></a>';
    
                //     $btn = $btn . '<a href="' . url('/' . $this->class . '/' . $row->id . '/cetak') . '"
                // class="btn btn-info btn-xs btn-rounded tooltip-info" data-toggle="tooltip" target="_blank"
                // data-placement="top" data-original-title="Print"><i class="fa fa-print"></i></a>';
    
                $btn = $btn . '</div>';
                return $btn;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
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
                return url($this->redirect . "/$id/getDataIsiNilai/$row->id");
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
                $query->orWhere('trans_krs_detail.nim', 'LIKE', "%$search")
                    ->orWhere('mst_mhs.nama', 'LIKE', "%$search")
                    ->orWhere('ref_jk.nama', 'LIKE', "%$search");
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
                'text' => 'Berhasil simpan nilai ' . $krs_detail->nim . ' ' . $jumlahAbsensi,
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
        // dd($request);
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
        // ->skip(0)
        // ->take(5);

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

    public function edit($id)
    {
        $data = JadwalKuliah::findOrFail($id);

        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $th_akademik = ThAkademik::aktif()->first();
        $semester = $th_akademik->semester;

        $tgl = date('Y-m-d H:i:s');
        $form = FormSchadule::where('kode', 'NILAI')->first();

        if ($semester == 'Ganjil') {
            $form = FormSchadule::where('kode', 'NIL-1')->first();
        } else {
            $form = FormSchadule::where('kode', 'NIL-2')->first();
        }

        $form->tgl_mulai = date('Y-m-d 00:00:00', strtotime($form->tgl_mulai));
        $form->tgl_selesai = date('Y-m-d 23:59:59', strtotime($form->tgl_selesai));

        $list_mhs = KRSDetail::orderBy('nim', 'asc')->where('jadwal_kuliah_id', $id)->get();
        $komponen_nilai = KomponenNilai::get();

        $namaMatkul = isset($data->kurikulum_matakuliah->matakuliah->nama) ? $data->kurikulum_matakuliah->matakuliah->nama : "";
        $cekSkripsi = strtolower($namaMatkul) == "skripsi" ? true : false;

        return view(
            $folder . '.edit',
            compact('title', 'redirect', 'folder', 'data', 'list_mhs', 'komponen_nilai', 'tgl', 'form', 'cekSkripsi', 'id')
        );
    }

    public function getBobotNilai(Request $request)
    {
        $nilai_akhir = $request->nilai_akhir;
        $data = BobotNilai::where('nilai_max', '>=', $nilai_akhir)->orderBy('nilai_max', 'asc')->limit(1)->first();

        return response()->json([
            'nilai_bobot' => $data->nilai_bobot,
            'nilai_huruf' => $data->nilai_huruf,
        ]);
    }

    public function update(Request $request, $id)
    {
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
                $krs_detail->nilai_akhir = number_format($value['nilai_akhir'], 2);
                $krs_detail->nilai_bobot = number_format($value['nilai_bobot'], 2);
                $krs_detail->nilai_huruf = $value['nilai_huruf'];
                $krs_detail->user_id = Auth::user()->id;
                $krs_detail->save();
            }
        }

        alert()->success('Simpan Nilai Success', $this->title);
        return back()->withInput();
    }

    public function cetakkrs($krs_id)
    {
        $krs = KRS::where('id', $krs_id)
            ->with('mahasiswa', 'th_akademik', 'prodi', 'kelas')
            ->first();

        $th_akademik = ThAkademik::where('id', $krs->th_akademik_id)->first();

        $pt = PT::first();
        $prodi = @Prodi::where('id', $krs->prodi_id)->first();

        $data = KRSDetail::where('krs_id', $krs->id)
            ->with('jadwal_kuliah', 'jamkul')
            ->get();

        $class = 'text-center';

        $pdf = PDF::loadView(
            $this->folder . '.cetak',
            compact('data', 'pt', 'th_akademik', 'krs', 'prodi', 'class')
        );

        return $pdf->setPaper('a4', 'portrait')
            ->stream('KRS ' . $th_akademik->kode . ' ' . $krs->nim . '.pdf');
    }

    public function cetakAll(Request $request)
    {
        $th_akademik_id = $request->th_akademik_id;
        $prodi_id = $request->prodi_id;
        $kelas_id = $request->kelas_id;
        $kelompok_id = $request->kelompok_id;

        $th_akademik = ThAkademik::where('id', $th_akademik_id)->first();
        $pt = PT::first();

        $data = JadwalKuliah::where('th_akademik_id', $th_akademik_id)
            ->where('prodi_id', $prodi_id)
            ->where('kelas_id', $kelas_id)
            ->when($kelompok_id, function ($query) use ($kelompok_id) {
                return $query->where('kelompok_id', $kelompok_id);
            })
            ->orderBy('smt', 'asc')
            ->with(['th_akademik', 'prodi', 'kelas', 'kelompok', 'kurikulum_matakuliah', 'dosen', 'ruang_kelas'])->get();

        $pdf = PDF::loadView(
            $this->folder . '.cetak',
            compact('data', 'th_akademik', 'pt')
        );

        return $pdf->setPaper('a4', 'landscape')->stream('Laporan KRS ' . $th_akademik->kode . '.pdf');
    }

    public function cetak($id)
    {
        $pt = PT::first();
        $prodi = @Prodi::where('id', Auth::user()->prodi_id)->first();

        $jadwal = JadwalKuliah::where('id', $id)
            ->with('kurikulum_matakuliah', 'th_akademik')->first();

        $th_akademik = ThAkademik::where('id', $jadwal->th_akademik_id)->first();

        $data = KRSDetail::select('trans_krs_detail.*')
            ->join('mst_mhs', 'mst_mhs.nim', '=', 'trans_krs_detail.nim')
            ->where('jadwal_kuliah_id', $id)
            ->orderBy('mst_mhs.nama', 'asc')
            ->with(['mahasiswa'])->get();

        $komponen_nilai = KomponenNilai::get();
        $class = 'text-center';

        $pdf = PDF::loadView(
            $this->folder . '.cetak',
            compact('data', 'th_akademik', 'pt', 'jadwal', 'komponen_nilai', 'prodi', 'class')
        );

        return $pdf->setPaper('a4', 'potrait')
            ->stream('NILAI MAHASISWA ' . $jadwal->dosen->nama . ' ' .
                $jadwal->kurikulum_matakuliah->matakuliah->nama . '.pdf');
    }
}
