<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ThAkademik;
use App\Mahasiswa;
use App\KeuanganTagihan;
use App\KeuanganPembayaran;
use App\KeuanganDispensasi;
use App\Perwalian;
use App\JadwalKuliah;
use App\KRS;
use App\MutasiMhs;
use App\Yudisium;

class TimelineController extends Controller {
    public function index(){
        $th_akademik = ThAkademik::Aktif()->first();    
        $title = 'Alur Data Th Akademik '.$th_akademik->kode;
        $jml_mhsbaru  = Mahasiswa::where('th_akademik_id',$th_akademik->id)->count();
        $jml_mhsaktif = Mahasiswa::where('th_akademik_id',$th_akademik->id)
        ->where('status_id',18)
        ->count();

        $jml_mhspasif = Mahasiswa::where('th_akademik_id',$th_akademik->id)
        ->where('status_id','!=',18)
        ->count();
        
        $jml_perwalian = Perwalian::select('trans_perwalian.*')
        ->join('trans_perwalian_detail','trans_perwalian.id','=','trans_perwalian_detail.perwalian_id')
        ->where('th_akademik_id',$th_akademik->id)
        ->count();
        
        $jml_tagihan = KeuanganTagihan::where('th_akademik_id',$th_akademik->id)->count();
        
        $jml_pembayaran = KeuanganPembayaran::select('nim')
        ->where('th_akademik_id',$th_akademik->id)     
        ->count();
        
        $jml_dispensasi = KeuanganDispensasi::where('th_akademik_id',$th_akademik->id)->count();

        $jml_jadwal	 = JadwalKuliah::where('th_akademik_id',$th_akademik->id)->count();
        $jml_krs	 = KRS::where('th_akademik_id',$th_akademik->id)->count();
        $jml_acc_krs = KRS::where('th_akademik_id',$th_akademik->id)->where('acc_pa','Setujui')->count();
        $jml_khs	 = JmlNilai($th_akademik->id);
        $jml_mutasi	 = MutasiMhs::where('th_akademik_id',$th_akademik->id)->count();
        $jml_wisuda	 = Yudisium::where('th_akademik_id',$th_akademik->id)->count();

        return view('timeline',
			compact(
				'title','th_akademik','jml_mhsbaru','jml_perwalian','jml_mhsaktif',
				'jml_mhspasif','jml_tagihan','jml_pembayaran','jml_dispensasi',
				'jml_jadwal','jml_krs','jml_acc_krs','jml_khs','jml_mutasi','jml_wisuda'
			)
		);
    }
}
