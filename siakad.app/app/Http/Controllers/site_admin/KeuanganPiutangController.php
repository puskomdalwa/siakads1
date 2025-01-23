<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\KeuanganPembayaran;
use App\KeuanganTagihan;
use Auth;
use Yajra\Datatables\Datatables;
Use Alert;
use App\Mahasiswa;
use App\ThAkademik;
use App\Prodi;
use App\Ref;
use DB;
use PDF;
use App\PT;

class KeuanganPiutangController extends Controller {
	private $title	  = 'Piutang Keuangan Mahasiswa';
	private $redirect = 'keuanganpiutang';
	private $folder	  = 'keuanganpiutang';
	private $class	  = 'keuanganpiutang';

	private $rules = [
		'th_akademik_id' => 'required',
		'nama_prodi'	 => 'required',
		'nama_kelas' 	 => 'required',
		'tagihan_id' 	 => 'required',
		'mhs_id'	 	 => 'required',
		'nim'		 	 => 'required|string|max:20',
		'nama_mhs'	 	 => 'required|string|max:100',
		'jumlah_tagihan' => 'required|numeric',
		'jumlah' 		 => 'required|numeric',
	];
	
	public function index(){
		$title			 = $this->title;
		$redirect		 = $this->redirect;
		$folder	 		 = $this->folder;
		$list_kelas		 = Ref::where('table','Kelas')->get();

		$th_akademik_id  = ThAkademik::Aktif()->first()->id;
		$list_thakademik = ThAkademik::orderBy('kode','Desc')->get();
		$list_thangkatan = ThAkademik::where('semester','Ganjil')->orderBy('kode','DESC')->get();

		$level	  = strtolower(Auth::user()->level->level);
		$prodi_id = @strtolower(Auth::user()->prodi->id);

		if($level=='prodi'){
			$list_prodi = Prodi::where('id',$prodi_id)->get();
		}else{
			$prodi_id	= Prodi::OrderBy('kode','ASC')->first()->id;
			$list_prodi = Prodi::OrderBy('kode','ASC')->get();
		}

		return view($folder.'.index',
			compact('title','redirect','folder','th_akademik_id','list_thakademik',
			'prodi_id','list_prodi','list_kelas','list_thangkatan','level')
		);
	}

