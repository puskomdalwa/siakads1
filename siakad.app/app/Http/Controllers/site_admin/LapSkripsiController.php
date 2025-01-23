<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Yajra\Datatables\Datatables;
use Alert;
use App\ThAkademik;
use App\Prodi;
use App\User;
use PDF;
use App\SkripsiPengajuan;
use App\SkripsiJudul;
use App\SkripsiPembimbing;

class LapSkripsiController extends Controller
{
    private $title = 'Laporan Skripsi';
    private $redirect = 'lapskripsi';
    private $folder = 'lapskripsi';
    private $class = 'lapskripsi';

    private $rules = [
        'th_akademik_id' => 'required',
    ];

    public function index()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;
        $level = strtolower(Auth::user()->level->level);

        $prodi_id = @strtolower(Auth::user()->prodi->id);
        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            // $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $list_thakademik = ThAkademik::orderBy('kode', 'DESC')->get();

        return view(
            $folder . '.index',
            compact('title', 'redirect', 'folder', 'list_prodi', 'list_thakademik', 'level')
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

        $data = SkripsiJudul::
            select('skripsi_judul.*')
            ->join('skripsi_pengajuan', 'skripsi_pengajuan.id', '=', 'skripsi_judul.skripsi_pengajuan_id')
            ->join('mst_mhs', 'mst_mhs.nim', '=', 'skripsi_pengajuan.nim')
            ->where('skripsi_judul.acc', 'Y')
            ->where('skripsi_pengajuan.th_akademik_id', $th_akademik_id)
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('mst_mhs.prodi_id', $prodi_id);
            })
            ->get();

        return view($this->folder . '.data', compact('data'));
    }
}