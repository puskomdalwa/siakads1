<?php
namespace App\Http\Controllers\site_admin;

use Alert;
use App\AbsensiDetail;
use App\BobotNilai;
use App\Http\Controllers\Controller;
use App\JadwalKuliah;
use App\KomponenNilai;
use App\KRSDetail;
use App\KRSDetailNilai;
use App\Prodi;
use App\Ref;
use App\ThAkademik;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class AbsensiDosenController extends Controller
{
    private $title = 'Absensi Dosen';
    private $redirect = 'absensidosen';
    private $folder = 'absensidosen';
    private $class = 'absensidosen';

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
        $semester = $th_akademik->semester;

        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $list_thakademik = ThAkademik::orderBy('kode', 'DESC')->get();

        $prodi_id = @strtolower(Auth::user()->prodi->id);

        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $list_kelas = Ref::where('table', 'Kelas')->get();

        return view($folder . '.index',
            compact('title', 'redirect', 'folder',
                'list_thakademik', 'list_prodi', 'list_kelas', 'prodi_id')
        );
    }

    public function getData(Request $request)
    {
        $prodi_id = $request->prodi_id;
        $kelas_id = $request->kelas_id;

        $th_akademik_id = $request->th_akademik_id;

        $row = JadwalKuliah::where('th_akademik_id', $th_akademik_id)
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('prodi_id', $prodi_id);
            })
            ->when($kelas_id, function ($query) use ($kelas_id) {
                return $query->where('kelas_id', $kelas_id);
            })
            ->with(['kurikulum_matakuliah', 'dosen', 'kelompok', 'hari', 'ruang_kelas', 'jamkul']);

        return Datatables::of($row)
            ->addColumn('kd_mk', function ($row) {
                return @$row->kurikulum_matakuliah->matakuliah->kode;
            })
            ->addColumn('nama_mk', function ($row) {
                return @$row->kurikulum_matakuliah->matakuliah->nama;
            })
            ->addColumn('sks_mk', function ($row) {
                return @$row->kurikulum_matakuliah->matakuliah->sks;
            })
            ->addColumn('smt_mk', function ($row) {
                return @$row->kurikulum_matakuliah->matakuliah->smt;
            })
            ->addColumn('kelompok', function ($row) {
                return @$row->kelompok->kode;
            })
            ->addColumn('kurikulum', function ($row) {
                return @$row->kurikulum_matakuliah->kurikulum->th_akademik->kode;
            })
            ->addColumn('dosen', function ($row) {
                return $row->dosen->nama;
            })
            ->addColumn('hari', function ($row) {
                return @$row->hari->nama;
            })
            ->addColumn('ruang_kelas', function ($row) {
                return @$row->ruang_kelas->kode;
            })
            ->addColumn('waktu', function ($row) {
                if ($row->jam_kuliah_id > 0) {
                    return $row->jamkul->nama;
                } else {
                    return $row->jam_mulai . ' ' . $row->jam_selesai;
                }
            })
            ->addColumn('jml_mhs', function ($row) {
                $krs_detail = KRSDetail::where('jadwal_kuliah_id', $row->id);
                return $krs_detail->count();
            })
            ->addColumn('status', function ($row) {
                $absensi_dosen = AbsensiDetail::where('trans_jadwal_kuliah_id', $row->id)->count();
                $absensi_detail = AbsensiDetail::where('trans_jadwal_kuliah_id', $row->id)
                    ->whereNotNull('status')->count();

                if ($absensi_dosen > 0) {
                    return $absensi_dosen == $absensi_detail ?
                    '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
                } else {
                    return '<i class="fa fa-times text-danger"></i>';
                }
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-xs" id="c-tooltips-demo">
			<a href="' . url('/' . $this->class . '/' . $row->id . '/absensi') .
                    '" class="btn btn-info btn-xs btn-rounded tooltip-info"
			data-toggle="tooltip" data-placement="top" data-original-title="Edit">
			<i class="fa fa-edit"></i></a></div>';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function absensi($id)
    {
        $data = JadwalKuliah::findOrFail($id);

        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $prodi_id = $data->prodi_id;
        $dosen_id = $data->dosen_id;
        $th_akademik_id = $data->th_akademik_id;

        $jmlmhs = KRSDetail::where('jadwal_kuliah_id', $id)->count();

        $list_abs = AbsensiDetail::select('trans_absensi_mhs.*')
            ->where('trans_absensi_mhs.trans_jadwal_kuliah_id', $id)
            ->join('trans_absensi_mhs', 'trans_absensi_mhs.id', '=', 'trans_absensi_mhs_detail.id')
            ->OrderBy('trans_absensi_mhs.tanggal', 'asc')
            ->get();

        $komponen_nilai = KomponenNilai::get();

        return view($folder . '.absensi',
            compact('title', 'redirect', 'folder', 'data', 'list_abs', 'komponen_nilai', 'jmlmhs')
        );
    }

    public function getBobotNilai(Request $request)
    {
        $nilai_akhir = $request->nilai_akhir;

        $data = BobotNilai::where('nilai_max', '>=', $nilai_akhir)
            ->orderBy('nilai_max', 'asc')
            ->limit(1)
            ->first();

        return response()->json([
            'nilai_huruf' => $data->nilai_huruf,
            'nilai_bobot' => $data->nilai_bobot,
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
                $krs_detail->nilai_akhir = $value['nilai_akhir'];
                $krs_detail->nilai_bobot = $value['nilai_bobot'];
                $krs_detail->nilai_huruf = $value['nilai_huruf'];
                $krs_detail->user_id = Auth::user()->id;
                $krs_detail->save();
            }
        }

        alert()->success('Simpan Nilai Success', $this->title);
        return back()->withInput();
    }
}
