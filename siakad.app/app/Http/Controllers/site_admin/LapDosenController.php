<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Dosen;
use Auth;
use Yajra\Datatables\Datatables;
use Alert;
use App\Prodi;
use App\Ref;
use App\User;
use App\PT;
use PDF;

class LapDosenController extends Controller
{

	private $title = 'Laporan Dosen';
	private $redirect = 'lapdosen';
	private $folder = 'lapdosen';
	private $class = 'lapdosen';

	private $rules = [
		'prodi_id' => 'required',
	];

	public function index()
	{
		$title = $this->title;
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

		$list_status = Ref::where('table', 'StatusDosen')->get();

		return view(
			$folder . '.index',
			compact('title', 'redirect', 'folder', 'list_prodi', 'list_status', 'level', 'prodi_id')
		);
	}

	public function store(Request $request)
	{
		$prodi_id = $request->prodi_id;
		$status_id = $request->status_id;

		$list_prodi = Prodi::when($prodi_id, function ($query) use ($prodi_id) {
			return $query->where('id', $prodi_id);
		})
			->orderBy('kode', 'ASC')
			->get();

		foreach ($list_prodi as $prodi) {
			$rows[$prodi->id] = Dosen::where('prodi_id', $prodi->id)

				->when($status_id, function ($query) use ($status_id) {
					return $query->where('dosen_status_id', $status_id);
				})
				->orderBy('nama', 'asc')
				->with(['prodi', 'jk', 'status'])
				->get();
		}

		$data = array(
			'list_prodi' => $list_prodi,
			'rows' => $rows
		);

		return view(
			$this->folder . '.data2',
			compact('data')
		);
	}

	public function cetak(Request $request)
	{
		$prodi_id = $request->prodi_id;
		$status_id = $request->status_id;

		$prodi = @Prodi::where('id', Auth::user()->prodi->id)->first();
		$lprodi = @Prodi::where('id', $prodi_id)->first();
		$nmprodi = $lprodi->nama . ' (' . $lprodi->jenjang . ')';

		$pt = PT::first();

		$data = Dosen::when($prodi_id, function ($query) use ($prodi_id) {
			return $query->where('prodi_id', $prodi_id);
		})
			->when($status_id, function ($query) use ($status_id) {
				return $query->where('dosen_status_id', $status_id);
			})
			->orderBy('nama', 'asc')
			->with(['prodi', 'jk', 'status'])
			->get();

		$class = 'text-left';

		$pdf = PDF::loadView(
			$this->folder . '.cetak',
			compact('data', 'pt', 'prodi', 'class', 'nmprodi')
		);

		return $pdf->setPaper('a4', 'landscape')->stream('Laporan Dosen.pdf');
	}
}