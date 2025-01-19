<?php
namespace App\Http\Controllers\site_mhs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use Alert;

use DB;
use Auth;
use App\KRS;
use App\KRSDetail;
use App\Mahasiswa;
use App\ThAkademik;
use App\SkripsiJudul;
use App\SkripsiPengajuan;
use App\SkripsiPembimbing;

class MhsSkripsiController extends Controller
{

	private $title = 'Skripsi';
	private $redirect = 'mhs_skripsi';
	private $folder = 'site_mhs.skripsi';
	private $class = 'mhs_skripsi';

	private $rules = ['judul' => 'required',];

	public function index()
	{
		$nim = Auth::user()->username;

		$mhs_aktif = Mahasiswa::Aktif($nim)->first();
		$th_akademik = ThAkademik::Aktif()->first();

		$max_sks = $mhs_aktif->prodi->max_sks_skripsi;
		$jml_sks = 145;

		$title = 'Pengajuan Judul ' . $this->title;
		$redirect = $this->redirect;
		$folder = $this->folder;

		$pengajuan = SkripsiPengajuan::where('th_akademik_id', $th_akademik->id)
			->where('nim', $nim)
			->first();

		$acc = KRS::where('th_akademik_id', $th_akademik->id)
			->where('nim', $nim)->where('acc_pa', 'Setujui')
			->first();

		$mk_skripsi = KRSDetail::where('th_akademik_id', $th_akademik->id)
			->where('nama_mk', 'skripsi')->orwhere('nama_mk', 'tugas akhir')
			->where('nim', $nim)
			->first();

		if ($mk_skripsi) {
			$mk_skripsi = strtolower($mk_skripsi->nama_mk);
		} else {
			$mk_skripsi = '';
		}

		if ($mhs_aktif->status->id == 18) {
			if (
				$mk_skripsi == strtolower('SKRIPSI') || $mk_skripsi == strtolower('TUGAS AKHIR') ||
				$mk_skripsi == strtolower('TESIS') || $mk_skripsi == strtolower('DISERTASI')
			) {

				if (is_null($acc)) {
					alert()->warning('Maaf, KRS Anda belum di VALIDASI Dosen Wali.', $this->title);
					return redirect('mhs_info');
				}

				// Pengajuan
				if (!is_null($pengajuan)) {
					$data_judul = SkripsiJudul::where('skripsi_pengajuan_id', $pengajuan->id)->get();
					if ($pengajuan->status == 'Diperiksa') {
						return view(
							$folder . '.proses',
							compact('title', 'redirect', 'folder', 'th_akademik', 'nim', 'pengajuan', 'data_judul')
						);
					}

					// Bimbingan
					$data_pembimbing = SkripsiPembimbing::where('skripsi_pengajuan_id', $pengajuan->id)->get();
					if (!empty($data_pembimbing) && $pengajuan->status == 'Bimbingan') {
						$title = 'Skipsi Mahasiswa dengan Judul';
						$judul = SkripsiJudul::where('skripsi_pengajuan_id', $pengajuan->id)->where('acc', 'Y')->first();

						return view(
							$folder . '.bimbingan',
							compact('title', 'redirect', 'folder', 'th_akademik', 'nim', 'pengajuan', 'judul', 'data_pembimbing')
						);
					}
				}

				return view(
					$folder . '.index',
					compact('title', 'redirect', 'folder', 'th_akademik', 'nim', 'pengajuan')
				);
			}
		}

		alert()->warning('Maaf, Anda belum berhak mengisi SKRIPSI !!!', $this->title);
		return redirect('mhs_info');
	}

	public function store(Request $request)
	{
		//dd($request->all());
		//ddd($request->all());
		//dump($request->all());

		// tidak error
		// ----------------------------
		//info($request->all());
		//logger($request->all());

		$this->validate($request, $this->rules);

		$judul = $request->judul;
		$judul_id = $request->judul_id;

		$th_akademik = ThAkademik::Aktif()->first();
		$th_akademik_id = $th_akademik->id;

		$nim = Auth::user()->username;
		$mhs_aktif = Mahasiswa::Aktif($nim)->first();
		$prodi_id = $mhs_aktif->prodi_id;

		$pengajuan = SkripsiPengajuan::where('th_akademik_id', $th_akademik_id)
			->where('nim', $nim)->first();

		//dd($pengajuan);

		if (empty($pengajuan)) {
			$pengajuan = new SkripsiPengajuan;
			$pengajuan->th_akademik_id = $th_akademik_id;
			$pengajuan->prodi_id = $prodi_id;
			$pengajuan->tanggal = date('Y-m-d');
			$pengajuan->nim = $nim;
			$pengajuan->sks = null;
			$pengajuan->status = 'Baru';
			$pengajuan->user_id = Auth::user()->id;
			$pengajuan->save();
		}

		// ori
		//$dt_judul = SkripsiJudul::where('skripsi_pengajuan_id',$pengajuan->id)
		//->where('id',$judul_id)
		//->first();

		$jml_judul = SkripsiJudul::where('skripsi_pengajuan_id', $pengajuan->id)
			->count();

		/*
			  if(!empty($dt_judul)){
				  if($pengajuan->count() >= 3){
				  
					  $info = array(
						  'title'  => $this->title,
						  'info'	 => 'Anda berhak mengajukan 3 JUDUL.',
						  'status' => 'warning'
					  );
					  return response()->json($info);
				  }
				  else{
						  
				  $dt_judul = new SkripsiJudul;
				  $dt_judul->skripsi_pengajuan_id = $pengajuan->id;      
			  }
			  */


		if ($jml_judul >= 3) {
			$info = array(
				'title' => $this->title,
				'info' => 'Anda berhak mengajukan 3 JUDUL.',
				'status' => 'warning'
			);
			return response()->json($info);
		} else {
			$dt_judul = new SkripsiJudul;
			$dt_judul->skripsi_pengajuan_id = $pengajuan->id;
		}

		$dt_judul->judul = $judul;
		$dt_judul->user_id = Auth::user()->id;
		$dt_judul->save();

		$info = array(
			'title' => $this->title,
			'info' => 'Judul Berhasil Disimpan.',
			'status' => 'success'
		);
		return response()->json($info);

	}

	public function edit($id)
	{
		$data = SkripsiJudul::find($id);
		return response()->json($data);
	}

	public function show($id)
	{
		$nim = Auth::user()->username;
		$th_akademik = ThAkademik::Aktif()->first();
		$pengajuan = SkripsiPengajuan::where('th_akademik_id', $th_akademik->id)
			->where('nim', $nim)->first();

		$data_judul = SkripsiJudul::where('skripsi_pengajuan_id', $pengajuan->id)->get();
		return view($this->folder . '.list_judul', compact('data_judul'));
	}

	public function destroy($id)
	{
		SkripsiJudul::find($id)->delete();
		$info = array(
			'title' => $this->title,
			'info' => 'Judul Berhasil Dihapus.',
			'status' => 'success'
		);
		return response()->json($info);
	}
}
