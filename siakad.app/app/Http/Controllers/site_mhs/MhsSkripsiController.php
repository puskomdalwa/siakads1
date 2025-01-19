<?php
namespace App\Http\Controllers\site_mhs;

use App\Http\Controllers\site_admin\SkripsiController;
use App\Http\Services\ServiceSkripsi;
use DB;
use Auth;
use Alert;
use App\KRS;
use App\KRSDetail;
use App\Mahasiswa;
use App\ThAkademik;

use App\SkripsiJudul;
use App\SkripsiBimbingan;
use App\SkripsiPengajuan;
use App\SkripsiPembimbing;
use App\SkripsiUjianSkripsi;
use Illuminate\Http\Request;
use App\SkripsiUjianProposal;
use Yajra\Datatables\Datatables;
use App\SkripsiUjianSkripsiDosen;
use App\SkripsiUjianProposalDosen;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MhsSkripsiController extends Controller
{

	private $title = 'Skripsi';
	private $redirect = 'mhs_skripsi';
	private $folder = 'site_mhs.mhs_skripsi';
	private $class = 'mhs_skripsi';

	private $rules = ['judul' => 'required',];

	public function index()
	{
		$title = $this->title;
		$redirect = $this->redirect;
		$folder = $this->folder;

		$mahasiswa = @Auth::user()->mahasiswa;
		$nim = @$mahasiswa->nim;
		$thAkademik = @ThAkademik::Aktif()->first();
		$pengajuan = SkripsiPengajuan::where('th_akademik_id', $thAkademik->id)
			->where('nim', $nim)->first();

		$cekSkripsiMahasiswa = ServiceSkripsi::cekMahasiswa();

		return view($this->folder . '.index', compact('title', 'redirect', 'folder', 'pengajuan', 'cekSkripsiMahasiswa'));
	}

	public function getData(Request $request)
	{
		$search = $request->search['value'];
		$row = SkripsiPengajuan::join('skripsi_judul', 'skripsi_judul.skripsi_pengajuan_id', '=', 'skripsi_pengajuan.id')
			->join('mst_th_akademik', 'mst_th_akademik.id', '=', 'skripsi_pengajuan.th_akademik_id')
			->join('mst_mhs', 'mst_mhs.nim', '=', 'skripsi_pengajuan.nim')
			->join('mst_prodi', 'mst_prodi.id', '=', 'mst_mhs.prodi_id')
			->select(
				'skripsi_pengajuan.id as skripsi_pengajuan_id',
				'skripsi_pengajuan.nim',
				'skripsi_pengajuan.status',
				'skripsi_judul.id',
				'skripsi_judul.judul as skripsi_judul_judul',
				'skripsi_judul.acc as skripsi_judul_acc',
				'mst_th_akademik.nama as mst_th_akademik_nama',
				'mst_th_akademik.semester as mst_th_akademik_semester',
				'mst_mhs.nama as mst_mhs_nama',
				'mst_prodi.nama as mst_prodi_nama',
			);

		return Datatables::of($row)
			->filter(function ($query) use ($search) {
				$query->where('skripsi_pengajuan.nim', Auth::user()->username);
				$query->where(function ($query) use ($search) {
					$query->orWhere('skripsi_judul.judul', 'LIKE', "%$search%");
				});
			})
			->editColumn('skripsi_judul_judul', function ($row) {
				$acc = SkripsiJudul::where('skripsi_pengajuan_id', $row->skripsi_pengajuan_id)
					->where('acc', 'Y')
					->first();
				$status = $acc ? true : false;

				$statusPengajuan = $row->status;
				if ($status) {
					if ($row->id != $acc->id) {
						$statusPengajuan = 'Tidak ACC';
					}
				}

				if ($statusPengajuan == "Baru") {
					$color = 'primary';
				}
				if ($statusPengajuan == "Diperiksa") {
					$color = 'warning';
				}
				if ($statusPengajuan == "Ujian Proposal") {
					$color = 'success';
				}
				if ($statusPengajuan == "Bimbingan") {
					$color = 'success';
				}
				if ($statusPengajuan == "Ujian Skripsi") {
					$color = 'success';
				}
				if ($statusPengajuan == "Tidak ACC") {
					$color = 'danger';
				}
				if ($statusPengajuan == 'Ditolak') {
					$color = 'danger';
				}

				$response =
					'<div>' .
					'<div class="text-' . @$color . '">* Status Skripsi: ' . $statusPengajuan . '</div>' .
					'<div><a style="color:black" href="' . route('mhs_skripsi.detail', ['id' => $row->id]) . '"><b>' . strip_tags($row->skripsi_judul_judul) . '</b></a></div>' .
					'<div>' .
					'<span class="text-gray" style="margin-right:5px">' . $row->nim . '</span>' .
					'<span class="text-gray" style="margin-right:5px"><i class="fa fa-circle" aria-hidden="true"></i> ' . strtoupper($row->mst_mhs_nama) . '</span>' .
					'<span class="badge badge-success" style="font-size: 1rem;margin-right:5px">' . strtoupper($row->mst_prodi_nama) . '</span>' .
					'<span class="badge badge-warning" style="font-size: 1rem">' . "$row->mst_th_akademik_nama ($row->mst_th_akademik_semester)" . '</span>' .
					'</div>' .
					'<div>';

				if ($statusPengajuan != "Tidak ACC") {
					// pembimbing
					$pembimbing = SkripsiPembimbing::where('skripsi_pengajuan_id', $row->skripsi_pengajuan_id)->get();
					for ($i = 0; $i < count($pembimbing); $i++) {
						$pembimbingSkripsi = @$pembimbing[$i]->jabatan . ': ' . @$pembimbing[$i]->dosen->nama;
						if ($i != count($pembimbing) - 1) {
							$pembimbingSkripsi .= ',';
						}
						$response .= '<span class="text-gray" style="margin-right:5px">' . $pembimbingSkripsi . '</span>';
					}
					if (count($pembimbing) < 1) {
						$response .= '<span class="text-gray" style="margin-right:5px">Belum ada pembimbing</span>';
					}
				}

				$response .=
					'</div>' .
					'</div>';
				return $response;
			})
			->editColumn('updated_at', function ($row) {
				return \Carbon\Carbon::parse($row->updated_at)->format('d F Y H:i:s');
			})
			->addColumn('action', function ($row) {
				$acc = SkripsiJudul::where('skripsi_pengajuan_id', $row->skripsi_pengajuan_id)
					->where('acc', 'Y')
					->first();
				$status = $acc ? true : false;

				$statusPengajuan = $row->status;
				if ($status) {
					if ($row->id != $acc->id) {
						$statusPengajuan = 'Tidak ACC';
					}
				}

				$response = '
				<div class="btn-group">
					<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Klik <span class="caret"></span></button>
					<ul class="dropdown-menu pull-right">
					<li><a href="' . route('mhs_skripsi.detail', ['id' => $row->id]) . '" style="cursor:pointer">Detail</a></li>';

				if ($statusPengajuan == "Baru") {
					$response .= '
						<li><a style="cursor:pointer" 
						data-toggle="modal" data-target="#modal_edit"
						data-id="' . $row->id . '"
						data-judul="' . strip_tags($row->skripsi_judul_judul) . '">Edit</a></li>';
					$response .= '
						<li class="divider"></li>';
					$response .= '
						<li><a style="cursor:pointer" onclick="deleteForm(' . $row->id . ')">Delete</a></li>';
				}
				$response .= '
					</ul>
				</div>';

				return $response;
			})
			->rawColumns(['skripsi_judul_judul', 'action'])
			->make(true);
	}

	public static function cekPengajuan($judul)
	{
		$acc = SkripsiJudul::where('skripsi_pengajuan_id', $judul->skripsi_pengajuan_id)
			->where('acc', 'Y')
			->first();
		$status = $acc ? true : false;

		$statusPengajuan = $judul->pengajuan->status;
		if ($status) {
			if ($judul->id != $acc->id) {
				$statusPengajuan = 'Tidak ACC';
			}
		}

		return $statusPengajuan;
	}

	public function store(Request $request)
	{
		try {
			DB::beginTransaction();
			$cek = ServiceSkripsi::cekMahasiswa();
			if (!$cek['status']) {
				return abort(500, $cek['message']);
			}

			$request->validate([
				'judul' => 'required',
				'dokumen_proposal' => 'nullable|file|mimes:doc,docx,pdf|max:5120'
			]);

			$mahasiswa = @Auth::user()->mahasiswa;
			$nim = @$mahasiswa->nim;
			$thAkademik = @ThAkademik::Aktif()->first();
			$dokumen_proposal = $request->file('dokumen_proposal');

			$pengajuan = SkripsiPengajuan::where('th_akademik_id', $thAkademik->id)
				->where('nim', $nim)->first();

			$statusPengajuan = @$pengajuan->status;
			if ($statusPengajuan != "Baru" && $statusPengajuan != null) {
				return response()->json([
					'status' => false,
					'code' => 500,
					'message' => "Tidak bisa menambahkan judul karena skripsi di tahun akademik ini berstatus $statusPengajuan",
					'color' => 'warning'
				]);
			}

			if ($pengajuan) {
				$cekJudulSama = SkripsiJudul::join('skripsi_pengajuan', 'skripsi_judul.skripsi_pengajuan_id', '=', 'skripsi_pengajuan.id')
					->where('skripsi_pengajuan_id', $pengajuan->id)
					->where('nim', $nim)
					->where('judul', $request->judul)
					->where('th_akademik_id', $thAkademik->id)
					->first();
				if ($cekJudulSama) {
					return response()->json([
						'status' => false,
						'code' => 500,
						'message' => 'Judul sama dengan proposal yang sudah diajukan',
						'color' => 'danger'
					]);
				}
				$jumlahPengajuan = $pengajuan->judul->count();
				if ($jumlahPengajuan >= 3) {
					return response()->json([
						'status' => false,
						'code' => 500,
						'message' => 'Sudah melebihi jumlah pengajuan skripsi maksimal 3',
						'color' => 'danger'
					]);
				}
			}

			if (empty($pengajuan)) {
				$pengajuan = new SkripsiPengajuan();
				$pengajuan->th_akademik_id = $thAkademik->id;
				$pengajuan->prodi_id = @$mahasiswa->prodi_id;
				$pengajuan->tanggal = date('Y-m-d');
				$pengajuan->nim = $nim;
				$pengajuan->status = 'Baru';
				$pengajuan->user_id = Auth::user()->id;
				$pengajuan->save();
			}

			$skripsiJudul = new SkripsiJudul();
			$skripsiJudul->skripsi_pengajuan_id = $pengajuan->id;
			$skripsiJudul->judul = $request->judul;
			$skripsiJudul->acc = 'T';

			if ($request->has('dokumen_proposal')) {
				$nama = uniqid() . "-$nim-$thAkademik->kode" . '.' . $dokumen_proposal->getClientOriginalExtension();
				$lokasi = public_path('dokumen_skripsi');
				$upload = $dokumen_proposal->move($lokasi, $nama);
				if (!$upload) {
					return response()->json([
						'status' => false,
						'code' => 500,
						'message' => 'file tidak terupload',
						'color' => 'danger'
					]);
				}
				$skripsiJudul->dokumen_proposal = $nama;
			}
			$skripsiJudul->user_id = Auth::user()->id;
			$skripsiJudul->save();

			DB::commit();
			return response()->json([
				'status' => true,
				'code' => 200,
				'message' => 'File proposal berhasil ditambahkan',
				'color' => 'success'
			]);
		} catch (\Throwable $th) {
			DB::rollback();
			return response()->json([
				'status' => false,
				'code' => 500,
				'message' => $th->getMessage(),
				'color' => 'danger'
			]);
		}
	}

	public function update(Request $request)
	{
		try {
			DB::beginTransaction();
			$request->validate([
				'id' => 'required',
				'judul' => 'required',
				'dokumen_proposal' => 'nullable|file|mimes:doc,docx,pdf|max:5120'
			]);

			$mahasiswa = @Auth::user()->mahasiswa;
			$nim = @$mahasiswa->nim;
			$thAkademik = @ThAkademik::Aktif()->first();
			$dokumen_proposal = $request->file('dokumen_proposal');

			$pengajuan = SkripsiPengajuan::where('th_akademik_id', $thAkademik->id)
				->where('nim', $nim)->first();
			$skripsiJudul = SkripsiJudul::find($request->id);

			$statusPengajuan = MhsSkripsiController::cekPengajuan($skripsiJudul);
			if ($statusPengajuan != 'Baru' && $statusPengajuan != 'Diperiksa' && $statusPengajuan != 'Ujian Proposal') {
				return response()->json([
					'status' => false,
					'code' => 500,
					'message' => "Tidak bisa diedit karena skripsi berstatus $statusPengajuan",
					'data' => null,
					'color' => 'warning'
				]);
			}

			$cekJudulSama = SkripsiJudul::join('skripsi_pengajuan', 'skripsi_judul.skripsi_pengajuan_id', '=', 'skripsi_pengajuan.id')
				->where('skripsi_pengajuan_id', $pengajuan->id)
				->where('nim', $nim)
				->where('skripsi_judul.id', '!=', $request->id)
				->where('judul', $request->judul)
				->where('th_akademik_id', $thAkademik->id)
				->first();
			if ($cekJudulSama) {
				return response()->json([
					'status' => false,
					'code' => 500,
					'message' => 'Judul sama dengan proposal yang sudah diajukan',
					'data' => null,
					'color' => 'danger'
				]);
			}

			if ($request->has('dokumen_proposal')) {
				$nama = uniqid() . "-$nim-$thAkademik->kode" . '.' . $dokumen_proposal->getClientOriginalExtension();
				$lokasi = public_path('dokumen_skripsi');

				if ($skripsiJudul->dokumen_proposal) {
					$oldFilePath = $lokasi . '/' . $skripsiJudul->dokumen_proposal;
					if (file_exists($oldFilePath)) {
						// Attempt to delete the older file
						if (!unlink($oldFilePath)) {
							return response()->json([
								'status' => false,
								'code' => 500,
								'message' => 'Failed to delete the old file.',
								'data' => null,
								'color' => 'danger'
							]);
						}
					}
				}

				$upload = $dokumen_proposal->move($lokasi, $nama);
				if (!$upload) {
					return response()->json([
						'status' => false,
						'code' => 500,
						'message' => 'file tidak terupload',
						'data' => null,
						'color' => 'danger'
					]);
				}
				$skripsiJudul->dokumen_proposal = $nama;
			}

			$skripsiJudul->skripsi_pengajuan_id = $pengajuan->id;
			$skripsiJudul->judul = $request->judul;
			$skripsiJudul->acc = 'T';
			$skripsiJudul->user_id = Auth::user()->id;
			$skripsiJudul->save();

			DB::commit();
			return response()->json([
				'status' => true,
				'code' => 200,
				'message' => 'File proposal berhasil diedit',
				'data' => $skripsiJudul,
				'color' => 'success'
			]);
		} catch (\Throwable $th) {
			DB::rollback();
			return response()->json([
				'status' => false,
				'code' => 500,
				'message' => $th->getMessage(),
				'data' => null,
				'color' => 'danger'
			]);
		}
	}

	public function delete($id)
	{
		try {
			DB::beginTransaction();
			$skripsi = SkripsiJudul::findOrFail($id);
			$pengajuan = $skripsi->pengajuan;
			if ($pengajuan->status != "Baru") {
				return response()->json([
					'status' => false,
					'code' => 500,
					'title' => $skripsi->judul,
					'text' => "Tidak dapat dihapus karena berstatus $pengajuan->status",
					'type' => 'error'
				]);
			}
			$skripsi->delete();

			if ($skripsi->dokumen_proposal) {
				$lokasi = public_path('dokumen_skripsi');

				$oldFilePath = $lokasi . '/' . $skripsi->dokumen_proposal;
				if (file_exists($oldFilePath)) {
					// Attempt to delete the older file
					if (!unlink($oldFilePath)) {
						DB::rollBack();
						return response()->json([
							'status' => false,
							'code' => 500,
							'title' => $skripsi->judul,
							'text' => 'Failed to delete the old file',
							'type' => 'error'
						]);
					}
				}
			}

			DB::commit();
			return response()->json([
				'status' => true,
				'code' => 200,
				'title' => $skripsi->judul,
				'text' => 'File proposal berhasil di hapus',
				'type' => 'success'
			]);
		} catch (\Throwable $th) {
			//throw $th;
			DB::rollback();
			return response()->json([
				'status' => true,
				'code' => 200,
				'title' => 'Error',
				'text' => 'File proposal gagal di hapus',
				'type' => 'error'
			]);
		}
	}

	public function downloadProposal($id)
	{
		try {
			$cekJudul = ServiceSkripsi::cekJudulMahasiswa($id);
			if (!$cekJudul['status']) {
				return abort(500, $cekJudul['message']);
			}

			$skripsi = SkripsiJudul::findOrFail($id);
			$path = asset('dokumen_skripsi/' . $skripsi->dokumen_proposal);
			if ($skripsi->dokumen_proposal) {
				return redirect($path);
			}

			return "tidak ada file";
		} catch (ModelNotFoundException $e) {
			if ($e instanceof ModelNotFoundException) {
				return abort(404);
			}
		} catch (\Throwable $th) {
			return $th->getMessage();
		}
	}

	public function detail($id)
	{
		try {
			$cekJudul = ServiceSkripsi::cekJudulMahasiswa($id);
			if (!$cekJudul['status']) {
				return abort(500, '404');
			}

			$title = $this->title . ' | Detail';
			$redirect = $this->redirect;
			$folder = $this->folder;
			$skripsi = SkripsiJudul::findOrFail($id);
			$mahasiswa = $skripsi->pengajuan->mahasiswa;
			$picture = Auth::user()->picture;

			$statusPengajuan = MhsSkripsiController::cekPengajuan($skripsi);
			if ($statusPengajuan == "Baru") {
				$color = 'primary';
			}
			if ($statusPengajuan == "Diperiksa") {
				$color = 'warning';
			}
			if ($statusPengajuan == "Ujian Proposal") {
				$color = 'success';
			}
			if ($statusPengajuan == "Bimbingan") {
				$color = 'success';
			}
			if ($statusPengajuan == "Ujian Skripsi") {
				$color = 'success';
			}
			if ($statusPengajuan == "Selesai") {
				$color = 'success';
			}
			if ($statusPengajuan == "Tidak ACC") {
				$color = 'danger';
			}
			if ($statusPengajuan == 'Ditolak') {
				$color = 'danger';
			}

			$pengajuan = $skripsi->pengajuan;
			$pembimbing = SkripsiPembimbing::where('skripsi_pengajuan_id', $pengajuan->id)->get();

			$skripsi->judul = strip_tags($skripsi->judul);

			$ujianProposal = SkripsiUjianProposal::where('skripsi_pengajuan_id', $pengajuan->id)->first();
			$dosenPengujiUjianProposal = $ujianProposal ? SkripsiUjianProposalDosen::where('ujian_proposal_id', $ujianProposal->id)->get()
				: null;
			$colorUjianProposal = 'secondary';
			if ($ujianProposal) {
				if ($ujianProposal->status == "lolos") {
					$colorUjianProposal = "success";
				}
				if ($ujianProposal->status == "tidak lolos") {
					$colorUjianProposal = "danger";
				}
				if ($ujianProposal->status == "belum ujian") {
					$colorUjianProposal = "warning";
				}
			}
			$ujianSkripsi = SkripsiUjianSkripsi::where('skripsi_pengajuan_id', $pengajuan->id)->first();
			$dosenPengujiUjianSkripsi = $ujianSkripsi ? SkripsiUjianSkripsiDosen::where('ujian_skripsi_id', $ujianSkripsi->id)->get()
				: null;
			$colorUjianSkripsi = 'secondary';
			if ($ujianSkripsi) {
				if ($ujianSkripsi->status == "lolos") {
					$colorUjianSkripsi = "success";
				}
				if ($ujianSkripsi->status == "tidak lolos") {
					$colorUjianSkripsi = "danger";
				}
				if ($ujianSkripsi->status == "belum ujian") {
					$colorUjianSkripsi = "warning";
				}
			}

			$listStatus = SkripsiController::getEnumValues('skripsi_pengajuan', 'status');
			unset($listStatus[6]);
			$keyStatus = array_search($statusPengajuan, $listStatus);

			for ($i = 0; $i < count($listStatus); $i++) {
				$completed = false;
				if ($i <= $keyStatus && $keyStatus != null) {
					$completed = true;
				}
				$listStatus[$i] = [
					'status' => $listStatus[$i],
					'completed' => $completed,
				];
			}
			return view(
				$this->folder . '.detail',
				compact(
					'title',
					'redirect',
					'folder',
					'skripsi',
					'mahasiswa',
					'picture',
					'color',
					'id',
					'pembimbing',
					'statusPengajuan',
					'ujianProposal',
					'dosenPengujiUjianProposal',
					'colorUjianProposal',
					'ujianSkripsi',
					'dosenPengujiUjianSkripsi',
					'colorUjianSkripsi',
					'listStatus'
				)
			);
		} catch (ModelNotFoundException $e) {
			if ($e instanceof ModelNotFoundException) {
				return abort(404);
			}
		} catch (\Throwable $th) {
			if ($th->getMessage() == '404') {
				return abort(404);
			}
			return $th->getMessage();
		}
	}

	public function getDataBimbingan(Request $request, $id)
	{
		$cekJudul = ServiceSkripsi::cekJudulMahasiswa($id);
		if (!$cekJudul['status']) {
			return abort(500, $cekJudul['message']);
		}

		$search = $request->search['value'];
		$judulId = $id;
		$row = SkripsiBimbingan::join('skripsi_judul', 'skripsi_judul.id', '=', 'skripsi_bimbingan.judul_id')
			->join('mst_dosen', 'mst_dosen.id', '=', 'skripsi_bimbingan.mst_dosen_id')
			->select(
				'skripsi_bimbingan.*',
				'mst_dosen.nama as dosen_nama'
			);

		return Datatables::of($row)
			->filter(function ($query) use ($search, $judulId) {
				$query->where('skripsi_bimbingan.judul_id', $judulId);
				$query->where(function ($query) use ($search) {
					$query->orWhere('skripsi_bimbingan.created_at', 'LIKE', "%$search%");
					$query->orWhere('skripsi_bimbingan.uraian', 'LIKE', "%$search%");
					$query->orWhere('skripsi_bimbingan.acc', 'LIKE', "%$search%");
				});
			})->editColumn('acc', function ($row) {
				if ($row->acc == 'acc') {
					return '<span class="badge badge-success">' . strtoupper($row->acc) . '</span>';
				} else if ($row->acc == 'belum acc') {
					return '<span class="badge badge-warning">' . strtoupper($row->acc) . '</span>';
				} else {
					return '<span class="badge badge-danger">' . strtoupper($row->acc) . '</span>';
				}
			})->addColumn('action', function ($row) {
				$response = '
				<div class="btn-group">
					<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Klik <span class="caret"></span></button>
					<ul class="dropdown-menu pull-right">';

				if ($row->acc == "belum acc") {
					$pembimbing = SkripsiPembimbing::where('mst_dosen_id', $row->mst_dosen_id)->where('jabatan', $row->jabatan)->first();
					$response .= '
						<li><a style="cursor:pointer" 
						data-toggle="modal" data-target="#modal_edit_bimbingan"
						data-id="' . $row->id . '"
						data-uraian="' . $row->uraian . '"
						data-tanggal="' . $row->tanggal . '"
						data-pembimbing_id="' . @$pembimbing->id . '"
						>Edit</a>
						</li>';
					$response .= '
						<li class="divider"></li>';
					$response .= '
						<li><a style="cursor:pointer" onclick="deleteForm(' . $row->id . ')">Delete</a></li>';
				} else {
					$response .= '
						<li><a style="cursor:pointer">Sudah diacc</a></li>';
				}
				$response .= '
					</ul>
				</div>';

				return $response;
			})
			->rawColumns(['acc', 'action'])
			->make(true);
	}

	public function updateBimbingan(Request $request, $id)
	{
		try {
			$cekJudul = ServiceSkripsi::cekJudulMahasiswa($id);
			if (!$cekJudul['status']) {
				return abort(500, $cekJudul['message']);
			}

			$request->validate([
				'id' => 'required',
				'uraian' => 'required',
				'tanggal' => 'required',
				'pembimbing_id' => 'required',
			]);

			$bimbingan = SkripsiBimbingan::find($request->id);

			if ($bimbingan->acc != 'belum acc') {
				return response()->json([
					'status' => false,
					'code' => 500,
					'message' => "Bimbingan tidak bisa diperbarui karena berstatus $bimbingan->acc",
					'color' => 'warning'
				]);
			}

			$pembimbing = SkripsiPembimbing::findOrFail($request->pembimbing_id);

			$bimbingan->tanggal = $request->tanggal;
			$bimbingan->uraian = $request->uraian;
			$bimbingan->mst_dosen_id = $pembimbing->mst_dosen_id;
			$bimbingan->jabatan = $pembimbing->jabatan;
			$bimbingan->save();

			return response()->json([
				'status' => true,
				'code' => 200,
				'message' => "Berhasil memperbarui bimbingan",
				'color' => 'success'
			]);
		} catch (\Throwable $th) {
			return response()->json([
				'status' => false,
				'code' => 500,
				'message' => $th->getMessage(),
				'color' => 'danger'
			]);
		}
	}

	public function tambahBimbingan(Request $request, $id)
	{
		try {
			$cekJudul = ServiceSkripsi::cekJudulMahasiswa($id);
			if (!$cekJudul['status']) {
				return abort(500, $cekJudul['message']);
			}

			$request->validate([
				'judul_id' => 'required',
				'uraian' => 'required',
				'tanggal' => 'required',
				'pembimbing_id' => 'required',
			]);

			$judul = SkripsiJudul::findOrFail($request->judul_id);
			$statusPengajuan = MhsSkripsiController::cekPengajuan($judul);

			if ($statusPengajuan != "Bimbingan" && $statusPengajuan != "Ujian Skripsi") {
				return response()->json([
					'status' => false,
					'code' => 500,
					'message' => "Tidak bisa menambahkan bimbingan karena status skripsi adalah $statusPengajuan",
					'color' => 'warning'
				]);
			}

			$pembimbing = SkripsiPembimbing::findOrFail($request->pembimbing_id);

			$bimbingan = new SkripsiBimbingan();
			$bimbingan->judul_id = $request->judul_id;
			$bimbingan->tanggal = $request->tanggal;
			$bimbingan->uraian = $request->uraian;
			$bimbingan->mst_dosen_id = $pembimbing->mst_dosen_id;
			$bimbingan->jabatan = $pembimbing->jabatan;
			$bimbingan->acc = 'belum acc';
			$bimbingan->save();

			return response()->json([
				'status' => true,
				'code' => 200,
				'message' => "Berhasil menambahkan bimbingan",
				'color' => 'success'
			]);
		} catch (\Throwable $th) {
			return response()->json([
				'status' => false,
				'code' => 500,
				'message' => $th->getMessage(),
				'color' => 'danger'
			]);
		}
	}

	public function deleteBimbingan($id, $idBimbingan)
	{
		try {
			$cekJudul = ServiceSkripsi::cekJudulMahasiswa($id);
			if (!$cekJudul['status']) {
				return abort(500, $cekJudul['message']);
			}

			$bimbingan = SkripsiBimbingan::find($idBimbingan);
			if ($bimbingan->acc != "belum acc") {
				return response()->json([
					'status' => false,
					'code' => 500,
					'title' => $bimbingan->tanggal,
					'text' => "Bimbingan gagal di hapus karena berstatus $bimbingan->acc",
					'type' => 'warning'
				]);
			}
			$bimbingan->delete();
			return response()->json([
				'status' => true,
				'code' => 200,
				'title' => $bimbingan->tanggal,
				'text' => 'Bimbingan berhasil di hapus',
				'type' => 'success'
			]);
		} catch (\Throwable $th) {
			//throw $th;
			return response()->json([
				'status' => false,
				'code' => 500,
				'title' => 'Error',
				'text' => 'Bimbingan gagal di hapus',
				'type' => 'error'
			]);
		}
	}

	public function storeDokumenSkripsi(Request $request, $id)
	{
		try {
			DB::beginTransaction();
			$cekJudul = ServiceSkripsi::cekJudulMahasiswa($id);
			if (!$cekJudul['status']) {
				return abort(500, $cekJudul['message']);
			}

			$cekJudul = ServiceSkripsi::cekJudulMahasiswa($id);
			if (!$cekJudul['status']) {
				return abort(500, $cekJudul['message']);
			}

			$request->validate([
				'judul_id' => 'required',
				'dokumen_skripsi' => 'required|file|mimes:pdf|max:5120'
			]);

			$mahasiswa = @Auth::user()->mahasiswa;
			$nim = @$mahasiswa->nim;
			$thAkademik = @ThAkademik::Aktif()->first();
			$dokumen_skripsi = $request->file('dokumen_skripsi');

			$judul = SkripsiJudul::find($request->judul_id);

			// upload new file
			$nama = uniqid() . "-$nim-$thAkademik->kode" . '.' . $dokumen_skripsi->getClientOriginalExtension();
			$lokasi = public_path('dokumen_skripsi/skripsi_final');
			$upload = $dokumen_skripsi->move($lokasi, $nama);
			if (!$upload) {
				return response()->json([
					'status' => false,
					'code' => 500,
					'message' => 'file tidak terupload',
					'color' => 'danger'
				]);
			}

			// delete old file
			if ($judul->dokumen_skripsi != null) {
				$oldFilePath = $lokasi . '/' . $judul->dokumen_skripsi;
				if (file_exists($oldFilePath)) {
					// Attempt to delete the older file
					if (!unlink($oldFilePath)) {
						return response()->json([
							'status' => false,
							'code' => 500,
							'message' => 'Failed to delete the old file.',
							'color' => 'danger'
						]);
					}
				}
			}

			$judul->dokumen_skripsi = $nama;
			$judul->save();

			DB::commit();
			return response()->json([
				'status' => true,
				'code' => 200,
				'message' => 'File skripsi berhasil ditambahkan',
				'color' => 'success'
			]);
		} catch (\Throwable $th) {
			DB::rollback();
			return response()->json([
				'status' => false,
				'code' => 500,
				'message' => $th->getMessage(),
				'color' => 'danger'
			]);
		}
	}

	public function downloadSkripsi($id)
	{
		try {
			$cekJudul = ServiceSkripsi::cekJudulMahasiswa($id);
			if (!$cekJudul['status']) {
				return abort(500, $cekJudul['message']);
			}

			$skripsi = SkripsiJudul::findOrFail($id);
			$path = asset('dokumen_skripsi/skripsi_final/' . $skripsi->dokumen_skripsi);
			return redirect($path);
		} catch (ModelNotFoundException $e) {
			if ($e instanceof ModelNotFoundException) {
				return abort(404);
			}
		} catch (\Throwable $th) {
			return $th->getMessage();
		}
	}
}
