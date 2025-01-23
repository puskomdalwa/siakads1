<?php
namespace App\Http\Controllers\site_admin;

use PDF;
use Auth;
use App\PT;
use App\Ref;
use App\User;
use App\Dosen;
use App\Prodi;
use App\ThAkademik;
use App\JadwalKuliah;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LapNilaiDosenController extends Controller
{

    private $title = 'Laporan Nilai Dosen';
    private $redirect = 'lapnilaidosen';
    private $folder = 'lapnilaidosen';
    private $class = 'lapnilaidosen';

    private $rules = [
        'prodi_id' => 'required',
    ];

    public function index()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;
        $level = strtolower(Auth::user()->level->level);

        $prodi_id = @strtolower(Auth::user()->prodi->id);

        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            // $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $list_status = Ref::where('table', 'StatusDosen')->get();

		$th_akademik = ThAkademik::orderBy('kode', 'desc')->get();
		$th_akademik_aktif = ThAkademik::aktif()->first();
        return view(
            $folder . '.index',
            compact('title', 'redirect', 'folder', 'list_prodi', 'list_status', 'level', 'prodi_id', 'th_akademik', 'th_akademik_aktif')
        );
    }

    public function store(Request $request)
    {
        $prodi_id = $request->prodi_id;
        $status_id = $request->status_id;

        $list_prodi = Prodi::when($prodi_id, function ($query) use ($prodi_id) {
            return $query->where('id', $prodi_id);
        })
            ->orderBy('kode', 'ASC')
            ->get();

        foreach ($list_prodi as $prodi) {
            $rows[$prodi->id] = Dosen::where('prodi_id', $prodi->id)

                ->when($status_id, function ($query) use ($status_id) {
                    return $query->where('dosen_status_id', $status_id);
                })
                ->orderBy('nama', 'asc')
                ->with(['prodi', 'jk', 'status'])
                ->get();
        }

        $data = array(
            'list_prodi' => $list_prodi,
            'rows' => $rows,
			'th_akademik_id' => $request->th_akademik_id
        );

        return view(
            $this->folder . '.data',
            compact('data')
        );
    }

    public function cetak(Request $request)
    {
        $prodi_id = $request->prodi_id;
        $status_id = $request->status_id;
        $th_akademik_id = $request->th_akademik_id;

		if($prodi_id){
			$prodi = @Prodi::where('id', Auth::user()->prodi->id)->first();
			$lprodi = @Prodi::where('id', $prodi_id)->first();
			$nmprodi = $lprodi->nama . ' (' . $lprodi->jenjang . ')';
		} else {
			$nmprodi = 'SEMUA PRODI';
			$prodi = null;
		}

        $pt = PT::first();

        $data = Dosen::when($prodi_id, function ($query) use ($prodi_id) {
            return $query->where('prodi_id', $prodi_id);
        })
            ->when($status_id, function ($query) use ($status_id) {
                return $query->where('dosen_status_id', $status_id);
            })
            ->orderBy('nama', 'asc')
            ->with(['prodi', 'jk', 'status'])
            ->get();

        $class = 'text-left';

        $pdf = PDF::loadView(
            $this->folder . '.cetak',
            compact('data', 'pt', 'prodi', 'class', 'nmprodi', 'th_akademik_id')
        );

        return $pdf->setPaper('a4', 'landscape')->stream('Laporan Input Nilai Dosen '.date('d M Y H-i').'.pdf');
    }
}
