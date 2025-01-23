<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;

use DB;
use PDF;
use Auth;
Use Alert;
use App\PT;
use App\Ref;
use App\Prodi;
use App\Mahasiswa;
use App\ThAkademik;
use App\KeuanganTagihan;
use App\KeuanganPembayaran;

class KeuanganPembayaranController extends Controller {

	private $title	  = 'Pembayaran Keuangan';
	private $redirect = 'keuanganpembayaran';
	private $folder	  = 'keuanganpembayaran';
	private $class	  = 'keuanganpembayaran';

	private $rules = [
		'th_akademik_id' => 'required',
		'nama_prodi' 	 => 'required',
		'nama_kelas' 	 => 'required',
		'tagihan_id' 	 => 'required',
		'nim'		 	 => 'required|string|max:20',
		'nama_mhs'	 	 => 'required|string|max:100',
		'jumlah_tagihan' => 'required|numeric',
		'jumlah' 		 => 'required|numeric',
	];

	public function index(){
		$title	  = $this->title;
		$redirect = $this->redirect;
		$folder	  = $this->folder;
					
		$th_akademik_id  = ThAkademik::Aktif()->first()->id;
		$list_thakademik = ThAkademik::orderBy('kode','Desc')->get();
		
		$th_angkatan_id  = ThAkademik::Aktif()->first()->id;
		$list_thangkatan = ThAkademik::where('semester','Ganjil')->orderBy('kode','Desc')->get();
		
		$level	  = strtolower(Auth::user()->level->level);
		$prodi_id = @strtolower(Auth::user()->prodi->id);

		if($level=='prodi'){
			$list_prodi = Prodi::where('id',$prodi_id)->get();
		}else{
			$list_prodi = Prodi::get();
		}

		$kelas_id	  = Ref::where('table','Kelas')->first()->id;
		$list_kelas   = Ref::where('table','Kelas')->get();
		
		$tagihan_id	  = KeuanganTagihan::orderBy('nama')->first()->id;
		$list_tagihan = KeuanganTagihan::orderBy('nama')->get();

		return view($folder.'.index', compact('title','redirect','folder',
			'th_akademik_id','th_angkatan_id','list_thakademik','list_thangkatan','level',
			'list_prodi','prodi_id','list_kelas','kelas_id','list_tagihan','tagihan_id')
		);
	}

	public function getData(Request $request){
		$th_akademik_id = $request->th_akademik_id;
		$th_angkatan_id = $request->th_angkatan_id;
		
		$prodi_id = $request->prodi_id;
		$kelas_id = $request->kelas_id;
		
		$tgl1 		= $request->tgl1;
		$tgl2 		= $request->tgl2;
		$tagihan_id = $request->tagihan_id;

		$row = KeuanganPembayaran::select('keuangan_pembayaran.*')
		->join('keuangan_tagihan','keuangan_tagihan.id','=','keuangan_pembayaran.tagihan_id')
		->where('keuangan_pembayaran.th_akademik_id',$th_akademik_id)
		->when($tagihan_id, function ($query) use ($tagihan_id) {
			return $query->where('keuangan_pembayaran.tagihan_id',$tagihan_id);
		})
		->when($th_angkatan_id, function ($query) use ($th_angkatan_id) {
			return $query->where('keuangan_tagihan.th_angkatan_id',$th_angkatan_id);
		})
		->when($prodi_id, function ($query) use ($prodi_id) {
			return $query->where('keuangan_tagihan.prodi_id',$prodi_id);
		})
		->when($kelas_id, function ($query) use ($kelas_id) {
			return $query->where('keuangan_tagihan.kelas_id',$kelas_id);
		})
		->when($tgl1, function ($query) use ($tgl1) {
			return $query->whereDate('keuangan_pembayaran.tanggal','>=',tgl_sql($tgl1));
		})
		->when($tgl2, function ($query) use ($tgl2) {
			return $query->whereDate('keuangan_pembayaran.tanggal','<=',tgl_sql($tgl2));
		})
		->with('th_akademik','mahasiswa','tagihan')->get();

		return Datatables::of($row)
		->addColumn('th_akademik',function($row){
			return  @$row->th_akademik->kode;
		})
		->addColumn('tgl_bayar',function($row){
			return  tgl_str($row->tanggal);
		})
		->addColumn('nama_mhs',function($row){
			return  @$row->mahasiswa->nama;
		})
		->addColumn('jk',function($row){
			return  @$row->mahasiswa->jk->kode;
		})
		->addColumn('alias',function($row){
			return  @$row->mahasiswa->prodi->alias;
		})
		->addColumn('prodi',function($row){
			return  @$row->mahasiswa->prodi->nama;
		})
		->addColumn('kelas',function($row){
			return  @$row->mahasiswa->kelas->nama;
		})
		->addColumn('klp',function($row){
			return  @$row->mahasiswa->kelompok->perwalian->kelompok->kode;
		})
		->addColumn('nama_tagihan',function($row){
			return  @$row->tagihan->nama.' '.@$row->tagihan->th_akademik->kode;
		})
		->addColumn('action',function($row){
			return '<div class="btn-group btn-group-xs" id="c-tooltips-demo">
			<a href="'.url('/'.$this->class.'/'.$row->id.'/cetakKwitansi').'" 
			class="btn btn-info btn-xs btn-rounded tooltip-info" 
			data-toggle="tooltip" data-placement="top" data-original-title="Cetak">
			<i class="fa fa-print"></i></a>
			
			<a onclick="deleteForm('.$row->id.')" class="btn btn-danger btn-xs btn-rounded tooltip-danger" 
			data-toggle="tooltip" data-placement="top" data-original-title="Delete">
			<i class="fa fa-times"></i></a>
			</div>';
		})
		->rawColumns(['action','txt_aktif'])->make(true);
	}

