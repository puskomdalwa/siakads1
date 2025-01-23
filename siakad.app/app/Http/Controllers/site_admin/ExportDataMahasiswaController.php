<?php
namespace App\Http\Controllers\site_admin;

use App\Http\Controllers\Controller;
use App\Http\Services\ServiceSiswa;
use App\Mahasiswa;
use App\Prodi;
use App\ThAkademik;
use Auth;
use Illuminate\Http\Request;

class ExportDataMahasiswaController extends Controller
{
    private $title = 'Export Data Mahasiswa';
    private $redirect = 'exportdatamahasiswa';
    private $folder = 'exportdatamahasiswa';
    private $class = 'exportdatamahasiswa';
    private $rules = [
        'prodi_id' => 'required',
    ];

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

        $list_thakademik = ThAkademik::where('semester', 'Ganjil')->orderBy('kode', 'DESC')->get();

        return view($folder . '.index', compact('title', 'redirect', 'folder', 'list_prodi', 'list_thakademik'));
    }

    public function export(Request $request)
    {
        $where = null;
        if ($request->prodi_id != null) {
            $mahasiswa = Mahasiswa::where([
                ['th_akademik_id', $request->th_akademik_id],
                ['prodi_id', $request->prodi_id],
            ])->get();

            // dataProdi untuk PMB
            $dataProdi = [
                1 => null,
                3 => 5,
                4 => 6,
                5 => 7,
                6 => 4,
                7 => 8,
                8 => 1,
                9 => 3,
                10 => 2,
                11 => 9,
                12 => 10,
                13 => 11,
                14 => null,
                15 => 2,
            ];
            $thAkademikSiakad = ThAkademik::find($request->th_akademik_id);
            $where[] = ['siswa.prodi_id', $dataProdi[$request->prodi_id]];
            $where[] = ['siswa.tahun_pelajaran', $thAkademikSiakad->nama];
        } else {
            $mahasiswa = Mahasiswa::where([
                ['th_akademik_id', $request->th_akademik_id],
            ])->get();

            $thAkademikSiakad = ThAkademik::find($request->th_akademik_id);
            $where[] = ['siswa.tahun_pelajaran', $thAkademikSiakad->nama];
        }

        $pmb = ServiceSiswa::all(null, null, null, null, null, $where);
        $dataPmb = [];
        if ($pmb->data) {
            foreach ($pmb->data as $key => $value) {
                $dataPmb[strtolower($value->nama)] = $value;
            }
        }

        foreach ($mahasiswa as $key => $value) {
            $nama = strtolower($value->nama);
            if ($value->nik == null) {
                if (isset($dataPmb[$nama])) {
                    $value->nik = $dataPmb[$nama]->nik;
                }
            }
            if ($value->nisn == null) {
                if (isset($dataPmb[$nama])) {
                    $value->nisn = $dataPmb[$nama]->nisn;
                }
            }
            if (isset($dataPmb[$nama])) {
                $value->kelurahan = $dataPmb[$nama]->desa;
                $value->kecamatan = $dataPmb[$nama]->kecamatan;
                $value->propinsi = $dataPmb[$nama]->propinsi;
                $value->kodepos = $dataPmb[$nama]->kodepos;
                $value->kota = $dataPmb[$nama]->kota;
            }
        }
        $prodi = Prodi::find($request->prodi_id);
        $aliasProdi = $prodi ? " - $prodi->alias" : "";
        $nama = "Export Mahasiswa" . $aliasProdi . " - $thAkademikSiakad->nama" . ".xls";
        return view('exportdatamahasiswa.excel', compact('mahasiswa', 'nama'));
    }
}
