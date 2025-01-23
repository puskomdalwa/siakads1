<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TranskripCetak;
use Auth;
use Yajra\Datatables\Datatables;
use Alert;
use App\Prodi;

class TranskripController extends Controller
{
    private $title = 'Pengajuan Cetak Transkrip Nilai Sementara';
    private $redirect = 'transkrip';
    private $folder = 'transkrip';
    private $class = 'transkrip';
    private $table = 'transkrip';

    public function index()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;
        $prodi_id = @strtolower(Auth::user()->prodi->id);

        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            // $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        // $row = TranskripCetak::join('mst_th_akademik as th_akademik', 'th_akademik.id', '=', 'transkrip_cetak.th_akademik_id')
        //             ->join('mst_mhs as mhs', 'mhs.nim', '=', 'transkrip_cetak.nim')
        //             ->join('ref as jenis_kelamin', 'jenis_kelamin.id', '=', 'mhs.jk_id')
        //             ->join('mst_prodi as prodi', 'prodi.id', '=', 'mhs.prodi_id')
        //             ->join('mst_th_akademik as th_akademik_mhs', 'th_akademik_mhs.id', '=', 'mhs.th_akademik_id')
        //             ->select('transkrip_cetak.*', 'th_akademik.kode as th_akademik', 'mhs.nama as mhs_nama',
        //                 'jenis_kelamin.nama as mhs_jk', 'prodi.nama as mhs_prodi', 'th_akademik_mhs.kode as tahun_akademik_mhs')
        //             ->get()[0];
        // dd($row);

        return view($folder . '.index', compact('title', 'redirect', 'folder', 'list_prodi'));
    }

    public function getData(Request $request)
    {

        $prodi = @strtolower(Auth::user()->prodi->id);

        if ($prodi) {
            $prodi_id = $prodi;
        } else {
            $prodi_id = $request->prodi_id;
        }

        $search = $request->search['value'];

        // $row = TranskripCetak::where('prodi_id',$prodi_id)
        // ->orderBy('created_at','DESC')
        // ->get();

        $row = TranskripCetak::join('mst_th_akademik as th_akademik', 'th_akademik.id', '=', 'transkrip_cetak.th_akademik_id')
            ->join('mst_mhs as mhs', 'mhs.nim', '=', 'transkrip_cetak.nim')
            ->join('ref as jenis_kelamin', 'jenis_kelamin.id', '=', 'mhs.jk_id')
            ->join('mst_prodi as prodi', 'prodi.id', '=', 'mhs.prodi_id')
            ->join('mst_th_akademik as th_akademik_mhs', 'th_akademik_mhs.id', '=', 'mhs.th_akademik_id')
            ->select(
                'transkrip_cetak.*',
                'th_akademik.kode as thakademik',
                'mhs.nama as mhs_nama',
                'jenis_kelamin.nama as mhs_jk',
                'prodi.nama as mhs_prodi',
                'th_akademik_mhs.kode as tahun_akademik_mhs'
            );

        return Datatables::of($row)
            ->filter(function ($query) use ($search, $prodi_id) {
                $query->where('transkrip_cetak.prodi_id', $prodi_id);
                $query->where(function ($query) use ($search) {
                    $query->orWhere('th_akademik.kode', 'LIKE', "%$search%")
                        ->orWhere('mhs.nama', 'LIKE', "%$search%")
                        ->orWhere('jenis_kelamin.nama', 'LIKE', "%$search%")
                        ->orWhere('prodi.nama', 'LIKE', "%$search%");
                });
            })
            ->addColumn('smt', function ($row) {
                $smt = @getSemesterMahasiswa($row->tahun_akademik_mhs, $row->nim);
                return $smt;
            })
            ->addColumn('status', function ($row) {
                $status = array('Baru', 'Setujui', 'Tolak');
                $x = '<select name="btnStatus' . $row->id . '" id="btnStatus' . $row->id . '" class="form-control" onchange="simpan(' . $row->id . ')">';
                foreach ($status as $s) {
                    $select = $s == $row->status ? 'selected' : '';
                    $x = $x . '<option value="' . $s . '" ' . $select . ' >' . $s . '</option>';
                }
                $x = $x . '</select>';

                return $x;
            })
            ->rawColumns(['status'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $data = TranskripCetak::where('id', $request->id)->first();
        $data->status = $request->status;
        $data->user_id = Auth::user()->id;
        $data->save();

        $msg = array(
            'title' => $this->title,
            'info' => 'Data Success di update menjadi ' . $request->status,
            'status' => 'success'
        );
        return response()->json($msg);
    }
}