	public function getMhs(Request $request){
		$nim = strtoupper($request->nim);
		$mhs = Mahasiswa::where('nim',$nim)->with(['prodi','kelas'])->first();

		if($mhs){
			$tagihan = KeuanganTagihan::where('th_angkatan_id',$mhs->th_akademik_id)
			->where('prodi_id',$mhs->prodi_id)
			->where('kelas_id',$mhs->kelas_id)->get();
			
			$list_tagihan = "<option value=''>-Pilih-</option>";

			foreach($tagihan as $row){
				$th_akademik = $row->th_akademik->kode;
				$sisa = getSisaTagihan($mhs->nim, $row->id);

				if($sisa>0){
					$list_tagihan.= "<option value='$row->id'>".$row->kode.' - '.$row->nama.' '.
					$th_akademik.' - Rp.'.number_format($sisa)."</option>";
				}
			}

			$return = [
				'nama_mhs'	   	=> $mhs->nama,
				'nama_prodi'   	=> @$mhs->prodi->nama,
				'nama_kelas'   	=> @$mhs->kelas->nama,
				'list_tagihan' 	=> $list_tagihan,
				'smt'		   	=> getSemesterMahasiswa($mhs->th_akademik->kode,$mhs->nim),
				'angkatan'	   	=> @$mhs->th_akademik->kode,
			];
		}else{
			$return = [
				'nama_mhs'	   => null,
				'nama_prodi'   => null,
				'nama_kelas'   => null,
				'list_tagihan' => null,
				'smt'		   => null,
				'angkatan'	   => null,
			];
		}
		return $return;
	}

	public function getJmlTagihan(Request $request){
		$nim = $request->nim;
		$tagihan_id = $request->tagihan_id;
		$tagihan	= KeuanganTagihan::where('id',$tagihan_id)->first();

		$return = [
			'jumlah_tagihan' => getSisaTagihan($nim,$tagihan_id),
			'x_sks' => $tagihan->x_sks=='Y'?'Y':'T'
		];
		return $return;
	}

	public function create(){
		$title	  = $this->title;
		$redirect = $this->redirect;
		$folder	  = $this->folder;
		
		$th_akademik	= ThAkademik::Aktif()->first();
		$th_akademik_id = $th_akademik->id;
		
		$list_thakademik = ThAkademik::where('id',$th_akademik_id)->orderBy('kode','DESC')->get();
		$nomor = $this->generateNomor();
	
		return view($folder.'.create', 
			compact('title','redirect','folder','list_thakademik','nomor')
		);
	}

	private function generateNomor(){
		$th	 = date('Y');
		$row = KeuanganPembayaran::select(DB::raw('right(nomor,7) as nomor_akhir'))
		->whereYear('tanggal',$th)->orderBy('nomor','DESC')->limit(1)->first();
	
		if(isset($row)){
			$akhir	= (int) $row->nomor_akhir+1;
			$return = $th.sprintf("%07s",$akhir);
		}
		else{
			$return = $th.'0000001';
		}
		return $return;
	}

	public function edit($id){
		$th_akademik	= ThAkademik::Aktif()->first();
		$th_akademik_id = $th_akademik->id;

		$data	  = KeuanganPembayaran::findOrFail($id);
		$title	  = $this->title;
		$redirect = $this->redirect;
		$folder	  = $this->folder;

		$list_thakademik = ThAkademik::where('id',$th_akademik_id)->orderBy('kode','DESC')->get();
		$nomor = $data->nomor;
	
		return view($folder.'.edit',
			compact('data','title','redirect','folder','list_thakademik','nomor')
		);
	}

	public function store(Request $request){
		$this->validate($request,$this->rules);

		$data = KeuanganPembayaran::where('nomor',$request->nomor)->first();		
		if(!$data){			
			$send_idn = true;
			if($send_idn){
				$data = [
					'amount'	 => $request->jumlah,
					'nim'		 => strtoupper($request->nim),
					'tagihan_id' => $request->tagihan_id,
				];
				
				$url = 'http://dwidn.dalwa.ac.id/api/offline_payment';
				
				$data	 = json_encode($data, JSON_UNESCAPED_SLASHES);
				$headers = ['Content-Type: application/json'];

				$curlHandle = curl_init($url);
				curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);
				curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);
				$exec = curl_exec($curlHandle);
			}
			
