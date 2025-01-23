<?php
namespace App\Http\Controllers\site_admin;

use App\KRS;
use App\KRSDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Yajra\Datatables\Datatables;
Use Alert;
use App\ThAkademik;
use App\Prodi;
use App\Ref;
use App\JadwalKuliah;
use App\Mahasiswa;
use App\PT;
use PDF;
use App\Pejabat;

class KRSController extends Controller {
	private $title	  = 'Kartu Rencana Studi (KRS)';
	private $redirect = 'krs';
	private $folder	  = 'krs';
	private $class	  = 'krs';

	private $rules = [
		'th_akademik_id' => 'required',
		'tanggal' 		 => 'required|date_format:"d-m-Y"',
		'prodi_id' 		 => 'required',
		'kelas_id' 		 => 'required',
		'nim' 			 => 'required|string|max:20',
		'nama_prodi' 	 => 'required',
		'nama_kelas' 	 => 'required',
		'kelompok' 		 => 'required',
		'keuangan' 		 => 'required',
		'cek_list' 		 => 'required',
	];

	public function index(){
		$title 	  = $this->title;
		$redirect = $this->redirect;
		$folder   = $this->folder;

		$th_akademik_id	 = ThAkademik::Aktif()->first()->id;
		$list_thakademik = ThAkademik::orderBy('kode','DESC')->get();
		
		// $list_prodi = Prodi::get();
		$prodi_id = @strtolower(Auth::user()->prodi->id);
		
		if($prodi_id){
			$list_prodi = Prodi::where('id',$prodi_id)->get();
		}else{
			$list_prodi = Prodi::orderBy('kode','ASC')->get();
		}
		
		$list_kelas = Ref::where('table','Kelas')->get();

		return view($folder.'.index',
			compact('title','redirect','folder','list_thakademik','list_prodi','list_kelas','prodi_id','th_akademik_id')
		);
	}

	public function getData(Request $request){
		$th_akademik_id = $request->th_akademik_id;
		$prodi_id = $request->prodi_id;
		$kelas_id = $request->kelas_id;

		$row = KRS::where('th_akademik_id',$th_akademik_id)
		->when($prodi_id, function ($query) use ($prodi_id) {
			return $query->where('prodi_id',$prodi_id);
		})
		->when($kelas_id, function ($query) use ($kelas_id) {
			return $query->where('kelas_id',$kelas_id);
		})
		->with('th_akademik','prodi','kelas','mahasiswa')
		->get();

		return Datatables::of($row)
		->addColumn('tgl',function($row){
			return  tgl_str($row->tanggal);
		})
		->addColumn('nama_mhs',function($row){
			return  $row->mahasiswa->nama;
		})
		->addColumn('prodi',function($row){
			return  $row->prodi->nama;
		})
		->addColumn('kelas',function($row){
			return  $row->kelas->nama;
		})
		->addColumn('kelompok',function($row){
			return  @$row->mahasiswa->kelompok->perwalian->kelompok->kode;
		})
		->addColumn('sks',function($row){
			return  sks_total($row->th_akademik_id,$row->nim);
		})
		->addColumn('action',function($row){
		  $acc_pa = acc_krs($row->th_akademik_id,$row->nim);
		  $btn = '<div class="btn-group btn-group-xs" id="c-tooltips-demo">';
		  
		  if($acc_pa=='Setujui'){
			$btn = $btn.'<a href="'.url('/'.$this->class.'/'.$row->id.'/cetak').'" class="btn btn-info btn-xs btn-rounded tooltip-info" 
			data-toggle="tooltip" data-placement="top" data-original-title="Print">
			<i class="fa fa-print"></i></a>';
		  }
		  
		  $btn = $btn.'<a href="'.url('/'.$this->class.'/'.$row->id.'/edit').'" class="btn btn-primary btn-xs btn-rounded tooltip-primary" 
		  data-toggle="tooltip" data-placement="top" data-original-title="Edit">
		  <i class="fa fa-pencil"></i></a>';
		  $btn = $btn.'<a onclick="deleteForm('.$row->id.')" class="btn btn-danger btn-xs btn-rounded tooltip-danger" d
		  ata-toggle="tooltip" data-placement="top" data-original-title="Delete">
		  <i class="fa fa-times"></i></a>';
		  $btn = $btn.'</div>';
		  return $btn;
		})
		->rawColumns(['action'])
		->make(true);
		}

