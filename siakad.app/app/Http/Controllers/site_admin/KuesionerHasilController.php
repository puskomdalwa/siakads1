<?php
namespace App\Http\Controllers\site_admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Yajra\Datatables\Datatables;
Use Alert;
use App\KuesionerPertanyaan;
use App\KuesionerPertanyaanPilihan;
use App\KuesionerJawaban;
use App\KuesionerJawabanDetail;
use App\ThAkademik;
use App\Prodi;
use DB;
use App\Dosen;

class KuesionerHasilController extends Controller
{
  private $title = 'Kuesioner Hasil';
  private $redirect = 'kuesionerhasil';
  private $folder = 'kuesionerhasil';
  private $class = 'kuesionerhasil';

  private $rules = [
      'pertanyaan' => 'required|string|max:100',
  ];

  public function index()
  {
      $title = $this->title;
      $redirect = $this->redirect;
      $folder = $this->folder;
      $th_akademik_id = ThAkademik::Aktif()->first()->id;
      $list_thakademik = ThAkademik::orderBy('kode','desc')->get();
      $level = strtolower(Auth::user()->level->level);
      $prodi_id = @strtolower(Auth::user()->prodi->id);
      if(!empty($prodi_id))
      {
        $list_prodi = Prodi::where('id',$prodi_id)->get();
      }else{
        $list_prodi = Prodi::where('jenjang', '!=', 'S1')->orderBy('kode', 'ASC')->get();
            // $list_prodi = Prodi::orderBy('kode', 'ASC')->get();
      }

      return view($folder.'.index',compact('title','redirect','list_thakademik',
      'list_prodi','level','prodi_id','th_akademik_id'));
  }

  public function getData(Request $request)
  {
    $th_akademik_id = $request->th_akademik_id;
    $prodi_id = $request->prodi_id;

    $search = $request->search['value'];

    \DB::statement("SET SQL_MODE=''");

    $row = KuesionerJawaban::join('mst_dosen as dosen', 'dosen.id', '=', 'kuesioner_jawaban.dosen_id')
    ->join('mst_prodi as prodi', 'prodi.id', '=', 'dosen.prodi_id')
    ->select('kuesioner_jawaban.*', 'dosen.kode as kode_dosen', 'dosen.nama as nama_dosen', 'prodi.nama as prodi')
    ->groupBy('kuesioner_jawaban.dosen_id');

    return Datatables::of($row)
    ->filter(function ($query) use ($search, $th_akademik_id, $prodi_id) {
			$query->where('kuesioner_jawaban.th_akademik_id', $th_akademik_id)
				->when($prodi_id, function ($query) use ($prodi_id) {
					return $query->where('dosen.prodi_id', $prodi_id);
				});
			$query->where(function ($query) use ($search) {
				$query->orWhere('dosen.kode', 'LIKE', "%$search%")
					->orWhere('dosen.nama', 'LIKE', "%$search%")
					->orWhere('prodi.nama', 'LIKE', "%$search%");
			});
		})
    ->addColumn('nilai',function($row){
      $tanya = KuesionerPertanyaan::where('aktif','Y')->count();
      $nilai = KuesionerJawabanDetail::
      select(DB::raw('sum(nilai) as total'))
      ->join('kuesioner_jawaban','kuesioner_jawaban.id','=','kuesioner_jawaban_detail.jawaban_id')
      ->where('kuesioner_jawaban.dosen_id',$row->dosen_id)
      ->first();
        return  $nilai->total/$tanya;
    })
    ->addColumn('details', function($row){
         return '<button class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal" onClick="details('.$row->dosen_id.')"><i class="fa fa-eye"></i> Show</button>';
     })
    ->rawColumns(['details'])
    ->make(true);
  }

  public function getDosen(Request $request)
  {
    $th_akademik = ThAkademik::Aktif()->first();  
    $dosen = Dosen::where('id',$request->id)->first();
    $list_pertanyaan = KuesionerPertanyaan::where('aktif','Y')->get();
    $kuesioner_jawaban = KuesionerJawaban::where('th_akademik_id',$th_akademik->id)->where('dosen_id',$dosen->id)->get();
    return view($this->folder.'/nilai',compact('dosen','list_pertanyaan','kuesioner_jawaban'));
  }
}