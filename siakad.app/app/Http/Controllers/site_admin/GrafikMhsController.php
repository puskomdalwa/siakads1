<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use App\ThAkademik;
use App\Mahasiswa;
use App\Prodi;

class GrafikMhsController extends Controller
{
	private $title = 'Grafik Mahasiswa';
	private $redirect = 'grafikmhs';
	private $folder = 'grafikmhs';
	private $class = 'grafikmhs';

	public function index()
	{
		$title = $this->title;
		$redirect = $this->redirect;
		$folder = $this->folder;

		$label = ThAkademik::select('kode')
			->where('semester', 'Ganjil')
			->limit(6)
			->orderBy('kode', 'desc')
			->get()
			->toArray();

		$label = array_column($label, 'kode');
		$json_label = json_encode($label, JSON_NUMERIC_CHECK);

		$datasets = collect([]);

		$list_prodi = Prodi::get();

		foreach ($list_prodi as $prodi) {
			$datasets->push([
				'label' => $prodi->nama,
				'backgroundColor' => $prodi->color,
				'data' => $this->getJumlah($prodi->id)
			]);
		}

		$json_datasets = json_encode($datasets);

		return view($folder . '.index',
			compact('title', 'redirect', 'folder', 'json_label', 'json_datasets')
		);
	}

	public function getJumlah($prodi_id)
	{
		$datasets = collect([]);
		$dt_thakademik = ThAkademik::
			where('semester', 'Ganjil')
			->limit(6)
			->orderBy('kode', 'desc')
			->get();

		foreach ($dt_thakademik as $row) {
			$datasets->push(Mahasiswa::where('th_akademik_id', $row->id)->where('prodi_id', $prodi_id)->count());
		}

		return $datasets;
	}
}
