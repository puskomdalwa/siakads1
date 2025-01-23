<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;
use DB;
use App\Mahasiswa;
use App\Prodi;
use App\Ref;
use App\User;
use App\ThAkademik;
use Auth;

class ImportDataPembayaranController extends Controller {
	private $title		= 'Import Data Pembayaran';
	private $redirect 	= 'importdatapembayaran';
	private $folder 	= 'importdatapembayaran';
	private $class 		= 'importdatapembayaran';

	public function index(){
		$title 		= $this->title;
		$redirect 	= $this->redirect;
		$folder 	= $this->folder;
		$list_prodi = Prodi::get();

		return view($folder.'.index',compact('title','redirect','folder','list_prodi'));
	}

	public function store(Request $request){
		$request->validate(['import_file' => 'required|mimes:xlsx,xls']);
		if($request->hasFile('import_file')){
			$path = $request->file('import_file')->getRealPath();
			$data = \Excel::load($path)->get();
			
			if($data->count()){
				$no=0;
				foreach ($data as $key => $value) {
					$th_akademik = ThAkademik::where('kode',trim($value->th_akademik))->first();
					$kelas = Ref::where('table','Kelas')->where('kode',$value->kode_kelas)->first();
					$prodi = Prodi::where('kode',trim($value->kode_prodi))->first();

					if($prodi){
						$mhs = Mahasiswa::where('nim',trim($value->nim))->first();
					
						if(!$mhs){
							$dt = new Mahasiswa;
							$dt->th_akademik_id = @$th_akademik->id;
							$dt->prodi_id 		= @$prodi->id;
							$dt->kelas_id 		= @$kelas->id;
							$dt->nim 			= trim($value->nim);
							$dt->nama 			= $value->nama;
							$dt->email 			= trim($value->email);
							$dt->jk_id 			= $this->getJK($value->jenis_kelamin);
							$dt->status_id 		= '20';
							$dt->user_id 		= Auth::user()->id;
							$dt->save();						
							$no++;
						}
					}
				}
							
				alert()->success('Dari '.$data->count().' '.$no.' Berhasil Diupload...',$this->title);
				return back();			
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
