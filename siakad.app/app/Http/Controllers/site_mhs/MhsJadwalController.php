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
use App\Absensi;
use App\AbsensiDetail;

class MhsJadwalController extends Controller {
	private $title	  = 'Jadwal Kuliah';
	private $redirect = 'mhs_jadwal';
	private $folder	  = 'site_mhs.mhs_jadwal';
	private $class	  = 'mhs_jadwal';

	public function index(){
		$nim = Auth::user()->username;
		
		$mhs_aktif   = Mahasiswa::Aktif($nim)->first();
		$th_akademik = ThAkademik::Aktif()->first();

		$acc_krs = KRS::select('acc_pa')
		->where('th_akademik_id',$th_akademik->id)
		->where('nim',$nim)->first();

		if(isset($acc_krs)){
			$acc_pa	  = $acc_krs->acc_pa;
			$title	  = $this->title.' NIM : '.$nim.' Tahun Akademik : '.$th_akademik->kode;
			$redirect = $this->redirect;
			$folder	  = $this->folder;

			if($mhs_aktif){
				if($acc_pa!='Setujui'){
					return view($folder.'.acc',compact('title','redirect','folder','th_akademik','nim','acc_pa'));  
				}
				return view($folder.'.index',compact('title','redirect','folder','th_akademik','nim'));
			}else{
				return redirect('home');
			}
		}

		alert()->warning('Silahkan Isi KRS terlebih dahulu.',$this->title);
		return redirect('mhs_krs');
	}

	public function getData(Request $request){
		$th_akademik_id = $request->th_akademik_id;
		$nim = $request->nim;

		$row = KRSDetail::where('th_akademik_id',$th_akademik_id)
		->where('nim',$nim)
		->whereNotNull('krs_id')
		->with('jadwal_kuliah')
		->get();

		return Datatables::of($row)
		->addColumn('kode_mk',function($row){
			return  @$row->jadwal_kuliah->kurikulum_matakuliah->matakuliah->kode;
		})
		->addColumn('nama_mk',function($row){
			return  @$row->jadwal_kuliah->kurikulum_matakuliah->matakuliah->nama;
		})
		->addColumn('sks_mk',function($row){
			return  @$row->jadwal_kuliah->kurikulum_matakuliah->matakuliah->sks;
		})
		->addColumn('smt_mk',function($row){
			return  @$row->jadwal_kuliah->kurikulum_matakuliah->matakuliah->smt;
		})
		->addColumn('kelompok',function($row){
			return  @$row->jadwal_kuliah->kelompok->kode;
		})
		->addColumn('dosen',function($row){
			return @$row->jadwal_kuliah->dosen->nama;			
		})
		->addColumn('ruang',function($row){
			return  @$row->jadwal_kuliah->ruang_kelas->kode;
		})
		->addColumn('hari',function($row){
			return  @$row->jadwal_kuliah->hari->nama;
		})
		->addColumn('waktu',function($row){			
			return  @$row->jadwal_kuliah->jamkul->nama;
		})
		->addColumn('details_url', function($row){
			 return url($this->redirect.'/getDetailsData/'.$row->jadwal_kuliah_id);
		 })
		->make(true);
	}

	public function getDetailsData($id){
		$row = Absensi:: where('trans_jadwal_kuliah_id',$id)	
		->latest()
		->get();

		return Datatables::of($row)
		->addColumn('txt-tgl',function($row){
			return tgl_str($row->tanggal);
		})
		->addColumn('txt-materi',function($row){
			return $row->materi;
		})
		->addColumn('status',function($row){
			return $this->txt_status($row->trans_jadwal_kuliah_id,$row->id); 		
		})
		->rawColumns(['txt-materi'])
		->make(true);
	}

	public function txt_status($jadwal_id,$absen_id){
		$nim  = Auth::user()->username;
		$data = AbsensiDetail::where('trans_jadwal_kuliah_id',$jadwal_id)
		->where('trans_absensi_mhs',$absen_id)
		->where('nim',$nim)
		->first();
		
		return $data->status;
	}
}
