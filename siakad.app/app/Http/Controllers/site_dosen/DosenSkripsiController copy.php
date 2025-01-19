<?php
namespace App\Http\Controllers\site_dosen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ThAkademik;
use App\Prodi;
use App\SkripsiPengajuan;
use App\SkripsiJudul;
use App\SkripsiPembimbing;
use Auth;
use Yajra\Datatables\Datatables;
Use Alert;
use App\Dosen;

class DosenSkripsiController extends Controller {
	private $title	  = 'Bimbingan Skripsi';
	private $redirect = 'dosen_skripsi';
	private $folder	  = 'site_dosen.skripsi';
	private $class	  = 'dosen_skripsi';

	public function index(){
		$th_akademik	= ThAkademik::Aktif()->first();
		$th_akademik_id = $th_akademik->id;

		$kode  = Auth::user()->username;
		$dosen = Dosen::where('kode',$kode)->first();

		$title	  = $this->title.' Tahun Akademik '.$th_akademik->kode;
		$redirect = $this->redirect;
		$folder	  = $this->folder;

		$data = SkripsiJudul::select('skripsi_judul.*')
		->join('skripsi_pengajuan','skripsi_pengajuan.id','=','skripsi_judul.skripsi_pengajuan_id')
		->join('skripsi_pembimbing','skripsi_pembimbing.skripsi_pengajuan_id','=','skripsi_pengajuan.id')
		->join('mst_mhs','mst_mhs.nim','=','skripsi_pengajuan.nim')
		->where('skripsi_judul.acc','Y')
		->where('skripsi_pembimbing.mst_dosen_id',$dosen->id)
		->where('skripsi_pengajuan.th_akademik_id',$th_akademik_id)->get();

		return view($folder.'.index',compact('title','redirect','folder','data'));
	}
}
