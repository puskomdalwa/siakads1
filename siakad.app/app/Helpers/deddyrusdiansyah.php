<?php
use App\KeuanganTagihan;
use App\KeuanganDispensasi;
use App\KeuanganPembayaran;
use App\KeuanganPembayaranIDN;
use App\Mahasiswa;
use App\KRS;
use App\KRSDetail;
use App\KRSDetailNilai;
use App\ThAkademik;
use App\KuesionerJawabanDetail;
use App\Absensi;
use App\AbsensiDetail;

//---------------------------------------

use App\KomponenNilai;

function JmlNilai($thakademik_id)
{
	$krs = KRS::select('trans_krs.*')
		->join('trans_krs_detail', 'trans_krs.id', '=', 'trans_krs_detail.krs_id')
		->join('trans_krs_detail_nilai', 'trans_krs_detail.id', '=', 'trans_krs_detail_nilai.krs_detail_id')
		->where('trans_krs.th_akademik_id', $thakademik_id)->count();
	return $krs;
}

function isi_kelas($jadwal_kuliah_id)
{
	$data = KRSDetail::where('jadwal_kuliah_id', $jadwal_kuliah_id)
		->count();
	return $data;
}

// Jumalah Tatap Muka Dosen
function pertemuanke($id)
{
	$data = Absensi::where('trans_jadwal_kuliah_id', $id)->count();
	return $data;
}

// ($id) ==>> ID Jadwal Kuliah
function jmlabsdos($id)
{
	$data = Absensi::where('trans_jadwal_kuliah_id', $id)->count();
	return $data;
}

// ($id) ==>> ID Jadwal Kuliah, ($nim) ==>> NIM Mahasiswa
function jmlabsmhs($id, $nim)
{
	$data = AbsensiDetail::where('trans_jadwal_kuliah_id', $id)
		->where('nim', $nim)->where('status', 'Hadir')->count();
	return $data;
}

// ($id) ==>> ID Jadwal Kuliah
function jmlabshadir($id)
{
	$data = AbsensiDetail::where('trans_absensi_mhs', $id)
		->where('status', 'Hadir')->count();
	return $data;
}

// ($id) ==>> ID Jadwal Kuliah
function jmlabsalpa($id)
{
	$data = AbsensiDetail::where('trans_absensi_mhs', $id)
		->where('status', 'Alpa')->count();
	return $data;
}

// ($id) ==>> ID Jadwal Kuliah
function jmlabssakit($id)
{
	$data = AbsensiDetail::where('trans_absensi_mhs', $id)
		->where('status', 'Sakit')->count();
	return $data;
}

// ($id) ==>> ID Jadwal Kuliah
function jmlabsijin($id)
{
	$data = AbsensiDetail::where('trans_absensi_mhs', $id)
		->where('status', 'Ijin')->count();
	return $data;
}

// ($id) ==>> ID Jadwal Kuliah
function jmlabslain($id)
{
	$data = AbsensiDetail::where('trans_absensi_mhs', $id)
		->where('status', '')->count();
	return $data;
}

function getKuesionerNilai($dosen_id, $pertanyaan_id)
{
	$th_akademik = ThAkademik::Aktif()->first();

	$row = KuesionerJawabanDetail::select('kuesioner_jawaban_detail.nilai')
		->join('kuesioner_jawaban', 'kuesioner_jawaban.id', '=', 'kuesioner_jawaban_detail.jawaban_id')
		->where('pertanyaan_id', $pertanyaan_id)
		->where('kuesioner_jawaban.th_akademik_id', $th_akademik->id)
		->where('kuesioner_jawaban.dosen_id', $dosen_id)->first();
	return $row->nilai;
}

function getKRSDetailNilai($krs_detail_id, $komponen_nilai_id)
{
	$return = KRSDetailNilai::where('krs_detail_id', $krs_detail_id)
		->where('komponen_nilai_id', $komponen_nilai_id)->first();
	return $return->nilai;
}

