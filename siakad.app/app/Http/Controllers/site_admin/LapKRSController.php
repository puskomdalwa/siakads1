<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use Auth;
use Alert;
use PDF;
use App\PT;
use App\KRS;
use App\Ref;
use App\User;
use App\Prodi;
use App\Pejabat;
use App\Mahasiswa;
use App\JadwalKuliah;

use App\KRSDetail;
use App\ThAkademik;

use App\Exports\KRSExport;
use Maatwebsite\Excel\Facades\Excel;

class LapKRSController extends Controller
{

	private $title = 'Laporan KRS';
	private $redirect = 'lapkrs';
	private $folder = 'lapkrs';
	private $class = 'lapkrs';

	private $rules = [
		'th_akademik_id' => 'required',
		'prodi_id' => 'required',
	];

	public function index()
	{
		$title = $this->title;
		$folder = $this->folder;
		$redirect = $this->redirect;

		$level = strtolower(Auth::user()->level->level);

		$th_akademik_id = ThAkademik::Aktif()->first()->id;
		$th_angkatan_id = ThAkademik::Aktif()->first()->id;

		$prodi_id = @strtolower(Auth::user()->prodi->id);

		if ($prodi_id) {
			$list_prodi = Prodi::where('id', $prodi_id)->get();
		} else {
			// $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
			$list_prodi = Prodi::orderBy('kode', 'ASC')->get();
		}

		$kelas_id = Ref::where('table', 'Kelas')->first()->id;
		$list_kelas = Ref::where('table', 'Kelas')->get();

		$list_thakademik = ThAkademik::orderBy('kode', 'DESC')->get();
		$list_thangkatan = ThAkademik::where('semester', 'ganjil')
			->orderby('nama', 'DESC')->get();

		return view(
			$folder . '.index',
			compact(
				'title',
				'redirect',
				'folder',
				'list_thakademik',
				'th_akademik_id',
				'list_kelas',
				'level',
				'list_thangkatan',
				'th_angkatan_id',
				'list_prodi',
				'prodi_id'
			)
		);
	}

	public function storexxx($krs_id)
	{
		$krs = KRS::where('id', $krs_id)
			->with('mahasiswa', 'th_akademik', 'prodi', 'kelas')
			->first();

		$th_akademik = ThAkademik::where('id', $krs->th_akademik_id)->first();

		$pt = PT::first();
		$prodi = @Prodi::where('id', Auth::user()->prodi_id)->first();

		$data = KRSDetail::where('krs_id', $krs->id)
			->with('jadwal_kuliah')
			->get();

		$biro_id = env('BIRO_AKADEMIK_ID');
		$biro = Pejabat::where('jabatan_id', $biro_id)->first();

		$class = 'text-center';

		$pdf = PDF::loadView(
			$this->folder . '.cetakKRS',
			compact('data', 'pt', 'th_akademik', 'krs', 'biro', 'prodi', 'class')
		);

		return $pdf->setPaper('a4', 'portrait')
			->stream('KHS ' . $th_akademik->kode . ' ' . $krs->nim . '.pdf');
	}

	public function store(Request $request)
	{

		$th_akademik_id = $request->th_akademik_id;

		$thangkatan = ThAkademik::where('id', $request->th_angkatan_id)->first();
		$angkatan = substr($thangkatan->kode, 0, 4);

		$prodi = @strtolower(Auth::user()->prodi->id);

		if ($prodi) {
			$prodi_id = $prodi;
		} else {
			$prodi_id = $request->prodi_id;
		}

		$kelas_id = $request->kelas_id;

		$data = KRS::where('th_akademik_id', $th_akademik_id)
			->where('nim', 'like', $angkatan . '%')
			->when($prodi_id, function ($query) use ($prodi_id) {
				return $query->where('prodi_id', $prodi_id); })
			->when($kelas_id, function ($query) use ($kelas_id) {
				return $query->where('kelas_id', $kelas_id); })
			->orderBy('nim', 'asc')
			->with(['th_akademik', 'prodi', 'kelas', 'mahasiswa'])
			->get();

		return view(
			$this->folder . '.data',
			compact('data')
		);
	}

