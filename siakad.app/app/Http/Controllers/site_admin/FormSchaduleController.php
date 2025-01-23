<?php
namespace App\Http\Controllers\site_admin;

use Alert;
use App\FormSchadule;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class FormSchaduleController extends Controller
{
    private $title = 'Form Schadule';
    private $redirect = 'formschadule';
    private $folder = 'formschadule';
    private $class = 'formschadule';

    private $rules = [
        'kode' => 'required|string|max:5',
        'nama' => 'required|string|max:50',
        'semester' => 'required|string|max:6',
        'tgl_mulai' => 'required|date_format:"d-m-Y"',
        'tgl_selesai' => 'required|date_format:"d-m-Y"',
    ];

    public function index()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        return view($folder . '.index',
            compact('title', 'redirect')
        );
    }

    public function getData()
    {
        $row = FormSchadule::select('*');

        return Datatables::of($row)
            ->addColumn('txt_aktif', function ($row) {
                return $row->aktif == 'Y' ? '<i class="fa fa-check text-success"></i>' : '';
            })
            ->addColumn('tanggal_mulai', function ($row) {
                return tgl_jam($row->tgl_mulai);
            })
            ->addColumn('tanggal_selesai', function ($row) {
                return tgl_jam($row->tgl_selesai);
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

    public function edit($id)
    {
        $data = FormSchadule::findOrFail($id);
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        return view($folder . '.edit',
            compact('data', 'title', 'redirect', 'folder')
        );
    }

    public function create()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        return view($folder . '.create', compact('title', 'redirect', 'folder'));
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
        $aktif = !empty($request->aktif) ? 'Y' : 'T';

        $tgl_mulai = tgL_jam($request->tgl_mulai . '11:22:33');
        $tgl_selesai = tgl_jam($request->tgl_selesai . '23:59:59');

        dd($request->tgl_mulai);

        $data = new FormSchadule;
        $data->kode = $request->kode;
        $data->nama = $request->nama;
        $data->semester = $request->semester;
        $data->aktif = $aktif;

        //$data->tgl_mulai       = tgl_sql($request->tgl_mulai.'11:22:33');
        //$data->tgl_selesai    = tgl_sql($request->tgl_selesai.'23:59:59');

        $data_tgl_mulai = $tgl_mulai;
        $data_tgl_selesai = $tgl_selesai;

        alert()->success('Create Data Success', $this->title);
        return redirect($this->redirect);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules);
        $aktif = !empty($request->aktif) ? 'Y' : 'T';

        $data = FormSchadule::findOrFail($id);
        $data->kode = $request->kode;
        $data->nama = $request->nama;
        $data->semester = $request->semester;
        $data->aktif = $aktif;
        $data->tgl_mulai = tgl_sql($request->tgl_mulai);
        $data->tgl_selesai = tgl_sql($request->tgl_selesai);

        $data->user_id = Auth::user()->id;
        $data->save();

        alert()->success('Update Data Success', $this->title);
        return redirect($this->redirect);
    }

    public function destroy($id)
    {
        $data = FormSchadule::findOrFail($id);
        $data->delete();

        return response()->json([
            'title' => 'Delete Data Success',
            'text' => $this->title . ' ' . $data->nama,
            'type' => 'success',
        ]);
    }
}
