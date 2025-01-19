<?php
namespace App\Http\Controllers\site_dosen;

use App\Dosen;
use App\Http\Controllers\Controller;
use App\Kota;
use App\Ref;
use Auth;
use Illuminate\Http\Request;

class DosenBiodataController extends Controller
{
    public function editBiodata(Request $request)
    {
        $data = Dosen::where('kode', Auth::user()->username)->first();

        $list_status = Ref::where('table', 'StatusDosen')->get();
        $list_jk = Ref::where('table', 'JenisKelamin')->get();
        $list_kota = Kota::orderBy('province_id')->get();

        return view('site_dosen.dosen_biodata.edit', compact('data', 'list_status', 'list_jk', 'list_kota'));
    }

    public function updateBiodata(Request $request)
    {
        $dataValidated = $request->validate([
            'status_id' => 'required',
            'kode' => 'required',
            'nidn' => 'required',
            'nama' => 'required',
            'jk_id' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'alamat' => 'nullable',
            'kota' => 'nullable',
            'email' => 'nullable',
            'hp' => 'nullable',
        ]);

        $dosen = Dosen::where('kode', $dataValidated['kode'])->first();

        $dosen->update([
            'status_id' => $dataValidated['status_id'],
            'kode' => $dataValidated['kode'],
            'nidn' => $dataValidated['nidn'],
            'nama' => $dataValidated['nama'],
            'jk_id' => $dataValidated['jk_id'],
            'tempat_lahir' => $dataValidated['tempat_lahir'],
            'tanggal_lahir' => tgl_sql($dataValidated['tanggal_lahir']),
            'alamat' => $dataValidated['alamat'],
            'kota_id' => $dataValidated['kota'],
            'email' => $dataValidated['email'],
            'hp' => $dataValidated['hp'],
        ]);

        \Session::flash('status', 'Update Data Success');
        return redirect()->route('home');

    }
}