	public function cetak($krs_id){
		$krs = KRS::where('id',$krs_id)
		->with('mahasiswa','th_akademik','prodi','kelas')
		->first();
		$th_akademik = ThAkademik::where('id',$krs->th_akademik_id)->first();
		$pt = PT::first();
		// $prodi = @Prodi::where('id',Auth::user()->prodi_id)->first();

		$data = KRSDetail::
		where('krs_id',$krs->id)
		->with('jadwal_kuliah')
		->get();

		// $biro_umum_id = env('BIRO_UMUM_ID');
		// $biro_umum = Pejabat::where('jabatan_id',$biro_umum_id)->first();

		$prodi = @Prodi::where('id',$krs->prodi_id)->first();

		$class = 'text-center';
		// return view($this->folder.'.cetak',compact('data','th_akademik','pt','krs','biro_umum'));
		$pdf = PDF::loadView($this->folder.'.cetak', compact('data','pt','th_akademik','krs','prodi','class'));
		return $pdf->setPaper('a4','portrait')->stream('KRS '.$th_akademik->kode.' '.$krs->nim.'.pdf');
	}

	public function getMhs(Request $request){
		$th_akademik = ThAkademik::Aktif()->first();

		$nim = strtoupper($request->nim);
		$mhs = Mahasiswa::where('nim',$nim)
		->with('prodi','kelas','kelompok','jk','status')
		->first();

		if($mhs){
			$return = [
				'jenis_kelamin' => $mhs->jk->nama,
				'status'		=> $mhs->status->nama,
				'prodi' 		=> $mhs->prodi,
				'kelas' 		=> $mhs->kelas,
				'kelompok' 		=> @$mhs->kelompok->perwalian->kelompok,
				'keuangan' 		=> KeuanganMhs($mhs->nim,$th_akademik->id),
				'sks_total' 	=> sks_total($th_akademik->id,$mhs->nim),
				'smt' 			=> getSemesterMahasiswa($mhs->th_akademik->kode,$mhs->nim),
				'th_angkatan' 	=> @$mhs->th_akademik->kode
			];
		}
		else{
			$return = [
				'jenis_kelamin' => null,
				'status' 		=> null,
				'prodi' 		=> null,
				'kelas' 		=> null,
				'kelompok' 		=> null,
				'keuangan' 		=> null,
				'sks_total' 	=> 0,
				'smt' 			=> null,
				'th_angkatan' 	=> null
			];
		}
		return $return;
	}

