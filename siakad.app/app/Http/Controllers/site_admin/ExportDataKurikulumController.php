<?php
namespace App\Http\Controllers\site_admin;

use App\Http\Controllers\Controller;
use App\Kurikulum;
use App\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportDataKurikulumController extends Controller
{
    private $title = 'Export Data Kurikulum';
    private $redirect = 'exportdatakurikulum';
    private $folder = 'exportdatakurikulum';
    private $class = 'exportdatakurikulum';

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

        return view($folder . '.index', compact('title', 'redirect', 'folder', 'list_prodi'));
    }
    public function export(Request $request)
    {

        if ($request->prodi_id != '') {
            $kurikulum = Kurikulum::where([
                ['prodi_id', $request->prodi_id],
            ])->orderBy('th_akademik_id', 'ASC')->get();
        } else {
            $kurikulum = Kurikulum::orderBy('th_akademik_id', 'ASC')->get();
        }

        foreach ($kurikulum as $k) {
            $detail = $k->detail;
            $sksWajib = 0;
            foreach ($detail as $d) {
                $mk = $d->matakuliah;
                $sksWajib += @$mk->sks;
            }
            $k->sks_wajib = $sksWajib;
        }

        $prodi = Prodi::find($request->prodi_id);
        $aliasProdi = $prodi ? " - $prodi->alias" : "";
        $nama = "Export Kurikulum" . $aliasProdi . ".xls";
        return view('exportdatakurikulum.excel', compact('kurikulum', 'nama'));
    }
}
