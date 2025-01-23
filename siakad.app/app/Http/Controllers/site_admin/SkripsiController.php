<?php
namespace App\Http\Controllers\site_admin;

use App\Http\Services\ServiceSkripsi;
use Auth;
use App\Dosen;
use App\Prodi;
use App\BobotNilai;
use App\ThAkademik;
use App\SkripsiJudul;
use App\SkripsiBimbingan;
use App\SkripsiPengajuan;
use App\SkripsiPembimbing;
use Illuminate\Support\Str;
use App\SkripsiUjianSkripsi;
use Illuminate\Http\Request;
use App\SkripsiUjianProposal;
use Yajra\Datatables\Datatables;
use App\SkripsiUjianSkripsiDosen;
use App\SkripsiUjianProposalDosen;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\site_mhs\MhsSkripsiController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SkripsiController extends Controller
{

    private $title = 'Skripsi';
    private $redirect = 'skripsi';
    private $folder = 'skripsi';

    private $rules = [
        'kode' => 'required|string|max:10',
        'nama' => 'required|string|max:255',
        'param' => 'required|string|max:3',
    ];

    public function index()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $list_thakademik = ThAkademik::orderBy('kode', 'DESC')->get();

        $prodi_id = @strtolower(Auth::user()->prodi->id);
        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $mst_dosen = Dosen::orderBy('nama')->get();

        $status = self::getEnumValues('skripsi_pengajuan', 'status');
        return view(
            $folder . '.index',
            compact('title', 'redirect', 'folder', 'list_thakademik', 'list_prodi', 'mst_dosen', 'status')
        );
    }

    public function getData(Request $request)
    {
        $search = $request->search['value'];
        $prodi_id = $request->prodi_id;
        $th_akademik_id = $request->th_akademik_id;
        $status = $request->status;

        if ($request->ajax()) {
            $row = SkripsiPengajuan::join('mst_mhs', 'mst_mhs.nim', '=', 'skripsi_pengajuan.nim')
                ->join('ref as ref_jk', 'ref_jk.id', '=', 'mst_mhs.jk_id')
                ->join('ref as ref_kelas', 'ref_kelas.id', '=', 'mst_mhs.kelas_id')
                ->join('mst_prodi as mp', 'mp.id', '=', 'mst_mhs.prodi_id')
                ->join('mst_th_akademik as mta', 'mta.id', '=', 'skripsi_pengajuan.th_akademik_id')
                ->select(
                    'skripsi_pengajuan.*',
                    'mst_mhs.nama as mhs_nama',
                    'ref_jk.nama as mhs_jk',
                    'mp.nama as mhs_prodi',
                    'ref_kelas.nama as mhs_kelas',
                    'mta.kode as mta_kode'
                )
                ->when($status, function ($query) use ($status) {
                    return $query->where('skripsi_pengajuan.status', $status);
                })
                ->when($th_akademik_id, function ($query) use ($th_akademik_id) {
                    return $query->where('skripsi_pengajuan.th_akademik_id', $th_akademik_id);
                })
                ->when($prodi_id, function ($query) use ($prodi_id) {
                    return $query->where('mst_mhs.prodi_id', $prodi_id);
                });

            return Datatables::of($row)
                ->filter(function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->orWhere('mst_mhs.nim', 'LIKE', "%$search%")
                            ->orWhere('mst_mhs.nama', 'LIKE', "%$search%")
                            ->orWhere('mp.nama', 'LIKE', "%$search%")
                            ->orWhere('ref_kelas.nama', 'LIKE', "%$search%")
                            ->orWhere('ref_jk.nama', 'LIKE', "%$search%")
                            ->orWhere('skripsi_pengajuan.created_at', 'LIKE', "%$search%")
                            ->orWhere('skripsi_pengajuan.status', 'LIKE', "%$search%")
                            ->orWhere('mta.kode', 'LIKE', "%$search%");
                    });
                })
                ->addColumn('pembimbing', function ($row) {
                    $pembimbing = SkripsiPembimbing::where('skripsi_pengajuan_id', $row->id)->get();
                    $hasil = '';

                    if ($pembimbing) {
                        $h = '<ol>';
                        foreach ($pembimbing as $t) {
                            $h = $h . '<li>' . $t->dosen->nama . '<br>' . $t->jabatan . '</li>';
                        }

                        $h = $h . '</ol>';
                        $hasil = $h;
                    }
                    return $hasil;

                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'Baru') {
                        $hasil = '<span class="label label-secondary">' . $row->status . '</span>';
                    }

                    if ($row->status == 'Diperiksa') {
                        $hasil = '<span class="label label-warning">' . $row->status . '</span>';
                    }

                    if ($row->status == 'Ujian Proposal') {
                        $hasil = '<span class="label label-success">' . $row->status . '</span>';
                    }

                    if ($row->status == 'Ujian Skripsi') {
                        $hasil = '<span class="label label-success">' . $row->status . '</span>';
                    }

                    if ($row->status == 'Bimbingan') {
                        $hasil = '<span class="label label-success">' . $row->status . '</span>';
                    }

                    if ($row->status == 'Selesai') {
                        $hasil = '<span class="label label-success">' . $row->status . '</span>';
                    }

                    if ($row->status == 'Ditolak') {
                        $hasil = '<span class="label label-danger">' . $row->status . '</span>';
                    }

                    return $hasil;
                })

                ->addColumn('action', function ($row) {
                    $content = '<div class="btn-group">
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Klik <span class="caret"></span></button>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="' . route('skripsi.detail', $row->id) . '">Detail</a></li>';
                    if ($row->status == "Baru") {
                        $content .= '
                                    <li class="divider"></li>
                                    <li><a onclick="deleteForm(' . $row->id . ')">Hapus</a></li>';
                    }
                    $content .= '</ul>
                        </div>';

                    return $content;
                })
                ->rawColumns(['action', 'status', 'pembimbing'])
                ->make(true);
        }
    }

    public static function getEnumValues($table, $column)
    {
        $type = \DB::select(\DB::raw("SHOW COLUMNS FROM $table WHERE Field = '$column'"))[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = array();

        foreach (explode(',', $matches[1]) as $value) {
            $v = trim($value, "'");
            array_push($enum, $v);
        }

        return $enum;
    }
    public function destroy($id)
    {
        try {
            $pengajuan = SkripsiPengajuan::findOrFail($id);
            if ($pengajuan->status != "Baru") {
                return response()->json([
                    'title' => 'Delete Data Gagal karena status skripsi bukan BARU..!!',
                    'text' => $this->title,
                    'type' => 'error',
                ]);
            }

            $pengajuan->delete();
            return response()->json([
                'title' => 'Delete Data Success',
                'text' => $this->title,
                'type' => 'success',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'title' => 'Delete Data Gagal..!!',
                'text' => $this->title,
                'type' => 'error',
            ]);
        }
    }

    public function detail($id)
    {
        try {
            $title = $this->title . ' | Detail';
            $redirect = $this->redirect;
            $folder = $this->folder;

            $pengajuan = SkripsiPengajuan::findOrFail($id);
            $mahasiswa = $pengajuan->mahasiswa;
            $picture = $mahasiswa->user->picture;
            $statusPengajuan = $pengajuan->status;

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
            if ($statusPengajuan == 'Selesai') {
                $color = 'success';
            }

            $skripsiAcc = SkripsiJudul::where('skripsi_pengajuan_id', $pengajuan->id)
                ->where('acc', 'Y')
                ->first();

            if ($skripsiAcc) {
                $skripsiAcc->judul = strip_tags($skripsiAcc->judul);
            }

            $judul = $pengajuan->judul;
            $dosen = Dosen::leftJoin('skripsi_pembimbing', 'skripsi_pembimbing.mst_dosen_id', '=', 'mst_dosen.id')
                ->where('mst_dosen.prodi_id', $pengajuan->prodi_id)
                ->select('mst_dosen.id', 'mst_dosen.nama', 'mst_dosen.kode')
                ->addSelect(DB::raw('count(skripsi_pembimbing.mst_dosen_id) as jumlah'))
                ->groupBy('mst_dosen.id', 'mst_dosen.nama', 'mst_dosen.kode')
                ->get();

            $ujianProposal = SkripsiUjianProposal::where('skripsi_pengajuan_id', $pengajuan->id)->first();
            $ujianSkripsi = SkripsiUjianSkripsi::where('skripsi_pengajuan_id', $pengajuan->id)->first();

            $bobotNilai = BobotNilai::all();
            return view(
                $this->folder . '.detail',
                compact(
                    'title',
                    'redirect',
                    'folder',
                    'pengajuan',
                    'mahasiswa',
                    'picture',
                    'statusPengajuan',
                    'id',
                    'color',
                    'skripsiAcc',
                    'judul',
                    'dosen',
                    'ujianProposal',
                    'ujianSkripsi',
                    'bobotNilai'
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

    public function getDataJudul(Request $request, $id)
    {
        $search = $request->search['value'];
        $skripsiPengajuanId = $id;
        $row = SkripsiJudul::select('*');
        $skripsiPengajuan = SkripsiPengajuan::find($id);

        return Datatables::of($row)
            ->filter(function ($query) use ($search, $skripsiPengajuanId) {
                $query->where('skripsi_pengajuan_id', $skripsiPengajuanId);
                $query->where(function ($query) use ($search) {
                    $query->orWhere('judul', 'LIKE', "%$search%");
                    $query->orWhere('catatan', 'LIKE', "%$search%");
                    $query->orWhere('acc', 'LIKE', "%$search%");
                });
            })->editColumn('judul', function ($row) {
                return strip_tags($row->judul);
            })->editColumn('acc', function ($row) {
                if ($row->acc == 'Y') {
                    return '<span class="badge badge-success">' . strtoupper($row->acc) . '</span>';
                } else {
                    return '<span class="badge badge-danger">' . strtoupper($row->acc) . '</span>';
                }
            })->editColumn('dokumen_proposal', function ($row) {
                if ($row->dokumen_proposal) {
                    return '<a href="' . route('skripsi.downloadProposal', ['id' => $row->skripsi_pengajuan_id, 'judulId' => $row->id]) . '"><span class="badge badge-success">DOWNLOAD</span></a>';
                }
            })->editColumn('dokumen_skripsi', function ($row) {
                if ($row->dokumen_skripsi) {
                    return '<a href="' . route('skripsi.downloadSkripsi', ['id' => $row->skripsi_pengajuan_id, 'judulId' => $row->id]) . '"><span class="badge badge-success">DOWNLOAD</span></a>';
                }
            })->editColumn('catatan', function ($row) {
                $catatan = Str::limit($row->catatan, 20, '...');
                return '<a data-toggle="modal" data-target="#modal_catatan" data-catatan="' . $row->catatan . '" style="cursor:pointer">' . $catatan . '</a>';
            })->addColumn('action', function ($row) use ($skripsiPengajuan) {
                $response = '
				<div class="btn-group">
					<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Klik <span class="caret"></span></button>
					<ul class="dropdown-menu pull-right">';
                if ($row->acc == "T") {
                    if ($skripsiPengajuan->status == "Baru" || $skripsiPengajuan->status == "Diperiksa") {
                        $response .= '
                            <li><a style="cursor:pointer" onclick="accJudul(' . $row->id . ', \'Y\')">ACC</a></li>';
                    }
                    $response .= '
                        <li><a data-toggle="modal" data-target="#modal_edit"
                            data-id="' . $row->id . '"
                            data-judul="' . strip_tags($row->judul) . '"
                            >Edit</a></li>';
                    $response .= '
                        <li class="divider"></li>';
                    $response .= '
                        <li><a style="cursor:pointer" onclick="deleteForm(' . $row->id . ')">Delete</a></li>';
                } else {
                    $response .= '
                        <li><a data-toggle="modal" data-target="#modal_edit"
                            data-id="' . $row->id . '"
                            data-judul="' . strip_tags($row->judul) . '"
                            >Edit</a></li>';
                    if ($skripsiPengajuan->status == "Bimbingan") {
                        $response .= '
                            <li><a style="cursor:pointer" href="' . route(
                            'skripsi.bimbingan',
                            ['id' => $skripsiPengajuan->id, 'judulId' => $row->id]
                        ) . '">Bimbingan</a></li>';
                        $response .= '
                            <li class="divider"></li>';
                    }
                    if ($skripsiPengajuan->status == "Diperiksa") {
                        $response .= '
                        <li><a style="cursor:pointer" onclick="accJudul(' . $row->id . ', \'T\')">Tidak ACC</a></li>';
                    }
                }
                $response .= '
					</ul>
				</div>';

                return $response;
            })
            ->rawColumns(['acc', 'action', 'dokumen_proposal', 'catatan', 'dokumen_skripsi'])
            ->make(true);
    }

    public function downloadProposal($id, $judulId)
    {
        try {
            $skripsi = SkripsiJudul::findOrFail($judulId);
            $path = asset('dokumen_skripsi/' . $skripsi->dokumen_proposal);
            if ($skripsi->dokumen_proposal) {
                return redirect($path);
            }

            return "Belum upload file";
        } catch (ModelNotFoundException $e) {
            if ($e instanceof ModelNotFoundException) {
                return abort(404);
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function downloadSkripsi($id, $judulId)
    {
        try {
            $skripsi = SkripsiJudul::findOrFail($judulId);
            $path = asset('dokumen_skripsi/skripsi_final/' . $skripsi->dokumen_skripsi);
            if ($skripsi->dokumen_skripsi) {
                return redirect($path);
            }

            return "Belum upload file";
        } catch (ModelNotFoundException $e) {
            if ($e instanceof ModelNotFoundException) {
                return abort(404);
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function tambahJudul(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'judul' => 'required',
                'dokumen_proposal' => 'nullable|file|mimes:doc,docx,pdf|max:5120'
            ]);

            $pengajuan = SkripsiPengajuan::findOrFail($id);
            $mahasiswa = $pengajuan->mahasiswa;
            $nim = @$mahasiswa->nim;
            $thAkademik = @ThAkademik::Aktif()->first();
            $dokumen_proposal = $request->file('dokumen_proposal');

            $statusPengajuan = @$pengajuan->status;
            if ($statusPengajuan != "Baru" && $statusPengajuan != null) {
                return response()->json([
                    'status' => false,
                    'code' => 500,
                    'message' => "Tidak bisa menambahkan judul karena skripsi di tahun akademik ini berstatus $statusPengajuan",
                    'color' => 'warning'
                ]);
            }

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

    public function updateJudul(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'id' => 'required',
                'judul' => 'required',
                'dokumen_proposal' => 'nullable|file|mimes:doc,docx,pdf|max:5120',
                'dokumen_skripsi' => 'nullable|file|mimes:doc,docx,pdf|max:5120'
            ]);

            $pengajuan = SkripsiPengajuan::findOrFail($id);
            $mahasiswa = $pengajuan->mahasiswa;
            $nim = @$mahasiswa->nim;
            $thAkademik = @ThAkademik::Aktif()->first();
            $dokumen_proposal = $request->file('dokumen_proposal');
            $dokumen_skripsi = $request->file('dokumen_skripsi');

            $skripsiJudul = SkripsiJudul::find($request->id);

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
            if ($request->has('dokumen_skripsi')) {
                $nama = uniqid() . "-$nim-$thAkademik->kode" . '.' . $dokumen_skripsi->getClientOriginalExtension();
                $lokasi = public_path('dokumen_skripsi/skripsi_final');

                if ($skripsiJudul->dokumen_skripsi) {
                    $oldFilePath = $lokasi . '/' . $skripsiJudul->dokumen_skripsi;
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

                $upload = $dokumen_skripsi->move($lokasi, $nama);
                if (!$upload) {
                    return response()->json([
                        'status' => false,
                        'code' => 500,
                        'message' => 'file tidak terupload',
                        'data' => null,
                        'color' => 'danger'
                    ]);
                }
                $skripsiJudul->dokumen_skripsi = $nama;
            }

            $skripsiJudul->skripsi_pengajuan_id = $pengajuan->id;
            $skripsiJudul->judul = $request->judul;
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

    public function deleteJudul($id, $judulId)
    {
        try {
            DB::beginTransaction();
            $skripsi = SkripsiJudul::findOrFail($judulId);
            if ($skripsi->acc == "Y") {
                return response()->json([
                    'status' => false,
                    'code' => 500,
                    'title' => $skripsi->judul,
                    'text' => "Tidak dapat dihapus karena berstatus sudah ACC",
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
                'text' => $th->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function getDosenPembimbing($id)
    {
        try {
            $pengajuan = SkripsiPengajuan::findOrFail($id);
            $dosen = Dosen::leftJoin('skripsi_pembimbing', 'skripsi_pembimbing.mst_dosen_id', '=', 'mst_dosen.id')
                ->join('mst_prodi', 'mst_prodi.id', '=', 'mst_dosen.prodi_id')
                ->select('mst_dosen.id', 'mst_dosen.nama', 'mst_dosen.kode', 'mst_prodi.alias')
                ->addSelect(DB::raw('count(skripsi_pembimbing.mst_dosen_id) as jumlah'))
                ->groupBy('mst_dosen.id', 'mst_dosen.nama', 'mst_dosen.kode', 'mst_prodi.alias')
                ->get();
            $pembimbing1 = SkripsiPembimbing::where('skripsi_pengajuan_id', $id)
                ->where('jabatan', 'Pembimbing I')->first();
            $pembimbing2 = SkripsiPembimbing::where('skripsi_pengajuan_id', $id)
                ->where('jabatan', 'Pembimbing II')->first();
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => 'success',
                'data' => [
                    'dosen' => $dosen,
                    'pembimbing_1' => $pembimbing1,
                    'pembimbing_2' => $pembimbing2,
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'data' => null,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function getDosenPenguji($id)
    {
        try {
            $pengajuan = SkripsiPengajuan::findOrFail($id);
            $dosen = Dosen::leftJoin('skripsi_ujian_proposal_dosen', 'skripsi_ujian_proposal_dosen.mst_dosen_id', '=', 'mst_dosen.id')
                ->join('mst_prodi', 'mst_prodi.id', '=', 'mst_dosen.prodi_id')
                ->select('mst_dosen.id', 'mst_dosen.nama', 'mst_dosen.kode', 'mst_prodi.alias')
                ->addSelect(DB::raw('count(skripsi_ujian_proposal_dosen.mst_dosen_id) as jumlah'))
                ->groupBy('mst_dosen.id', 'mst_dosen.nama', 'mst_dosen.kode', 'mst_prodi.alias')
                ->get();
            $penguji1 = SkripsiUjianProposal::join('skripsi_ujian_proposal_dosen', 'skripsi_ujian_proposal_dosen.ujian_proposal_id', '=', 'skripsi_ujian_proposal.id')
                ->where('skripsi_pengajuan_id', $pengajuan->id)
                ->where('jabatan', 'penguji 1')
                ->first();
            $penguji2 = SkripsiUjianProposal::join('skripsi_ujian_proposal_dosen', 'skripsi_ujian_proposal_dosen.ujian_proposal_id', '=', 'skripsi_ujian_proposal.id')
                ->where('skripsi_pengajuan_id', $pengajuan->id)
                ->where('jabatan', 'penguji 2')
                ->first();
            $jadwal = SkripsiUjianProposal::where('skripsi_pengajuan_id', $pengajuan->id)->first();
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => 'success',
                'data' => [
                    'penguji_1' => $penguji1,
                    'penguji_2' => $penguji2,
                    'jadwal' => $jadwal,
                    'dosen' => $dosen,
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'data' => null,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function updateUjianProposal(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'jadwal' => 'required',
                'penguji_1_id' => 'required',
                'penguji_2_id' => 'required',
            ]);

            $cek = ServiceSkripsi::cekPenguji('proposal', $request->penguji_1_id, $request->penguji_2_id, $id);
            if (!$cek['status']) {
                return abort(500, $cek['message']);
            }

            $pengajuan = SkripsiPengajuan::findOrFail($id);
            $ujianProposal = SkripsiUjianProposal::where('skripsi_pengajuan_id', $pengajuan->id)->first();
            if (!$ujianProposal) {
                $ujianProposal = new SkripsiUjianProposal();
                $ujianProposal->skripsi_pengajuan_id = $pengajuan->id;
            }
            $ujianProposal->jadwal = $request->jadwal;
            $ujianProposal->status = 'belum ujian';
            $ujianProposal->save();

            $penguji1 = SkripsiUjianProposalDosen::where('ujian_proposal_id', $ujianProposal->id)
                ->where('jabatan', 'penguji 1')->first();
            if (!$penguji1) {
                $penguji1 = new SkripsiUjianProposalDosen();
                $penguji1->ujian_proposal_id = $ujianProposal->id;
            }
            $penguji1->mst_dosen_id = $request->penguji_1_id;
            $penguji1->jabatan = 'penguji 1';
            $penguji1->save();

            $penguji2 = SkripsiUjianProposalDosen::where('ujian_proposal_id', $ujianProposal->id)
                ->where('jabatan', 'penguji 2')->first();
            if (!$penguji2) {
                $penguji2 = new SkripsiUjianProposalDosen();
                $penguji2->ujian_proposal_id = $ujianProposal->id;
            }
            $penguji2->mst_dosen_id = $request->penguji_2_id;
            $penguji2->jabatan = 'penguji 2';
            $penguji2->save();

            $pengajuan->status = "Ujian Proposal";
            $pengajuan->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => "Berhasil mengupdate jadwal ujian proposal",
                'data' => [
                    'status_ujian_proposal' => $ujianProposal->status,
                ],
                'color' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $th->getMessage(),
                'error' => $th->getMessage(),
                'color' => 'danger'
            ]);
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

            // if ($request->status == "tidak lolos") {
            //     self::deleteUjianProposal($request, $id);
            // }
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

    public function deleteUjianProposal(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $pengajuan = SkripsiPengajuan::findOrFail($id);
            $ujianProposal = SkripsiUjianProposal::where('skripsi_pengajuan_id', $pengajuan->id)->first();
            if (!$ujianProposal) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'code' => 500,
                    'message' => "Belum ada ujian proposal yang diset",
                    'color' => 'danger'
                ]);
            }
            SkripsiUjianProposalDosen::where('ujian_proposal_id', $ujianProposal->id)
                ->delete();
            $ujianProposal->delete();

            $pengajuan->status = "Diperiksa";
            $pengajuan->save();
            DB::commit();
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => "Berhasil delete ujian proposal",
                'data' => [
                    'status_ujian_proposal' => 'Kosong',
                ],
                'color' => 'success'
            ]);
        } catch (ModelNotFoundException $e) {
            if ($e instanceof ModelNotFoundException) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'code' => 500,
                    'message' => "Belum ada ujian proposal yang diset",
                    'color' => 'danger'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $th->getMessage(),
                'color' => 'danger'
            ]);
        }
    }

    public function accJudul(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'judul_id' => 'required',
                'mst_dosen_id_1' => 'required',
                'mst_dosen_id_2' => 'required',
                'catatan' => 'nullable'
            ]);

            $cek = ServiceSkripsi::cekPembimbing($request->mst_dosen_id_1, $request->mst_dosen_id_2, $id);
            if (!$cek['status']) {
                return abort(500, $cek['message']);
            }

            $judul = SkripsiJudul::findOrFail($request->judul_id);
            $pengajuan = SkripsiPengajuan::findOrFail($id);

            SkripsiJudul::where('skripsi_pengajuan_id', $pengajuan->id)
                ->where('id', '!=', $request->judul_id)
                ->update([
                    'acc' => 'T'
                ]);
            $judul->acc = 'Y';
            $judul->catatan = $request->catatan;
            $judul->save();

            $pembimbing1 = SkripsiPembimbing::where('skripsi_pengajuan_id', $pengajuan->id)
                ->where('jabatan', 'Pembimbing I')->first();

            if (!$pembimbing1) {
                $pembimbing1 = new SkripsiPembimbing();
                $pembimbing1->jabatan = 'Pembimbing I';
            }
            $pembimbing1->skripsi_pengajuan_id = $pengajuan->id;
            $pembimbing1->mst_dosen_id = $request->mst_dosen_id_1;
            $pembimbing1->user_id = Auth::user()->id;
            $pembimbing1->save();

            $pembimbing2 = SkripsiPembimbing::where('skripsi_pengajuan_id', $pengajuan->id)
                ->where('jabatan', 'Pembimbing II')->first();

            if (!$pembimbing2) {
                $pembimbing2 = new SkripsiPembimbing();
                $pembimbing2->jabatan = 'Pembimbing II';
            }
            $pembimbing2->skripsi_pengajuan_id = $pengajuan->id;
            $pembimbing2->mst_dosen_id = $request->mst_dosen_id_2;
            $pembimbing2->user_id = Auth::user()->id;
            $pembimbing2->save();

            $pengajuan->status = 'Bimbingan';
            $pengajuan->save();
            DB::commit();
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => "Berhasil acc judul",
                'data' => [
                    'judul' => $judul
                ],
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

    public function updateStatusJudul(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'status' => 'required',
                'judul_id' => 'nullable',
            ]);

            if ($request->status == "Y") {

                $judul = SkripsiJudul::findOrFail($request->judul_id);
                $pengajuan = SkripsiPengajuan::findOrFail($id);

                SkripsiJudul::where('skripsi_pengajuan_id', $pengajuan->id)
                    ->where('id', '!=', $request->judul_id)
                    ->update([
                        'acc' => 'T'
                    ]);
                $judul->acc = 'Y';
                $judul->catatan = $request->catatan;
                $judul->save();

                if ($pengajuan->status == "Baru") {
                    $pengajuan->status = "Diperiksa";
                }
                $pengajuan->save();

                DB::commit();
                return response()->json([
                    'status' => true,
                    'code' => 200,
                    'message' => 'Berhasil acc judul',
                    'data' => [
                        'judul' => $judul
                    ],
                    'color' => 'success',
                    'status_pengajuan' => $pengajuan->status,
                ]);
            }
            if ($request->status == "T") {

                $judul = SkripsiJudul::findOrFail($request->judul_id);
                $pengajuan = SkripsiPengajuan::findOrFail($id);

                SkripsiJudul::where('skripsi_pengajuan_id', $pengajuan->id)
                    ->update([
                        'acc' => 'T'
                    ]);

                $pengajuan->status = "Baru";
                $pengajuan->save();
                DB::commit();
                return response()->json([
                    'status' => true,
                    'code' => 200,
                    'message' => 'Berhasil tolak judul',
                    'data' => [
                        'judul' => null
                    ],
                    'color' => 'success'
                ]);
            }
            return abort(500, 'tidak ada status yang di pilih');
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

    public function getDosenPengujiSkripsi($id)
    {
        try {
            $pengajuan = SkripsiPengajuan::findOrFail($id);
            $dosen = Dosen::leftJoin('skripsi_ujian_skripsi_dosen', 'skripsi_ujian_skripsi_dosen.mst_dosen_id', '=', 'mst_dosen.id')
                ->join('mst_prodi', 'mst_prodi.id', '=', 'mst_dosen.prodi_id')
                ->select('mst_dosen.id', 'mst_dosen.nama', 'mst_dosen.kode', 'mst_prodi.alias')
                ->addSelect(DB::raw('count(skripsi_ujian_skripsi_dosen.mst_dosen_id) as jumlah'))
                ->groupBy('mst_dosen.id', 'mst_dosen.nama', 'mst_dosen.kode', 'mst_prodi.alias')
                ->get();
            $penguji1 = SkripsiUjianSkripsi::join('skripsi_ujian_skripsi_dosen', 'skripsi_ujian_skripsi_dosen.ujian_skripsi_id', '=', 'skripsi_ujian_skripsi.id')
                ->where('skripsi_pengajuan_id', $pengajuan->id)
                ->where('jabatan', 'penguji 1')
                ->first();
            $penguji2 = SkripsiUjianSkripsi::join('skripsi_ujian_skripsi_dosen', 'skripsi_ujian_skripsi_dosen.ujian_skripsi_id', '=', 'skripsi_ujian_skripsi.id')
                ->where('skripsi_pengajuan_id', $pengajuan->id)
                ->where('jabatan', 'penguji 2')
                ->first();
            $jadwal = SkripsiUjianSkripsi::where('skripsi_pengajuan_id', $pengajuan->id)->first();
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => 'success',
                'data' => [
                    'penguji_1' => $penguji1,
                    'penguji_2' => $penguji2,
                    'jadwal' => $jadwal,
                    'dosen' => $dosen,
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'data' => null,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function updateUjianSkripsi(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'jadwal' => 'required',
                'penguji_1_id' => 'required',
                'penguji_2_id' => 'required',
            ]);

            $cek = ServiceSkripsi::cekPenguji('skripsi', $request->penguji_1_id, $request->penguji_2_id, $id);
            if (!$cek['status']) {
                return abort(500, $cek['message']);
            }

            $pengajuan = SkripsiPengajuan::findOrFail($id);
            $ujianSkripsi = SkripsiUjianSkripsi::where('skripsi_pengajuan_id', $pengajuan->id)->first();
            if (!$ujianSkripsi) {
                $ujianSkripsi = new SkripsiUjianSkripsi();
                $ujianSkripsi->skripsi_pengajuan_id = $pengajuan->id;
            }
            $ujianSkripsi->jadwal = $request->jadwal;
            $ujianSkripsi->status = 'belum ujian';
            $ujianSkripsi->save();

            $penguji1 = SkripsiUjianSkripsiDosen::where('ujian_skripsi_id', $ujianSkripsi->id)
                ->where('jabatan', 'penguji 1')->first();
            if (!$penguji1) {
                $penguji1 = new SkripsiUjianSkripsiDosen();
                $penguji1->ujian_skripsi_id = $ujianSkripsi->id;
            }
            $penguji1->mst_dosen_id = $request->penguji_1_id;
            $penguji1->jabatan = 'penguji 1';
            $penguji1->save();

            $penguji2 = SkripsiUjianSkripsiDosen::where('ujian_skripsi_id', $ujianSkripsi->id)
                ->where('jabatan', 'penguji 2')->first();
            if (!$penguji2) {
                $penguji2 = new SkripsiUjianSkripsiDosen();
                $penguji2->ujian_skripsi_id = $ujianSkripsi->id;
            }
            $penguji2->mst_dosen_id = $request->penguji_2_id;
            $penguji2->jabatan = 'penguji 2';
            $penguji2->save();

            $pengajuan->status = "Ujian Skripsi";
            $pengajuan->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => "Berhasil mengupdate jadwal ujian skripsi",
                'data' => [
                    'status_ujian_skripsi' => $ujianSkripsi->status,
                ],
                'color' => 'success',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $th->getMessage(),
                'error' => $th->getMessage(),
                'color' => 'danger'
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

    public function deleteUjianSkripsi(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $pengajuan = SkripsiPengajuan::findOrFail($id);
            $ujianSkripsi = SkripsiUjianSkripsi::where('skripsi_pengajuan_id', $pengajuan->id)->first();
            if (!$ujianSkripsi) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'code' => 500,
                    'message' => "Belum ada ujian skripsi yang diset",
                    'color' => 'danger'
                ]);
            }
            SkripsiUjianSkripsiDosen::where('ujian_skripsi_id', $ujianSkripsi->id)
                ->delete();
            $ujianSkripsi->delete();

            $pengajuan->status = "Bimbingan";
            $pengajuan->save();
            DB::commit();
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => "Berhasil delete ujian skripsi",
                'data' => [
                    'status_ujian_skripsi' => 'Kosong',
                ],
                'color' => 'success'
            ]);
        } catch (ModelNotFoundException $e) {
            if ($e instanceof ModelNotFoundException) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'code' => 500,
                    'message' => "Belum ada ujian skripsi yang diset",
                    'color' => 'danger'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $th->getMessage(),
                'color' => 'danger'
            ]);
        }
    }

    public function deletePembimbing(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $pengajuan = SkripsiPengajuan::findOrFail($id);
            SkripsiPembimbing::where('skripsi_pengajuan_id', $id)
                ->delete();

            $pengajuan->status = "Ujian Proposal";
            $pengajuan->save();
            DB::commit();
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => "Berhasil delete pembimbing",
                'color' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => "Gagal delete pembimbing",
                'color' => 'success'
            ]);
        }
    }

    public function bimbingan($id, $judulId)
    {
        try {
            $title = $this->title;
            $redirect = $this->redirect;
            $folder = $this->folder;

            $pengajuan = SkripsiPengajuan::findOrFail($id);
            if (!$pengajuan) {
                return abort(404);
            }
            $judul = SkripsiJudul::findOrFail($judulId);
            if (!$judul) {
                return abort(404);
            }

            $mahasiswa = $pengajuan->mahasiswa;
            $statusPengajuan = $pengajuan->status;

            $pembimbing = SkripsiPembimbing::where('skripsi_pengajuan_id', $id)->get();

            return view(
                $this->folder . '.bimbingan',
                compact(
                    'title',
                    'redirect',
                    'folder',
                    'id',
                    'judulId',
                    'mahasiswa',
                    'judul',
                    'statusPengajuan',
                    'pembimbing',
                )
            );
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getDataBimbingan(Request $request, $id, $judulId)
    {
        $search = $request->search['value'];
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
                    <li><a style="cursor:pointer" onclick="updateStatus(' . $row->id . ', \'acc\')">ACC</a></li>';
                    $response .= '
                    <li><a style="cursor:pointer" onclick="updateStatus(' . $row->id . ', \'ditolak\')">DITOLAK</a></li>';
                    $response .= '
						<li class="divider"></li>';
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
                } else if ($row->acc == "acc") {
                    $response .= '
                    <li><a style="cursor:pointer" onclick="updateStatus(' . $row->id . ', \'ditolak\')">DITOLAK</a></li>';
                } else if ($row->acc == "ditolak") {
                    $response .= '
                    <li><a style="cursor:pointer" onclick="updateStatus(' . $row->id . ', \'acc\')">ACC</a></li>';
                }
                $response .= '
					</ul>
				</div>';

                return $response;
            })
            ->rawColumns(['acc', 'action'])
            ->make(true);
    }

    public function updateBimbingan(Request $request, $id, $judulId)
    {
        try {
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

    public function tambahBimbingan(Request $request, $id, $judulId)
    {
        try {
            $request->validate([
                'judul_id' => 'required',
                'uraian' => 'required',
                'tanggal' => 'required',
                'pembimbing_id' => 'required',
            ]);

            $judul = SkripsiJudul::findOrFail($request->judul_id);
            $statusPengajuan = MhsSkripsiController::cekPengajuan($judul);

            if ($statusPengajuan != "Bimbingan") {
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

    public function deleteBimbingan($id, $judulId, $idBimbingan)
    {
        try {
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
                'error' => $th->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function updateStatusBimbingan(Request $request, $id, $judulId)
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
