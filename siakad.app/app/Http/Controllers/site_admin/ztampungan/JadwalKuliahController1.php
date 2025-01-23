<?php
namespace App\Http\Controllers\site_admin;

use App\JadwalKuliah;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Yajra\Datatables\Datatables;
Use Alert;
use App\ThAkademik;
use App\Prodi;
use App\Ref;
use App\Kurikulum;
use App\KurikulumMataKuliah;
use App\Dosen;
use App\KRSDetail;

class JadwalKuliahController extends Controller {
	private $title	  = 'Jadwal Kuliah';
	private $redirect = 'jadwalkuliah';
	private $folder	  = 'jadwalkuliah';
	private $class	  = 'jadwalkuliah';

	private $rules = [
		'th_akademik_id' => 'required',
		'prodi_id'		 => 'required',
		'kelas_id'		 => 'required',
		'kelompok_id'	 => 'required',
		'kurikulum_matakuliah_id' => 'required',
		'dosen_id'		 => 'required',
		'ruang_kelas_id' => 'required',
		'hari_id'		 => 'required',
		'jamkul_id'		 => 'required',
		'jam_mulai'		 => 'required',
		'jam_selesai'	 => 'required',
	];

	public function index() {
		// dd("TEST");
		$th_akademik	= ThAkademik::Aktif()->first();
		$th_akademik_id = $th_akademik->id;
		$semester		= $th_akademik->semester;

		$title	  = $this->title;
		$redirect = $this->redirect;
		$folder	  = $this->folder;
		
		$list_thakademik = ThAkademik::where('id',$th_akademik_id)->orderBy('kode','DESC')->get();
		
		// $list_prodi = Prodi::get();
		$prodi_id = @strtolower(Auth::user()->prodi->id);
		if($prodi_id){
			$list_prodi = Prodi::where('id',$prodi_id)->get();
		}else{
			$list_prodi = Prodi::orderBy('kode','ASC')->get();
		}
		
		$list_smt = Ref::where('table','Semester')->where('nama',$semester)->get();
		$list_kurikulum = Kurikulum::orderBy('id','DESC')->get();

		return view($folder.'.index',
			compact('title','redirect','folder','list_thakademik','list_prodi','list_smt','list_kurikulum','prodi_id')
		);
	}

	public function getData(Request $request){
		$th_akademik_id = $request->th_akademik_id;
		$kurikulum_id	= $request->kurikulum_id;
		$prodi_id		= $request->prodi_id;

		$row = KurikulumMataKuliah::where('kurikulum_id',$kurikulum_id)

		// ->when($th_angkatan_id, function ($query) use ($th_angkatan_id) {
		//     return $query->where('th_angkatan_id',$th_angkatan_id);
		// })
		// ->when($prodi_id, function ($query) use ($prodi_id) {
		//     return $query->where('prodi_id',$prodi_id);
		// })
		// ->when($kelas_id, function ($query) use ($kelas_id) {
		//     return $query->where('kelas_id',$kelas_id);
		// })
		
		->with('kurikulum','matakuliah')->get();

		return Datatables::of($row)
			->addColumn('kd_mk',function($row){return $row->matakuliah->kode;})
			->addColumn('nama_mk',function($row){return $row->matakuliah->nama;})
			->addColumn('sks_mk',function($row){return $row->matakuliah->sks;})
			->addColumn('smt_mk',function($row){return $row->matakuliah->smt;})
			->addColumn('jml_kelompok',function($row){return JadwalKuliah::where('kurikulum_matakuliah_id',$row->id)->count();})
			->setRowClass(function ($row) { $jml = JadwalKuliah::where('kurikulum_matakuliah_id',$row->id)->count();
			return $jml>0 ? 'alert-success' : 'alert-danger';
		})

		->addColumn('action',function($row) use ($th_akademik_id,$prodi_id,$kurikulum_id){
			return '<div class="btn-group btn-group-xs" id="c-tooltips-demo">
			<a href="'.url('/'.$this->class.'/'.$th_akademik_id.'/'.$prodi_id.'/'.$kurikulum_id.'/'.$row->id.'/createDetail').
			'" class="btn btn-primary btn-xs btn-rounded tooltip-primary" data-toggle="tooltip" 
			data-placement="top" data-original-title="Create" target="_blank">
			<i class="fa fa-plus"></i></a>
            </div>';
          
            // <a onclick="deleteForm('.$row->id.')" class="btn btn-danger btn-xs btn-rounded tooltip-danger" data-toggle="tooltip" data-placement="top" data-original-title="Delete"><i class="fa fa-times"></i></a>
			// return '<button class="btn btn-primary btn-xs btn-rounded" data-toggle="modal" data-target="#myModal"> <i class="fa fa-plus"></i></button>';
		})
		->addColumn('details_url', function($row){
		  	// $jadwal = JadwalKuliah::where('th_akademik_id',$th_akademik_id)
			// $jadwal_id = $jadwal;
			return url($this->folder.'/getDetailsData/'.$row->id);
		})
		->rawColumns(['action'])->make(true);
	}

