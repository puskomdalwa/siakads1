@extends('layouts.app')
@section('title', $title)

@section('content')
    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">Filter @yield('title')</span>
        </div>

        @include($folder . '.filter')

        <div class="panel-footer text-center">
            <button type="button" class="btn btn-info" name="btnRefresh" id="btnRefresh">
                <i class="fa fa-refresh"></i> Refresh
            </button>
        </div>
    </div>

    <div class="panel panel-success panel-dark">
        <div class="panel-heading">
            <span class="panel-title">Data @yield('title')</span>
        </div>

        <div class="table-responsive">
            <table id="serversideTable" class="table table-hover table-bordered">
                <div id="table-loader" class="table-loader"></div>
                <thead>
                    <tr>
                        <th></th>
                        <th class="text-center col-md-1">Tanggal<br />Daftar</th>
                        <th class="text-center col-md-1">NIM</th>
                        <th class="text-center">Nama Mahasiswa</th>
                        <th class="text-center">L/P</th>
                        <th class="text-center">Prodi</th>
                        <th class="text-center">Kelas</th>
                        <th class="text-center">Dosen Pembimbing</th>
                        <th class="text-center">Status</th>
                        <th class="text-center col-md-1">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div id="modalCatatan" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Catatan</h4>
                </div>

                <div class="modal-body">
                    <form action="#" class="form-horizontal" name="formcatatan" id="formcatatan">
                        {{ csrf_field() }}

                        <input type="hidden" name="judul_id" id="judul_id">
                        <div class="row form-group">
                            <label class="col-sm-2 control-label">Judul:</label>
                            <div class="col-sm-8">
                                <textarea name="judul" id="judul" cols="30" rows="10" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="col-sm-2 control-label">Catatan:</label>
                            <div class="col-sm-8">
                                <textarea name="catatan" id="catatan" cols="30" rows="10" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="col-sm-2 control-label">Acc:</label>
                            <div class="col-sm-3">
                                <select name="acc" id="acc" class="form-control">
                                    <option value="T">Tidak</option>
                                    <option value="Y">Ya</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-success btn-sm" name="simpancatatan"
                        id="simpancatatan">SIMPAN</button>
                </div>
            </div> <!-- / .modal-content -->
        </div> <!-- / .modal-dialog -->
    </div> <!-- / .modal -->

    @include('skripsi_pengajuan.formpembimbing')

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


@push('demo')
    <script>
        init.push(function() {
            $('#c-tooltips-demo a').tooltip();
        });
    </script>
@endpush

@push('scripts')
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/adapters/jquery.js') }}"></script>
    <script src="{{ asset('js/handlebars-v4.0.5.js') }}"></script>

    <script id="details-template" type="text/x-handlebars-template">
	<div class="table-responsive">
		<div class="table-header">
        <span class="text-info"><b>Judul Skripsi</b></span>
		</div>
   
		<table class="table details-table table-vcenter table-condensed table-bordered table-hover table-striped" id="posts-@{{id}}">
			<thead>
				<tr>
				<th class="text-center">Judul</th>
				<th class="text-center">Acc</th>
				<th class="text-center col-md-1">Action</th>
				</tr>
			</thead>
		</table>
	</div>
