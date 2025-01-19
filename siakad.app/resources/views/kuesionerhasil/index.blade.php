@extends('layouts.app')
@section('title', $title)
@section('content')

    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">@yield('title')</span>
        </div>

        <form class="form-horizontal form-bordered">
            @include($redirect . '.form')
        </form>

        <div class="table-responsive">
            <table id="serversideTable" class="table table-hover table-bordered">
                <div id="table-loader" class="table-loader"></div>
                <thead>
                    <tr>
                        <th class="text-center col-md-1">Kode</th>
                        <th class="text-center">Nama Dosen</th>
                        <th class="text-center">Program Studi</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-center col-md-1">Details</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Details</h4>
                </div>
                <div class="modal-body">
                    <div id="nilai"></div>
                </div> <!-- / .modal-body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div> <!-- / .modal-content -->
        </div> <!-- / .modal-dialog -->
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
            // paging: false,
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
                },
            },
            columns: [{
                    data: 'kode_dosen',
                    name: 'kode_dosen',
                    'class': 'text-center'
                },
                {
                    data: 'nama_dosen',
                    name: 'nama_dosen'
                },
                {
                    data: 'prodi',
                    name: 'prodi'
                },
                {
                    data: 'nilai',
                    name: 'nilai',
                    'class': 'text-center'
                },
                {
                    data: 'details',
                    name: 'details',
                    'class': 'text-center'
                },
            ],
            "order": [
                [1, "asc"]
            ]
        });

        $("#filter").on('click', function() {
            if (!$("#th_akademik_id").val()) {
                swal(
                    'Peringatan..!!',
                    'Silahkan Pilih Tahun Akademik',
                    'warning'
                );
                $("#th_akademik_id").focus();
                return false;
            }
            dataTable.draw();
        });

        function details(dosen_id) {
            // swal('test',id,'success');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{ url($redirect . '/getDosen') }}",
                type: 'POST',
                data: {
                    '_method': 'POST',
                    _token: CSRF_TOKEN,
                    id: dosen_id
                },
                success: function(data) {
                    $("#nilai").html(data);
                    // console.log(data);
                }
            });
        }
    </script>
@endpush
