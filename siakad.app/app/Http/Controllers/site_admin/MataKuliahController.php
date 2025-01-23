<?php
namespace App\Http\Controllers\site_admin;

use Alert;
use App\Http\Controllers\Controller;
use App\MataKuliah;
use App\Prodi;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class MataKuliahController extends Controller
{
    private $title = 'Mata Kuliah';
    private $redirect = 'matakuliah';
    private $folder = 'matakuliah';
    private $class = 'matakuliah';

    private $rules = [
        'prodi_id' => 'required',
        'kode' => 'required|string|max:10',
        'nama' => 'required|string|max:255',
        'sks' => 'required|numeric',
        'smt' => 'required|numeric',
    ];

    private $rulesUpdate = [
        'prodi_id' => 'required',
        'kode' => 'required|string|max:10',
        'nama' => 'required|string|max:255',
        'sks' => 'required|numeric',
        'smt' => 'required|numeric',
    ];

    public function index()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $prodi_id = @strtolower(Auth::user()->prodi->id);

        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $list_smt = MataKuliah::select('smt')
            ->orderBy('aktif', 'DESC')
            ->groupBy('smt')->get();

        return view(
            $folder . '.index',
            compact('title', 'redirect', 'folder', 'list_prodi', 'prodi_id', 'list_smt')
        );
    }

    public function getData(Request $request)
    {
        $search = $request->search['value'];

        $prodi_id = $request->prodi_id;
        $smt = $request->smt;

        $row = MataKuliah::join('mst_prodi as prod', 'prod.id', '=', 'mst_matakuliah.prodi_id')
            ->select(
                '*',
                'mst_matakuliah.id as mk_id',
                'mst_matakuliah.kode as mk_kode',
                'mst_matakuliah.nama as mk_nama',
                'mst_matakuliah.sks as mk_sks',
                'mst_matakuliah.smt as mk_smt',
                'mst_matakuliah.aktif as mk_aktif',
                'prod.nama as prodi_nama'
            );

        return Datatables::of($row)
            ->filter(function ($query) use ($prodi_id, $smt, $search) {
                $query->when($prodi_id, function ($query) use ($prodi_id) {
                    return $query->where('mst_matakuliah.prodi_id', $prodi_id);
                });
                $query->when($smt, function ($query) use ($smt) {
                    return $query->where('mst_matakuliah.smt', $smt);
                });
                $query->where(function ($query) use ($search) {
                    $query->orWhere('mst_matakuliah.kode', 'LIKE', "%$search%")
                        ->orWhere('mst_matakuliah.nama', 'LIKE', "%$search%")
                        ->orWhere('mst_matakuliah.sks', 'LIKE', "%$search%")
                        ->orWhere('mst_matakuliah.smt', 'LIKE', "%$search%")
                        ->orWhere('mst_matakuliah.aktif', 'LIKE', "%$search%")
                        ->orWhere('prod.nama', 'LIKE', "%$search%");
                });
            })
            ->editColumn('mk_aktif', function ($row) {
                return $row->mk_aktif == 'Y' ? '<i class="fa fa-check text-success"></i>' : '
			<i class="fa fa-times" style="font-size:18px; color:red"></i>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
			<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Klik <span class="caret"></span></button>
			<ul class="dropdown-menu pull-right">
				<li><a href="' . url('/' . $this->class . '/' . $row->mk_id . '/edit') . '">Edit</a></li>
				<li class="divider"></li>
				<li><a onclick="deleteForm(' . $row->mk_id . ')">Delete</a></li>
			</ul>
		</div>';
            })
            ->rawColumns(['action', 'mk_aktif'])
            ->make(true);
    }

    public function create()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $prodi_id = @strtolower(Auth::user()->prodi->id);

        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        return view($folder . '.create', compact('title', 'redirect', 'folder', 'list_prodi'));
    }

    public function edit($id)
    {
        $data = MataKuliah::findOrFail($id);
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $prodi_id = @strtolower(Auth::user()->prodi->id);

        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        return view($folder . '.edit', compact('data', 'title', 'redirect', 'folder', 'list_prodi'));
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        $aktif = !empty($request->aktif) ? 'Y' : 'T';

        $cek = MataKuliah::where('prodi_id', $request->prodi_id)
            ->where('kode', strtoupper($request->kode))
            ->first();
        if ($cek) {
            alert()->error('Create Data Failed, Mata Kuliah Sudah Ada', $this->title);
            return redirect()->back()->withInput();
        }

        $data = new MataKuliah;
        $data->prodi_id = $request->prodi_id;
        $data->kode = strtoupper($request->kode);
        $data->nama = strtoupper($request->nama);
        $data->sks = $request->sks;
        $data->smt = $request->smt;
        $data->aktif = $aktif;
        $data->user_id = Auth::user()->id;
        $data->save();

        alert()->success('Create Data Success', $this->title);
        return redirect()->back()->withInput();
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rulesUpdate);
        $aktif = !empty($request->aktif) ? 'Y' : 'T';

        $data = MataKuliah::findOrFail($id);
        $data->prodi_id = $request->prodi_id;
        $data->kode = strtoupper($request->kode);
        $data->nama = strtoupper($request->nama);
        $data->sks = $request->sks;
        $data->smt = $request->smt;
        $data->aktif = $aktif;
        $data->user_id = Auth::user()->id;
        $data->save();

        alert()->success('Update Data Success', $this->title);
        return redirect($this->redirect);
    }

    public function destroy($id)
    {
        $data = MataKuliah::findOrFail($id);
        $data->delete();

        return response()->json([
            'title' => 'Delete Data Success',
            'text' => $this->title . ' ' . $data->nama,
            'type' => 'success',
        ]);
    }
}
