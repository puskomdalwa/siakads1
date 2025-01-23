<?php
namespace App\Http\Controllers\site_admin;

use App\Http\Controllers\Controller;
use App\JadwalKuliah;
use App\Prodi;
use App\ThAkademik;
use Auth;
use Illuminate\Http\Request;

class ExportDataKrsController extends Controller
{
    private $title = 'Export Data KRS';
    private $redirect = 'exportdatakrs';
    private $folder = 'exportdatakrs';
    private $class = 'exportdatakrs';

    public function index()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $prodi_id = @strtolower(Auth::user()->prodi->id);
        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $list_thakademik = ThAkademik::orderBy('kode', 'DESC')->get();

        return view($folder . '.index', compact('title', 'redirect', 'folder', 'list_prodi', 'list_thakademik'));
    }

    public function export(Request $request)
    {
		$jadwal = JadwalKuliah::where([
			['th_akademik_id', $request->th_akademik_id],
			['prodi_id', $request->prodi_id]
		])->get();

		// foreach ($jadwal as $j) {
		// 	$listMhs = KRSDetail::orderBy('nim', 'asc')
        //     ->where('jadwal_kuliah_id', $j->id)->get();
		// }

        $prodi = Prodi::find($request->prodi_id);
        $aliasProdi = $prodi ? " - $prodi->alias" : "";
        $thAkademikSiakad = ThAkademik::find($request->th_akademik_id);
        $nama = "Export KRS" . $aliasProdi . " - $thAkademikSiakad->nama" . ".xls";
        return view('exportdatakrs.excel', compact('nama', 'jadwal'));
    }
}
