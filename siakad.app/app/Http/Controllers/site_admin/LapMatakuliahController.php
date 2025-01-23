<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MataKuliah;
use Auth;
use Yajra\Datatables\Datatables;
use Alert;
use App\Prodi;
use App\Ref;
use App\User;
use App\PT;
use PDF;

class LapMatakuliahController extends Controller
{
	private $title = 'Laporan Matakuliah';
	private $redirect = 'lapmatakuliah';
	private $folder = 'lapmatakuliah';
	private $class = 'lapmatakuliah';

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

		return view(
			$folder . '.index',
			compact('title', 'redirect', 'folder', 'list_prodi', 'level', 'prodi_id')
		);
	}

	public function store(Request $request)
	{
		$prodi_id = $request->prodi_id;

		$list_prodi = $data = Prodi::
			when($prodi_id, function ($query) use ($prodi_id) {
				return $query->where('id', $prodi_id);
			})
			->orderBy('kode', 'asc')
			->get();

		$list_smt = $data = MataKuliah::
			select('smt')
			->groupBy('smt')
			->orderBy('smt', 'asc')
			->get();

		foreach ($list_prodi as $prodi) {
			foreach ($list_smt as $smt) {
				$rows[$prodi->id][$smt->smt] = MataKuliah::
					where('prodi_id', $prodi->id)
					->where('smt', $smt->smt)
					->orderBy('smt', 'asc')
					->with(['prodi'])
					->get();
			}
		}

		$data = array(
			'list_prodi' => $list_prodi,
			'list_smt' => $list_smt,
			'rows' => $rows,
		);

		return view($this->folder . '.data2', compact('data'));
	}

	public function cetak(Request $request)
	{
		$prodi_id = $request->prodi_id;

		$pt = PT::first();
		$prodi = @Prodi::where('id', Auth::user()->prodi->id)->first();

		$list_prodi = $data = Prodi::when($prodi_id, function ($query) use ($prodi_id) {
			return $query->where('id', $prodi_id);
		})
			->orderBy('kode', 'asc')
			->get();

		$list_smt = $data = MataKuliah::select('smt')
			->groupBy('smt')
			->orderBy('smt', 'asc')
			->get();

		foreach ($list_prodi as $prodi) {
			foreach ($list_smt as $smt) {
				$rows[$prodi->id][$smt->smt] = MataKuliah::where('prodi_id', $prodi->id)
					->where('smt', $smt->smt)
					->orderBy('smt', 'asc')
					->with(['prodi'])
					->get();
			}
		}

		$data = array(
			'list_prodi' => $list_prodi,
			'list_smt' => $list_smt,
			'rows' => $rows,
		);

		$class = 'text-center';

		$pdf = PDF::loadView(
			$this->folder . '.cetak2',
			compact('data', 'pt', 'prodi', 'class')
		);

		return $pdf->setPaper('a4')
			->stream('Laporan Matakuliah.pdf');
	}
}