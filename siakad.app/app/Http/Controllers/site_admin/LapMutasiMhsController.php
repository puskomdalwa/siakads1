<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mahasiswa;
use App\MutasiMhs;
use Auth;
use Yajra\Datatables\Datatables;
use Alert;
use App\ThAkademik;
use App\Prodi;
use App\Ref;
use App\User;
use App\PT;
use PDF;

class LapMutasiMhsController extends Controller
{
    private $title = 'Laporan Mutasi Mahasiswa';
    private $redirect = 'lapmutasimhs';
    private $folder = 'lapmutasimhs';
    private $class = 'lapmutasimhs';

    private $rules = [
        'th_akademik_id' => 'required',
    ];

    public function index()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;
        $level = strtolower(Auth::user()->level->level);
        $th_akademik_id = ThAkademik::Aktif()->first()->id;

        $prodi_id = @strtolower(Auth::user()->prodi->id);
        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            // $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }
        $list_status = Ref::where('table', 'StatusMhs')->get();
        $list_kelas = Ref::where('table', 'Kelas')->get();
        $list_kelompok = Ref::where('table', 'Kelompok')->get();
        $list_thakademik = ThAkademik::orderBy('kode', 'DESC')->get();

        return view(
            $folder . '.index',
            compact(
                'title',
                'redirect',
                'folder',
                'list_prodi',
                'list_status',
                'list_thakademik',
                'list_kelas',
                'list_kelompok',
                'level',
                'th_akademik_id',
                'prodi_id'
            )
        );
    }

    public function store(Request $request)
    {

        $th_akademik_id = $request->th_akademik_id;

        $prodi = @strtolower(Auth::user()->prodi->id);
        if ($prodi) {
            $prodi_id = $prodi;
        } else {
            $prodi_id = $request->prodi_id;
        }
        $kelas_id = $request->kelas_id;
        $kelompok_id = $request->kelompok_id;
        $status_id = $request->status_id;


        $data = MutasiMhs::
            select('trans_mutasi_mhs.*')
            ->join('mst_mhs', 'mst_mhs.nim', '=', 'trans_mutasi_mhs.nim')
            ->where('trans_mutasi_mhs.th_akademik_id', $th_akademik_id)
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('mst_mhs.prodi_id', $prodi_id);
            })
            ->when($kelas_id, function ($query) use ($kelas_id) {
                return $query->where('mst_mhs.kelas_id', $kelas_id);
            })
            ->when($kelompok_id, function ($query) use ($kelompok_id) {
                return $query->where('trans_perwalian.kelompok_id', $kelompok_id);
            })
            ->when($status_id, function ($query) use ($status_id) {
                return $query->where('trans_mutasi_mhs.status_id', $status_id);
            })
            ->orderBy('tanggal', 'desc')
            ->with(['mahasiswa'])
            ->get();

        return view($this->folder . '.data', compact('data'));
    }

    public function cetak(Request $request)
    {

        $th_akademik_id = $request->th_akademik_id;

        $prodi = @strtolower(Auth::user()->prodi->id);
        if ($prodi) {
            $prodi_id = $prodi;
        } else {
            $prodi_id = $request->prodi_id;
        }
        $kelas_id = $request->kelas_id;
        $kelompok_id = $request->kelompok_id;
        $status_id = $request->status_id;

        $th_akademik = ThAkademik::where('id', $th_akademik_id)->first();
        $pt = PT::first();


        $data = MutasiMhs::
            select('trans_mutasi_mhs.*')
            ->join('mst_mhs', 'mst_mhs.nim', '=', 'trans_mutasi_mhs.nim')
            ->where('trans_mutasi_mhs.th_akademik_id', $th_akademik_id)
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('mst_mhs.prodi_id', $prodi_id);
            })
            ->when($kelas_id, function ($query) use ($kelas_id) {
                return $query->where('mst_mhs.kelas_id', $kelas_id);
            })
            ->when($kelompok_id, function ($query) use ($kelompok_id) {
                return $query->where('trans_perwalian.kelompok_id', $kelompok_id);
            })
            ->when($status_id, function ($query) use ($status_id) {
                return $query->where('trans_mutasi_mhs.status_id', $status_id);
            })
            ->orderBy('tanggal', 'desc')
            ->with(['mahasiswa'])
            ->get();

        return view($this->folder . '.cetak', compact('data', 'th_akademik', 'pt'));

    }
}