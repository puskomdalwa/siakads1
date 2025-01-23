<?php
namespace App\Http\Controllers\site_admin;

use App\KeuanganDispensasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Yajra\Datatables\Datatables;
Use Alert;
use App\ThAkademik;
use App\Prodi;
use App\Ref;
use App\Mahasiswa;

class KeuanganDispensasiController extends Controller {
	private $title	  = 'Dispensasi Keuangan';
	private $redirect = 'keuangandispensasi';
	private $folder   = 'keuangandispensasi';
	private $class	  = 'keuangandispensasi';

	private $rules = [
		'th_akademik_id'=> 'required',
		'nim' 			=> 'required|string|max:20',
		'nama_mhs'	 	=> 'required',
		'nama_prodi' 	=> 'required',
		'nama_kelas' 	=> 'required',
	];

	public function index(){
		$title	  			= $this->title;
		$redirect 			= $this->redirect;
		$folder 			= $this->folder;
		$list_thakademik 	= ThAkademik::orderBy('kode','DESC')->get();
		$list_thangkatan 	= ThAkademik::where('semester','Ganjil')->orderBy('kode','DESC')->get();
		$list_kelas 		= Ref::where('table','Kelas')->get();

		$level = strtolower(Auth::user()->level->level);
		$prodi_id = @strtolower(Auth::user()->prodi->id);

		if($level=='prodi'){
			$list_prodi = Prodi::where('id',$prodi_id)->get();
		}else{
			$list_prodi = Prodi::get();
		}
		
		return view($folder.'.index',
			compact('title','redirect','folder','list_thakademik','list_prodi','list_kelas','list_thangkatan','level')
		);
	}

	public function getData(Request $request){
		$th_akademik_id = $request->th_akademik_id;
		$th_angkatan_id = $request->th_angkatan_id;
		$prodi_id 		= $request->prodi_id;
		$kelas_id 		= $request->kelas_id;

		$row = KeuanganDispensasi::select('keuangan_dispensasi.*')
		->join('mst_mhs','mst_mhs.nim','=','keuangan_dispensasi.nim')
		->where('keuangan_dispensasi.th_akademik_id',$th_akademik_id)
		->when($th_angkatan_id, function ($query) use ($th_angkatan_id) {
			return $query->where('mst_mhs.th_akademik_id',$th_angkatan_id);
		})
		->when($prodi_id, function ($query) use ($prodi_id) {
			return $query->where('mst_mhs.prodi_id',$prodi_id);
		})
		->when($kelas_id, function ($query) use ($kelas_id) {
			return $query->where('mst_mhs.kelas_id',$kelas_id);
		})
		->with('th_akademik','mahasiswa')->get();

		return Datatables::of($row)
		->addColumn('th_akademik',function($row){
			return  $row->th_akademik->kode;
		})
		->addColumn('nama_mhs',function($row){
			return  $row->mahasiswa->nama;
		})
		->addColumn('jk',function($row){
			return  $row->mahasiswa->jk->kode;
		})
		->addColumn('prodi',function($row){
			return  $row->mahasiswa->prodi->nama;
		})
		->addColumn('kelas',function($row){
			return  $row->mahasiswa->kelas->nama;
		})
		->addColumn('kelompok',function($row){
			return  $row->mahasiswa->kelompok->perwalian->kelompok->kode;
		})
		->addColumn('action',function($row){
			return '<div class="btn-group btn-group-xs" id="c-tooltips-demo">
				<a href="'.url('/'.$this->class.'/'.$row->id.'/edit').'" class="btn btn-primary btn-xs btn-rounded tooltip-primary" data-toggle="tooltip" data-placement="top" data-original-title="Edit"><i class="fa fa-pencil"></i></a>
				<a onclick="deleteForm('.$row->id.')" class="btn btn-danger btn-xs btn-rounded tooltip-danger" data-toggle="tooltip" data-placement="top" data-original-title="Delete"><i class="fa fa-times"></i></a>
				</div>';
		})
		->rawColumns(['action','txt_aktif'])->make(true);
	}

	public function create(){
		$title	  		= $this->title;
		$redirect 		= $this->redirect;
		$folder	  		= $this->folder;
		$list_thakademik= ThAkademik::orderBy('kode','DESC')->get();
		$list_prodi 	= Prodi::get();
		$list_kelas		= Ref::where('table','Kelas')->get();
	
		return view($folder.'.create',
			compact('title','redirect','folder','list_thakademik','list_prodi','list_kelas')
		);
	}

	public function getMhs(Request $request){
		$nim = $request->nim;
		$mhs = Mahasiswa::where('nim',$nim)
		->with(['prodi','kelas'])->first();
	
		if($mhs){
			$return = [
				'nama_mhs'	 => $mhs->nama,
				'nama_prodi' => $mhs->prodi->nama,
				'nama_kelas' => $mhs->kelas->nama,
			];
		}else{
			$return = [
				'nama_mhs'	 => null,
				'nama_prodi' => null,
				'nama_kelas' => null,
			];
		}
		return $return;
	}

	public function edit($id){
		$data	  		= KeuanganDispensasi::findOrFail($id);
		$title	  		= $this->title;
		$redirect 		= $this->redirect;
		$folder	  		= $this->folder;
		$list_thakademik= ThAkademik::orderBy('kode','DESC')->get();
		$list_prodi 	= Prodi::get();
		$list_kelas 	= Ref::where('table','Kelas')->get();
	
		return view($folder.'.edit',
			compact('data','title','redirect','folder','list_thakademik','list_prodi','list_kelas')
		);
	}

	public function store(Request $request){
		$this->validate($request,$this->rules);
		$data = KeuanganDispensasi::where('th_akademik_id',$request->th_akademik_id)
		->where('nim',$request->nim)->first();

		if($data){
		  alert()->error('Error NIM ',$this->title.' NIM '.$request->nim.' sudah ada.');
		  return back()->withInput();
		}

		$data = new KeuanganDispensasi;
		$data->th_akademik_id	= $request->th_akademik_id;
		$data->nim		 		= $request->nim;
		$data->keterangan		= $request->keterangan;
		$data->user_id	 		= Auth::user()->id;
		$data->save();

		$mhs = Mahasiswa::where('nim',$data->nim)->first();
		if($mhs){
			$mhs->status_id = 18; 
			$mhs->user_id = Auth::user()->id;
			$mhs->save();
		}

		alert()->success('Create Data Success',$this->title);
		return back()->withInput();
	}

	public function update(Request $request, $id){
		$this->validate($request,$this->rules);

		$data = KeuanganDispensasi::findOrFail($id);
		$data->th_akademik_id 	= $request->th_akademik_id;
		$data->nim 				= $request->nim;
		$data->keterangan		= $request->keterangan;
		$data->user_id			= Auth::user()->id;
		$data->save();

		$mhs = Mahasiswa::where('nim',$data->nim)->first();
		if($mhs){
		  $mhs->status_id = 18; 
		  $mhs->user_id = Auth::user()->id;
		  $mhs->save();
		}

		alert()->success('Update Data Success',$this->title);
		return back()->withInput();
	}

	public function destroy($id){
		$data = KeuanganDispensasi::findOrFail($id);
		$data->delete();
		return response()->json([
			'title' => 'Delete Data Success',
			'text'  => $this->title.' '.$data->nama,
			'type'  => 'success'
		]);
	}
}
