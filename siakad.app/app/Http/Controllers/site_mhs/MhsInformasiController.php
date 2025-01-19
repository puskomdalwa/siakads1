<?php
namespace App\Http\Controllers\site_mhs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Yajra\Datatables\Datatables;
use Alert;
use App\Info;
use App\ThAkademik;
use App\Mahasiswa;

class MhsInformasiController extends Controller
{
	private $title = 'Informasi Mahasiswa';
	private $redirect = 'mhs_informasi';
	private $folder = 'site_mhs.mhs_informasi';
	private $class = 'mhs_informasi';

	public function index()
	{
		$nim = Auth::user()->username;
		$level_id = Auth::user()->level_id;
		$mhs_aktif = Mahasiswa::Aktif($nim)->first();
		$th_akademik = ThAkademik::Aktif()->first();

		$title = $this->title;
		$redirect = $this->redirect;
		$folder = $this->folder;

		$data = Info::where('user_level_id', $level_id)
			->orWhereNull('user_level_id')
			->orderBy('tanggal', 'desc')
			->paginate(15);

		return view($folder . '.index', compact('title', 'redirect', 'folder', 'data'));
	}
}