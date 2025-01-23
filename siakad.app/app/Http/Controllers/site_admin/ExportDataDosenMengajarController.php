<?php
namespace App\Http\Controllers\site_admin;

use App\Dosen;
use App\Http\Controllers\Controller;
use App\JadwalKuliah;
use App\KRSDetail;
use App\Prodi;
use App\ThAkademik;
use Auth;
use Illuminate\Http\Request;

class ExportDataDosenMengajarController extends Controller
{
    private $title = 'Export Data Dosen Mengajar';
    private $redirect = 'exportdatadosenmengajar';
    private $folder = 'exportdatadosenmengajar';
    private $class = 'exportdatadosenmengajar';

    public function index()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $prodi_id = @strtolower(Auth::user()->prodi->id);
        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $list_thakademik = ThAkademik::orderBy('kode', 'DESC')->get();

        return view($folder . '.index', compact('title', 'redirect', 'folder', 'list_prodi', 'list_thakademik'));
    }

    public function export(Request $request)
    {
        $prodi = Prodi::find($request->prodi_id);
        $aliasProdi = $prodi ? " - $prodi->alias" : "Semua Prodi";
        $thAkademikSiakad = ThAkademik::find($request->th_akademik_id);
        $aliasThAkademik = $thAkademikSiakad ? "- $thAkademikSiakad->kode" : " Semua Th Akademik";

        $dosens = Dosen::when($request->dosen_id, function ($q) use ($request) {
            $q->where('prodi_id', $request->prodi_id);
        })->get();

        $data = [];
        foreach ($dosens as $dosen) {
            $jadwalKuliah = JadwalKuliah::leftJoin('mst_dosen', 'mst_dosen.id', 'trans_jadwal_kuliah.dosen_id')
                ->leftJoin('mst_th_akademik', 'mst_th_akademik.id', '=', 'trans_jadwal_kuliah.th_akademik_id')
                ->leftJoin('ref as kelompok', 'kelompok.id', '=', 'trans_jadwal_kuliah.kelompok_id')
                ->leftJoin('mst_prodi', 'mst_prodi.id', '=', 'trans_jadwal_kuliah.prodi_id')
                ->leftJoin('trans_kurikulum_matakuliah', 'trans_kurikulum_matakuliah.id', '=', 'trans_jadwal_kuliah.kurikulum_matakuliah_id')
                ->leftJoin('mst_matakuliah', 'mst_matakuliah.id', '=', 'trans_kurikulum_matakuliah.matakuliah_id')
                ->leftJoin('trans_absensi_mhs', 'trans_absensi_mhs.trans_jadwal_kuliah_id', '=', 'trans_jadwal_kuliah.id')
                ->when($request->th_akademik_id, function ($q) use ($request) {
                    $q->where('trans_jadwal_kuliah.th_akademik_id', $request->th_akademik_id);
                })
                ->where('trans_jadwal_kuliah.dosen_id', $dosen->id)
                ->select(
                    'mst_th_akademik.kode as th_akademik_kode',
                    'mst_dosen.nidn as dosen_nidn',
                    'mst_dosen.nama as dosen_nama',
                    'mst_matakuliah.nama as matakuliah_nama',
                    'mst_matakuliah.kode as matakuliah_kode',
                    'mst_matakuliah.sks as matakuliah_sks',
                    'kelompok.kode as kelompok_kode',
                    'mst_prodi.kode as prodi_kode',
                    'mst_prodi.nama as prodi_nama',
                    \DB::raw('COUNT(trans_absensi_mhs.trans_jadwal_kuliah_id) as tatap_muka')
                )
                ->groupBy(
                    'mst_th_akademik.kode',
                    'mst_dosen.nidn',
                    'mst_dosen.nama',
                    'mst_matakuliah.nama',
                    'mst_matakuliah.kode',
                    'mst_matakuliah.sks',
                    'kelompok.kode',
                    'mst_prodi.kode',
                    'mst_prodi.nama',
                )
                ->get();

            $data[] = $jadwalKuliah;
        }
        $nama = "Export Dosen Mengajar $aliasProdi $aliasThAkademik.xls";
        return view('exportdatadosenmengajar.excel', compact('nama', 'data'));
    }
}


// $jadwalKuliah = JadwalKuliah::when($request->th_akademik_id, function ($q) use ($request) {
//     $q->where('th_akademik_id', $request->th_akademik_id);
// })
//     ->where('dosen_id', $dosen->id)
//     ->select('trans_jadwal_kuliah.*')
//     ->addSelect(\DB::raw('(SELECT COUNT(trans_jadwal_kuliah_id) FROM trans_absensi_mhs WHERE trans_jadwal_kuliah_id = trans_jadwal_kuliah.id) as tatap_muka'))
//     ->get();