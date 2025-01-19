<!-- ----------------------- -->
<!-- Tampilan Awal dashboard -->
<!-- ----------------------- -->

<div class="row">
    <div class="col-sm-3">
        <div class="stat-panel">
            <!-- <div class="stat-cell bg-danger valign-middle"> -->
            <div class="stat-cell bg-success valign-middle">
                <i class="fa fa-calendar-o bg-icon"></i>
                <span class="text-xlg"><strong>{{ App\ThAkademik::get()->count() }}</strong></span><br>
                <a href="{{ url('thakademik') }}"><span class="text-bg">Tahun Akademik</span></a>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="stat-panel">
            <div class="stat-cell bg-success valign-middle">
                <i class="fa fa-check-circle-o bg-icon"></i>
                <span class="text-xlg"><strong>{{ App\Prodi::get()->count() }}</strong></span><br>
                <a href="{{ url('prodi') }}"><span class="text-bg">Program Studi</span></a>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="stat-panel">
            <div class="stat-cell bg-success valign-middle">
                <i class="fa fa-user bg-icon"></i>
                <span class="text-xlg"><strong>{{ App\Dosen::get()->count() }}</strong></span><br>
                <a href="{{ url('dosen') }}"><span class="text-bg">Dosen</span></a>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="stat-panel">
            <div class="stat-cell bg-success valign-middle">
                <i class="fa fa-book bg-icon"></i>
                <span class="text-xlg"><strong>{{ App\MataKuliah::get()->count() }}</strong></span><br>
                <a href="{{ url('matakuliah') }}"><span class="text-bg">Matakuliah</span></a>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="stat-panel">
            <div class="stat-cell bg-info valign-middle">
                <i class="fa fa-bars bg-icon"></i>
                <span class="text-xlg"><strong>{{ App\Kurikulum::get()->count() }}</strong></span><br>
                <a href="{{ url('kurikulum') }}"><span class="text-bg">Kurikulum</span></a>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="stat-panel">
            <div class="stat-cell bg-info valign-middle">
                <i class="fa fa-check-square-o bg-icon"></i>
                <span class="text-xlg">
                    <strong>{{ App\KRS::where('th_akademik_id', $th_akademik_aktif->id)->get()->count() }}
                    </strong></span><br>
                <a href="{{ url('krs') }}"><span class="text-bg">KRS</span></a>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="stat-panel">
            <div class="stat-cell bg-info valign-middle">
                <i class="fa fa-calendar bg-icon"></i>
                <span class="text-xlg">
                    <strong>{{ App\JadwalKuliah::where('th_akademik_id', $th_akademik_aktif->id)->get()->count() }}
                    </strong></span><br>
                <a href="{{ url('jadwalkuliah') }}"><span class="text-bg">Jadwal</span></a>
            </div>
        </div>
    </div>

    @if ($level == 'admin')
        <div class="col-sm-3">
            <div class="stat-panel">
                <div class="stat-cell bg-info valign-middle">
                    <i class="fa fa-money bg-icon"></i>
                    <span class="text-xlg">
                        <strong>{{ App\KeuanganPembayaran::where('th_akademik_id', $th_akademik_aktif->id)->get()->count() }}
                        </strong></span><br>
                    <a href="{{ url('krs') }}"><span class="text-bg">Keuangan</span></a>
                </div>
            </div>
        </div>
    @endif

    @if ($level == 'baak')
        <div class="col-sm-3">
            <div class="stat-panel">
                <div class="stat-cell bg-implode valign-middle">
                    <i class="fa fa-money bg-icon"></i>
                    <span class="text-xlg">
                        <strong>{{ App\KeuanganPembayaran::where('th_akademik_id', $th_akademik_aktif->id)->get()->count() }}
                        </strong></span><br>
                    <span class="text-bg">Keuangan</span>
                </div>
            </div>
        </div>
    @endif

    <div class="col-sm-3">
        <div class="stat-panel">
            <div class="stat-cell bg-danger valign-middle">
                <i class="fa fa-group bg-icon"></i>
                <span class="text-xlg">
                    <strong>{{ App\Mahasiswa::where('status_id', 18)->get()->count() }}</strong></span><br>
                <a href="{{ url('mahasiswa') }}"><span class="text-bg">Mahasiswa Aktif</span></a>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="stat-panel">
            <div class="stat-cell bg-danger valign-middle">
                <i class="fa fa-group bg-icon"></i>
                <span class="text-xlg">
                    <strong>{{ App\Mahasiswa::where('status_id', 20)->get()->count() }}</strong></span><br>
                <a href="{{ url('mahasiswa') }}"><span class="text-bg">Mahasiswa Non Aktif</span></a>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="stat-panel">
            <div class="stat-cell bg-danger valign-middle">
                <i class="fa fa-group bg-icon"></i>
                <span class="text-xlg">
                    <strong>{{ App\Mahasiswa::where('status_id', 19)->get()->count() }}</strong></span><br>
                <a href="{{ url('mahasiswa') }}"><span class="text-bg">Mahasiswa Cuti</span></a>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="stat-panel">
            <div class="stat-cell bg-danger valign-middle">
                <i class="fa fa-group bg-icon"></i>
                <span class="text-xlg">
                    <strong>{{ App\Mahasiswa::where('status_id', 22)->get()->count() }}</strong></span><br>
                <a href="{{ url('mahasiswa') }}"><span class="text-bg">Mahasiswa Keluar</span></a>
            </div>
        </div>
    </div>
</div>
