<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Yajra\Datatables\Datatables;
use Alert;
use App\ThAkademik;
use App\Prodi;
use App\Ref;
use App\User;
use App\PT;
use PDF;
use App\KRSDetail;
use App\JadwalKuliah;
use App\KRSDetailNilai;
use App\KomponenNilai;

class LapNilaiController extends Controller
{
	private $title = 'Cetak Nilai Mahasiswa';
	private $redirect = 'lapnilai';
	private $folder = 'lapnilai';
	private $class = 'lapnilai';

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
			->with(['th_akademik', 'prodi', 'kelas', 'kelompok', 'kurikulum_matakuliah', 'dosen', 'ruang_kelas', 'jamkul'])->get();

		$redirect = $this->redirect;

		return view(
			$this->folder . '.data',
			compact('data', 'redirect')
		);
	}

	public function cetakAll(Request $request)
	{
		$th_akademik_id = $request->th_akademik_id;
		$prodi_id = $request->prodi_id;
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
			->with(['th_akademik', 'prodi', 'kelas', 'kelompok', 'kurikulum_matakuliah', 'dosen', 'ruang_kelas'])->get();

		$pdf = PDF::loadView(
			$this->folder . '.cetak',
			compact('data', 'th_akademik', 'pt')
		);

		return $pdf->setPaper('a4', 'landscape')->stream('Laporan KRS ' . $th_akademik->kode . '.pdf');
	}

	public function cetak($id)
	{
		$jadwal = JadwalKuliah::where('id', $id)
			->with('kurikulum_matakuliah', 'th_akademik')->first();

		$th_akademik = ThAkademik::where('id', $jadwal->th_akademik_id)->first();
		$pt = PT::first();
		$prodi = @Prodi::where('id', Auth::user()->prodi_id)->first();

		$data = KRSDetail::select('trans_krs_detail.*')
			->join('mst_mhs', 'mst_mhs.nim', '=', 'trans_krs_detail.nim')
			->where('jadwal_kuliah_id', $id)
			->orderBy('mst_mhs.nama', 'asc')
			->with(['mahasiswa'])->get();

		$komponen_nilai = KomponenNilai::get();
		$class = 'text-center';

		$pdf = PDF::loadView(
			$this->folder . '.cetak',
			compact('data', 'th_akademik', 'pt', 'jadwal', 'komponen_nilai', 'prodi', 'class')
		);

		return $pdf->setPaper('a4', 'potrait')
			->stream('NILAI MAHASISWA ' . $jadwal->dosen->nama . ' ' .
				$jadwal->kurikulum_matakuliah->matakuliah->nama . '.pdf');
	}
}