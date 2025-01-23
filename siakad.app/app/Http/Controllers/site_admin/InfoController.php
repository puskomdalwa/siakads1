<?php
namespace App\Http\Controllers\site_admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Info;
use App\Level;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class InfoController extends Controller
{
    private $title = 'Informasi Sistem';
    private $redirect = 'info';
    private $folder = 'info';
    private $class = 'info';

    private $rules = [
        'tanggal' => 'required|date_format:"d-m-Y"',
        'judul' => 'required|string|max:100',
        'isi' => 'required',
    ];

    public function index()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        return view($folder . '.index',
            compact('title', 'redirect', 'folder')
        );
    }

    public function getData(Request $request)
    {
        $row = Info::select('*');

        return Datatables::of($row)
            ->addColumn('tgl', function ($row) {
                return tgl_str($row->tanggal);
            })
            ->addColumn('level', function ($row) {
                return !empty($row->level->level) ? $row->level->level : 'All';
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
        $list_level = Level::all();
        return view($folder . '.create',
            compact('title', 'redirect', 'folder', 'list_level')
        );
    }

    public function edit($id)
    {
        $data = Info::findOrFail($id);
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;
        $list_level = Level::all();
        return view($folder . '.edit',
            compact('data', 'title', 'redirect', 'folder', 'list_level')
        );
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        $data = new Info;
        $data->tanggal = tgl_sql($request->tanggal);
        $data->judul = $request->judul;
        $data->isi = $request->isi;
        $data->user_level_id = $request->user_level_id;
        $data->user_id = Auth::user()->id;
        $data->save();

        alert()->success('Create Data Success', $this->title);
        return redirect($this->redirect);

    }

    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules);

        $data = Info::findOrFail($id);
        $data->tanggal = tgl_sql($request->tanggal);
        $data->judul = $request->judul;
        $data->isi = $request->isi;
        $data->user_level_id = $request->user_level_id;
        $data->user_id = Auth::user()->id;
        $data->save();
        alert()->success('Update Data Success', $this->title);

        return redirect($this->redirect);

    }

    public function destroy($id)
    {
        $data = Info::findOrFail($id);
        $data->delete();
        return response()->json([
            'title' => 'Delete Data Success',
            'text' => $this->title . ' ' . $data->nama,
            'type' => 'success',
        ]);
    }
}