	public function getData(Request $request){
		$th_akademik_id = $request->th_akademik_id;
		$prodi_id		= $request->prodi_id;
		$tagihan_id 	= $request->tagihan_id;

		$row = Mahasiswa::select('mst_mhs.*')
		->addSelect('keuangan_tagihan.kode as kode_tagihan','keuangan_tagihan.nama as nama_tagihan','keuangan_tagihan.jumlah')
		->addSelect(DB::raw('(select SUM(keuangan_pembayaran.jumlah) FROM keuangan_pembayaran 
		WHERE keuangan_pembayaran.tagihan_id=keuangan_tagihan.id AND keuangan_pembayaran.nim=mst_mhs.nim) as total_bayar'))
		->addSelect(DB::raw('(keuangan_tagihan.jumlah - (select SUM(keuangan_pembayaran.jumlah) FROM keuangan_pembayaran 
		WHERE keuangan_pembayaran.tagihan_id=keuangan_tagihan.id 
		AND keuangan_pembayaran.nim=mst_mhs.nim)) as sisa'))
		->join('keuangan_tagihan','keuangan_tagihan.th_angkatan_id','=','mst_mhs.th_akademik_id')
		->where('keuangan_tagihan.id',$tagihan_id)
		->where('mst_mhs.prodi_id',$prodi_id)
		->with('jk','prodi','kelas','kelompok')
		->orderBy('nim','asc')->get();

		return Datatables::of($row)
		->addColumn('jk',function($row){
			return  $row->jk->kode;
		})
		->addColumn('prodi',function($row){
			return  $row->prodi->nama;
		})
		->addColumn('kelas',function($row){
			return  $row->kelas->kode;
		})
		->addColumn('klp',function($row){
			return  @$row->kelompok->perwalian->kelompok->kode;
		})
		->addColumn('tagihan',function($row){
			return  $row->kode_tagihan.' - '.$row->nama_tagihan;
		})
		->addColumn('jml_tagihan',function($row){
			return  number_format($row->jumlah);
		})
		->addColumn('jml_bayar',function($row){
			return  number_format($row->total_bayar);
		})
		->addColumn('sisa',function($row) use ($tagihan_id){
			$sisa = $row->jumlah - $row->total_bayar;
			return number_format($sisa);
		})
		->setRowClass(function ($row) {
			$sisa = $row->jumlah - $row->total_bayar;
			 return $sisa > 0 ? 'alert-danger' : 'alert-success';
		 })
		->make(true);
	}

	public function listMahasiswa(Request $request){
		$prodi_id		= '10';
		$th_akademik_id = '15';
		
		$prodi_id		= $request->prodi_id;
		$th_akademik_id = $request->th_akademik_id;
		
		$mhs = Mahasiswa::where('th_akademik_id',$th_akademik_id)
		->where('prodi_id',$prodi_id)->get();
				
		echo '<option value="">-Pilih Mahasiswa-</option>';		
		foreach($mhs as $row){				
			echo '<option value="'.$row->id.'">'.$row->nim.' - '.$row->nama.'</option>';
		}
	}

	public function getListMahasiswa(Request $request){		
		$prodi_id		= $request->prodi_id;
		$th_akademik_id = $request->th_akademik_id;

		$tagihan = KeuanganTagihan::where('th_akademik_id',$th_akademik_id)
		->where('prodi_id',$prodi_id)->get();
		
		echo '<option value="">-Pilih Piutang-</option>';
		foreach($tagihan as $row){
			echo '<option value="'.$row->id.'">'.$row->kode.' - '.$row->nama.' Angkatan '.
			$row->th_angkatan->kode.'</option>';
		}
	}

	public function listTagihan(Request $request){			
		$prodi_id		= $request->prodi_id;
		$th_akademik_id = $request->th_akademik_id;

		$tagihan = KeuanganTagihan::where('th_akademik_id',$th_akademik_id)
		->where('prodi_id',$prodi_id)->get();
		
		echo '<option value="">-Pilih Piutang-</option>';
		foreach($tagihan as $row){
			echo '<option value="'.$row->id.'">'.$row->kode.' - '.$row->nama.' Angkatan '.
			$row->th_angkatan->kode.'</option>';
		}
	}

	public function cetak(Request $request){
		$prodi_id	= $request->prodi_id;
		$tagihan_id = $request->tagihan_id;
	
		$th_akademik_id	 = $request->th_akademik_id;
		$th_akademik	 = ThAkademik::where('id',$th_akademik_id)->first();

		$pt = PT::first();

		$data = Mahasiswa::select('mst_mhs.*')
		->addSelect('keuangan_tagihan.kode as kode_tagihan','keuangan_tagihan.nama as nama_tagihan','keuangan_tagihan.jumlah')
		->addSelect(DB::raw('(select SUM(keuangan_pembayaran.jumlah) FROM keuangan_pembayaran 
		WHERE keuangan_pembayaran.tagihan_id=keuangan_tagihan.id AND keuangan_pembayaran.nim=mst_mhs.nim) as total_bayar'))
		->addSelect(DB::raw('(keuangan_tagihan.jumlah - (select SUM(keuangan_pembayaran.jumlah) FROM keuangan_pembayaran 
		WHERE keuangan_pembayaran.tagihan_id=keuangan_tagihan.id AND keuangan_pembayaran.nim=mst_mhs.nim)) as sisa'))
		->join('keuangan_tagihan','keuangan_tagihan.th_angkatan_id','=','mst_mhs.th_akademik_id')
		->where('keuangan_tagihan.id',$tagihan_id)
		->where('mst_mhs.prodi_id',$prodi_id)
		->with('jk','prodi','kelas','kelompok')
		->orderBy('nim','asc')
		->get();

		$pdf = PDF::loadView($this->folder.'.cetak', compact('data','th_akademik','pt'));		
		return $pdf->setPaper('a4','landscape')->stream('Laporan Piutang '.$th_akademik->kode.'.pdf');
	}

	private function sisa($tagihan_id,$nim){
		$jml_tagihan = KeuanganTagihan::select('jumlah')->where('id',$tagihan_id)->first()->jumlah;
		$jml_bayar	 = KeuanganPembayaran::select(DB::raw('sum(jumlah) as total'))
		->where('tagihan_id',$tagihan_id)->where('nim',$nim)->first()->total;		
		return $jml_tagihan - $jml_bayar;
	}
}
