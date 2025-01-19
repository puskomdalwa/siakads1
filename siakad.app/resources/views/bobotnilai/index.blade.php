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
                        <th class="text-center">Tahun Akademik</th>
                        <th class="text-center">Nilai Min</th>
                        <th class="text-center">Nilai Max</th>
                        <th class="text-center">Nilai Huruf</th>
                        <th class="text-center">Nilai Bobot</th>
                        <th class="text-center">Predikat</th>
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
                beforeSend: function() {
                    addTableLoader('#table-loader');
                },
                complete: function() {
                    deleteTableLoader('#table-loader');
                }
            },
            columns: [{
                    data: 'th_akademik',
                    name: 'th_akademik',
                    'class': 'text-center'
                },
                {
                    data: 'nilai_min',
                    name: 'nilai_min',
                    'class': 'text-center'
                },
                {
                    data: 'nilai_max',
                    name: 'nilai_max',
                    'class': 'text-center'
                },
                {
                    data: 'nilai_huruf',
                    name: 'nilai_huruf',
                    'class': 'text-center'
                },
                {
                    data: 'nilai_bobot',
                    name: 'nilai_bobot',
                    'class': 'text-center'
                },
                {
                    data: 'predikat',
                    name: 'predikat',
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
                [0, "asc"],
                [4, "desc"]
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