			/* ====================================================================== */
			
			$data = new KeuanganPembayaran;
			$data->th_akademik_id	= $request->th_akademik_id;

			$data->nomor	  	= $request->nomor;
			$data->tanggal		= tgl_sql($request->tanggal);
			$data->tagihan_id	= $request->tagihan_id;
			
			$data->nim		= strtoupper($request->nim);
			$data->jumlah  	= $request->jumlah;
			$data->smt	   	= $request->smt;
			$data->jml_sks	= $request->jml_sks;
			$data->user_id	= Auth::user()->id;
			$data->save();

			$mhs = Mahasiswa::where('nim',$data->nim)->first();
			if($mhs){
				$mhs->status_id = 18; //status Aktif
				$mhs->user_id = Auth::user()->id;
				$mhs->save();
			}

			alert()->success('Pembayaran Berhasil di Simpan',$this->title.' NIM '.$data->nim);
		}else{
			alert()->error('Nomor Pembayaran sudah digunakan',$this->title);
		}
		
		return back()->withInput();
	}

	public function cetak(Request $request){
		$th_akademik_id = $request->th_akademik_id;
		$th_angkatan_id = $request->th_angkatan_id;

		$prodi_id = $request->prodi_id;
		$kelas_id = $request->kelas_id;

		$tgl1 		= $request->tgl1;
		$tgl2 		= $request->tgl2;
		$tagihan_id = $request->tagihan_id;

		$pt = PT::first();
		$th_akademik = ThAkademik::where('id',$th_akademik_id)->first();

		$data = KeuanganPembayaran::
		select('keuangan_pembayaran.*')
		->join('keuangan_tagihan','keuangan_tagihan.id','=','keuangan_pembayaran.tagihan_id')
		->where('keuangan_pembayaran.th_akademik_id',$th_akademik_id)
		->when($tagihan_id, function ($query) use ($tagihan_id) {
			return $query->where('keuangan_pembayaran.tagihan_id',$tagihan_id);
		})
		->when($th_angkatan_id, function ($query) use ($th_angkatan_id) {
			return $query->where('keuangan_tagihan.th_angkatan_id',$th_angkatan_id);
		})
		->when($prodi_id, function ($query) use ($prodi_id) {
			return $query->where('keuangan_tagihan.prodi_id',$prodi_id);
		})
		->when($kelas_id, function ($query) use ($kelas_id) {
			return $query->where('keuangan_tagihan.kelas_id',$kelas_id);
		})
		->when($tgl1, function ($query) use ($tgl1) {
			return $query->whereDate('keuangan_pembayaran.tanggal','>=',tgl_sql($tgl1));
		})
		->when($tgl2, function ($query) use ($tgl2) {
			return $query->whereDate('keuangan_pembayaran.tanggal','<=',tgl_sql($tgl2));
		})
		->with('th_akademik','mahasiswa','tagihan')
		->orderBy('tanggal','desc')->get();

		$pdf = PDF::loadView($this->folder.'.cetak', compact('data','th_akademik','pt'));
		return $pdf->setPaper('a4','landscape')->stream('Laporan Pembayaran '.@$th_akademik->kode.'.pdf');
	}

	public function cetakKwitansi($id){
		$keu = KeuanganPembayaran::where('id',$id)->orWhere('nomor',$id)->first();
		
		$pt = PT::first();
		$data = KeuanganPembayaran::select('keuangan_pembayaran.*')
		->join('keuangan_tagihan','keuangan_tagihan.id','=','keuangan_pembayaran.tagihan_id')
		->where('keuangan_pembayaran.id',$keu->id)
		->with('th_akademik','mahasiswa','tagihan')->orderBy('tanggal','desc')->first();

		$th_akademik = ThAkademik::where('id',$data->th_akademik_id)->first();

		$pdf = PDF::loadView($this->folder.'.cetakKwitansi', compact('data','th_akademik','pt'));
		return $pdf->setPaper('a4','portrait')->download('Kwitansi Pembayaran '.$data->nomor.'.pdf');
	}

	public function destroy($id){
		$data = KeuanganPembayaran::findOrFail($id);
		$mhs  = Mahasiswa::where('nim',$data->nim)->first();
		$mhs->status_id = 20;
		$mhs->user_id = Auth::user()->id;
		$mhs->save();

		$data->delete();
		return response()->json([
			'title' => 'Delete Data Success. Status Mahasiswa NIM '.$data->nim.' Menjadi Non Aktif.',
			'text'  => $this->title.' '.$data->nama,
			'type'  => 'success'
		]);
	}
}	
