<?php
namespace App\Http\Controllers\site_admin;

use App\Http\Controllers\Controller;
use App\JadwalKuliah;
use App\Prodi;
use App\ThAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportDataKelasController extends Controller
{
    private $title = 'Export Data Kelas';
    private $redirect = 'exportdatakelas';
    private $folder = 'exportdatakelas';
    private $class = 'exportdatakelas';

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

        if ($request->prodi_id != null) {
            $kelas = JadwalKuliah::where([
                ['th_akademik_id', $request->th_akademik_id],
                ['prodi_id', $request->prodi_id],
            ])->get();
        } else {
            $kelas = JadwalKuliah::where([
                ['th_akademik_id', $request->th_akademik_id],
            ])->get();
        }

        $prodi = Prodi::find($request->prodi_id);
        $aliasProdi = $prodi ? " - $prodi->alias" : "";
        $nama = "Export Kelas" . $aliasProdi . ".xls";
        
        return view('exportdatakelas.excel', compact('kelas', 'nama'));
    }
}