	public function getDataMK(Request $request){
		//dd($request->all());
		$th_akademik_id = $request->th_akademik_id;
		$prodi_id		= $request->prodi_id;
		$kelas_id 		= $request->kelas_id;
		$kelompok_id 	= $request->kelompok_id;

		// $data = [
		//   'th_akademik_id'=>$th_akademik_id,
		//   'prodi_id'=>$prodi_id,
		//   'kelas_id'=>$kelas_id,
		//   'kelompok_id'=>$kelompok_id
		// ];
		// dd($data);

		$nim = strtoupper($request->nim);
		$mhs = Mahasiswa::where('nim',$nim)->first();
		
		if($mhs){
			$th_angkatan_id = $mhs->th_akademik_id;
		}else{
			$th_angkatan_id = null;
		}
    
		$row = JadwalKuliah::select('trans_jadwal_kuliah.*')
		->join('trans_kurikulum_matakuliah','trans_kurikulum_matakuliah.id','=','trans_jadwal_kuliah.kurikulum_matakuliah_id')
		->join('trans_kurikulum','trans_kurikulum.id','=','trans_kurikulum_matakuliah.kurikulum_id')
		->join('trans_kurikulum_angkatan','trans_kurikulum_angkatan.kurikulum_id','=','trans_kurikulum.id')

		// ->when($th_angkatan_id, function ($query) use ($th_angkatan_id) {
		//   return $query->where('trans_kurikulum_angkatan.th_akademik_id',$th_angkatan_id);
		//   })
		
		->where('trans_kurikulum_angkatan.th_akademik_id',$th_angkatan_id)
		
		// ->when($th_akademik_id, function ($query) use ($th_akademik_id) {
		//   return $query->where('trans_jadwal_kuliah.th_akademik_id',$th_akademik_id);
		//   })
		
		->where('trans_jadwal_kuliah.th_akademik_id',$th_akademik_id)
		
		// ->when($prodi_id, function ($query) use ($prodi_id) {
		//   return $query->where('trans_jadwal_kuliah.prodi_id',$prodi_id);
		//   })
		
		->where('trans_jadwal_kuliah.prodi_id',$prodi_id)
		
		// ->when($kelas_id, function ($query) use ($kelas_id) {
		//   return $query->where('trans_jadwal_kuliah.kelas_id',$kelas_id);
		//   })
		
		->where('trans_jadwal_kuliah.kelas_id',$kelas_id)
		
		// ->when($kelompok_id, function ($query) use ($kelompok_id) {
		//   return $query->where('trans_jadwal_kuliah.kelompok_id',$kelompok_id);
		//   })

		->where('trans_jadwal_kuliah.kelompok_id',$kelompok_id)
		->with(['kurikulum_matakuliah','dosen','ruang_kelas','hari','kelompok'])
		->orderBy('smt')
		->get();

		return Datatables::of($row)
		->addColumn('cek_list',function($row) use ($th_akademik_id,$nim){
		$krs = KRSDetail::where('th_akademik_id',$th_akademik_id)
		->where('jadwal_kuliah_id',$row->id)
		->where('nim',$nim)
		->first();

		if($krs){
			$krs_nilai = KRSDetail::where('th_akademik_id',$th_akademik_id)
			->where('jadwal_kuliah_id',$row->id)
			->where('nim',$nim)
			->whereNotNull('nilai_huruf')
			->first();

			if($krs_nilai){
				return '<i class="fa fa-check text-success"></i>';
			}else{
				return '<input type="checkbox" name="cek_list[]" id="cek_list_'.
				$row->id.'" value="'.$row->id.'" onClick="cekList(\''.
				$row->id.'\',\''.$row->kurikulum_matakuliah->matakuliah->sks.'\')" checked >';
			}
		}else{
			return '<input type="checkbox" name="cek_list[]" id="cek_list_'.
			$row->id.'" value="'.$row->id.'" onClick="cekList(\''.
			$row->id.'\',\''.$row->kurikulum_matakuliah->matakuliah->sks.'\')"  >';
		}

		// $data = KurikulumMataKuliah::where('kurikulum_id',$kurikulum_id)->where('matakuliah_id',$row->id)->first();
		// if($data)
		// {
		//   $jadwal = JadwalKuliah::where('kurikulum_matakuliah_id',$data->id)->first();
		//   if($jadwal)
		//   {
		//     return '<i class="fa fa-check-square-o text-success"></i>';
		//   }else{
		//     return '<a onclick="deleteDetail('.$data->id.')" class="btn btn-danger btn-xs btn-rounded tooltip-danger" data-toggle="tooltip" data-placement="top" data-original-title="Delete"><i class="fa fa-times"></i></a>';
		//   }
		//   // return '<input type="checkbox" name="cek_list[]" id="cek_list" value="'.$row->id.'" onClick="cekList(\''.$row->id.'\')" checked >';
		// }else{
		//   return '<input type="checkbox" name="cek_list[]" id="cek_list" value="'.$row->id.'" onClick="cekList(\''.$row->id.'\')"  >';
		// }

		})
		->addColumn('kode_mk',function($row){
			return  @$row->kurikulum_matakuliah->matakuliah->kode;
		})
		->addColumn('nama_mk',function($row){
			return  @$row->kurikulum_matakuliah->matakuliah->nama;
			// return  @$row->kurikulum_matakuliah->matakuliah->nama.' - '.@$row->kurikulum_matakuliah->matakuliah->id;
		})
		->addColumn('sks_mk',function($row){
			return  @$row->kurikulum_matakuliah->matakuliah->sks;
		})
		->addColumn('smt_mk',function($row){
			return  @$row->kurikulum_matakuliah->matakuliah->smt;
		})
		->addColumn('kelompok',function($row){
			return  @$row->kelompok->kode;
		})
		->addColumn('dosen',function($row){
			return  @$row->dosen->nama;
		})
		->addColumn('ruang',function($row){
			return  @$row->ruang_kelas->kode;
		})
		->addColumn('hari',function($row){
			return  @$row->hari->nama;
		})
		->addColumn('waktu',function($row){
			return  @$row->jam_mulai.' '.@$row->jam_selesai;
		})
		->addColumn('sisa',function($row){
		  return  '';
		})
		->rawColumns(['cek_list'])
		->make(true);
	}

