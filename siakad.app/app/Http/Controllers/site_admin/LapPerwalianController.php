<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mahasiswa;
use App\KRS;
use App\KRSDetail;
use Auth;
use Yajra\Datatables\Datatables;
use Alert;
use App\ThAkademik;
use App\Prodi;
use App\Ref;
use App\User;
use App\PT;
use PDF;
use App\Exports\KRSExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Perwalian;
use App\PerwalianDetail;

class LapPerwalianController extends Controller
{
    private $title = 'Laporan Perwalian';
    private $redirect = 'lapperwalian';
    private $folder = 'lapperwalian';
    private $class = 'lapperwalian';

    private $rules = [
        'prodi_id' => 'required',
    ];

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

        $list_thakademik = ThAkademik::where('semester', 'Ganjil')->orderBy('kode', 'DESC')->get();
        return view(
            $folder . '.index',
            compact('title', 'redirect', 'folder', 'list_prodi', 'prodi_id')
        );
    }

    public function store(Request $request)
    {

        $prodi = @strtolower(Auth::user()->prodi->id);
        if ($prodi) {
            $prodi_id = $prodi;
        } else {
            $prodi_id = $request->prodi_id;
        }

        $list_prodi = Perwalian::
            select('prodi_id')
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('prodi_id', $prodi_id);
            })
            ->groupBy('prodi_id')
            ->orderBy('prodi_id', 'ASC')
            ->get();


        $list_kelas = Perwalian::
            select('kelas_id')
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('prodi_id', $prodi_id);
            })
            ->groupBy('kelas_id')
            ->orderBy('kelas_id', 'ASC')
            ->get();


        $list_kelompok = Perwalian::
            select('kelompok_id')
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('prodi_id', $prodi_id);
            })
            ->groupBy('kelompok_id')
            ->orderBy('kelompok_id', 'ASC')
            ->get();

        foreach ($list_prodi as $prodi) {
            foreach ($list_kelas as $kelas) {
                foreach ($list_kelompok as $kelompok) {
                    $rows[$prodi->prodi_id][$kelas->kelas_id][$kelompok->kelompok_id] = Perwalian::
                        where('prodi_id', $prodi->prodi_id)
                        ->where('kelas_id', $kelas->kelas_id)
                        ->where('kelompok_id', $kelompok->kelompok_id)
                        ->with(['th_akademik', 'prodi', 'kelas'])
                        ->get();
                    foreach ($rows[$prodi->prodi_id][$kelas->kelas_id][$kelompok->kelompok_id] as $row) {
                        $detail[$row->id] = PerwalianDetail::
                            where('perwalian_id', $row->id)
                            ->orderBy('nim', 'ASC')
                            ->get();
                    }
                }
            }
        }

        $data = array(
            'list_prodi' => $list_prodi,
            'list_kelas' => $list_kelas,
            'list_kelompok' => $list_kelompok,
            'rows' => $rows,
            'detail' => $detail,
        );

        return view($this->folder . '.data2', compact('data'));
    }
}