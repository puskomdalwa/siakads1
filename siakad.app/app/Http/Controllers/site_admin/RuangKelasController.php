<?php
namespace App\Http\Controllers\site_admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Prodi;
use App\Ref;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class RuangKelasController extends Controller
{
    private $title = 'Ruang Kelas';
    private $redirect = 'ruangkelas';
    private $folder = 'ruangkelas';
    private $class = 'ruangkelas';
    private $table = 'RuangKelas';

    private $rules = [
        'kode' => 'required|string|max:10',
        'nama' => 'required|string|max:255',
        'param' => 'required|string|max:3',
    ];

    public function index()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        // $list_prodi = Prodi::get();
        $prodi_id = @strtolower(Auth::user()->prodi->id);
        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }
        $row = Ref::where('table', $this->table)->get();
        return view($folder . '.index',
            compact('title', 'redirect', 'list_prodi', 'prodi_id')
        );
    }

    public function getData()
    {
        $row = Ref::where('table', $this->table);
        return Datatables::of($row)
            ->editColumn('param', function ($row) {
                return $row->param . ' Mahasiswa';
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
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        return view($folder . '.create',
            compact('title', 'redirect', 'folder')
        );
    }

    public function edit($id)
    {
        $data = Ref::findOrFail($id);
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        return view($folder . '.edit',
            compact('data', 'title', 'redirect', 'folder')
        );
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        $data = new Ref;
        $data->table = $this->table;
        $data->kode = $request->kode;
        $data->nama = $request->nama;
        $data->param = $request->param;
        $data->keterangan = 'Kapasitas';
        $data->user_id = Auth::user()->id;
        $data->save();

        alert()->success('Create Data Success', $this->title);
        return redirect($this->redirect);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules);

        $data = Ref::findOrFail($id);
        $data->table = $this->table;
        $data->kode = $request->kode;
        $data->nama = $request->nama;
        $data->param = $request->param;
        $data->keterangan = 'Kapasitas';
        $data->user_id = Auth::user()->id;
        $data->save();

        alert()->success('Update Data Success', $this->title);
        return redirect($this->redirect);
    }

    public function destroy($id)
    {
        $data = Ref::findOrFail($id);
        $data->delete();
        return response()->json([
            'title' => 'Delete Data Success',
            'text' => $this->title . ' ' . $data->nama,
            'type' => 'success',
        ]);
    }
}
