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

class GrafikDosenController extends Controller
{
  private $title	 = 'Grafik Dosen';
  private $redirect	 = 'grafikdosen';
  private $folder	 = 'grafikdosen';
  private $class	 = 'grafikdosen';


  public function index()
  {
      $title = $this->title;
      $redirect = $this->redirect;
      $folder = $this->folder;

      return view($folder.'.index',
        compact('title','redirect','folder')
      );
  }

  public function chart()
  {
    $mhs = Dosen::
    select('mst_prodi.nama as nama_prodi','mst_prodi.color',DB::raw('count(*) as total'))
    ->join('mst_prodi','mst_prodi.id','=','mst_dosen.prodi_id')
    ->groupBy('mst_prodi.nama','mst_prodi.color')
    ->get();

    return response()->json($mhs);
  }

}
