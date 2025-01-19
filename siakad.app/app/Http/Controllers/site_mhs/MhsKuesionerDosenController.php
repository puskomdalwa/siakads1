<?php
namespace App\Http\Controllers\site_mhs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Yajra\Datatables\Datatables;
use Alert;
use App\ThAkademik;
use App\Mahasiswa;
use App\Dosen;
use App\JadwalKuliah;
use App\KRSDetail;
use App\KRS;
use App\KuesionerPertanyaan;
use App\KuesionerPertanyaanPilihan;
use App\KuesionerJawaban;
use App\KuesionerJawabanDetail;

class MhsKuesionerDosenController extends Controller
{
  private $title = 'Kuesioner Dosen';
  private $redirect = 'mhs_kuesioner_dosen';
  private $folder = 'site_mhs.mhs_kuesioner_dosen';
  private $class = 'mhs_kuesioner_dosen';



  public function index()
  {
    $nim = Auth::user()->username;
    $mhs_aktif = Mahasiswa::Aktif($nim)->first();
    $th_akademik = ThAkademik::Aktif()->first();

    $title = $this->title . ' NIM : ' . $nim . ' Tahun Akademik : ' . $th_akademik->kode;
    $redirect = $this->redirect;
    $folder = $this->folder;

    if ($mhs_aktif) {
      return view($folder . '.index', compact('title', 'redirect', 'folder', 'th_akademik', 'nim'));
    } else {
      return redirect('home');
    }

  }

  public function getData(Request $request)
  {
    $thAkademikAktif = ThAkademik::Aktif()->first()->id;
    $search = $request->search['value'];
    $nim = Auth::user()->username;

    $row = KRSDetail::
      select('trans_jadwal_kuliah.dosen_id', 'mst_dosen.nama as dosen_nama', 'mst_dosen.kode as dosen_kode', 'mst_th_akademik.kode as th_akademik_kode', 'mst_th_akademik.id as th_akademik_id', 'kuesioner_jawaban.nim as kuesioner_nim')
      ->join('trans_jadwal_kuliah', 'trans_jadwal_kuliah.id', 'trans_krs_detail.jadwal_kuliah_id')
      ->join('mst_dosen', 'trans_jadwal_kuliah.dosen_id', '=', 'mst_dosen.id')
      ->join('mst_th_akademik', 'trans_krs_detail.th_akademik_id', '=', 'mst_th_akademik.id')
      ->leftJoin('kuesioner_jawaban', function ($join) {
        $join->on('trans_jadwal_kuliah.th_akademik_id', '=', 'kuesioner_jawaban.th_akademik_id');
        $join->on('trans_jadwal_kuliah.dosen_id', '=', 'kuesioner_jawaban.dosen_id');
        $join->on('trans_krs_detail.nim', '=', 'kuesioner_jawaban.nim');
      })
      ->where('trans_krs_detail.nim', $nim)
      ->where('trans_jadwal_kuliah.th_akademik_id', '<', $thAkademikAktif)
      ->whereNull('kuesioner_jawaban.nim')
      ->groupBy('trans_jadwal_kuliah.dosen_id', 'mst_dosen.nama', 'mst_dosen.kode', 'mst_th_akademik.kode', 'mst_th_akademik.id', 'kuesioner_jawaban.nim');

    return Datatables::of($row)
      ->filter(function ($query) use ($search) {
        $query->where(function ($q) use ($search) {
          $q->orWhere('mst_dosen.nama', 'LIKE', "%$search%");
          $q->orWhere('mst_dosen.kode', 'LIKE', "%$search%");
          $q->orWhere('mst_th_akademik.kode', 'LIKE', "%$search%");
        });
      })
      ->addColumn('action', function ($row) use ($nim) {
        // $jawaban = KuesionerJawaban::where('th_akademik_id', $row->th_akademik_id)
        //   ->where('nim', $nim)->where('dosen_id', $row->dosen_id)->first();
        return '<div class="btn-group btn-group-xs" id="c-tooltips-demo">
           <a href="' . url('/' . $this->class . '/' . $row->dosen_id . '/' . $row->th_akademik_id . '/edit') . '" class="btn btn-danger btn-xs btn-rounded tooltip-primary" data-toggle="tooltip" data-placement="top" data-original-title="Isi"><i class="fa fa-pencil"></i></a>
            </div>';
        // if (!$jawaban) {
        // } else {
        //   return '<i class="fa fa-check text-success"></i>';
        // }
  
      })
      ->rawColumns(['action'])
      ->make(true);

  }

  public function edit($id, $thAkademikId)
  {
    $kuesionerJawaban = KuesionerJawaban::where([
      ['nim', Auth::user()->username],
      ['th_akademik_id', $thAkademikId],
      ['dosen_id', $id]
    ])->count();

    if ($kuesionerJawaban > 0) {
      alert()->warning('Maaf, Anda sudah isi kuesioner', $this->title);
      return redirect('mhs_kuesioner_dosen');
    }

    $th_akademik = ThAkademik::find($thAkademikId);

    $dosen = Dosen::where('id', $id)->first();
    $nim = Auth::user()->username;
    $krs = KRS::where('th_akademik_id', $th_akademik->id)->where('nim', $nim)->first();
    $matakuliah = KRSDetail::where('krs_id', $krs->id)->get();
    $pertanyaan = KuesionerPertanyaan::where('aktif', 'Y')->get();

    $title = $this->title . ' NIM : ' . $nim . ' Tahun Akademik : ' . $th_akademik->kode;
    $redirect = $this->redirect;
    $folder = $this->folder;

    return view($folder . '.edit', compact('title', 'redirect', 'folder', 'th_akademik', 'nim', 'dosen', 'matakuliah', 'pertanyaan'));
  }

  public function store(Request $request)
  {

    $jawaban = new KuesionerJawaban;
    $jawaban->th_akademik_id = $request->th_akademik_id;
    $jawaban->nim = $request->nim;
    $jawaban->dosen_id = $request->dosen_id;
    $jawaban->kekurangan = $request->kekurangan;
    $jawaban->kelebihan = $request->kelebihan;
    $jawaban->user_id = Auth::user()->id;
    $jawaban->save();


    foreach ($request->pertanyaan as $tanya) {

      $hasil = explode("#", $tanya);
      $pertanyaan_id = $hasil[0];

      $jawab = $hasil[3];
      $nilai = $hasil[2];

      $jawaban_detail = new KuesionerJawabanDetail;
      $jawaban_detail->pertanyaan_id = $pertanyaan_id;
      $jawaban_detail->jawaban_id = $jawaban->id;
      $jawaban_detail->jawab = $jawab;
      $jawaban_detail->nilai = $nilai;
      $jawaban_detail->user_id = Auth::user()->id;
      $jawaban_detail->save();
    }

    alert()->success('Input Data Success', $this->title);
    return redirect($this->redirect);
  }
}
