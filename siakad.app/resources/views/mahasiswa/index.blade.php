@extends('layouts.app')
@section('title', $title)

@section('content')
    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">Program Studi @yield('title')</span>
        </div>

        @include($folder . '.filter')
    </div>

    <div class="panel panel-info panel-dark">
        <div class="panel-heading"><span class="panel-title">Cari @yield('title')</span></div>

        <div class="panel-body no-padding-hr">
            <form class="form-horizontal form-borderd">
                {{ csrf_field() }}

                <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                    <div class="row">
                        <label class="col-sm-2 control-label">NIM / Nama :</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                {!! Form::text('txt_cari', null, [
                                    'class' => 'form-control',
                                    'id' => 'txt_cari',
                                    'autofocus' => 'true',
                                    'placeholder' => 'Masukan NIM atau Nama Mahasiswa..',
                                ]) !!}

                                <span class="input-group-btn">
                                    <button type="button" name="cari_mhs" id="cari_mhs" class="btn btn-info">
                                        <i class="fa fa-search"></i> Cari</button></span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="alert alert-warning">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Perhatian!</strong>
        Mengubah status mahasiswa menjadi AKTIF melalui proses Pembayaran.
    </div>

    <div class="panel panel-success panel-dark">
        <div class="panel-heading">
            <span class="panel-title">Data @yield('title')</span>
            <div class="panel-heading-controls">
                <a href="{{ url($redirect . '/create') }}" class="btn  btn-primary">
                    <i class="fa fa-plus-square"></i> Create</a>

                <a href="{{ url($redirect . '/createUsers') }}" class="btn">
                    <i class="fa fa-key"></i> Create All Users</a>
            </div>
        </div>

        <div class="table-responsive">
            <table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
                <div id="table-loader" class="table-loader"></div>
                <thead>
                    <tr>
                        <th class="text-center col-md-1">NIM</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center col-md-1">L/P</th>
                        <th class="text-center col-md-1">Prodi</th>
                        <th class="text-center col-md-1">Kelas</th>
                        <th class="text-center col-md-1">Klp</th>
                        <th class="text-center col-md-1">Status</th>
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
                    d.status_id = $("#status_id").val();
                    d.kelas_id = $("#kelas_id").val();
                    d.txt_cari = $("#txt_cari").val();
                },
                beforeSend: function() {
                    addTableLoader('#table-loader');
                },
                complete: function() {
                    deleteTableLoader('#table-loader');
                }
            },
            columns: [{
                    data: 'nim',
                    name: 'nim',
                    'class': 'text-center'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'mhs_jk',
                    name: 'mhs_jk',
                    'class': 'text-center'
                },
                {
                    data: 'mhs_prodi',
                    name: 'mhs_prodi',
                    'class': 'text-center'
                },
                {
                    data: 'mhs_kelas',
                    name: 'mhs_kelas',
                    'class': 'text-center'
                },
                {
                    data: 'mhs_kelompok',
                    name: 'mhs_kelompok',
                    'class': 'text-center'
                },
                {
                    data: 'mhs_status',
                    name: 'mhs_status',
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

        /*
        $("#filter").on('click',function(){
        	if(!$("#th_akademik_id").val()){
        		swal(
        			'Peringatan..!!',
        			'Silahkan Pilih Tahun Angkatan',
        			'warning'
        		);
        		$("#th_akademik_id").focus();
        		return false;
        	}
        	
        	if(!$("#prodi_id").val()){
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
        */


        $("#th_akademik_id").on('change', function() {
            dataTable.draw();
        });

        $("#kelas_id").on('change', function() {
            dataTable.draw();
        });

        $("#status_id").on('change', function() {
            dataTable.draw();
        });

        $("#prodi_id").on('change', function() {
            if (!$("#kelas_id").val()) {
                swal(
                    'Peringatan..!!',
                    'Silahkan Pilih Kelas',
                    'warning'
                );
                $("#kelas_id").focus();
                return false;
            }

            // if (!$("#status_id").val()) {
            //     swal(
            //         'Peringatan..!!',
            //         'Silahkan Pilih Status',
            //         'warning'
            //     );
            //     $("#status_id").focus();
            //     return false;
            // }
            dataTable.draw();
        });


        $("#cari_mhs").on('click', function() {
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

        function deleteForm(id) {
            s
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
                            swal('Error Deleted!', 'Silahkan Hubungi Administrator', 'error')
                        }
                    });
                }
            });
        }
    </script>
@endpush