	public function create(){
		$title		 = $this->title;
		$redirect	 = $this->redirect;
		$folder		 = $this->folder;
		$th_akademik = ThAkademik::Aktif()->first();
		$prodi_id	 = @strtolower(Auth::user()->prodi->id);
		
		if($prodi_id){
			// $list_prodi = Prodi::where('id',$prodi_id)->get();
			$list_mhs = Mahasiswa::where('status_id',18)->where('prodi_id',$prodi_id)
			->orderBy('nim','DESC')->get();
		}
		else{
			// $list_prodi = Prodi::orderBy('kode','ASC')->get();
			$list_mhs = Mahasiswa::where('status_id',18)->orderBy('nim','DESC')->get();
		}

		// $list_mhs = Mahasiswa::where('status_id',18)->orderBy('nim','DESC')->get();
		$nim = null;
		return view($folder.'.create',
			compact('title','redirect','folder','th_akademik','list_mhs','nim')
		);
	}

	public function edit($id){
		$data		 = KRS::findOrFail($id);
		$nim		 = $data->nim;
		$title		 = $this->title;
		$redirect	 = $this->redirect;
		$folder		 = $this->folder;
		$th_akademik = ThAkademik::Aktif()->first();
		$prodi_id	 = @strtolower(Auth::user()->prodi->id);

		if($prodi_id){
			// $list_prodi = Prodi::where('id',$prodi_id)->get();
			$list_mhs = Mahasiswa::where('status_id',18)->where('prodi_id',$prodi_id)
			->orderBy('nim','DESC')->get();
		}else{
			// $list_prodi = Prodi::orderBy('kode','ASC')->get();
			$list_mhs = Mahasiswa::where('status_id',18)->orderBy('nim','DESC')->get();
		}
		
		// $list_mhs = Mahasiswa::where('status_id',18)->orderBy('nim','DESC')->get();
		return view($folder.'.edit',
			compact('data','title','redirect','folder','th_akademik','list_mhs','nim')
		);
	}

