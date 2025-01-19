@extends('layouts.app')
@section('title', $title)

@section('content')
    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">@yield('title')</span>
        </div>

        <form class="form-horizontal">
            {{ csrf_field() }}
            <input type="hidden" name="nim" id="nim" value="{{ $nim }}">
            <input type="hidden" name="th_akademik_id" id="th_akademik_id" value="{{ $th_akademik->id }}">
        </form>

        <div class="panel-body no-padding-hr">
            <div class="table-responsive">
                <table id="serversideTable" class="table table-hover table-bordered table-sm-responsive">
                    <div id="table-loader" class="table-loader"></div>
                    <thead>
                        <tr>
                            <th class="text-center col-md-1">Tahun Akademik</th>
                            <th class="text-center col-md-1">Kode Dosen</th>
                            <th class="text-center col-md-3">Nama Dosen</th>
                            <th class="text-center col-md-1">Isi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        var dataTable = $("#serversideTable").DataTable({
            responsive: false,
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
                    d.nim = $("#nim").val();
                },
                beforeSend: function() {
                    addTableLoader('#table-loader');
                },
                complete: function() {
                    deleteTableLoader('#table-loader');
                }
            },
            columns: [{
                    data: 'th_akademik_kode',
                    name: 'th_akademik_kode',
                    'class': 'text-center'
                }, {
                    data: 'dosen_kode',
                    name: 'dosen_kode',
                    'class': 'text-center'
                },
                {
                    data: 'dosen_nama',
                    name: 'dosen_nama'
                },
                {
                    data: 'action',
                    name: 'action',
                    'orderable': false,
                    'searchable': false,
                    'class': 'text-center'
                },
            ],
            "order": [
                [0, "desc"]
            ]
        });
    </script>
@endpush
