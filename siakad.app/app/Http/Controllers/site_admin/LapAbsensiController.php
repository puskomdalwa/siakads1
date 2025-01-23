<?php
namespace App\Http\Controllers\site_admin;

use App\Http\Controllers\Controller;
use App\JadwalKuliah;
use App\KRSDetail;
use App\Prodi;
use App\PT;
use App\Ref;
use App\ThAkademik;
use App\User;
use Auth;
use Illuminate\Http\Request;
use PDF;

class LapAbsensiController extends Controller
{
    private $title = 'Cetak Absensi';
    private $redirect = 'lapabsensi';
    private $folder = 'lapabsensi';
    private $class = 'lapabsensi';
    private $rules = ['th_akademik_id' => 'required'];

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

        $data = JadwalKuliah::where('th_akademik_id', $th_akademik_id)
            ->where('prodi_id', $prodi_id)
            ->where('kelas_id', $kelas_id)
            ->when($kelompok_id, function ($query) use ($kelompok_id) {
                return $query->where('kelompok_id', $kelompok_id);
            })

            ->orderBy('smt', 'asc')
            ->with(['th_akademik', 'prodi', 'kelas', 'kelompok', 'kurikulum_matakuliah', 'dosen', 'ruang_kelas'])
            ->get();

        $redirect = $this->redirect;
        return view(
            $this->folder . '.data',
            compact('data', 'redirect')
        );
    }

    public function cetakAll(Request $request)
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

        $th_akademik = ThAkademik::where('id', $th_akademik_id)->first();
        $pt = PT::first();

        $data = JadwalKuliah::where('th_akademik_id', $th_akademik_id)
            ->where('prodi_id', $prodi_id)
            ->where('kelas_id', $kelas_id)
            ->when($kelompok_id, function ($query) use ($kelompok_id) {
                return $query->where('kelompok_id', $kelompok_id);
            })
            ->orderBy('smt', 'asc')
            ->with(['th_akademik', 'prodi', 'kelas', 'kelompok', 'kurikulum_matakuliah', 'dosen', 'ruang_kelas'])
            ->get();

        $pdf = PDF::loadView($this->folder . '.cetak', compact('data', 'th_akademik', 'pt'));

        return $pdf->setPaper('a4', 'landscape')->stream('Laporan KRS ' . $th_akademik->kode . '.pdf');
    }

    public function cetak($id)
    {
        $jadwal = JadwalKuliah::where('id', $id)
            ->with('kurikulum_matakuliah', 'th_akademik')->first();

        $pt = PT::first();
        $prodi = @Prodi::where('id', Auth::user()->prodi_id)->first();
        $th_akademik = ThAkademik::where('id', $jadwal->th_akademik_id)->first();

        $data = KRSDetail::select('trans_krs_detail.*')
            ->join('mst_mhs', 'mst_mhs.nim', '=', 'trans_krs_detail.nim')
            ->where('jadwal_kuliah_id', $id)
            ->orderBy('mst_mhs.nama', 'asc')
            ->with(['mahasiswa'])->get();

        $class = 'text-left';
        $pdf = PDF::loadView(
            $this->folder . '.cetak',
            compact('data', 'th_akademik', 'pt', 'jadwal', 'class', 'prodi')
        );

        return $pdf->setPaper('a4', 'landscape')->stream('ABSENSI MAHASISWA ' .
            $jadwal->dosen->nama . ' ' . $jadwal->kurikulum_matakuliah->matakuliah->nama . '.pdf');
    }

    public function cetakLengkap($id)
    {
        $jadwal = JadwalKuliah::where('id', $id)
            ->with('kurikulum_matakuliah', 'th_akademik')->first();

        $pt = PT::first();
        $prodi = @Prodi::where('id', Auth::user()->prodi_id)->first();
        $th_akademik = ThAkademik::where('id', $jadwal->th_akademik_id)->first();

        $data = KRSDetail::select('trans_krs_detail.*')
            ->join('mst_mhs', 'mst_mhs.nim', '=', 'trans_krs_detail.nim')
            ->where('jadwal_kuliah_id', $id)
            ->orderBy('mst_mhs.nama', 'asc')
            ->with(['mahasiswa'])->get();

        $class = 'text-left';
        $pdf = PDF::loadView(
            $this->folder . '.cetak-lengkap',
            compact('data', 'th_akademik', 'pt', 'jadwal', 'class', 'prodi')
        );

        return $pdf->setPaper('a4', 'landscape')->stream('ABSENSI MAHASISWA ' .
            $jadwal->dosen->nama . ' ' . $jadwal->kurikulum_matakuliah->matakuliah->nama . '.pdf');
    }

    public function cetakUas($id)
    {
        $jadwal = JadwalKuliah::where('id', $id)
            ->with('kurikulum_matakuliah', 'th_akademik')->first();

        $pt = PT::first();
        $prodi = @Prodi::where('id', Auth::user()->prodi_id)->first();
        $th_akademik = ThAkademik::where('id', $jadwal->th_akademik_id)->first();

        $getThAkademikAktif = ThAkademik::aktif()->first();
        $thAkademikAktif = substr($getThAkademikAktif->kode, 0, -1);

        $data = KRSDetail::select('trans_krs_detail.*')
            ->join('mst_mhs', 'mst_mhs.nim', '=', 'trans_krs_detail.nim')
            ->join('mst_th_akademik', 'mst_th_akademik.id', '=', 'mst_mhs.th_akademik_id')
            ->where('jadwal_kuliah_id', $id)
            ->orderBy('mst_mhs.nama', 'asc')
            ->addSelect([
                // 'smt_mhs' => \DB::raw("CAST((({$thAkademikAktif} - LEFT(mst_th_akademik.kode, LENGTH(mst_th_akademik.kode) - 1)) * 2) + 1 AS UNSIGNED) AS smt_mhs"),
                \DB::raw("(EXISTS (
                                SELECT 1
                                FROM keuangan_pembayaran
                                JOIN keuangan_tagihan
                                ON keuangan_tagihan.id = keuangan_pembayaran.tagihan_id
                                WHERE keuangan_pembayaran.nim = mst_mhs.nim
                                AND keuangan_tagihan.nama = CONCAT('UAS Semester ', CAST((({$thAkademikAktif} - LEFT(mst_th_akademik.kode, LENGTH(mst_th_akademik.kode) - 1)) * 2) + 1 AS UNSIGNED))
                            )) AS uas"),
                \DB::raw("(EXISTS (
                                SELECT 1
                                FROM keuangan_dispensasi_tagihan
                                JOIN keuangan_tagihan
                                ON keuangan_tagihan.id = keuangan_dispensasi_tagihan.jenis_tagihan_id
                                WHERE keuangan_dispensasi_tagihan.nim = mst_mhs.nim
                                AND keuangan_tagihan.nama = CONCAT('UAS Semester ', CAST((({$thAkademikAktif} - LEFT(mst_th_akademik.kode, LENGTH(mst_th_akademik.kode) - 1)) * 2) + 1 AS UNSIGNED))
                            )) AS uas_dispensasi_tagihan"),
                \DB::raw("(EXISTS (
                                SELECT 1
                                FROM keuangan_dispensasi_uas
                                WHERE keuangan_dispensasi_uas.nim = mst_mhs.nim
                                AND keuangan_dispensasi_uas.th_akademik_id = ".$getThAkademikAktif->id."
                            )) AS uas_dispensasi"),
            ])
            ->havingRaw('uas = 1 OR uas_dispensasi = 1 OR uas_dispensasi_tagihan = 1')
            ->get();

        $class = 'text-left';
        $pdf = PDF::loadView(
            $this->folder . '.cetak-uas',
            compact('data', 'th_akademik', 'pt', 'jadwal', 'class', 'prodi')
        );

        return $pdf->setPaper('a4', 'potrait')->stream('ABSENSI MAHASISWA UAS' .
            $jadwal->dosen->nama . ' ' . $jadwal->kurikulum_matakuliah->matakuliah->nama . '.pdf');
    }
}
