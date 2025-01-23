<?php
namespace App\Http\Controllers\site_admin;

use Auth;
use Alert;
use App\PT;
use App\Ref;
use App\Prodi;
use App\Pejabat;
use App\KRSDetail;
use App\Mahasiswa;
use App\ThAkademik;
use App\KompreDosen;
use App\KompreNilai;
use App\KompreNilaiLog;
use PDF;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Http\Services\ServiceKompre;

class KomprehensifMahasiswaController extends Controller
{
    private $title = 'Komprehensif Mahasiswa';
    private $redirect = 'kompre_mahasiswa';
    private $folder = 'komprehensif/mahasiswa';
    private $class = 'mahasiswa';


    public function index()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        // $list_prodi = Prodi::get();
        $prodi_id = @strtolower(Auth::user()->prodi->id);

        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            // $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $list_status = Ref::where('table', 'StatusMhs')->get();
        $list_kelas = Ref::where('table', 'Kelas')->get();

        $th_akademik = ThAkademik::Aktif()->first();

        $list_thakademik = ThAkademik::where('semester', 'Ganjil')
            ->orderBy('kode', 'Desc')
            ->get();

        return view(
            $folder . '.index',
            compact(
                'title',
                'redirect',
                'folder',
                'list_prodi',
                'list_status',
                'th_akademik',
                'list_thakademik',
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
        $status_id = $request->status_id;
        $txt_cari = $request->txt_cari;

        $th_akademik_id = $request->th_akademik_id;

        $row = Mahasiswa::join('ref as ref_jk', 'ref_jk.id', '=', 'mst_mhs.jk_id')
            ->join('mst_prodi as prod', 'prod.id', '=', 'mst_mhs.prodi_id')
            ->join('ref as ref_kelas', 'ref_kelas.id', '=', 'mst_mhs.kelas_id')
            ->leftJoin('trans_perwalian_detail as tpd', 'tpd.nim', '=', 'mst_mhs.nim')
            ->leftJoin('trans_perwalian as tp', 'tp.id', '=', 'tpd.perwalian_id')
            ->leftJoin('ref as ref_kelompok', 'ref_kelompok.id', '=', 'tp.kelompok_id')
            ->join('ref as ref_status', 'ref_status.id', '=', 'mst_mhs.status_id')
            ->select('mst_mhs.*', 'ref_jk.kode as mhs_jk', 'prod.alias as mhs_prodi', 'ref_kelas.nama as mhs_kelas', 'ref_kelompok.kode as mhs_kelompok', 'ref_status.nama as mhs_status');

        return Datatables::of($row)
            ->filter(function ($query) use ($search, $th_akademik_id, $prodi_id, $kelas_id, $status_id, $txt_cari) {
                $query->where('mst_mhs.th_akademik_id', $th_akademik_id)
                    ->when($prodi_id, function ($query) use ($prodi_id) {
                        return $query->where('mst_mhs.prodi_id', $prodi_id);
                    })
                    ->when($kelas_id, function ($query) use ($kelas_id) {
                        return $query->where('mst_mhs.kelas_id', $kelas_id);
                    })
                    ->when($status_id, function ($query) use ($status_id) {
                        return $query->where('mst_mhs.status_id', $status_id);
                    })
                    ->when($txt_cari, function ($query) use ($txt_cari) {
                        return $query->where('mst_mhs.nim', 'like', '%' . $txt_cari . '%')->orWhere('mst_mhs.nama', 'like', '%' . $txt_cari . '%');
                    });
                $query->where(function ($query) use ($search) {
                    $query->orWhere('mst_mhs.nim', 'LIKE', "%$search%")
                        ->orWhere('mst_mhs.nama', 'LIKE', "%$search%")
                        ->orWhere('ref_jk.nama', 'LIKE', "%$search%")
                        ->orWhere('prod.alias', 'LIKE', "%$search%")
                        ->orWhere('ref_kelas.nama', 'LIKE', "%$search%")
                        ->orWhere('ref_kelompok.kode', 'LIKE', "%$search%")
                        ->orWhere('ref_status.nama', 'LIKE', "%$search%");
                });
            })
            ->editColumn('mhs_status', function ($row) {
                return strtoupper($row->mhs_status) == 'AKTIF' ?
                    '<span class="badge badge-success">' . $row->mhs_status . '</span>' :
                    '<span class="badge badge-danger">' . $row->mhs_status . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Klik <span class="caret"></span></button>
                <ul class="dropdown-menu pull-right">
                    <li><a href="' . route('kompre_mahasiswa.detail', ['mahasiswaId' => $row->id]) . '">Isi Nilai</a></li>
                    <li class="divider"></li>
                    <li><a href="' . route('kompre_mahasiswa.cetak', ['mahasiswaId' => $row->id]) . '">Cetak</a></li>
                </ul>
            </div>';
            })
            ->rawColumns(['action', 'mhs_status', 'nama'])->make(true);
    }

    public function cetak(Request $request, $mahasiswaId)
    {
        try {
            $th_akademik = ThAkademik::aktif()->first();

            $pt = PT::first();
            $mahasiswa = Mahasiswa::find($mahasiswaId);
            $nim = $mahasiswa->nim;
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
                'site_mhs.mhs_komprehensif.cetakKomprehensif',
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

    public function detail($mahasiswaId)
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $jumlahPenguji = 6;

        $mahasiswa = Mahasiswa::findOrFail($mahasiswaId);
        $penguji = [];

        for ($i = 1; $i <= $jumlahPenguji; $i++) {
            $kompreDosen = KompreDosen::where('penguji', $i)->where('jenis_kelamin', $mahasiswa->jk->kode)->first();
            if (!@$kompreDosen->dosen_id) {
                $penguji[$i] = [
                    "status" => false,
                    "nilai" => 0
                ];
                continue;
            }
            $kompreNilai = KompreNilai::where([
                ['mahasiswa_id', $mahasiswaId],
                ['kompre_dosen_id', $kompreDosen->id]
            ])->first();
            $penguji[$i] = [
                "status" => true,
                "nilai" => $kompreNilai ? $kompreNilai->nilai : 0,
                "dosen" => $kompreDosen->dosen->nama
            ];
        }
        return view($folder . '/detail', compact('title', 'redirect', 'folder', 'jumlahPenguji', 'mahasiswa', 'penguji'));
    }

    public function updateNilai(Request $request, $mahasiswaId)
    {
        try {
            \DB::beginTransaction();
            $mahasiswa = Mahasiswa::findOrFail($mahasiswaId);
            $jumlahDosen = 6;
            for ($i = 1; $i <= $jumlahDosen; $i++) {
                $kompreDosen = KompreDosen::where('penguji', $i)->where('jenis_kelamin', $mahasiswa->jk->kode)->first();
                if (!@$kompreDosen->dosen_id) {
                    continue;
                }

                $kompreNilai = KompreNilai::where([
                    ['mahasiswa_id', $mahasiswaId],
                    ['kompre_dosen_id', $kompreDosen->id]
                ])->first();

                if (!$kompreNilai) {
                    $kompreNilai = new KompreNilai;
                    $kompreNilai->mahasiswa_id = $mahasiswaId;
                    $kompreNilai->kompre_dosen_id = $kompreDosen->id;
                }

                $kompreNilai->nilai = $request->input('penguji_' . $i);
                $kompreNilai->save();

                $kompreNilaiLog = KompreNilaiLog::where([
                    ['dosen_id', $kompreDosen->dosen_id],
                    ['kompre_nilai_id', $kompreNilai->id]
                ])->first();


                if (!$kompreNilaiLog) {
                    KompreNilaiLog::create([
                        'dosen_id' => $kompreDosen->dosen_id,
                        'kompre_nilai_id' => $kompreNilai->id
                    ]);
                }
            }

            $thAkademik = ThAkademik::aktif()->first();
            $mahasiswa = Mahasiswa::find($mahasiswaId);
            $serviceKompre = ServiceKompre::inputNilai($mahasiswa, $thAkademik->id);
            \DB::commit();
            alert()->success('Berhasil update nilai komprehensif', $this->title);
            return back();
        } catch (\Throwable $th) {
            \DB::rollback();
            alert()->error($th->getMessage(), $this->title);
            return back();
        }
    }
}
