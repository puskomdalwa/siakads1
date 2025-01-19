<?php
namespace App\Http\Controllers\site_dosen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Yajra\Datatables\Datatables;
Use Alert;
use App\Info;
use App\ThAkademik;
use App\Dosen;

class DosenInformasiController extends Controller {

	private $title	  = 'Informasi Dosen';
	private $redirect = 'dosen_informasi';
	private $folder	  = 'site_dosen.dosen_informasi';
	private $class	  = 'dosen_informasi';

	public function index(){
		$kode		 = Auth::user()->username;
		$level_id	 = Auth::user()->level_id;
		$th_akademik = ThAkademik::Aktif()->first();

		$title	  = $this->title;		
		$redirect = $this->redirect;
		$folder	  = $this->folder;

		$data = Info::where('user_level_id',$level_id)
		->orWhereNull('user_level_id')
		->orderBy('tanggal','Desc')
		->paginate(15);

		return view($folder.'.index',compact('title','redirect','folder','data'));
  }
}
