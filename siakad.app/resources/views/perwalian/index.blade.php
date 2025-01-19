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
                <a href="{{ url($redirect . '/create') }}" class="btn btn-primary">
                    <i class="fa fa-plus-square"></i> Create</a>
            </div>
        </div>

        <div class="table-responsive">
            <table id="serversideTable" class="table table-hover table-bordered">
                <div id="table-loader" class="table-loader"></div>
                <thead>
                    <tr>
                        <th class="text-center col-md-1">Tahun<br />Angkatan</th>
                        <th class="text-center col-md-2">Dosen Wali</th>
                        <th class="text-center col-md-1">Prodi</th>
                        <th class="text-center col-md-1">Kelas</th>
                        <th class="text-center col-md-1">Kelompok</th>
                        <th class="text-center col-md-1">Jml Mhs</th>
                        <th class="col-md-1 text-center">Action</th>
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
                    d.kelas_id = $("#kelas_id").val();
                },
                beforeSend: function() {
                    addTableLoader('#table-loader');
                },
                complete: function() {
                    deleteTableLoader('#table-loader');
                }
            },
            columns: [{
                    data: 'perwalian_ta',
                    name: 'perwalian_ta',
                    'class': 'text-center'
                },
                {
                    data: 'perwalian_dosen',
                    name: 'perwalian_dosen'
                },
                {
                    data: 'perwalian_prodi',
                    name: 'perwalian_prodi',
                    'class': 'text-center'
                },
                {
                    data: 'perwalian_kelas',
                    name: 'perwalian_kelas',
                    'class': 'text-center'
                },
                {
                    data: 'perwalian_kelompok',
                    name: 'perwalian_kelompok',
                    'class': 'text-center'
                },
                {
                    data: 'jml_mhs',
                    name: 'jml_mhs',
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
                [2, "asc"],
                [4, "asc"]
            ]
        });

        $("#filter").on('click', function() {
            if (!$("#th_akademik_id").val()) {
                swal(
                    'Peringatan..!!',
                    'Silahkan Pilih Tahun Angkatan',
                    'warning'
                );

                $("#th_akademik_id").focus();
                return false;
            }
            dataTable.draw();
        });

        $("#th_akademik_id").on('change', function() {
            dataTable.draw();
        });

        $("#prodi_id").on('change', function() {
            dataTable.draw();
        });

        $("#kelas_id").on('change', function() {
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
