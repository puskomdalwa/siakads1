@extends('layouts.app')
@section('title', $title)

@section('content')
    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">@yield('title')</span>
        </div>

        @if ($errors->count() > 0)
            <div id="ERROR_COPY" style="display:none" class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br />
                @endforeach
            </div>
        @endif

        <form class="form-horizontal">
            {{ csrf_field() }}
            <input type="hidden" name="kode" id="kode" value="{{ $kode }}">
            <input type="hidden" name="th_akademik_id" id="th_akademik_id" value="{{ $th_akademik->id }}">
        </form>

        <div class="panel-body no-padding-hr">
            <div class="table-responsive">
                <table id="serversideTable" class="table table-hover table-bordered">
                    <div id="table-loader" class="table-loader"></div>
                    <thead>
                        <tr>
                            <th class="text-center col-md-1">Kode</th>
                            <th class="text-center col-md-4">Nama Matakuliah</th>
                            <th class="text-center">SKS</th>
                            <th class="text-center">smt</th>
                            <th class="text-center">Klp</th>
                            <th class="text-center ">Ruang</th>
                            <th class="text-center ">Hari</th>
                            <th class="text-center col-md-1">Waktu</th>
                            <th class="text-center ">Dokumen</th>
                            <th class="text-center ">Upload</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Upload Dokumen PDF</h4>
                </div>

                <div class="modal-body">
                    <form class="form-horizontal" action="{{ url($redirect) }}" method="post"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="jadwal_id" id="jadwal_id" value="">
                        <div class="panel-body no-padding-hr">
                            <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                                <div class="row">
                                    <label class="col-sm-3 control-label">Dokumen (PDF):</label>
                                    <div class="col-sm-5">
                                        <input type="file" class="form-control" name="dokumen" id="dokumen"
                                            value="" required>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- / .modal-body -->

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-upload"></i> Upload</button>
                    </form>
                </div>
            </div> <!-- / .modal-content -->
        </div> <!-- / .modal-dialog -->
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        var has_errors = {{ $errors->count() > 0 ? 'true' : 'false' }};
        if (has_errors) {
            swal({
                title: 'Errors',
                type: 'error',
                html: $("#ERROR_COPY").html(),
                showCloseButton: true,
            });
        }

        var dataTable = $("#serversideTable").DataTable({
            processing: true,
            serverSide: true,
            paging: false,
            search: {
                return: true,
            },
            ajax: {
                url: "{{ url($redirect) }}" + '/getData',
                data: function(d) {
                    d.th_akademik_id = $("#th_akademik_id").val();
                    d.kode = $("#kode").val();
                },
                beforeSend: function() {
                    addTableLoader('#table-loader');
                },
                complete: function() {
                    deleteTableLoader('#table-loader');
                }
            },
            columns: [{
                    data: 'kode_mk',
                    name: 'kode_mk',
                    'class': 'text-center'
                },
                {
                    data: 'nama_mk',
                    name: 'nama_mk'
                },
                {
                    data: 'sks_mk',
                    name: 'sks_mk',
                    'class': 'text-center'
                },
                {
                    data: 'smt_mk',
                    name: 'smt_mk',
                    'class': 'text-center'
                },
                {
                    data: 'kelompok',
                    name: 'kelompok',
                    'class': 'text-center'
                },
                {
                    data: 'ruang',
                    name: 'ruang',
                    'class': 'text-center'
                },
                {
                    data: 'hari',
                    name: 'hari',
                    'class': 'text-center'
                },
                {
                    data: 'waktu',
                    name: 'waktu',
                    'class': 'text-center'
                },
                {
                    data: 'dokumen',
                    name: 'dokumen',
                    'class': 'text-center'
                },
                {
                    data: 'upload',
                    name: 'upload',
                    'class': 'text-center'
                },
            ],
            "order": [
                [3, "asc"],
                [0, "asc"]
            ]
        });

        function getID(id) {
            $("#jadwal_id").val(id);
        }
    </script>
@endpush
