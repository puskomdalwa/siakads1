<?php
namespace App\Http\Controllers\site_admin;

use Alert;
use App\Http\Controllers\Controller;
use App\JadwalKuliah;
use App\KeuanganTagihan;
use App\Kota;
use App\KRS;
use App\KRSDetail;
use App\Kurikulum;
use App\KurikulumAngkatan;
use App\KurikulumMataKuliah;
use App\Mahasiswa;
use App\MataKuliah;
use App\Pejabat;
use App\Prodi;
use App\PT;
use App\Ref;
use App\ThAkademik;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use PDF;
use Yajra\Datatables\Datatables;

class MahasiswaController extends Controller
{

    private $title = 'Mahasiswa';
    private $redirect = 'mahasiswa';
    private $folder = 'mahasiswa';
    private $class = 'mahasiswa';

    private $rules = [
        'th_akademik_id' => 'required',
        'kelas_id' => 'required',
        'prodi_id' => 'required',
        'nim' => 'required|string|max:20|unique:mst_mhs',
        'nama' => 'required|string|max:100',
        'jk_id' => 'required',
        'kota_id' => 'required',
        'agama_id' => 'required',
        'tanggal_lahir' => 'required|date_format:"d-m-Y"',
        'status_id' => 'required',
        'email' => 'required|string|email|max:75|unique:users',
        'tanggal_masuk' => 'required|date_format:"d-m-Y"',
    ];

    private $rules_update = [
        'th_akademik_id' => 'required',
        'kelas_id' => 'required',
        'prodi_id' => 'required',
        'nim' => 'required|string|max:20',
        'nama' => 'required|string|max:100',
        'jk_id' => 'required',
        'kota_id' => 'nullable',
        'agama_id' => 'nullable',
        'tanggal_lahir' => 'nullable|date_format:"d-m-Y"',
        'status_id' => 'nullable',
        'email' => 'nullable|string|email|max:75',
        'tanggal_masuk' => 'nullable|date_format:"d-m-Y"',
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
            // $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $list_status = Ref::where('table', 'StatusMhs')->get();
        $list_kelas = Ref::where('table', 'Kelas')->get();

        $th_akademik = ThAkademik::Aktif()->first();

        $list_thakademik = ThAkademik::where('semester', 'Ganjil')
            ->orderBy('kode', 'Desc')
            ->get();

        $row = Mahasiswa::join('ref as ref_jk', 'ref_jk.id', '=', 'mst_mhs.jk_id')
            ->join('mst_prodi as prod', 'prod.id', '=', 'mst_mhs.prodi_id')
            ->join('ref as ref_kelas', 'ref_kelas.id', '=', 'mst_mhs.kelas_id')
            ->leftJoin('trans_perwalian_detail as tpd', 'tpd.nim', '=', 'mst_mhs.nim')
            ->leftJoin('trans_perwalian as tp', 'tp.id', '=', 'tpd.perwalian_id')
            ->leftJoin('ref as ref_kelompok', 'ref_kelompok.id', '=', 'tp.kelompok_id')
            ->join('ref as ref_status', 'ref_status.id', '=', 'mst_mhs.status_id')
            ->where('mst_mhs.nim', '202285010002')
            ->select('mst_mhs.*', 'ref_jk.kode as mhs_jk', 'prod.alias as mhs_prodi', 'ref_kelas.nama as mhs_kelas', 'ref_kelompok.kode as mhs_kelompok', 'ref_status.nama as mhs_status')
            ->get();
        // dd($row);
        return view(
            $folder . '.index',
            compact(
                'title',
                'redirect',
                'folder',
                'list_prodi',
                'list_status',
                'th_akademik',
                'list_thakademik',
                'list_kelas',
                'prodi_id'
            )
        );
    }