	public function cetak(Request $request)
	{

		$th_akademik_id = $request->th_akademik_id;

		$thangkatan = ThAkademik::where('id', $request->th_angkatan_id)->first();
		$angkatan = substr($thangkatan->kode, 0, 4);

		$prodi = @strtolower(Auth::user()->prodi->id);

		if ($prodi) {
			$prodi_id = $prodi;
		} else {
			$prodi_id = $request->prodi_id;
		}

		$kelas_id = $request->kelas_id;
		$th_akademik = ThAkademik::where('id', $th_akademik_id)->first();

		$pt = PT::first();
		$prodi = Prodi::where('id', $prodi_id)->first();

		$data = KRS::where('th_akademik_id', $th_akademik_id)
			->where('nim', 'like', $angkatan . '%')
			->when($prodi_id, function ($query) use ($prodi_id) {
				return $query->where('prodi_id', $prodi_id); })
			->when($kelas_id, function ($query) use ($kelas_id) {
				return $query->where('kelas_id', $kelas_id); })
			->orderBy('nim', 'asc')
			->with(['th_akademik', 'prodi', 'kelas', 'mahasiswa'])
			->get();

		if ($request->cetak == 'pdf') {
			$class = 'text-left';

			$pdf = PDF::loadView(
				$this->folder . '.cetak',
				compact('data', 'th_akademik', 'pt', 'class', 'prodi')
			);

			return $pdf->setPaper('a4', 'landscape')
				->stream('Laporan KRS_' . $th_akademik->kode . '.pdf');
		} else {
			return (new KRSExport)
				->ThAkademikId($th_akademik->id)
				->download('Laporan KRS_' . $th_akademik->kode . '.xlsx');
		}
	}

	public function cetakKRS($krs_id)
	{
		$krs = KRS::where('id', $krs_id)
			->with('mahasiswa', 'th_akademik', 'prodi', 'kelas')
			->first();

		$th_akademik = ThAkademik::where('id', $krs->th_akademik_id)->first();

		$pt = PT::first();
		$prodi = @Prodi::where('id', $krs->prodi_id)->first();

		$data = KRSDetail::where('krs_id', $krs->id)
			->where('th_akademik_id', $th_akademik->id)
			->with('jadwal_kuliah')
			->get();

		$biro_id = env('BIRO_AKADEMIK_ID');
		$biro = Pejabat::where('jabatan_id', $biro_id)->first();

		$class = 'text-center';

		$pdf = PDF::loadView(
			$this->folder . '.cetakKRS',
			compact('data', 'pt', 'th_akademik', 'krs', 'biro', 'prodi', 'class')
		);

		return $pdf->setPaper('a4', 'portrait')
			->stream('KRS ' . $th_akademik->kode . ' ' . $krs->nim . '.pdf');
	}

	public function krskosong(Request $request)
	{
		$th_akademik = ThAkademik::Aktif()->first();
		$th_akademik_id = $th_akademik->id;
		$th_akademik_aktif = $th_akademik->kode;


		$th_angkatan_id = $request->th_angkatan_id;
		$thangkatan = ThAkademik::where('id', $request->th_angkatan_id)->first();
		$angkatan = substr($thangkatan->kode, 0, 4);

		$prodi = @strtolower(Auth::user()->prodi->id);

		if ($prodi) {
			$prodi_id = $prodi;
		} else {
			$prodi_id = $request->prodi_id;
		}

		$data = Mahasiswa::select('mst_mhs.*', 'trans_krs.tanggal')
			->where('status_id', 18)
			->when($prodi_id, function ($query) use ($prodi_id) {
				return $query->where('mst_mhs.prodi_id', $prodi_id);
			})
			->leftjoin('trans_krs', 'trans_krs.nim', '=', 'mst_mhs.nim')
			->whereNull('trans_krs.tanggal')
			->with('th_akademik', 'jk', 'prodi', 'kelas', 'kelompok')
			->orderBy('nim', 'asc')
			->get();

		return view(
			$this->folder . '.datakrskosong',
			compact('data', 'th_akademik_aktif')
		);
	}
}