@extends('layouts.app')
@section('title', $title)

@section('content')
    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">Filter @yield('title')
                <!-- ===> Batas Akhir Pengisian Niilai : 31/01/2022 -->
            </span>
        </div>

        @include($folder . '.filter')
    </div>

    <div class="panel panel-success panel-dark">
        <div class="panel-heading">
            <span class="panel-title">Data @yield('title')</span>
            <div class="panel-heading-controls">
                <a href="{{ url($redirect) }}" class="btn btn-primary">
                    <i class="fa fa-refresh"></i> Refresh</a>
            </div>
        </div>

        </br>
        <span class="label label-warning text-primary"> Status : &nbsp;&nbsp;
            <i class="fa fa-check text-success"></i> : Sudah Isi Absensi &nbsp;&nbsp;&nbsp;
            <i class="fa fa-times text-danger"></i> : Belum Isi Absensi. </span>

        <div class="table-responsive">
            <table id="serversideTable" class="table table-hover table-bordered">
                <div id="table-loader" class="table-loader"></div>
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center col-md-3">Nama Dosen</th>
                        <!-- <th class="text-center col-md-1">Kode</th> -->
                        <th class="text-center col-md-4">Nama Mata Kuliah</th>
                        <th class="text-center">SKS</th>
                        <th class="text-center">Smt</th>
                        <th class="text-center">Hari</th>
                        <th class="text-center">Klompok</th>
                        <!-- <th class="text-center">Kurikulum</th> -->
                        <th class="text-center">Ruang</th>
                        <th class="text-center col-md-1">Waktu</th>
                        <th class="text-center">Mhs</th>
                        <th class="text-center">Abs</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>

        <span class="label label-warning text-primary"> Status : &nbsp;&nbsp;
            <i class="fa fa-check text-success"></i> : Sudah Isi Absensi &nbsp;&nbsp;&nbsp;
            <i class="fa fa-times text-danger"></i> : Belum Isi Absensi. </span>
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
            // paging: false,
            // "searching": false,
            columns: [{
                    data: 'id',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    className: "align-middle"
                }, {
                    data: 'dosen',
                    name: 'dosen'
                },
                //{ data: 'kd_mk', 		name: 'kd_mk','class':'text-center'},
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
                    data: 'hari',
                    name: 'hari',
                    'class': 'text-center'
                },
                {
                    data: 'kelompok',
                    name: 'kelompok',
                    'class': 'text-center'
                },
                //{ data: 'kurikulum',	name: 'kurikulum','class':'text-center'},
                {
                    data: 'ruang_kelas',
                    name: 'ruang',
                    'class': 'text-center'
                },
                {
                    data: 'waktu',
                    name: 'waktu',
                    'class': 'text-center'
                },
                {
                    data: 'jml_mhs',
                    name: 'jml_mhs',
                    'class': 'text-center'
                },
                {
                    data: 'status',
                    name: 'status',
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
                [0, "asc"]
            ]
        });

        $("#th_akademik_id").on('change', function() {
            //if(!$("#prodi_id").val()){
            //	swal('Peringatan..!!','Silahkan Pilih Program Studi','warning');
            //	$("#th_akademik_id").focus();
            //	return false;
            //}
            dataTable.draw();
        });

        $("#prodi_id").on('change', function() {
            //if(!$("#th_adkademik_id").val()){
            //	swal('Peringatan..!!','Silahkan Pilih Tahun Akademik','warning');
            //	$("#th_akademik_id").focus();
            //	return false;
            //}
            dataTable.draw();
        });

        $("#kelas_id").on('change', function() {
            dataTable.draw();
        });
    </script>
@endpush
