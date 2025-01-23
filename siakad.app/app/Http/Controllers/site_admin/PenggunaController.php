<?php
namespace App\Http\Controllers\site_admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Level;
use App\Prodi;
use App\Ref;
use App\User;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class PenggunaController extends Controller
{
    private $title = 'Pengguna';
    private $redirect = 'pengguna';
    private $folder = 'pengguna';
    private $class = 'pengguna';

    private $rules = [
        'username' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ];

    private $rules_update = [
        'username' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
    ];

    public function index()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        // dd(tahunAkademikSekarang());
        // dd(\App\ThAkademik::aktif()->first());
        return view($folder . '.index',
            compact('title', 'redirect')
        );
    }

    public function getData(Request $request)
    {
        $search = $request->search['value'];

        $row = User::leftJoin('mst_prodi as prodi', 'prodi.id', '=', 'users.prodi_id')
            ->leftJoin('users_level as ul', 'ul.id', '=', 'users.level_id')
            ->select('*', 'users.id as users_id', 'users.username as users_username',
                'users.name as users_name', 'users.email as users_email',
                'ul.level as level',
                'prodi.nama as prodi_nama');

        return Datatables::of($row)
            ->filter(function ($query) use ($search) {
                $query->orWhere('users.id', 'LIKE', "%$search%")
                    ->orWhere('users.username', 'LIKE', "%$search%")
                    ->orWhere('users.name', 'LIKE', "%$search%")
                    ->orWhere('users.email', 'LIKE', "%$search%")
                    ->orWhere('ul.level', 'LIKE', "%$search%")
                    ->orWhere('prodi.nama', 'LIKE', "%$search%")
                    ->orWhere('prodi.alias', 'LIKE', "%$search%");
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Klik <span class="caret"></span></button>
                <ul class="dropdown-menu pull-right">
                    <li><a href="' . url('/' . $this->class . '/' . $row->users_id . '/edit') . '">Edit</a></li>
                    <li class="divider"></li>
                    <li><a onclick="deleteForm(' . $row->users_id . ')">Delete</a></li>
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
        $list_prodi = Prodi::get();
        $list_level = $this->list_group();

        $jekel_id = Ref::where('table', 'JenisKelamin')->first()->id;
        $list_jekel = Ref::where('table', 'JenisKelamin')->get();

        return view($folder . '.create',
            compact('title', 'redirect', 'folder', 'list_level', 'list_prodi', 'jekel_id', 'list_jekel')
        );
    }

    public function edit($id)
    {
        $data = User::findOrFail($id);
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;
        $list_prodi = Prodi::get();
        $list_level = $this->list_group();
        $list_jekel = $this->list_jekel();

        $list_jekel = Ref::where('table', 'JenisKelamin')->get();

        return view($folder . '.edit',
            compact('data', 'title', 'redirect', 'folder', 'list_level', 'list_prodi', 'list_jekel')
        );
    }

    private function list_group()
    {
        $data = Level::all();
        foreach ($data as $row) {$x[$row->id] = $row->level;}
        return $x;
    }

    private function list_jekel()
    {
        $data = Ref::all();
        foreach ($data as $row) {$x[$row->id] = $row->nama;}
        return $x;
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
        $data = new User;
        $data->username = $request->username;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->level_id = $request->level_id;
        $data->prodi_id = $request->prodi_id;
        $data->keypass = $request->password;
        $data->jk_id = $request->jekel_id;
        $data->password = bcrypt($request->password);

        $data->save();

        alert()->success('Create Data Success', $this->title);
        return redirect($this->redirect);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules_update);
        $data = User::findOrFail($id);

        $data->name = $request->name;
        $data->email = $request->email;
        $data->level_id = $request->level_id;
        $data->prodi_id = $request->prodi_id;
        $data->jk_id = $request->jekel_id;

        if (!empty($request->password)) {
            $data->keypass = $request->password;
            $data->password = bcrypt($request->password);
        }

        $data->save();

        alert()->success('Update Data Success', $this->title);
        return redirect($this->redirect);
    }

    public function destroy($id)
    {
        $data = User::findOrFail($id);
        $data->delete();

        return response()->json([
            'title' => 'Delete Data Success',
            'text' => $this->title . ' ' . $data->name,
            'type' => 'success',
        ]);
    }
}