	public function store(Request $request){
		// dd($request->all());
		$this->validate($request,$this->rules);
		$krs = KRS::where('th_akademik_id',$request->th_akademik_id)
		->where('nim',$request->nim)->first();

		if(!$krs){
			// alert()->warning('Sudah mengisi KRS.',$this->title.' NIM '.$request->nim);
			// return back()->withInput();
			$krs = new KRS;
		}

		$krs->th_akademik_id = $request->th_akademik_id;
		$krs->prodi_id = $request->prodi_id;
		$krs->kelas_id = $request->kelas_id;
		$krs->nim = strtoupper($request->nim);
		$krs->smt = $request->smt;
		$krs->tanggal = tgl_sql($request->tanggal);
		$krs->user_id = Auth::user()->id;
		$krs->save();

		KRSDetail::where('th_akademik_id',$request->th_akademik_id)
		->where('nim',$request->nim)
		->whereNull('nilai_huruf')
		->delete();

		foreach($request->cek_list as $key=>$value){
			// dd($value);
			$data = KRSDetail::where('th_akademik_id',$request->th_akademik_id)
			->where('nim',$request->nim)
			->where('jadwal_kuliah_id',$value)
			->first();

			if(!$data){
				$data = new KRSDetail;
			}

			$data->krs_id = $krs->id;
			$data->th_akademik_id = $request->th_akademik_id;
			$data->jadwal_kuliah_id = $value;
			$data->nim = strtoupper($request->nim);
			$mhs = Mahasiswa::where('nim',$request->nim)->first();
			$data->nama_mhs = @$mhs->nama;

			$jadwal = JadwalKuliah::where('id',$value)->with('kurikulum_matakuliah')->first();
			// dd($jadwal->kurikulum_matakuliah->matakuliah->kode);
			$data->dosen_id = @$jadwal->dosen->id;
			$data->kode_mk	= @$jadwal->kurikulum_matakuliah->matakuliah->kode;
			$data->nama_mk 	= @$jadwal->kurikulum_matakuliah->matakuliah->nama;
			$data->sks_mk 	= @$jadwal->kurikulum_matakuliah->matakuliah->sks;
			$data->smt_mk 	= @$jadwal->kurikulum_matakuliah->matakuliah->smt;
		
			$data->transkrip = 'Y';
			$data->user_id	 = Auth::user()->id;
			$data->save();
		}

		alert()->success('Input KRS Success',$this->title);
		// return redirect($this->redirect);
		return back()->withInput();
	}

	public function update(Request $request, $id){
		// dd($request->all());
		$this->validate($request,$this->rules);
		$krs = KRS::where('th_akademik_id',$request->th_akademik_id)
		->where('nim',$request->nim)->first();

		if(!$krs){
			// alert()->warning('Sudah mengisi KRS.',$this->title.' NIM '.$request->nim);
			// return back()->withInput();
			$krs = new KRS;
		}

		$krs->th_akademik_id = $request->th_akademik_id;
		$krs->prodi_id = $request->prodi_id;
		$krs->kelas_id = $request->kelas_id;
		$krs->nim	   = strtoupper($request->nim);
		$krs->smt	   = $request->smt;
		$krs->tanggal  = tgl_sql($request->tanggal);
		$krs->user_id  = Auth::user()->id;
		$krs->save();

		KRSDetail::where('th_akademik_id',$request->th_akademik_id)
		->where('nim',$request->nim)
		->whereNull('nilai_huruf')
		->delete();

		foreach($request->cek_list as $key=>$value){
			// dd($value);
			$data = KRSDetail::where('th_akademik_id',$request->th_akademik_id)
			->where('nim',strtoupper($request->nim))
			->where('jadwal_kuliah_id',$value)
			->first();
			
			if(!$data){
				$data = new KRSDetail;
			}

			$data->krs_id = $krs->id;
			$data->th_akademik_id = $request->th_akademik_id;
			$data->jadwal_kuliah_id = $value;
			$data->nim = strtoupper($request->nim);
			
			$mhs = Mahasiswa::where('nim',$request->nim)->first();
			$data->nama_mhs = @$mhs->nama;

			$jadwal = JadwalKuliah::where('id',$value)->with('kurikulum_matakuliah')->first();
			// dd($jadwal->kurikulum_matakuliah->matakuliah->kode);
			$data->dosen_id = @$jadwal->dosen->id;
			$data->kode_mk	= @$jadwal->kurikulum_matakuliah->matakuliah->kode;
			$data->nama_mk 	= @$jadwal->kurikulum_matakuliah->matakuliah->nama;
			$data->sks_mk	= @$jadwal->kurikulum_matakuliah->matakuliah->sks;
			$data->smt_mk   = @$jadwal->kurikulum_matakuliah->matakuliah->smt;
			
			$data->transkrip = 'Y';
			$data->user_id	 = Auth::user()->id;
			$data->save();
		}

		alert()->success('Update KRS Success',$this->title);
		// return redirect($this->redirect);
		return back()->withInput();
	}

	public function destroy($id){
		$data = KRS::findOrFail($id);
		$data->delete();
		KRSDetail::where('krs_id',$id)->delete();
		
		return response()->json([
			'title' => 'Delete Data Success',
			'text' => $this->title.' '.$data->nama,
			'type' => 'success'
		]);
	}
}
