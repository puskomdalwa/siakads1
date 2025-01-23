<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Auth;
use Alert;
use PDF;
use App\PT;
use App\KRS;
use App\Ref;
use App\User;
use App\Prodi;
use App\Pejabat;
use App\KRSDetail;
use App\ThAkademik;

use App\JadwalKuliah;
use App\Mahasiswa;

class LapTranskripController extends Controller
{

	private $title = 'Laporan Transkrip';
	private $redirect = 'laptranskrip';
	private $folder = 'laptranskrip';
	private $class = 'laptranskrip';

	private $rules = [
		'th_akademik_id' => 'required',
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
		$jekel_id = Ref::where('table', 'JenisKelamin')->first()->id;

		$list_kelas = Ref::where('table', 'Kelas')->get();
		$list_jekel = Ref::where('table', 'JenisKelamin')->get();

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
				'list_jekel',
				'jekel_id',
				'list_kelas',
				'kelas_id',
				'level',
				'list_prodi',
				'prodi_id',
				'list_thangkatan',
				'th_angkatan_id'
			)
		);
	}

	public function store(Request $request)
	{

		$kelas_id = $request->kelas_id;

		$th_akademik_id = $request->th_akademik_id;
		$thangkatan = ThAkademik::where('id', $request->th_angkatan_id)->first();
		$angkatan = substr($thangkatan->kode, 0, 4);

		$prodi = @strtolower(Auth::user()->prodi->id);

		if ($prodi) {
			$prodi_id = $prodi;
		} else {
			$prodi_id = $request->prodi_id;
		}

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
			compact('data', 'prodi')
		);
	}

	public function cetak(Request $request)
	{

		$kelas_id = $request->kelas_id;
		$prodi_id = $request->prodi_id;

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

		$data = KRS::where('th_akademik_id', $th_akademik_id)
			->when($prodi_id, function ($query) use ($prodi_id) {
				return $query->where('prodi_id', $prodi_id); })
			->when($kelas_id, function ($query) use ($kelas_id) {
				return $query->where('kelas_id', $kelas_id); })
			->orderBy('nim', 'asc')
			->with(['th_akademik', 'prodi', 'kelas', 'mahasiswa'])
			->get();

		$class = 'text-left';

		$prodi = @Prodi::where('id', $prodi_id)->first();


		$pdf = PDF::loadView(
			$this->folder . '.cetak',
			compact('data', 'th_akademik', 'thangkatan', 'pt', 'prodi', 'class')
		);

		return $pdf->setPaper('a4', 'portrait')
			->stream('Laporan Transkrip_' . $th_akademik->nama . '.pdf');
	}

	public function cetakTranskrip($krs_id)
	{

		$krs = KRS::where('id', $krs_id)
			->with('mahasiswa', 'th_akademik', 'prodi', 'kelas')
			->first();

		$th_akademik = ThAkademik::where('id', $krs->th_akademik_id)->first();

		$pt = PT::first();
		$prodi = @Prodi::where('id', Auth::user()->prodi_id)->first();

		$data = KRSDetail::where('nim', $krs->nim)
			->with('jadwal_kuliah')
			->get();

		$biro_id = env('BIRO_AKADEMIK_ID');
		$biro = Pejabat::where('jabatan_id', $biro_id)->first();

		$class = 'text-center';

		$pdf = PDF::loadView(
			$this->folder . '.cetakTranskrip',
			compact('data', 'pt', 'th_akademik', 'krs', 'biro', 'prodi', 'class')
		);

		return $pdf->setPaper('a4', 'portrait')
			->stream('Transkrip_' . $krs->nim . '.pdf');

	}

	public function cetakTranskrip_XXX(Request $request)
	{
		$krs = KRS::where('id', $request->krs_id)
			->with('mahasiswa', 'th_akademik', 'prodi', 'kelas')
			->first();

		$th_akademik = ThAkademik::where('id', $krs->th_akademik_id)->first();

		$pt = PT::first();
		$nim = Auth::user()->username;

		$prodi = @Prodi::where('id', Auth::user()->prodi_id)->first();

		$data = KRSDetail::where('nim', $krs->nim)
			->with('jadwal_kuliah')
			->get();

		$biro_id = env('BIRO_AKADEMIK_ID');
		$biro = Pejabat::where('jabatan_id', $biro_id)->first();

		$class = 'text-center';

		$pdf = PDF::loadView(
			$this->folder . '.cetakTranskrip',
			compact('data', 'pt', 'th_akademik', 'krs', 'biro', 'prodi', 'class')
		);

		return $pdf->setPaper('a4', 'portrait')
			->stream('Transkrip_' . $th_akademik->kode . ' ' . $krs->nim . '.pdf');
	}
}