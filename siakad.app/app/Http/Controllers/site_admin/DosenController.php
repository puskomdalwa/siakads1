<?php
namespace App\Http\Controllers\site_admin;

use Alert;
use App\Absensi;
use App\Dosen;
use App\Http\Controllers\Controller;
use App\JadwalKuliah;
use App\Kota;
use App\KRS;
use App\KRSDetail;
use App\Perwalian;
use App\PerwalianDetail;
use App\Prodi;
use App\Ref;
use App\SkripsiJudul;
use App\ThAkademik;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;

class DosenController extends Controller
{

    private $title = 'Dosen';
    private $redirect = 'dosen';
    private $folder = 'dosen';
    private $class = 'dosen';

    private $rules = [
        'prodi_id' => 'required',
        'kode' => 'required|string|max:10|unique:mst_dosen',
        'nama' => 'required|string|max:255',
        'jk_id' => 'required',
        'kota_id' => 'required',
        'tanggal_lahir' => 'required|date_format:"d-m-Y"',
        'dosen_status_id' => 'required',
        'email' => 'required|string|email|max:255|unique:users',
    ];

    private $rules_update = [
        'prodi_id' => 'required',
        'kode' => 'required|string|max:10',
        'nama' => 'required|string|max:255',
        'jk_id' => 'required',
        'kota_id' => 'required',
        'tanggal_lahir' => 'required|date_format:"d-m-Y"',
        'dosen_status_id' => 'required',
        'email' => 'required|string|email|max:255',
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
        // $row = Dosen::join('ref as ref_jk', 'ref_jk.id', '=', 'mst_dosen.jk_id')
        //     ->join('ref as ref_status', 'ref_status.id', '=', 'mst_dosen.dosen_status_id')
        //     ->select('mst_dosen.*', 'ref_jk.nama as dosen_jk', 'ref_status.nama as dosen_status')->get();
        // dd($row[0]);
        return view(
            $folder . '.index',
            compact('title', 'redirect', 'folder', 'list_prodi', 'prodi_id')
        );
    }

