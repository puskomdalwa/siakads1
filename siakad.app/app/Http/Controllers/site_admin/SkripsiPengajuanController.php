<?php
namespace App\Http\Controllers\site_admin;

use App\Dosen;
use App\Http\Controllers\Controller;
use App\Prodi;
use App\SkripsiJudul;
use App\SkripsiPembimbing;
use App\SkripsiPengajuan;
use App\ThAkademik;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class SkripsiPengajuanController extends Controller
{

    private $title = 'Pengajuan Skripsi';
    private $redirect = 'skripsi_pengajuan';
    private $folder = 'skripsi_pengajuan';
    private $class = 'skripsi_pengajuan';
    private $table = 'skripsi_pengajuan';

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
        $row = SkripsiPengajuan::join('mst_mhs', 'mst_mhs.nim', '=', 'skripsi_pengajuan.nim')
            ->join('ref as ref_jk', 'ref_jk.id', '=', 'mst_mhs.jk_id')
            ->join('ref as ref_kelas', 'ref_kelas.id', '=', 'mst_mhs.kelas_id')
            ->join('mst_prodi as mp', 'mp.id', '=', 'mst_mhs.prodi_id')
            ->select('skripsi_pengajuan.*', 'mst_mhs.nama as mhs_nama', 'ref_jk.nama as mhs_jk', 'mp.nama as mhs_prodi',
                'ref_kelas.nama as mhs_kelas')
            ->where('skripsi_pengajuan.id', 10)
            ->get();
        // dd($row);

        return view($folder . '.index',
            compact('title', 'redirect', 'folder', 'list_thakademik', 'list_prodi', 'mst_dosen')
        );
    }

    public function getData(Request $request)
    {
        $search = $request->search['value'];
        $prodi_id = $request->prodi_id;
        $th_akademik_id = $request->th_akademik_id;

        if ($request->ajax()) {
            $row = SkripsiPengajuan::join('mst_mhs', 'mst_mhs.nim', '=', 'skripsi_pengajuan.nim')
                ->join('ref as ref_jk', 'ref_jk.id', '=', 'mst_mhs.jk_id')
                ->join('ref as ref_kelas', 'ref_kelas.id', '=', 'mst_mhs.kelas_id')
                ->join('mst_prodi as mp', 'mp.id', '=', 'mst_mhs.prodi_id')
                ->select('skripsi_pengajuan.*', 'mst_mhs.nama as mhs_nama', 'ref_jk.nama as mhs_jk', 'mp.nama as mhs_prodi',
                    'ref_kelas.nama as mhs_kelas')
                ->where('skripsi_pengajuan.th_akademik_id', $th_akademik_id)
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
                            ->orWhere('skripsi_pengajuan.status', 'LIKE', "%$search%");
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
                        $hasil = '<span class="label label-danger">' . $row->status . '</span>';
                    }

                    if ($row->status == 'Diperiksa') {
                        $hasil = '<span class="label label-warning">' . $row->status . '</span>';
                    }

                    if ($row->status == 'Bimbingan') {
                        $hasil = '<span class="label label-success">' . $row->status . '</span>';
                    }

                    return $hasil;
                })

                ->addColumn('action', function ($row) {
                    $content = '<div class="btn-group">
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Klik <span class="caret"></span></button>
                            <ul class="dropdown-menu pull-right">
                                <li><a onclick="getStatus(' . $row->id . ')">Proses</a></li>';
                    if ($row->status == 'Bimbingan') {
                        $content .= '<li><a onclick="PembimbingForm(' . $row->id . ')" data-toogle="modal"
                        data-target="#modalPembimbing">Pembimbing</a></li>';
                    }
                    $content .= '
                                <li class="divider"></li>
                                <li><a onclick="deleteForm(' . $row->id . ')">Hapus</a></li>
                            </ul>
                        </div>';

                    return $content;
                })
                ->addColumn('details_url', function ($row) {
                    return url($this->folder . '/getDetailsData/' . $row->id);
                })
                ->rawColumns(['action', 'status', 'pembimbing'])
                ->make(true);
        }
    }

    public function getDetailsData($id)
    {
        $row = SkripsiJudul::
            where('skripsi_pengajuan_id', $id)
            ->get();

        return Datatables::of($row)
            ->addColumn('txt_judul', function ($row) {
                return $row->judul . '<br/><i><span class="label label-info text-right">' . $row->catatan . '</span></i>';
            })
            ->addColumn('txt_acc', function ($row) {
                if ($row->acc == 'T') {
                    $icon = '<i class="fa fa-times text-danger"></i>';
                } else {
                    $icon = '<i class="fa fa-check text-success"></i>';
                }
                return $icon;
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-xs">
			<a onclick="CatatanForm(' . $row->id . ')" class="btn btn-xs btn-alt btn-info"
			data-toogle="modal" data-target="#modalCatatan">
			<i class="fa fa-edit"></i> Catatan </a>
			</div>';
            })
            ->rawColumns(['action', 'txt_judul', 'txt_acc'])
            ->make(true);
    }

    public function getStatus(Request $request)
    {
        $id = $request->id;
        $pengajuan = SkripsiPengajuan::where('id', $id)->first();

        if (!empty($pengajuan)) {
            $status = $pengajuan->status;
            if ($status == 'Baru') {
                $status = 'Diperiksa';
            } else {
                $status = 'Baru';
            }

            $pengajuan->status = $status;
            $pengajuan->user_id = Auth::user()->id;
            $pengajuan->save();

            $info = array(
                'title' => $this->title,
                'text' => 'Ubah Status ' . $status . ' Success.',
                'type' => 'success',
            );
            return response()->json($info);
        }

        $info = array(
            'title' => $this->title,
            'text' => 'Error Data.',
            'type' => 'error',
        );
        return response()->json($info);
    }

    public function destroy($id)
    {
        $data = SkripsiPengajuan::where('id', $id)->first();
        if (!empty($data)) {
            $data->delete();
            $judul = SkripsiJudul::where('skripsi_pengajuan_id', $id)->delete();
            $pembimbing = SkripsiPembimbing::where('skripsi_pengajuan_id', $id)->delete();

            return response()->json([
                'title' => 'Delete Data Success',
                'text' => $this->title,
                'type' => 'success',
            ]);
        }

        return response()->json([
            'title' => 'Delete Data Gagal..!!',
            'text' => $this->title,
            'type' => 'error',
        ]);
    }

    public function editCatatan($id)
    {
        $data = SkripsiJudul::where('id', $id)->first();
        return $data;
    }

    public function editPembimbing($id)
    {
        $pengajuan = SkripsiPengajuan::where('id', $id)->first();
        $judul = SkripsiJudul::where('skripsi_pengajuan_id', $id)
            ->where('acc', 'Y')
            ->first();

        if (!empty($judul)) {
            $info = array(
                'title' => $this->title,
                'text' => 'NIM ' . $pengajuan->nim . ' Nama ' . $pengajuan->mahasiswa->nama,
                'judul' => $judul->judul,
                'type' => 'success',
            );
            return response()->json($info);
        }

        $info = array(
            'title' => $this->title,
            'text' => 'NIM ' . $pengajuan->nim . ' Nama ' . $pengajuan->mahasiswa->nama . ' judul belum ada di ACC.',
            'judul' => '',
            'type' => 'error',
        );
        return response()->json($info);
    }

    public function listPembimbing($id)
    {
        $data = SkripsiPembimbing::where('skripsi_pengajuan_id', $id)->get();
        return view($this->folder . '.list_dosen', compact('data'));
    }

    public function simpancatatan(Request $request)
    {
        $judul_id = $request->judul_id;
        $judul = $request->judul;
        $catatan = $request->catatan;
        $acc = $request->acc;

        $data = SkripsiJudul::where('id', $judul_id)->first();
        $data->catatan = $catatan;
        $data->acc = $acc;
        $data->user_id = Auth::user()->id;
        $data->save();

        if ($acc == 'Y') {
            $skripsi = SkripsiPengajuan::where('id', $data->skripsi_pengajuan_id)->first();
            $skripsi->status = 'Bimbingan';
            $skripsi->user_id = Auth::user()->id;
            $skripsi->save();
        } else {
            $skripsi = SkripsiPengajuan::where('id', $data->skripsi_pengajuan_id)->first();
            $skripsi->status = 'Baru';
            $skripsi->user_id = Auth::user()->id;
            $skripsi->save();
        }

        $info = array(
            'title' => $this->title,
            'info' => 'Catatan Berhasil Disimpan.',
            'status' => 'success',
        );
        return response()->json($info);
    }

    public function simpanpembimbing(Request $request)
    {
        $id = $request->id;
        $pembimbing_id = $request->pembimbing_id;
        $mst_dosen_id = $request->mst_dosen_id;
        $jabatan = $request->jabatan;

        $data = SkripsiPembimbing::where('skripsi_pengajuan_id', $id)
            ->where('id', $pembimbing_id)
            ->first();

        if (empty($data)) {
            $data = new SkripsiPembimbing;
        }

        $data->skripsi_pengajuan_id = $id;
        $data->mst_dosen_id = $mst_dosen_id;

        $data->jabatan = $jabatan;
        $data->user_id = Auth::user()->id;
        $data->save();

        $info = array(
            'title' => $this->title,
            'info' => 'Catatan Berhasil Disimpan.',
            'status' => 'success',
        );
        return response()->json($info);
    }

    public function hapusPembimbing($id)
    {
        SkripsiPembimbing::where('id', $id)->delete();
        return response()->json([
            'title' => 'Delete Data Success',
            'text' => $this->title,
            'type' => 'success',
        ]);
    }
}
