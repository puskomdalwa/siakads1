<?php
namespace App\Http\Controllers\site_admin;

use Alert;
use App\Dosen;
use App\Http\Controllers\Controller;
use App\KompreDosen;
use App\Ref;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class KomprehensifDosenController extends Controller
{
    private $title = 'Dosen Ujian Komprehensif';
    private $redirect = 'kompre_dosen';
    private $folder = 'komprehensif/dosen';
    private $class = 'kompre';


    public function index()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;

        $jumlahDosen = 6;
        $dosen = Dosen::all();

        return view($folder . '.index', compact('title', 'redirect', 'jumlahDosen', 'dosen'));
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'id' => 'nullable',
                'dosen_id' => 'required',
                'penguji' => 'required',
                'jenis_kelamin' => 'required',
            ]);

            $data = KompreDosen::find($request->id);
            if (!$data) {
                $data = new KompreDosen;
            }

            $data->dosen_id = $request->dosen_id;
            $data->penguji = $request->penguji;
            $data->jenis_kelamin = $request->jenis_kelamin;
            $data->save();
            alert()->success('Berhasil update dosen penguji', $this->title);
            return back();
        } catch (\Throwable $th) {
            //throw $th;
            alert()->error('Gagal update dosen penguji ' . $th->getMessage(), $this->title);
            return back();
        }
    }

    public function delete(Request $request)
    {
        try {
            $kompreDosen = KompreDosen::findOrFail($request->id);
            $kompreDosen->dosen_id = null;
            $kompreDosen->save();
            alert()->success('Berhasil delete dosen penguji', $this->title);
            return back();
        } catch (\Throwable $th) {
            alert()->error('Gagal delete dosen penguji ' . $th->getMessage(), $this->title);
            return back();
        }
    }
}
