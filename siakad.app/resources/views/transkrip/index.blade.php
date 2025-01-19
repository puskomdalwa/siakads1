@extends('layouts.app')
@section('title', $title)

@section('content')
    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">Filter @yield('title')</span>
        </div>

        @include($folder . '.filter')
        <div class="panel-footer text-center">
            <button type="button" class="btn btn-primary" name="btnRefresh" id="btnRefresh">
                <i class="fa fa-refresh"></i> Refresh </button>
        </div>
    </div>

    <div class="panel panel-success panel-dark">
        <div class="panel-heading">
            <span class="panel-title">Data @yield('title')</span>
        </div>

        <div class="table-responsive">
            <table id="serversideTable" class="table table-hover">
                <div id="table-loader" class="table-loader"></div>
                <thead>
                    <tr>
                        <th class="text-center col-md-2">Tanggal</th>
                        <th class="text-center col-md-1">Th Akademik</th>
                        <th class="text-center col-md-1">NIM</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">L/P</th>
                        <th class="text-center">Prodi</th>
                        <th class="text-center">SMT</th>
                        <th class="text-center col-md-2">Status</th>
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
            // paging: false,
            search: {
                return: true,
            },
            ajax: {
                url: "{{ url($redirect) }}" + '/getData',
                data: function(d) {
                    d.prodi_id = $("#prodi_id").val();
                },
                beforeSend: function() {
                    addTableLoader('#table-loader');
                },
                complete: function() {
                    deleteTableLoader('#table-loader');
                }
            },
            columns: [{
                    data: 'created_at',
                    name: 'created_at',
                    'class': 'text-center'
                },
                {
                    data: 'thakademik',
                    name: 'thakademik',
                    'class': 'text-center'
                },
                {
                    data: 'nim',
                    name: 'nim',
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
                    data: 'smt',
                    name: 'smt',
                    'class': 'text-center'
                },
                {
                    data: 'status',
                    name: 'status',
                    'class': 'text-center'
                },
            ],
            "order": [
                [0, "desc"]
            ]
        });

        $("#prodi_id").on('change', function() {
            dataTable.draw();
        });

        $("#btnRefresh").click(function() {
            dataTable.draw();
        });

        function simpan(id) {
            var status = $("#btnStatus" + id).val();
            var dt = {
                id: id,
                status: status,
                _token: "{{ csrf_token() }}"
            };
            // alert(status);
            $.ajax({
                data: dt,
                url: "{{ url($redirect) }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    Swal.fire(data.title, data.info, data.status);
                },
                error: function(data) {
                    Swal.fire('ERROR', 'Silahkan hubungi Administrator', 'error');
                }
            });
        }
    </script>
@endpush
