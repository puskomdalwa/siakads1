<?php
namespace App\Http\Controllers\site_admin;

use App\KeuanganTagihan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Yajra\Datatables\Datatables;
Use Alert;
use App\Mahasiswa;
use App\ThAkademik;
use App\Prodi;
use App\Ref;
use App\FormSchadule;

class KeuanganTagihanController extends Controller {
	private $title	  = 'Tagihan Keuangan';
	private $redirect = 'keuangantagihan';
	private $folder	  = 'keuangantagihan';
	private $class	  = 'keuangantagihan';

	private $rules = [
		'th_akademik_id' => 'required',
		'prodi_id' => 'required',
		'kelas_id' => 'required',
		'nama'	   => 'required|string|max:100',
		'jumlah'   => 'required|numeric',
	];

	public function index(){
		$title	  = $this->title;
		$redirect = $this->redirect;
		$folder	  = $this->folder;
		
		$th_akademik_id	 = ThAkademik::Aktif()->first()->id;
		// dd($th_akademik_id);
		$list_thakademik = ThAkademik::orderBy('kode','DESC')->get();
		$list_thangkatan = ThAkademik::where('semester','Ganjil')->orderBy('kode','DESC')->get();
		
		$list_prodi = Prodi::orderBy('kode','ASC')->get();
		$list_kelas = Ref::where('table','Kelas')->get();		

		return view($folder.'.index', compact('title','redirect','folder','list_thakademik',
			'list_prodi','list_kelas','list_thangkatan','th_akademik_id')
		);
	}

	public function getData(Request $request){
		$th_akademik_id = $request->th_akademik_id;
		$th_angkatan_id = $request->th_angkatan_id;
		$prodi_id = $request->prodi_id;
		$kelas_id = $request->kelas_id;

		$row = KeuanganTagihan::where('th_akademik_id',$th_akademik_id)
		->when($th_angkatan_id, function ($query) use ($th_angkatan_id){
			return $query->where('th_angkatan_id',$th_angkatan_id);
		})
		->when($prodi_id, function ($query) use ($prodi_id) {
			return $query->where('prodi_id',$prodi_id);
		})
		->when($kelas_id, function ($query) use ($kelas_id) {
			return $query->where('kelas_id',$kelas_id);
		})
		->with('th_akademik','prodi','kelas','form_schadule')->get();

		return Datatables::of($row)
		->addColumn('th_akademik',function($row){
			return  $row->th_akademik->kode;
		})
		->addColumn('th_angkatan',function($row){
			return  substr($row->th_angkatan->kode,0,4);
		})
		->addColumn('prodi',function($row){
			return  $row->prodi->alias;
		})
		->addColumn('kelas',function($row){
			return  $row->kelas->nama;
		})
		->addColumn('form_schadule',function($row){
			return  @$row->form_schadule->nama;
		})
		->addColumn('action',function($row){
			return '<div class="btn-group btn-group-xs" id="c-tooltips-demo">
			<a href="'.url('/'.$this->class.'/'.$row->id.'/edit').'" 
			class="btn btn-primary btn-xs btn-rounded tooltip-primary" 
			data-toggle="tooltip" data-placement="top" data-original-title="Edit">
			<i class="fa fa-pencil"></i></a>
			
			<a onclick="deleteForm('.$row->id.')" 
			class="btn btn-danger btn-xs btn-rounded tooltip-danger" 
			data-toggle="tooltip" data-placement="top" data-original-title="Delete">
			<i class="fa fa-times"></i></a>
			</div>';
		})
		->rawColumns(['action','txt_aktif'])->make(true);
	}

	public function create(){
		$title	  = $this->title;
		$redirect = $this->redirect;
		$folder	  = $this->folder;
		
		$list_thakademik = ThAkademik::orderBy('kode','DESC')->get();
		$list_thangkatan = ThAkademik::where('semester','Ganjil')->orderBy('kode','DESC')->get();
		
		$list_prodi = Prodi::orderBy('kode','ASC')->get();
		$list_kelas = Ref::where('table','Kelas')->get();
		
		$list_form_schadule = FormSchadule::get();
		
		return view($folder.'.create',
			compact('title','redirect','folder','list_thakademik','list_thangkatan',
					'list_prodi','list_kelas','list_form_schadule')
		);
	}

	public function edit($id){
		$data	  = KeuanganTagihan::findOrFail($id);
		$title	  = $this->title;
		$redirect = $this->redirect;
		$folder	  = $this->folder;
		
		$list_thakademik = ThAkademik::orderBy('kode','DESC')->get();
		$list_thangkatan = ThAkademik::where('semester','Ganjil')->orderBy('kode','DESC')->get();
		
		$list_prodi = Prodi::orderBy('kode','ASC')->get();
		$list_kelas = Ref::where('table','Kelas')->get();
		
		$list_form_schadule = FormSchadule::get();
		
		return view($folder.'.edit',
			compact('data','title','redirect','folder','list_thakademik','list_thangkatan',
					'list_prodi','list_kelas','list_form_schadule')
		);
	}

	public function store(Request $request){
		$this->validate($request,$this->rules);
		$txt_kode = $request->th_akademik_id.$request->th_angkatan_id.$request->prodi_id.
					$request->kelas_id.$request->form_schadule_id;
		$kode = str_slug($txt_kode,'');

		$x_sks = !empty($request->x_sks)?'Y':'T';

		$data = new KeuanganTagihan;
		$data->th_akademik_id 	= $request->th_akademik_id;
		$data->th_angkatan_id 	= $request->th_angkatan_id;
		$data->prodi_id 		= $request->prodi_id;
		$data->kelas_id 		= $request->kelas_id;
		$data->form_schadule_id = $request->form_schadule_id;
		$data->kode	   			= $kode;
		$data->nama	   			= $request->nama;
		$data->jumlah  			= $request->jumlah;
		$data->x_sks   			= $x_sks;
		$data->user_id 			= Auth::user()->id;
		$data->save();

		alert()->success('Create Data Success',$this->title);
		return redirect($this->redirect);
	}

	public function update(Request $request, $id){
		$this->validate($request,$this->rules);

		$txt_kode = $request->th_akademik_id.$request->th_angkatan_id.$request->prodi_id.	
					$request->kelas_id.$request->form_schadule_id;
		$kode  = str_slug($txt_kode,'');
		$x_sks = !empty($request->x_sks)?'Y':'T';

		$data = KeuanganTagihan::findOrFail($id);
		$data->th_akademik_id 	= $request->th_akademik_id;
		$data->th_angkatan_id 	= $request->th_angkatan_id;
		$data->prodi_id 	  	= $request->prodi_id;
		$data->kelas_id			= $request->kelas_id;
		$data->form_schadule_id = $request->form_schadule_id;
		$data->kode	   			= $kode;
		$data->nama	   			= $request->nama;
		$data->jumlah  			= $request->jumlah;
		$data->x_sks   			= $x_sks;
		$data->user_id 			= Auth::user()->id;
		$data->save();
		
		alert()->success('Update Data Success',$this->title);
		return redirect($this->redirect);
	}

	public function destroy($id){
		$data = KeuanganTagihan::findOrFail($id);
		$data->delete();
		return response()->json([
			'title' => 'Delete Data Success',
			'text'  => $this->title.' '.$data->nama,
			'type'  => 'success'
		]);
	}
}
