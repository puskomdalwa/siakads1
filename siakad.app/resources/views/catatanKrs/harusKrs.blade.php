@extends('layouts.app')
@section('title', $title)

@section('content')
    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">Filter @yield('title')</span>
        </div>

        @include($folder . '.filter')
    </div>

    <div class="panel panel-success panel-dark">

        <div class="panel-heading">
            <span class="panel-title">Data @yield('title')</span>
        </div>

        <div class="table-responsive">
            <table id="serversideTable" class="table table-hover table-bordered">
                <div id="table-loader-1" class="table-loader"></div>
                <thead>
                    <tr>
                        <th class="text-center col-md-1">NIM</th>
                        <th class="text-center col-md-4">NAMA MAHASISWA</th>
                        <th class="text-center col-md-1">PRODI</th>
                        <th class="text-center col-md-1">KELAS</th>
                        <th class="text-center col-md-1">SEMESTER</th>
                        <th class="text-center col-md-1">STATUS</th>
                        <th class="text-center col-md-1">KETERANGAN</th>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="panel-body">
            <div id="preload" class="text-center" style="display:none">
                <img src="{{ asset('img/load.gif') }}" alt="">
            </div>
            <div id="detail"></div>
        </div>

    </div>

    <div class="panel panel-success panel-dark">

        <div class="panel-heading">
            <span class="panel-title">Rekap @yield('title')</span>

        </div>

        <div class="table-responsive">
            <table id="serversideTableRekap" class="table table-hover table-bordered">
                <div id="table-loader-2" class="table-loader"></div>
                <thead>
                    <tr>
                        <th class="text-center col-md-4">SEMESTER</th>
                        <th class="text-center col-md-1">JUMLAH MHS</th>
                        <th class="text-center col-md-1">MAHASISWA AKTIF</th>
                        <th class="text-center col-md-1">SUDAH KRS</th>
                        <th class="text-center col-md-1">BELUM KRS</th>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="panel-body">
            <div id="preload" class="text-center" style="display:none">
                <img src="{{ asset('img/load.gif') }}" alt="">
            </div>
            <div id="detail"></div>
        </div>

    </div>
@endsection


@push('demo')
    <script>
        init.push(function() {
            $('#c-tooltips-demo a').tooltip();
        });
    </script>
@endpush

@push('scripts')
    <script type="text/javascript">
        var dataTable = $("#serversideTable").DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('catatanKrs.harusKrs.getData') }}",
                data: function(d) {
                    d.prodi_id = $("#prodi_id").val();
                    d.kelas_id = $("#kelas_id").val();
                    d.th_akademik_id = $("#th_akademik_id").val();
                    d.th_angkatan_id = $("#th_angkatan_id").val();
                    d.jk_id = $("#jk_id").val();
                },
                beforeSend: function() {
                    addTableLoader('#table-loader-1');
                },
                complete: function() {
                    deleteTableLoader('#table-loader-1');
                }
            },
            columns: [{
                    data: 'mhs_nim',
                    name: 'mhs_nim',
                    'class': 'text-center'
                },
                {
                    data: 'mhs_nama',
                    name: 'mhs_nama'
                },
                {
                    data: 'prodi_nama',
                    name: 'prodi_nama',
                    'class': 'text-center'
                },
                {
                    data: 'kelas_nama',
                    name: 'kelas_nama',
                    'class': 'text-center'
                },
                {
                    data: 'mhs_semester',
                    name: 'mhs_semester',
                    'class': 'text-center'
                },
                {
                    data: 'mhs_status',
                    name: 'mhs_status',
                    'class': 'text-center'
                },
                {
                    data: 'mhs_keterangan',
                    name: 'mhs_keterangan',
                    'class': 'text-center'
                }
            ],
            "order": [
                [0, "desc"]
            ]
        });

        var dataTableRekap = $("#serversideTableRekap").DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('catatanKrs.harusKrs.getDataRekap') }}",
                data: function(d) {
                    d.prodi_id = $("#prodi_id").val();
                    d.kelas_id = $("#kelas_id").val();
                    d.th_akademik_id = $("#th_akademik_id").val();
                    d.th_angkatan_id = $("#th_angkatan_id").val();
                    d.jk_id = $("#jk_id").val();
                },
                beforeSend: function() {
                    addTableLoader('#table-loader-2');
                },
                complete: function() {
                    deleteTableLoader('#table-loader-2');
                }
            },
            columns: [{
                    data: 'semester',
                    name: 'semester',
                    'class': 'text-center',
                    'orderable': false,
                    'searchable': false,
                    'class': 'text-center'
                },
                {
                    data: 'jumlahMhs',
                    name: 'jumlahMhs',
                    'class': 'text-center',
                    'orderable': false,
                    'searchable': false,
                    'class': 'text-center'
                },
                {
                    data: 'jumlahMhsAktif',
                    name: 'jumlahMhsAktif',
                    'class': 'text-center',
                    'orderable': false,
                    'searchable': false,
                    'class': 'text-center'
                },
                {
                    data: 'jumlahSudahKrs',
                    name: 'jumlahSudahKrs',
                    'class': 'text-center',
                    'orderable': false,
                    'searchable': false,
                    'class': 'text-center'
                },
                {
                    data: 'jumlahBelumKrs',
                    name: 'jumlahBelumKrs',
                    'class': 'text-center',
                    'orderable': false,
                    'searchable': false,
                    'class': 'text-center'
                }
            ],
            order: [
                [0, "desc"]
            ],
        });

        $("#filter").on('click', function() {
            if (!$("#th_akademik_id").val()) {
                swal('Peringatan..!!', 'Silahkan Tahun Akademik', 'warning');
                $("#th_akademik_id").focus();
                return false;
            }

            if (!$("#prodi_id").val()) {
                swal('Peringatan..!!', 'Silahkan Pilih Program Studi', 'warning');
                $("#prodi_id").focus();
                return false;
            }

            dataTable.draw();
            dataTableRekap.draw();
        });

        $("#excel").on('click', function() {
            if (!$("#th_akademik_id").val()) {
                swal('Peringatan..!!', 'Silahkan Tahun Akademik', 'warning');
                $("#th_akademik_id").focus();
                return false;
            }

            if (!$("#prodi_id").val()) {
                swal('Peringatan..!!', 'Silahkan Pilih Program Studi', 'warning');
                $("#prodi_id").focus();
                return false;
            }

            var kelasId = $('#kelas_id').val();
            var prodiId = $('#prodi_id').val();
            var thAkademikId = $('#th_akademik_id').val();
            var jkId = $('#jk_id').val();
            window.open(
                `{{ route('catatanKrs') }}/harusKrs/rekapExcel/${thAkademikId}/${kelasId}/${prodiId}/${jkId}`,
                '_blank');
        });
    </script>
@endpush
