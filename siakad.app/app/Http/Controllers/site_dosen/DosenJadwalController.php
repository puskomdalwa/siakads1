<?php
namespace App\Http\Controllers\site_dosen;

use App\Http\Services\ServiceIntro;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Yajra\Datatables\Datatables;
use Alert;
use App\ThAkademik;
use App\Mahasiswa;
use App\JadwalKuliah;
use App\KRSDetail;
use App\KRS;
use App\FormSchadule;
use App\Dosen;
use App\Absensi;
use App\AbsensiDetail;

class DosenJadwalController extends Controller
{

	private $title = 'Jadwal Kuliah';
	private $redirect = 'dosen_jadwal';
	private $folder = 'site_dosen.dosen_jadwal';
	private $class = 'dosen_jadwal';

	private $rules = [
		'materi' => 'required',
		'absen.*' => 'required',
	];

	public function index()
	{
		$kode = Auth::user()->username;
		$th_akademik = ThAkademik::Aktif()->first();
		$dosen = Dosen::where('kode', $kode)->first();

		$folder = $this->folder;
		$redirect = $this->redirect;
		// $title = $this->title . ' Kode  : ' . $kode . ' Tahun Akademik : ' . $th_akademik->kode;
		$title = $this->title . ' Kode  : ' . $kode;

		$list_thakademik = ThAkademik::orderBy('kode', 'DESC')->get();

		// $data = JadwalKuliah::where('th_akademik_id', $th_akademik->id)
		// 	->where('dosen_id', $dosen->id)
		// 	->with('kurikulum_matakuliah', 'jamkul')
		// 	->orderBy('hari_id', 'asc')
		// 	->orderBy('jam_kuliah_id', 'asc')
		// 	->get();

		return view(
			$folder . '.view',
			compact('title', 'redirect', 'folder', 'kode', 'th_akademik', 'dosen', 'list_thakademik')
		);
	}

	public function getData(Request $request)
	{
		$redirect = $this->redirect;
		$th_akademik_id = $request->th_akademik_id;
		$kode = Auth::user()->username;
		$dosen = Dosen::where('kode', $kode)->first();

		$row = JadwalKuliah::where('th_akademik_id', $th_akademik_id)
			->where('dosen_id', $dosen->id)
			->with('kurikulum_matakuliah', 'kurikulum_matakuliah.matakuliah', 'prodi', 'kelompok', 'ruang_kelas', 'hari', 'jamkul', )
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
			->addColumn('jml_mhs', function ($row) {
				$krs_detail = KRSDetail::join('trans_krs', 'trans_krs.id', '=', 'trans_krs_detail.krs_id')->join('mst_mhs', 'mst_mhs.nim', '=', 'trans_krs.nim')->where('trans_krs_detail.jadwal_kuliah_id', $row->id)
					->where('trans_krs.acc_pa', 'Setujui')->count();
				return $krs_detail;
			})
			->addColumn('pertemuan_ke', function ($row) {
				return pertemuanke($row->id);
			})
			->addColumn('url_rekap', function ($row) use ($redirect) {
				return url($redirect . '/' . $row->id . '/rekapAbsensi');
			})
			->addColumn('url_absensi', function ($row) use ($redirect) {
				return url($redirect . '/' . $row->id . '/0/absensi');
			})
			->addColumn('absensi', function ($row) {
				$btn = '<a href="' . url('/' . $this->class . '/' . $row->id . '/absensi') .
					'" class="btn btn-info btn-xs btn-block "><i class="fa fa-calendar"></i></a>';
				return $btn;
			})
			->rawColumns(['absensi'])
			->make(true);
	}