function getKRSJmlMhsACC($jadwal_kuliah_id)
{
	$jml = KRSDetail::select('trans_krs_detail.nim')
		->join('trans_krs', 'trans_krs.id', '=', 'trans_krs_detail.krs_id')
		->join('mst_mhs', 'mst_mhs.nim', '=', 'trans_krs.nim')
		->where('trans_krs_detail.jadwal_kuliah_id', $jadwal_kuliah_id)
		->where('trans_krs.acc_pa', 'Setujui')->count();
	return $jml;
}

function getKRSJmlMhs($jadwal_kuliah_id)
{
	$jml = KRSDetail::where('jadwal_kuliah_id', $jadwal_kuliah_id)->count();
	return $jml;
}

function nilai_abshadir($id, $nim)
{
	$jmlabsdos = Absensi::where('trans_jadwal_kuliah_id', $id)->count();
	$jmlabsmhs = AbsensiDetail::where('trans_jadwal_kuliah_id', $id)
		->where('nim', $nim)->count();

	$komponen = KomponenNilai::where('nama', 'absensi')->first();
	$nilaibobot = $komponen->bobot;

	$nilabshadir = 0;
	if ($jmlabsmhs > 0 && $jmlabsdos > 0) {
		$nilabshadir = ($jmlabsmhs * $nilaibobot) / $jmlabsdos;
	}

	return $nilabshadir;
}

function getNilaiAbs($id, $nim)
{
	$jmlabsdos = Absensi::where('trans_jadwal_kuliah_id', $id)->count();
	$jmlabsmhs = AbsensiDetail::where('trans_jadwal_kuliah_id', $id)
		->where('nim', $nim)->where('status', 'Hadir')->count();

	if ($jmlabsmhs > 0 && $jmlabsdos > 0) {
		$return = ceil(($jmlabsmhs / $jmlabsdos) * 100);
	} else {
		$return = 0;
	}

	if ($return > 100) {
		$return = 100;
	}

	return $return;
}

function getNilai($krs_detail_id, $komponen_nilai_id)
{
	$nilai = KRSDetailNilai::where('krs_detail_id', $krs_detail_id)
		->where('komponen_nilai_id', $komponen_nilai_id)->first();

	if ($nilai) {
		$return = $nilai->nilai;
	} else {
		$return = null;
	}

	return $return;
}

function sks_total($th_akademik_id, $nim)
{
	$krs = KRSDetail::select(DB::raw('sum(sks_mk) as total_sks'))
		->where('th_akademik_id', $th_akademik_id)
		->where('nim', $nim)->first();

	if ($krs) {
		$return = !empty($krs->total_sks) ? $krs->total_sks : 0;
	} else {
		$return = 0;
	}

	return $return;
}

function acc_krs($th_akademik_id, $nim)
{
	$krs = KRS::select('acc_pa')
		->where('th_akademik_id', $th_akademik_id)
		->where('nim', $nim)->first();

	if ($krs) {
		$return = $krs->acc_pa;
	} else {
		$return = '';
	}

	return $return;
}

function bayarWisuda($th_akademik_id, $nim)
{
	$kode = 'wis';
	$bayar = KeuanganPembayaran::where('th_akademik_id', $th_akademik_id)
		->where('nim', $nim)
		->orderBy('tanggal', 'desc')->first();

	if ($bayar) {
		$kode_form = $bayar->tagihan->form_schadule->kode;
		if (strtolower($kode_form) == strtolower($kode)) {
			$return = 'Pembayaran ' . $bayar->tagihan->nama . ' Nomor ' .
				$bayar->nomor . ' Tanggal ' . tgl_str($bayar->tanggal);
		} else {
			$return = null;
		}
	} else {
		$dispensasi = KeuanganDispensasi::where('th_akademik_id', $th_akademik_id)
			->where('nim', $nim)->first();

		if ($dispensasi) {
			$return = ' Tanggal Dispensasi ' . $dispensasi->created_at;
		} else {
			$return = null;
		}
	}

	return $return;
}

