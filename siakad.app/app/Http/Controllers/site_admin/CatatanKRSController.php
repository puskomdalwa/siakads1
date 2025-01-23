<?php
namespace App\Http\Controllers\site_admin;

use App\FormSchadule;
use App\Http\Controllers\Controller;
use App\Mahasiswa;
use App\Prodi;
use App\Ref;
use App\ThAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

//---------------------------------------------

class CatatanKRSController extends Controller
{
    private $title = 'Catatan Kartu Rencana Studi (KRS)';
    private $redirect = 'catatanKrs';
    private $folder = 'catatanKrs';

    public function index()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        return redirect()->route('home');
        // return view($folder . '.index',

        // );
    }

    public function harusKrs()
    {
        $title = "Mahasiswa Harus KRS";
        $folder = $this->folder;

        $level = strtolower(Auth::user()->level->level);

        $th_akademik = ThAkademik::Aktif()->first();
        $th_akademik_id = ThAkademik::Aktif()->first()->id;
        $list_thakademik = ThAkademik::orderBy('kode', 'Desc')->get();

        $th_angkatan_id = ThAkademik::Aktif()->first()->id;

        $semester = ThAkademik::Aktif()->first()->semester;
        $ta_thakademik = ThAkademik::orderBy('kode', 'DESC')->get();

        if ($semester == 'Ganjil') {
            $list_thakademik = ThAkademik::where('semester', 'Ganjil')->orderBy('kode', 'DESC')->get();
        } else {
            $list_thakademik = ThAkademik::where('semester', 'Genap')->orderBy('kode', 'DESC')->get();
        }

        $tgl = date('Y-m-d H:i:s');

        if ($semester == 'Ganjil') {
            $form = FormSchadule::where('kode', 'KRS-1')->first();
        } else {
            $form = FormSchadule::where('kode', 'KRS2')->first();
        }

        $prodi_id = @strtolower(Auth::user()->prodi->id);

        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            $prodi_id = Prodi::orderBy('kode', 'ASC')->first()->id;
            // $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $kelas_id = Ref::where('kode', 'REG')->first()->id;
        $list_kelas = Ref::where('table', 'Kelas')->get();

        return view(
            $folder . '.harusKrs',
            compact(
                'title',
                'folder',
                'th_akademik_id',
                'ta_thakademik',
                'list_thakademik',
                'prodi_id',
                'list_prodi',
                'kelas_id',
                'list_kelas',
                'tgl',
                'form',
                'level'
            )
        );
    }

    public function harusKrsGetData(Request $request)
    {
        $kelas_id = $request->kelas_id;
        $prodi_id = $request->prodi_id;
        $th_akademik_id = $request->th_akademik_id;
        $jk_id = $request->jk_id;

        $columns = array(
            0 => 'mst_mhs.id',
            1 => 'mst_mhs.nim',
            2 => 'mhs.nama',
            3 => 'prodi.nama',
            4 => 'kelas.nama',
            5 => 'mhs_semester',
            6 => 'mhs_status',
            7 => 'mhs_keterangan',
        );
        $limit = $request->length;
        $start = $request->start;

        $search = $request->search['value'];
        $order = $columns[$request->order[0]['column']];
        $dir = $request->order[0]['dir'];

        if ($order != "mst_mhs.id") {
            $order = $columns[$request->order[0]['column'] + 1];
        }

        $thAkademik = ThAkademik::find($th_akademik_id);
        $thAkademikKode = substr($thAkademik->kode, 0, 4);

        $results = Mahasiswa::join('mst_th_akademik as ta', 'ta.id', '=', 'mst_mhs.th_akademik_id')
            ->join('mst_prodi as prodi', 'prodi.id', '=', 'mst_mhs.prodi_id')
            ->join('ref as kelas', 'kelas.id', '=', 'mst_mhs.kelas_id')
            ->join('mst_mhs as mhs', 'mhs.nim', '=', 'mst_mhs.nim')
            ->join('ref as status_mhs', 'status_mhs.id', '=', 'mst_mhs.status_id')
            ->leftJoin('trans_krs as krs', function ($join) use ($th_akademik_id) {
                $join->on('krs.nim', '=', 'mst_mhs.nim');
                $join->on('krs.th_akademik_id', '=', DB::raw("'$th_akademik_id'"));
            })
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('mst_mhs.prodi_id', $prodi_id);
            })
            ->when($kelas_id, function ($query) use ($kelas_id) {
                return $query->where('mst_mhs.kelas_id', $kelas_id);
            })
            ->when($jk_id != "semua", function ($query) use ($jk_id) {
                $query->where("mhs.jk_id", $jk_id);
            })
            ->where('ta.nama', '<=', $thAkademik->nama)
            ->where(function ($query) use ($search) {
                $query->orWhere('mst_mhs.nim', 'LIKE', "%$search%")
                    ->orWhere('mst_mhs.nama', 'LIKE', "%$search%")
                    ->orWhere('prodi.alias', 'LIKE', "%$search%")
                    ->orWhere('kelas.nama', 'LIKE', "%$search%")
                    ->orWhere('status_mhs.nama', '=', "$search");
            })
            ->select(
                '*',
                'kelas.nama as kelas_nama',
                'prodi.alias as prodi_nama',
                'mst_mhs.nama as mhs_nama',
                'mst_mhs.nim as mhs_nim',
                'status_mhs.nama as mhs_status',
                'krs.id as krs_id'
            )
            ->addSelect(DB::raw('(SELECT IF("' . $thAkademik->semester . '" = "Genap", 2 + (2 * (' . $thAkademikKode . ' - SUBSTRING(ta.nama, 1, 4))), 1 + (2 * (' . $thAkademikKode . ' - SUBSTRING(ta.nama, 1, 4)))) ) as mhs_semester'))
            ->addSelect(DB::raw('(SELECT IF(krs.id IS NULL, "BELUM ISI KRS", "SUDAH ISI KRS")) as mhs_keterangan'))
            ->offset($start)->limit($limit)->orderBy($order, $dir)->get();

        foreach ($results as $value) {
            switch (strtolower($value->mhs_status)) {
                case 'aktif':
                    $value->mhs_status = '<span class="badge badge-success">AKTIF</span>';
                    break;
                case 'cuti':
                    $value->mhs_status = '<span class="badge badge-warning">CUTI</span>';
                    break;
                case 'non-aktif':
                    $value->mhs_status = '<span class="badge badge-danger">NON-AKTIF</span>';
                    break;
                case 'keluar':
                    $value->mhs_status = '<span class="badge badge-secondary">KELUAR</span>';
                    break;
            }

            switch ($value->mhs_keterangan) {
                case 'SUDAH ISI KRS':
                    $value->mhs_keterangan = '<span class="badge badge-success">SUDAH ISI KRS</span>';
                    break;
                case 'BELUM ISI KRS':
                    $value->mhs_keterangan = '<span class="badge badge-danger">BELUM ISI KRS</span>';
                    break;
            }
        }

        $resultFiltered = Mahasiswa::join('mst_th_akademik as ta', 'ta.id', '=', 'mst_mhs.th_akademik_id')
            ->join('mst_prodi as prodi', 'prodi.id', '=', 'mst_mhs.prodi_id')
            ->join('ref as kelas', 'kelas.id', '=', 'mst_mhs.kelas_id')
            ->join('mst_mhs as mhs', 'mhs.nim', '=', 'mst_mhs.nim')
            ->join('ref as status_mhs', 'status_mhs.id', '=', 'mst_mhs.status_id')
            ->leftJoin('trans_krs as krs', function ($join) use ($th_akademik_id) {
                $join->on('krs.nim', '=', 'mst_mhs.nim');
                $join->on('krs.th_akademik_id', '=', DB::raw("'$th_akademik_id'"));
            })
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('mst_mhs.prodi_id', $prodi_id);
            })
            ->when($kelas_id, function ($query) use ($kelas_id) {
                return $query->where('mst_mhs.kelas_id', $kelas_id);
            })
            ->when($jk_id != "semua", function ($query) use ($jk_id) {
                $query->where("mhs.jk_id", $jk_id);
            })
            ->where('ta.nama', '<=', $thAkademik->nama)
            ->where(function ($query) use ($search) {
                $query->orWhere('mst_mhs.nim', 'LIKE', "%$search%")
                    ->orWhere('mst_mhs.nama', 'LIKE', "%$search%")
                    ->orWhere('prodi.nama', 'LIKE', "%$search%")
                    ->orWhere('kelas.nama', 'LIKE', "%$search%")
                    ->orWhere('status_mhs.nama', '=', "$search");
            })
            ->count();

        return response()->json([
            "draw" => intval(request('draw')),
            "recordsTotal" => intval(Mahasiswa::count()),
            "recordsFiltered" => intval($resultFiltered),
            "data" => $results,
        ]);
    }

    public function harusKrsGetDataRekap(Request $request)
    {
        $semester = $this->results($request)->orderBy('mhs_semester', 'asc')->get()->unique('mhs_semester')->pluck('mhs_semester')->toArray();

        $data = [];

        foreach ($semester as $value) {
            $value = (int) $value;
            $resSemester = $this->results($request)->havingRaw("mhs_semester = $value")->get();
            $sudahKrs = $this->results($request)->havingRaw("mhs_semester = $value")
                ->whereNotNull('krs.id')->get();
            $belumKrs = $this->results($request)->havingRaw("mhs_semester = $value")
                ->whereNull('krs.id')->get();
            $mhsAktif = $this->results($request)->havingRaw("mhs_semester = $value")->where('mst_mhs.status_id', 18)->get();
            $jumlahMhsSemester = count($resSemester);
            $jumlahSudahKrs = count($sudahKrs);
            $jumlahBelumKrs = count($belumKrs);
            $jumlahMhsAktif = count($mhsAktif);

            $data[] = (object) [
                "semester" => $value,
                "jumlahMhs" => $jumlahMhsSemester,
                "jumlahMhsAktif" => $jumlahMhsAktif,
                "jumlahSudahKrs" => $jumlahSudahKrs,
                "jumlahBelumKrs" => $jumlahBelumKrs,
            ];
        }

        return response()->json([
            "draw" => intval(request('draw')),
            "recordsTotal" => intval(count($data)),
            "recordsFiltered" => intval(count($data)),
            "data" => $data,
        ]);
    }

    // untuk get data yang dipakai di fungsi rekap sudah krs dan belum krs
    public function results($request)
    {
        $prodi_id = $request->prodi_id;
        $th_akademik_id = $request->th_akademik_id;
        $jk_id = $request->jk_id;

        $thAkademik = ThAkademik::find($th_akademik_id);
        $thAkademikKode = substr($thAkademik->kode, 0, 4);

        $results = Mahasiswa::join('mst_th_akademik as ta', 'ta.id', '=', 'mst_mhs.th_akademik_id')
            ->leftJoin('trans_krs as krs', function ($join) use ($th_akademik_id) {
                $join->on('krs.nim', '=', 'mst_mhs.nim');
                $join->on('krs.th_akademik_id', '=', DB::raw("'$th_akademik_id'"));
            })
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('mst_mhs.prodi_id', $prodi_id);
            })
            ->when($jk_id != "semua", function ($query) use ($jk_id) {
                $query->where("mst_mhs.jk_id", $jk_id);
            })
            ->where('ta.nama', '<=', $thAkademik->nama)
            ->select(
                '*',
                'mst_mhs.nama as mhs_nama',
                'mst_mhs.nim as mhs_nim',
                'krs.id as krs_id'
            )
            ->addSelect(DB::raw('(SELECT IF("' . $thAkademik->semester . '" = "Genap", 2 + (2 * (' . $thAkademikKode . ' - SUBSTRING(ta.nama, 1, 4))), 1 + (2 * (' . $thAkademikKode . ' - SUBSTRING(ta.nama, 1, 4)))) ) as mhs_semester'))
            ->addSelect(DB::raw('(SELECT IF(krs.id IS NULL, "BELUM ISI KRS", "SUDAH ISI KRS")) as mhs_keterangan'));
        return $results;
    }

    public function sudahKrs()
    {
        $title = "Mahasiswa Sudah KRS";
        $folder = $this->folder;

        $level = strtolower(Auth::user()->level->level);

        $th_akademik = ThAkademik::Aktif()->first();
        $th_akademik_id = ThAkademik::Aktif()->first()->id;
        $list_thakademik = ThAkademik::orderBy('kode', 'Desc')->get();

        $th_angkatan_id = ThAkademik::Aktif()->first()->id;

        $semester = ThAkademik::Aktif()->first()->semester;
        $ta_thakademik = ThAkademik::orderBy('kode', 'DESC')->get();

        if ($semester == 'Ganjil') {
            $list_thakademik = ThAkademik::where('semester', 'Ganjil')->orderBy('kode', 'DESC')->get();
        } else {
            $list_thakademik = ThAkademik::where('semester', 'Genap')->orderBy('kode', 'DESC')->get();
        }

        $tgl = date('Y-m-d H:i:s');

        if ($semester == 'Ganjil') {
            $form = FormSchadule::where('kode', 'KRS-1')->first();
        } else {
            $form = FormSchadule::where('kode', 'KRS2')->first();
        }

        $prodi_id = @strtolower(Auth::user()->prodi->id);

        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            $prodi_id = Prodi::orderBy('kode', 'ASC')->first()->id;
            // $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $kelas_id = Ref::where('kode', 'REG')->first()->id;
        $list_kelas = Ref::where('table', 'Kelas')->get();

        return view(
            $folder . '.sudahKrs',
            compact(
                'title',
                'folder',
                'th_akademik_id',
                'ta_thakademik',
                'list_thakademik',
                'prodi_id',
                'list_prodi',
                'kelas_id',
                'list_kelas',
                'tgl',
                'form',
                'level'
            )
        );
    }

    public function sudahKrsGetData(Request $request)
    {
        $kelas_id = $request->kelas_id;
        $prodi_id = $request->prodi_id;
        $th_akademik_id = $request->th_akademik_id;
        $jk_id = $request->jk_id;

        $columns = array(
            0 => 'mst_mhs.id',
            1 => 'mst_mhs.nim',
            2 => 'mhs.nama',
            3 => 'prodi.nama',
            4 => 'kelas.nama',
            5 => 'mhs_semester',
            6 => 'mhs_status',
            7 => 'mhs_keterangan',
        );
        $limit = $request->length;
        $start = $request->start;

        $search = $request->search['value'];
        $order = $columns[$request->order[0]['column']];
        $dir = $request->order[0]['dir'];

        if ($order != "mst_mhs.id") {
            $order = $columns[$request->order[0]['column'] + 1];
        }

        $thAkademik = ThAkademik::find($th_akademik_id);
        $thAkademikKode = substr($thAkademik->kode, 0, 4);

        $results = Mahasiswa::join('mst_th_akademik as ta', 'ta.id', '=', 'mst_mhs.th_akademik_id')
            ->join('mst_prodi as prodi', 'prodi.id', '=', 'mst_mhs.prodi_id')
            ->join('ref as kelas', 'kelas.id', '=', 'mst_mhs.kelas_id')
            ->join('mst_mhs as mhs', 'mhs.nim', '=', 'mst_mhs.nim')
            ->join('ref as status_mhs', 'status_mhs.id', '=', 'mst_mhs.status_id')
            ->leftJoin('trans_krs as krs', function ($join) use ($th_akademik_id) {
                $join->on('krs.nim', '=', 'mst_mhs.nim');
                $join->on('krs.th_akademik_id', '=', DB::raw("'$th_akademik_id'"));
            })
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('mst_mhs.prodi_id', $prodi_id);
            })
            ->when($kelas_id, function ($query) use ($kelas_id) {
                return $query->where('mst_mhs.kelas_id', $kelas_id);
            })
            ->when($jk_id != "semua", function ($query) use ($jk_id) {
                $query->where("mhs.jk_id", $jk_id);
            })
            ->where('ta.nama', '<=', $thAkademik->nama)
            ->where(function ($query) use ($search) {
                $query->orWhere('mst_mhs.nim', 'LIKE', "%$search%")
                    ->orWhere('mst_mhs.nama', 'LIKE', "%$search%")
                    ->orWhere('prodi.alias', 'LIKE', "%$search%")
                    ->orWhere('kelas.nama', 'LIKE', "%$search%")
                    ->orWhere('status_mhs.nama', '=', "$search");
            })
            ->whereNotNull('krs.id')
            ->select(
                '*',
                'kelas.nama as kelas_nama',
                'prodi.alias as prodi_nama',
                'mst_mhs.nama as mhs_nama',
                'mst_mhs.nim as mhs_nim',
                'status_mhs.nama as mhs_status',
                'krs.id as krs_id'
            )
            ->addSelect(DB::raw('(SELECT IF("' . $thAkademik->semester . '" = "Genap", 2 + (2 * (' . $thAkademikKode . ' - SUBSTRING(ta.nama, 1, 4))), 1 + (2 * (' . $thAkademikKode . ' - SUBSTRING(ta.nama, 1, 4)))) ) as mhs_semester'))
            ->addSelect(DB::raw('(SELECT IF(krs.id IS NULL, "BELUM ISI KRS", "SUDAH ISI KRS")) as mhs_keterangan'))
            ->offset($start)->limit($limit)->orderBy($order, $dir)->get();

        foreach ($results as $value) {
            switch (strtolower($value->mhs_status)) {
                case 'aktif':
                    $value->mhs_status = '<span class="badge badge-success">AKTIF</span>';
                    break;
                case 'cuti':
                    $value->mhs_status = '<span class="badge badge-warning">CUTI</span>';
                    break;
                case 'non-aktif':
                    $value->mhs_status = '<span class="badge badge-danger">NON AKTIF</span>';
                    break;
                case 'keluar':
                    $value->mhs_status = '<span class="badge badge-secondary">KELUAR</span>';
                    break;
            }

            switch ($value->mhs_keterangan) {
                case 'SUDAH ISI KRS':
                    $value->mhs_keterangan = '<span class="badge badge-success">SUDAH ISI KRS</span>';
                    break;
                case 'BELUM ISI KRS':
                    $value->mhs_keterangan = '<span class="badge badge-danger">BELUM ISI KRS</span>';
                    break;
            }
        }

        $resultFiltered = Mahasiswa::join('mst_th_akademik as ta', 'ta.id', '=', 'mst_mhs.th_akademik_id')
            ->join('mst_prodi as prodi', 'prodi.id', '=', 'mst_mhs.prodi_id')
            ->join('ref as kelas', 'kelas.id', '=', 'mst_mhs.kelas_id')
            ->join('mst_mhs as mhs', 'mhs.nim', '=', 'mst_mhs.nim')
            ->join('ref as status_mhs', 'status_mhs.id', '=', 'mst_mhs.status_id')
            ->leftJoin('trans_krs as krs', function ($join) use ($th_akademik_id) {
                $join->on('krs.nim', '=', 'mst_mhs.nim');
                $join->on('krs.th_akademik_id', '=', DB::raw("'$th_akademik_id'"));
            })
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('mst_mhs.prodi_id', $prodi_id);
            })
            ->when($kelas_id, function ($query) use ($kelas_id) {
                return $query->where('mst_mhs.kelas_id', $kelas_id);
            })
            ->when($jk_id != "semua", function ($query) use ($jk_id) {
                $query->where("mhs.jk_id", $jk_id);
            })
            ->where('ta.nama', '<=', $thAkademik->nama)
            ->where(function ($query) use ($search) {
                $query->orWhere('mst_mhs.nim', 'LIKE', "%$search%")
                    ->orWhere('mst_mhs.nama', 'LIKE', "%$search%")
                    ->orWhere('prodi.nama', 'LIKE', "%$search%")
                    ->orWhere('kelas.nama', 'LIKE', "%$search%")
                    ->orWhere('status_mhs.nama', '=', "$search");
            })
            ->whereNotNull('krs.id')
            ->count();

        return response()->json([
            "draw" => intval(request('draw')),
            "recordsTotal" => intval(Mahasiswa::count()),
            "recordsFiltered" => intval($resultFiltered),
            "data" => $results,
        ]);
    }

    public function belumKrs()
    {
        $title = "Mahasiswa Belum KRS";
        $folder = $this->folder;

        $level = strtolower(Auth::user()->level->level);

        $th_akademik = ThAkademik::Aktif()->first();
        $th_akademik_id = ThAkademik::Aktif()->first()->id;
        $list_thakademik = ThAkademik::orderBy('kode', 'Desc')->get();

        $th_angkatan_id = ThAkademik::Aktif()->first()->id;

        $semester = ThAkademik::Aktif()->first()->semester;
        $ta_thakademik = ThAkademik::orderBy('kode', 'DESC')->get();

        if ($semester == 'Ganjil') {
            $list_thakademik = ThAkademik::where('semester', 'Ganjil')->orderBy('kode', 'DESC')->get();
        } else {
            $list_thakademik = ThAkademik::where('semester', 'Genap')->orderBy('kode', 'DESC')->get();
        }

        $tgl = date('Y-m-d H:i:s');

        if ($semester == 'Ganjil') {
            $form = FormSchadule::where('kode', 'KRS-1')->first();
        } else {
            $form = FormSchadule::where('kode', 'KRS2')->first();
        }

        $prodi_id = @strtolower(Auth::user()->prodi->id);

        if ($prodi_id) {
            $list_prodi = Prodi::where('id', $prodi_id)->get();
        } else {
            $prodi_id = Prodi::orderBy('kode', 'ASC')->first()->id;
            // $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
            $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
        }

        $kelas_id = Ref::where('kode', 'REG')->first()->id;
        $list_kelas = Ref::where('table', 'Kelas')->get();

        return view(
            $folder . '.belumKrs',
            compact(
                'title',
                'folder',
                'th_akademik_id',
                'ta_thakademik',
                'list_thakademik',
                'prodi_id',
                'list_prodi',
                'kelas_id',
                'list_kelas',
                'tgl',
                'form',
                'level'
            )
        );
    }

    public function belumKrsGetData(Request $request)
    {
        $kelas_id = $request->kelas_id;
        $prodi_id = $request->prodi_id;
        $th_akademik_id = $request->th_akademik_id;
        $jk_id = $request->jk_id;

        $columns = array(
            0 => 'mst_mhs.id',
            1 => 'mst_mhs.nim',
            2 => 'mhs.nama',
            3 => 'prodi.nama',
            4 => 'kelas.nama',
            5 => 'mhs_semester',
            6 => 'mhs_status',
            7 => 'mhs_keterangan',
        );
        $limit = $request->length;
        $start = $request->start;

        $search = $request->search['value'];
        $order = $columns[$request->order[0]['column']];
        $dir = $request->order[0]['dir'];

        if ($order != "mst_mhs.id") {
            $order = $columns[$request->order[0]['column'] + 1];
        }

        $thAkademik = ThAkademik::find($th_akademik_id);
        $thAkademikKode = substr($thAkademik->kode, 0, 4);
        $results = Mahasiswa::join('mst_th_akademik as ta', 'ta.id', '=', 'mst_mhs.th_akademik_id')
            ->join('mst_prodi as prodi', 'prodi.id', '=', 'mst_mhs.prodi_id')
            ->join('ref as kelas', 'kelas.id', '=', 'mst_mhs.kelas_id')
            ->join('mst_mhs as mhs', 'mhs.nim', '=', 'mst_mhs.nim')
            ->join('ref as status_mhs', 'status_mhs.id', '=', 'mst_mhs.status_id')
            ->leftJoin('trans_krs as krs', function ($join) use ($th_akademik_id) {
                $join->on('krs.nim', '=', 'mst_mhs.nim');
                $join->on('krs.th_akademik_id', '=', DB::raw("'$th_akademik_id'"));
            })
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('mst_mhs.prodi_id', $prodi_id);
            })
            ->when($kelas_id, function ($query) use ($kelas_id) {
                return $query->where('mst_mhs.kelas_id', $kelas_id);
            })
            ->when($jk_id != "semua", function ($query) use ($jk_id) {
                $query->where("mhs.jk_id", $jk_id);
            })
            ->where(function ($query) use ($search) {
                $query->orWhere('mst_mhs.nim', 'LIKE', "%$search%")
                    ->orWhere('mst_mhs.nama', 'LIKE', "%$search%")
                    ->orWhere('prodi.alias', 'LIKE', "%$search%")
                    ->orWhere('kelas.nama', 'LIKE', "%$search%")
                    ->orWhere('status_mhs.nama', '=', "$search");
            })
            ->where('ta.nama', '<=', $thAkademik->nama)
            ->whereNull('krs.id')
            ->select(
                '*',
                'kelas.nama as kelas_nama',
                'prodi.alias as prodi_nama',
                'mst_mhs.nama as mhs_nama',
                'mst_mhs.nim as mhs_nim',
                'status_mhs.nama as mhs_status',
                'krs.id as krs_id'
            )
            ->addSelect(DB::raw('(SELECT IF("' . $thAkademik->semester . '" = "Genap", 2 + (2 * (' . $thAkademikKode . ' - SUBSTRING(ta.nama, 1, 4))), 1 + (2 * (' . $thAkademikKode . ' - SUBSTRING(ta.nama, 1, 4)))) ) as mhs_semester'))
            ->addSelect(DB::raw('(SELECT IF(krs.id IS NULL, "BELUM ISI KRS", "SUDAH ISI KRS")) as mhs_keterangan'))
            ->offset($start)->limit($limit)->orderBy($order, $dir)->get();

        foreach ($results as $value) {
            switch (strtolower($value->mhs_status)) {
                case 'aktif':
                    $value->mhs_status = '<span class="badge badge-success">AKTIF</span>';
                    break;
                case 'cuti':
                    $value->mhs_status = '<span class="badge badge-warning">CUTI</span>';
                    break;
                case 'non-aktif':
                    $value->mhs_status = '<span class="badge badge-danger">NON AKTIF</span>';
                    break;
                case 'keluar':
                    $value->mhs_status = '<span class="badge badge-secondary">KELUAR</span>';
                    break;
            }

            switch ($value->mhs_keterangan) {
                case 'SUDAH ISI KRS':
                    $value->mhs_keterangan = '<span class="badge badge-success">SUDAH ISI KRS</span>';
                    break;
                case 'BELUM ISI KRS':
                    $value->mhs_keterangan = '<span class="badge badge-danger">BELUM ISI KRS</span>';
                    break;
            }
        }

        $resultFiltered = Mahasiswa::join('mst_th_akademik as ta', 'ta.id', '=', 'mst_mhs.th_akademik_id')
            ->join('mst_prodi as prodi', 'prodi.id', '=', 'mst_mhs.prodi_id')
            ->join('ref as kelas', 'kelas.id', '=', 'mst_mhs.kelas_id')
            ->join('mst_mhs as mhs', 'mhs.nim', '=', 'mst_mhs.nim')
            ->join('ref as status_mhs', 'status_mhs.id', '=', 'mst_mhs.status_id')
            ->leftJoin('trans_krs as krs', function ($join) use ($th_akademik_id) {
                $join->on('krs.nim', '=', 'mst_mhs.nim');
                $join->on('krs.th_akademik_id', '=', DB::raw("'$th_akademik_id'"));
            })
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('mst_mhs.prodi_id', $prodi_id);
            })
            ->when($kelas_id, function ($query) use ($kelas_id) {
                return $query->where('mst_mhs.kelas_id', $kelas_id);
            })
            ->when($jk_id != "semua", function ($query) use ($jk_id) {
                $query->where("mhs.jk_id", $jk_id);
            })
            ->where('ta.nama', '<=', $thAkademik->nama)
            ->where(function ($query) use ($search) {
                $query->orWhere('mst_mhs.nim', 'LIKE', "%$search%")
                    ->orWhere('mst_mhs.nama', 'LIKE', "%$search%")
                    ->orWhere('prodi.nama', 'LIKE', "%$search%")
                    ->orWhere('kelas.nama', 'LIKE', "%$search%")
                    ->orWhere('status_mhs.nama', '=', "$search");
            })
            ->whereNull('krs.id')
            ->count();

        return response()->json([
            "draw" => intval(request('draw')),
            "recordsTotal" => intval(Mahasiswa::count()),
            "recordsFiltered" => intval($resultFiltered),
            "data" => $results,
        ]);
    }

    public function rekapExcel($th_akademik_id, $kelas_id, $prodi_id, $jk_id)
    {
        // daftar
        $thAkademik = ThAkademik::find($th_akademik_id);
        $thAkademikKode = substr($thAkademik->kode, 0, 4);

        $mahasiswa = Mahasiswa::join('mst_th_akademik as ta', 'ta.id', '=', 'mst_mhs.th_akademik_id')
            ->join('mst_prodi as prodi', 'prodi.id', '=', 'mst_mhs.prodi_id')
            ->join('ref as kelas', 'kelas.id', '=', 'mst_mhs.kelas_id')
            ->join('mst_mhs as mhs', 'mhs.nim', '=', 'mst_mhs.nim')
            ->join('ref as status_mhs', 'status_mhs.id', '=', 'mst_mhs.status_id')
            ->leftJoin('trans_krs as krs', function ($join) use ($th_akademik_id) {
                $join->on('krs.nim', '=', 'mst_mhs.nim');
                $join->on('krs.th_akademik_id', '=', DB::raw("'$th_akademik_id'"));
            })
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('mst_mhs.prodi_id', $prodi_id);
            })
            ->when($kelas_id, function ($query) use ($kelas_id) {
                return $query->where('mst_mhs.kelas_id', $kelas_id);
            })
            ->when($jk_id != "semua", function ($query) use ($jk_id) {
                $query->where("mhs.jk_id", $jk_id);
            })
            ->where('ta.nama', '<=', $thAkademik->nama)
            ->select(
                '*',
                'kelas.nama as kelas_nama',
                'prodi.alias as prodi_nama',
                'mst_mhs.nama as mhs_nama',
                'mst_mhs.nim as mhs_nim',
                'status_mhs.nama as mhs_status',
                'krs.id as krs_id'
            )
            ->addSelect(DB::raw('(SELECT IF("' . $thAkademik->semester . '" = "Genap", 2 + (2 * (' . $thAkademikKode . ' - SUBSTRING(ta.nama, 1, 4))), 1 + (2 * (' . $thAkademikKode . ' - SUBSTRING(ta.nama, 1, 4)))) ) as mhs_semester'))
            ->addSelect(DB::raw('(SELECT IF(krs.id IS NULL, "BELUM ISI KRS", "SUDAH ISI KRS")) as mhs_keterangan'))
            ->orderBy('mhs_semester', 'asc')->get();

        // jumlah
        $request = (object) [];
        $request->prodi_id = $prodi_id;
        $request->th_akademik_id = $th_akademik_id;
        $request->jk_id = $jk_id;

        $semester = $this->results($request)->orderBy('mhs_semester', 'asc')->get()->unique('mhs_semester')->pluck('mhs_semester')->toArray();

        $data = [];

        foreach ($semester as $value) {
            $value = (int) $value;
            $resSemester = $this->results($request)->havingRaw("mhs_semester = $value")->get();

            $sudahKrs = $this->results($request)->havingRaw("mhs_semester = $value")
                ->whereNotNull('krs.id')->get();
            $belumKrs = $this->results($request)->havingRaw("mhs_semester = $value")
                ->whereNull('krs.id')->get();
            $mhsAktif = $this->results($request)->havingRaw("mhs_semester = $value")->where('mst_mhs.status_id', 18)->get();
            $jumlahMhsSemester = count($resSemester);
            $jumlahSudahKrs = count($sudahKrs);
            $jumlahBelumKrs = count($belumKrs);
            $jumlahMhsAktif = count($mhsAktif);

            $data[] = (object) [
                "semester" => $value,
                "jumlahMhs" => $jumlahMhsSemester,
                "jumlahSudahKrs" => $jumlahSudahKrs,
                "jumlahBelumKrs" => $jumlahBelumKrs,
                "jumlahMhsAktif" => $jumlahMhsAktif,
            ];
        }

        $thAkademik = ThAkademik::find($th_akademik_id);
        $prodi = Prodi::find($prodi_id);

        $jenisKelamin = "";
        switch ($jk_id) {
            case 'PutraPutri':
                $jenisKelamin = "Semua";
                break;
            case 8:
                $jenisKelamin = "Putra";
                break;
            case 9:
                $jenisKelamin = "Putri";
                break;
        }
        return view('catatanKrs.rekapKRSExcel', compact('mahasiswa', 'data', 'thAkademik', 'prodi', 'jenisKelamin'));
    }
}
