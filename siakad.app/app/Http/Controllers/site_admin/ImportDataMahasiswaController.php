<?php
namespace App\Http\Controllers\site_admin;

use App\Http\Controllers\Controller;
use App\Mahasiswa;
use App\Prodi;
use App\Ref;
use App\ThAkademik;
use App\User;
use Auth;
use Excel;
use Illuminate\Http\Request;

// use File;
// use App\Imports\MahasiswaImport;

class ImportDataMahasiswaController extends Controller
{
    private $title = 'Import Data Mahasiswa';
    private $redirect = 'importdatamahasiswa';
    private $folder = 'importdatamahasiswa';
    private $class = 'importdatamahasiswa';

    public function index()
    {
        $title = $this->title;
        $redirect = $this->redirect;
        $folder = $this->folder;
        $list_prodi = Prodi::get();

        return view($folder . '.index', compact('title', 'redirect', 'folder', 'list_prodi'));
    }

    public function store(Request $request)
    {
        $request->validate(['import_file' => 'required|mimes:xlsx,xls']);

        if ($request->hasFile('import_file')) {
            $path = $request->file('import_file')->getRealPath();
            $data = \Excel::load($path)->get();

            // dd($data->count());
            if ($data->count()) {
                $no = 0;
                $nu = 0;
                foreach ($data as $key => $value) {
                    $th_akademik = ThAkademik::where('kode', trim($value->th_akademik))->first();
                    $prodi = Prodi::where('kode', trim($value->kode_prodi))->first();
                    $kelas = Ref::where('table', 'Kelas')->where('kode', $value->kode_kelas)->first();

                    if ($prodi) {
                        $mhs = Mahasiswa::where('nim', trim($value->nim))->first();
                        // dd($mhs);
                        if (!$mhs) {
                            $dt = new Mahasiswa;
                            $dt->th_akademik_id = @$th_akademik->id;
                            $dt->prodi_id = @$prodi->id;
                            $dt->kelas_id = @$kelas->id;

                            // --- Simpan Sebagai Mahasiswa --------------------------------------------
                            $dt->tanggal_masuk = $value->tanggal_masuk;
                            $dt->nik = trim($value->nik);
                            $dt->nim = trim($value->nim);
                            $dt->nama = $value->nama;
                            $dt->jk_id = $this->getJK($value->jenis_kelamin);
                            $dt->tempat_lahir = $value->tempat_lahir;
                            $dt->tanggal_lahir = $value->tanggal_lahir;
                            $dt->agama_id = '10';
                            $dt->alamat = $value->alamat;
                            $dt->email = trim($value->email); //trim($value->nim).'@email.com';
                            $dt->hp = $value->hp;
                            $dt->nama_ayah = $value->nama_ayah;
                            $dt->nama_ibu = $value->nama_ibu;
                            $dt->status_id = '20';
                            $dt->user_id = Auth::user()->id;
                            $dt->save();

                            // --- Simpan Sebagai User -----------------------------------------------
                            $user = new User;
                            $user->prodi_id = $prodi->id;
                            $user->username = trim($value->nim);
                            $user->name = $value->nama;
                            $user->email = trim($value->email); //trim($value->nim).'@email.com';
                            $user->level_id = '5';
                            $user->aktif = 'Y';
                            $user->password = bcrypt($value->nim);
                            $user->keypass = $value->nim;
                            $dt->jk_id = $this->getJK($value->jenis_kelamin);
                            $user->save();
                            $no++;
                        } else {
                            $dt = Mahasiswa::findOrFail(@$mhs->id);
                            $dt->th_akademik_id = @$th_akademik->id;
                            $dt->prodi_id = @$prodi->id;
                            $dt->kelas_id = @$kelas->id;

                            // --- Simpan Sebagai Mahasiswa --------------------------------------------
                            $dt->tanggal_masuk = $value->tanggal_masuk;
                            $dt->nik = trim($value->nik);
                            $dt->nim = trim($value->nim);
                            $dt->nama = $value->nama;
                            $dt->jk_id = $this->getJK($value->jenis_kelamin);
                            $dt->tempat_lahir = $value->tempat_lahir;
                            $dt->tanggal_lahir = $value->tanggal_lahir;
                            $dt->agama_id = '10';
                            $dt->alamat = $value->alamat;
                            $dt->email = trim($value->email); //trim($value->nim).'@email.com';
                            $dt->hp = $value->hp;
                            $dt->nama_ayah = $value->nama_ayah;
                            $dt->nama_ibu = $value->nama_ibu;
                            // $dt->status_id     = '20';
                            $dt->user_id = Auth::user()->id;
                            $dt->save();
                            $nu++;
                        }
                    }
                }

                // if(!empty($arr)){
                // \DB::table('mst_mhs')->insert($arr);
                alert()->success('Dari ' . $data->count() . ' ' . $no . ' Berhasil Diupload...' . $nu . ' Berhasil Diupdate...', $this->title);
                return back();
                // }
            }
        }

        // if($request->hasFile('import_file')){
        //     $extension = File::extension($request->import_file->getClientOriginalName());
        //     if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
        //         $data = Excel::import(new MahasiswaImport, $request->file('import_file'));
        //         alert()->success('Upload Data Berhasil.',$this->title);
        //         return back();
        //     }else {
        //         alert()->error('Upload Error.','File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
        //         return back();
        //     }
        // }
    }

    private function getJK($kode)
    {
        $jk = Ref::where('table', 'JenisKelamin')->where('kode', $kode)->first();
        if ($jk) {
            return $jk->id;
        } else {
            return null;
        }
    }
}
