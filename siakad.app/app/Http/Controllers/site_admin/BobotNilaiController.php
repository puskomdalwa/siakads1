<?php
namespace App\Http\Controllers\site_admin;

use Alert;
use App\BobotNilai;
use App\Http\Controllers\Controller;
use App\ThAkademik;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class BobotNilaiController extends Controller
{
    private $title = 'Grade Nilai';
    private $redirect = 'bobotnilai';
    private $folder = 'bobotnilai';
    private $class = 'bobotnilai';

    private $rules = [
        'th_akademik_id' => 'required',
        'predikat' => 'required|string|max:100',
        'nilai_min' => 'required|numeric',
        'nilai_max' => 'required|numeric',
        'nilai_bobot' => 'required|numeric',
        'nilai_huruf' => 'required|string|max:2',
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
        $row = BobotNilai::select('*');
        return Datatables::of($row)
            ->addColumn('th_akademik', function ($row) {
                return @$row->th_akademik->kode;
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
        $list_thakademik = ThAkademik::orderBy('kode', 'DESC')->get();

        return view($folder . '.create', compact('title', 'redirect', 'folder', 'list_thakademik'));
    }

    public function edit($id)
    {
        $data = BobotNilai::findOrFail($id);
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;
        $list_thakademik = ThAkademik::orderBy('kode', 'DESC')->get();

        return view($folder . '.edit', compact('data', 'title', 'redirect', 'folder', 'list_thakademik'));
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        $data = new BobotNilai;
        $data->th_akademik_id = $request->th_akademik_id;
        $data->nilai_min = $request->nilai_min;
        $data->nilai_max = $request->nilai_max;
        $data->nilai_huruf = $request->nilai_huruf;
        $data->nilai_bobot = $request->nilai_bobot;
        $data->predikat = $request->predikat;
        $data->user_id = Auth::user()->id;
        $data->save();

        alert()->success('Create Data Success', $this->title);
        return redirect($this->redirect);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules);

        $data = BobotNilai::findOrFail($id);
        $data->th_akademik_id = $request->th_akademik_id;
        $data->nilai_min = $request->nilai_min;
        $data->nilai_max = $request->nilai_max;
        $data->nilai_huruf = $request->nilai_huruf;
        $data->nilai_bobot = $request->nilai_bobot;
        $data->predikat = $request->predikat;
        $data->user_id = Auth::user()->id;
        $data->save();

        alert()->success('Update Data Success', $this->title);
        return redirect($this->redirect);
    }

    public function destroy($id)
    {
        $data = BobotNilai::findOrFail($id);
        $data->delete();
        return response()->json([
            'title' => 'Delete Data Success',
            'text' => $this->title . ' ' . $data->nama,
            'type' => 'success',
        ]);
    }
}
