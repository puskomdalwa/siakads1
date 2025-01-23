<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use Auth;
use Alert;
use PDF;
use App\PT;
use App\Ref;
use App\User;
use App\Prodi;
use App\Mahasiswa;
use App\ThAkademik;

use Excel;

class LapMahasiswaController extends Controller
{

	private $title = 'Laporan Mahasiswa';
	private $redirect = 'lapmahasiswa';
	private $folder = 'lapmahasiswa';
	private $class = 'lapmahasiswa';

	private $rules = [
		'th_akademik_id' => 'required',
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

		$list_kelas = Ref::where('table', 'Kelas')->get();
		$kelas_id = Ref::where('table', 'Kelas')->first()->id;

		$list_status = Ref::where('table', 'StatusMhs')->get();
		$status_id = Ref::where('table', 'StatusMhs')->first()->id;

		$list_kelompok = Ref::where('table', 'Kelompok')->get();
		$list_thakademik = ThAkademik::where('semester', 'Ganjil')->orderBy('kode', 'DESC')->get();

		return view(
			$folder . '.index',
			compact(
				'title',
				'redirect',
				'folder',
				'prodi_id',
				'list_prodi',
				'level',
				'kelas_id',
				'list_kelas',
				'status_id',
				'list_status',
				'list_kelompok',
				'list_thakademik'
			)
		);
	}

	public function store(Request $request)
	{
		$th_akademik_id = $request->th_akademik_id;

		$prodi_id = $request->prodi_id;
		$prodi = @strtolower(Auth::user()->prodi->id);

		if ($prodi) {
			$prodi_id = $prodi;
		} else {
			$prodi_id = $request->prodi_id;
		}

		$kelas_id = $request->kelas_id;
		$kelompok_id = $request->kelompok_id;
		$status_id = $request->status_id;

		$th_akademik = ThAkademik::where('id', $th_akademik_id)->first();

		$list_kelas = Ref::where('table', 'Kelas')
			->when($kelas_id, function ($query) use ($kelas_id) {
				return $query->where('id', $kelas_id);
			})
			->get();

		$list_prodi = Prodi::when($prodi_id, function ($query) use ($prodi_id) {
			return $query->where('id', $prodi_id);
		})
			->orderBy('kode')
			->get();

		foreach ($list_kelas as $kelas) {
			foreach ($list_prodi as $prodi) {
				if ($kelompok_id) {
					$rows[$kelas->id][$prodi->id] = Mahasiswa::select('mst_mhs.*')
						->join('trans_perwalian_detail', 'trans_perwalian_detail.nim', '=', 'mst_mhs.nim')
						->join('trans_perwalian', 'trans_perwalian.id', '=', 'trans_perwalian_detail.perwalian_id')
						->where('mst_mhs.th_akademik_id', $th_akademik_id)
						->where('mst_mhs.prodi_id', $prodi->id)
						->where('mst_mhs.kelas_id', $kelas->id)
						->where('trans_perwalian.kelompok_id', $kelompok_id)

						->when($status_id, function ($query) use ($status_id) {
							return $query->where('mst_mhs.status_id', $status_id);
						})
						->orderBy('nim', 'asc')
						->with(['th_akademik', 'prodi', 'kelas', 'jk', 'agama', 'status'])
						->get();
				} else {
					$rows[$kelas->id][$prodi->id] = Mahasiswa::

						where('mst_mhs.th_akademik_id', $th_akademik_id)
						->where('mst_mhs.prodi_id', $prodi->id)
						->where('mst_mhs.kelas_id', $kelas->id)

						->when($status_id, function ($query) use ($status_id) {
							return $query->where('mst_mhs.status_id', $status_id);
						})
						->orderBy('nim', 'asc')
						->with(['th_akademik', 'prodi', 'kelas', 'jk', 'agama', 'status'])
						->get();
				}
			}
		}

		$data = array(
			'th_akademik' => $th_akademik,
			'kelas_id' => $kelas_id,
			'kelompok_id' => $kelompok_id,
			'status_id' => $status_id,
			'list_kelas' => $list_kelas,
			'list_prodi' => $list_prodi,
			'rows' => $rows,
		);

		return view($this->folder . '.data2', compact('data'));
	}

