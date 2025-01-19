@extends('layouts.app')
@section('title', $title)
@section('content')

    <div class="timeline centered">
        <!-- Timeline header -->
        <div class="tl-header now bg-primary"> {{ $th_akademik->nama . ' ' . $th_akademik->semester }} </div>

        <div class="tl-entry">
            <div class="tl-time">
                Langkah 1
            </div>
            <div class="tl-icon {{ $jml_mhsbaru < 1 ? 'bg-danger' : 'bg-success' }}"><i class="fa fa-users"></i></div>
            <div class="panel tl-body {{ $jml_mhsbaru < 1 ? 'bg-danger' : '' }}">
                <h4 class="text">Mahasiswa</h4>
                Jumlah {{ $jml_mhsbaru }} Data.<br />
                Jumlah Mahasiswa Aktif {{ $jml_mhsaktif }} Data. <br />
                Jumlah Mahasiswa Non Aktif {{ $jml_mhspasif }} Data.
            </div>
        </div>

        <div class="tl-entry">
            <div class="tl-time">
                Langkah 2
            </div>
            <div class="tl-icon {{ $jml_perwalian < 1 ? 'bg-danger' : 'bg-success' }}"><i class="fa fa-users"></i></div>
            <div class="panel tl-body {{ $jml_perwalian < 1 ? 'bg-danger' : '' }}">
                <h4 class="text">Perwalian Mahasiswa</h4>
                Jumlah {{ $jml_perwalian }} Data.
            </div>
        </div>

        <div class="tl-entry left">
            <div class="tl-time">
                Langkah 3
            </div>
            <div class="tl-icon {{ $jml_tagihan < 1 ? 'bg-danger' : 'bg-info' }}"><i class="fa fa-dollar"></i></div>
            <div class="panel tl-body {{ $jml_tagihan < 1 ? 'bg-danger' : '' }}">
                <h4 class="text-info">Tagihan</h4>
                Jumlah {{ $jml_tagihan }} Data.
            </div>
        </div>

        <div class="tl-entry left">
            <div class="tl-time">
                Langkah 4
            </div>
            <div class="tl-icon {{ $jml_pembayaran < 1 ? 'bg-danger' : 'bg-info' }}"> <i class="fa fa-dollar"></i> </div>
            <div class="panel tl-body {{ $jml_pembayaran < 1 ? 'bg-danger' : '' }}">
                <h4 class="text-info">Pembayaran Mahasiswa</h4>
                Jumlah {{ $jml_pembayaran }} Data.
                <h4 class="text-info">Dispensasi Mahasiswa</h4>
                Jumlah {{ $jml_dispensasi }} Data.
            </div>
        </div>

        <div class="tl-entry">
            <div class="tl-time">
                Langkah 5
            </div>
            <div class="tl-icon {{ $jml_jadwal < 1 ? 'bg-danger' : 'bg-warning' }}"><i class="fa fa-retweet"></i></div>
            <div class="panel tl-body {{ $jml_jadwal < 1 ? 'bg-danger' : '' }}">
                <h4 class="text-warning">Jadwal Kuliah</h4>
                Jumlah {{ $jml_jadwal }} Data.
            </div>
        </div>

        <div class="tl-entry">
            <div class="tl-time">
                Langkah 6
            </div>
            <div class="tl-icon {{ $jml_krs < 1 ? 'bg-danger' : 'bg-warning' }}"><i class="fa fa-retweet"></i></div>
            <div class="panel tl-body {{ $jml_krs < 1 ? 'bg-danger' : '' }}">
                <h4 class="text-warning">KRS Mahasiswa</h4>
                Jumlah {{ $jml_krs }} Data.
            </div>
        </div>

        <div class="tl-entry">
            <div class="tl-time">
                Langkah 7
            </div>
            <div class="tl-icon {{ $jml_acc_krs < 1 ? 'bg-danger' : 'bg-warning' }}"><i class="fa fa-retweet"></i></div>
            <div class="panel tl-body {{ $jml_acc_krs < 1 ? 'bg-danger' : '' }}">
                <h4 class="text-warning">ACC KRS Mahasiswa</h4>
                Jumlah {{ $jml_acc_krs }} Data.
            </div>
        </div>

        <div class="tl-entry">
            <div class="tl-time">
                Langkah 8
            </div>
            <div class="tl-icon {{ $jml_khs < 1 ? 'bg-danger' : 'bg-warning' }}"><i class="fa fa-retweet"></i></div>
            <div class="panel tl-body {{ $jml_khs < 1 ? 'bg-danger' : '' }}">
                <h4 class="text-warning">Nilai Mahasiswa</h4>
                Jumlah {{ $jml_khs }} Data.
            </div>
        </div>

        <div class="tl-entry left">
            <div class="tl-time">
                Langkah 9
            </div>
            <div class="tl-icon {{ $jml_mutasi < 1 ? 'bg-danger' : 'bg-dark-gray' }}"><i class="fa fa-check"></i></div>
            <div class="panel tl-body {{ $jml_mutasi < 1 ? 'bg-danger' : '' }}">
                <h4 class="text">Mutasi Mahasiswa</h4>
                Jumlah {{ $jml_mutasi }} Data.
            </div>
        </div>

        <div class="tl-entry left">
            <div class="tl-time">
                Langkah 10
            </div>
            <div class="tl-icon {{ $jml_wisuda < 1 ? 'bg-danger' : 'bg-dark-gray' }}"><i class="fa fa-check"></i></div>
            <div class="panel tl-body {{ $jml_wisuda < 1 ? 'bg-danger' : '' }}">
                <h4 class="text">Wisuda Mahasiswa</h4>
                Jumlah {{ $jml_wisuda }} Data.
            </div>
        </div>



    </div>

@endsection

@push('css')
    <link href="{{ asset('assets/stylesheets/pages.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@push('demo')
    <script>
        init.push(function() {
            $('#timeline-centered').switcher();
            $('#timeline-centered').on($('html').hasClass('ie8') ? "propertychange" : "change", function() {
                var turn_on = $(this).is(':checked');
                if (turn_on) {
                    $('.timeline').addClass('centered');
                } else {
                    $('.timeline').removeClass('centered');
                }
            });
        });
    </script>
@endpush

@push('scripts')
    <script type="text/javascript"></script>
@endpush
