<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;
use DB;
use App\MataKuliah;
use App\Prodi;
use Auth;

class ImportDataJadwalKuliahController extends Controller {
	private $title	  = 'Import Data Jadwal Kuliah';
	private $redirect = 'importdatajadwalkuliah';
	private $folder	  = 'importdatajadwalkuliah';
	private $class	  = 'importdatajadwalkuliah';

	public function index(){
		$title	  = $this->title;
		$redirect = $this->redirect;
		$folder	  = $this->folder;

		$list_prodi = Prodi::get();
		return view($folder.'.index', compact('title','redirect','folder','list_prodi'));
	}

	public function store(Request $request){		
		$request->validate([			
			'import_file' => 'required|mimes:xlsx,xls'
		]);

		if($request->hasFile('import_file')){
			$path = $request->file('import_file')->getRealPath();
			$data = \Excel::load($path)->get();
		
			if($data->count()){
				$no=0;
				foreach ($data as $key => $value) {
					$prodi = Prodi::where('kode',trim($value->kode_prodi))->first();
					if($prodi){
						$mk = MataKuliah::where('kode',trim($value->kode))->first();						
						if(!$mk){
							$dt = new MataKuliah;
							$dt->prodi_id = $prodi->id;
							$dt->kode	  = trim($value->kode);
							$dt->nama	  = strtoupper($value->nama);
							$dt->sks	  = $value->sks;
							$dt->smt	  = $value->smt;
							$dt->aktif	  = 'Y';
							$dt->user_id  = Auth::user()->id;
							$dt->save();
							$no++;
						}
					}
				}
				alert()->success('Dari '.$data->count().' data '.$no.' Berhasil diupload.',$this->title);
				return back();
			}else {
				alert()->error('Upload Error.',$this->title);
				return back();
			}			
		}
	}
}
