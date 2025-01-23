<?php
namespace App\Http\Controllers\site_admin;

use App\Http\Controllers\Controller;
use App\JadwalKuliah;
use App\Prodi;
use App\PT;
use App\ThAkademik;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class LapJadwalKuliahController extends Controller
{

    private $title = 'Laporan Jadwal Kuliah';
    private $redirect = 'lapjadwalkuliah';
    private $folder = 'lapjadwalkuliah';
    private $class = 'lapjadwalkuliah';

    private $rules = [
        'prodi_id' => 'required',
    ];

    public function index()
    {
        $th_akademik = ThAkademik::Aktif()->first();
        $th_akademik_aktif = $th_akademik->kode;

        $title = $this->title . ' Tahun Akademik ' . $th_akademik_aktif;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $prodi_id = @strtolower(Auth::user()->prodi->id);

        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            // $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $list_thakademik = ThAkademik::where('semester', 'Ganjil')->orderBy('kode', 'DESC')->get();
        $list_tahun_akademik = ThAkademik::orderBy('kode', 'DESC')->get();

        return view(
            $folder . '.index',
            compact('title', 'redirect', 'folder', 'list_prodi', 'prodi_id', 'list_tahun_akademik')
        );
    }

    public function store(Request $request)
    {

        $th_akademik = ThAkademik::Aktif()->first();
        if ($request->th_akademik_id) {
            $th_akademik = ThAkademik::where('id', $request->th_akademik_id)->first();
        }
        $th_akademik_id = $th_akademik->id;

        $prodi = @strtolower(Auth::user()->prodi->id);

        if ($prodi) {
            $prodi_id = $prodi;
        } else {
            $prodi_id = $request->prodi_id;
        }

        $dt_prodi = Prodi::where('id', $prodi_id)->first();

        $list_smt = JadwalKuliah::select('smt')
            ->where('th_akademik_id', $th_akademik_id)
            ->where('prodi_id', $prodi_id)
            ->groupBy('smt')
            ->orderBy('smt')
            ->get();

        return view(
            $this->folder . '.data2',
            compact('list_smt', 'th_akademik', 'prodi_id', 'dt_prodi')
        );
    }

    public function cetak(Request $request)
    {
        // $th_akademik = ThAkademik::Aktif()->first();
        $th_akademik = ThAkademik::where('id', $request->th_akademik_id)->first();
        $th_akademik_id = $th_akademik->id;
        $prodi_id = $request->prodi_id;

        $dt_prodi = Prodi::where('id', $prodi_id)->first();

        $pt = PT::first();
        $prodi = @Prodi::where('id', Auth::user()->prodi->id)->first();

        $list_smt = JadwalKuliah::select('smt')
            ->where('th_akademik_id', $th_akademik_id)
            ->where('prodi_id', $prodi_id)
            ->groupBy('smt')
            ->orderBy('smt')
            ->get();

        $class = 'text-left';

        $pdf = PDF::loadView(
            $this->folder . '.cetak',
            compact('list_smt', 'th_akademik', 'prodi_id', 'dt_prodi', 'prodi', 'pt', 'class')
        );

        return $pdf->setPaper('a4', 'landscape')->stream('Laporan Jadwal Kuliah.pdf');
    }

    public function excel($prodiId, $thAkademikId)
    {
        // $th_akademik = ThAkademik::Aktif()->first();
        $th_akademik = ThAkademik::where('id', $thAkademikId)->first();
        $th_akademik_id = $th_akademik->id;
        $prodi_id = $prodiId;

        $dt_prodi = Prodi::where('id', $prodi_id)->first();

        $pt = PT::first();
        $prodi = @Prodi::where('id', Auth::user()->prodi->id)->first();

        $list_smt = JadwalKuliah::select('smt')
            ->where('th_akademik_id', $th_akademik_id)
            ->where('prodi_id', $prodi_id)
            ->groupBy('smt')
            ->orderBy('smt')
            ->get();

        $class = 'text-left';
        $judul = "Jadwal Kuliah $dt_prodi->alias $th_akademik->kode";
        return view('lapjadwalkuliah.excel', compact('list_smt', 'th_akademik', 'prodi_id', 'dt_prodi', 'prodi', 'pt', 'class', 'judul'));
    }
}