function KeuanganMhs($nim, $th_akademik_id)
{
	//$kode  = 'krs-1';	
	$krs1 = 'krs-1';
	$krs2 = 'krs-2';

	$bayar = KeuanganPembayaran::where('th_akademik_id', $th_akademik_id)
		->where('nim', $nim)->first();

	if ($bayar) {
		$kode_form = $bayar->tagihan->form_schadule->kode;
		if ((strtolower($kode_form) == strtolower($krs1)) || (strtolower($kode_form) == strtolower($krs2))) {
			$return = 'Pembayaran ' . $bayar->tagihan->nama . ' Nomor ' . $bayar->nomor . ' Tanggal ' . tgl_str($bayar->tanggal);
		} else {
			$return = 'null';
		}
	} else {
		$dispensasi = KeuanganDispensasi::where('th_akademik_id', $th_akademik_id)
			->where('nim', $nim)->first();
		if ($dispensasi) {
			$return = ' Tanggal Dispensasi ' . $dispensasi->created_at;
		} else {
			$return = null;
		}
	}
	return $return;
}

function KeuanganMhsIDN($nim, $th_akademik_id)
{
	$kode = 'krs';
	$bayar = KeuanganPembayaranIDN::where('th_akademik_id', $th_akademik_id)
		->where('bill_key', $nim)->first();

	if ($bayar) {
		$kode_form = $bayar->tagihan->form_schadule->kode;
		if (strtolower($kode_form) == strtolower($kode)) {
			$return = 'Pembayaran ' . $bayar->tagihan->nama . ' Nomor ' . $bayar->biller_code . ' Tanggal ' . tgl_str($bayar->paid_date);
		} else {
			$return = null;
		}
	}
	return $return;
}

function getSisaTagihan($nim, $tagihan_id)
{
	$tagihan = KeuanganTagihan::select('jumlah')->where('id', $tagihan_id)->first();
	$jml_tagihan = $tagihan->jumlah;

	$pembayaran = KeuanganPembayaranIDN::select(DB::raw('SUM(total_bill_amount) as total_bayar'))
		->where('tagihan_id', $tagihan_id)->where('bill_key', $nim)->first();
	$jml_bayar_idn = $pembayaran->total_bayar;

	$pembayaran = KeuanganPembayaran::select(DB::raw('SUM(jumlah) as total_bayar'))
		->where('tagihan_id', $tagihan_id)->where('nim', $nim)->first();
	$jml_bayar_pdw = $pembayaran->total_bayar;

	$sisa = $jml_tagihan - ($jml_bayar_idn + $jml_bayar_pdw);
	return $sisa;
}

function getSKS($th_akademik_id, $nim)
{
	$krs_detail = KRSDetail::select(DB::raw('sum(sks_mk) as total_sks'))
		->where('th_akademik_id', $th_akademik_id)
		->where('nim', $nim)->first();

	if ($krs_detail) {
		$return = $krs_detail->total_sks;
	} else {
		$return = 0;
	}
	return $return;
}

function getSKSTranskrip($nim)
{
	$krs_detail = KRSDetail::select(DB::raw('sum(sks_mk) as total_sks'))
		->where('nim', $nim)->first();

	if ($krs_detail) {
		$return = $krs_detail->total_sks;
	} else {
		$return = 0;
	}
	return $return;
}

function getIP($nim, $th_akademik_id, $smt = null)
{
	$krs = KRS::where('th_akademik_id', $th_akademik_id)
		->where('nim', $nim)
		->where('smt', $smt)->first();

	if ($krs) {
		if ($smt > 0) {
			$nilai = KRSDetail::select(DB::raw('SUM(nilai_bobot) / SUM(sks_mk) as ip'))
				->where('krs_id', $krs->id)->first();
			$ip = number_format($nilai->ip, 2);
		} else {
			$ip = 0;
		}
	} else {
		$ip = 0;
	}
	return $ip;
}

// Function rekap
function getSMT($thawal, $thakhir)
{
	$tha = substr($thawal, 0, 4);
	$thb = substr($thakhir, 0, 4);
	$smt = substr($thakhir, 4, 1);

	$th = $tha - $thb;
	if ($th < 1) {
		$th = $thb - $tha;
		$hasil = ($th * 2) + $smt;
	} else {
		$hasil = ($th * 2) + $smt;
	}
	return $hasil;
}

