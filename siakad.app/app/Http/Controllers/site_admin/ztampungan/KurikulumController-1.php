<?php
namespace App\Http\Controllers\site_admin;

use App\Kurikulum;
use App\KurikulumMataKuliah;
use App\KurikulumAngkatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Yajra\Datatables\Datatables;
Use Alert;
use App\ThAkademik;
use App\Prodi;
use App\MataKuliah;
use App\JadwalKuliah;

class KurikulumController extends Controller {
	private $title		= 'Kurikulum';
	private $redirect	= 'kurikulum';
	private $folder	 	= 'kurikulum';
	private $class		= 'kurikulum';

	private $rules = [
		'th_akademik_id' => 'required',
		'prodi_id'		 => 'required',
		'nama' 			 => 'required|string|max:100',
		// 'cek_list' => 'required',
	];

	private $rules_update = [
		'th_akademik_id' => 'required',
		'prodi_id' 		 => 'required',
		'nama'			 => 'required|string|max:100',
	];

	public function index(){
		$title 	  = $this->title;
		$redirect = $this->redirect;
		$folder   = $this->folder;

		//$list_thakademik = ThAkademik::where('semester','Ganjil')->orderBy('kode','DESC')->get();
		
		$th_akademik_id  = ThAkademik::Aktif()->first()->id;		
		$semester		 = ThAkademik::Aktif()->first()->semester;
		
		if ($semester=='Ganjil'){
			$list_thakademik = ThAkademik::where('semester','Ganjil')->orderBy('kode','DESC')->get();
		}else{
			$list_thakademik = ThAkademik::where('semester','Genap')->orderBy('kode','DESC')->get();
		}
		
		//	$list_thakademik = ThAkademik::orderBy('kode','Desc')->get();
		// $list_prodi = Prodi::get();
		
		$prodi_id = @strtolower(Auth::user()->prodi->id);
		if($prodi_id){
			$list_prodi = Prodi::where('id',$prodi_id)->get();
		}else{
			$list_prodi = Prodi::orderBy('kode','ASC')->get();
		}

		return view($folder.'.index', 
			compact('title','redirect','folder','th_akademik_id','list_thakademik','list_prodi','prodi_id')
		);
	}

	public function getData(Request $request) {
		$th_akademik_id = $request->th_akademik_id;
		$prodi_id = $request->prodi_id;
		if ($request->ajax()) {
			$row = Kurikulum::
			when($th_akademik_id, function ($query) use ($th_akademik_id) {
				return $query->where('th_akademik_id',$th_akademik_id);
			})
			// where('th_akademik_id',$th_akademik_id)
			->when($prodi_id, function ($query) use ($prodi_id) {
				return $query->where('prodi_id',$prodi_id);
			})
			->with('th_akademik','prodi')
			->get();

			return Datatables::of($row)
			->addColumn('th_akademik',function($row){
				return  @$row->th_akademik->kode;
			})
			->addColumn('prodi',function($row){
				return  @$row->prodi->alias;
			})
			->addColumn('jml_mk',function($row){
				$jml_mk = @KurikulumMataKuliah::where('kurikulum_id',$row->id)->count();
				return  $jml_mk;
			})
			->addColumn('angkatan',function($row){
				$dt = KurikulumAngkatan::where('kurikulum_id',$row->id)->get();
				if($dt){
					$h = '';
					foreach($dt as $t){
						$h = $h.'<span class="badge badge-primary">'.substr($t->th_angkatan->kode,0,4).'</span>';
					}
					$hasil = $h;
				}else{
					$hasil = null;
				}
				return $hasil;
			})
			->addColumn('action',function($row){
				return '<div class="btn-group btn-group-xs" id="c-tooltips-demo">
					<a href="'.url('/'.$this->class.'/'.$row->id.'/edit').'" 
					class="btn btn-primary btn-xs btn-rounded tooltip-primary" 
					data-toggle="tooltip" data-placement="top" data-original-title="Edit">
					<i class="fa fa-pencil"></i></a>
					
					<a onclick="deleteForm('.$row->id.')" class="btn btn-danger btn-xs btn-rounded tooltip-danger" 
					data-toggle="tooltip" data-placement="top" data-original-title="Delete">
					<i class="fa fa-times"></i></a>
				</div>';
			})
			->addColumn('details_url', function($row){
				return url($this->folder.'/getDetailsData/'.$row->id);
			})
			->rawColumns(['action','txt_aktif','angkatan'])
			->make(true);
		}
	}

