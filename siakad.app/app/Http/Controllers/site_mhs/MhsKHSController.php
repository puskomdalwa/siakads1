<?php
namespace App\Http\Controllers\site_mhs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use Auth;
Use Alert;
use App\KRS;
use App\ThAkademik;
use App\Mahasiswa;
use App\JadwalKuliah;
use App\KRSDetail;
use App\FormSchadule;
use App\KuesionerJawaban;

class MhsKHSController extends Controller {
	private $title	  = 'Kartu Hasil Studi (KHS)';
	private $redirect = 'mhs_khs';
	private $folder	  = 'site_mhs.mhs_khs';
	private $class	  = 'mhs_khs';

	public function index(){
		$nim	   	 = Auth::user()->username;
		$mhs_aktif	 = Mahasiswa::Aktif($nim)->first();
		$th_akademik = ThAkademik::Aktif()->first();

		$acc_krs = acc_krs($th_akademik->id,$nim);    

		$title	  = $this->title.' NIM : '.$nim.' Tahun Akademik : '.$th_akademik->kode;
		$redirect = $this->redirect;
		$folder	  = $this->folder;

		if($mhs_aktif){
			$kuesioner = KuesionerJawaban::where('th_akademik_id',$th_akademik->id)->where('nim',$nim)->count();
			
			if($kuesioner<=1){
				alert()->warning('Anda belum melakukan Kuesioner Dosen',$this->title);
				return redirect('mhs_kuesioner_dosen');
			}else{
				if($acc_krs=='Setujui'){
					return view($folder.'.index',
						compact('title','redirect','folder','th_akademik','nim')
					);
				}
				return view($folder.'.acc_krs',
					compact('title','redirect','folder','th_akademik','nim','acc_krs')
				);
			}
		}else{
			return redirect('home');
		}
	}

	public function getData(Request $request){
		$th_akademik_id = ThAkademik::Aktif()->first()->id;
		$nim = Auth::user()->username;

		$row = KRSDetail::where('th_akademik_id',$th_akademik_id)
		->where('nim',$nim)->whereNotNull('krs_id')
		->with('dosen')->get();

		return Datatables::of($row)
		->addColumn('dosen',function($row){
			return  @$row->dosen->nama;
		})
		->addColumn('nilai_mutu',function($row){
			return  $row->sks_mk * $row->nilai_bobot;
		})
		->make(true);
	}	
	
	public function cetakKHS(Request $request){	
		$krs = KRS::where('id',$krs_id)
		->with('mahasiswa','th_akademik','prodi','kelas')
		->first();
		
		$th_akademik = ThAkademik::where('id',$krs->th_akademik_id)->first();
		
		$pt = PT::first();
		$prodi = @Prodi::where('id',Auth::user()->prodi_id)->first();

		$data = KRSDetail::where('krs_id',$krs->id)
		->with('jadwal_kuliah')
		->get();

		$biro_id = env('BIRO_AKADEMIK_ID');
		$biro = Pejabat::where('jabatan_id',$biro_id)->first();

		$class ='text-center';	
		
		$pdf = PDF::loadView($this->folder.'.cetakKHS', 
			compact('data','pt','th_akademik','krs','biro','prodi','class')
		);
		
		return $pdf->setPaper('a4','portrait')->stream('KHS '.$th_akademik->kode.' '.$krs->nim.'.pdf');
	}		
}
