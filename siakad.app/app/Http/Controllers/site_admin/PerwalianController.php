<?php
namespace App\Http\Controllers\site_admin;

use Alert;
use App\Dosen;
use App\Http\Controllers\Controller;
use App\Mahasiswa;
use App\Perwalian;
use App\PerwalianDetail;
use App\Prodi;
use App\Ref;
use App\ThAkademik;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class PerwalianController extends Controller
{

    private $title = 'Dosen Wali / Perwalian';
    private $redirect = 'perwalian';
    private $folder = 'perwalian';
    private $class = 'perwalian';

    private $rules = [
        'th_akademik_id' => 'required',
        'prodi_id' => 'required',
        'kelas_id' => 'required',
        'dosen_id' => 'required',
        'kelompok_id' => 'required',
        // 'cek_list'      => 'required',
    ];

    public function index()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $th_akademik = ThAkademik::Aktif()->first();

        $th_akademik_id = ThAkademik::Aktif()->first()->id;
        $semester = ThAkademik::Aktif()->first()->semester;
        $list_thakademik = ThAkademik::orderBy('kode', 'Desc')->get();

        $prodi_id = @strtolower(Auth::user()->prodi->id);

        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            // $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
            $prodi_id = Prodi::orderBy('kode', 'Asc')->first()->id;
        }

        $kelas_id = Ref::where('table', 'Kelas')->first()->id;
        $list_kelas = Ref::where('table', 'Kelas')->get();
        // $row = Perwalian::join('mst_dosen as dos', 'dos.id', '=', 'trans_perwalian.dosen_id')
        //     ->join('mst_th_akademik as ta', 'ta.id', '=', 'trans_perwalian.th_akademik_id')
        //     ->join('mst_prodi as prod', 'prod.id', '=', 'trans_perwalian.prodi_id')
        //     ->join('ref as ref_kelas', 'ref_kelas.id', '=', 'trans_perwalian.kelas_id')
        //     ->join('ref as ref_kelompok', 'ref_kelompok.id', '=', 'trans_perwalian.kelompok_id')
        //     ->select('trans_perwalian.*', 'dos.nama as perwalian_dosen', 'ta.kode as perwalian_ta', 'prod.alias as perwalian_prodi',
        //         'ref_kelas.nama as perwalian_kelas', 'ref_kelompok.kode as perwalian_kelompok')
        //     ->addSelect(DB::raw('(SELECT trans_perwalian.id ) as mhs_semester'))
        //     ->get();
        // dd($row[0]);
        return view(
            $folder . '.index',
            compact(
                'title',
                'redirect',
                'folder',
                'th_akademik',
                'th_akademik_id',
                'list_thakademik',
                'list_prodi',
                'prodi_id',
                'list_kelas',
                'kelas_id'
            )
        );
    }

    public function getData(Request $request)
    {
        $search = $request->search['value'];

        $th_akademik_id = $request->th_akademik_id;

        $kelas_id = $request->kelas_id;
        $prodi_id = $request->prodi_id;

        $row = Perwalian::join('mst_dosen as dos', 'dos.id', '=', 'trans_perwalian.dosen_id')
            ->join('mst_th_akademik as ta', 'ta.id', '=', 'trans_perwalian.th_akademik_id')
            ->join('mst_prodi as prod', 'prod.id', '=', 'trans_perwalian.prodi_id')
            ->join('ref as ref_kelas', 'ref_kelas.id', '=', 'trans_perwalian.kelas_id')
            ->join('ref as ref_kelompok', 'ref_kelompok.id', '=', 'trans_perwalian.kelompok_id')
            ->select(
                'trans_perwalian.*',
                'dos.nama as perwalian_dosen',
                'ta.kode as perwalian_ta',
                'prod.alias as perwalian_prodi',
                'ref_kelas.nama as perwalian_kelas',
                'ref_kelompok.kode as perwalian_kelompok'
            );
        return Datatables::of($row)
            ->filter(function ($query) use ($search, $th_akademik_id, $prodi_id, $kelas_id) {
                $query->when($th_akademik_id, function ($query) use ($th_akademik_id) {
                    return $query->where('trans_perwalian.th_akademik_id', $th_akademik_id);
                })
                    ->when($prodi_id, function ($query) use ($prodi_id) {
                        return $query->where('trans_perwalian.prodi_id', $prodi_id);
                    })
                    ->when($kelas_id, function ($query) use ($kelas_id) {
                        return $query->where('trans_perwalian.kelas_id', $kelas_id);
                    });
                $query->where(function ($query) use ($search) {
                    $query->orWhere('ta.kode', 'LIKE', "%$search%")
                        ->orWhere('dos.nama', 'LIKE', "%$search%")
                        ->orWhere('prod.alias', 'LIKE', "%$search%")
                        ->orWhere('ref_kelas.nama', 'LIKE', "%$search%")
                        ->orWhere('ref_kelompok.kode', 'LIKE', "%$search%");
                });
            })
            ->addColumn('jml_mhs', function ($row) {
                return PerwalianDetail::where('perwalian_id', $row->id)->count();
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

    public function getDataMhs(Request $request)
    {
        $th_akademik_id = $request->th_akademik_id;
        $prodi_id = $request->prodi_id;
        $kelas_id = $request->kelas_id;
        $perwalian_id = $request->perwalian_id;
        $kelompok_id = $request->kelompok_id;

        $row = Mahasiswa::where('th_akademik_id', $th_akademik_id)
            ->where('prodi_id', $prodi_id)
            ->when($kelas_id, function ($query) use ($kelas_id) {
                return $query->where('kelas_id', $kelas_id);
            })
            ->with(['th_akademik', 'prodi', 'kelas', 'jk', 'status', 'kelompok'])->get();

        $x = Datatables::of($row)->addColumn('cek_list', function ($row) use ($perwalian_id, $kelompok_id) {
            $perwalian_detail = PerwalianDetail::where('perwalian_id', $perwalian_id)
                ->where('nim', $row->nim)->first();

            if ($perwalian_detail) {
                return '<a onclick="deleteDetail(' . $perwalian_detail->id . ')"
				class="btn btn-rounded btn-danger btn-xs tooltip-danger" data-toggle="tooltip"
				data-placement="top" data-original-title="Hapus"><i class="fa fa-trash-o"></i></a>';
            } else {
                $kelompok_id_perwalian = @$row->kelompok->perwalian->kelompok->id;
                if (empty($kelompok_id_perwalian)) {
                    return '<div class="m-chck">
					<label class="px-single">
					<input type="checkbox" class="px" name="cek_list[]" id="cek_list" value="' . $row->nim . '"
					onClick="cekList(\'' . $row->nim . '\',\'' . $perwalian_id . '\')"  >
					<span class="lbl"></span></label>
					</div>';
                }

                if ($kelompok_id == $kelompok_id_perwalian) {
                    if ($perwalian_detail) {
                        return '<a onclick="deleteDetail(' . $perwalian_detail->id . ')"
						class="btn btn-rounded btn-danger btn-xs tooltip-danger" data-toggle="tooltip"
						data-placement="top" data-original-title="Hapus"><i class="fa fa-trash-o"></i></a>';
                    }
                }
            }
        })
            ->addColumn('jk', function ($row) {
                return @$row->jk->kode;
            })
            ->addColumn('prodi', function ($row) {
                return @$row->prodi->nama;
            })
            ->addColumn('kelas', function ($row) {
                return @$row->kelas->nama;
            })
            ->addColumn('status', function ($row) {
                if (strtolower(@$row->status->nama) == 'aktif') {
                    $hasil = '<span class="badge badge-success">' . $row->status->nama . '</span>';
                } else {
                    $hasil = '<span class="badge badge-danger">' . $row->status->nama . '</span>';
                }
                return $hasil;
            })
            ->addColumn('kelompok', function ($row) {
                return @$row->kelompok->perwalian->kelompok->kode;
            })
            ->rawColumns(['cek_list', 'status'])
            ->make(true);

        return $x;
    }

    public function create()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;
        $list_thakademik = ThAkademik::where('semester', 'Ganjil')->orderBy('kode', 'DESC')->get();

        $prodi_id = @strtolower(Auth::user()->prodi->id);
        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            // $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $list_dosen = Dosen::get();
        $list_kelas = Ref::where('table', 'Kelas')->get();
        $list_kelompok = Ref::where('table', 'Kelompok')->get();

        return view(
            $folder . '.create',
            compact(
                'title',
                'redirect',
                'folder',
                'list_thakademik',
                'list_kelompok',
                'list_prodi',
                'list_kelas',
                'list_dosen'
            )
        );
    }

    public function edit($id)
    {
        $data = Perwalian::findOrFail($id);
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;
        $list_thakademik = ThAkademik::where('semester', 'Ganjil')->orderBy('kode', 'DESC')->get();

        $prodi_id = @strtolower(Auth::user()->prodi->id);
        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            // $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $list_dosen = Dosen::get();
        $list_kelas = Ref::where('table', 'Kelas')->get();
        $list_kelompok = Ref::where('table', 'Kelompok')->get();

        return view(
            $folder . '.edit',
            compact(
                'data',
                'title',
                'redirect',
                'folder',
                'list_thakademik',
                'list_kelompok',
                'list_prodi',
                'list_kelas',
                'list_dosen'
            )
        );
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
        $data = Perwalian::where('th_akademik_id', $request->th_akademik_id)
            ->where('prodi_id', $request->prodi_id)
            ->where('kelas_id', $request->kelas_id)
            ->where('kelompok_id', $request->kelompok_id)
            ->first();

        if ($data) {
            alert()->warning('Maaf, Perwalian sudah pernah dibuat.', $this->title);
            return back()->withInput();
        }

        $data = new Perwalian;
        $data->th_akademik_id = $request->th_akademik_id;
        $data->prodi_id = $request->prodi_id;
        $data->kelas_id = $request->kelas_id;
        $data->kelompok_id = $request->kelompok_id;
        // }

        $data->dosen_id = $request->dosen_id;
        $data->user_id = Auth::user()->id;
        $data->save();

        if (isset($request->cek_list)) {
            foreach ($request->cek_list as $key => $value) {
                $dt_detail = new PerwalianDetail;
                $dt_detail->perwalian_id = $data->id;
                $dt_detail->nim = $value;
                $dt_detail->user_id = Auth::user()->id;
                $dt_detail->save();
                // }
            }
        }

        alert()->success('Create Data Success', $this->title);
        return redirect($this->redirect . '/' . $data->id . '/edit');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules);
        $data = Perwalian::where('id', $id)
            ->first();

        $data->dosen_id = $request->dosen_id;
        $data->user_id = Auth::user()->id;
        $data->save();

        if (isset($request->cek_list)) {
            foreach ($request->cek_list as $key => $value) {
                $dt_detail = new PerwalianDetail;
                $dt_detail->perwalian_id = $data->id;
                $dt_detail->nim = $value;

                // }
                $dt_detail->user_id = Auth::user()->id;
                $dt_detail->save();
            }
        }

        alert()->success('Update Data Success', $this->title);
        return back()->withInput();
    }

    public function destroy($id)
    {
        $data = Perwalian::findOrFail($id);
        $data->delete();
        PerwalianDetail::where('perwalian_id', $id)->delete();
        return response()->json([
            'title' => 'Delete Data Success',
            'text' => $this->title . ' ' . $data->nama,
            'type' => 'success',
        ]);
    }

    public function deleteDetail($id)
    {
        $data = PerwalianDetail::findOrFail($id);
        $data->delete();
        return response()->json([
            'title' => 'Delete Data Success',
            'text' => $this->title . ' ' . $data->nim,
            'type' => 'success',
        ]);
    }
}