    public function getData(Request $request)
    {
        $search = $request->search['value'];
        $prodi_id = $request->prodi_id;

        $row = Dosen::join('ref as ref_jk', 'ref_jk.id', '=', 'mst_dosen.jk_id')
            ->join('ref as ref_status', 'ref_status.id', '=', 'mst_dosen.dosen_status_id')
            ->select('mst_dosen.*', 'ref_jk.nama as dosen_jk', 'ref_status.nama as dosen_status');

        return Datatables::of($row)
            ->filter(function ($query) use ($search, $prodi_id) {
                $query->where('mst_dosen.prodi_id', $prodi_id);
                $query->where(function ($query) use ($search) {
                    $query->orWhere('mst_dosen.kode', 'LIKE', "%$search%")
                        ->orWhere('mst_dosen.nidn', 'LIKE', "%$search%")
                        ->orWhere('mst_dosen.nama', 'LIKE', "%$search%")
                        ->orWhere('ref_jk.nama', 'LIKE', "%$search%")
                        ->orWhere('mst_dosen.email', 'LIKE', "%$search%")
                        ->orWhere('mst_dosen.hp', 'LIKE', "%$search%")
                        ->orWhere('ref_status.nama', 'LIKE', "%$search%");
                });
            })
            ->addColumn('nama_dosen', function ($row) {
                return '<a href="' . url('/' . $this->class . '/' . $row->id) . '">' . $row->nama . '</a>';
            })
            ->editColumn('dosen_status', function ($row) {
                return strtolower($row->dosen_status) == 'aktif' ? '<span class="badge badge-success">' .
                    $row->dosen_status . '</span>' : $row->dosen_status;
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
            ->rawColumns(['action', 'dosen_status', 'nama_dosen'])->make(true);
    }

    public function getDataPerwalian(Request $request)
    {
        $search = $request->search['value'];
        $dosen_id = $request->dosen_id;

        $row = PerwalianDetail::join('trans_perwalian', 'trans_perwalian.id', '=', 'trans_perwalian_detail.perwalian_id')
            ->join('mst_mhs', 'mst_mhs.nim', '=', 'trans_perwalian_detail.nim')
            ->where('trans_perwalian.dosen_id', $dosen_id)
            ->select('trans_perwalian_detail.*', 'mst_mhs.nama as nama_mhs');

        return Datatables::of($row)
            ->filter(function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->orWhere('trans_perwalian_detail.nim', 'LIKE', "%$search%");
                    $q->orWhere('mst_mhs.nama', 'LIKE', "%$search%");
                });
            })
            ->addColumn('jk', function ($row) {
                return @$row->mahasiswa->jk->kode;
            })
            ->addColumn('prodi', function ($row) {
                return @$row->mahasiswa->prodi->alias;
            })
            ->addColumn('kelas', function ($row) {
                return @$row->mahasiswa->kelas->nama;
            })
            ->addColumn('kelompok', function ($row) {
                return @$row->mahasiswa->kelompok->perwalian->kelompok->kode;
            })
            ->addColumn('status', function ($row) {
                return @strtolower($row->mahasiswa->status->nama) == 'aktif' ? '<span class="badge badge-success">' .
                    @$row->mahasiswa->status->nama . '</span>' : 'N.AKTIF';
            })
            ->addColumn('krs', function ($row) {
                return @$this->krs($row->nim) . ' SKS';
            })
            ->addColumn('krs_acc', function ($row) {
                return @$this->krs_acc($row->nim);
            })
            ->addColumn('krs_acc_btn', function ($row) {
                $status = @$this->krs_acc($row->nim);
                $id_krs = @$this->krs_id($row->nim);

                if ($status) {
                    $btn = '<select class="form-control" style="width:80px" onChange="btnAcc(' . $id_krs . ');" id="btnAcc' . $id_krs . '">';

                    if ($status == 'Baru') {
                        $btn = $btn . '<option value="Baru" selected>Baru</option>';
                    } else {
                        $btn = $btn . '<option value="Baru">Baru</option>';
                    }

                    if ($status == 'Setujui') {
                        $btn = $btn . '<option value="Setujui" selected>Setujui</option>';
                    } else {
                        $btn = $btn . '<option value="Setujui">Setujui</option>';
                    }

                    if ($status == 'Tolak') {
                        $btn = $btn . '<option value="Tolak" selected>Tolak</option>';
                    } else {
                        $btn = $btn . '<option value="Tolak">Tolak</option>';
                    }

                    $btn = $btn . '</select>';
                    return $btn;
                }
            })
            ->addColumn('details_url', function ($row) {
                $nim = $row->nim;
                $th_akademik_id = ThAkademik::Aktif()->first()->id;
                return url($this->folder . "/getDetailsDataPerwalian/$nim/$th_akademik_id");
            })
            ->rawColumns(['status', 'krs_acc_btn'])
            ->make(true);
    }

    public function getDetailsDataPerwalian($nim, $th_akademik_id)
    {
        $row = KRSDetail::where('th_akademik_id', $th_akademik_id)
            ->where('nim', $nim)->with(['jadwal_kuliah', 'dosen', 'jamkul'])->get();

        return Datatables::of($row)
            ->addColumn('matkul_kode', function ($row) {
                return @$row->jadwal_kuliah->kurikulum_matakuliah->matakuliah->kode;
            })
            ->addColumn('matkul_nama', function ($row) {
                return @$row->jadwal_kuliah->kurikulum_matakuliah->matakuliah->nama;
            })
            ->addColumn('matkul_sks', function ($row) {
                return @$row->jadwal_kuliah->kurikulum_matakuliah->matakuliah->sks;
            })
            ->addColumn('matkul_smt', function ($row) {
                return @$row->jadwal_kuliah->kurikulum_matakuliah->matakuliah->smt;
            })
            ->addColumn('dosen', function ($row) {
                return @$row->dosen->nama;
            })
            ->addColumn('ruang', function ($row) {
                return @$row->jadwal_kuliah->ruang_kelas->kode;
            })
            ->addColumn('hari', function ($row) {
                return @$row->jadwal_kuliah->hari->nama;
            })
            ->addColumn('waktu', function ($row) {
                if (@$row->jadwal_kuliah->jam_kuliah_id > 0) {
                    return @$row->jadwal_kuliah->jamkul->nama;
                } else {
                    return @$row->jadwal_kuliah->jam_mulai . ' ' . @$row->jadwal_kuliah->jam_selesai;
                }
            })
            ->addColumn('kuota', function ($row) {
                $quota = @$row->jadwal_kuliah->ruang_kelas->param;
                $isi = isi_kelas($row->jadwal_kuliah_id);
                $sisa = $quota - $isi;
                return $isi . '/' . $quota;
            })
            ->rawColumns(['ruang', 'kuota'])
            ->make(true);
    }

    public function getDataMengajar(Request $request)
    {
        $dosen_id = $request->dosen_id;
        $th_akademik_id = $request->th_akademik_id_dosen;

        $row = JadwalKuliah::where('dosen_id', $dosen_id)
            ->where('th_akademik_id', $th_akademik_id)
            ->with(['kurikulum_matakuliah', 'ruang_kelas', 'hari', 'jamkul'])->get();

        return Datatables::of($row)
            ->addColumn('no_id', function ($row) {
                return @$row->id;
            })
            ->addColumn('kode_mk', function ($row) {
                return @$row->kurikulum_matakuliah->matakuliah->kode;
            })
            ->addColumn('nama_mk', function ($row) {
                return @$row->kurikulum_matakuliah->matakuliah->nama;
            })
            ->addColumn('sks_mk', function ($row) {
                return @$row->kurikulum_matakuliah->matakuliah->sks;
            })
            ->addColumn('smt', function ($row) {
                return @$row->kurikulum_matakuliah->matakuliah->smt;
            })
            ->addColumn('ruang', function ($row) {
                return @$row->ruang_kelas->nama;
            })
            ->addColumn('hari', function ($row) {
                return @$row->hari->nama;
            })
            ->addColumn('waktu', function ($row) {
                $kode = "";
                switch (@$row->jamkul->kode) {
                    case 1:
                        $kode = "Pertama";
                        break;

                    case 2:
                        $kode = "Kedua";
                        break;

                    case 3:
                        $kode = "Ketiga";
                        break;
                    case 4:
                        $kode = "Keempat";
                        break;

                    default:
                        # code...
                        break;
                }
                return "Jam $kode";
            })
            ->addColumn('details_url', function ($row) {
                return url($this->folder . '/getDetailsDataMengajar/' . $row->id);
            })
            ->make(true);
    }

    public function getDetailsDataMengajar($id)
    {
        $row = Absensi::where('trans_jadwal_kuliah_id', $id);

        return Datatables::of($row)
            ->editColumn('materi', function ($row) {
                $cleanText = strip_tags($row->materi);
                $limitedText = Str::limit($cleanText, 20);
                return $limitedText;
            })
            ->make(true);
    }

    public function getDataNilai(Request $request)
    {
        $dosen_id = $request->dosen_id;
        $th_akademik_id = $request->th_akademik_id_nilai;

        $row = JadwalKuliah::where('dosen_id', $dosen_id)
            ->where('th_akademik_id', $th_akademik_id)
            ->with(['kurikulum_matakuliah', 'ruang_kelas', 'hari'])->get();

        return Datatables::of($row)
            ->addColumn('kode_mk', function ($row) {
                return @$row->kurikulum_matakuliah->matakuliah->kode;
            })
            ->addColumn('nama_mk', function ($row) {
                return @$row->kurikulum_matakuliah->matakuliah->nama;
            })
            ->addColumn('sks_mk', function ($row) {
                return @$row->kurikulum_matakuliah->matakuliah->sks;
            })
            ->addColumn('smt', function ($row) {
                return @$row->kurikulum_matakuliah->matakuliah->smt;
            })
            ->addColumn('ruang', function ($row) {
                return @$row->ruang_kelas->nama;
            })
            ->addColumn('hari', function ($row) {
                return @$row->hari->nama;
            })
            ->addColumn('waktu', function ($row) {
                return @$row->jamkul->nama;
            })
            ->addColumn('details_url', function ($row) {
                return url($this->folder . '/getDetailsData/' . $row->id);
            })
            ->make(true);
    }

    public function getDetailsData($id)
    {
        $row = KRSDetail::where('jadwal_kuliah_id', $id)
            ->with(['th_akademik', 'mahasiswa'])->get();

        return Datatables::of($row)
            ->addColumn('sex', function ($row) {
                return @$row->mahasiswa->jk->kode;
            })
            ->make(true);
    }

    public function getDataSkripsi(Request $request)
    {
        $dosen_id = $request->dosen_id;
        $th_akademik_id = $request->th_akademik_id_dosen;

        $row = SkripsiJudul::select('skripsi_judul.*')
            ->join('skripsi_pengajuan', 'skripsi_pengajuan.id', '=', 'skripsi_judul.skripsi_pengajuan_id')
            ->join('skripsi_pembimbing', 'skripsi_pembimbing.skripsi_pengajuan_id', '=', 'skripsi_pengajuan.id')
            ->join('mst_mhs', 'mst_mhs.nim', '=', 'skripsi_pengajuan.nim')
            ->where('skripsi_judul.acc', 'Y')
            ->where('skripsi_pembimbing.mst_dosen_id', $dosen_id)
            ->where('skripsi_pengajuan.th_akademik_id', $th_akademik_id)->get();

        return Datatables::of($row)
            ->addColumn('tgl_pengajuan', function ($row) {
                return @tgl_str($row->pengajuan->tanggal);
            })
            ->addColumn('mhs_nim', function ($row) {
                return @$row->pengajuan->mahasiswa->nim;
            })
            ->addColumn('mhs_nama', function ($row) {
                return @$row->pengajuan->mahasiswa->nama;
            })
            ->addColumn('mhs_sex', function ($row) {
                return @$row->pengajuan->mahasiswa->jk->kode;
            })
            ->addColumn('mhs_prodi', function ($row) {
                return @$row->pengajuan->mahasiswa->prodi->nama;
            })
            ->addColumn('txt_judul', function ($row) {
                return @strip_tags($row->judul);
            })
            ->addColumn('tgl_acc', function ($row) {
                return @tgl_Nojam($row->updated_at);
            })
            ->make(true);
    }

    private function krs($nim)
    {
        $th_akademik_id = ThAkademik::Aktif()->first()->id;

        $krs_detail = KRSDetail::select(DB::raw('sum(sks_mk) as total_sks'))
            ->where('th_akademik_id', $th_akademik_id)
            ->where('nim', $nim)
            ->first();

        return $krs_detail->total_sks;
    }

    private function krs_acc($nim)
    {
        $th_akademik_id = ThAkademik::Aktif()->first()->id;

        $krs = KRS::select('acc_pa')
            ->where('th_akademik_id', $th_akademik_id)
            ->where('nim', $nim)
            ->first();

        return $krs->acc_pa;
    }

    private function krs_id($nim)
    {
        $th_akademik_id = ThAkademik::Aktif()->first()->id;

        $krs = KRS::select('id')
            ->where('th_akademik_id', $th_akademik_id)
            ->where('nim', $nim)
            ->first();

        return $krs->id;
    }

    public function create()
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

        $list_status = Ref::where('table', 'StatusDosen')->get();
        $list_jk = Ref::where('table', 'JenisKelamin')->get();
        $list_kota = Kota::orderBy('province_id')->get();

        return view(
            $folder . '.create',
            compact('title', 'redirect', 'folder', 'list_prodi', 'list_status', 'list_jk', 'list_kota')
        );
    }

    public function edit($id)
    {
        $data = Dosen::findOrFail($id);
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $prodi_id = @strtolower(Auth::user()->prodi->id);

        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $list_status = Ref::where('table', 'StatusDosen')->get();
        $list_jk = Ref::where('table', 'JenisKelamin')->get();
        $list_kota = Kota::orderBy('province_id')->get();

        return view(
            $folder . '.edit',
            compact('data', 'title', 'redirect', 'folder', 'list_prodi', 'list_status', 'list_jk', 'list_kota')
        );
    }

    public function show($id)
    {
        $data = Dosen::findOrFail($id);
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $list_thakademik = ThAkademik::orderBy('kode', 'desc')->get();

        return view(
            $folder . '.show',
            compact('data', 'title', 'redirect', 'folder', 'list_thakademik')
        );
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
        $email = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? strtolower($request->email) : null;

        $data = new Dosen;
        $data->prodi_id = $request->prodi_id;
        $data->kode = strtoupper($request->kode);
        $data->nidn = $request->nidn;
        $data->nama = $request->nama;
        $data->jk_id = $request->jk_id;
        $data->tempat_lahir = $request->tempat_lahir;
        $data->tanggal_lahir = tgl_sql($request->tanggal_lahir);
        $data->alamat = $request->alamat;
        $data->kota_id = $request->kota_id;
        $data->email = $email;
        $data->hp = $request->hp;
        $data->dosen_status_id = $request->dosen_status_id;
        $data->user_id = Auth::user()->id;
        $data->save();

        $user = User::where('username', $data->kode)->first();
        if (!$user) {
            $email = filter_var($data->email, FILTER_VALIDATE_EMAIL) ? strtolower($data->email) : null;

            $user = new User;
            $user->username = $data->kode;
            $user->name = $data->nama;
            $user->email = $email;
            $user->level_id = 4;
            $user->prodi_id = $request->prodi_id;
            $user->aktif = 'Y';
            $user->password = bcrypt($data->kode);
            $user->keypass = $data->kode;
            $user->jk_id = $data->jk_id;
            $user->save();
        }

        alert()->success('Create Data Success', $this->title);
        return redirect($this->redirect);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules_update);
        $email = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? strtolower($request->email) : null;

        $data = Dosen::findOrFail($id);
        $data->prodi_id = $request->prodi_id;
        $data->kode = strtoupper($request->kode);
        $data->nidn = $request->nidn;
        $data->nama = $request->nama;
        $data->jk_id = $request->jk_id;
        $data->tempat_lahir = $request->tempat_lahir;
        $data->tanggal_lahir = tgl_sql($request->tanggal_lahir);
        $data->alamat = $request->alamat;
        $data->kota_id = $request->kota_id;
        $data->email = $email;
        $data->hp = $request->hp;
        $data->dosen_status_id = $request->dosen_status_id;
        $data->user_id = Auth::user()->id;
        $data->save();

        $user = User::where('username', $data->kode)->first();

        if (!$user) {
            $email = filter_var($data->email, FILTER_VALIDATE_EMAIL) ? strtolower($data->email) : null;

            $user = new User;
            $user->password = bcrypt('123456');
            $user->keypass = '123456';
        } else {
            $user->username = $data->kode;
            $user->name = $data->nama;
            $user->email = $email;
            $user->level_id = 4;
            $user->prodi_id = $request->prodi_id;
            $user->aktif = 'Y';
            $user->keypass = $data->kode;
            $user->jk_id = $data->jk_id;
            $user->save();
        }

        alert()->success('Update Data Success', $this->title);
        return redirect($this->redirect);
    }

    public function getResetPassword($id)
    {
        // dd($id);
        $password = '123456';
        $dosen = Dosen::where('id', $id)->first();
        $user = User::where('username', $dosen->kode)->first();

        if (!$user) {
            $user = new User;
        }
        $email = filter_var($dosen->email, FILTER_VALIDATE_EMAIL) ? strtolower($dosen->email) : $dosen->kode . '@email.com';

        $user->username = $dosen->kode;
        $user->name = $dosen->nama;
        $user->email = $email;
        $user->level_id = 4;
        $user->prodi_id = $dosen->prodi_id;
        $user->aktif = 'Y';
        $user->password = bcrypt($password);
        $user->keypass = $password;
        $user->save();

        alert()->success('Reset Password Success', $this->title);
        return back();
    }

    public function destroy($id)
    {
        $data = Dosen::findOrFail($id);
        $data->delete();

        return response()->json([
            'title' => 'Delete Data Success',
            'text' => $this->title . ' ' . $data->nama,
            'type' => 'success',
        ]);
    }

    public function UpdateAccKRS(Request $request)
    {
        $data = KRS::findOrFail($request->id);

        $data->acc_pa = $request->acc;
        $data->user_id = Auth::user()->id;
        $data->save();

        return response()->json([
            'title' => 'ACC Success',
            'text' => $this->title . ' ' . $data->acc_pa,
            'type' => 'success',
        ]);
    }

    public function accKrsSemua(Request $request)
    {
        try {
            \DB::beginTransaction();
            $mhsNonSetujui = PerwalianDetail::select('trans_perwalian_detail.*', 'trans_krs.acc_pa', 'trans_krs.id as krs_id')
                ->join('trans_perwalian', 'trans_perwalian.id', '=', 'trans_perwalian_detail.perwalian_id')
                ->join('trans_krs', 'trans_krs.nim', '=', 'trans_perwalian_detail.nim')
                ->where('trans_perwalian.dosen_id', $request->dosen_id)
                ->where('trans_krs.acc_pa', '=', 'Baru')
                ->get();

            $jumlah = count($mhsNonSetujui);
            foreach ($mhsNonSetujui as $data) {
                KRS::where('id', $data->krs_id)->update(['acc_pa' => 'Setujui']);
            }

            \DB::commit();
            return [
                'title' => 'ACC Semua Success',
                'text' => "$jumlah data krs sudah disetujui",
                'type' => 'success',
                'data' => $mhsNonSetujui,
            ];
        } catch (\Throwable $th) {
            \DB::rollback();
            return [
                'title' => 'Gagal ACC Semua Success',
                'text' => "$jumlah data krs gagal disetujui",
                'type' => 'danger',
                'msg' => $th->getMessage(),
                'req' => $request->all(),
            ];
        }
    }
}
