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
            <div class="panel-heading-controls">
                <a href="{{ url($redirect) }}" class="btn btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
            </div>
        </div>

        <div class="table-responsive">
            <table id="serversideTable" class="table table-hover table-bordered table-condensed">
                <div id="table-loader" class="table-loader"></div>
                <thead>
                    <tr>
                        <th class="text-center col-md-1">Tanggal<br />Daftar</th>
                        <th class="text-center col-md-1">NIM</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">L/P</th>
                        <th class="text-center">Prodi</th>
                        <th class="text-center col-md-1">Tanggal<br />Acc</th>
                        <th class="text-center">Judul Skripsi</th>
                        <th class="text-center">Pembimbing</th>
                    </tr>
                </thead>
            </table>
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
            search: {
                return: true,
            },
            ajax: {
                url: "{{ url($redirect) }}" + '/getData',
                data: function(d) {
                    d.th_akademik_id = $("#th_akademik_id").val();
                    d.prodi_id = $("#prodi_id").val();
                },
                beforeSend: function() {
                    addTableLoader('#table-loader');
                },
                complete: function() {
                    deleteTableLoader('#table-loader');
                }
            },
            paging: false,
            "searching": true,
            columns: [{
                    data: 'tgl_pengajuan',
                    name: 'tgl_pengajuan',
                    'class': 'text-center'
                },
                {
                    data: 'mhs_nim',
                    name: 'mhs_nim',
                    'class': 'text-center'
                },
                {
                    data: 'mhs_nama',
                    name: 'mhs_nama'
                },
                {
                    data: 'mhs_jk',
                    name: 'mhs_jk',
                    'class': 'text-center'
                },
                {
                    data: 'mhs_prodi',
                    name: 'mhs_prodi'
                },
                {
                    data: 'tgl_acc',
                    name: 'tgl_acc',
                    'class': 'text-center'
                },
                {
                    data: 'judul',
                    name: 'judul'
                },
                {
                    data: 'pembimbing',
                    name: 'pembimbing'
                },
            ],
            "order": [
                [0, "desc"]
            ]
        });

        $("#th_akademik_id").on('change', function() {
            dataTable.draw();
        });

        $("#prodi_id").on('change', function() {
            dataTable.draw();
        });
    </script>
@endpush