	public function getDataMK(Request $request){
		$kurikulum_id = $request->kurikulum_id;
		$prodi_id = $request->prodi_id;

		$row = MataKuliah::where('prodi_id',$prodi_id)
		->with('prodi')
		->get();

		return Datatables::of($row)
		->addColumn('cek_list',function($row) use ($kurikulum_id){
			$data = KurikulumMataKuliah::where('kurikulum_id',$kurikulum_id)->where('matakuliah_id',$row->id)->first();
			if($data){
				$jadwal = JadwalKuliah::where('kurikulum_matakuliah_id',$data->id)->first();
				if($jadwal){
					return '<i class="fa fa-check-square-o text-success"></i>';
				}else{
					return '<a onclick="deleteDetail('.$data->id.')" class="btn btn-danger btn-xs btn-rounded tooltip-danger" 
					data-toggle="tooltip" data-placement="top" data-original-title="Delete"><i class="fa fa-times"></i></a>';
				}
				// return '<input type="checkbox" name="cek_list[]" id="cek_list" 
				// value="'.$row->id.'" onClick="cekList(\''.$row->id.'\')" checked >';
			}else{
				return '<input type="checkbox" name="cek_list[]" id="cek_list" 
				value="'.$row->id.'" onClick="cekList(\''.$row->id.'\')"  >';
			}
		})
		->addColumn('prodi',function($row){
			return  $row->prodi->nama;
		})
		->rawColumns(['cek_list'])
		->make(true);
	}

	public function getDetailsData($id){
		$row = KurikulumMataKuliah::where('kurikulum_id',$id)
		->with('matakuliah')
		->get();

		return Datatables::of($row)
		->addColumn('kode_mk',function($row){
			return $row->matakuliah->kode;
		})
		->addColumn('nama_mk',function($row){
			return $row->matakuliah->nama;
		})
		->addColumn('sks_mk',function($row){
			return $row->matakuliah->sks;
		})
		->addColumn('smt_mk',function($row){
			return $row->matakuliah->smt;
		})
		->addColumn('aktif',function($row){
			return $row->matakuliah->aktif;
		})
		->addColumn('prodi',function($row){
			return $row->matakuliah->prodi->nama;
		})
		
		// ->addColumn('action',function($row){
		//   return '<div class="btn-group btn-group-xs">
		//   <a href="'.$this->class.'/'.$row->id.'/edit" class="btn btn-primary btn-xs btn-alt"><i class="fa fa-pencil"></i></a>
		//   <a onclick="deleteForm('.$row->id.')" class="btn btn-danger btn-xs btn-alt"><i class="fa fa-times"></i> </a>
		//   </div>';
		// })
		// ->rawColumns(['action'])
		->make(true);
	}

	public function create(){
		$title	  = $this->title;
		$redirect = $this->redirect;
		$folder   = $this->folder;				
				
		$list_thakademik = ThAkademik::orderBy('kode','DESC')->get();
		$list_thangkatan = ThAkademik::where('semester','Ganjil')->orderBy('kode','DESC')->get();
						
		$th_akademik_id  = ThAkademik::Aktif()->first()->id;		
		$semester = ThAkademik::Aktif()->first()->semester;
		
		/*
		if ($semester=='Ganjil'){
			$list_thakademik = ThAkademik::where('semester','Ganjil')->orderBy('kode','DESC')->get();
			$list_thangkatan = ThAkademik::where('semester','Ganjil')->orderBy('kode','DESC')->get();
		}else{
			$list_thakademik = ThAkademik::where('semester','Genap')->orderBy('kode','DESC')->get();
			$list_thangkatan = ThAkademik::where('semester','Genap')->orderBy('kode','DESC')->get();
		}
		*/
		
		//$list_thakademik = ThAkademik::where('semester','Genap')->orderBy('kode','DESC')->get();
		//$list_thangkatan = ThAkademik::where('semester','Genap')->orderBy('kode','DESC')->get();
	
		// $list_prodi = Prodi::get();
		$prodi_id = @strtolower(Auth::user()->prodi->id);
		if($prodi_id){
			$list_prodi = Prodi::where('id',$prodi_id)->get();
		}else{
			$list_prodi = Prodi::orderBy('kode','ASC')->get();
		}
		
		return view($folder.'.create',
			compact('title','redirect','folder','list_thakademik','th_akademik_id','list_prodi','list_thangkatan')
		);
	}

	public function edit($id){
		$data		= Kurikulum::findOrFail($id);
		$title		= $this->title;
		$redirect 	= $this->redirect;
		$folder 	= $this->folder;
		
		$semester = ThAkademik::Aktif()->first()->semester;
			
		if ($semester=='Ganjil'){
			$list_thakademik = ThAkademik::where('semester','Ganjil')->orderBy('kode','DESC')->get();
			$list_thangkatan = ThAkademik::where('semester','Ganjil')->orderBy('kode','DESC')->get();
		}else{
			$list_thakademik = ThAkademik::where('semester','Genap')->orderBy('kode','DESC')->get();
			$list_thangkatan = ThAkademik::where('semester','Genap')->orderBy('kode','DESC')->get();
		}
			
			
		//$list_thakademik = ThAkademik::where('semester','Ganjil')->orderBy('kode','DESC')->get();
		//$list_thangkatan = ThAkademik::where('semester','Ganjil')->orderBy('kode','DESC')->get();
	
		// $list_prodi = Prodi::get();
		$prodi_id = @strtolower(Auth::user()->prodi->id);
		if($prodi_id){
			$list_prodi = Prodi::where('id',$prodi_id)->get();
		}else{
			$list_prodi = Prodi::orderBy('kode','ASC')->get();
		}

		return view($folder.'.edit',
			compact('data','title','redirect','folder','list_thakademik','list_prodi','list_thangkatan')
		);
	}