function TSKS($th_akademik_id, $nim)
{
	$krs = KRSDetail::select(DB::raw('SUM(sks_mk) as s_sks'))
		->where('th_akademik_id', $th_akademik_id)
		->where('nim', $nim)->first();

	if ($krs) {
		$hasil = $krs->s_sks;
	} else {
		$hasil = 0;
	}
	return $hasil;
}

function TIP($th_akademik_id, $nim)
{
	$nilai = KRSDetail::select(DB::raw('sum(nilai_bobot) / sum(sks_mk) as ip'))
		->where('th_akademik_id', $th_akademik_id)
		->where('nim', $nim)->first();

	if ($nilai->ip > 0) {
		$ip = number_format($nilai->ip, 2);
	} else {
		$ip = '';
	}
	return $ip;
}

function getSemesterMahasiswa($th_masuk, $npm = null)
{
	$th_aktif = ThAkademik::Aktif()->first();
	$smt = $th_aktif->semester;
	$cuti = 0;

	$th_a = substr($th_aktif->kode, 0, 4);
	$th_m = substr($th_masuk, 0, 4);

	$smt_m = substr($th_aktif->kode, 4, 1);

	if ((int) $th_a >= $th_m) {
		$th = $th_a - $th_m;
		$hasil = ($th * 2) + $smt_m;
	} else {
		$hasil = 0;
	}

	return $hasil;
}

function list_semester()
{
	$semester = [1, 2, 3, 4, 5, 6, 7, 8];
	return $semester;
}

function list_semester_ganjil()
{
	$semester = [1, 3, 5, 7];
	return $semester;
}

function list_semester_genap()
{
	$semester = [2, 4, 6, 8];
	return $semester;
}

function max_sks()
{
	$max_sks = 24;
	return $max_sks;
}

function hari($tanggal)
{
	$day = date('D', strtotime($tanggal));

	$dayList = array(
		'Sun' => 'Minggu',
		'Mon' => 'Senin',
		'Tue' => 'Selasa',
		'Wed' => 'Rabu',
		'Thu' => 'Kamis',
		'Fri' => 'Jum\'at',
		'Sat' => 'Sabtu'
	);
	return $dayList[$day];
}

function namahari($date)
{
	switch (date_format(date_create($date), 'j')) {
		case '1':
			$hari = 'Senin';
			break;
		case '2':
			$hari = 'Selasa';
			break;
		case '3':
			$hari = 'Rabu';
			break;
		case '4':
			$hari = 'Kamis';
			break;
		case '5':
			$hari = 'Jum\'at';
			break;
		case '6':
			$hari = 'Sabtu';
			break;
		case '7':
			$hari = 'Minggu';
			break;
	}
	return $hari;
}

function namabulan($bln)
{
	switch ($bln) {
		case '1':
			$bulan = 'Januari';
			break;
		case '2':
			$bulan = 'Februari';
			break;
		case '3':
			$bulan = 'Maret';
			break;
		case '4':
			$bulan = 'April';
			break;
		case '5':
			$bulan = 'Mei';
			break;
		case '6':
			$bulan = 'Juni';
			break;
		case '7':
			$bulan = 'Juli';
			break;
		case '8':
			$bulan = 'Agustus';
			break;
		case '9':
			$bulan = 'September';
			break;
		case '10':
			$bulan = 'Oktober';
			break;
		case '11':
			$bulan = 'November';
			break;
		case '12':
			$bulan = 'Desember';
			break;
	}
	return $bulan;
}

function bulan($bln)
{
	switch ($bln) {
		case '1':
			$bulan = 'JAN';
			break;
		case '2':
			$bulan = 'FEB';
			break;
		case '3':
			$bulan = 'MAR';
			break;
		case '4':
			$bulan = 'APR';
			break;
		case '5':
			$bulan = 'MEI';
			break;
		case '6':
			$bulan = 'JUN';
			break;
		case '7':
			$bulan = 'JUL';
			break;
		case '8':
			$bulan = 'AGU';
			break;
		case '9':
			$bulan = 'SEP';
			break;
		case '10':
			$bulan = 'OKT';
			break;
		case '11':
			$bulan = 'NOV';
			break;
		case '12':
			$bulan = 'DES';
			break;
	}
	return $bulan;
}

