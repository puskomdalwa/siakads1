<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', 'RootController@index')->name('root');

Auth::routes(['register' => false]);
Auth::routes();

Route::group(['middleware' => ['web', 'auth', 'roles']], function () {

    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('userprofile', 'UserProfileController');
    Route::resource('editpassword', 'EditPasswordController');

    Route::group(['roles' => ['superadmin']], function () {
        Route::get('session', 'SessionController@index');
        Route::post('pencarian', 'PencarianController@index')->name('pencarian');

        Route::get('penggunalevel/getData', 'site_admin\PenggunaLevelController@getData')->name('penggunalevel.getData');
        Route::resource('penggunalevel', 'site_admin\PenggunaLevelController');

        Route::get('pengguna/getData', 'site_admin\PenggunaController@getData')->name('pengguna.getData');
        Route::resource('pengguna', 'site_admin\PenggunaController');
    });

    Route::group(['roles' => ['admin', 'baak', 'baak (hanya lihat)']], function () {
        Route::get('session', 'SessionController@index');
        Route::post('pencarian', 'PencarianController@index')->name('pencarian');

        Route::get('penggunalevel/getData', 'site_admin\PenggunaLevelController@getData')->name('penggunalevel.getData');
        Route::resource('penggunalevel', 'site_admin\PenggunaLevelController');

        Route::get('pengguna/getData', 'site_admin\PenggunaController@getData')->name('pengguna.getData');
        Route::resource('pengguna', 'site_admin\PenggunaController');

        Route::get('pt/getData', 'site_admin\PTController@getData')->name('pt.getData');
        Route::resource('pt', 'site_admin\PTController');

        Route::resource('timeline', 'site_admin\TimelineController');
        Route::resource('exportfeeder', 'ExportfeederController');

        Route::get('thakademik/getData', 'site_admin\ThAkademikController@getData')->name('thakademik.getData');
        Route::post('thakademik/setNonAktif', 'site_admin\ThAkademikController@setNonAktif')->name('thakademik.setNonAktif');
        Route::resource('thakademik', 'site_admin\ThAkademikController');

        Route::get('formschadule/getData', 'site_admin\FormSchaduleController@getData')->name('formschadule.getData');
        Route::resource('formschadule', 'site_admin\FormSchaduleController');

        Route::get('komponennilai/getData', 'site_admin\KomponenNilaiController@getData')->name('komponennilai.getData');
        Route::resource('komponennilai', 'site_admin\KomponenNilaiController');

        Route::get('bobotnilai/getData', 'site_admin\BobotNilaiController@getData')->name('bobotnilai.getData');
        Route::resource('bobotnilai', 'site_admin\BobotNilaiController');

        Route::get('pejabat/getData', 'site_admin\PejabatController@getData')->name('pejabat.getData');
        Route::resource('pejabat', 'site_admin\PejabatController');

        Route::resource('laprekapnilai', 'site_admin\LapRekapNilaiController');

        Route::prefix('kompre_dosen')->group(function () {
            Route::get('/', 'site_admin\KomprehensifDosenController@index')->name('kompre_dosen');
            Route::post('/update', 'site_admin\KomprehensifDosenController@update')->name('kompre_dosen.update');
            Route::delete('/', 'site_admin\KomprehensifDosenController@delete')->name('kompre_dosen.delete');
        });
    });

    Route::group(['roles' => ['admin', 'baak', 'baak (hanya lihat)', 'staf', 'prodi']], function () {
        Route::get('ruangkelas/getData', 'site_admin\RuangKelasController@getData')->name('ruangkelas.getData');
        Route::resource('ruangkelas', 'site_admin\RuangKelasController');

        Route::get('jamkuliah/getData', 'site_admin\JamKuliahController@getData')->name('jamkuliah.getData');
        Route::resource('jamkuliah', 'site_admin\JamKuliahController');

        Route::get('kelompok/getData', 'site_admin\KelompokController@getData')->name('kelompok.getData');
        Route::resource('kelompok', 'site_admin\KelompokController');

        Route::get('kelas/getData', 'site_admin\KelasController@getData')->name('kelas.getData');
        Route::resource('kelas', 'site_admin\KelasController');

        Route::get('statusmhs/getData', 'site_admin\StatusMhsController@getData')->name('statusmhs.getData');
        Route::resource('statusmhs', 'site_admin\StatusMhsController');

        Route::get('statusdosen/getData', 'site_admin\StatusDosenController@getData')->name('statusdosen.getData');
        Route::resource('statusdosen', 'site_admin\StatusDosenController');

        Route::get('jabatan/getData', 'site_admin\JabatanController@getData')->name('jabatan.getData');
        Route::resource('jabatan', 'site_admin\JabatanController');

        Route::get('prodi/getData', 'site_admin\ProdiController@getData')->name('prodi.getData');
        Route::resource('prodi', 'site_admin\ProdiController');

        Route::get('matakuliah/getData', 'site_admin\MataKuliahController@getData')->name('matakuliah.getData');
        Route::resource('matakuliah', 'site_admin\MataKuliahController');

        Route::get('dosen/{id}/getResetPassword', 'site_admin\DosenController@getResetPassword')->name('dosen.getResetPassword');
        Route::get('dosen/getDataSkripsi', 'site_admin\DosenController@getDataSkripsi')->name('dosen.getDataSkripsi');
        Route::post('dosen/UpdateAccKRS', 'site_admin\DosenController@UpdateAccKRS')->name('dosen.UpdateAccKRS');
        Route::get('dosen/getDetailsData/{id}', 'site_admin\DosenController@getDetailsData')->name('dosen.getDetailsData');
        Route::get('dosen/getDataNilai', 'site_admin\DosenController@getDataNilai')->name('dosen.getDataNilai');
        Route::get('dosen/getDataMengajar', 'site_admin\DosenController@getDataMengajar')->name('dosen.getDataMengajar');
        Route::get('dosen/getDataPerwalian', 'site_admin\DosenController@getDataPerwalian')->name('dosen.getDataPerwalian');
        Route::get('dosen/getDetailsDataPerwalian/{nim}/{th_akademik_id}', 'site_admin\DosenController@getDetailsDataPerwalian')->name('dosen.getDetailsDataPerwalian');
        Route::get('dosen/getData', 'site_admin\DosenController@getData')->name('dosen.getData');
        Route::resource('dosen', 'site_admin\DosenController');

        Route::get('mahasiswa/createUsers', 'site_admin\MahasiswaController@createUsers')->name('mahasiswa.createUsers');
        Route::get('mahasiswa/{id}/getResetPassword', 'site_admin\MahasiswaController@getResetPassword')->name('mahasiswa.getResetPassword');
        Route::get('mahasiswa/getDataKeuangan', 'site_admin\MahasiswaController@getDataKeuangan')->name('mahasiswa.getDataKeuangan');
        Route::get('mahasiswa/getDataKHS', 'site_admin\MahasiswaController@getDataKHS')->name('mahasiswa.getDataKHS');
        Route::get('mahasiswa/getDataKRS', 'site_admin\MahasiswaController@getDataKRS')->name('mahasiswa.getDataKRS');
        Route::get('mahasiswa/getData', 'site_admin\MahasiswaController@getData')->name('mahasiswa.getData');
        Route::resource('mahasiswa', 'site_admin\MahasiswaController');

        Route::get('info/getData', 'site_admin\InfoController@getData')->name('info.getData');
        Route::resource('info', 'site_admin\InfoController');

        Route::delete('perwalian/{id}/deleteDetail', 'site_admin\PerwalianController@deleteDetail')->name('perwalian.deleteDetail');
        Route::get('perwalian/getDataMhs', 'site_admin\PerwalianController@getDataMhs')->name('perwalian.getDataMhs');
        Route::get('perwalian/getData', 'site_admin\PerwalianController@getData')->name('perwalian.getData');
        Route::resource('perwalian', 'site_admin\PerwalianController');

        Route::delete('kurikulum/{id}/deleteDetail', 'site_admin\KurikulumController@deleteDetail')->name('kurikulum.deleteDetail');
        Route::get('kurikulum/getDetailsData/{id}', 'site_admin\KurikulumController@getDetailsData')->name('kurikulum.getDetailsData');
        Route::get('kurikulum/getDataMK', 'site_admin\KurikulumController@getDataMK')->name('kurikulum.getDataMK');
        Route::get('kurikulum/getData', 'site_admin\KurikulumController@getData')->name('kurikulum.getData');
        Route::resource('kurikulum', 'site_admin\KurikulumController');

        Route::get('jadwalkuliah/{th}/{prodi}/{kurikulum_id}/{id}/createDetail', 'site_admin\JadwalKuliahController@createDetail')->name('jadwalkuliah.createDetail');
        Route::get('jadwalkuliah/getListKurikulum/{prodiId}/{thAkademikId}', 'site_admin\JadwalKuliahController@getListKurikulum')->name('jadwalkuliah.getListKurikulum');
        Route::get('jadwalkuliah/getDetailsData/{id}', 'site_admin\JadwalKuliahController@getDetailsData')->name('jadwalkuliah.getDetailsData');
        Route::get('jadwalkuliah/getDataMK', 'site_admin\JadwalKuliahController@getDataMK')->name('jadwalkuliah.getDataMK');
        Route::get('jadwalkuliah/getData', 'site_admin\JadwalKuliahController@getData')->name('jadwalkuliah.getData');
        Route::resource('jadwalkuliah', 'site_admin\JadwalKuliahController');

        // ****** Absensi Dosen *****
        Route::get('absensidosen/{id}/absensi', 'site_admin\AbsensiDosenController@absensi')->name('absensidosen.absensi');
        Route::get('absensidosen/getData', 'site_admin\AbsensiDosenController@getData')->name('absensidosen.getData');
        Route::resource('absensidosen', 'site_admin\AbsensiDosenController');

        Route::get('rps/getDetailsData/{id}', 'site_admin\RPSController@getDetailsData')->name('rps.getDetailsData');
        Route::get('rps/getData', 'site_admin\RPSController@getData')->name('rps.getData');
        Route::resource('rps', 'site_admin\RPSController');

        Route::get('krs/{id}/cetak', 'site_admin\KRSController@cetak')->name('krs.cetak');
        Route::get('krs/getDataMK', 'site_admin\KRSController@getDataMK')->name('krs.getDataMK');
        Route::post('krs/getMhs', 'site_admin\KRSController@getMhs')->name('krs.getMhs');
        Route::get('krs/getData', 'site_admin\KRSController@getData')->name('krs.getData');
        Route::resource('krs', 'site_admin\KRSController');

        Route::prefix('catatanKrs')->group(function () {
            Route::get('/', 'site_admin\CatatanKRSController@index')->name('catatanKrs');
            Route::get('/sudahKrs', 'site_admin\CatatanKRSController@sudahKrs')->name('catatanKrs.sudahKrs');
            Route::get('/harusKrs', 'site_admin\CatatanKRSController@harusKrs')->name('catatanKrs.harusKrs');
            Route::get('/harusKrs/getData', 'site_admin\CatatanKRSController@harusKrsGetData')->name('catatanKrs.harusKrs.getData');
            Route::get('/harusKrs/getDataRekap', 'site_admin\CatatanKRSController@harusKrsGetDataRekap')->name('catatanKrs.harusKrs.getDataRekap');
            Route::get('/belumKrs', 'site_admin\CatatanKRSController@belumKrs')->name('catatanKrs.belumKrs');
            Route::get('/belumKrs/getData', 'site_admin\CatatanKRSController@belumKrsGetData')->name('catatanKrs.belumKrs.getData');
            Route::get('/sudahKrs', 'site_admin\CatatanKRSController@sudahKrs')->name('catatanKrs.sudahKrs');
            Route::get('/sudahKrs/getData', 'site_admin\CatatanKRSController@sudahKrsGetData')->name('catatanKrs.sudahKrs.getData');
            Route::get('/harusKrs/rekapExcel/{th_akademik_id}/{kelas_id}/{prodi_id}/{jk_id}', 'site_admin\CatatanKRSController@rekapExcel')->name('catatanKrs.rekapExcel');
        });

        Route::post('nilai/getBobotNilai', 'site_admin\NilaiController@getBobotNilai')->name('nilai.getBobotNilai');
        Route::get('nilai/getData', 'site_admin\NilaiController@getData')->name('nilai.getData');
        Route::resource('nilai', 'site_admin\NilaiController');
        Route::get('nilai/{id}/getDataNilai', 'site_admin\NilaiController@getDataNilai')->name('nilai.getDataNilai');
        Route::get('nilai/{id}/getDataIsiNilai/{idKrsDetail}', 'site_admin\NilaiController@getDataIsiNilai')->name('nilai.getDataIsiNilai');
        Route::post('nilai/{id}/saveNilai', 'site_admin\NilaiController@saveNilai')->name('nilai.saveNilai');

        Route::get('mutasimhs/getData', 'site_admin\MutasiMhsController@getData')->name('mutasimhs.getData');
        Route::resource('mutasimhs', 'site_admin\MutasiMhsController');

        Route::post('yudisium/saveNomorSeriIjazah', 'site_admin\YudisiumController@saveNomorSeriIjazah')->name('yudisium.saveNomorSeriIjazah');
        Route::post('yudisium/saveSKYudisium', 'site_admin\YudisiumController@saveSKYudisium')->name('yudisium.saveSKYudisium');
        Route::post('yudisium/saveTglSKYudisium', 'site_admin\YudisiumController@saveTglSKYudisium')->name('yudisium.saveTglSKYudisium');
        Route::post('yudisium/getMhs', 'site_admin\YudisiumController@getMhs')->name('yudisium.getMhs');
        Route::post('yudisium/approve', 'site_admin\YudisiumController@approve')->name('yudisium.approve');
        Route::get('yudisium/getData', 'site_admin\YudisiumController@getData')->name('yudisium.getData');
        Route::resource('yudisium', 'site_admin\YudisiumController');

        Route::get('kuesionerpertanyaan/{id}/copyData', 'site_admin\KuesionerPertanyaanController@copyData')
            ->name('kuesionerpertanyaan.copyData');
        Route::get('kuesionerpertanyaan/getData', 'site_admin\KuesionerPertanyaanController@getData')
            ->name('kuesionerpertanyaan.getData');
        Route::resource('kuesionerpertanyaan', 'site_admin\KuesionerPertanyaanController');

        Route::post('kuesionerhasil/getDosen', 'site_admin\KuesionerHasilController@getDosen')->name('kuesionerhasil.getDosen');
        Route::get('kuesionerhasil/getData', 'site_admin\KuesionerHasilController@getData')->name('kuesionerhasil.getData');
        Route::resource('kuesionerhasil', 'site_admin\KuesionerHasilController');

        Route::resource('importdatanilai', 'site_admin\ImportDataNilaiController');
        Route::resource('importdatadosen', 'site_admin\ImportDataDosenController');
        Route::resource('importdatamahasiswa', 'site_admin\ImportDataMahasiswaController');
        Route::resource('importdatamatakuliah', 'site_admin\ImportDataMataKuliahController');
        Route::resource('importdatapembayaran', 'site_admin\ImportDataPembayaranController');

        // Tampilan Export Data
        Route::resource('exportdatamahasiswa', 'site_admin\ExportDataMahasiswaController');
        Route::resource('exportdatamatakuliah', 'site_admin\ExportDataMataKuliahController');
        Route::resource('exportdatakurikulum', 'site_admin\ExportDataKurikulumController');
        Route::resource('exportdatakelas', 'site_admin\ExportDataKelasController');
        Route::resource('exportdatanilai', 'site_admin\ExportDataNilaiController');
        Route::resource('exportdatakrs', 'site_admin\ExportDataKrsController');
        Route::resource('exportdatadosenmengajar', 'site_admin\ExportDataDosenMengajarController');

        // Export Data ke Excel
        Route::post('exportdatamahasiswa/export', 'site_admin\ExportDataMahasiswaController@export')->name('exportdatamahasiswa.export');
        Route::post('exportdatamatakuliah/export', 'site_admin\ExportDataMataKuliahController@export')->name('exportdatamatakuliah.export');
        Route::post('exportdatakurikulum/export', 'site_admin\ExportDataKurikulumController@export')->name('exportdatakurikulum.export');
        Route::post('exportdatakelas/export', 'site_admin\ExportDataKelasController@export')->name('exportdatakelas.export');
        Route::post('exportdatanilai/export', 'site_admin\ExportDataNilaiController@export')->name('exportdatanilai.export');
        Route::post('exportdatakrs/export', 'site_admin\ExportDataKrsController@export')->name('exportdatakrs.export');
        Route::post('exportdataDosenMengajar/export', 'site_admin\ExportDataDosenMengajarController@export')->name('exportdatadosenmengajar.export');

        Route::resource('laprekapnilai', 'site_admin\LapRekapNilaiController');
        Route::resource('lapperwalian', 'site_admin\LapPerwalianController');

        Route::post('lapjadwalkuliah/cetak', 'site_admin\LapJadwalKuliahController@cetak');
        Route::get('lapjadwalkuliah/excel/{prodiId}/{thAkademikId}', 'site_admin\LapJadwalKuliahController@excel');
        Route::resource('lapjadwalkuliah', 'site_admin\LapJadwalKuliahController');
        Route::resource('lapkurikulum', 'site_admin\LapKurikulumController');
        Route::resource('lapskripsi', 'site_admin\LapSkripsiController');

        Route::get('transkrip/getData', 'site_admin\TranskripController@getData')->name('transkrip.getData');
        Route::resource('transkrip', 'site_admin\TranskripController');

        Route::prefix('kompre_mahasiswa')->group(function () {
            Route::get('/', 'site_admin\KomprehensifMahasiswaController@index')->name('kompre_mahasiswa');
            Route::get('/detail/{mahasiswaId}', 'site_admin\KomprehensifMahasiswaController@detail')->name('kompre_mahasiswa.detail');
            Route::post('/detail/{mahasiswaId}', 'site_admin\KomprehensifMahasiswaController@updateNilai')->name('kompre_mahasiswa.updateNilai');
            Route::get('/getData', 'site_admin\KomprehensifMahasiswaController@getData')->name('kompre_mhs.getData');
            Route::get('/cetak/{mahasiswaId}', 'site_admin\KomprehensifMahasiswaController@cetak')->name('kompre_mahasiswa.cetak');
        });
    });

    Route::group(['roles' => ['keuangan', 'admin', 'pimpinan']], function () {
        Route::get('keuangantagihan/getData', 'site_admin\KeuanganTagihanController@getData')->name('keuangantagihan.getData');
        Route::resource('keuangantagihan', 'site_admin\KeuanganTagihanController');

        Route::get('keuanganpembayaran/{id}/cetakKwitansi', 'site_admin\KeuanganPembayaranController@cetakKwitansi')->name('keuanganpembayaran.cetakKwitansi');
        Route::post('keuanganpembayaran/cetak', 'site_admin\KeuanganPembayaranController@cetak')->name('keuanganpembayaran.cetak');
        Route::post('keuanganpembayaran/getJmlTagihan', 'site_admin\KeuanganPembayaranController@getJmlTagihan')->name('keuanganpembayaran.getJmlTagihan');
        Route::post('keuanganpembayaran/getMhs', 'site_admin\KeuanganPembayaranController@getMhs')->name('keuanganpembayaran.getMhs');
        Route::get('keuanganpembayaran/getData', 'site_admin\KeuanganPembayaranController@getData')->name('keuanganpembayaran.getData');
        Route::resource('keuanganpembayaran', 'site_admin\KeuanganPembayaranController');

        Route::post('keuangandispensasi/getMhs', 'site_admin\KeuanganDispensasiController@getMhs')->name('keuangandispensasi.getMhs');
        Route::get('keuangandispensasi/getData', 'site_admin\KeuanganDispensasiController@getData')->name('keuangandispensasi.getData');
        Route::resource('keuangandispensasi', 'site_admin\KeuanganDispensasiController');

        Route::post('keuanganpiutang/cetak', 'site_admin\KeuanganPiutangController@cetak')->name('keuanganpiutang.cetak');
        Route::post('keuanganpiutang/listTagihan', 'site_admin\KeuanganPiutangController@listTagihan')->name('keuanganpiutang.listTagihan');
        Route::get('keuanganpiutang/getData', 'site_admin\KeuanganPiutangController@getData')->name('keuanganpiutang.getData');
        Route::resource('keuanganpiutang', 'site_admin\KeuanganPiutangController');
    });

    Route::group(['roles' => ['admin', 'baak', 'baak (hanya lihat)', 'prodi', 'pimpinan']], function () {
        Route::get('skripsi/getData', 'site_admin\SkripsiController@getData')
            ->name('skripsi.getData');
        Route::get('skripsi/detail/{id}', 'site_admin\SkripsiController@detail')->name('skripsi.detail');
        Route::get('skripsi/detail/{id}/getDataJudul', 'site_admin\SkripsiController@getDataJudul')
            ->name('skripsi.getDataJudul');
        Route::post('skripsi/detail/{id}/tambahJudul', 'site_admin\SkripsiController@tambahJudul')
            ->name('skripsi.tambahJudul');
        Route::put('skripsi/detail/{id}/updateJudul', 'site_admin\SkripsiController@updateJudul')
            ->name('skripsi.updateJudul');
        Route::get('skripsi/detail/{id}/downloadProposal/{judulId}', 'site_admin\SkripsiController@downloadProposal')
            ->name('skripsi.downloadProposal');
        Route::get('skripsi/detail/{id}/downloadSkripsi/{judulId}', 'site_admin\SkripsiController@downloadSkripsi')
            ->name('skripsi.downloadSkripsi');
        Route::delete('skripsi/detail/{id}/deleteJudul/{judulId}', 'site_admin\SkripsiController@deleteJudul')
            ->name('skripsi.deleteJudul');
        Route::post('skripsi/detail/{id}/accJudul', 'site_admin\SkripsiController@accJudul')
            ->name('skripsi.accJudul');
        Route::post('skripsi/detail/{id}/updateStatusJudul', 'site_admin\SkripsiController@updateStatusJudul')
            ->name('skripsi.updateStatusJudul');
        Route::get('skripsi/detail/{id}/getDosenPembimbing', 'site_admin\SkripsiController@getDosenPembimbing')
            ->name('skripsi.getDosenPembimbing');
        Route::get('skripsi/detail/{id}/getDosenPenguji', 'site_admin\SkripsiController@getDosenPenguji')
            ->name('skripsi.getDosenPenguji');
        Route::get('skripsi/detail/{id}/deletePembimbing', 'site_admin\SkripsiController@deletePembimbing')
            ->name('skripsi.deletePembimbing');
        Route::get('skripsi/detail/{id}/bimbingan/{judulId}', 'site_admin\SkripsiController@bimbingan')
            ->name('skripsi.bimbingan');
        Route::get('skripsi/detail/{id}/bimbingan/{judulId}/getDataBimbingan', 'site_admin\SkripsiController@getDataBimbingan')
            ->name('skripsi.getDataBimbingan');
        Route::get('skripsi/detail/{id}/bimbingan/{judulId}/tambahBimbingan', 'site_admin\SkripsiController@tambahBimbingan')
            ->name('skripsi.tambahBimbingan');
        Route::post('skripsi/detail/{id}/bimbingan/{judulId}/updateBimbingan', 'site_admin\SkripsiController@updateBimbingan')
            ->name('skripsi.updateBimbingan');
        Route::post('skripsi/detail/{id}/bimbingan/{judulId}/tambahBimbingan', 'site_admin\SkripsiController@tambahBimbingan')
            ->name('skripsi.tambahBimbingan');
        Route::delete('skripsi/detail/{id}/bimbingan/{judulId}/deleteBimbingan/{idBimbingan}', 'site_admin\SkripsiController@deleteBimbingan')
            ->name('skripsi.deleteBimbingan');
        Route::put('skripsi/detail/{id}/bimbingan/{judulId}/updateStatusBimbingan', 'site_admin\SkripsiController@updateStatusBimbingan')
            ->name('skripsi.updateStatusBimbingan');
        Route::post('skripsi/detail/{id}/updateUjianProposal', 'site_admin\SkripsiController@updateUjianProposal')
            ->name('skripsi.updateUjianProposal');
        Route::post('skripsi/detail/{id}/updateStatusUjianProposal', 'site_admin\SkripsiController@updateStatusUjianProposal')
            ->name('skripsi.updateStatusUjianProposal');
        Route::get('skripsi/detail/{id}/deleteUjianProposal', 'site_admin\SkripsiController@deleteUjianProposal')
            ->name('skripsi.deleteUjianProposal');
        Route::post('skripsi/detail/{id}/updateUjianSkripsi', 'site_admin\SkripsiController@updateUjianSkripsi')
            ->name('skripsi.updateUjianSkripsi');
        Route::post('skripsi/detail/{id}/updateStatusUjianSkripsi', 'site_admin\SkripsiController@updateStatusUjianSkripsi')
            ->name('skripsi.updateStatusUjianSkripsi');
        Route::get('skripsi/detail/{id}/deleteUjianSkripsi', 'site_admin\SkripsiController@deleteUjianSkripsi')
            ->name('skripsi.deleteUjianSkripsi');
        Route::get('skripsi/detail/{id}/getDosenPengujiSkripsi', 'site_admin\SkripsiController@getDosenPengujiSkripsi')
            ->name('skripsi.getDosenPengujiSkripsi');
        Route::put('skripsi/detail/{id}/simpanNilaiSkripsi', 'site_admin\SkripsiController@simpanNilaiSkripsi')
            ->name('skripsi.simpanNilaiSkripsi');
        Route::put('skripsi/detail/{id}/kosongkanNilaiSkripsi', 'site_admin\SkripsiController@kosongkanNilaiSkripsi')
            ->name('skripsi.kosongkanNilaiSkripsi');
        Route::resource('skripsi', 'site_admin\SkripsiController');
    });

    Route::group(['roles' => ['prodi', 'pimpinan', 'admin', 'baak', 'baak (hanya lihat)', 'staf']], function () {
        Route::post('lapmahasiswa/cetak', 'site_admin\LapMahasiswaController@cetak')->name('lapmahasiswa.cetak');
        Route::get('lapmahasiswa/getListKelompok/{id}', 'site_admin\LapMahasiswaController@getListKelompok')->name('lapmahasiswa.getListKelompok');
        Route::resource('lapmahasiswa', 'site_admin\LapMahasiswaController');

        Route::post('lapdosen/cetak', 'site_admin\LapDosenController@cetak')->name('lapdosen.cetak');
        Route::resource('lapdosen', 'site_admin\LapDosenController');

        Route::post('lapnilaidosen/cetak', 'site_admin\LapNilaiDosenController@cetak')->name('lapnilaidosen.cetak');
        Route::resource('lapnilaidosen', 'site_admin\LapNilaiDosenController');

        Route::post('lapmatakuliah/cetak', 'site_admin\LapMatakuliahController@cetak')->name('lapmatakuliah.cetak');
        Route::resource('lapmatakuliah', 'site_admin\LapMatakuliahController');

        Route::post('lapkrs/krskosong', 'site_admin\LapKRSController@krskosong')->name('lapkrs.datakrskosong');
        Route::post('lapkrs/cetak', 'site_admin\LapKRSController@cetak')->name('lapkrs.cetak');
        Route::resource('lapkrs', 'site_admin\LapKRSController');

        Route::get('lapabsensi/{id}/cetak', 'site_admin\LapAbsensiController@cetak')->name('lapabsensi.cetak');
        Route::get('lapabsensi/{id}/cetakLengkap', 'site_admin\LapAbsensiController@cetakLengkap')->name('lapabsensi.cetakLengkap');
        Route::get('lapabsensi/{id}/cetakUas', 'site_admin\LapAbsensiController@cetakUas')->name('lapabsensi.cetakUas');
        Route::post('lapabsensi/cetakAll', 'site_admin\LapAbsensiController@cetakAll')->name('lapabsensi.cetakAll');
        Route::resource('lapabsensi', 'site_admin\LapAbsensiController');

        Route::get('lapnilai/{id}/cetak', 'site_admin\LapNilaiController@cetak')->name('lapnilai.cetak');
        Route::post('lapnilai/cetakAll', 'site_admin\LapNilaiController@cetakAll')->name('lapnilai.cetakAll');
        Route::resource('lapnilai', 'site_admin\LapNilaiController');

        Route::get('lapkhs/{id}/cetakKHS', 'site_admin\LapKHSController@cetakKHS')->name('lapkhs.cetakKHS');
        Route::post('lapkhs/cetak', 'site_admin\LapKHSController@cetak')->name('lapkhs.cetak');
        Route::resource('lapkhs', 'site_admin\LapKHSController');

        Route::get('lapkrs/{id}/cetakKRS', 'site_admin\LapKRSController@cetakKRS')->name('lapkhs.cetakKRS');
        Route::post('lapkrs/cetak', 'site_admin\LapKRSController@cetak')->name('lapkrs.cetak');
        Route::resource('lapkrs', 'site_admin\LapKRSController');

        // ==========================================================================================================================

        Route::get('laptranskrip/{id}/cetakTranskrip', 'site_admin\LapTranskripController@cetakTranskrip')->name('laptranskrip.cetakTranskrip');
        Route::post('laptranskrip/cetak', 'site_admin\LapTranskripController@cetak')->name('laptranskrip.cetak');
        Route::resource('laptranskrip', 'site_admin\LapTranskripController');

        // ==========================================================================================================================

        Route::post('lapmutasimhs/cetak', 'site_admin\LapMutasiMhsController@cetak')->name('lapmutasimhs.cetak');
        Route::resource('lapmutasimhs', 'site_admin\LapMutasiMhsController');

        Route::post('lapyudisium/cetak', 'site_admin\LapYudisiumController@cetak')->name('lapyudisium.cetak');
        Route::resource('lapyudisium', 'site_admin\LapYudisiumController');

        Route::resource('laplampiransk', 'site_admin\LapLampiranSKController');

        Route::get('grafikmhs/ChartBar', 'site_admin\GrafikMhsController@ChartBar');
        Route::resource('grafikmhs', 'site_admin\GrafikMhsController');

        Route::get('grafikdosen/chart', 'site_admin\GrafikDosenController@chart');
        Route::resource('grafikdosen', 'site_admin\GrafikDosenController');

        Route::get('grafikmhsaktif/chart', 'site_admin\GrafikMhsAktifController@chart');
        Route::resource('grafikmhsaktif', 'site_admin\GrafikMhsAktifController');

        Route::get('grafikkrs/chart', 'site_admin\GrafikKrsController@chart');
        Route::resource('grafikkrs', 'site_admin\GrafikKrsController');

        Route::get('grafikyudisium/chart', 'site_admin\GrafikYudisiumController@chart');
        Route::resource('grafikyudisium', 'site_admin\GrafikYudisiumController');

        Route::resource('laprekapnilai', 'site_admin\LapRekapNilaiController');
    });

    Route::group(['roles' => ['dosen', 'admin', 'baak', 'baak (hanya lihat)', 'staf', 'prodi']], function () {
        Route::resource('dosen_info', 'site_dosen\DosenInformasiController');

        Route::get('dosen/getDataSkripsi', 'site_admin\DosenController@getDataSkripsi')->name('dosen.getDataSkripsi');
        Route::post('dosen/UpdateAccKRS', 'site_admin\DosenController@UpdateAccKRS')->name('dosen.UpdateAccKRS');
        Route::post('dosen/accKrsSemua', 'site_admin\DosenController@accKrsSemua')->name('dosen.accKrsSemua');
        Route::get('dosen/getDetailsData/{id}', 'site_admin\DosenController@getDetailsData')->name('dosen.getDetailsData');
        Route::get('dosen/getDataNilai', 'site_admin\DosenController@getDataNilai')->name('dosen.getDataNilai');
        Route::get('dosen/getDataMengajar', 'site_admin\DosenController@getDataMengajar')->name('dosen.getDataMengajar');
        Route::get('dosen/getDetailsDataMengajar/{id}', 'site_admin\DosenController@getDetailsDataMengajar')->name('dosen.getDetailsDataMengajar');
        Route::get('dosen/getDataPerwalian', 'site_admin\DosenController@getDataPerwalian')->name('dosen.getDataPerwalian');
        Route::get('dosen/getDetailsDataPerwalian/{nim}/{th_akademik_id}', 'site_admin\DosenController@getDetailsDataPerwalian')->name('dosen.getDetailsDataPerwalian');
        Route::post('dosen_jadwal/simpanabsensi', 'site_dosen\DosenJadwalController@simpanabsensi')->name('dosen_jadwal.simpanabsensi');
        Route::get('dosen_jadwal/{id}/{absen_id}/absensi', 'site_dosen\DosenJadwalController@absensi')->name('dosen_jadwal.absensi');
        Route::get('dosen_jadwal/getData', 'site_dosen\DosenJadwalController@getData')->name('dosen_jadwal.getData');
        Route::resource('dosen_jadwal', 'site_dosen\DosenJadwalController');

        // rekap absensi
        Route::get('dosen_jadwal/{id}/rekapAbsensi', 'site_dosen\DosenJadwalController@rekapAbsensi')->name('dosen_jadwal.rekapAbsensi');
        Route::delete('dosen_jadwal/{absen_id}/delete-absensi', 'site_dosen\DosenJadwalController@deleteAbsensi')->name('dosen_jadwal.deleteAbsensi');

        Route::get('dosen_rps/getData', 'site_dosen\DosenRPSController@getData')->name('dosen_rps.getData');
        Route::resource('dosen_rps', 'site_dosen\DosenRPSController');

        Route::get('dosen_nilai/{id}/cetak', 'site_dosen\DosenNilaiController@cetak')->name('dosen_nilai.cetak');
        Route::post('dosen_nilai/getBobotNilai', 'site_dosen\DosenNilaiController@getBobotNilai')->name('dosen_nilai.getBobotNilai');
        Route::get('dosen_nilai/getData', 'site_dosen\DosenNilaiController@getData')->name('dosen_nilai.getData');
        Route::get('dosen_nilai/{id}/getDataNilai', 'site_dosen\DosenNilaiController@getDataNilai')->name('dosen_nilai.getDataNilai');
        Route::resource('dosen_nilai', 'site_dosen\DosenNilaiController');
        Route::get('dosen_nilai/{id}/getDataIsiNilai/{idKrsDetail}', 'site_dosen\DosenNilaiController@getDataIsiNilai')->name('dosen_nilai.getDataIsiNilai');
        Route::post('dosen_nilai/{id}/saveNilai', 'site_dosen\DosenNilaiController@saveNilai')->name('dosen_nilai.saveNilai');

        Route::get('dosen_kuesioner/getData', 'site_dosen\DosenKuesionerController@getData')->name('dosen_kuesioner.getData');
        Route::resource('dosen_kuesioner', 'site_dosen\DosenKuesionerController');

        Route::get('dosen_skripsi/getData', 'site_dosen\DosenSkripsiController@getData')->name('dosen_skripsi.getData');
        Route::get('dosen_skripsi/getDataUjianProposal', 'site_dosen\DosenSkripsiController@getDataUjianProposal')->name('dosen_skripsi.getDataUjianProposal');
        Route::get('dosen_skripsi/getDataUjianSkripsi', 'site_dosen\DosenSkripsiController@getDataUjianSkripsi')->name('dosen_skripsi.getDataUjianSkripsi');
        Route::get('dosen_skripsi/detail/{id}', 'site_dosen\DosenSkripsiController@detail')->name('dosen_skripsi.detail');
        Route::get('dosen_skripsi/detail/{id}/getDataBimbingan', 'site_dosen\DosenSkripsiController@getDataBimbingan')->name('dosen_skripsi.getDataBimbingan');
        Route::get('dosen_skripsi/detail/{id}/downloadProposal', 'site_dosen\DosenSkripsiController@downloadProposal')->name('dosen_skripsi.downloadProposal');
        Route::get('dosen_skripsi/detail/{id}/downloadSkripsi', 'site_dosen\DosenSkripsiController@downloadSkripsi')->name('dosen_skripsi.downloadSkripsi');
        Route::put('dosen_skripsi/detail/{id}/updateStatusBimbingan', 'site_dosen\DosenSkripsiController@updateStatusBimbingan')->name('dosen_skripsi.updateStatusBimbingan');
        Route::post('dosen_skripsi/detail/{id}/updateStatusUjianProposal', 'site_dosen\DosenSkripsiController@updateStatusUjianProposal')
            ->name('dosen_skripsi.updateStatusUjianProposal');
        Route::post('dosen_skripsi/detail/{id}/updateStatusUjianSkripsi', 'site_dosen\DosenSkripsiController@updateStatusUjianSkripsi')
            ->name('dosen_skripsi.updateStatusUjianSkripsi');
        Route::put('dosen_skripsi/detail/{id}/simpanNilaiSkripsi', 'site_dosen\DosenSkripsiController@simpanNilaiSkripsi')
            ->name('dosen_skripsi.simpanNilaiSkripsi');
        Route::put('dosen_skripsi/detail/{id}/kosongkanNilaiSkripsi', 'site_dosen\DosenSkripsiController@kosongkanNilaiSkripsi')
            ->name('dosen_skripsi.kosongkanNilaiSkripsi');
        Route::resource('dosen_skripsi', 'site_dosen\DosenSkripsiController');

        // Biodata Dosen
        Route::get('edit-biodata-dosen/', 'site_dosen\DosenBiodataController@editBiodata')->name('biodata.dosen.edit');
        Route::post('update-biodata-dosen/', 'site_dosen\DosenBiodataController@updateBiodata')->name('biodata.dosen.update');

        // Komprehensif
        Route::get('dosen_komprehensif', 'site_dosen\DosenKomprehensifController@index')->name('komprehensif.dosen');
        Route::get('dosen_komprehensif/getData', 'site_dosen\DosenKomprehensifController@getData')->name('komprehensif.dosen.getData');
        Route::post('dosen_komprehensif/edit', 'site_dosen\DosenKomprehensifController@edit')->name('komprehensif.dosen.edit');
        Route::delete('dosen_komprehensif/{id}', 'site_dosen\DosenKomprehensifController@destroy')->name('komprehensif.dosen.destroy');
    });

    Route::group(['roles' => ['mahasiswa', 'admin', 'baak', 'baak (hanya lihat)', 'staf']], function () {
        Route::resource('mhs_info', 'site_mhs\MhsInformasiController');
        Route::get('mahasiswa/fixKRS/{idKrsDetail}', 'site_admin\MahasiswaController@fixKRS')->name('mahasiswa.fixKRS');

        Route::get('mahasiswa/getDataKeuangan', 'site_admin\MahasiswaController@getDataKeuangan')->name('mahasiswa.getDataKeuangan');
        Route::get('mahasiswa/getDataKHS', 'site_admin\MahasiswaController@getDataKHS')->name('mahasiswa.getDataKHS');
        Route::get('mahasiswa/getDataKRS', 'site_admin\MahasiswaController@getDataKRS')->name('mahasiswa.getDataKRS');

        Route::post('mahasiswa/cetakKHS', 'site_admin\MahasiswaController@cetakKHS')->name('mahasiswa.cetakKHS');
        Route::post('mahasiswa/cetakKRS', 'site_admin\MahasiswaController@cetakKRS')->name('mahasiswa.cetakKRS');
        Route::resource('mahasiswa', 'site_admin\MahasiswaController');

        Route::get('mhs_jadwal/getDetailsData/{id}', 'site_mhs\MhsJadwalController@getDetailsData')->name('mhs_jadwal.getDetailsData');
        Route::get('mhs_jadwal/getData', 'site_mhs\MhsJadwalController@getData')->name('mhs_jadwal.getData');
        Route::resource('mhs_jadwal', 'site_mhs\MhsJadwalController');

        Route::get('mhs_kuesioner_dosen/getData', 'site_mhs\MhsKuesionerDosenController@getData')->name('mhs_kuesioner_dosen.getData');
        Route::resource('mhs_kuesioner_dosen', 'site_mhs\MhsKuesionerDosenController');
        Route::get('mhs_kuesioner_dosen/{idDosen}/{thAkademikId}/edit', 'site_mhs\MhsKuesionerDosenController@edit')->name('mhs_kuesioner_dosen.edit');

        Route::post('mhs_krs/getMhs', 'site_mhs\MhsKRSController@getMhs')->name('mhs_krs.getMhs');
        Route::get('mhs_krs/getDataMK', 'site_mhs\MhsKRSController@getDataMK')->name('mhs_krs.getDataMK');
        Route::get('mhs_krs/cetakKRS', 'site_mhs\MhsKRSController@cetakKRS')->name('mhs_krs.cetakKRS');
        Route::resource('mhs_krs', 'site_mhs\MhsKRSController');

        Route::get('mhs_khs/getData', 'site_mhs\MhsKHSController@getData')->name('mhs_khs.getData');
        Route::get('mhs_khs/cetakKHS', 'site_mhs\MhsKHSController@cetakKHS')->name('mhs_khs.cetakKHS');
        Route::resource('mhs_khs', 'site_mhs\MhsKHSController');

        Route::get('mhs_skripsi/getData', 'site_mhs\MhsSkripsiController@getData')->name('mhs_skripsi.getData');
        Route::post('mhs_skripsi/updateProposal', 'site_mhs\MhsSkripsiController@updateProposal')->name('mhs_skripsi.updateProposal');
        Route::delete('mhs_skripsi/delete/{id}', 'site_mhs\MhsSkripsiController@delete')->name('mhs_skripsi.delete');
        Route::get('mhs_skripsi/downloadProposal/{id}', 'site_mhs\MhsSkripsiController@downloadProposal')->name('mhs_skripsi.downloadProposal');
        Route::get('mhs_skripsi/detail/{id}', 'site_mhs\MhsSkripsiController@detail')->name('mhs_skripsi.detail');
        Route::get('mhs_skripsi/detail/{id}/getDataBimbingan', 'site_mhs\MhsSkripsiController@getDataBimbingan')->name('mhs_skripsi.getDataBimbingan');
        Route::post('mhs_skripsi/detail/{id}/updateBimbingan', 'site_mhs\MhsSkripsiController@updateBimbingan')->name('mhs_skripsi.updateBimbingan');
        Route::post('mhs_skripsi/detail/{id}/tambahBimbingan', 'site_mhs\MhsSkripsiController@tambahBimbingan')->name('mhs_skripsi.tambahBimbingan');
        Route::post('mhs_skripsi/detail/{id}/storeDokumenSkripsi', 'site_mhs\MhsSkripsiController@storeDokumenSkripsi')->name('mhs_skripsi.storeDokumenSkripsi');
        Route::get('mhs_skripsi/downloadSkripsi/{id}', 'site_mhs\MhsSkripsiController@downloadSkripsi')->name('mhs_skripsi.downloadSkripsi');
        Route::delete('mhs_skripsi/detail/{id}/deleteBimbingan/{idBimbingan}', 'site_mhs\MhsSkripsiController@deleteBimbingan')->name('mhs_skripsi.deleteBimbingan');
        Route::resource('mhs_skripsi', 'site_mhs\MhsSkripsiController');
        Route::resource('mhs_surat_aktif', 'site_mhs\MhsSuratAktifController');

        Route::post('mhs_transkrip/getKirim', 'site_mhs\MhsTranskripController@getKirim')->name('mhs_transkrip.getKirim');
        Route::resource('mhs_transkrip', 'site_mhs\MhsTranskripController');

        Route::get('mhs_wisuda/getData', 'site_mhs\MhsWisudaController@getData')->name('mhs_wisuda.getData');
        Route::resource('mhs_wisuda', 'site_mhs\MhsWisudaController');

        // Biodata Mhs
        Route::get('edit-biodata-mahasiswa/', 'site_mhs\MhsBiodataController@editBiodata')->name('biodata.mhs.edit');
        Route::post('update-biodata-mahasiswa/', 'site_mhs\MhsBiodataController@updateBiodata')->name('biodata.mhs.update');

        // Komprehensif
        Route::get('mhs_komprehensif', 'site_mhs\MhsKomprehensifController@index')->name('komprehensif.mhs');
        Route::get('mhs_komprehensif/getData', 'site_mhs\MhsKomprehensifController@getData')->name('komprehensif.mhs.getData');
        Route::post('mhs_komprehensif/cetak', 'site_mhs\MhsKomprehensifController@cetak')->name('komprehensif.mhs.cetak');
    });
});

Route::get('/testing', 'TestingController@index')->name('testing');
// Route::get('/testing/krs', 'TestingController@krs')->name('testing.krs');
// Route::post('/testing/krs/store', 'TestingController@krsStore')->name('testing.krsStore');