	public function store(Request $request){
		// dd($request->all());
		$this->validate($request,$this->rules);
		$data = Kurikulum::where('th_akademik_id',$request->th_akademik_id)
		->where('prodi_id',$request->prodi_id)->first();
		
		if($data){
			alert()->warning('Maaf, Kurikulum sudah pernah dibuat.',$this->title);
			return back()->withInput();
		}

		// if(!$data)
		// {
		$data = new Kurikulum;
		$data->th_akademik_id = $request->th_akademik_id;
		$data->prodi_id = $request->prodi_id;
		// }

		$data->nama = $request->nama;
		$data->user_id = Auth::user()->id;
		$data->save();

		// KurikulumMataKuliah::where('kurikulum_id',$data->id)->delete();
		// $jml_cek = count($request->cek_list);
		// dd($jml_cek);
		
		if(isset($request->cek_list)){
			foreach($request->cek_list as $key => $value){
				$dt_detail = New KurikulumMataKuliah;
				$dt_detail->kurikulum_id = $data->id;
				$dt_detail->matakuliah_id = $value;
				$dt_detail->user_id = Auth::user()->id;
				$dt_detail->save();
			}
		}

		// $jml_th = count($request->th_angkatan_id);
		// dd($jml_th);
		if(isset($request->th_angkatan_id)){
			KurikulumAngkatan::where('kurikulum_id',$data->id)->delete();
			foreach($request->th_angkatan_id as $key => $value){
				$dt_detail = New KurikulumAngkatan;
				$dt_detail->kurikulum_id = $data->id;
				$dt_detail->th_akademik_id = $value;
				$dt_detail->user_id = Auth::user()->id;
				$dt_detail->save();
			}
		}
		alert()->success('Create Data Success',$this->title);
		return redirect($this->redirect.'/'.$data->id.'/edit');
		// return back()->withInput();
	}

	public function update(Request $request, $id){
		// dd($request->all());
		$this->validate($request,$this->rules_update);
		$data = Kurikulum::
		where('id',$id)->first();
		
		// where('th_akademik_id',$request->th_akademik_id)
		// ->where('prodi_id',$request->prodi_id)
		// ->first();
		// if(!$data)
		// {
		//   $data = new Kurikulum;
		//   $data->th_akademik_id = $request->th_akademik_id;
		//   $data->prodi_id = $request->prodi_id;
		// }
		
		$data->nama = $request->nama;
		$data->user_id = Auth::user()->id;
		$data->save();

		// KurikulumMataKuliah::where('kurikulum_id',$data->id)->delete();
		// $jml_cek = count($request->cek_list);
		// dd($jml_cek);
		
		if(isset($request->cek_list)){
			foreach($request->cek_list as $key => $value){
				$dt_detail = New KurikulumMataKuliah;
				$dt_detail->kurikulum_id = $data->id;
				$dt_detail->matakuliah_id = $value;
				$dt_detail->user_id = Auth::user()->id;
				$dt_detail->save();
			}
		}


		// $jml_th = count($request->th_angkatan_id);
		// dd($jml_th);
		if(isset($request->th_angkatan_id)){
			KurikulumAngkatan::where('kurikulum_id',$data->id)->delete();
			foreach($request->th_angkatan_id as $key => $value){
				$dt_detail = New KurikulumAngkatan;
				$dt_detail->kurikulum_id = $data->id;
				$dt_detail->th_akademik_id = $value;
				$dt_detail->user_id = Auth::user()->id;
				$dt_detail->save();
			}
		}
		
		alert()->success('Update Data Success',$this->title);
		return redirect($this->redirect.'/'.$data->id.'/edit');
		// return back()->withInput();
	}

	public function destroy($id){
		$data = Kurikulum::findOrFail($id);
		$data->delete();
		KurikulumMataKuliah::where('kurikulum_id',$id)->delete();
		return response()->json([
			'title' => 'Delete Data Success',
			'text' => $this->title.' '.$data->nama,
			'type' => 'success'
		]);
	}

	public function deleteDetail($id){
		// dd($id);
		KurikulumMataKuliah::where('id',$id)->delete();
		
		return response()->json([
			'title' => 'Delete Data Success',
			'text' => $this->title.' Delete Detail',
			'type' => 'success'
		]);
	}
}