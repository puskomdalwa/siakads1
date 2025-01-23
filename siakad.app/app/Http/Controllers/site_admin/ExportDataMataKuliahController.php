<?php
namespace App\Http\Controllers\site_admin;
use App\Http\Controllers\Controller;
use App\MataKuliah;
use App\Prodi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ExportDataMataKuliahController extends Controller {
	private $title		= 'Export Data MataKuliah';
	private $redirect 	= 'exportdatamatakuliah';
	private $folder 	= 'exportdatamatakuliah';
	private $class 		= 'exportdatamatakuliah';

	public function index(){
		$title 		= $this->title;
		$redirect 	= $this->redirect;
		$folder 	= $this->folder;

		$prodi_id = @strtolower(Auth::user()->prodi->id);
		if($prodi_id){
			$list_prodi = Prodi::where('id',$prodi_id)->get();
		}else{
			$list_prodi = Prodi::orderBy('kode','ASC')->get();
		}

		return view($folder.'.index',compact('title','redirect','folder','list_prodi'));
	}

	public function export(Request $request){

		if ($request->prodi_id != '') {
            $matkul = MataKuliah::where([
				['prodi_id', $request->prodi_id]
			])->get();
        }else{
			$matkul = MataKuliah::orderBy('prodi_id', 'ASC')->get();
        }
		
		$prodi = Prodi::find($request->prodi_id);
		$aliasProdi = $prodi ? " - $prodi->alias" : ""; 
		$nama = "Export Mata Kuliah".$aliasProdi.".xls";
        return view('exportdatamatakuliah.excel', compact('matkul', 'nama'));
    }
}