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
                <table id="serversideTable" class="table table-hover table-bordered">
                    <div id="table-loader" class="table-loader"></div>
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-center col-md-1">KODE</th>
                            <th class="text-center col-md-3">MATA KULIAH</th>
                            <th class="text-center">SKS</th>
                            <th class="text-center">SMT</th>
                            <th class="text-center">KLP</th>
                            <th class="text-center col-md-3">DOSEN</th>
                            <th class="text-center ">RUANG</th>
                            <th class="text-center ">HARI</th>
                            <th class="text-center col-md-1">WAKTU</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style media="screen">
        .text-putih {
            color: #fff;
        }

        td.details-control {
            background: url('../img/details_open.png') no-repeat center center;
            cursor: pointer;
            width: 18px;
        }

        tr.shown td.details-control {
            background: url('../img/details_close.png') no-repeat center center;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/handlebars-v4.0.5.js') }}"></script>
    <script id="details-template" type="text/x-handlebars-template">
<div class="table-responsive">
	<div class="table-header">
	<span class="text-info"><b>DETAIL KEHADIRAN</b></span></div>

	<table class="table details-table table-vcenter table-condensed table-bordered table-hover table-striped" id="posts-@{{id}}">
		<thead>
			<tr>
			<th class="text-center col-md-1">TANGGAL</th>
			<th class="text-center col-md-3">MATERI</th>
			<th class="text-center col-md-1">STATUS</th>
			</tr>
		</thead>
	</table>
	</div>
</script>

    <script type="text/javascript">
        var template = Handlebars.compile($("#details-template").html());
        var dataTable = $("#serversideTable").DataTable({
            autoWidth: false,
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
                    "className": 'details-control',
                    "orderable": false,
                    "searchable": false,
                    "data": null,
                    "defaultContent": ''
                },
                {
                    data: 'kode_mk',
                    name: 'kode_mk',
                    'class': 'text-center valign-middle'
                },
                {
                    data: 'nama_mk',
                    name: 'nama_mk',
                    'class': 'valign-middle'
                },
                {
                    data: 'sks_mk',
                    name: 'sks_mk',
                    'class': 'text-center valign-middle'
                },
                {
                    data: 'smt_mk',
                    name: 'smt_mk',
                    'class': 'text-center valign-middle'
                },
                {
                    data: 'kelompok',
                    name: 'kelompok',
                    'class': 'text-center valign-middle'
                },
                {
                    data: 'dosen',
                    name: 'dosen',
                    'class': 'valign-middle'
                },
                {
                    data: 'ruang',
                    name: 'ruang',
                    'class': 'text-center valign-middle'
                },
                {
                    data: 'hari',
                    name: 'hari',
                    'class': 'text-center valign-middle'
                },
                {
                    data: 'waktu',
                    name: 'waktu',
                    'class': 'text-center'
                },
            ],
            "order": [
                [4, "ASC"],
                [1, "ASC"]
            ]
        });


        $('#serversideTable tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = dataTable.row(tr);
            var tableId = 'posts-' + row.data().id;

            // console.log(tableId);
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(template(row.data())).show();
                initTable(tableId, row.data());
                tr.addClass('shown');
                tr.next().find('td').addClass('no-padding bg-gray');
            }
        });

        function initTable(tableId, data) {
            $('#' + tableId).DataTable({
                processing: true,
                serverSide: true,
                paging: false,
                "searching": false,
                ajax: data.details_url,
                columns: [{
                        data: 'txt-tgl',
                        name: 'txt-tgl',
                        class: 'text-center valign-middle',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'txt-materi',
                        name: 'txt-materi'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        'class': 'text-center valign-middle'
                    },
                ],
                "order": [
                    [0, "asc"]
                ]
            });
        }
    </script>
@endpush
