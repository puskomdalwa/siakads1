<?php
namespace App\Http\Controllers\site_admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Pejabat;
use App\Ref;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class PejabatController extends Controller
{
    private $title = 'Pejabat';
    private $redirect = 'pejabat';
    private $folder = 'pejabat';
    private $class = 'pejabat';

    private $rules = [
        'kode' => 'required|string|max:10',
        'nama' => 'required|string|max:100',
        'jabatan_id' => 'required',
    ];

    public function index()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;
        return view($folder . '.index', compact('title', 'redirect'));
    }

    public function getData()
    {
        $row = Pejabat::select('*');

        return Datatables::of($row)
            ->addColumn('jabatan', function ($row) {
                return $row->jabatan->nama;
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
      <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Klik <span class="caret"></span></button>
      <ul class="dropdown-menu pull-right">
          <li><a href="' . url('/' . $this->class . '/' . $row->id . '/edit') . '">Edit</a></li>
          <li class="divider"></li>
          <li><a onclick="deleteForm(' . $row->id . ')">Delete</a></li>
      </ul>
  </div>';
            })
            ->rawColumns(['action', 'txt_aktif'])
            ->make(true);
    }

    public function create()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;
        $list_jabatan = Ref::where('table', 'Jabatan')->orderBy('kode', 'DESC')->get();
        return view($folder . '.create', compact('title', 'redirect', 'folder', 'list_jabatan'));
    }

    public function edit($id)
    {
        $data = Pejabat::findOrFail($id);
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;
        $list_jabatan = Ref::where('table', 'Jabatan')->orderBy('kode', 'DESC')->get();
        return view($folder . '.edit', compact('data', 'title', 'redirect', 'folder', 'list_jabatan'));
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        $data = new Pejabat;
        $data->kode = $request->kode;
        $data->nama = $request->nama;
        $data->jabatan_id = $request->jabatan_id;
        $data->user_id = Auth::user()->id;
        $data->save();

        alert()->success('Create Data Success', $this->title);
        return redirect($this->redirect);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules);

        $data = Pejabat::findOrFail($id);
        $data->kode = $request->kode;
        $data->nama = $request->nama;
        $data->jabatan_id = $request->jabatan_id;
        $data->user_id = Auth::user()->id;
        $data->save();
        alert()->success('Update Data Success', $this->title);
        return redirect($this->redirect);
    }

    public function destroy($id)
    {
        $data = Pejabat::findOrFail($id);
        $data->delete();
        return response()->json([
            'title' => 'Delete Data Success',
            'text' => $this->title . ' ' . $data->nama,
            'type' => 'success',
        ]);
    }
}
