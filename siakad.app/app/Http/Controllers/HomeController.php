<?php
namespace App\Http\Controllers;

use App\Dosen;
use App\Http\Services\ServiceIntro;
use App\JadwalKuliah;
use App\Mahasiswa;
use App\ThAkademik;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $picture = Auth::user()->picture;
        $level = strtolower(Auth::user()->level->level);

        $th_akademik_aktif = ThAkademik::Aktif()->first();
        $statusIntro = ServiceIntro::check();

        if ($level == 'mahasiswa') {
            $redirect = 'mahasiswa';
            $brosur = 'idn_pembayaran';
            $title = 'Profile Mahasiswa';

            $nim = Auth::user()->username;
            $data = Mahasiswa::where('nim', $nim)->first();

            // $list_thakademik = ThAkademik::where('id','!=',$th_akademik_aktif->id)
            // ->orderBy('kode','DESC')
            // ->get();

            $list_thakademik = ThAkademik::orderBy('kode', 'DESC')
                ->get();

            return view('home',
                compact('th_akademik_aktif', 'level', 'data', 'title',
                    'picture', 'list_thakademik', 'redirect', 'brosur')
            );
        } elseif ($level == 'dosen') {
            $redirect = 'dosen';
            $title = 'Profile Dosen';

            $kode = Auth::user()->username;
            $data = Dosen::where('kode', $kode)->first();

            $list_thakademik = ThAkademik::where('id', '!=',
                $th_akademik_aktif->id)->orderBy('kode', 'DESC')->get();
            
            $th_akademik = ThAkademik::Aktif()->first();
            $hari = $this->getHari();
            // $jam = date("H.00");
            $jadwal = JadwalKuliah::join('ref as ref_hari', 'ref_hari.id', '=', 'trans_jadwal_kuliah.hari_id')
                ->join('ref as ref_jam', 'ref_jam.id', '=', 'trans_jadwal_kuliah.jam_kuliah_id')
                ->select('trans_jadwal_kuliah.*')
            // ->addSelect(\DB::raw("SUBSTRING(ref_jam.nama, 1, 5) as mulai"))
            // ->addSelect(\DB::raw("SUBSTRING(ref_jam.nama, 7, 11) as selesai"))
            // ->havingRaw("mulai <= $jam")
            // ->havingRaw("selesai >= $jam")
                ->where('th_akademik_id', $th_akademik->id)
                ->where('dosen_id', $data->id)
                ->where('ref_hari.nama', $hari)
                ->with('kurikulum_matakuliah', 'jamkul')
                ->orderBy('hari_id', 'asc')
                ->orderBy('jam_kuliah_id', 'asc')
                ->get();

            return view('home',
            compact('th_akademik_aktif', 'level',
                'data', 'title', 'picture', 'list_thakademik', 'redirect', 'jadwal', 'statusIntro')
            );
        } else {
            return view('home',
                compact('th_akademik_aktif', 'level', 'picture')
            );
        }
    }

    private function getHari()
    {
        $hari = date("D");

        switch ($hari) {
            case 'Sun':
                $hari = "Ahad";
                break;

            case 'Mon':
                $hari = "Senin";
                break;

            case 'Tue':
                $hari = "Selasa";
                break;

            case 'Wed':
                $hari = "Rabu";
                break;

            case 'Thu':
                $hari = "Kamis";
                break;

            case 'Fri':
                $hari = "Jum'at";
                break;

            case 'Sat':
                $hari = "Sabtu";
                break;

            default:
                $hari = "Tidak di ketahui";
                break;
        }
        return $hari;
    }
}