</script>

    <script type="text/javascript">
        $('textarea').ckeditor();
        // $("#mst_dosen_id").select2({
        //     dropdownParent: $('#modalpembimbing')
        // });

        var template = Handlebars.compile($("#details-template").html());
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
                    data: 'created_at',
                    name: 'created_at',
                    'class': 'text-center'
                },
                {
                    data: 'nim',
                    name: 'nim',
                    'class': 'text-center'
                },
                {
                    data: 'mhs_nama',
                    name: 'mhs_nama'
                },
                {
                    data: 'mhs_jk',
                    name: 'mhs_jk',
                    'class': 'text-center'
                },
                {
                    data: 'mhs_prodi',
                    name: 'mhs_prodi'
                },
                {
                    data: 'mhs_kelas',
                    name: 'mhs_kelas',
                    'class': 'text-center'
                },
                {
                    data: 'pembimbing',
                    name: 'pembimbing'
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
                    'searchable': false
                },
            ],
            "order": [
                [0, "desc"]
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
                        data: 'txt_judul',
                        name: 'txt_judul'
                    },
                    {
                        data: 'txt_acc',
                        name: 'txt_acc',
                        'class': 'text-center'
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
                    [0, "asc"]
                ]
            });
        };

        $("#th_akademik_id").on('change', function() {
            dataTable.draw();
        });

        $("#prodi_id").on('change', function() {
            dataTable.draw();
        });

        $("#btnRefresh").on('click', function() {
            dataTable.draw();
        });

        function getStatus(id) {
            // var status_id = $("#status_id_"+id).val();
            // console.log(status_id);
            var string = {
                id: id,
                status: 'Diperiksa',
                _token: "{{ csrf_token() }}"
            };
            $.ajax({
                url: "{{ url($folder . '/getStatus') }}",
                method: 'POST',
                data: string,
                success: function(data) {
                    dataTable.draw();
                    //   if(data.type=='success')
                    //   {
                    swal(data.title, data.text, data.type)
                    //   }
                }
            });
        }

        function CatatanForm(id) {
            $.get("{{ url($folder) }}" + '/' + id + '/editCatatan', function(data) {
                $("#judul_id").val(id);
                $("#judul").val(data.judul);
                $("#catatan").val(data.catatan);
                $('#acc').val(data.acc).trigger("change");
                $('#modalCatatan').modal('show');
            });
        }

        function PembimbingForm(id) {
            // $("#id").val(id);
            // $('#modalpembimbing').modal('show');
            $.get("{{ url($folder) }}" + '/' + id + '/editPembimbing', function(data) {
                console.log(data);
                if (data.type == 'success') {
                    $('#modelHeading').html("Pembimbing " + data.text);
                    $("#id").val(id);
                    $("#lbljudul").html(data.judul);
                    $('#modalpembimbing').modal('show');
                    $('#formpembimbing').trigger("reset");
                    listPembimbing(id);
                } else {
                    Swal.fire(data.title, data.info, data.status);
                }

            });
        }

        function listPembimbing(id) {
            $.get("{{ url($folder) }}" + '/' + id + '/listPembimbing', function(data) {
                $("#detailPembimbing").html(data);
            });
        }

        $("#simpancatatan").on('click', function() {
            var formdata = $("#formcatatan").serialize();
            if (!$("#catatan").val()) {
                Swal.fire(
                    'ERROR',
                    'Catatan tidak boleh kosong.!!',
                    'error'
                );
                $("#catatan").focus();
                return false;
            }

            $.ajax({
                data: formdata,
                url: "{{ url($redirect) }}" + '/simpancatatan',
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    Swal.fire(data.title, data.info, data.status);
                    $('#modalCatatan').modal('hide');
                    $('#formcatatan').trigger("reset");
                    table.draw();
                },
                error: function(data) {
                    Swal.fire('ERROR', 'Silahkan hubungi Administrator', 'error');
                }
            });
        });

        $("#simpanpembimbing").on('click', function() {
            var id = $("#id").val();
            var formdata = $("#formpembimbing").serialize();

            if (!$("#mst_dosen_id").val()) {
                Swal.fire('ERROR', 'Anda belum memilih Dosen.', 'error');
                $("#mst_dosen_id").focus();
                return false;
            }

            if (!$("#jabatan").val()) {
                Swal.fire('ERROR', 'Anda belum memilih Jabatan.', 'error');
                $("#jabatan").focus();
                return false;
            }

            $.ajax({
                data: formdata,
                url: "{{ url($redirect) }}" + '/simpanpembimbing',
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    Swal.fire(data.title, data.info, data.status);
                    // $('#modalpembimbing').modal('hide');
                    listPembimbing(id);
                    $('#formpembimbing').trigger("reset");
                    table.draw();
                },
                error: function(data) {
                    Swal.fire('ERROR', 'Silahkan hubungi Administrator', 'error');
                }
            });
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

        function hapusPembimbing(id) {
            var skripsi_id = $("#id").val();
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
                        url: "{{ url($redirect) }}" + '/hapusPembimbing/' + id,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': csrf_token
                        },
                        success: function(data) {
                            // table.ajax.reload();
                            // dataTable.draw();
                            listPembimbing(skripsi_id);
                            swal({
                                title: data.title,
                                text: data.text,
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
