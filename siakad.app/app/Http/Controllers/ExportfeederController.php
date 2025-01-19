<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ThAkademik;
use App\Mahasiswa;
use App\KRS;
use DB;
use Excel;

class ExportfeederController extends Controller {
	
    public function index(){
        $title = 'Export Data Feeder';
        $list_thakademik = ThAkademik::orderBy('kode','DESC')->get();
    
        $list_table = array(
            'mst_mhs' => 'Mahasiswa',
            'akm' => 'AKM'
        );		
      
        return view('exportfeeder.index',compact(
            'title','list_thakademik','list_table'
		));
    }

    public function store(Request $request){    
        $type = 'xlsx';
        $th_akademik_id = $request->th_akademik_id;
        $thakademik = ThAkademik::where('id',$th_akademik_id)->first();
        $table_name = $request->table_name;
        $nama = null;
        $data = null;
		
        if($table_name=='mst_mhs'){          
            $data = Mahasiswa:: select('nim as NIM',DB::raw('\'\' as NISN'),'mst_mhs.nama as NAMA_MHS',
			'tempat_lahir as TEMPAT_LAHIR','tanggal_lahir as TANGGAL_LAHIR','ref.kode as JENIS_KELAMIN',            
			DB::raw('\'1\' as AGAMA'),'alamat as DESA','kota_id as WILAYAH','nama_ibu as NAMA_IBU','mst_prodi.kode as KODE_PRODI',
            'tanggal_masuk as TANGGAL_MASUK','mst_th_akademik.kode as SEMESTER_MASUK',DB::raw('\'A\' as STATUS_AKTIF'),           
			DB::raw('\'1\' as STATUS_MASUK_AWAL'),DB::raw('\'\' as SKS_DIAKUI'),DB::raw('\'\' as PTS_AWAL'),
            DB::raw('\'\' as PRODI_AWAL'),'nik as NIK')
            ->join('mst_th_akademik','mst_th_akademik.id','=','mst_mhs.th_akademik_id')
            ->join('mst_prodi','mst_prodi.id','=','mst_mhs.prodi_id')
            ->join('ref','ref.id','=','mst_mhs.jk_id')
            ->where('mst_mhs.th_akademik_id',$th_akademik_id)
            ->orderBy('mst_mhs.nim')
            ->get()
            ->toArray();
			
            $nama = 'Mahasiswa '.$thakademik->kode;
        }
		elseif($table_name=='akm'){
            $data = KRS::select('trans_krs.nim as NIM','mst_mhs.nama as NAMA','mst_th_akademik.kode as SEMESTER',
            DB::raw('(SELECT SUM(trans_krs_detail.nilai_bobot)/SUM(trans_krs_detail.sks_mk)  FROM trans_krs_detail 
			WHERE trans_krs_detail.nim=trans_krs.nim GROUP BY trans_krs_detail.nim) as IPS'),
            DB::raw('\'0\' as IPK'),
            DB::raw('(SELECT SUM(trans_krs_detail.sks_mk)  FROM trans_krs_detail 
			WHERE trans_krs_detail.nim=trans_krs.nim 
			GROUP BY trans_krs_detail.nim) as SKS_SEMESTER'),
            DB::raw('\'0\' as SKS_TOTAL'),
            DB::raw('\'AKTIF\' as STATUS_MAHASISWA'))
            ->join('mst_mhs','mst_mhs.nim','=','trans_krs.nim')
            ->join('mst_th_akademik','mst_th_akademik.id','=','trans_krs.th_akademik_id')
            ->where('trans_krs.th_akademik_id',$th_akademik_id)
            ->orderBy('trans_krs.nim')
            ->get()
            ->toArray();
            
			$nama = 'AKM '.$thakademik->kode;
        }		
     
        if($data){
            return Excel::create($nama, function($excel) use ($data) {
                $excel->sheet('Data', function($sheet) use ($data){
                    $sheet->fromArray($data);
                });
            })->download($type);
        }
        alert()->error('Tidak ada Data. Th Akademik '.$thakademik->kode);
        return back();        
    }

    public function importExcel(Request $request){
        $request->validate([
            'import_file' => 'required'
        ]);
 
        $path = $request->file('import_file')->getRealPath();
        $data = Excel::load($path)->get();
 
        if($data->count()){
            foreach ($data as $key => $value) {
                $arr[] = ['title' => $value->title, 'description' => $value->description];
            }
 
            if(!empty($arr)){
                Item::insert($arr);
            }
        } 
        return back()->with('success', 'Insert Record successfully.');
    }
}
