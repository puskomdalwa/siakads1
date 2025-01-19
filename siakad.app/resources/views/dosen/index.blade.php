@extends('layouts.app')
@section('title', $title)

@section('content')
    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">Filter @yield('title') </span>
        </div>

        @include($folder . '.filter')
    </div>

    <div class="panel panel-success panel-dark">
        <div class="panel-heading">
            <span class="panel-title">Data @yield('title')</span>
            <div class="panel-heading-controls">
                <a href="{{ url($redirect . '/create') }}" class="btn  btn-primary">
                    <i class="fa fa-plus-square"></i> Create</a>
            </div>
        </div>

        <div class="table-responsive">
            <table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
                <div id="table-loader" class="table-loader"></div>
                <thead>
                    <tr>
                        <!-- <th class="text-center col-md-1">Prodi</th> -->
                        <th class="text-center col-md-1">NIY</th>
                        <th class="text-center col-md-1">NIDN</th>
                        <th class="text-center col-md-4">Nama</th>
                        <th class="text-center">L/P</th>
                        <th class="text-center col-md-2">Email</th>
                        <th class="text-center">HP</th>
                        <th class="text-center col-md-1">Status</th>
                        <th class="col-md-1 text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <span class="text-danger">
        <h4>*) Password Default Dosen : <b>123456</b></h4>
    </span>
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
                    d.prodi_id = $("#prodi_id").val();
                },
                beforeSend: function() {
                    addTableLoader('#table-loader');
                },
                complete: function() {
                    deleteTableLoader('#table-loader');
                }
            },
            columns: [
                //{ data: 'prodi',	  name: 'prodi','class':'text-center'},
                {
                    data: 'kode',
                    name: 'kode',
                    'class': 'text-center'
                },
                {
                    data: 'nidn',
                    name: 'nidn',
                    'class': 'text-center'
                },
                {
                    data: 'nama_dosen',
                    name: 'nama_dosen'
                },
                {
                    data: 'dosen_jk',
                    name: 'dosen_jk',
                    'class': 'text-center'
                },
                {
                    data: 'email',
                    name: 'email',
                    'class': 'text-lefth'
                },
                {
                    data: 'hp',
                    name: 'hp',
                    'class': 'text-center'
                },
                {
                    data: 'dosen_status',
                    name: 'dosen_status',
                    'class': 'text-center'
                },
                {
                    data: 'action',
                    name: 'action',
                    'orderable': false,
                    'searchable': false,
                },
            ],
            "order": [
                [1, "asc"]
            ]
        });

        $("#filter").on('change', function() {
            if (!$("#prodi_id").val()) {
                swal(
                    'Peringatan..!!',
                    'Silahkan Pilih Program Studi',
                    'warning'
                );

                $("#prodi_id").focus();
                return false;
            }
            dataTable.draw();
        });

        $("#prodi_id").on('change', function() {
            dataTable.draw();
        });

        function deleteForm(id) {
            swal({
                title: "Anda Yakin ?",
                type: "warning",
                text: "Data yang sudah dihapus tidak dapat kembali.",
                showCancelButton: "true",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.value) {
                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ url($redirect) }}" + '/' + id,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': csrf_token
                        },
                        success: function(data) {
                            // table.ajax.reload();
                            dataTable.draw();
                            swal({
                                title: data.title,
                                text: data.text,
                                // timer: 2000,
                                // showConfirmButton: false,
                                type: data.type
                            });
                        },
                        error: function() {
                            swal(
                                'Error Deleted!',
                                'Silahkan Hubungi Administrator',
                                'error'
                            )
                        }
                    });
                }
            });
        }
    </script>
@endpush
