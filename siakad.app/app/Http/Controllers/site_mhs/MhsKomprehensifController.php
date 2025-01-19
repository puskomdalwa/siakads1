<?php
namespace App\Http\Controllers\site_mhs;

use App\KompreCetakDetail;
use PDF;
use Auth;
use App\PT;
use App\Prodi;
use App\Pejabat;
use App\KRSDetail;
use App\Mahasiswa;
use App\ThAkademik;
use App\KompreCetak;
use App\KompreNilai;
use App\KomponenNilai;
use App\KompreMahasiswa;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MhsKomprehensifController extends Controller
{
    private $title = 'Ujian Komprehensif';
    private $redirect = 'mhs_komprehensif';
    private $folder = 'site_mhs.mhs_komprehensif';
    private $class = 'mhs_informasi';
    public function index()
    {
        $nim = Auth::user()->username;
        $mhs_aktif = Mahasiswa::Aktif($nim)->first();

        $title = $this->title;
        $folder = $this->folder;
        $redirect = $this->redirect;
        if ($mhs_aktif) {
            return view($folder . '.index', compact('title', 'redirect'));
        } else {
            return redirect('home');
        }
    }

    public function getData(Request $request)
    {
        $data = Mahasiswa::where('nim', Auth::user()->username)->first();

        $row = KompreNilai::where('mahasiswa_id', $data->id)->select('*');
        return Datatables::of($row)
            ->addColumn('dosen', function ($row) {
                return $row->kompreDosen->dosen->nama;
            })
            ->rawColumns(['action', 'dosen'])
            ->make(true);
    }

    public function cetak(Request $request)
    {
        try {
            $request->validate([
                '_token' => 'required'
            ]);

            $th_akademik = ThAkademik::aktif()->first();

            $pt = PT::first();
            $nim = Auth::user()->username;
            $mahasiswa = Auth::user()->mahasiswa;
            $prodi = $mahasiswa->prodi;
            $biro_id = env('BIRO_AKADEMIK_ID');
            $biro = Pejabat::where('jabatan_id', $biro_id)->first();

            $nSmt = $th_akademik->semester == "Ganjil" ? 1 : 2;
            $smt = $nSmt + 2 * (
                explode('/', $th_akademik->nama)[0] - explode('/', $mahasiswa->th_akademik->nama)[0]
            );

            $class = "text-center";
            $nilaiKompre = KompreNilai::where('mahasiswa_id', $mahasiswa->id)->get();

            $khsKompre = KRSDetail::join('trans_jadwal_kuliah', 'trans_jadwal_kuliah.id', '=', 'trans_krs_detail.jadwal_kuliah_id')
                ->join('trans_kurikulum_matakuliah', 'trans_kurikulum_matakuliah.id', '=', 'trans_jadwal_kuliah.kurikulum_matakuliah_id')
                ->join('mst_matakuliah', 'mst_matakuliah.id', '=', 'trans_kurikulum_matakuliah.matakuliah_id')
                ->where('mst_matakuliah.nama', 'LIKE', '%kompre%')
                ->where('mst_matakuliah.sks', 0)
                ->where('trans_krs_detail.nim', $mahasiswa->nim)
                ->select('trans_krs_detail.*', 'mst_matakuliah.nama as matakuliah_nama')
                ->first();

            $pdf = PDF::loadView(
                $this->folder . '.cetakKomprehensif',
                compact('pt', 'th_akademik', 'prodi', 'mahasiswa', 'class', 'smt', 'nilaiKompre', 'khsKompre', 'biro', 'pt')
            );

            return $pdf->setPaper('a4', 'portrait')
                ->stream('Cetak_Kompre_' . $th_akademik->kode . ' ' . $mahasiswa->nim . '.pdf');

        } catch (\Throwable $th) {
            //throw $th;
            alert()->error('Gagal Cetak ' . $th->getMessage(), $this->title);
            return back();
        }
    }
}