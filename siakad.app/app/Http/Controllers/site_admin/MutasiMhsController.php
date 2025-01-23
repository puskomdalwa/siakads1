<?php
namespace App\Http\Controllers\site_admin;

use App\MutasiMhs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Yajra\Datatables\Datatables;
Use Alert;
use App\Mahasiswa;
use App\ThAkademik;
use App\Prodi;
use App\Ref;

class MutasiMhsController extends Controller {
	private $title	  = 'Mutasi Mahasiswa';
	private $redirect = 'mutasimhs';
	private $folder	  = 'mutasimhs';
	private $class	  = 'mutasimhs';

	private $rules = [
		'th_akademik_id' => 'required',
	];

	public function index(){	
		$th_akademik	= ThAkademik::Aktif()->first();
		$th_akademik_id = $th_akademik->id;
		$semester 		= $th_akademik->semester;

		$title	  = $this->title;
		$redirect = $this->redirect;
		$folder	  = $this->folder;
	
		$prodi_id = @strtolower(Auth::user()->prodi->id);

		if($prodi_id){
			$list_prodi = Prodi::where('id',$prodi_id)->get();
		}else{
			$list_prodi = Prodi::orderBy('kode','ASC')->get();
		}
		
		$list_thangkatan = ThAkademik::where('semester','Ganjil')->orderBy('kode','asc')->get();

		return view($folder.'.index',
			compact('title','redirect','folder','th_akademik','list_prodi','list_thangkatan','prodi_id')
		);
	}

	public function getData(Request $request){
		$th_akademik_id = $request->th_akademik_id;
		$th_angkatan_id = $request->th_angkatan_id;
		$prodi_id		= $request->prodi_id;

		$row = Mahasiswa::
		where('th_akademik_id',$th_angkatan_id)
		->when($prodi_id, function ($query) use ($prodi_id) {
			return $query->where('prodi_id',$prodi_id);
		})
		->with(['th_akademik','prodi','kelas','jk'])
		->get();

		return Datatables::of($row)
		->addColumn('jk',function($row){
			return  @$row->jk->kode;
		})
		->addColumn('prodi',function($row){
			return  @$row->prodi->nama;
		})
		->addColumn('kelas',function($row){
			return  @$row->kelas->nama;
		})
		->addColumn('kelompok',function($row){
			return  @$row->kelompok->perwalian->kelompok->kode;
		})
		->addColumn('status',function($row){
			return  strtolower($row->status->nama)=='aktif'?'<span class="badge badge-success">'.$row->status->nama.'</span>':
			'<span class="badge badge-danger">'.$row->status->nama.'</span>';
		})
		->addColumn('action',function($row){
		$list_status = Ref::where('table','StatusMhs')->orderBy('kode','asc')->get();
		$select = '<select name="status_id" id="status_id_'.$row->nim.'" class="form-control" onchange="getStatus('.$row->nim.')">';
		$select .='<option value="">-Pilih-</option>';

		foreach($list_status as $status){
			$x = $row->status_id==$status->id?'selected':null;
			$select .='<option value="'.$status->id.'" '.$x.'>'.$status->kode.' - '.$status->nama.'</option>';
		}

		$select .='</select>';
			return $select;
		})
		->setRowClass(function ($row) {
			return strtolower($row->status->nama)=='aktif' ? 'alert-success' : 'alert-danger';
		})
		->rawColumns(['action','status'])
		->make(true);
	}

	public function store(Request $request){	
		$data = MutasiMhs::where('th_akademik_id',$request->th_akademik_id)
		->where('nim',$request->nim)
		->first();

		if(!$data){
			$data = new MutasiMhs;
		}
		
		$data->th_akademik_id = $request->th_akademik_id;
		$data->tanggal = date('Y-m-d');
		$data->nim = $request->nim;
		$data->status_id = $request->status_id;
		$data->user_id = Auth::user()->id;
		$data->save();

		$mhs = Mahasiswa::where('nim',$request->nim)->first();

		if($mhs){
			$mhs->status_id = $request->status_id;
			$mhs->user_id = Auth::user()->id;
			$mhs->save();
		}

		return response()->json([
			'type' => 'success',
			'title' => 'Update Success',
			'text' => $this->title.' NIM '.$request->nim
		]);		
	}
}
