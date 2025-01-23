<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mahasiswa;
use App\KRS;
use App\KRSDetail;
use Auth;
use Yajra\Datatables\Datatables;
use Alert;
use App\ThAkademik;
use App\Prodi;
use App\Ref;
use App\User;
use App\PT;
use PDF;
use App\Exports\KRSExport;
use Maatwebsite\Excel\Facades\Excel;

class LapRekapNilaiController extends Controller
{

	private $title = 'Laporan Rekap Nilai';
	private $redirect = 'laprekapnilai';
	private $folder = 'laprekapnilai';
	private $class = 'laprekapnilai';

	private $rules = [
		'th_akademik_id' => 'required',
	];

	public function index()
	{
		$th_akademik = ThAkademik::Aktif()->first();
		$th_akademik_aktif = $th_akademik->kode;

		$title = $this->title . ' Tahun Akademik ' . $th_akademik_aktif;
		$redirect = $this->redirect;
		$folder = $this->folder;
		$level = strtolower(Auth::user()->level->level);

		$prodi_id = @strtolower(Auth::user()->prodi->id);

		if ($prodi_id) {
			$list_prodi = Prodi::where('id', $prodi_id)->get();
		} else {
			// $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
			$list_prodi = Prodi::orderBy('kode', 'ASC')->get();
		}

		$list_thakademik = ThAkademik::where('semester', 'Ganjil')->orderBy('kode', 'DESC')->get();

		return view(
			$folder . '.index',
			compact('title', 'redirect', 'folder', 'list_prodi', 'list_thakademik', 'level', 'prodi_id')
		);
	}

	public function store(Request $request)
	{
		$th_akademik_id = $request->th_akademik_id;
		$th_akademik_angkatan = ThAkademik::where('id', $th_akademik_id)->first()->kode;

		$prodi = @strtolower(Auth::user()->prodi->id);
		if ($prodi) {
			$prodi_id = $prodi;
		} else {
			$prodi_id = $request->prodi_id;
		}

		$th_akademik = ThAkademik::Aktif()->first();
		$th_akademik_aktif = $th_akademik->kode;

		$data = Mahasiswa::
			where('th_akademik_id', $th_akademik_id)
			->where('prodi_id', $prodi_id)
			->orderBy('nim', 'asc')
			->with(['th_akademik', 'prodi', 'kelas'])
			->get();

		$list_thakademik = ThAkademik::where('kode', '>=', $th_akademik_angkatan)
			->orderBy('kode', 'asc')
			->get();

		return view(
			$this->folder . '.data',
			compact('data', 'th_akademik_angkatan', 'th_akademik_aktif', 'list_thakademik')
		);
	}
}