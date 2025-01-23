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
use App\Yudisium;

class GrafikYudisiumController extends Controller
{
  private $title = 'Grafik Yudisium Mahasiswa';
  private $redirect = 'grafikyudisium';
  private $folder = 'grafikyudisium';
  private $class = 'grafikyudisium';


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
    $th_akademik_id = ThAkademik::Aktif()->first()->id;
    $mhs = Yudisium::
    select('mst_prodi.nama as nama_prodi','mst_prodi.color',DB::raw('count(*) as total'))
    ->join('mst_prodi','mst_prodi.id','=','trans_yudisium.prodi_id')
    ->where('trans_yudisium.th_akademik_id',$th_akademik_id)
    ->groupBy('mst_prodi.nama','mst_prodi.color')
    ->get();

    return response()->json($mhs);
  }
}
