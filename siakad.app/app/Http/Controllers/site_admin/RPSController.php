<?php
namespace App\Http\Controllers\site_admin;

use App\Dosen;
use App\Http\Controllers\Controller;
use App\JadwalKuliah;
use App\KRSDetail;
use App\Kurikulum;
use App\KurikulumMataKuliah;
use App\Prodi;
use App\Ref;
use App\ThAkademik;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class RPSController extends Controller
{

    private $title = 'Rencana Perkuliahan Semester';
    private $redirect = 'rps';
    private $folder = 'rps';
    private $class = 'rps';

    private $rules = [
        'th_akademik_id' => 'required',
        'prodi_id' => 'required',
        'kelas_id' => 'required',
        'kelompok_id' => 'required',
        'dosen_id' => 'required',
        'ruang_kelas_id' => 'required',
        'hari_id' => 'required',
        'jam_mulai' => 'required',
        'jam_selesai' => 'required',
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

        $th_akademik_id = ThAkademik::Aktif()->first()->id;
        $semester = ThAkademik::Aktif()->first()->semester;
        $list_thakademik = ThAkademik::orderBy('kode', 'Desc')->get();

        // if ($semester=='Ganjil'){
        //     $list_thakademik = ThAkademik::where('semester','Ganjil')->orderBy('kode','DESC')->get();
        // }else{
        //     $list_thakademik = ThAkademik::where('semester','Genap')->orderBy('kode','DESC')->get();
        // }

        $list_thakademik = ThAkademik::orderBy('kode', 'DESC')->get();

        $prodi_id = @strtolower(Auth::user()->prodi->id);

        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            // $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $prodi_id = Prodi::orderBy('kode', 'Asc')->first()->id;
        $kelas_id = Ref::where('table', 'Kelas')->first()->id;
        $list_kelas = Ref::where('table', 'Kelas')->get();

        return view(
            $folder . '.index',
            compact(
                'title',
                'redirect',
                'folder',
                'list_thakademik',
                'th_akademik_id',
                'list_prodi',
                'prodi_id',
                'list_kelas',
                'kelas_id'
            )
        );
    }

    public function getData(Request $request)
    {
        $th_akademik_id = $request->th_akademik_id;
        $prodi_id = $request->prodi_id;
        $kelas_id = $request->kelas_id;

        $search = $request->search['value'];

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
                'ref_jam_kuliah.nama as jamkul'
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
            ->editColumn('dosen', function ($row) {
                return $row->kode_dosen . ' - ' . $row->nama_dosen;
            })
            ->addColumn('jml_mhs', function ($row) {
                $krs_detail = KRSDetail::where('jadwal_kuliah_id', $row->id);
                return $krs_detail->count();
            })
            ->addColumn('waktu', function ($row) {
                if ($row->jam_kuliah_id != 0) {
                    return $row->jamkul;
                } else {
                    return $row->jam_mulai . ' ' . $row->jam_selesai;
                }
            })
            ->addColumn('file_rps', function ($row) {
                return !empty($row->rps->dokumen) ? '<a href="' . url('/dokumen_rps/' . $row->rps->dokumen) . '" target="_blank">
			<i class="fa fa-download" style="font-size:15px;"><i> <span>Download</span></a>' : null;
            })
            ->rawColumns(['file_rps'])->make(true);
    }

    public function getDataOld(Request $request)
    {
        $th_akademik_id = $request->th_akademik_id;
        $prodi_id = $request->prodi_id;

        $kurikulum = Kurikulum::select('id')->where('prodi_id', $prodi_id)->get();

        $row = KurikulumMataKuliah::select('trans_kurikulum_matakuliah.*')->join(
            'trans_jadwal_kuliah',
            'trans_jadwal_kuliah.kurikulum_matakuliah_id',
            '=',
            'trans_kurikulum_matakuliah.id'
        )
            ->whereIn('trans_kurikulum_matakuliah.kurikulum_id', $kurikulum)
            ->with('kurikulum', 'matakuliah')
            ->get();

        return Datatables::of($row)
            ->addColumn('kd_mk', function ($row) {
                return $row->matakuliah->kode;
            })
            ->addColumn('nama_mk', function ($row) {
                return $row->matakuliah->nama;
            })
            ->addColumn('sks_mk', function ($row) {
                return $row->matakuliah->sks;
            })
            ->addColumn('smt_mk', function ($row) {
                return $row->matakuliah->smt;
            })
            ->addColumn('jml_rps', function ($row) {
                $jml = 0;
                return $jml;
            })
            ->setRowClass(function ($row) {
                $jml = 0;
                return $jml > 0 ? 'alert-success' : '';
            })
            ->addColumn('details_url', function ($row) {
                return url($this->folder . '/getDetailsData/' . $row->id);
            })
            ->rawColumns(['action'])->make(true);
    }

    public function getDetailsData($id)
    {
        $row = JadwalKuliah::where('kurikulum_matakuliah_id', $id)
            ->with('kelas', 'kelompok', 'hari', 'ruang_kelas', 'dosen')->get();

        return Datatables::of($row)
            ->addColumn('kelas', function ($row) {
                return $row->kelas->nama;
            })
            ->addColumn('kelompok', function ($row) {
                return $row->kelompok->kode;
            })
            ->addColumn('hari', function ($row) {
                return $row->hari->nama;
            })
            ->addColumn('ruang', function ($row) {
                return $row->ruang_kelas->nama;
            })
            ->addColumn('waktu', function ($row) {
                return $row->jam_mulai . ' - ' . $row->jam_selesai;
            })
            ->addColumn('dosen', function ($row) {
                return $row->dosen->kode . ' - ' . $row->dosen->nama;
            })
            ->addColumn('file_rps', function ($row) {
                return '';
            })
            ->make(true);
    }
}