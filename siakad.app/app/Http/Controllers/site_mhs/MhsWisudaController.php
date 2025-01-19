<?php
namespace App\Http\Controllers\site_mhs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Yajra\Datatables\Datatables;
Use Alert;
use App\ThAkademik;
use App\Mahasiswa;
use App\JadwalKuliah;
use App\KRSDetail;
use App\KRS;
use App\FormSchadule;
use App\Ref;
use App\Yudisium;

class MhsWisudaController extends Controller {
	private $title 		= 'Wisuda';
	private $redirect	= 'mhs_wisuda';
	private $folder 	= 'site_mhs.mhs_wisuda';
	private $class	 	= 'mhs_wisuda';

	private $rules = [
		'nim'			=> 'required|string|max:20',
		'jml_sks' 		=> 'required',
		'ipk' 			=> 'required',
		'judul_skripsi' => 'required',
		'ukuran_toga' 	=> 'required',
	];

	public function index(){
		$nim		 = Auth::user()->username;
		$mhs_aktif	 = Mahasiswa::Aktif($nim)->first();
		$th_akademik = ThAkademik::Aktif()->first();

		$title		= $this->title.' NIM : '.$nim.' Tahun Akademik : '.$th_akademik->kode;
		$redirect 	= $this->redirect;
		$folder 	= $this->folder;
		$list_toga 	= Ref::where('table','UkuranToga')->get();

		$mhs = Mahasiswa::where('nim',$nim)
		->with('th_akademik','jk','prodi','kelas','status','kelompok')->first();
		
		// dd($mhs);
		$data_mhs = [
			'nama_mhs'		=> $mhs->nama,
			'jk'			=> $mhs->jk->nama,
			'th_angkatan'	=> $mhs->th_akademik->kode,
			'prodi'			=> $mhs->prodi->jenjang.' - '.$mhs->prodi->nama,
			'kelas'			=> $mhs->kelas->nama,
			'kelompok'		=> $mhs->kelompok,			
			'status'		=> $mhs->status->nama,
			'keuangan'		=> bayarWisuda($th_akademik->id,$mhs->nim)
		];

		if(empty($data_mhs['keuangan'])){
			alert()->warning('Maaf, Anda belum melakukan Pembayaran Wisuda',$this->title);
			return redirect('mhs_info');		
		}

		$data = Yudisium::where('nim',$nim)->first();		

		if($data){
			return view($folder.'.index', compact('title','redirect','folder',
			'th_akademik','nim','list_toga','data_mhs','data'));
		}else{
			alert()->warning('Anda belum berhak daftar WISUDA.',$this->title);
			return redirect('mhs_info');			
		}
	}

	public function store(Request $request){	
		$this->validate($request,$this->rules);
		$th_akademik	= ThAkademik::Aktif()->first();
		$th_akademik_id = $th_akademik->id;

		$nim = $request->nim;
		$mhs = Mahasiswa::where('nim',$nim)->first();

		$data = Yudisium::where('nim',$nim)->first();
		if(!$data){
		  $data = new Yudisium;
		}
		
		$data->tanggal			= date('Y-m-d');
		$data->th_akademik_id 	= $th_akademik_id;
		$data->th_angkatan_id 	= $mhs->th_akademik_id;
		$data->prodi_id 		= $mhs->prodi_id;
		$data->kelas_id 		= $mhs->kelas_id;
		$data->kelompok_id 		= $mhs->kelompok->perwalian->kelompok->id;
		$data->nim 				= $mhs->nim;
		$data->nama_lengkap 	= $mhs->nama;
		$data->motto 			= $request->motto;
		$data->judul_skripsi 	= $request->judul_skripsi;
		$data->jml_sks 			= $request->jml_sks;
		$data->ipk 				= $request->ipk;
		$data->ukuran_toga 		= $request->ukuran_toga;
		$data->approve			= null;
		$data->user_id			= Auth::user()->id;
		$data->save();

		alert()->success('Save Success',$this->title);
		return back()->withInput();
	}
}
