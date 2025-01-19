@extends('layouts.app')
@section('title', $title)
@section('content')
    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">@yield('title')</span>
            <div class="panel-heading-controls">
                <a href="{{ url($redirect . '/create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-square"></i>
                    Create</a>
            </div>
        </div>

        <div class="table-responsive">
            <table id="serversideTable" class="table table-hover table-bordered">
                <div id="table-loader" class="table-loader"></div>
                <thead>
                    <tr>
                        <th class="text-center">Kode</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Semester</th>
                        <th class="text-center">Aktif</th>
                        <th class="col-md-1 text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="panel panel-warning panel-dark">
        <div class="panel-heading">
            <span class="panel-title">Set Mahasiswa Non aktif</span>
        </div>
        <div class="alert alert-warning">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Perhatian !</strong> <br>
            Tombol set non aktif semua akan mengubah seluruh data status Mahasiswa Aktif menjadi
            Non-Aktif.
        </div>
        <form action="{{ route('thakademik.setNonAktif') }}" id="set-non-aktif" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <button class="btn btn-danger" style="margin: -10px 10px 10px 10px" type="submit">Set Non Aktif</button>
        </form>
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
        $('#set-non-aktif').submit(function (e) { 
            e.preventDefault();
            swal({
                title: "Semua mahasiswa akan diset non aktif ?",
                type: "warning",
                text: "Tetap ingin melanjutkan ?",
                showCancelButton: "true",
                cancelButtonText: "Batal",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#d33",
                confirmButtonText: "Iya",
            }).then((result) => {
                if (result.value) {
                    $(this).unbind('submit').submit();
                }
            });
        });
        
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
                beforeSend: function() {
                    addTableLoader('#table-loader');
                },
                complete: function() {
                    deleteTableLoader('#table-loader');
                }
            },
            columns: [{
                    data: 'kode',
                    name: 'kode',
                    'class': 'text-center'
                },
                {
                    data: 'nama',
                    name: 'nama',
                    'class': 'text-center'
                },
                {
                    data: 'semester',
                    name: 'semester',
                    'class': 'text-center'
                },
                {
                    data: 'txt_aktif',
                    name: 'txt_aktif',
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
                [0, "desc"]
            ]
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