	public function absensi($id, $absen_id)
	{
		$kode = Auth::user()->username;

		$dosen = Dosen::where('kode', $kode)->first();

		$title = $this->title . ' Input Absensi Mahasiswa';
		$redirect = $this->redirect;
		$data = JadwalKuliah::findOrFail($id);

		$absen = Absensi::where('trans_jadwal_kuliah_id', $data->id)
			->where('id', $absen_id)
			->first();

		$mhs = KRSDetail::join('trans_krs', 'trans_krs.id', '=', 'trans_krs_detail.krs_id')
			->join('mst_mhs', 'mst_mhs.nim', '=', 'trans_krs.nim')
			->leftJoin('trans_absensi_mhs_detail', function ($join) use ($id, $absen_id) {
				$join->on('trans_absensi_mhs_detail.nim', '=', 'mst_mhs.nim')
					->where('trans_absensi_mhs_detail.trans_jadwal_kuliah_id', $id)
					->where('trans_absensi_mhs_detail.trans_absensi_mhs', $absen_id);
			})
			->where('trans_krs_detail.jadwal_kuliah_id', $data->id)
			->where('trans_krs.acc_pa', 'Setujui')
			->orderBy('trans_krs_detail.nama_mhs', 'asc')
			->select('trans_krs_detail.*','trans_absensi_mhs_detail.status as absensi_status')
			->get();

		$pertemuanKe = Absensi::where('trans_jadwal_kuliah_id', $data->id)->count() + 1;
		return view(
			$this->folder . '.absensi',
			compact('data', 'title', 'redirect', 'mhs', 'absen', 'pertemuanKe')
		);
	}

	public function simpanabsensi(Request $request)
	{
		try {
			\DB::beginTransaction();
			$this->validate($request, $this->rules);
			if (!$request->absen) {
				alert()->error('Data Error ', $this->title);
				return back();
			}

			$trans_jadwal_kuliah_id = $request->trans_jadwal_kuliah_id;
			$trans_absensi_mhs = $request->trans_absensi_mhs;

			$absen = Absensi::where('trans_jadwal_kuliah_id', $trans_jadwal_kuliah_id)
				->where('id', $trans_absensi_mhs)->first();

			if (!$absen) {
				$absen = new Absensi;
				$absen->tanggal = date('Y-m-d');
			}

			$absen->trans_jadwal_kuliah_id = $trans_jadwal_kuliah_id;
			$absen->materi = $request->materi;
			$absen->user_id = Auth::user()->id;
			$absen->save();

			foreach ($request->absen as $key => $value) {
				$nim = $value['nim'];

				if (isset($value['status']) == 'Hadir') {
					$status = $value['status'];
				} else {
					if (isset($value['status']) == 'Alpha') {
						$status = $value['status'];
					} else {
						if (isset($value['status']) == 'Sakit') {
							$status = $value['status'];
						} else {
							if (isset($value['status']) == 'Ijin') {
								$status = $value['status'];
							} else {
								$status = '';
							}
						}
					}
				}

				$absenDetail = AbsensiDetail::where('trans_jadwal_kuliah_id', $trans_jadwal_kuliah_id)
					->where('trans_absensi_mhs', $absen->id)
					->where('nim', $nim)->first();

				if (!$absenDetail) {
					$absenDetail = new AbsensiDetail;
				}

				$absenDetail->trans_jadwal_kuliah_id = $trans_jadwal_kuliah_id;
				$absenDetail->trans_absensi_mhs = $absen->id;
				$absenDetail->nim = $nim;
				$absenDetail->status = $status;
				$absenDetail->save();
			}
			\DB::commit();

			alert()->success('Simpan Data Kehadiran Berhasil ', $this->title);
			return redirect($this->redirect . '/' . $trans_jadwal_kuliah_id . '/' . $absen->id . '/absensi');
		} catch (\Throwable $th) {
			\DB::rollBack();
			alert()->error('Gagal simpan absensi ', $this->title);
			return back();
		}
	}

	public function rekapAbsensi($id)
	{
		$title = 'Rekap Absensi Mahasiswa';
		$redirect = $this->redirect;
		$data = JadwalKuliah::findOrFail($id);
		$absen = Absensi::where('trans_jadwal_kuliah_id', $id)->get();

		return view($this->folder . '.rekapAbsensi', compact('title', 'data', 'absen', 'redirect'));
	}

	public function deleteAbsensi(Request $request, $absen_id)
	{
		try {
			\DB::beginTransaction();

			$absensi = Absensi::where('id', $absen_id)->first();
			AbsensiDetail::where('trans_absensi_mhs', $absen_id)->delete();
			$absensi->delete();

			\DB::commit();
			return [
				'success' => true,
				'title' => 'Delete',
				'message' => "Berhasil menghapus data $request->pertemuan",
				'id' => $absen_id,
				'type' => 'success',
			];
		} catch (\Throwable $th) {
			\DB::rollback();
			return [
				'success' => false,
				'title' => 'Delete',
				'message' => "Gagal menghapus data $request->pertemuan",
				'id' => $absen_id,
				'type' => 'error',
				'error' => $th->getMessage()
			];
		}

	}
}