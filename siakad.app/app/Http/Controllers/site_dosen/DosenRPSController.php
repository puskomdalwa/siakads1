<?php
namespace App\Http\Controllers\site_dosen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Yajra\Datatables\Datatables;
use Alert;

use Image;
use App\ThAkademik;
use App\Mahasiswa;
use App\JadwalKuliah;
use App\KRSDetail;
use App\KRS;
use App\FormSchadule;
use App\Dosen;
use App\RPS;

class DosenRPSController extends Controller
{

	private $title = 'Rencana Perkuliahan Semester (RPS)';
	private $redirect = 'dosen_rps';
	private $folder = 'site_dosen.dosen_rps';
	private $class = 'dosen_rps';


	private $rules = [
		'dokumen' => 'required|file|mimes:pdf|max:5000',
	];

	public function index()
	{
		$kode = Auth::user()->username;
		$th_akademik = ThAkademik::Aktif()->first();

		$title = $this->title . ' Kode  : ' . $kode . ' Tahun Akademik : ' . $th_akademik->kode;
		$redirect = $this->redirect;
		$folder = $this->folder;

		return view(
			$folder . '.index',
			compact('title', 'redirect', 'folder', 'th_akademik', 'kode')
		);
	}

	public function getData(Request $request)
	{
		$th_akademik_id = $request->th_akademik_id;

		$kode = Auth::user()->username;
		$dosen = Dosen::where('kode', $kode)
			->first();

		$row = JadwalKuliah::where('th_akademik_id', $th_akademik_id)
			->where('dosen_id', $dosen->id)
			->with('kurikulum_matakuliah', 'rps')
			->get();

		return Datatables::of($row)
			->addColumn('kode_mk', function ($row) {
				return @$row->kurikulum_matakuliah->matakuliah->kode;
			})
			->addColumn('nama_mk', function ($row) {
				return @$row->kurikulum_matakuliah->matakuliah->nama;
			})
			->addColumn('sks_mk', function ($row) {
				return @$row->kurikulum_matakuliah->matakuliah->sks;
			})
			->addColumn('smt_mk', function ($row) {
				return @$row->kurikulum_matakuliah->matakuliah->smt;
			})
			->addColumn('kelompok', function ($row) {
				return @$row->kelompok->kode;
			})
			->addColumn('ruang', function ($row) {
				return @$row->ruang_kelas->kode;
			})
			->addColumn('hari', function ($row) {
				return @$row->hari->nama;
			})
			->addColumn('waktu', function ($row) {
				return @$row->jamkul->nama;
			})
			->addColumn('dokumen', function ($row) {
				return !empty($row->rps->dokumen) ? '<a href="' . url('/dokumen_rps/' . $row->rps->dokumen) . '" target="_blank">
			<i class="fa fa-download"><i></a>' : null;
			})
			->addColumn('upload', function ($row) {
				return '<button class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal" onClick="getID(' . $row->id . ')"> 
			<i class="fa fa-upload"></i></button>';
			})
			->rawColumns(['upload', 'dokumen'])
			->make(true);
	}

	public function store(Request $request)
	{
		$this->validate($request, $this->rules);
		$id = $request->jadwal_id;

		$image = $request->file('dokumen');
		$nama_file = $id . '.' . $image->getClientOriginalExtension();

		$destinationPath = public_path('dokumen_rps');

		$request->dokumen->move($destinationPath, $nama_file);

		$data = RPS::where('id', $id)->first();

		if (!$data) {
			$data = new RPS;
		}

		$data->jadwal_id = $id;
		$data->dokumen = $nama_file;
		$data->user_id = Auth::user()->id;
		$data->save();

		alert()->success('Update Data Success', $this->title);
		return back();
	}
}