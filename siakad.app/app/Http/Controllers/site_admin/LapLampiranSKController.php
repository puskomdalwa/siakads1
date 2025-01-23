<?php
namespace App\Http\Controllers\site_admin;

use PDF;
use Auth;
use Alert;
use App\PT;
use App\Ref;
use App\User;
use App\Dosen;
use App\Prodi;
use App\Yudisium;
use App\Mahasiswa;
use App\ThAkademik;
use App\JadwalKuliah;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;

class LapLampiranSKController extends Controller
{
    private $title = 'Lampiran SK';
    private $redirect = 'laplampiransk';
    private $folder = 'laplampiransk';
    private $class = 'laplampiransk';

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
        $list_thakademik = ThAkademik::orderBy('kode', 'DESC')->get();

        $jk = Ref::where('table', 'JenisKelamin')->get();
        return view(
            $folder . '.index',
            compact(
                'title',
                'redirect',
                'folder',
                'list_prodi',
                'list_thakademik',
                'level',
                'th_akademik_id',
                'prodi_id',
                'jk'
            )
        );
    }

    public function store(Request $request)
    {
        $prodi = Prodi::find($request->prodi_id);
        $aliasProdi = $prodi ? " - $prodi->alias" : "Semua Prodi";
        $thAkademikSiakad = ThAkademik::find($request->th_akademik_id);
        $aliasThAkademik = $thAkademikSiakad ? "- $thAkademikSiakad->kode" : " Semua Th Akademik";

        $dosens = Dosen::when($request->dosen_id, function ($q) use ($request) {
            $q->where('prodi_id', $request->prodi_id);
        })->get();

        $data = JadwalKuliah::leftJoin('mst_dosen', 'mst_dosen.id', 'trans_jadwal_kuliah.dosen_id')
            ->leftJoin('mst_th_akademik', 'mst_th_akademik.id', '=', 'trans_jadwal_kuliah.th_akademik_id')
            ->leftJoin('ref as kelompok', 'kelompok.id', '=', 'trans_jadwal_kuliah.kelompok_id')
            ->leftJoin('mst_prodi', 'mst_prodi.id', '=', 'trans_jadwal_kuliah.prodi_id')
            ->leftJoin('trans_kurikulum_matakuliah', 'trans_kurikulum_matakuliah.id', '=', 'trans_jadwal_kuliah.kurikulum_matakuliah_id')
            ->leftJoin('mst_matakuliah', 'mst_matakuliah.id', '=', 'trans_kurikulum_matakuliah.matakuliah_id')
            ->when($request->th_akademik_id, function ($q) use ($request) {
                $q->where('trans_jadwal_kuliah.th_akademik_id', $request->th_akademik_id);
            })
            ->when($request->prodi_id, function ($q) use ($request) {
                $q->where('trans_jadwal_kuliah.prodi_id', $request->prodi_id);
            })
            ->when($request->jk_id, function ($q) use ($request) {
                $q->where('mst_dosen.jk_id', $request->jk_id);
            })
            ->select(
                'trans_jadwal_kuliah.prodi_id as prodi_id',
                'trans_jadwal_kuliah.th_akademik_id as th_akademik_id',
                'trans_jadwal_kuliah.kurikulum_matakuliah_id as kurikulum_matakuliah_id',
                'trans_jadwal_kuliah.dosen_id as dosen_id',
                'mst_th_akademik.kode as th_akademik_kode',
                'mst_dosen.nidn as dosen_nidn',
                'mst_dosen.kode as dosen_kode',
                'mst_dosen.nama as dosen_nama',
                'mst_matakuliah.nama as matakuliah_nama',
                'mst_matakuliah.kode as matakuliah_kode',
                'mst_matakuliah.sks as matakuliah_sks',
                'mst_matakuliah.smt as matakuliah_smt',
                'mst_prodi.kode as prodi_kode',
                'mst_prodi.nama as prodi_nama',
                'mst_prodi.alias as prodi_alias',
            )
            ->groupBy(
                'trans_jadwal_kuliah.prodi_id',
                'trans_jadwal_kuliah.th_akademik_id',
                'trans_jadwal_kuliah.kurikulum_matakuliah_id',
                'trans_jadwal_kuliah.dosen_id',
                'mst_th_akademik.kode',
                'mst_dosen.nidn',
                'mst_dosen.kode',
                'mst_dosen.nama',
                'mst_matakuliah.nama',
                'mst_matakuliah.kode',
                'mst_matakuliah.sks',
                'mst_matakuliah.smt',
                'mst_prodi.kode',
                'mst_prodi.nama',
                'mst_prodi.alias'
            )
            ->orderBy('mst_dosen.nama', 'ASC')
            ->get()
            ->map(function ($row) {
                $jadwal = JadwalKuliah::where('dosen_id', $row->dosen_id)
                    ->where('prodi_id', $row->prodi_id)
                    ->where('th_akademik_id', $row->th_akademik_id)
                    ->where('kurikulum_matakuliah_id', $row->kurikulum_matakuliah_id)
                    ->get();

                // change kelas to A B C and SKS
                $kelas = "";
                $sks = 0;
                foreach ($jadwal as $key => $value) {
                    $nama = explode(' ', $value->kelompok->nama);
                    if (stripos(end($nama), "putra") !== false || stripos(end($nama), "putri") !== false) {
                        $nama[] = "A";
                    }
                    $jenisKelamin = stripos($nama[3], 'putra') !== false ? "L" : "P";
                    $jenisKelas = $nama[4];
                    $kelas .= "$jenisKelamin$jenisKelas ";

                    $sks += $value->kurikulum_matakuliah->matakuliah->sks;
                }
                $row->kelas = trim($kelas);
                $row->sks = $sks;
                return $row;
            });

        $dataLampiran = [];
        $dataLampiranTotal = [];
        foreach ($data as $key => $value) {
            $dataLampiran[$value->dosen_nama][] = $value;
            if (isset($dataLampiranTotal[$value->dosen_nama])) {
                $dataLampiranTotal[$value->dosen_nama] += $value->sks;
            } else {
                $dataLampiranTotal[$value->dosen_nama] = $value->sks;
            }
        }
        $jk = Ref::find($request->jk_id);
        $jk = $jk ? $jk->kode : 'PutraPutri';
        $nama = "Lampiran SK $aliasProdi $aliasThAkademik -$jk.xls";
        $tentang = "PENGAMPU MATA KULIAH SEMESTER " . strtoupper($thAkademikSiakad->semester) . "$thAkademikSiakad->nama";
        $nomerSk = $request->nomer_sk;
        $tanggal = $request->tanggal;
        return view("$this->folder/excel", compact('nama', 'dataLampiran', 'dataLampiranTotal', 'tentang', 'nomerSk', 'tanggal'));
    }
}