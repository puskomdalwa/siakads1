<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Dosen;
use DB;
use App\ThAkademik;
use App\Mahasiswa;
use App\Prodi;
use App\KRS;

class GrafikKrsController extends Controller {
	private $title	  = 'Grafik KRS Mahasiswa';
	private $redirect = 'grafikkrs';
	private $folder	  = 'grafikkrs';
	private $class	  = 'grafikkrs';

	public function index(){
		$title	  = $this->title;
		$redirect = $this->redirect;
		$folder   = $this->folder;

		$th_akademik = ThAkademik::Aktif()->first();

		return view($folder.'.index',
			compact('title','redirect','folder','th_akademik')
		);
	}

	public function chart() {
		$th_akademik_id = ThAkademik::Aktif()->first()->id;
		$mhs = KRS::select('mst_prodi.nama as nama_prodi','mst_prodi.color',DB::raw('count(*) as total'))
		->join('mst_prodi','mst_prodi.id','=','trans_krs.prodi_id')
		->where('trans_krs.th_akademik_id',$th_akademik_id)
		->groupBy('mst_prodi.nama','mst_prodi.color')
		->get();

		return response()->json($mhs);
	}
}
