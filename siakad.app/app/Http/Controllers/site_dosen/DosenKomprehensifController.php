<?php
namespace App\Http\Controllers\site_dosen;

use App\Dosen;
use App\Http\Services\ServiceKompre;
use App\KompreDosen;
use App\KompreNilai;
use App\KompreNilaiLog;
use App\Mahasiswa;
use App\ThAkademik;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class DosenKomprehensifController extends Controller
{
    private $title = 'Ujian Komprehensif';
    private $redirect = 'dosen_komprehensif';
    private $folder = 'site_dosen.dosen_komprehensif';
    private $class = 'dosen_komprehensif';

    public function index()
    {
        $title = $this->title;
        $folder = $this->folder;
        $redirect = $this->redirect;

        $dosen = Dosen::where('kode', Auth::user()->username)->first();
        if ($dosen) {
            $kompreDosen = KompreDosen::where('dosen_id', $dosen->id)->first();
            if (!$kompreDosen) {
                abort(404);
            }
        }
        $list_mhs = Mahasiswa::orderBy('nim', 'DESC')->limit(10)->get();

        return view($folder . '.index', compact('title', 'list_mhs', 'redirect'));
    }

    public function getData(Request $request)
    {
        $search = $request->search['value'];
        $kode = Auth::user()->username;
        $dosen = Dosen::where('kode', $kode)->first();
        $dosenKompre = KompreDosen::where('dosen_id', $dosen->id)->first();

        $row = Mahasiswa::leftJoin('kompre_nilai', function ($join) use ($dosenKompre) {
            $join->on('mst_mhs.id', '=', 'kompre_nilai.mahasiswa_id')
                ->where('kompre_nilai.kompre_dosen_id', '=', $dosenKompre->id);
        })
            ->orderBy('mst_mhs.id', 'DESC')
            ->select('mst_mhs.*', 'kompre_nilai.id as kompreId')
            ->addSelect(\DB::raw('IFNULL(kompre_nilai.nilai, 0) AS nilai'));

        return Datatables::of($row)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('mst_mhs.nim', 'LIKE', "%$search%")
                        ->orWhere('mst_mhs.nama', 'LIKE', "%$search%")
                        ->orWhere('kompre_nilai.nilai', 'LIKE', "%$search%");
                });
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Klik <span class="caret"></span></button>
                <ul class="dropdown-menu pull-right">
                    <li><a type="button" class="dropdown-item"
                    data-toggle="modal" data-target="#modal_edit"
                        data-id="' . $row->id . '"
                        data-nim="' . $row->nim . '"
                        data-nama="' . $row->nama . '"
                        data-nilai="' . $row->nilai . '"
                    >Isi Nilai</a></li>
                    <li class="divider"></li>
                    <li><a onclick="deleteForm(' . $row->kompreId . ')">Delete</a></li>
                </ul>
            </div>';
            })
            ->rawColumns(['action', 'nilai', 'status', 'status_ujian'])
            ->make(true);
    }

    public function edit(Request $request)
    {
        try {
            $request->validate([
                'mahasiswa_id' => 'required',
                'nilai' => 'required'
            ]);

            \DB::beginTransaction();
            $kode = Auth::user()->username;
            $dosen = Dosen::where('kode', $kode)->first();
            $dosenKompre = KompreDosen::where('dosen_id', $dosen->id)->first();
            if (!$dosenKompre) {
                abort(500, "Bukan dosen penguji");
            }

            $kompre = KompreNilai::where('mahasiswa_id', $request->mahasiswa_id)->where('kompre_dosen_id', $dosenKompre->id)->first();
            if (!$kompre) {
                $kompre = new KompreNilai;
            }

            $kompre->mahasiswa_id = $request->mahasiswa_id;
            $kompre->kompre_dosen_id = $dosenKompre->id;
            $kompre->nilai = $request->nilai;
            $kompre->save();

            $kompreNilaiLog = KompreNilaiLog::where([
                ['dosen_id', $dosenKompre->dosen_id],
                ['kompre_nilai_id', $kompre->id]
            ])->first();

            
            if (!$kompreNilaiLog) {
                KompreNilaiLog::create([
                    'dosen_id' => $dosenKompre->dosen_id,
                    'kompre_nilai_id' => $kompre->id
                ]);
            }

            $thAkademik = ThAkademik::aktif()->first();
            $mahasiswa = Mahasiswa::find($request->mahasiswa_id);
            ServiceKompre::inputNilai($mahasiswa, $thAkademik->id);

            \DB::commit();
            $data = [
                'status' => true,
                'type' => 'success',
                'text' => 'Berhasil menginputkan nilai',
            ];

        } catch (\Throwable $th) {
            //throw $th;
            \DB::rollBack();
            $data = [
                'status' => false,
                'type' => 'danger',
                'text' => 'Gagal menginputkan nilai',
            ];
        }
        return $data;
    }

    public function destroy($id)
    {
        $data = KompreNilai::findOrFail($id);
        $data->delete();
        return response()->json([
            'title' => 'Delete Data Success',
            'text' => $this->title,
            'type' => 'success',
        ]);
    }
}