	public function getListKurikulum($prodi_id){
		$kurikulum = Kurikulum::where('prodi_id',$prodi_id)->get();
		echo '<option value="">-Pilih-</option>';

		foreach($kurikulum as $row){
			echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
		}
	}

	public function getDetailsData($id){
		$row = JadwalKuliah::
		where('kurikulum_matakuliah_id',$id)
		->with('kelas','kelompok','hari','ruang_kelas','dosen')->get();

		return Datatables::of($row)
		->addColumn('kelas',function($row){
			return $row->kelas->nama;
		})
		->addColumn('kelompok',function($row){return $row->kelompok->kode;})
		->addColumn('hari',function($row){return $row->hari->nama;})
		->addColumn('ruang',function($row){return $row->ruang_kelas->nama;})
		->addColumn('waktu',function($row){return $row->jam_mulai.' - '.$row->jam_selesai;
		})
		
		/* 
		->addColumn('jamkuliah',function($row){return $row->jamkuliah->nama;})
		*/
		
		->addColumn('dosen',function($row){
			return $row->dosen->kode.' - '.$row->dosen->nama;
		})
		->addColumn('jml_mhs',function($row){
		$krs_detail = KRSDetail::where('jadwal_kuliah_id',$row->id);
			return $krs_detail->count();
		})
		->addColumn('action',function($row){
			return '<div class="btn-group btn-group-xs">
			<a href="'.$this->class.'/'.$row->id.'/edit" class="btn btn-primary btn-xs btn-alt"><i class="fa fa-pencil"></i></a>
			<a onclick="deleteForm('.$row->id.')" class="btn btn-danger btn-xs btn-alt"><i class="fa fa-times"></i> </a>
			</div>';
		})
		->rawColumns(['action'])
		->make(true);
	}

	// public function create()
	// {
	//   $title = $this->title;
	//   $redirect = $this->redirect;
	//   $folder = $this->folder;
	//   $list_thakademik = ThAkademik::orderBy('kode','DESC')->get();
	//   $list_thangkatan = ThAkademik::where('semester','Ganjil')->orderBy('kode','DESC')->get();
	//   $list_prodi = Prodi::get();
	//   $list_kelas = Ref::where('table','Kelas')->get();
	//
	//   return view($folder.'.create',
	//     compact('data','title','redirect','folder','list_thakademik','list_thangkatan','list_prodi','list_kelas')
	//   );
	// }

	public function createDetail($th_akademik_id,$prodi_id,$kurikulum_id,$kurikulum_matakuliah_id){
		// dd($th_akademik_id);
		$title	 	 = $this->title;
		$redirect	 = $this->redirect;
		$folder	 	 = $this->folder;
		$th_akademik = ThAkademik::where('id',$th_akademik_id)->first();
		$prodi		 = Prodi::where('id',$prodi_id)->first();
		$kurikulum	 = Kurikulum::where('id',$kurikulum_id)->first();
		$kurikulum_matakuliah = KurikulumMataKuliah::where('id',$kurikulum_matakuliah_id)->with('matakuliah')->first();

		// $data = null;
		return view($folder.'.create',
			compact('title','redirect','folder','th_akademik','prodi','kurikulum','kurikulum_matakuliah')
		);
	}

	public function edit($id){
		$title	  = $this->title;
		$redirect = $this->redirect;
		$folder	  = $this->folder;

		$data = JadwalKuliah::findOrFail($id);
		$th_akademik_id = $data->th_akademik_id;
		$prodi_id = $data->prodi_id;
		$kurikulum_matakuliah_id = $data->kurikulum_matakuliah->id;
		$kurikulum_id = $data->kurikulum_matakuliah->kurikulum->id;
		$smt = $data->smt;

		$th_akademik = ThAkademik::where('id',$th_akademik_id)->first();
		$prodi = Prodi::where('id',$prodi_id)->first();
		$kurikulum = Kurikulum::where('id',$kurikulum_id)->first();
		$kurikulum_matakuliah = KurikulumMataKuliah::where('id',$kurikulum_matakuliah_id)->with('matakuliah')->first();
		
		return view($folder.'.edit',
			compact('data','title','redirect','folder','th_akademik','prodi','smt','kurikulum','kurikulum_matakuliah','data')
		);
	}

