<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ref;
use Auth;
use Yajra\Datatables\Datatables;
Use Alert;

class JamKuliahController extends Controller {
	private $title	  = 'Jam Kuliah';
	private $redirect = 'jamkuliah';
	private $folder	  = 'jamkuliah';
	private $class 	  = 'jamkuliah';
	private $table 	  = 'Jamkuliah';

	private $rules = [
		'kode' => 'required|string|max:10',
		'nama' => 'required|string|max:255',
	];

	public function index(){
		$title    = $this->title;
		$redirect = $this->redirect;
		$folder   = $this->folder;
		return view($folder.'.index',compact('title','redirect'));
	}

	public function getData(){
		$row = Ref::where('table',$this->table)->get();
		return Datatables::of($row)->addColumn('action',function($row){
			return '<div class="btn-group btn-group-xs" id="c-tooltips-demo">
			<a href="'.url('/'.$this->class.'/'.$row->id.'/edit').
			'" class="btn btn-primary btn-xs btn-rounded tooltip-primary" data-toggle="tooltip" data-placement="top" 
			data-original-title="Edit"><i class="fa fa-pencil"></i></a>
			
			<a onclick="deleteForm('.$row->id.')" class="btn btn-danger btn-xs btn-rounded tooltip-danger" 
			data-toggle="tooltip" data-placement="top" data-original-title="Delete"><i class="fa fa-times"></i></a>
			</div>';
		})
		->rawColumns(['action'])->make(true);
	}

	public function create(){
		$title	  = $this->title;
		$redirect = $this->redirect;
		$folder   = $this->folder;
		return view($folder.'.create',compact('title','redirect','folder'));
	}

	public function edit($id){
		$data	  = Ref::findOrFail($id);
		$title	  = $this->title;
		$redirect = $this->redirect;
		$folder	  = $this->folder;
		return view($folder.'.edit',compact('data','title','redirect','folder'));
	}

	public function store(Request $request){
		$this->validate($request,$this->rules);
		$data = new Ref;
		$data->table = $this->table;
		$data->kode  = $request->kode;
		$data->nama  = $request->nama;
		$data->user_id = Auth::user()->id;
		$data->save();

		alert()->success('Create Data Success',$this->title);
		return redirect($this->redirect);
	}

	public function update(Request $request, $id){
		$this->validate($request,$this->rules);
		$data = Ref::findOrFail($id);
		$data->table = $this->table;
		$data->kode  = $request->kode;
		$data->nama  = $request->nama;
		$data->user_id = Auth::user()->id;
		$data->save();

		alert()->success('Update Data Success',$this->title);
		// toast('Success Update','success','top-right');
		return redirect($this->redirect);
	}

	public function destroy($id){
		$data = Ref::findOrFail($id);
		$data->delete();

		return response()->json([
			'title' => 'Delete Data Success',
			'text' => $this->title.' '.$data->nama,
			'type' => 'success'
		]);
	}
}
