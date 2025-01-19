<?php
namespace App\Http\Middleware;

use App\Dosen;
use App\KompreDosen;
use App\Mahasiswa;
use Auth;
use Closure;

class GenerateMenus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        $level = !empty(Auth::user()->level->level) ? strtolower(Auth::user()->level->level) : null;
        $mhs = @Mahasiswa::where('nim', Auth::user()->username)->first();
        $status_mhs = @strtolower($mhs->status->nama);
        $prodi_id = @strtolower(Auth::user()->prodi->id);

        if ($level == 'superadmin') {
            \Menu::create('navbar', function ($menu) {
                $menu->url('home', 'Dashboard', ['icon' => 'fa fa-dashboard']);
                $menu->dropdown('Master', function ($master) {
                    $master->dropdown('Pengguna', function ($pengguna) {
                        $pengguna->url('penggunalevel', 'Level', ['icon' => 'fa fa-th-list']);
                        $pengguna->url('pengguna', 'Pengguna', ['icon' => 'fa fa-th-list']);
                    }, ['icon' => 'fa fa-user']);
                }, ['icon' => 'fa fa-user']);
            });
        }

        // Menu / Modul Untuk Superadmin atau Admin
        if (($level == 'superadmin') or ($level == 'admin')) {
            \Menu::create('navbar', function ($menu) {
                $menu->url('home', 'Dashboard', ['icon' => 'fa fa-dashboard']);
                $menu->dropdown('Master', function ($master) {
                    $master->dropdown('Pengguna', function ($pengguna) {
                        $pengguna->url('penggunalevel', 'Level', ['icon' => 'fa fa-circle-o']);
                        $pengguna->url('pengguna', 'Pengguna', ['icon' => 'fa fa-circle-o']);
                    }, ['icon' => 'fa fa-user']);

                    $master->dropdown('Referensi', function ($ref) {
                        $ref->url('ruangkelas', 'Ruang Kelas', ['icon' => 'fa fa-circle-o']);
                        $ref->url('jamkuliah', 'Jam Kuliah', ['icon' => 'fa fa-circle-o']);
                        $ref->url('kelompok', 'Kelompok', ['icon' => 'fa fa-circle-o']);
                        $ref->url('kelas', 'Kelas', ['icon' => 'fa fa-circle-o']);
                        $ref->url('statusmhs', 'Status Mahasiswa', ['icon' => 'fa fa-circle-o']);
                        $ref->url('statusdosen', 'Status Dosen', ['icon' => 'fa fa-circle-o']);
                        $ref->url('jabatan', 'Jabatan', ['icon' => 'fa fa-circle-o']);
                    }, ['icon' => 'fa fa-cogs']);

                    $master->dropdown('Setting', function ($setting) {
                        $setting->url('pt', 'Perguruan Tinggi', ['icon' => 'fa fa-circle-o']);
                        $setting->url('thakademik', 'Tahun Akademik', ['icon' => 'fa fa-circle-o']);
                        $setting->url('formschadule', 'Form Schadule', ['icon' => 'fa fa-circle-o']);
                        $setting->url('komponennilai', 'Komponen Nilai', ['icon' => 'fa fa-circle-o']);
                        $setting->url('bobotnilai', 'Bobot Nilai', ['icon' => 'fa fa-circle-o']);
                        $setting->url('pejabat', 'Pejabat', ['icon' => 'fa fa-circle-o']);
                    }, ['icon' => 'fa fa-cogs']);

                    $master->url('prodi', 'Program Studi', ['icon' => 'fa fa-th-list']);
                    $master->url('matakuliah', 'Mata Kuliah', ['icon' => 'fa fa-th-list']);
                    $master->url('dosen', 'Dosen', ['icon' => 'fa fa-th-list']);
                    $master->url('mahasiswa', 'Mahasiswa', ['icon' => 'fa fa-th-list']);
                }, ['icon' => 'fa fa-cogs']);

                $menu->dropdown('Keuangan', function ($keuangan) {
                    $keuangan->url('keuangantagihan', 'Tagihan', ['icon' => 'fa fa-circle-o']);
                    $keuangan->url('keuanganpembayaran', 'Pembayaran', ['icon' => 'fa fa-circle-o']);
                    $keuangan->url('keuangandispensasi', 'Dispensasi', ['icon' => 'fa fa-circle-o']);
                    $keuangan->url('keuanganpiutang', 'Piutang', ['icon' => 'fa fa-circle-o']);
                    // $keuangan->url('keuanganvalidasiwisuda', 'Validasi Wisuda',['icon' => 'fa fa-money']);
                    // $keuangan->dropdown('Laporan', function ($lapkeuangan) {
                    //   $lapkeuangan->url('lappembayaran', 'Pembayaran',['icon' => 'fa fa-print']);
                    //   $lapkeuangan->url('lappiutang', 'Piutang',['icon' => 'fa fa-print']);
                    // },['icon' => 'fa fa-print']);
                }, ['icon' => 'fa fa-money']);

                $menu->dropdown('Transaksi', function ($transaksi) {
                    $transaksi->url('info', 'Informasi', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('perwalian', 'Perwalian', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('kurikulum', 'Kurikulum', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('jadwalkuliah', 'Jadwal Kuliah', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('absensidosen', 'Absensi Dosen', ['icon' => 'fa fa-circle-o']);

                    $transaksi->url('rps', 'RPS', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('krs', 'KRS', ['icon' => 'fa fa-circle-o']);
                    $transaksi->dropdown('Catatan KRS', function ($krs) {
                        $krs->url('catatanKrs/belumKrs', 'Belum KRS', ['icon' => 'fa fa-circle-o']);
                        $krs->url('catatanKrs/sudahKrs', 'Sudah KRS', ['icon' => 'fa fa-circle-o']);
                        $krs->url('catatanKrs/harusKrs', 'Harus KRS', ['icon' => 'fa fa-circle-o']);
                    }, ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('nilai', 'Nilai', ['icon' => 'fa fa-circle-o']);

                    $transaksi->dropdown('Kompre', function ($skripsi) {
                        $skripsi->url('kompre_dosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                        $skripsi->url('kompre_mahasiswa', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    }, ['icon' => 'fa fa-circle-o']);

                    $transaksi->url('skripsi', 'Skripsi', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('mutasimhs', 'Mutasi Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('transkrip', 'Transkrip', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('yudisium', 'Yudisium', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-laptop']);

                $menu->dropdown('Kuesioner', function ($kuesioner) {
                    $kuesioner->url('kuesionerpertanyaan', 'Pertanyaan', ['icon' => 'fa fa-circle-o']);
                    $kuesioner->url('kuesionerhasil', 'Hasil', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-envelope']);

                $menu->dropdown('Laporan', function ($laporan) {
                    $laporan->url('lapmahasiswa', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapdosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapnilaidosen', 'Nilai Dosen', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapmatakuliah', 'Mata Kuliah', ['icon' => 'fa fa-circle-o']);

                    $laporan->url('lapperwalian', 'Perwalian', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapjadwalkuliah', 'Jadwal Kuliah', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapkurikulum', 'Kurikulum', ['icon' => 'fa fa-circle-o']);

                    $laporan->url('lapkrs', 'KRS', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapabsensi', 'Absensi', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapnilai', 'Nilai', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapkhs', 'KHS', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('laptranskrip', 'Transkrip', ['icon' => 'fa fa-circle-o']);

                    $laporan->url('laprekapnilai', 'Rekap Nilai', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapmutasimhs', 'Mutasi Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapskripsi', 'Skripsi', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapyudisium', 'Yudisium', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('laplampiransk', 'Lampiran SK', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-print']);

                $menu->dropdown('Import Data', function ($importdata) {
                    $importdata->url('importdatadosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                    $importdata->url('importdatamahasiswa', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $importdata->url('importdatamatakuliah', 'Mata Kuliah', ['icon' => 'fa fa-circle-o']);
                    $importdata->url('importdatanilai', 'Nilai', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-upload']);

                $menu->dropdown('Export Data', function ($exportdata) {
                    $exportdata->url('exportdatamahasiswa', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $exportdata->url('exportdatamatakuliah', 'Mata Kuliah', ['icon' => 'fa fa-circle-o']);
                    $exportdata->url('exportdatakurikulum', 'Kurikulum', ['icon' => 'fa fa-circle-o']);
                    $exportdata->url('exportdatakelas', 'Kelas', ['icon' => 'fa fa-circle-o']);
                    $exportdata->url('exportdatanilai', 'Nilai', ['icon' => 'fa fa-circle-o']);
                    $exportdata->url('exportdatakrs', 'KRS', ['icon' => 'fa fa-circle-o']);
                    $exportdata->url('exportdatadosenmengajar', 'Dosen Mengajar', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-download']);

                // $menu->dropdown('Export Data', function ($importdata) {
                //   $importdata->url('importdatamatakuliah', 'Mata Kuliah',['icon' => 'fa fa-upload']);
                //   $importdata->url('importdatadosen', 'Dosen',['icon' => 'fa fa-upload']);
                //   $importdata->url('importdatamhs', 'Mahasiswa',['icon' => 'fa fa-upload']);
                //   $importdata->url('importdatanilai', 'Nilai',['icon' => 'fa fa-upload']);
                // },['icon' => 'fa fa-upload']);

                $menu->dropdown('Grafik', function ($grafik) {
                    $grafik->url('grafikmhs', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikdosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikmhsaktif', 'Mahasiswa Aktif', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikkrs', 'KRS', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikyudisium', 'Yudisium', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-signal']);

                $menu->url('timeline', 'Alur Data', ['icon' => 'fa fa-bars']);
            });
        }

        // Menu / Modul Untuk BAAK
        elseif ($level == 'baak') {
            \Menu::create('navbar', function ($menu) {
                $menu->url('home', 'Dashboard', ['icon' => 'fa fa-dashboard']);
                $menu->dropdown('Master', function ($master) {
                    $master->dropdown('Pengguna', function ($pengguna) {
                        $pengguna->url('penggunalevel', 'Level', ['icon' => 'fa fa-circle-o']);
                        $pengguna->url('pengguna', 'Pengguna', ['icon' => 'fa fa-circle-o']);
                    }, ['icon' => 'fa fa-user']);

                    $master->dropdown('Referensi', function ($ref) {
                        $ref->url('ruangkelas', 'Ruang Kelas', ['icon' => 'fa fa-circle-o']);
                        $ref->url('jamkuliah', 'Jam Kuliah', ['icon' => 'fa fa-circle-o']);
                        $ref->url('kelompok', 'Kelompok', ['icon' => 'fa fa-circle-o']);
                        $ref->url('kelas', 'Kelas', ['icon' => 'fa fa-circle-o']);
                        $ref->url('statusmhs', 'Status Mahasiswa', ['icon' => 'fa fa-circle-o']);
                        $ref->url('statusdosen', 'Status Dosen', ['icon' => 'fa fa-circle-o']);
                        $ref->url('jabatan', 'Jabatan', ['icon' => 'fa fa-circle-o']);
                    }, ['icon' => 'fa fa-cogs']);

                    $master->dropdown('Setting', function ($setting) {
                        $setting->url('pt', 'Perguruan Tinggi', ['icon' => 'fa fa-circle-o']);
                        $setting->url('thakademik', 'Tahun Akademik', ['icon' => 'fa fa-circle-o']);
                        $setting->url('formschadule', 'Form Schadule', ['icon' => 'fa fa-circle-o']);
                        $setting->url('komponennilai', 'Komponen Nilai', ['icon' => 'fa fa-circle-o']);
                        $setting->url('bobotnilai', 'Bobot Nilai', ['icon' => 'fa fa-circle-o']);
                        $setting->url('pejabat', 'Pejabat', ['icon' => 'fa fa-circle-o']);
                    }, ['icon' => 'fa fa-cogs']);

                    $master->url('prodi', 'Program Studi', ['icon' => 'fa fa-circle-o']);
                    $master->url('matakuliah', 'Mata Kuliah', ['icon' => 'fa fa-circle-o']);
                    $master->url('dosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                    $master->url('mahasiswa', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-cogs']);

                $menu->dropdown('Transaksi', function ($transaksi) {
                    $transaksi->url('info', 'Informasi', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('perwalian', 'Perwalian', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('kurikulum', 'Kurikulum', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('jadwalkuliah', 'Jadwal Kuliah', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('absensidosen', 'Absensi Dosen', ['icon' => 'fa fa-circle-o']);

                    $transaksi->url('rps', 'RPS', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('krs', 'KRS', ['icon' => 'fa fa-circle-o']);
                    $transaksi->dropdown('Catatan KRS', function ($krs) {
                        $krs->url('catatanKrs/belumKrs', 'Belum KRS', ['icon' => 'fa fa-circle-o']);
                        $krs->url('catatanKrs/sudahKrs', 'Sudah KRS', ['icon' => 'fa fa-circle-o']);
                        $krs->url('catatanKrs/harusKrs', 'Harus KRS', ['icon' => 'fa fa-circle-o']);
                    }, ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('nilai', 'Nilai', ['icon' => 'fa fa-circle-o']);

                    $transaksi->dropdown('Kompre', function ($kompre) {
                        $kompre->url('kompre_dosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                        $kompre->url('kompre_mahasiswa', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    }, ['icon' => 'fa fa-circle-o']);

                    $transaksi->url('skripsi', 'Skripsi', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('mutasimhs', 'Mutasi Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('transkrip', 'Transkrip', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('yudisium', 'Yudisium', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-laptop']);

                $menu->dropdown('Kuesioner', function ($kuesioner) {
                    $kuesioner->url('kuesionerpertanyaan', 'Pertanyaan', ['icon' => 'fa fa-circle-o']);
                    $kuesioner->url('kuesionerhasil', 'Hasil', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-envelope']);

                $menu->dropdown('Laporan', function ($laporan) {
                    $laporan->url('lapmahasiswa', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapdosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapnilaidosen', 'Nilai Dosen', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapmatakuliah', 'Mata Kuliah', ['icon' => 'fa fa-circle-o']);

                    $laporan->url('lapperwalian', 'Perwalian', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapjadwalkuliah', 'Jadwal Kuliah', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapkurikulum', 'Kurikulum', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapkrs', 'KRS', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapabsensi', 'Absensi', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapnilai', 'Nilai', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapkhs', 'KHS', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('laptranskrip', 'Transkrip', ['icon' => 'fa fa-circle-o']);

                    $laporan->url('laprekapnilai', 'Rekap Nilai', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapmutasimhs', 'Mutasi Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapskripsi', 'Skripsi', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapyudisium', 'Yudisium', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-print']);

                $menu->dropdown('Import Data', function ($importdata) {
                    $importdata->url('importdatadosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                    $importdata->url('importdatamahasiswa', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $importdata->url('importdatamatakuliah', 'Mata Kuliah', ['icon' => 'fa fa-circle-o']);
                    //$importdata->url('importdatajadwalkuliah', 'Jadwal Kuliah',['icon' => 'fa fa-circle-o']);
                    //$importdata->url('importdatakrs', 'KRS',['icon' => 'fa fa-circle-o']);
                    $importdata->url('importdatanilai', 'Nilai', ['icon' => 'fa fa-circle-o']);
                    //$importdata->url('importdatapembayaran', 'Pembayaran',['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-upload']);

                // $menu->dropdown('Export Data', function ($importdata){
                //   $importdata->url('importdatamatakuliah', 'Mata Kuliah',['icon' => 'fa fa-upload']);
                //   $importdata->url('importdatadosen', 'Dosen',['icon' => 'fa fa-upload']);
                //   $importdata->url('importdatamhs', 'Mahasiswa',['icon' => 'fa fa-upload']);
                //   $importdata->url('importdatanilai', 'Nilai',['icon' => 'fa fa-upload']);
                // },['icon' => 'fa fa-upload']);

                $menu->dropdown('Grafik', function ($grafik) {
                    $grafik->url('grafikmhs', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikdosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikmhsaktif', 'Mahasiswa Aktif', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikkrs', 'KRS', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikyudisium', 'Yudisium', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-signal']);

                $menu->url('timeline', 'Alur Data', ['icon' => 'fa fa-bars']);
            });
        }

        // Menu / Modul Untuk BAAK
        elseif ($level == 'baak (hanya lihat)') {
            \Menu::create('navbar', function ($menu) {
                $menu->url('home', 'Dashboard', ['icon' => 'fa fa-dashboard']);
                $menu->dropdown('Master', function ($master) {
                    $master->dropdown('Pengguna', function ($pengguna) {
                        $pengguna->url('penggunalevel', 'Level', ['icon' => 'fa fa-circle-o']);
                        $pengguna->url('pengguna', 'Pengguna', ['icon' => 'fa fa-circle-o']);
                    }, ['icon' => 'fa fa-user']);

                    $master->dropdown('Referensi', function ($ref) {
                        $ref->url('ruangkelas', 'Ruang Kelas', ['icon' => 'fa fa-circle-o']);
                        $ref->url('jamkuliah', 'Jam Kuliah', ['icon' => 'fa fa-circle-o']);
                        $ref->url('kelompok', 'Kelompok', ['icon' => 'fa fa-circle-o']);
                        $ref->url('kelas', 'Kelas', ['icon' => 'fa fa-circle-o']);
                        $ref->url('statusmhs', 'Status Mahasiswa', ['icon' => 'fa fa-circle-o']);
                        $ref->url('statusdosen', 'Status Dosen', ['icon' => 'fa fa-circle-o']);
                        $ref->url('jabatan', 'Jabatan', ['icon' => 'fa fa-circle-o']);
                    }, ['icon' => 'fa fa-cogs']);

                    $master->dropdown('Setting', function ($setting) {
                        $setting->url('pt', 'Perguruan Tinggi', ['icon' => 'fa fa-circle-o']);
                        $setting->url('thakademik', 'Tahun Akademik', ['icon' => 'fa fa-circle-o']);
                        $setting->url('formschadule', 'Form Schadule', ['icon' => 'fa fa-circle-o']);
                        $setting->url('komponennilai', 'Komponen Nilai', ['icon' => 'fa fa-circle-o']);
                        $setting->url('bobotnilai', 'Bobot Nilai', ['icon' => 'fa fa-circle-o']);
                        $setting->url('pejabat', 'Pejabat', ['icon' => 'fa fa-circle-o']);
                    }, ['icon' => 'fa fa-cogs']);

                    $master->url('prodi', 'Program Studi', ['icon' => 'fa fa-circle-o']);
                    $master->url('matakuliah', 'Mata Kuliah', ['icon' => 'fa fa-circle-o']);
                    $master->url('dosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                    $master->url('mahasiswa', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-cogs']);

                $menu->dropdown('Transaksi', function ($transaksi) {
                    $transaksi->url('info', 'Informasi', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('perwalian', 'Perwalian', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('kurikulum', 'Kurikulum', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('jadwalkuliah', 'Jadwal Kuliah', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('absensidosen', 'Absensi Dosen', ['icon' => 'fa fa-circle-o']);

                    $transaksi->url('rps', 'RPS', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('krs', 'KRS', ['icon' => 'fa fa-circle-o']);
                    $transaksi->dropdown('Catatan KRS', function ($krs) {
                        $krs->url('catatanKrs/belumKrs', 'Belum KRS', ['icon' => 'fa fa-circle-o']);
                        $krs->url('catatanKrs/sudahKrs', 'Sudah KRS', ['icon' => 'fa fa-circle-o']);
                        $krs->url('catatanKrs/harusKrs', 'Harus KRS', ['icon' => 'fa fa-circle-o']);
                    }, ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('nilai', 'Nilai', ['icon' => 'fa fa-circle-o']);

                    $transaksi->dropdown('Kompre', function ($kompre) {
                        $kompre->url('kompre_dosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                        $kompre->url('kompre_mahasiswa', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    }, ['icon' => 'fa fa-circle-o']);
                
                    $transaksi->url('skripsi', 'Skripsi', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('mutasimhs', 'Mutasi Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('transkrip', 'Transkrip', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('yudisium', 'Yudisium', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-laptop']);

                $menu->dropdown('Kuesioner', function ($kuesioner) {
                    $kuesioner->url('kuesionerpertanyaan', 'Pertanyaan', ['icon' => 'fa fa-circle-o']);
                    $kuesioner->url('kuesionerhasil', 'Hasil', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-envelope']);

                $menu->dropdown('Laporan', function ($laporan) {
                    $laporan->url('lapmahasiswa', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapdosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapnilaidosen', 'Nilai Dosen', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapmatakuliah', 'Mata Kuliah', ['icon' => 'fa fa-circle-o']);

                    $laporan->url('lapperwalian', 'Perwalian', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapjadwalkuliah', 'Jadwal Kuliah', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapkurikulum', 'Kurikulum', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapkrs', 'KRS', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapabsensi', 'Absensi', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapnilai', 'Nilai', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapkhs', 'KHS', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('laptranskrip', 'Transkrip', ['icon' => 'fa fa-circle-o']);

                    $laporan->url('laprekapnilai', 'Rekap Nilai', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapmutasimhs', 'Mutasi Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapskripsi', 'Skripsi', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapyudisium', 'Yudisium', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-print']);

                // $menu->dropdown('Export Data', function ($importdata){
                //   $importdata->url('importdatamatakuliah', 'Mata Kuliah',['icon' => 'fa fa-upload']);
                //   $importdata->url('importdatadosen', 'Dosen',['icon' => 'fa fa-upload']);
                //   $importdata->url('importdatamhs', 'Mahasiswa',['icon' => 'fa fa-upload']);
                //   $importdata->url('importdatanilai', 'Nilai',['icon' => 'fa fa-upload']);
                // },['icon' => 'fa fa-upload']);

                $menu->dropdown('Grafik', function ($grafik) {
                    $grafik->url('grafikmhs', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikdosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikmhsaktif', 'Mahasiswa Aktif', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikkrs', 'KRS', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikyudisium', 'Yudisium', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-signal']);

                $menu->url('timeline', 'Alur Data', ['icon' => 'fa fa-bars']);
            });
        }

        // Menu / Modul Untuk Staff
        elseif ($level == 'staf') {
            \Menu::create('navbar', function ($menu) use ($prodi_id) {
                $menu->url('home', 'Dashboard', ['icon' => 'fa fa-dashboard']);
                $menu->dropdown('Master', function ($master) use ($prodi_id) {
                    if (!$prodi_id) {
                        $master->dropdown('Referensi', function ($ref) {
                            $ref->url('ruangkelas', 'Ruang Kelas', ['icon' => 'fa fa-circle-o']);
                            $ref->url('jamkuliah', 'Jam Kuliah', ['icon' => 'fa fa-circle-o']);
                            $ref->url('kelompok', 'Kelompok', ['icon' => 'fa fa-circle-o']);
                            $ref->url('kelas', 'Kelas', ['icon' => 'fa fa-circle-o']);
                            $ref->url('statusmhs', 'Status Mahasiswa', ['icon' => 'fa fa-circle-o']);
                            $ref->url('statusdosen', 'Status Dosen', ['icon' => 'fa fa-circle-o']);
                            $ref->url('jabatan', 'Jabatan', ['icon' => 'fa fa-circle-o']);
                        }, ['icon' => 'fa fa-cogs']);

                        $master->dropdown('Setting', function ($setting) {
                            $setting->url('pt', 'Perguruan Tinggi', ['icon' => 'fa fa-circle-o']);
                            $setting->url('thakademik', 'Tahun Akademik', ['icon' => 'fa fa-circle-o']);
                            $setting->url('formschadule', 'Form Schadule', ['icon' => 'fa fa-circle-o']);
                            $setting->url('komponennilai', 'Komponen Nilai', ['icon' => 'fa fa-circle-o']);
                            $setting->url('bobotnilai', 'Bobot Nilai', ['icon' => 'fa fa-circle-o']);
                            $setting->url('pejabat', 'Pejabat', ['icon' => 'fa fa-circle-o']);
                        }, ['icon' => 'fa fa-cogs']);
                        $master->url('prodi', 'Program Studi', ['icon' => 'fa fa-th-list']);
                    }
                    $master->url('matakuliah', 'Mata Kuliah', ['icon' => 'fa fa-circle-o']);
                    $master->url('dosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                    $master->url('mahasiswa', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-cogs']);

                $menu->dropdown('Transaksi', function ($transaksi) {
                    $transaksi->url('info', 'Informasi', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('perwalian', 'Perwalian', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('kurikulum', 'Kurikulum', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('jadwalkuliah', 'Jadwal Kuliah', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('absensidosen', 'Absensi Dosen', ['icon' => 'fa fa-circle-o']);

                    $transaksi->url('rps', 'RPS', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('krs', 'KRS', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('nilai', 'Nilai', ['icon' => 'fa fa-circle-o']);
                    $transaksi->dropdown('Kompre', function ($kompre) {
                        $kompre->url('kompre_mahasiswa', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    }, ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('skripsi', 'Skripsi', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('mutasimhs', 'Mutasi Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('yudisium', 'Yudisium', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-laptop']);

                $menu->dropdown('Kuesioner', function ($kuesioner) {
                    $kuesioner->url('kuesionerpertanyaan', 'Pertanyaan', ['icon' => 'fa fa-circle-o']);
                    $kuesioner->url('kuesionerhasil', 'Hasil', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-envelope']);

                $menu->dropdown('Laporan', function ($laporan) {
                    $laporan->url('lapmahasiswa', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapdosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapnilaidosen', 'Nilai Dosen', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapmatakuliah', 'Mata Kuliah', ['icon' => 'fa fa-circle-o']);

                    $laporan->url('lapperwalian', 'Perwalian', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapjadwalkuliah', 'Jadwal Kuliah', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapkurikulum', 'Kurikulum', ['icon' => 'fa fa-circle-o']);

                    $laporan->url('lapkrs', 'KRS', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapabsensi', 'Absensi', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapnilai', 'Nilai', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapkhs', 'KHS', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('laptranskrip', 'Transkrip', ['icon' => 'fa fa-circle-o']);

                    $laporan->url('laprekapnilai', 'Rekap Nilai', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapmutasimhs', 'Mutasi Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapyudisium', 'Yudisium', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-print']);

                $menu->dropdown('Import Data', function ($importdata) {
                    $importdata->url('importdatamatakuliah', 'Mata Kuliah', ['icon' => 'fa fa-circle-o']);
                    $importdata->url('importdatadosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                    $importdata->url('importdatamahasiswa', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $importdata->url('importdatanilai', 'Nilai', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-upload']);

                // $menu->dropdown('Export Data', function ($importdata){
                //   $importdata->url('importdatamatakuliah', 'Mata Kuliah',['icon' => 'fa fa-upload']);
                //   $importdata->url('importdatadosen', 'Dosen',['icon' => 'fa fa-upload']);
                //   $importdata->url('importdatamhs', 'Mahasiswa',['icon' => 'fa fa-upload']);
                //   $importdata->url('importdatanilai', 'Nilai',['icon' => 'fa fa-upload']);
                // },['icon' => 'fa fa-upload']);

                $menu->dropdown('Grafik', function ($grafik) {
                    $grafik->url('grafikmhs', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikdosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikmhsaktif', 'Mahasiswa Aktif', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikkrs', 'KRS', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikyudisium', 'Yudisium', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikkuesioner', 'Kuesioner', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-signal']);
            });
        }

        // Menu / Modul Untuk Mahasiswa
        elseif ($level == 'mahasiswa') {
            \Menu::create('navbar', function ($menu) use ($status_mhs) {
                $menu->url('home', 'Home', ['icon' => 'fa fa-home']);
                $menu->url('mhs_info', 'Informasi', ['icon' => 'fa fa-list-alt']);

                if ($status_mhs == 'aktif' || $status_mhs == "mutasi") {
                    // $menu->url('mhs_surat_aktif', 'Keterangan Aktif', ['icon' => 'fa fa-envelope-o']);
                    $menu->url('mhs_krs', 'Kartu Rencana Studi', ['icon' => 'fa fa-edit']);
                    $menu->url('mhs_jadwal', 'Jadwal', ['icon' => 'fa fa-calendar']);
                    $menu->url('mhs_kuesioner_dosen', 'Kuesioner Dosen', ['icon' => 'fa fa-check-square']);
                    $menu->url('mhs_khs', 'Kartu Hasil Studi', ['icon' => 'fa fa-book']);
                    $menu->url('mhs_komprehensif', 'Kompre', ['icon' => 'fa fa-laptop']);
                    $menu->url('mhs_skripsi', 'Skripsi', ['icon' => 'fa fa-file']);
                    $menu->url('mhs_transkrip', 'Transkrip Nilai', ['icon' => 'fa fa-star']);
                    $menu->url('mhs_wisuda', 'Wisuda', ['icon' => 'fa fa-gavel']);
                }
            });
        }

        // Menu / Modul Untuk Dosen
        elseif ($level == 'dosen') {
            \Menu::create('navbar', function ($menu) use ($status_mhs) {
                $menu->url('home', 'Home', ['icon' => 'fa fa-home']);
                $menu->url('dosen_info', 'Informasi', ['icon' => 'fa fa-list-alt']);
                $menu->url('dosen_jadwal', 'Jadwal', ['icon' => 'fa fa-calendar']);
                $menu->url('dosen_rps', 'RPS', ['icon' => 'fa fa-envelope']);
                $menu->url('dosen_nilai', 'Nilai', ['icon' => 'fa fa-book']);

                $dosen = Dosen::where('kode', Auth::user()->username)->first();
                if ($dosen) {
                    $kompreDosen = KompreDosen::where('dosen_id', $dosen->id)->first();
                    if ($kompreDosen) {
                        $menu->url('dosen_komprehensif', 'Kompre', ['icon' => 'fa fa-laptop']);
                    }
                }
                $menu->url('dosen_skripsi', 'Skripsi', ['icon' => 'fa fa-file']);
                $menu->url('dosen_kuesioner', 'Kuesioner', ['icon' => 'fa fa-check-square']);
            });
        }

        // Menu / Modul Untuk Pimpinan
        elseif ($level == 'pimpinan') {
            \Menu::create('navbar', function ($menu) use ($status_mhs) {
                $menu->url('home', 'Home', ['icon' => 'fa fa-home']);
                $menu->dropdown('Keuangan', function ($keuangan) {
                    $keuangan->url('keuanganpembayaran', 'Pembayaran', ['icon' => 'fa fa-circle-o']);
                    $keuangan->url('keuangandispensasi', 'Dispensasi', ['icon' => 'fa fa-circle-o']);
                    $keuangan->url('keuanganpiutang', 'Piutang', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-money']);

                $menu->dropdown('Kuesioner', function ($kuesioner) {
                    $kuesioner->url('kuesionerhasil', 'Hasil', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-envelope']);

                $menu->dropdown('Laporan', function ($laporan) {
                    $laporan->url('lapmahasiswa', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapdosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapnilaidosen', 'Nilai Dosen', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapmatakuliah', 'Mata Kuliah', ['icon' => 'fa fa-circle-o']);

                    $laporan->url('lapperwalian', 'Perwalian', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapjadwalkuliah', 'Jadwal Kuliah', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapkurikulum', 'Kurikulum', ['icon' => 'fa fa-circle-o']);

                    $laporan->url('lapkrs', 'KRS', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapabsensi', 'Absensi', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapnilai', 'Nilai', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapkhs', 'KHS', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('laptranskrip', 'Transkrip', ['icon' => 'fa fa-circle-o']);

                    $laporan->url('laprekapnilai', 'Rekap Nilai', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapmutasimhs', 'Mutasi Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapyudisium', 'Yudisium', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-print']);

                $menu->dropdown('Grafik', function ($grafik) {
                    $grafik->url('grafikmhs', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikdosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikmhsaktif', 'Mahasiswa Aktif', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikkrs', 'KRS', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikyudisium', 'Yudisium', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-signal']);
            });
        }

        // Menu / Modul Untuk BProdi
        elseif ($level == 'prodi') {
            \Menu::create('navbar', function ($menu) use ($status_mhs) {
                $menu->url('home', 'Home', ['icon' => 'fa fa-home']);
                // $menu->dropdown('Keuangan', function ($keuangan) {
                //   $keuangan->url('keuanganpembayaran', 'Pembayaran',['icon' => 'fa fa-money']);
                //   $keuangan->url('keuangandispensasi', 'Dispensasi',['icon' => 'fa fa-money']);
                //   $keuangan->url('keuanganpiutang', 'Piutang',['icon' => 'fa fa-money']);
                // },['icon' => 'fa fa-money']);

                $menu->dropdown('Transaksi', function ($transaksi) {
                    $transaksi->url('nilai', 'Nilai', ['icon' => 'fa fa-circle-o']);
                    $transaksi->url('absensidosen', 'Absensi Dosen', ['icon' => 'fa fa-circle-o']);
                    $transaksi->dropdown('Skripsi', function ($skripsi) {
                        $skripsi->url('skripsi_pengajuan', 'Pengajuan', ['icon' => 'fa fa-circle-o']);
                        $skripsi->url('skripsi_acc', 'Acc', ['icon' => 'fa fa-circle-o']);
                    }, ['icon' => 'fa fa-laptop']);
                }, ['icon' => 'fa fa-laptop']);

                $menu->dropdown('Kuesioner', function ($kuesioner) {
                    $kuesioner->url('kuesionerhasil', 'Hasil', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-envelope']);

                $menu->dropdown('Laporan', function ($laporan) {
                    $laporan->url('lapmahasiswa', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapdosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapnilaidosen', 'Nilai Dosen', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapmatakuliah', 'Mata Kuliah', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapperwalian', 'Perwalian', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapjadwalkuliah', 'Jadwal Kuliah', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapkurikulum', 'Kurikulum', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapkrs', 'KRS', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapabsensi', 'Absensi', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapnilai', 'Nilai', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapkhs', 'KHS', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('laptranskrip', 'Transkrip', ['icon' => 'fa fa-circle-o']);

                    $laporan->url('laprekapnilai', 'Rekap Nilai', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapmutasimhs', 'Mutasi Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapyudisium', 'Yudisium', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-print']);

                $menu->dropdown('Grafik', function ($grafik) {
                    $grafik->url('grafikdosen', 'Dosen', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikmhs', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikmhsaktif', 'Mahasiswa Aktif', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikkrs', 'KRS', ['icon' => 'fa fa-circle-o']);
                    $grafik->url('grafikyudisium', 'Yudisium', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-signal']);
            });
        }

        // Menu / Modul Untuk Keuangana
        elseif ($level == 'keuangan') {
            \Menu::create('navbar', function ($menu) use ($status_mhs) {
                $menu->url('home', 'Home', ['icon' => 'fa fa-home']);
                $menu->dropdown('Keuangan', function ($keuangan) {
                    $keuangan->url('keuangantagihan', 'Tagihan', ['icon' => 'fa fa-circle-o']);
                    $keuangan->url('keuanganpembayaran', 'Pembayaran', ['icon' => 'fa fa-circle-o']);
                    $keuangan->url('keuangandispensasi', 'Dispensasi', ['icon' => 'fa fa-circle-o']);
                    $keuangan->url('keuanganpiutang', 'Piutang', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-money']);

                $menu->dropdown('Laporan', function ($laporan) {
                    $laporan->url('lapmahasiswa', 'Mahasiswa', ['icon' => 'fa fa-circle-o']);
                    $laporan->url('lapyudisium', 'Yudisium', ['icon' => 'fa fa-circle-o']);
                }, ['icon' => 'fa fa-print']);
            });
        } else {
            \Menu::create('navbar', function ($menu) {
                $menu->url('home', 'Dashboard', ['icon' => 'fa fa-dashboard']);
            });
        }
        return $next($request);
    }
}