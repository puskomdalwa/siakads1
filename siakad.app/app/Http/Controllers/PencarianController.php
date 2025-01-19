<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mahasiswa;
use App\Dosen;
use App\Prodi;
use App\MataKuliah;
use App\User;
use App\Perwalian;
use App\PerwalianDetail;
use App\KeuanganPembayaran;
use App\ThAkademik;
use App\KRS;
use App\KRSDetail;

class PencarianController extends Controller
{
    public function index(Request $request)
    {
        $th_akademik = ThAkademik::Aktif()->first();
        $th_akademik_id = $th_akademik->id;
     
        $cari = $request->cari;
        $title = 'Pencarian Data ';
    
        if($cari)
        {
            $data['user'] = User::where('username','LIKE','%'.$cari.'%')
            ->orWhere('name','LIKE','%'.$cari.'%')
            ->orderBy('username')
            ->get();

            $data['mhs'] = Mahasiswa::where('nim','LIKE','%'.$cari.'%')
            ->orWhere('nama','LIKE','%'.$cari.'%')
            ->orderBy('nama')
            ->get();
    
            $data['dosen'] = Dosen::where('kode','LIKE','%'.$cari.'%')
            ->orWhere('nama','LIKE','%'.$cari.'%')
            ->orderBy('nama')
            ->get();
    
            $data['prodi'] = Prodi::where('kode','LIKE','%'.$cari.'%')
            ->orWhere('nama','LIKE','%'.$cari.'%')
            ->orderBy('nama')
            ->get();
    
            $data['matakuliah'] = MataKuliah::where('kode','LIKE','%'.$cari.'%')
            ->orWhere('nama','LIKE','%'.$cari.'%')
            ->orderBy('nama')
            ->get();

            $data['perwalian'] = PerwalianDetail::where('nim','LIKE','%'.$cari.'%')          
            ->get();

            $data['keuangan'] = KeuanganPembayaran::
                where('th_akademik_id',$th_akademik_id)
                ->where('nim','LIKE','%'.$cari.'%')
                ->orWhere('nomor','LIKE','%'.$cari.'%')              
                ->get();

            $data['krs'] = KRS::
            where('th_akademik_id',$th_akademik_id)
            ->where('nim','LIKE','%'.$cari.'%')         
            ->get();        
          
            return view('pencarian',compact('title','cari','data'));
        }else{
            redirect('home');
        }
        
    }
}