    public function getData(Request $request)
    {
        $search = $request->search['value'];

        $prodi_id = $request->prodi_id;
        $kelas_id = $request->kelas_id;
        $status_id = $request->status_id;
        $txt_cari = $request->txt_cari;

        $th_akademik_id = $request->th_akademik_id;

        $row = Mahasiswa::join('ref as ref_jk', 'ref_jk.id', '=', 'mst_mhs.jk_id')
            ->join('mst_prodi as prod', 'prod.id', '=', 'mst_mhs.prodi_id')
            ->join('ref as ref_kelas', 'ref_kelas.id', '=', 'mst_mhs.kelas_id')
            ->leftJoin('trans_perwalian_detail as tpd', 'tpd.nim', '=', 'mst_mhs.nim')
            ->leftJoin('trans_perwalian as tp', 'tp.id', '=', 'tpd.perwalian_id')
            ->leftJoin('ref as ref_kelompok', 'ref_kelompok.id', '=', 'tp.kelompok_id')
            ->join('ref as ref_status', 'ref_status.id', '=', 'mst_mhs.status_id')
            ->select('mst_mhs.*', 'ref_jk.kode as mhs_jk', 'prod.alias as mhs_prodi', 'ref_kelas.nama as mhs_kelas', 'ref_kelompok.kode as mhs_kelompok', 'ref_status.nama as mhs_status');

        return Datatables::of($row)
            ->filter(function ($query) use ($search, $th_akademik_id, $prodi_id, $kelas_id, $status_id, $txt_cari) {
                $query->where('mst_mhs.th_akademik_id', $th_akademik_id)
                    ->when($prodi_id, function ($query) use ($prodi_id) {
                        return $query->where('mst_mhs.prodi_id', $prodi_id);
                    })
                    ->when($kelas_id, function ($query) use ($kelas_id) {
                        return $query->where('mst_mhs.kelas_id', $kelas_id);
                    })
                    ->when($status_id, function ($query) use ($status_id) {
                        return $query->where('mst_mhs.status_id', $status_id);
                    })
                    ->when($txt_cari, function ($query) use ($txt_cari) {
                        return $query->where('mst_mhs.nim', 'like', '%' . $txt_cari . '%')->orWhere('mst_mhs.nama', 'like', '%' . $txt_cari . '%');
                    });
                $query->where(function ($query) use ($search) {
                    $query->orWhere('mst_mhs.nim', 'LIKE', "%$search%")
                        ->orWhere('mst_mhs.nama', 'LIKE', "%$search%")
                        ->orWhere('ref_jk.nama', 'LIKE', "%$search%")
                        ->orWhere('prod.alias', 'LIKE', "%$search%")
                        ->orWhere('ref_kelas.nama', 'LIKE', "%$search%")
                        ->orWhere('ref_kelompok.kode', 'LIKE', "%$search%")
                        ->orWhere('ref_status.nama', 'LIKE', "%$search%");
                });
            })
            ->editColumn('nama', function ($row) {
                return '<a href="' . url('/' . $this->class . '/' . $row->id) . '">' . $row->nama . '</a>';
            })
            ->editColumn('mhs_status', function ($row) {
                return strtoupper($row->mhs_status) == 'AKTIF' ?
                '<span class="badge badge-success">' . $row->mhs_status . '</span>' :
                '<span class="badge badge-danger">' . $row->mhs_status . '</span>';
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
            ->rawColumns(['action', 'mhs_status', 'nama'])->make(true);
    }

    public function getDataKRS(Request $request)
    {
        $nim = $request->nim;
        $th_akademik_id = $request->th_akademik_id;

        $row = KRSDetail::where('th_akademik_id', $th_akademik_id)
            ->where('nim', $nim)->with(['jadwal_kuliah', 'dosen', 'jamkul'])->get();

        return Datatables::of($row)
        // ->editColumn('nama_mk', function ($row) {
        //     return @$row->jadwal_kuliah->kurikulum_matakuliah->matakuliah->nama;
        // })
            ->addColumn('dosen', function ($row) {
                return @$row->dosen->nama . 'id:' . @$row->dosen->id . ' kmk_id:' . @$row->jadwal_kuliah->kurikulum_matakuliah_id . ' k_id:' . @$row->jadwal_kuliah->kurikulum_matakuliah->kurikulum_id . ' ruangKelasId: ' . @$row->jadwal_kuliah->ruang_kelas_id;
            })
            ->addColumn('ruang', function ($row) {
                return @$row->jadwal_kuliah->ruang_kelas->kode . 'jadwal_kuliah_id:' . @$row->jadwal_kuliah_id .
                ';kelompok_id: ' . @$row->jadwal_kuliah->kelompok_id .
                ';kelompok_kode: ' . @$row->jadwal_kuliah->kelompok->kode .
                ';prodi_id: ' . @$row->jadwal_kuliah->prodi_id .
                ';idKrsDetail: ' . @$row->id .
                ';kelas_id: ' . @$row->jadwal_kuliah->kelas_id;
            })
            ->addColumn('hari', function ($row) {
                return @$row->jadwal_kuliah->hari->nama . 'th_akademik_id:' . @$row->jadwal_kuliah->th_akademik_id . ' hari_id:' . @$row->jadwal_kuliah->hari->id;
            })
            ->addColumn('waktu', function ($row) {
                if (@$row->jadwal_kuliah->jam_kuliah_id > 0) {
                    return @$row->jadwal_kuliah->jamkul->nama . '-jamId :' . @$row->jadwal_kuliah->jam_kuliah_id;
                } else {
                    return @$row->jadwal_kuliah->jam_mulai . ' ' . @$row->jadwal_kuliah->jam_selesai . 'jamId :' . @$row->jadwal_kuliah->jam_kuliah_id;
                }
            })
            ->make(true);
    }

    public function fixKRS($idKrsDetail)
    {
        try {
            DB::beginTransaction();
            $krsDetail = KRSDetail::findOrFail($idKrsDetail);
            $jadwal = JadwalKuliah::find($krsDetail->jadwal_kuliah_id);
            $mahasiswa = Mahasiswa::where('nim', $krsDetail->nim)->first();

            $kodeMkMaster = @$jadwal->kurikulum_matakuliah->matakuliah->kode;
            $kodeMkKhs = $krsDetail->kode_mk;
            if ($kodeMkMaster == $kodeMkKhs) {
                return abort(500, 'sudah fix, tidak perlu diperbaiki lagi');
            }
            if (!$jadwal) {
                $hariId = 39; //senin
                $semuaKrs = KRSDetail::where('krs_id', $krsDetail->krs_id)->get();
                $ruangKelasId = null;
                foreach ($semuaKrs as $krs) {
                    if ($krs->jadwal_kuliah) {
                        $ruangKelasId = $krs->jadwal_kuliah->ruang_kelas_id;
                        break;
                    }
                }
                $jadwal = new JadwalKuliah;
                $jadwal->id = $krsDetail->jadwal_kuliah_id;
                $jadwal->th_akademik_id = $krsDetail->th_akademik_id;
                $jadwal->prodi_id = $mahasiswa->prodi_id;
                $jadwal->kelas_id = $mahasiswa->kelas_id;
                $jadwal->kelompok_id = $mahasiswa->kelompok->perwalian->kelompok_id;
                $jadwal->smt = $krsDetail->smt_mk;
                $jadwal->dosen_id = $krsDetail->dosen_id;
                $jadwal->hari_id = $hariId;
                $jadwal->ruang_kelas_id = $ruangKelasId;
                $jadwal->jam_kuliah_id = 350;
                $jadwal->user_id = Auth::user()->id;
                $jadwal->save();
            }

            $kurikulum = Kurikulum::leftJoin('trans_kurikulum_matakuliah', 'trans_kurikulum_matakuliah.kurikulum_id', '=', 'trans_kurikulum.id')
                ->leftJoin('mst_matakuliah', 'mst_matakuliah.id', '=', 'trans_kurikulum_matakuliah.matakuliah_id')
                ->where('trans_kurikulum.th_akademik_id', $krsDetail->th_akademik_id)
                ->where('trans_kurikulum.prodi_id', $jadwal->prodi_id)
                ->where('mst_matakuliah.kode', $krsDetail->kode_mk)
                ->select('trans_kurikulum.*', 'trans_kurikulum_matakuliah.id as kurikulum_matakuliah_id', 'mst_matakuliah.nama as matakuliah_nama')
                ->first();
            $kurikulumMatakuliahId = @$kurikulum->kurikulum_matakuliah_id;
            if (!$kurikulum) {
                //create kurikulum
                $kurikulum = Kurikulum::where('prodi_id', $mahasiswa->prodi_id)
                    ->where('th_akademik_id', $krsDetail->th_akademik_id)
                    ->first();
                if (!$kurikulum) {
                    $kurikulum = new Kurikulum;
                    $kurikulum->th_akademik_id = $jadwal->th_akademik_id;
                    $kurikulum->prodi_id = $jadwal->prodi_id;
                    $kurikulum->nama = 'kurikulum ' . $jadwal->prodi->alias . ' ' . $jadwal->th_akademik->nama . ' ' . $jadwal->th_akademik->semester;
                    $kurikulum->save();

                    $thAkademikId = $jadwal->th_akademik_id;
                    KurikulumAngkatan::where('kurikulum_id', $kurikulum->id)->delete();
                    while ($thAkademik = ThAkademik::find($thAkademikId)) {
                        $dt_detail = new KurikulumAngkatan;
                        $dt_detail->kurikulum_id = $kurikulum->id;
                        $dt_detail->th_akademik_id = $thAkademik->id;
                        $dt_detail->user_id = Auth::user()->id;
                        $dt_detail->save();

                        $thAkademikId -= 2;
                    }
                }
                //insert kurikulum mk
                $mk = MataKuliah::where('kode', $krsDetail->kode_mk)->first();
                if (!$mk) {
                    return abort(500, 'tidak ada mk');
                }

                $kurikulumMatakuliah = KurikulumMatakuliah::where('kurikulum_id', $kurikulum->id)
                    ->where('matakuliah_id', $mk->id)
                    ->first();
                if (!$kurikulumMatakuliah) {
                    $kurikulumMatakuliah = new KurikulumMataKuliah;
                    $kurikulumMatakuliah->kurikulum_id = $kurikulum->id;
                    $kurikulumMatakuliah->matakuliah_id = $mk->id;
                    $kurikulumMatakuliah->save();
                }

                $kurikulumMatakuliahId = $kurikulumMatakuliah->id;
            }
            $jadwal->kurikulum_matakuliah_id = $kurikulumMatakuliahId;
            $jadwal->save();

            DB::commit();
            return [
                'status' => true,
                'message' => 'success',
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function getDataKHS(Request $request)
    {
        $nim = $request->nim;
        $th_akademik_id = $request->th_akademik_id;

        $row = KRSDetail::where('th_akademik_id', $th_akademik_id)
            ->where('nim', $nim)->get();

        return Datatables::of($row)
            ->editColumn('nama_mk', function ($row) use ($request) {
                if ($request->sumber_data == "master") {
                    $namaMk = @$row->jadwal_kuliah->kurikulum_matakuliah->matakuliah->nama;
                } else {
                    $namaMk = @$row->nama_mk;
                }

                $namaMk .= '|jkId:' . $row->jadwal_kuliah_id . '|';
                $namaMk .= @$row->jadwal_kuliah->kurikulum_matakuliah->kurikulum->nama;
                $namaMk .= "id:$row->id";
                return $namaMk;
            })
            ->editColumn('sks_mk', function ($row) use ($request) {
                if ($request->sumber_data == "master") {
                    $sksMk = @$row->jadwal_kuliah->kurikulum_matakuliah->matakuliah->sks;
                } else {
                    $sksMk = @$row->sks_mk;
                }

                return $sksMk;
            })
            ->editColumn('kode_mk', function ($row) use ($request) {
                if ($request->sumber_data == "master") {
                    $kodeMk = @$row->jadwal_kuliah->kurikulum_matakuliah->matakuliah->kode;
                } else {
                    $kodeMk = @$row->kode_mk;
                }
                if (Auth::user()->level->level == 'Admin') {
                    $kodeMkMaster = @$row->jadwal_kuliah->kurikulum_matakuliah->matakuliah->kode;
                    $kodeMkKhs = @$row->kode_mk;
                    if ($kodeMkMaster != $kodeMkKhs) {
                        $kodeMk .= '<a href="' . route('mahasiswa.fixKRS', ['idKrsDetail' => $row->id]) . '" target="_blank" class="btn btn-primary">fix</a>';
                    }
                }

                return $kodeMk;
            })
            ->addColumn('mutu', function ($row) use ($request) {
                if ($request->sumber_data == "master") {
                    $mutu = @$row->jadwal_kuliah->kurikulum_matakuliah->matakuliah->sks * $row->nilai_bobot;
                } else {
                    $mutu = $row->sks_mk * $row->nilai_bobot;
                }

                return $mutu;
            })
            ->rawColumns(['kode_mk'])
            ->make(true);
    }

    public function getDataKeuangan(Request $request)
    {
        $nim = $request->nim;
        $mhs = Mahasiswa::where('nim', $nim)->first();

        $row = KeuanganTagihan::select('keuangan_tagihan.*')

        // Pembayaran dari IDN ()
        // ------------------------------------------------------------------------------
            ->addSelect(DB::raw('(select idn_pembayaran.paid_date FROM idn_pembayaran
		WHERE idn_pembayaran.merchant_name<>"sekolah" AND
		idn_pembayaran.tagihan_id=keuangan_tagihan.id AND idn_pembayaran.bill_key=mst_mhs.nim) as idn_tanggal'))

            ->addSelect(DB::raw('(select idn_pembayaran.merchant_name FROM idn_pembayaran
		WHERE idn_pembayaran.tagihan_id=keuangan_tagihan.id
		AND idn_pembayaran.bill_key=mst_mhs.nim) as nama_merchant'))

            ->addSelect(DB::raw('(select SUM(idn_pembayaran.total_bill_amount) FROM idn_pembayaran
		WHERE idn_pembayaran.tagihan_id=keuangan_tagihan.id
		AND idn_pembayaran.bill_key=mst_mhs.nim) as idn_bayar')) // jumlah pembayaran

        // *********************************************************************************************************** //
        // Pembayaran dari IAI Dalwa ()
        //------------------------------------------------------------------------------------------
            ->addSelect(DB::raw('(select keuangan_pembayaran.tanggal FROM keuangan_pembayaran
		WHERE keuangan_pembayaran.tagihan_id=keuangan_tagihan.id AND keuangan_pembayaran.nim=mst_mhs.nim) as dw_tanggal'))

            ->addSelect(DB::raw('(select SUM(keuangan_pembayaran.jumlah) FROM keuangan_pembayaran
		WHERE keuangan_pembayaran.tagihan_id=keuangan_tagihan.id AND keuangan_pembayaran.nim=mst_mhs.nim) as dw_bayar'))

            ->addSelect(DB::raw('(keuangan_tagihan.jumlah - (select SUM(keuangan_pembayaran.jumlah) FROM keuangan_pembayaran
		WHERE keuangan_pembayaran.tagihan_id=keuangan_tagihan.id AND keuangan_pembayaran.nim=mst_mhs.nim)) as sisa'))

        // ========================================================================================================== //

            ->join('mst_mhs', function ($join) {
                $join->on('mst_mhs.th_akademik_id', '=', 'keuangan_tagihan.th_angkatan_id');
            })
            ->where('keuangan_tagihan.prodi_id', $mhs->prodi_id)
            ->where('keuangan_tagihan.kelas_id', $mhs->kelas_id)
            ->where('mst_mhs.nim', $nim)->with('th_akademik', 'prodi')
            ->orderBy('keuangan_tagihan.id', 'asc')->get();

        return Datatables::of($row)
            ->addColumn('th_akademik', function ($row) {
                return $row->th_akademik->kode;
            })
            ->addColumn('prodi', function ($row) {
                return $row->prodi->alias;
            })
            ->addColumn('tagihan', function ($row) {
                return $row->kode . ' - ' . $row->nama;
            })
            ->addColumn('jml_tagihan', function ($row) {
                return number_format($row->jumlah);
            })
            ->addColumn('dw_tanggal', function ($row) {
                return $row->dw_tanggal;
            })
            ->addColumn('dw_bayar', function ($row) {
                $row->dw_bayar = $row->idn_bayar == 0 ? $row->dw_bayar : 0;
                return number_format($row->dw_bayar);
            })
            ->addColumn('idn_tanggal', function ($row) {
                return $row->idn_tanggal . '-' . $row->nama_merchant;
            })
            ->addColumn('idn_bayar', function ($row) {
                return number_format($row->idn_bayar);
            })
            ->addColumn('sisa', function ($row) {
                $sisa = $row->jumlah - ($row->dw_bayar + $row->idn_bayar);
                if ($sisa < 0) {
                    $sisa = 0;
                }
                return number_format($sisa);
            })
            ->setRowClass(function ($row) {
                $sisa = $row->jumlah - ($row->dw_bayar + $row->idn_bayar);
                return $sisa > 0 ? 'alert-danger' : 'alert-success';
            })
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
            // $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $list_status = Ref::where('table', 'StatusDosen')->get();
        $list_jk = Ref::where('table', 'JenisKelamin')->get();
        $list_kota = Kota::orderBy('province_id')->get();
        $list_status = Ref::where('table', 'StatusMhs')->get();
        $list_agama = Ref::where('table', 'Agama')->get();
        $list_kelas = Ref::where('table', 'Kelas')->get();
        $list_thakademik = ThAkademik::where('semester', 'Ganjil')->orderBy('kode', 'DESC')->get();

        return view(
            $folder . '.create',
            compact(
                'title',
                'redirect',
                'folder',
                'list_prodi',
                'list_status',
                'list_jk',
                'list_kota',
                'list_status',
                'list_thakademik',
                'list_agama',
                'list_kelas',
                'prodi_id'
            )
        );
    }

    public function createUsers()
    {
        $mhs = Mahasiswa::where('status_id', 18)->get();

        foreach ($mhs as $data) {
            $user = User::where('username', $data->nim)->first();

            if (!$user) {
                $email = filter_var($data->email, FILTER_VALIDATE_EMAIL) ? strtolower($data->email) :
                $data->nim . '@email.com';

                $user = new User;
                $user->username = strtoupper($data->nim);
                $user->name = $data->nama;
                $user->email = $email;
                $user->level_id = 5;
                $user->prodi_id = $data->prodi_id;
                $user->aktif = 'Y';
                $user->password = bcrypt($data->nim);
                $user->keypass = $data->nim;
                $user->save();
            }
        }

        alert()->success('Create All Users Success', $this->title);
        return back();
    }

    public function edit($id)
    {
        $data = Mahasiswa::findOrFail($id);
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

        $list_status = Ref::where('table', 'StatusDosen')->get();
        $list_jk = Ref::where('table', 'JenisKelamin')->get();
        $list_kota = Kota::orderBy('province_id')->get();
        $list_status = Ref::where('table', 'StatusMhs')->get();
        $list_agama = Ref::where('table', 'Agama')->get();
        $list_kelas = Ref::where('table', 'Kelas')->get();

        $list_thakademik = ThAkademik::where('semester', 'Ganjil')->orderBy('kode', 'DESC')->get();

        return view(
            $folder . '.edit',
            compact(
                'data',
                'title',
                'redirect',
                'folder',
                'list_prodi',
                'list_status',
                'list_jk',
                'list_kota',
                'list_status',
                'list_thakademik',
                'list_agama',
                'list_kelas',
                'prodi_id'
            )
        );
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
        $email = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? strtolower($request->email) : null;

        $data = new Mahasiswa;
        $data->th_akademik_id = $request->th_akademik_id;
        $data->prodi_id = $request->prodi_id;
        $data->kelas_id = $request->kelas_id;
        $data->tanggal_masuk = tgl_sql($request->tanggal_masuk);
        $data->nim = strtoupper($request->nim);
        $data->nik = $request->nik;
        $data->nama = $request->nama;
        $data->jk_id = $request->jk_id;
        $data->tempat_lahir = strtoupper($request->tempat_lahir);
        $data->tanggal_lahir = tgl_sql($request->tanggal_lahir);
        $data->agama_id = $request->agama_id;
        $data->alamat = $request->alamat;
        $data->kota_id = $request->kota_id;
        $data->email = $email;
        $data->hp = $request->hp;
        $data->nik_ayah = $request->nik_ayah;
        $data->nama_ayah = $request->nama_ayah;
        $data->nik_ibu = $request->nik_ibu;
        $data->nama_ibu = $request->nama_ibu;
        $data->status_id = $request->status_id;
        $data->spm = $request->spm;
        $data->user_id = Auth::user()->id;
        $data->save();

        $user = User::where('username', $data->nim)->first();

        if (!$user) {
            $email = filter_var($data->email, FILTER_VALIDATE_EMAIL) ? strtolower($data->email) : null;

            $user = new User;
            $user->username = strtoupper($data->nim);
            $user->name = $data->nama;
            $user->email = $email;
            $user->level_id = 5;
            $user->prodi_id = $data->prodi_id;
            $user->aktif = 'Y';
            $user->password = bcrypt($data->nim);
            $user->keypass = $data->nim;
            $user->save();
        }

        alert()->success('Create Data Success', $this->title);
        return redirect($this->redirect);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules_update);
        $email = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? strtolower($request->email) : '';

        $data = Mahasiswa::findOrFail($id);
        $data->th_akademik_id = $request->th_akademik_id;
        $data->prodi_id = $request->prodi_id;
        $data->kelas_id = $request->kelas_id;
        $data->tanggal_masuk = tgl_sql($request->tanggal_masuk);
        $data->nim = strtoupper($request->nim);
        $data->nik = $request->nik;
        $data->nama = $request->nama;
        $data->jk_id = $request->jk_id;
        $data->tempat_lahir = strtoupper($request->tempat_lahir);
        $data->tanggal_lahir = tgl_sql($request->tanggal_lahir);
        $data->agama_id = $request->agama_id;
        $data->alamat = $request->alamat;
        $data->kota_id = $request->kota_id;
        $data->email = $email;
        $data->hp = $request->hp;
        $data->nik_ayah = $request->nik_ayah;
        $data->nama_ayah = $request->nama_ayah;
        $data->nik_ibu = $request->nik_ibu;
        $data->nama_ibu = $request->nama_ibu;
        $data->status_id = $request->status_id;
        $data->spm = $request->spm;
        $data->izin = $request->izin;
        $data->user_id = Auth::user()->id;
        $data->save();

        $user = User::where('username', $data->nim)->first();

        if (!$user) {
            $email = filter_var($data->email, FILTER_VALIDATE_EMAIL) ? strtolower($data->email) : null;
            $user = new User;
            $user->password = bcrypt($data->nim);
            $user->keypass = $data->nim;
        } else {
            $user->username = strtoupper($data->nim);
            $user->name = $data->nama;
            $user->email = $email;
            $user->level_id = 5;
            $user->prodi_id = $data->prodi_id;
            $user->aktif = 'Y';
            $user->save();
        }

        alert()->success('Update Data Success', $this->title);
        return redirect($this->redirect);
    }

    public function show($id)
    {
        $data = Mahasiswa::findOrFail($id);
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $list_thakademik = ThAkademik::orderBy('kode', 'DESC')
            ->get();

        return view(
            $folder . '.show',
            compact('data', 'title', 'redirect', 'folder', 'list_thakademik')
        );
    }

    public function getResetPassword($nim)
    {
        $user = User::where('username', $nim)->first();

        if ($user) {
            $user->password = bcrypt($nim);
            $user->keypass = $nim;
            $user->save();
            alert()->success('Reset Password Success', $this->title);
        } else {
            alert()->error('Username tidak ada.!!', $this->title);
        }
        return back();
    }

    public function destroy($id)
    {
        $data = Mahasiswa::findOrFail($id);
        $data->delete();

        return response()->json([
            'title' => 'Delete Data Success',
            'text' => $this->title . ' ' . $data->nama,
            'type' => 'success',
        ]);
    }

    public function cetakKRS(Request $request)
    {
        $nim = Auth::user()->username;
        $th_akademik_id = $request->th_akademik_id_krs;

        $krs = KRS::where('nim', $nim)
            ->with('mahasiswa', 'th_akademik', 'prodi', 'kelas')
            ->first();

        $th_akademik = ThAkademik::where('id', $krs->th_akademik_id)
            ->first();

        $pt = PT::first();
        $prodi = @Prodi::where('id', Auth::user()->prodi_id)
            ->first();

        $data = KRSDetail::where('nim', $nim)
            ->where('th_akademik_id', $th_akademik_id)
            ->with(['jadwal_kuliah', 'dosen', 'jamkul'])
            ->get();

        $biro_id = env('BIRO_AKADEMIK_ID');
        $biro = Pejabat::where('jabatan_id', $biro_id)->first();

        $class = 'text-center';
        $pdf = PDF::loadView(
            $this->folder . '.cetakKRS',
            compact('data', 'pt', 'th_akademik', 'krs', 'biro', 'prodi', 'class')
        );

        return $pdf->setPaper('a4', 'portrait')
            ->stream('KRS ' . $th_akademik->kode . ' ' . $krs->nim . '.pdf');
    }

    public function cetakKHS(Request $request)
    {
        $nim = Auth::user()->username;
        $th_akademik_id = $request->th_akademik_id_khs;

        $krs = KRS::where('nim', $nim)
            ->where('th_akademik_id', $th_akademik_id)
            ->with('mahasiswa', 'prodi', 'kelas')
            ->first();

        $th_akademik = ThAkademik::where('id', $krs->th_akademik_id)
            ->first();

        $pt = PT::first();
        $prodi = @Prodi::where('id', Auth::user()->prodi_id)
            ->first();

        $data = KRSDetail::where('nim', $nim)
            ->where('th_akademik_id', $th_akademik_id)
            ->get();

        $biro_id = env('BIRO_AKADEMIK_ID');
        $biro = Pejabat::where('jabatan_id', $biro_id)->first();

        $class = 'text-center';
        $pdf = PDF::loadView(
            $this->folder . '.cetakKHS',
            compact('data', 'pt', 'th_akademik', 'krs', 'biro', 'prodi', 'class')
        );

        return $pdf->setPaper('a4', 'portrait')
            ->stream('KHS ' . $th_akademik->kode . ' ' . $krs->nim . '.pdf');
    }
}
