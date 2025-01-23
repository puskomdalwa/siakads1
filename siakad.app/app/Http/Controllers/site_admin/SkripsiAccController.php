<?php
namespace App\Http\Controllers\site_admin;

use App\Dosen;
use App\Http\Controllers\Controller;
use App\Prodi;
use App\SkripsiJudul;
use App\SkripsiPembimbing;
use App\ThAkademik;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class SkripsiAccController extends Controller
{

    private $title = 'Pengajuan ACC';
    private $redirect = 'skripsi_acc';
    private $folder = 'skripsi_acc';
    private $class = 'skripsi_acc';
    private $table = 'skripsi_acc';

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

        return view($folder . '.index',
            compact('title', 'redirect', 'folder', 'list_thakademik', 'list_prodi')
        );
    }

    public function getData(Request $request)
    {
        $th_akademik_id = $request->th_akademik_id;
        $prodi_id = $request->prodi_id;

        if ($request->ajax()) {
            $row = SkripsiJudul::join('skripsi_pengajuan as sp', 'sp.id', '=', 'skripsi_judul.skripsi_pengajuan_id')
                ->join('mst_mhs', 'mst_mhs.nim', '=', 'sp.nim')
                ->join('ref as ref_jk', 'ref_jk.id', '=', 'mst_mhs.jk_id')
                ->join('mst_prodi as mp', 'mp.id', '=', 'mst_mhs.prodi_id')
                ->select('skripsi_judul.*', 'sp.tanggal as tgl_pengajuan', 'mst_mhs.nim as mhs_nim', 'mst_mhs.nama as mhs_nama',
                    'ref_jk.nama as mhs_jk', 'mp.nama as mhs_prodi')
                ->where('skripsi_judul.acc', 'Y')
                ->where('sp.th_akademik_id', $th_akademik_id)
                ->when($prodi_id, function ($query) use ($prodi_id) {
                    $query->where('mst_mhs.prodi_id', $prodi_id);
                });

            return Datatables::of($row)
                ->addColumn('tgl_acc', function ($row) {
                    return @tgl_Nojam($row->updated_at);
                })
                ->addColumn('pembimbing', function ($row) {
                    $pembimbing = SkripsiPembimbing::where('skripsi_pengajuan_id', $row->skripsi_pengajuan_id)->get();
                    if ($pembimbing) {
                        $h = '<ol>';
                        foreach ($pembimbing as $r) {
                            $h = $h . '<li>' . $r->dosen->nama . ' => ' . $r->jabatan . '</li>';
                        }
                        $h = $h . '</ol>';
                    } else {
                        $h = '';
                    }
                    return $h;

                })
                ->rawColumns(['judul', 'pembimbing'])
                ->make(true);
        }
    }
}
