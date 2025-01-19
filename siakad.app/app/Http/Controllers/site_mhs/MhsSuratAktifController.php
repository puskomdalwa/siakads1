<?php
namespace App\Http\Controllers\site_mhs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mahasiswa;
use App\ThAkademik;
Use Alert;
use App\PT;
use PDF;
use App\Pejabat;
use Illuminate\Support\Facades\Auth;

class MhsSuratAktifController extends Controller {
    private $title	  = 'Surat Keterangan Aktif';
    private $redirect = 'mhs_surat_aktif';
    private $folder	  = 'site_mhs.mhs_surat_aktif';
    private $class	  = 'mhs_surat_aktif';

    public function index(){
        $nim = Auth::user()->username;
        $level_id	 = Auth::user()->level_id;
        $mhs_aktif	 = Mahasiswa::Aktif($nim)->first();
        $th_akademik = ThAkademik::Aktif()->first();
        
		$pt = PT::first();

        $title	  = $this->title.' Tahun Akademik '.$th_akademik->kode; 		
        $redirect = $this->redirect;
        $folder	  = $this->folder;
     
        return view($folder.'.index',
			compact('title','redirect','folder','mhs_aktif','pt')
		);
    }

    public function store(Request $request){     
		$pt	 = PT::first();
        $nim = Auth::user()->username;
        
		$level_id	 = Auth::user()->level_id;
        $mhs_aktif	 = Mahasiswa::Aktif($nim)->first();
        $th_akademik = ThAkademik::Aktif()->first();		  
		
        $pdf = PDF::loadView($this->folder.'.cetak', 
			compact('mhs_aktif','th_akademik','pt')
		);
        
		return $pdf->setPaper('a4','portrait')
		->stream('Surat Keterangan Aktif '.$mhs_aktif->nim.'.pdf');
    }
}
