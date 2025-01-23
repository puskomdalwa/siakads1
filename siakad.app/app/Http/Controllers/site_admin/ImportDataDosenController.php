<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;
use DB;
use App\Dosen;
use App\Prodi;
use App\Ref;
use File;
use Auth;
use App\User;

// use App\Imports\DosenImport;

class ImportDataDosenController extends Controller {
	private $title	  = 'Import Data Dosen';
	private $redirect = 'importdatadosen';
	private $folder   = 'importdatadosen';
	private $class 	  = 'importdatadosen';


	public function index(){
		$title	  	= $this->title;
		$redirect	= $this->redirect;
		$folder   	= $this->folder;
		$list_prodi = Prodi::get();

		return view($folder.'.index',compact('title','redirect','folder','list_prodi'));
	}

	public function store(Request $request) {
	
		$request->validate([			
			'import_file' => 'required|mimes:xlsx,xls'
		]);
		
		if($request->hasFile('import_file')){
			$path = $request->file('import_file')->getRealPath();
			$data = \Excel::load($path)->get();
			// dd($data->count());
			if($data->count()){
				$no=0;
				$nu=0;
				foreach ($data as $key => $value) {
					$prodi = Prodi::where('kode',trim($value->kode_prodi))->first();
					if($prodi){
						$dosen = Dosen::where('kode',trim($value->kode))->first();					
						if(!$dosen){
							$dt = new Dosen;
							$dt->prodi_id = $prodi->id;
							$dt->kode = trim($value->kode);
							$dt->nidn = trim($value->nidn);
							$dt->nama = $value->nama;
							$dt->email = trim($value->email); 						
							$dt->jk_id = $this->getJK($value->jenis_kelamin);
							$dt->dosen_status_id = '23';
							$dt->user_id = Auth::user()->id;
							$dt->save();

							$user = new User;
							$user->username = trim($value->kode);
							$user->name = $value->nama;
							$user->email = trim($value->email); 						
							$user->level_id='4';
							$user->prodi_id = $prodi->id;
							$user->aktif ='Y';
							$user->password = bcrypt('123456');
							$user->save();

							$no++;
						} else {
							$dt = Dosen::findOrFail($dosen->id);
							dd($value);
							$dt->prodi_id = $prodi->id;
							$dt->kode = trim($value->kode);
							$dt->nidn = trim($value->nidn);
							$dt->nama = $value->nama;
							$dt->email = trim($value->email); 						
							$dt->jk_id = $this->getJK($value->jenis_kelamin);
							$dt->dosen_status_id = '23';
							$dt->user_id = Auth::user()->id;
							$dt->save();

							$nu++;
						}
					} else {
						$dt = Dosen::where('kode',trim($value->kode))->first();	
						if ($dt) {
							$dt->nidn = $value->nidn != "" ? trim($value->nidn) : $dt->nidn;
							$dt->nama = $value->nama != "" ? trim($value->nama) : $dt->nama;
							$dt->email = $value->email != "" ? trim($value->email) : $dt->email;					
							$dt->jk_id = $value->jenis_kelamin != "" ? $this->getJK($value->jenis_kelamin) : $dt->jk_id; ;
							$dt->status_dosen_tetap_id = $value->status_dosen_tetap_id != "" ? trim($value->status_dosen_tetap_id) : $dt->status_dosen_tetap_id;
							$dt->save();
							$nu++;
						}
					}
				}
							
				alert()->success('Dari '.$data->count().' '.$no.' Berhasil Diupload... '.$nu.' Berhasil Diupdate...',$this->title);
				return back();
				// }
			}
		}
	}

	private function getJK($kode){
		$jk = Ref::where('table','JenisKelamin')->where('kode',$kode)->first();
		if($jk){
			return $jk->id;
		}else{
			return null;
		}
	}
}