	public function store(Request $request){		
		$this->validate($request,$this->rules);
		$data = JadwalKuliah::where('th_akademik_id',$request->th_akademik_id)
		->where('hari_id',$request->hari_id)
		->where('dosen_id',$request->dosen_id)
		->whereTime('jam_selesai','>=',$request->jam_mulai)
		->whereTime('jam_selesai','<=',$request->jam_selesai)->first();
		
		/*
		->where('jamkul',$request->jamkul_id)->first();
		*/
				
		/*
		->where('kelas_id',$request->kelas_id)
		->where('kelompok_id',$request->kelompok_id)
		->where('ruang_kelas_id',$request->ruang_kelas_id)
		->whereTime('jam_selesai','>=',$request->jam_mulai)
		->whereTime('jam_selesai','<=',$request->jam_selesai)->first();
		*/
		
		// dd($data);
		if($data){
			alert()->error('Jadwal Bentrok...',$this->title);
			return back()->withInput();
		}
		
		$data = new JadwalKuliah;
		$data->th_akademik_id 			= $request->th_akademik_id;
		$data->kurikulum_matakuliah_id 	= $request->kurikulum_matakuliah_id;
		$data->prodi_id					= $request->prodi_id;
		$data->kelas_id					= $request->kelas_id;
		$data->kelompok_id				= $request->kelompok_id;
		$data->smt 						= $request->smt;
		$data->dosen_id 				= $request->dosen_id;
		$data->hari_id 					= $request->hari_id;
		$data->ruang_kelas_id 			= $request->ruang_kelas_id;
		//$data->jamkul_id 				= $request->jamkul_id;
		$data->jam_mulai 				= $request->jam_mulai;
		$data->jam_selesai 				= $request->jam_selesai;
		$data->user_id 					= Auth::user()->id;
		$data->save();

		alert()->success('Create Data Sukses...',$this->title);
		// return redirect($this->redirect);
		return back()->withInput();
	}

	public function update(Request $request, $id){
		$this->validate($request,$this->rules);
		$data = JadwalKuliah::where('th_akademik_id',$request->th_akademik_id)
		->where('hari_id',$request->hari_id)
		->where('dosen_id',$request->dosen_id)
		->where('kelas_id',$request->kelas_id)
		->where('kelompok_id',$request->kelompok_id)
		->where('ruang_kelas_id',$request->ruang_kelas_id)
		->whereTime('jam_selesai','>=',$request->jam_mulai)
		->whereTime('jam_selesai','<=',$request->jam_selesai)->first();
		
		/*
		->whereTime('jam_selesai','>=',$request->jam_mulai)
		->whereTime('jam_selesai','<=',$request->jam_selesai)->first();

		$data = JadwalKuliah::where('th_akademik_id',$request->th_akademik_id)
		->where('hari_id',$request->hari_id)
		->whereTime('jam_mulai','>=',$request->jam_mulai)
		->whereTime('jam_selesai','<=',$request->jam_selesai)
		->first();
		*/
		
		// dd($data);
		if($data){
			alert()->error('Jadwal Bentrok...',$this->title);
			return back()->withInput();
		}

		$data = JadwalKuliah::findOrFail($id);
		$data->th_akademik_id			= $request->th_akademik_id;
		$data->kurikulum_matakuliah_id 	= $request->kurikulum_matakuliah_id;
		$data->prodi_id 				= $request->prodi_id;
		$data->kelas_id					= $request->kelas_id;
		$data->kelompok_id				= $request->kelompok_id;
		$data->smt						= $request->smt;
		$data->dosen_id					= $request->dosen_id;
		$data->hari_id					= $request->hari_id;
		$data->ruang_kelas_id			= $request->ruang_kelas_id;
		//$data->jamkul_id				= $request->jamkul_id;
		$data->jam_mulai				= $request->jam_mulai;
		$data->jam_selesai				= $request->jam_selesai;
		$data->user_id					= Auth::user()->id;
		$data->save();

		$krs_detail = KRSDetail::where('jadwal_kuliah_id',$id)->first();
		if($krs_detail){
			$krs_detail->dosen_id = $request->dosen_id;
			$krs_detail->save();
		}

		alert()->success('Update Data Sukses',$this->title);
		// toast('Success Update','success','top-right');
		return redirect($this->redirect);
		// ->withInput($request->except('prodi_id'));
	}

	public function destroy($id){
		$data = JadwalKuliah::findOrFail($id);
		$data->delete();
		return response()->json([
			'title' => 'Delete Data Success',
			'text' => $this->title.' '.$data->nama,
			'type' => 'success'
		]);
  }
}
