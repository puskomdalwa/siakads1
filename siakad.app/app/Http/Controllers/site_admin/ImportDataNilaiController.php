<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;
use DB;
use App\MataKuliah;
use App\Prodi;
use App\Ref;
use App\ThAkademik;
use App\KRSDetail;
use Auth;

class ImportDataNilaiController extends Controller {
	private $title	  = 'Import Data Nilai';
	private $redirect = 'importdatanilai';
	private $folder	  = 'importdatanilai';
	private $class	  = 'importdatanilai';


	public function index(){
		$title = $this->title;
		$redirect = $this->redirect;
		$folder = $this->folder;

		$list_prodi = Prodi::get();
		return view($folder.'.index',
			compact('title','redirect','folder','list_prodi')
		);
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
					$th_akademik = ThAkademik::where('kode',trim($value->th_akademik))->first();
					$mk = KRSDetail::where('nim',trim($value->nim))->where('kode_mk',trim($value->kode_mk))->first();				
					if(!$mk){
						$dt = new KRSDetail;
						$dt->th_akademik_id = $th_akademik->id;
						$dt->nim = trim($value->nim);
						
						$dt->nama_mhs = strtoupper($value->nama_mhs);
						$dt->kode_mk  = trim($value->kode_mk);
						$dt->nama_mk  = strtoupper($value->nama_mk);
						$dt->sks_mk	  = $value->sks_mk;
						$dt->smt_mk	  = $value->smt_mk;
						
						$dt->nilai_bobot = $value->nilai_bobot;
						$dt->nilai_huruf = $value->nilai_huruf;
						
						$dt->user_id = Auth::user()->id;
						$dt->save();
						$no++;
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
