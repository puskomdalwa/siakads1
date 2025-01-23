<?php
namespace App\Http\Controllers\site_admin;

use Alert;
use App\Http\Controllers\Controller;
use App\KomponenNilai;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class KomponenNilaiController extends Controller
{
    private $title = 'Komponen Nilai';
    private $redirect = 'komponennilai';
    private $folder = 'komponennilai';
    private $class = 'komponennilai';

    private $rules = [
        'kode' => 'required|string|max:5',
        'nama' => 'required|string|max:255',
        'bobot' => 'required|numeric',
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
        $row = KomponenNilai::get();
        return Datatables::of($row)
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
        return view($folder . '.create', compact('title', 'redirect', 'folder'));
    }

    public function edit($id)
    {
        $data = KomponenNilai::findOrFail($id);
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;
        return view($folder . '.edit', compact('data', 'title', 'redirect', 'folder'));
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        $data = new KomponenNilai;
        $data->kode = $request->kode;
        $data->nama = $request->nama;
        $data->bobot = $request->bobot;
        $data->user_id = Auth::user()->id;
        $data->save();

        alert()->success('Create Data Success', $this->title);
        return redirect($this->redirect);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules);

        $data = KomponenNilai::findOrFail($id);
        $data->kode = $request->kode;
        $data->nama = $request->nama;
        $data->bobot = $request->bobot;
        $data->user_id = Auth::user()->id;
        $data->save();
        alert()->success('Update Data Success', $this->title);
        return redirect($this->redirect);
    }

    public function destroy($id)
    {
        $data = KomponenNilai::findOrFail($id);
        $data->delete();
        return response()->json([
            'title' => 'Delete Data Success',
            'text' => $this->title . ' ' . $data->nama,
            'type' => 'success',
        ]);
    }
}
