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
use App\Kurikulum;
use App\KurikulumMataKuliah;
use App\KurikulumAngkatan;
use App\MataKuliah;


class LapKurikulumController extends Controller
{
    private $title = 'Laporan Kurikulum';
    private $redirect = 'lapkurikulum';
    private $folder = 'lapkurikulum';
    private $class = 'lapkurikulum';

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

        $list_smt = MataKuliah::
            select('smt')
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('prodi_id', $prodi_id);
            })
            ->groupBy('smt')
            ->orderBy('smt', 'ASC')
            ->get();

        $list_prodi = Kurikulum::
            select('prodi_id')
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('prodi_id', $prodi_id);
            })
            ->groupBy('prodi_id')
            ->orderBy('prodi_id', 'ASC')
            ->get();

        $list_thakademik = Kurikulum::
            select('th_akademik_id')
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('prodi_id', $prodi_id);
            })
            ->groupBy('th_akademik_id')
            ->orderBy('th_akademik_id', 'ASC')
            ->get();

        foreach ($list_prodi as $prodi) {
            foreach ($list_thakademik as $thakademik) {
                $rows[$prodi->prodi_id][$thakademik->th_akademik_id] = Kurikulum::
                    where('th_akademik_id', $thakademik->th_akademik_id)
                    ->where('prodi_id', $prodi->prodi_id)
                    ->with(['th_akademik', 'prodi'])
                    ->orderBy('id', 'ASC')
                    ->get();
                foreach ($rows[$prodi->prodi_id][$thakademik->th_akademik_id] as $row) {
                    $angkatan[$row->id] = KurikulumAngkatan::
                        where('kurikulum_id', $row->id)->get();

                    foreach ($list_smt as $smt) {
                        $mk[$row->id][$smt->smt] = KurikulumMataKuliah::
                            select('trans_kurikulum_matakuliah.*')
                            ->join('mst_matakuliah', 'mst_matakuliah.id', '=', 'trans_kurikulum_matakuliah.matakuliah_id')
                            ->where('kurikulum_id', $row->id)
                            ->where('mst_matakuliah.smt', $smt->smt)
                            ->with(['matakuliah'])
                            ->orderBy('trans_kurikulum_matakuliah.matakuliah_id', 'ASC')
                            ->get();
                    }

                }
            }
        }

        $data = array(
            'list_smt' => $list_smt,
            'list_prodi' => $list_prodi,
            'list_thakademik' => $list_thakademik,
            'rows' => $rows,
            'angkatan' => $angkatan,
            'mk' => $mk,
        );

        return view($this->folder . '.data2', compact('data'));
    }
}