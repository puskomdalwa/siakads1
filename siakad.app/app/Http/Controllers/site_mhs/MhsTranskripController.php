<?php
namespace App\Http\Controllers\site_mhs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
Use Alert;

use PDF;
use App\PT;
use App\KRS;
use App\Pejabat;
use App\Mahasiswa;
use App\ThAkademik;
use App\KRSDetail;
use App\TranskripCetak;

//---------------------------

use App\Ref;
use App\User;
use App\Prodi;

use App\JadwalKuliah;

class MhsTranskripController extends Controller {

	private $title	  = 'Transkrip Nilai Sementara';
	private $redirect = 'mhs_transkrip';
	private $folder	  = 'site_mhs.mhs_transkrip';
	private $class	  = 'mhs_transkrip';

	public function index(){
						
		$nim 		 = Auth::user()->username;
		$level_id	 = Auth::user()->level_id;
		$mhs_aktif	 = Mahasiswa::Aktif($nim)->first();
		$th_akademik = ThAkademik::Aktif()->first();

		$pt = PT::first();

		$title	  = $this->title.' Tahun Akademik '.$th_akademik->kode.' | NIM : '.$nim; 
	
		$redirect = $this->redirect;
		$folder	  = $this->folder;

		$data = KRSDetail::where('nim',$nim)
		->where('transkrip','Y')
		->orderBy('smt_mk')
		->orderBy('nama_mk')->get();	
		
		return view($folder.'.index',
			compact('title','redirect','folder','mhs_aktif','pt','data','nim')
		);
	}

	public function getKirim(Request $request){	
		$nim = Auth::user()->username;
		$mhs = Mahasiswa::where('nim',strtoupper($nim))->first();
		$prodi_id = $mhs->prodi_id;

		$th_akademik = ThAkademik::Aktif()->first();
		
		$data = TranskripCetak::where('th_akademik_id',$th_akademik->id)
		->where('nim',$nim)
		->first();
		
		if($data){
			$msg = array (
				'title'	 => $this->title,
				'info'	 => 'Permohonan sudah pernah Anda Kirim. Status '.$data->status,
				'status' => 'warning'
			);
			return response()->json($msg);
		}
		
		$data = new TranskripCetak;
		$data->th_akademik_id = $th_akademik->id;
		$data->nim = $nim;
		$data->prodi_id = $prodi_id;
		$data->status	= 'Baru';
		$data->user_id 	= Auth::user()->id;
		$data->save();

		$msg = array (
			'title' => $this->title,
			'info' => 'Permohonan Sukses dikirim.',
			'status' => 'success'
		);
		
		return response()->json($msg);
	}

	public function store(Request $request){	
	
		$pt	   = PT::first();
        $nim   = Auth::user()->username;
		$prodi = @Prodi::where('id',Auth::user()->prodi_id)->first();
        
		$level_id	 = Auth::user()->level_id;
        $mhs_aktif	 = Mahasiswa::Aktif($nim)->first();
        $th_akademik = ThAkademik::Aktif()->first();
		
		$krs = KRS::where('nim',$nim)
		->with('mahasiswa','th_akademik','prodi','kelas')
		->first();
		
		$data = KRSDetail::where('nim',$nim)
		->with('jadwal_kuliah')
		->get();				
		
		$level_id	 = Auth::user()->level_id;
		$mhs_aktif	 = Mahasiswa::Aktif($nim)->first();
		$th_akademik = ThAkademik::Aktif()->first();
	
		$biro_id = env('BIRO_AKADEMIK_ID');
		$biro = Pejabat::where('jabatan_id',$biro_id)->first();

		$class ='text-center';
				
		$pdf = PDF::loadView($this->folder.'.cetakTranskrip',
			compact('data','pt','th_akademik','krs','biro','prodi','class')				
		);
		
		return $pdf->setPaper('a4','portrait')->stream('TranSem_'.$mhs_aktif->nim.'.pdf');	
	}
			
	public function cetakTranskrip(Request $request){
		$krs = KRS::where('id',$request->krs_id)
		->with('mahasiswa','th_akademik','prodi','kelas')
		->first();
				
		$th_akademik = ThAkademik::where('id',$krs->th_akademik_id)->first();
		
		$pt  = PT::first();
		$nim = Auth::user()->username;
		$prodi = @Prodi::where('id',Auth::user()->prodi_id)->first();

		$data = KRSDetail::where('nim',$krs->nim)
		->with('jadwal_kuliah')
		->get();

		$biro_id = env('BIRO_AKADEMIK_ID');
		$biro = Pejabat::where('jabatan_id',$biro_id)->first();

		$class ='text-center';	
		
		$pdf = PDF::loadView($this->folder.'.cetakTranskrip', 
			compact('data','pt','th_akademik','krs','biro','prodi','class')
		);
		
		return $pdf->setPaper('a4','portrait')
		->stream('Transkrip_'.$th_akademik->kode.' '.$krs->nim.'.pdf');
	}
}
