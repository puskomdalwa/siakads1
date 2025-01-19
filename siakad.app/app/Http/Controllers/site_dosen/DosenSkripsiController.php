<?php
namespace App\Http\Controllers\site_dosen;

use Auth;
use Alert;
use App\Dosen;
use App\Prodi;
use App\BobotNilai;
use App\ThAkademik;
use App\SkripsiJudul;
use App\SkripsiBimbingan;
use App\SkripsiPengajuan;
use App\SkripsiPembimbing;
use App\SkripsiUjianSkripsi;
use Illuminate\Http\Request;
use App\SkripsiUjianProposal;
use Yajra\Datatables\Datatables;
use App\SkripsiUjianProposalDosen;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\site_admin\SkripsiController;
use App\Http\Controllers\site_mhs\MhsSkripsiController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DosenSkripsiController extends Controller
{
	private $title = 'Skripsi';
	private $redirect = 'dosen_skripsi';
	private $folder = 'site_dosen.dosen_skripsi';
	private $class = 'dosen_skripsi';

	public function index()
	{
		$title = $this->title;
		$redirect = $this->redirect;
		$folder = $this->folder;

		$list_thakademik = ThAkademik::orderBy('kode', 'DESC')->get();

		$prodi_id = @strtolower(Auth::user()->prodi->id);
		$list_prodi = Prodi::orderBy('kode', 'ASC')->get();

		$mst_dosen = Dosen::orderBy('nama')->get();

		$status = SkripsiController::getEnumValues('skripsi_pengajuan', 'status');
		return view(
			$folder . '.index',
			compact('title', 'redirect', 'folder', 'list_thakademik', 'list_prodi', 'mst_dosen', 'status')
		);
	}

	public function getData(Request $request)
	{
		$search = $request->search['value'];
		$row = SkripsiPengajuan::join('skripsi_judul', 'skripsi_judul.skripsi_pengajuan_id', '=', 'skripsi_pengajuan.id')
			->join('skripsi_pembimbing', 'skripsi_pembimbing.skripsi_pengajuan_id', '=', 'skripsi_pengajuan.id')
			->join('mst_th_akademik', 'mst_th_akademik.id', '=', 'skripsi_pengajuan.th_akademik_id')
			->join('mst_mhs', 'mst_mhs.nim', '=', 'skripsi_pengajuan.nim')
			->join('mst_prodi', 'mst_prodi.id', '=', 'mst_mhs.prodi_id')
			->when($request->prodi_id, function ($query) use ($request) {
				return $query->where('skripsi_pengajuan.prodi_id', $request->prodi_id);
			})
			->when($request->th_akademik_id, function ($query) use ($request) {
				return $query->where('skripsi_pengajuan.th_akademik_id', $request->th_akademik_id);
			})
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
				$query->where('skripsi_pembimbing.mst_dosen_id', @Auth::user()->dosen->id);
				$query->where('skripsi_pengajuan.status', 'Bimbingan');
				$query->where(function ($query) use ($search) {
					$query->orWhere('skripsi_judul.judul', 'LIKE', "%$search%");
					$query->orWhere('mst_mhs.nim', 'LIKE', "%$search%");
					$query->orWhere('mst_mhs.nama', 'LIKE', "%$search%");
					$query->orWhere('mst_prodi.nama', 'LIKE', "%$search%");
					$query->orWhere('mst_prodi.alias', 'LIKE', "%$search%");
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
				if ($statusPengajuan == "Selesai") {
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
					'<div><a style="color:black" href="' . route('dosen_skripsi.detail', ['id' => $row->id]) . '"><b>' . strip_tags($row->skripsi_judul_judul) . '</b></a></div>' .
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
					<li><a href="' . route('dosen_skripsi.detail', ['id' => $row->id]) . '" style="cursor:pointer">Detail</a></li>';
				$response .= '
					</ul>
				</div>';

				return $response;
			})
			->rawColumns(['skripsi_judul_judul', 'action'])
			->make(true);
	}
	public function getDataUjianProposal(Request $request)
	{
		$search = $request->search['value'];
		$row = SkripsiPengajuan::join('skripsi_judul', 'skripsi_judul.skripsi_pengajuan_id', '=', 'skripsi_pengajuan.id')
			->join('mst_th_akademik', 'mst_th_akademik.id', '=', 'skripsi_pengajuan.th_akademik_id')
			->join('mst_mhs', 'mst_mhs.nim', '=', 'skripsi_pengajuan.nim')
			->join('mst_prodi', 'mst_prodi.id', '=', 'mst_mhs.prodi_id')
			->join('skripsi_ujian_proposal', 'skripsi_ujian_proposal.skripsi_pengajuan_id', '=', 'skripsi_pengajuan.id')
			->join('skripsi_ujian_proposal_dosen', 'skripsi_ujian_proposal_dosen.ujian_proposal_id', '=', 'skripsi_ujian_proposal.id')
			->when($request->prodi_id, function ($query) use ($request) {
				return $query->where('skripsi_pengajuan.prodi_id', $request->prodi_id);
			})
			->when($request->th_akademik_id, function ($query) use ($request) {
				return $query->where('skripsi_pengajuan.th_akademik_id', $request->th_akademik_id);
			})
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
				'skripsi_ujian_proposal.status as skripsi_ujian_proposal_status',
			);

		return Datatables::of($row)
			->filter(function ($query) use ($search) {
				$query->where('skripsi_ujian_proposal_dosen.mst_dosen_id', Auth::user()->dosen->id);
				$query->where('skripsi_pengajuan.status', 'Ujian Proposal');
				$query->where('skripsi_judul.acc', 'Y');
				$query->where(function ($query) use ($search) {
					$query->orWhere('skripsi_judul.judul', 'LIKE', "%$search%");
					$query->orWhere('mst_mhs.nim', 'LIKE', "%$search%");
					$query->orWhere('mst_mhs.nama', 'LIKE', "%$search%");
					$query->orWhere('mst_prodi.nama', 'LIKE', "%$search%");
					$query->orWhere('mst_prodi.alias', 'LIKE', "%$search%");
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
				if ($statusPengajuan == "Selesai") {
					$color = 'success';
				}
				if ($statusPengajuan == "Tidak ACC") {
					$color = 'danger';
				}
				if ($statusPengajuan == 'Ditolak') {
					$color = 'danger';
				}

				$statusUjianProposal = $row->skripsi_ujian_proposal_status;
				if ($statusUjianProposal == "lolos") {
					$colorUjianProposal = 'success';
				}
				if ($statusUjianProposal == "tidak lolos") {
					$colorUjianProposal = 'danger';
				}
				if ($statusUjianProposal == "belum ujian") {
					$colorUjianProposal = 'warning';
				}

				$response =
					'<div>' .
					'<div class="text-' . @$color . '">* Status Skripsi: ' . $statusPengajuan .
					'<span style="margin-left:5px" class="badge badge-' . $colorUjianProposal . '">' . strtoupper($statusUjianProposal) . '</span>' .
					'</div>' .
					'<div><a style="color:black" href="' . route('dosen_skripsi.detail', ['id' => $row->id]) . '"><b>' . strip_tags($row->skripsi_judul_judul) . '</b></a></div>' .
					'<div>' .
					'<span class="text-gray" style="margin-right:5px">' . $row->nim . '</span>' .
					'<span class="text-gray" style="margin-right:5px"><i class="fa fa-circle" aria-hidden="true"></i> ' . strtoupper($row->mst_mhs_nama) . '</span>' .
					'<span class="badge badge-success" style="font-size: 1rem;margin-right:5px">' . strtoupper($row->mst_prodi_nama) . '</span>' .
					'<span class="badge badge-warning" style="font-size: 1rem">' . "$row->mst_th_akademik_nama ($row->mst_th_akademik_semester)" . '</span>' .
					'</div>' .
					'<div>';

				if ($statusPengajuan != "Tidak ACC") {
					// penguji
					$ujianProposal = SkripsiUjianProposal::where('skripsi_pengajuan_id', $row->skripsi_pengajuan_id)->first();
					$penguji = $ujianProposal->ujianProposalDosen;
					for ($i = 0; $i < count($penguji); $i++) {
						$pembimbingSkripsi = @$penguji[$i]->jabatan . ': ' . @$penguji[$i]->dosen->nama;
						if ($i != count($penguji) - 1) {
							$pembimbingSkripsi .= ',';
						}
						$response .= '<span class="text-gray" style="margin-right:5px">' . $pembimbingSkripsi . '</span>';
					}
					if (count($penguji) < 1) {
						$response .= '<span class="text-gray" style="margin-right:5px">Belum ada penguji</span>';
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
					<li><a href="' . route('dosen_skripsi.detail', ['id' => $row->id]) . '" style="cursor:pointer">Detail</a></li>';
				$response .= '
					</ul>
				</div>';

				return $response;
			})
			->rawColumns(['skripsi_judul_judul', 'action'])
			->make(true);
	}
	public function getDataUjianSkripsi(Request $request)
	{
		$search = $request->search['value'];
		$row = SkripsiPengajuan::join('skripsi_judul', 'skripsi_judul.skripsi_pengajuan_id', '=', 'skripsi_pengajuan.id')
			->join('mst_th_akademik', 'mst_th_akademik.id', '=', 'skripsi_pengajuan.th_akademik_id')
			->join('mst_mhs', 'mst_mhs.nim', '=', 'skripsi_pengajuan.nim')
			->join('mst_prodi', 'mst_prodi.id', '=', 'mst_mhs.prodi_id')
			->join('skripsi_ujian_skripsi', 'skripsi_ujian_skripsi.skripsi_pengajuan_id', '=', 'skripsi_pengajuan.id')
			->join('skripsi_ujian_skripsi_dosen', 'skripsi_ujian_skripsi_dosen.ujian_skripsi_id', '=', 'skripsi_ujian_skripsi.id')
			->when($request->prodi_id, function ($query) use ($request) {
				return $query->where('skripsi_pengajuan.prodi_id', $request->prodi_id);
			})
			->when($request->th_akademik_id, function ($query) use ($request) {
				return $query->where('skripsi_pengajuan.th_akademik_id', $request->th_akademik_id);
			})
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
				'skripsi_ujian_skripsi.status as skripsi_ujian_skripsi_status',
			);

		return Datatables::of($row)
			->filter(function ($query) use ($search) {
				$query->where('skripsi_ujian_skripsi_dosen.mst_dosen_id', Auth::user()->dosen->id);
				$query->where('skripsi_pengajuan.status', 'Ujian Skripsi');
				$query->where('skripsi_judul.acc', 'Y');
				$query->where(function ($query) use ($search) {
					$query->orWhere('skripsi_judul.judul', 'LIKE', "%$search%");
					$query->orWhere('mst_mhs.nim', 'LIKE', "%$search%");
					$query->orWhere('mst_mhs.nama', 'LIKE', "%$search%");
					$query->orWhere('mst_prodi.nama', 'LIKE', "%$search%");
					$query->orWhere('mst_prodi.alias', 'LIKE', "%$search%");
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
				if ($statusPengajuan == "Selesai") {
					$color = 'success';
				}
				if ($statusPengajuan == "Tidak ACC") {
					$color = 'danger';
				}
				if ($statusPengajuan == 'Ditolak') {
					$color = 'danger';
				}

				$statusUjianProposal = $row->skripsi_ujian_skripsi_status;
				if ($statusUjianProposal == "lolos") {
					$colorUjianProposal = 'success';
				}
				if ($statusUjianProposal == "tidak lolos") {
					$colorUjianProposal = 'danger';
				}
				if ($statusUjianProposal == "belum ujian") {
					$colorUjianProposal = 'warning';
				}

				$response =
					'<div>' .
					'<div class="text-' . @$color . '">* Status Skripsi: ' . $statusPengajuan .
					'<span style="margin-left:5px" class="badge badge-' . $colorUjianProposal . '">' . strtoupper($statusUjianProposal) . '</span>' .
					'</div>' .
					'<div><a style="color:black" href="' . route('dosen_skripsi.detail', ['id' => $row->id]) . '"><b>' . strip_tags($row->skripsi_judul_judul) . '</b></a></div>' .
					'<div>' .
					'<span class="text-gray" style="margin-right:5px">' . $row->nim . '</span>' .
					'<span class="text-gray" style="margin-right:5px"><i class="fa fa-circle" aria-hidden="true"></i> ' . strtoupper($row->mst_mhs_nama) . '</span>' .
					'<span class="badge badge-success" style="font-size: 1rem;margin-right:5px">' . strtoupper($row->mst_prodi_nama) . '</span>' .
					'<span class="badge badge-warning" style="font-size: 1rem">' . "$row->mst_th_akademik_nama ($row->mst_th_akademik_semester)" . '</span>' .
					'</div>' .
					'<div>';

				if ($statusPengajuan != "Tidak ACC") {
					// penguji
					$ujianSkripsi = SkripsiUjianSkripsi::where('skripsi_pengajuan_id', $row->skripsi_pengajuan_id)->first();
					$penguji = $ujianSkripsi->ujianSkripsiDosen;
					for ($i = 0; $i < count($penguji); $i++) {
						$pembimbingSkripsi = @$penguji[$i]->jabatan . ': ' . @$penguji[$i]->dosen->nama;
						if ($i != count($penguji) - 1) {
							$pembimbingSkripsi .= ',';
						}
						$response .= '<span class="text-gray" style="margin-right:5px">' . $pembimbingSkripsi . '</span>';
					}
					if (count($penguji) < 1) {
						$response .= '<span class="text-gray" style="margin-right:5px">Belum ada penguji</span>';
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
					<li><a href="' . route('dosen_skripsi.detail', ['id' => $row->id]) . '" style="cursor:pointer">Detail</a></li>';
				$response .= '
					</ul>
				</div>';

				return $response;
			})
			->rawColumns(['skripsi_judul_judul', 'action'])
			->make(true);
	}

	public function detail($id)
	{
		try {
			$title = $this->title . ' | Detail';
			$redirect = $this->redirect;
			$folder = $this->folder;
			$skripsi = SkripsiJudul::findOrFail($id);
			$mahasiswa = $skripsi->pengajuan->mahasiswa;
			$picture = $mahasiswa->user->picture;

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

			if ($statusPengajuan == "Ujian Proposal") {
				$cekUjianProposal = SkripsiUjianProposal::join(
					'skripsi_ujian_proposal_dosen',
					'skripsi_ujian_proposal_dosen.ujian_proposal_id',
					'=',
					'skripsi_ujian_proposal.id'
				)
					->where('skripsi_ujian_proposal.skripsi_pengajuan_id', $skripsi->pengajuan->id)
					->where('skripsi_ujian_proposal_dosen.mst_dosen_id', Auth::user()->dosen->id)->first();
				if (!$cekUjianProposal) {
					return redirect()->route('dosen_skripsi.index');
				}
			}
			if ($statusPengajuan == "Ujian Skripsi") {
				$cekUjianSkripsi = SkripsiUjianSkripsi::join(
					'skripsi_ujian_skripsi_dosen',
					'skripsi_ujian_skripsi_dosen.ujian_skripsi_id',
					'=',
					'skripsi_ujian_skripsi.id'
				)
					->where('skripsi_ujian_skripsi.skripsi_pengajuan_id', $skripsi->pengajuan->id)
					->where('skripsi_ujian_skripsi_dosen.mst_dosen_id', Auth::user()->dosen->id)->first();
				if (!$cekUjianSkripsi) {
					return redirect()->route('dosen_skripsi.index');
				}
			}
			if ($statusPengajuan == "Bimbingan") {
				$cekBimbingan = SkripsiPembimbing::where('skripsi_pengajuan_id', $skripsi->pengajuan->id)
					->where('mst_dosen_id', Auth::user()->dosen->id)->first();
				if (!$cekBimbingan) {
					return redirect()->route('dosen_skripsi.index');
				}
			}

			$pengajuan = $skripsi->pengajuan;
			$pembimbing = SkripsiPembimbing::where('skripsi_pengajuan_id', $pengajuan->id)->get();

			$skripsi->judul = strip_tags($skripsi->judul);
			$ujianProposal = SkripsiUjianProposal::where('skripsi_pengajuan_id', $pengajuan->id)->first();
			$penguji = [
				1 => @$ujianProposal->ujianProposalDosen ? $ujianProposal->ujianProposalDosen->where('jabatan', 'penguji 1')->first() : null,
				2 => @$ujianProposal->ujianProposalDosen ? $ujianProposal->ujianProposalDosen->where('jabatan', 'penguji 2')->first() : null
			];
			$ujianSkripsi = SkripsiUjianSkripsi::where('skripsi_pengajuan_id', $pengajuan->id)->first();
			$pengujiSkripsi = [
				1 => @$ujianSkripsi->ujianSkripsiDosen ? $ujianSkripsi->ujianSkripsiDosen->where('jabatan', 'penguji 1')->first() : null,
				2 => @$ujianSkripsi->ujianSkripsiDosen ? $ujianSkripsi->ujianSkripsiDosen->where('jabatan', 'penguji 2')->first() : null
			];

			$bobotNilai = BobotNilai::all();
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
					'pengajuan',
					'ujianSkripsi',
					'pengujiSkripsi',
					'bobotNilai',
					'penguji'
				)
			);
		} catch (ModelNotFoundException $e) {
			if ($e instanceof ModelNotFoundException) {
				return abort(404);
			}
		} catch (\Throwable $th) {
			return $th->getMessage();
		}
	}

	public function updateStatusUjianProposal(Request $request, $id)
	{
		try {
			$request->validate([
				'status' => 'required',
			]);

			$pengajuan = SkripsiPengajuan::findOrFail($id);
			$ujianProposal = SkripsiUjianProposal::where('skripsi_pengajuan_id', $pengajuan->id)->first();
			$ujianProposal->status = $request->status;
			$ujianProposal->save();

			return response()->json([
				'status' => true,
				'code' => 200,
				'message' => "Berhasil mengupdate status ujian proposal",
				'data' => [
					'status' => $request->status,
				],
				'color' => 'success'
			]);
		} catch (\Throwable $th) {
			return response()->json([
				'status' => false,
				'code' => 500,
				'message' => $th->getMessage(),
				'error' => $th->getMessage(),
				'color' => 'danger'
			]);
		}
	}

	public function getDataBimbingan(Request $request, $id)
	{
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
				$cekAcc = $row->mst_dosen_id == Auth::user()->dosen->id ? true : false;
				if (!$cekAcc) {
					return "";
				}
				$response = '
				<div class="btn-group">
					<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Klik <span class="caret"></span></button>
					<ul class="dropdown-menu pull-right">';

				if ($row->acc == "belum acc") {
					$response .= '
						<li><a style="cursor:pointer" onclick="updateStatus(' . $row->id . ', \'acc\')">ACC</a></li>';
					$response .= '
						<li><a style="cursor:pointer" onclick="updateStatus(' . $row->id . ', \'ditolak\')">DITOLAK</a></li>';
				} else if ($row->acc == "ditolak") {
					$response .= '
						<li><a style="cursor:pointer" onclick="updateStatus(' . $row->id . ', \'acc\')">ACC</a></li>';
				} else if ($row->acc == "acc") {
					$response .= '
						<li><a style="cursor:pointer" onclick="updateStatus(' . $row->id . ', \'ditolak\')">DITOLAK</a></li>';
				}
				$response .= '
					</ul>
				</div>';

				return $response;
			})
			->rawColumns(['acc', 'action'])
			->make(true);
	}

	public function updateStatusBimbingan(Request $request, $id)
	{
		try {
			$request->validate([
				'id' => 'required',
				'status' => 'required',
			]);

			$bimbingan = SkripsiBimbingan::findOrFail($request->id);
			$bimbingan->acc = $request->status;
			$bimbingan->save();

			return response()->json([
				'status' => true,
				'code' => 200,
				'title' => 'Sukses',
				'text' => "Bimbingan berhasil di$request->status",
				'type' => 'success',
				'data' => $request->all(),
			]);
		} catch (\Throwable $th) {
			return response()->json([
				'status' => false,
				'code' => 500,
				'title' => 'Error',
				'text' => 'Bimbingan gagal diperbarui',
				'error' => $th->getMessage(),
				'type' => 'error'
			]);
		}
	}

	public function updateStatusUjianSkripsi(Request $request, $id)
	{
		try {
			$request->validate([
				'status' => 'required',
			]);

			$pengajuan = SkripsiPengajuan::findOrFail($id);
			$ujianSkripsi = SkripsiUjianSkripsi::where('skripsi_pengajuan_id', $pengajuan->id)->first();
			$ujianSkripsi->status = $request->status;
			$ujianSkripsi->save();

			return response()->json([
				'status' => true,
				'code' => 200,
				'message' => "Berhasil mengupdate status ujian skripsi",
				'data' => [
					'status' => $request->status,
				],
				'color' => 'success'
			]);
		} catch (\Throwable $th) {
			return response()->json([
				'status' => false,
				'code' => 500,
				'message' => $th->getMessage(),
				'error' => $th->getMessage(),
				'color' => 'danger'
			]);
		}
	}

	public function downloadSkripsi($id)
	{
		try {
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

	public function downloadProposal($id)
	{
		try {
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

	public function simpanNilaiSkripsi(Request $request, $id)
	{
		try {

			$request->validate([
				'nilai_angka' => 'required',
				'nilai_huruf' => 'required'
			]);

			$skripsiPengajuan = SkripsiPengajuan::findOrFail($id);
			$skripsiPengajuan->nilai_angka = $request->nilai_angka;
			$skripsiPengajuan->nilai_huruf = strtoupper($request->nilai_huruf);
			$skripsiPengajuan->status = "Selesai";
			$skripsiPengajuan->save();

			return [
				'message' => "Nilai berhasil diinputkan, status skripsi menjadi SELESAI",
				'color' => 'success',
				'req' => $request->all(),
				'data' => $skripsiPengajuan
			];
		} catch (\Throwable $th) {
			//throw $th;
			return [
				'message' => "Gagal merubah status menjadi selesai",
				'color' => 'danger',
			];
		}
	}
	public function kosongkanNilaiSkripsi(Request $request, $id)
	{
		try {
			$skripsiPengajuan = SkripsiPengajuan::findOrFail($id);
			$skripsiPengajuan->nilai_angka = null;
			$skripsiPengajuan->nilai_huruf = null;
			$skripsiPengajuan->save();

			SkripsiPengajuan::where('id', $id)->update([
				'status' => "Ujian Skripsi"
			]);

			return [
				'status' => true,
				'message' => "Berhasil mengkosongkan nilai skripsi",
				'color' => 'success',
			];
		} catch (\Throwable $th) {
			//throw $th;
			return [
				'status' => false,
				'message' => "Gagal mengkosongkan nilai skripsi",
				'color' => 'danger',
			];
		}
	}
}