	public function getListKelompok($prodi_id)
	{
		$alias = Prodi::where('id', $prodi_id)->first()->alias;

		$kelompok = Ref::where('table', 'Kelompok')
			->where('keterangan', $alias)
			->orderby('nama', 'Desc')
			->get();

		echo '<option value="">-Pilih Kelompok-</option>';
		foreach ($kelompok as $row) {
			echo '<option value="' . $row->id . '">' . $row->id . ' <> ' . $row->nama . '</option>';
		}
	}

	public function cetak(Request $request)
	{

		$th_akademik_id = $request->th_akademik_id;

		$prodi_id = $request->prodi_id;
		$prodi = @strtolower(Auth::user()->prodi->id);

		if ($prodi) {
			$prodi_id = $prodi;
		} else {
			$prodi_id = $request->prodi_id;
		}

		$kelas_id = $request->kelas_id;
		$kelompok_id = $request->kelompok_id;
		$status_id = $request->status_id;

		$th_akademik = ThAkademik::where('id', $th_akademik_id)->first();

		$pt = PT::first();

		$prodi = @Prodi::where('id', Auth::user()->prodi->id)->first();

		$data = Mahasiswa::select('mst_mhs.*')
			->join('trans_perwalian_detail', 'trans_perwalian_detail.nim', '=', 'mst_mhs.nim')
			->join('trans_perwalian', 'trans_perwalian.id', '=', 'trans_perwalian_detail.perwalian_id')
			->where('mst_mhs.th_akademik_id', $th_akademik_id)
			->when($prodi_id, function ($query) use ($prodi_id) {
				return $query->where('mst_mhs.prodi_id', $prodi_id);
			})
			->when($kelas_id, function ($query) use ($kelas_id) {
				return $query->where('mst_mhs.kelas_id', $kelas_id);
			})
			->when($kelompok_id, function ($query) use ($kelompok_id) {
				return $query->where('trans_perwalian.kelompok_id', $kelompok_id);
			})
			->when($status_id, function ($query) use ($status_id) {
				return $query->where('mst_mhs.status_id', $status_id);
			})
			->orderBy('nim', 'asc')
			->with(['th_akademik', 'prodi', 'kelas', 'jk', 'agama', 'status'])
			->get();

		if ($request->cetak == 'pdf') {
			$class = 'text-left';
			$pdf = PDF::loadView(
				$this->folder . '.cetak',
				compact('data', 'th_akademik', 'pt', 'prodi', 'class')
			);

			return $pdf->setPaper('a4', 'landscape')
				->stream('Laporan Mahasiswa ' . $th_akademik->kode . '.pdf');
		} else {
			// $prodi_id = $request->prodi_id;
			// $type = 'xls';
			// $data = Mahasiswa::select('nim', 'nama', 'tempat_lahir', 'tanggal_lahir', 'nik')
			// 	->where('th_akademik_id', $th_akademik_id)
			// 	->when($prodi_id, function ($query) use ($prodi_id) {
			// 		return $query->where('prodi_id', $prodi_id);
			// 	})
			// 	->get()
			// 	->toArray();

			// return Excel::create(
			// 	'Mahasiswa',
			// 	function ($excel) use ($data) {
			// 		$excel->sheet(
			// 			'Data Mahasiswa',
			// 			function ($sheet) use ($data) {
			// 				$sheet->fromArray($data);
			// 			}
			// 		);
			// 	}
			// )
			// 	->download($type);

			$th_akademik = ThAkademik::where('id', $th_akademik_id)->first();
			$dt_prodi = Prodi::where('id', $prodi_id)->first();
			$pt = PT::first();
			$judul = "mahasiswa";
			return view('lapmahasiswa.excel', compact('data', 'th_akademik', 'dt_prodi', 'prodi_id', 'pt', 'judul'));
		}
	}
}