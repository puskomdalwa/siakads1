<?php
namespace App\Http\Controllers\site_admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Mahasiswa;
use App\ThAkademik;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class ThAkademikController extends Controller
{
    private $title = 'Tahun Akademik';
    private $redirect = 'thakademik';
    private $folder = 'thakademik';
    private $class = 'thakademik';
    private $statusAktif = 18;
    private $statusNonAktif = 20;

    private $rules = [
        'kode' => 'required|string|max:5',
        'nama' => 'required|string|max:255',
        'semester' => 'required',
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
        $row = ThAkademik::select('*');
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
        $data = ThAkademik::findOrFail($id);
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;
        return view($folder . '.edit', compact('data', 'title', 'redirect', 'folder'));
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        $aktif = !empty($request->aktif) ? 'Y' : 'T';
        if ($aktif == 'Y') {
            $thak = ThAkademik::where('aktif', 'Y')->update(['aktif' => 'T']);

            // $mhs = Mahasiswa::where('status_id', $this->statusAktif)
            //     ->update(['status_id' => $this->statusNonAktif]);
        }

        $data = new ThAkademik;
        $data->kode = $request->kode;
        $data->nama = $request->nama;
        $data->semester = $request->semester;
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
        if ($aktif == 'Y') {
            $thak = ThAkademik::where('aktif', 'Y')->update(['aktif' => 'T']);

            // $mhs = Mahasiswa::where('status_id', $this->statusAktif)
            //     ->update(['status_id' => $this->statusNonAktif]);
        }

        $data = ThAkademik::findOrFail($id);
        $data->kode = $request->kode;
        $data->nama = $request->nama;
        $data->semester = $request->semester;
        $data->aktif = $aktif;
        $data->user_id = Auth::user()->id;
        $data->save();
        alert()->success('Update Data Success', $this->title);
        return redirect($this->redirect);
    }

    public function destroy($id)
    {
        $data = ThAkademik::findOrFail($id);
        $data->delete();
        return response()->json([
            'title' => 'Delete Data Success',
            'text' => $this->title . ' ' . $data->nama,
            'type' => 'success',
        ]);
    }

    public function setNonAktif(Request $request)
    {
        try {
            Mahasiswa::where('status_id', $this->statusAktif)
                ->update(['status_id' => $this->statusNonAktif]);
            alert()->success('Semua mahasiswa sudah terset non aktif');
            return redirect($this->redirect);
        } catch (\Throwable $th) {
            alert()->error('Error, gagal set mahasiswa non aktif');
            return redirect($this->redirect);
        }
    }
}
