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

class GrafikMhsAktifController extends Controller
{
  private $title = 'Grafik Mahasiswa Aktif';
  private $redirect = 'grafikmhsaktif';
  private $folder = 'grafikmhsaktif';
  private $class = 'grafikmhsaktif';


  public function index()
  {
      $title = $this->title;
      $redirect = $this->redirect;
      $folder = $this->folder;

      $th_akademik = ThAkademik::Aktif()->first();

      return view($folder.'.index',
        compact('title','redirect','folder','th_akademik')
      );
  }

  public function chart()
  {
    $mhs = Mahasiswa::
    select('mst_prodi.nama as nama_prodi','mst_prodi.color',DB::raw('count(*) as total'))
    ->join('mst_prodi','mst_prodi.id','=','mst_mhs.prodi_id')
    ->where('mst_mhs.status_id',18)
    ->groupBy('mst_prodi.nama','mst_prodi.color')
    ->get();

    return response()->json($mhs);
  }
}
