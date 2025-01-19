<?php
namespace App\Http\Controllers\site_dosen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Yajra\Datatables\Datatables;
Use Alert;
use App\ThAkademik;
use App\KuesionerPertanyaan;
use App\KuesionerJawaban;
use App\KuesionerJawabanDetail;
use App\Dosen;
use DB;

class DosenKuesionerController extends Controller {

	private $title = 'Hasil Kuesioner Dosen';
	private $redirect = 'dosen_kuesioner';
	private $folder = 'site_dosen.dosen_kuesioner';
	private $class = 'dosen_kuesioner';

	public function index(){
		$kode		 = Auth::user()->username;
		$dosen		 = Dosen::where('kode',$kode)->first();
		$th_akademik = ThAkademik::Aktif()->first();

		$title	  = $this->title.' Kode  : '.$kode.' Tahun Akademik : '.$th_akademik->kode;
		$redirect = $this->redirect;
		$folder	  = $this->folder;
		
		$kuesioner_tanya   = KuesionerPertanyaan::where('aktif','Y')->count();
		$kuesioner_jawaban = KuesionerJawaban::where('th_akademik_id',$th_akademik->id)->where('dosen_id',$dosen->id)->get();
		return view($folder.'.index',compact('title','redirect','folder','th_akademik','kode','kuesioner_jawaban','kuesioner_tanya'));
	}

	public function getData(Request $request){
		$th_akademik = ThAkademik::Aktif()->first();
		$kode		 = Auth::user()->username;
		$dosen		 = Dosen::where('kode',$kode)->first();

		$row = KuesionerPertanyaan::get();
		return Datatables::of($row)

		->addColumn('nilai',function($row) use ($th_akademik,$dosen){
			$nilai = KuesionerJawabanDetail::select(DB::raw('sum(kuesioner_jawaban_detail.nilai) as total'))
			->join('kuesioner_jawaban','kuesioner_jawaban.id','=','kuesioner_jawaban_detail.jawaban_id')
			->where('pertanyaan_id',$row->id)
			->where('kuesioner_jawaban.th_akademik_id',$th_akademik->id)
			->where('kuesioner_jawaban.dosen_id',$dosen->id)->first();
	
			return  $nilai->total;
		})
		->make(true);
	}
}
