<?php
namespace App\Http\Controllers\site_admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Mahasiswa;
use App\Prodi;
use App\Ref;
use App\ThAkademik;
use App\Yudisium;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class YudisiumController extends Controller
{
    private $title = 'Yudisium Mahasiswa';
    private $redirect = 'yudisium';
    private $folder = 'yudisium';
    private $class = 'yudisium';

    private $rules = [
        'nim' => 'required|string|max:20',
        'jml_sks' => 'required',
        'ipk' => 'required',
        'judul_skripsi' => 'required',
        'ukuran_toga' => 'required',
    ];

    public function index()
    {
        $th_akademik = ThAkademik::Aktif()->first();
        $th_akademik_id = $th_akademik->id;
        $semester = $th_akademik->semester;

        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $prodi_id = @strtolower(Auth::user()->prodi->id);

        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            // $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $list_thangkatan = ThAkademik::where('semester', 'Ganjil')
            ->skip(3)->take(4)
            ->orderBy('kode', 'desc')->get();

        return view(
            $folder . '.index',
            compact('title', 'redirect', 'folder', 'th_akademik', 'list_prodi', 'list_thangkatan', 'prodi_id')
        );
    }

    public function getData(Request $request)
    {
        $th_akademik = ThAkademik::Aktif()->first();
        $th_akademik_id = $th_akademik->id;
        $th_angkatan_id = $request->th_angkatan_id;

        $prodi_id = $request->prodi_id;
        $kelas_id = $request->kelas_id;

        $row = Yudisium::join('mst_mhs', 'mst_mhs.nim', '=', 'trans_yudisium.nim')
            ->join('ref as ref_jk', 'ref_jk.id', '=', 'mst_mhs.id')
            ->join('ref as ref_kelompok', 'ref_kelompok.id', '=', 'trans_yudisium.kelompok_id')
            ->join('mst_prodi as mp', 'mp.id', '=', 'trans_yudisium.prodi_id')
            ->join('ref as ref_kelas', 'ref_kelas.id', '=', 'trans_yudisium.kelas_id')
            ->join('ref as ref_status', 'ref_status.id', '=', 'mst_mhs.status_id')
            ->select(
                'trans_yudisium.*',
                'mst_mhs.nama as mhs_nama',
                'ref_jk.kode as jk',
                'mp.nama as prodi',
                'ref_kelas.nama as kelas',
                'ref_kelompok.kode as kelompok',
                'ref_status.nama as status'
            )
            ->where('trans_yudisium.th_akademik_id', $th_akademik_id)
            ->when($th_angkatan_id, function ($query) use ($th_angkatan_id) {
                return $query->where('trans_yudisium.th_angkatan_id', $th_angkatan_id);
            })
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('trans_yudisium.prodi_id', $prodi_id);
            })
            ->when($kelas_id, function ($query) use ($kelas_id) {
                return $query->where('trans_yudisium.kelas_id', $kelas_id);
            });

        return Datatables::of($row)
            ->editColumn('status', function ($row) {
                return strtolower($row->status) == 'aktif' ? '<span class="badge badge-success">' . $row->status . '</span>' :
                    '<span class="badge badge-danger">' . $row->status . '</span>';
            })
            ->addColumn('tanggal_sk_yudisium', function ($row) {
                return '<a href="#" class="tanggal" name="tgl_sk_yudisium" id="tanggal_sk_yudisium_' . $row->id . '" data-type="date" data-viewformat="dd-mm-yyyy" data-pk="' . $row->id . '" data-placement="right" data-title="Masukan Tanggal SK Yudisium">'
                    . @tgl_str($row->tgl_sk_yudisium) .
                    '</a>';
            })
            ->addColumn('link_sk_yudisium', function ($row) {
                return '<a href="#" class="sk_yudisium" name="sk_yudisium" id="sk_yudisium_' . $row->id . '" data-type="text" data-pk="' . $row->id . '" data-title="Masukan SK Yudisium" >' . @$row->sk_yudisium . '</a>';
            })
            ->addColumn('link_nomor_seri_ijazah', function ($row) {
                return '<a href="#" class="nomor_seri_ijazah" name="nomor_seri_ijazah" id="nomor_seri_ijazah_' . $row->id . '" data-type="text" data-pk="' . $row->id . '" data-title="Masukan Nomor Seri Ijazah" >' . @$row->nomor_seri_ijazah . '</a>';
            })
            ->addColumn('approve', function ($row) {
                $list_approve = ['Y' => 'Ya', 'T' => 'Tidak'];
                $select = '<select name="approve" id="approve_' . $row->id . '" class="form-control" onchange="getStatus(' . $row->id . ')">';
                $select .= '<option value="">-Pilih-</option>';

                foreach ($list_approve as $key => $approve) {
                    $x = $row->approve == $key ? 'selected' : null;
                    $select .= '<option value="' . $key . '" ' . $x . '>' . $approve . '</option>';
                }
                $select .= '</select>';
                return $select;
            })
            ->setRowClass(function ($row) {
                return strtolower($row->status) == 'aktif' ? 'alert-success' : 'alert-danger';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Klik <span class="caret"></span></button>
                <ul class="dropdown-menu pull-right">
                    <li><a href="' . url('/' . $this->class . '/' . $row->id . '/edit') . '">Edit</a></li>
                    <li class="divider"></li>
                    <li><a onclick="deleteForm(' . $row->id . ')" >Delete</a></li>
                </ul>
            </div>';
            })
            ->rawColumns(['action', 'status', 'approve', 'tanggal_sk_yudisium', 'link_sk_yudisium', 'link_nomor_seri_ijazah'])
            ->make(true);
    }

    public function create()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;
        $list_toga = Ref::where('table', 'UkuranToga')->get();

        $list_thangkatan = ThAkademik::where('semester', 'Ganjil')
            ->skip(3)->take(4)
            ->orderBy('kode', 'desc')->first();
        //->orderBy('kode','desc')->get();

        //$ta_kd = $list_thangkatan->kode;
        $ta_id = $list_thangkatan->id;

        $prodi_id = @strtolower(Auth::user()->prodi->id);
        if ($prodi_id) {
            $list_mhs = Mahasiswa::where('status_id', 18)
                ->where('prodi_id', $prodi_id)
                ->where('th_akademik_id', '<=', $ta_id)
                ->orderBy('nim', 'DESC')->get();
        } else {
            $list_mhs = Mahasiswa::where('status_id', 18)
                ->where('th_akademik_id', '<=', $ta_id)
                ->orderBy('nim', 'DESC')->get();
        }

        return view(
            $folder . '.create',
            compact('title', 'redirect', 'folder', 'list_toga', 'list_mhs')
        );
    }

    public function edit($id)
    {
        $data = Yudisium::findOrFail($id);
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $list_toga = Ref::where('table', 'UkuranToga')->get();

        $prodi_id = @strtolower(Auth::user()->prodi->id);
        if ($prodi_id) {
            $list_mhs = Mahasiswa::where('status_id', 18)->where('prodi_id', $prodi_id)
                ->orderBy('nim', 'DESC')->get();
        } else {
            $list_mhs = Mahasiswa::where('status_id', 18)->orderBy('nim', 'DESC')->get();
        }

        return view($folder . '.edit', compact('title', 'redirect', 'folder', 'list_toga', 'list_mhs', 'data'));
    }

    public function getMhs(Request $request)
    {
        $th_akademik = ThAkademik::Aktif()->first();
        $th_akademik_id = $th_akademik->id;

        $nim = $request->nim;
        $mhs = Mahasiswa::where('nim', $nim)
            ->with('th_akademik', 'jk', 'prodi', 'kelas', 'status', 'kelompok')
            ->first();

        if ($mhs) {
            $return = [
                'jk' => $mhs->jk->nama,
                'th_angkatan' => $mhs->th_akademik->kode,
                'prodi' => $mhs->prodi->jenjang . ' - ' . $mhs->prodi->nama,
                'kelas' => $mhs->kelas->nama,
                'kelompok' => $mhs->kelompok->perwalian->kelompok->kode,
                'status' => $mhs->status->nama,
                'keuangan' => bayarWisuda($th_akademik_id, $mhs->nim),
            ];
        } else {
            $return = [
                'jk' => null,
                'th_angkatan' => null,
                'prodi' => null,
                'kelas' => null,
                'kelompok' => null,
                'status' => null,
                'keuangan' => null,
            ];
        }
        return $return;
    }

    public function approve(Request $request)
    {
        $data = Yudisium::where('id', $request->id)->first();
        if ($data) {
            $data->approve = $request->approve;
            $data->user_id = Auth::user()->id;
            $data->save();

            $mhs = Mahasiswa::where('nim', $data->nim)->first();
            if ($mhs) {
                $status_id = $request->approve == 'Y' ? 27 : 18;
                $mhs->status_id = $status_id;
                $mhs->user_id = Auth::user()->id;
                $mhs->save();
            }
            return response()->json([
                'type' => 'success',
                'title' => 'Approve Success',
                'text' => $this->title . ' NIM ' . $data->nim,
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'title' => 'Approve Error',
                'text' => $this->title . ' ID ' . $request->id,
            ]);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
        $th_akademik = ThAkademik::Aktif()->first();
        $th_akademik_id = $th_akademik->id;

        $nim = $request->nim;
        $mhs = Mahasiswa::where('nim', $nim)->first();

        $data = Yudisium::where('nim', $nim)->first();
        if (!$data) {
            $data = new Yudisium;
        }

        $data->tanggal = date('Y-m-d');
        $data->th_akademik_id = $th_akademik_id;
        $data->th_angkatan_id = $mhs->th_akademik_id;
        $data->prodi_id = $mhs->prodi_id;
        $data->kelas_id = $mhs->kelas_id;
        $data->kelompok_id = $mhs->kelompok->perwalian->kelompok->id;
        $data->nim = $mhs->nim;
        $data->nama_lengkap = $mhs->nama;
        $data->motto = $request->motto;
        $data->judul_skripsi = $request->judul_skripsi;
        $data->jml_sks = $request->jml_sks;
        $data->ipk = $request->ipk;
        $data->ukuran_toga = $request->ukuran_toga;
        $data->tgl_sk_yudisium = !empty($request->tgl_sk_yudisium) ? tgl_sql($request->tgl_sk_yudisium) : null;
        $data->sk_yudisium = $request->sk_yudisium;
        $data->nomor_seri_ijazah = $request->nomor_seri_ijazah;
        $data->approve = null;
        $data->user_id = Auth::user()->id;
        $data->save();

        alert()->success('Save Success', $this->title);
        return back()->withInput();
    }

    public function update(Request $request)
    {
        $this->validate($request, $this->rules);
        $th_akademik = ThAkademik::Aktif()->first();
        $th_akademik_id = $th_akademik->id;

        $nim = $request->nim;
        $mhs = Mahasiswa::where('nim', $nim)->first();

        $data = Yudisium::where('nim', $nim)->first();
        if (!$data) {
            $data = new Yudisium;
        }

        $data->tanggal = date('Y-m-d');
        $data->th_akademik_id = $th_akademik_id;
        $data->th_angkatan_id = $mhs->th_akademik_id;
        $data->prodi_id = $mhs->prodi_id;
        $data->kelas_id = $mhs->kelas_id;
        $data->kelompok_id = $mhs->kelompok->perwalian->kelompok->id;
        $data->nim = $mhs->nim;
        $data->nama_lengkap = $mhs->nama;
        $data->motto = $request->motto;
        $data->judul_skripsi = $request->judul_skripsi;
        $data->jml_sks = $request->jml_sks;
        $data->ipk = $request->ipk;
        $data->ukuran_toga = $request->ukuran_toga;
        $data->tgl_sk_yudisium = !empty($request->tgl_sk_yudisium) ? tgl_sql($request->tgl_sk_yudisium) : null;
        $data->sk_yudisium = $request->sk_yudisium;
        $data->nomor_seri_ijazah = $request->nomor_seri_ijazah;
        $data->user_id = Auth::user()->id;
        $data->save();

        alert()->success('Update Success', $this->title);
        return back()->withInput();
    }

    public function saveTglSKYudisium(Request $request)
    {
        $data = Yudisium::where('id', $request->pk)->first();
        $data->tgl_sk_yudisium = $request->value;
        $data->user_id = Auth::user()->id;
        $data->save();
    }

    public function saveSKYudisium(Request $request)
    {
        $data = Yudisium::where('id', $request->pk)->first();
        $data->sk_yudisium = $request->value;
        $data->user_id = Auth::user()->id;
        $data->save();
    }

    public function saveNomorSeriIjazah(Request $request)
    {
        $data = Yudisium::where('id', $request->pk)->first();
        $data->nomor_seri_ijazah = $request->value;
        $data->user_id = Auth::user()->id;
        $data->save();
    }

    public function destroy($id)
    {
        $data = Yudisium::findOrFail($id);
        $mhs = Mahasiswa::where('nim', $data->nim)->first();
        $mhs->status_id = 18;
        $mhs->save();

        $data->delete();
        return response()->json([
            'title' => 'Delete Data Success',
            'text' => $this->title . ' ' . $data->nim,
            'type' => 'success',
        ]);
    }
}