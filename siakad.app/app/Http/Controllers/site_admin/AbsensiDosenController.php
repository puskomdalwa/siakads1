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
            // $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $list_kelas = Ref::where('table', 'Kelas')->get();
        $list_kelas = Ref::where('table', 'Kelas')->get();
        \DB::statement("SET SQL_MODE=''");
        $row = JadwalKuliah::where('trans_jadwal_kuliah.id', 3451)
            ->join('trans_kurikulum_matakuliah as tmk', 'tmk.id', '=', 'trans_jadwal_kuliah.kurikulum_matakuliah_id')
            ->join('mst_matakuliah as mm', 'mm.id', '=', 'tmk.matakuliah_id')
            ->join('mst_dosen as md', 'md.id', '=', 'trans_jadwal_kuliah.dosen_id')
            ->join('ref as r_kelompok', 'r_kelompok.id', '=', 'trans_jadwal_kuliah.kelompok_id')
            ->join('ref as r_hari', 'r_hari.id', '=', 'trans_jadwal_kuliah.hari_id')
            ->join('ref as r_ruang', 'r_ruang.id', '=', 'trans_jadwal_kuliah.ruang_kelas_id')
            ->join('trans_krs_detail as tkd', 'tkd.jadwal_kuliah_id', '=', 'trans_jadwal_kuliah.id')
            ->select(
                'trans_jadwal_kuliah.*',
                'mm.kode as kode_mk',
                'mm.nama as nama_mk',
                'mm.sks as sks_mk',
                'r_kelompok.kode as kelompok',
                'md.nama as dosen',
                'r_hari.nama as hari',
                'r_ruang.kode as ruang_kelas'
            )
            ->addSelect(\DB::raw('count(tkd.id) as jml_mhs'))
            // ->addSelect(\DB::raw("(SELECT mm.kode) as kode_md"))
            ->groupBy('trans_jadwal_kuliah.id')
            ->get();
        // $tes = AbsensiDetail::where('trans_jadwal_kuliah_id', $row->id)->get();
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
                'prodi_id'
            )
        );
    }

    public function getData(Request $request)
    {
        $search = $request->search['value'];

        $prodi_id = $request->prodi_id;
        $kelas_id = $request->kelas_id;

        $th_akademik_id = $request->th_akademik_id;

        \DB::statement("SET SQL_MODE=''");
        $row = JadwalKuliah::join('trans_kurikulum_matakuliah as tmk', 'tmk.id', '=', 'trans_jadwal_kuliah.kurikulum_matakuliah_id')
            ->join('mst_matakuliah as mm', 'mm.id', '=', 'tmk.matakuliah_id')
            ->join('mst_dosen as md', 'md.id', '=', 'trans_jadwal_kuliah.dosen_id')
            ->join('ref as r_kelompok', 'r_kelompok.id', '=', 'trans_jadwal_kuliah.kelompok_id')
            ->join('ref as r_hari', 'r_hari.id', '=', 'trans_jadwal_kuliah.hari_id')
            ->join('ref as r_ruang', 'r_ruang.id', '=', 'trans_jadwal_kuliah.ruang_kelas_id')
            ->join('trans_krs_detail as tkd', 'tkd.jadwal_kuliah_id', '=', 'trans_jadwal_kuliah.id')
            ->join('ref as r_jam', 'r_jam.id', '=', 'trans_jadwal_kuliah.jam_kuliah_id')
            ->select(
                'trans_jadwal_kuliah.*',
                'mm.kode as kd_mk',
                'mm.nama as nama_mk',
                'mm.sks as sks_mk',
                'mm.smt as smt_mk',
                'r_kelompok.kode as kelompok',
                'md.nama as dosen',
                'r_hari.nama as hari',
                'r_ruang.kode as ruang_kelas',
                'r_jam.nama as waktu'
            )
            ->addSelect(\DB::raw('count(tkd.id) as jml_mhs'))
            // ->addSelect(\DB::raw("(SELECT mm.kode) as kode_md"))
            ->groupBy('trans_jadwal_kuliah.id')
            ->where('trans_jadwal_kuliah.th_akademik_id', $th_akademik_id)
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('trans_jadwal_kuliah.prodi_id', $prodi_id);
            })
            ->when($kelas_id, function ($query) use ($kelas_id) {
                return $query->where('trans_jadwal_kuliah.kelas_id', $kelas_id);
            });

        return Datatables::of($row)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('md.nama', 'LIKE', "%$search%")
                        ->orWhere('mm.nama', 'LIKE', "%$search%")
                        ->orWhere('mm.sks', 'LIKE', "%$search%")
                        ->orWhere('mm.smt', 'LIKE', "%$search%")
                        ->orWhere('r_hari.nama', 'LIKE', "%$search%")
                        ->orWhere('r_kelompok.kode', 'LIKE', "%$search%")
                        ->orWhere('r_ruang.kode', 'LIKE', "%$search%");
                });
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
                return '<div class="btn-group">
                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Klik <span class="caret"></span></button>
                <ul class="dropdown-menu pull-right">' .
                    // '<li><a href="' . url('/' . $this->class . '/' . $row->id . '/absensi') . '">Isi Absensi</a></li>' .
                    '<li><a href="' . route('dosen_jadwal.rekapAbsensi', ['id' => $row->id]) . '">Isi Absensi</a></li>' .
                    '</ul>
            </div>';
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

        return view(
            $folder . '.absensi',
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
