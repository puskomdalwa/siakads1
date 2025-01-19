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

    <div class="alert alert-warning">
        <div>*Data yang bisa dihapus hanyalah data pengajuan yang berstatus <b>BARU</b></div>
    </div>
    <div class="panel panel-success panel-dark">
        <div class="panel-heading">
            <span class="panel-title">Data Pengajuan Skripsi</span>
        </div>

        <div class="table-responsive">
            <table id="serversideTable" class="table table-hover table-bordered">
                <div id="table-loader" class="table-loader"></div>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun Akademik</th>
                        <th class="text-center col-md-1">Tanggal<br />Daftar</th>
                        <th class="text-center col-md-1">NIM</th>
                        <th class="text-center">Nama Mahasiswa</th>
                        <th class="text-center">L/P</th>
                        <th class="text-center">Prodi</th>
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

@push('scripts')
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/adapters/jquery.js') }}"></script>


    <script type="text/javascript">
        $('textarea').ckeditor();
        // $("#mst_dosen_id").select2({
        //     dropdownParent: $('#modalpembimbing')
        // });
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
                    d.status = $("#status").val();
                },
                beforeSend: function() {
                    addTableLoader('#table-loader');
                },
                complete: function() {
                    deleteTableLoader('#table-loader');
                }
            },
            columns: [{
                    data: 'id',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    className: "align-middle"
                },
                {
                    data: 'mta_kode',
                    name: 'mta_kode',
                    'class': 'text-center'
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

        $("#th_akademik_id").on('change', function() {
            dataTable.draw();
        });

        $("#prodi_id").on('change', function() {
            dataTable.draw();
        });

        $("#status").on('change', function() {
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
