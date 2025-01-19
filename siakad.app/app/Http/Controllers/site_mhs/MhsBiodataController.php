<?php
namespace App\Http\Controllers\site_mhs;

use App\Kota;
use App\Mahasiswa;
use App\Ref;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MhsBiodataController extends Controller {
    public function editBiodata(Request $request)
    {
        $data = Mahasiswa::where('nim', Auth::user()->username)->first();
       
        $list_status = Ref::where('table', 'StatusMhs')->get();
		$list_jk	 = Ref::where('table','JenisKelamin')->get();
		$list_kota = Kota::orderBy('province_id')->get();
        $list_agama = Ref::where('table', 'Agama')->get();

        return view('site_mhs.mhs_biodata.edit', compact('data', 'list_status','list_jk','list_kota','list_agama'));  
    }

    public function updateBiodata(Request $request)
    {
        $dataValidated = $request->validate([
            'th_akademik_id' => 'required',
            'status_id' => 'required',
            'tanggal_masuk' => 'nullable',
            'nim' => 'required',
            'nik' => 'nullable',
            'nama' => 'required',
            'jk_id' => 'required',
            'tempat_lahir' => 'nullable',
            'tanggal_lahir' => 'nullable',
            'agama_id' => 'nullable',
            'alamat' => 'nullable',
            'kota_id' => 'nullable',
            'email' => 'nullable',
            'hp' => 'nullable',
        ]);

        $mhs = Mahasiswa::where('nim', $dataValidated['nim'])->first();

        $mhs->update([
            'th_akademik_id' => $dataValidated['th_akademik_id'],
            'status_id' => $dataValidated['status_id'],
            'tanggal_masuk' => $dataValidated['tanggal_masuk'],
            'nim' => strtoupper($dataValidated['nim']),
            'nik' => $dataValidated['nik'],
            'nama' => $dataValidated['nama'],
            'jk_id' => $dataValidated['jk_id'],
            'tempat_lahir' => $dataValidated['tempat_lahir'],
            'tanggal_lahir' => tgl_sql($dataValidated['tanggal_lahir']),
            'agama_id' => $dataValidated['agama_id'],
            'alamat' => $dataValidated['alamat'],
            'kota_id' => $dataValidated['kota_id'],
            'email' => $dataValidated['email'],
            'hp' => $dataValidated['hp'],
        ]);

        \Session::flash('status', 'Update Data Success');
        return redirect()->route('home');

    }
}