function format_long_date($tgl)
{
	$tanggal = explode('-', substr($tgl, 0, 10));
	$jam = substr($tgl, 11, 8);
	$dd = $tanggal[2];
	$mm = getBulan($tanggal[1]);
	$yy = $tanggal[0];

	return $dd . ' ' . $mm . ' ' . $yy;	//.' '.$jam;
}

function format_long_date_time($tgl)
{
	$tanggal = explode('-', substr($tgl, 0, 10));
	$jam = substr($tgl, 11, 8);
	$dd = $tanggal[2];
	$mm = getBulan($tanggal[1]);
	$yy = $tanggal[0];

	return $dd . ' ' . $mm . ' ' . $yy . ' ' . $jam;
}

function getBulan($bln)
{
	switch ($bln) {
		case 1:
			return "Januari";
			break;
		case 2:
			return "Februari";
			break;
		case 3:
			return "Maret";
			break;
		case 4:
			return "April";
			break;
		case 5:
			return "Mei";
			break;
		case 6:
			return "Juni";
			break;
		case 7:
			return "Juli";
			break;
		case 8:
			return "Agustus";
			break;
		case 9:
			return "September";
			break;
		case 10:
			return "Oktober";
			break;
		case 11:
			return "November";
			break;
		case 12:
			return "Desember";
			break;
	}
}

/*fungsi terbilang*/
function bilang($x)
{
	$x = abs($x);
	$angka = array(
		"",
		"satu",
		"dua",
		"tiga",
		"empat",
		"lima",
		"enam",
		"tujuh",
		"delapan",
		"sembilan",
		"sepuluh",
		"sebelas"
	);

	$result = "";

	if ($x < 12) {
		$result = " " . $angka[$x];
	} else if ($x < 20) {
		$result = bilang($x - 10) . " belas";
	} else if ($x < 100) {
		$result = bilang($x / 10) . " puluh" . bilang($x % 10);
	} else if ($x < 200) {
		$result = " seratus" . bilang($x - 100);
	} else if ($x < 1000) {
		$result = bilang($x / 100) . " ratus" . bilang($x % 100);
	} else if ($x < 2000) {
		$result = " seribu" . bilang($x - 1000);
	} else if ($x < 1000000) {
		$result = bilang($x / 1000) . " ribu" . bilang($x % 1000);
	} else if ($x < 1000000000) {
		$result = bilang($x / 1000000) . " juta" . bilang($x % 1000000);
	} else if ($x < 1000000000000) {
		$result = bilang($x / 1000000000) . " milyar" . bilang(fmod($x, 1000000000));
	} else if ($x < 1000000000000000) {
		$result = bilang($x / 1000000000000) . " trilyun" . bilang(fmod($x, 1000000000000));
	}
	return $result;
}

function terbilang($x, $style = 4)
{
	if ($x < 0) {
		$hasil = "minus " . trim(bilang($x));
	} else {
		$hasil = trim(bilang($x));
	}

	switch ($style) {
		case 1:
			$hasil = strtoupper($hasil);
			break;
		case 2:
			$hasil = strtolower($hasil);
			break;
		case 3:
			$hasil = ucwords($hasil);
			break;
		default:
			$hasil = ucfirst($hasil);
			break;
	}
	return $hasil;
}

//Konversi tanggal
function tgl_sql($date)
{
	$exp = explode('-', $date);
	if (count($exp) == 3) {
		$date = $exp[2] . '-' . $exp[1] . '-' . $exp[0];
	}
	return $date;
}

function tgl_str($date)
{
	$exp = explode('-', $date);
	if (count($exp) == 3) {
		$date = $exp[2] . '-' . $exp[1] . '-' . $exp[0];
	}
	return $date;
}

function tgl_jam($date)
{
	$exp = explode(' ', $date);
	$tgl = explode('-', $exp[0]);
	$jam = $exp[1];
	return $tgl[2] . '-' . $tgl[1] . '-' . $tgl[0] . ' ' . $jam;
}

function tgl_Nojam($date)
{
	$exp = explode(' ', $date);
	$tgl = explode('-', $exp[0]);
	$jam = $exp[1];
	return $tgl[2] . '-' . $tgl[1] . '-' . $tgl[0];
}
