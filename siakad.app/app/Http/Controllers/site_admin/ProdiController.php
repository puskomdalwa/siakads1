<?php
namespace App\Http\Controllers\site_admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Prodi;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class ProdiController extends Controller
{
    private $title = 'Program Studi';
    private $redirect = 'prodi';
    private $folder = 'prodi';
    private $class = 'prodi';

    private $rules = [
        'kode' => 'required|string|max:20',
        'alias' => 'required|string|max:5',
        'nama' => 'required|string|max:75',
        'jenjang' => 'required|string|max:5',
        'akreditasi' => 'required|string|max:1',
        'color' => 'required',
        'max_sks_skripsi' => 'required',
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
        $row = Prodi::get();
        return Datatables::of($row)
            ->addColumn('txt_aktif', function ($row) {
                return $row->aktif == 'Y' ? '<i class="fa fa-check text-success"></i>' : '';
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
            ->rawColumns(['action', 'txt_aktif'])->make(true);
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
        $data = Prodi::findOrFail($id);
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;
        return view($folder . '.edit', compact('data', 'title', 'redirect', 'folder'));
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        $aktif = !empty($request->aktif) ? 'Y' : 'T';

        $data = new Prodi;
        $data->kode = $request->kode;
        $data->alias = $request->alias;
        $data->nama = $request->nama;
        $data->jenjang = $request->jenjang;
        $data->nidn_kepala = $request->nidn_kepala;
        $data->nama_kepala = $request->nama_kepala;
        $data->akreditasi = $request->akreditasi;
        $data->color = $request->color;
        $data->max_sks_skripsi = $request->max_sks_skripsi;
        $data->aktif = $aktif;
        $data->user_id = Auth::user()->id;
        $data->save();

        alert()->success('Create Data Success', $this->title);
        return redirect($this->redirect);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules);
        $aktif = !empty($request->aktif) ? 'Y' : 'T';

        $data = Prodi::findOrFail($id);
        $data->kode = $request->kode;
        $data->alias = $request->alias;
        $data->nama = $request->nama;
        $data->jenjang = $request->jenjang;
        $data->nidn_kepala = $request->nidn_kepala;
        $data->nama_kepala = $request->nama_kepala;
        $data->akreditasi = $request->akreditasi;
        $data->color = $request->color;
        $data->max_sks_skripsi = $request->max_sks_skripsi;
        $data->aktif = $aktif;
        $data->user_id = Auth::user()->id;
        $data->save();

        alert()->success('Update Data Success', $this->title);
        return redirect($this->redirect);
    }

    public function destroy($id)
    {
        $data = Prodi::findOrFail($id);
        $data->delete();
        return response()->json([
            'title' => 'Delete Data Success',
            'text' => $this->title . ' ' . $data->nama,
            'type' => 'success',
        ]);
    }
}
