@extends('layouts.app')
@section('title', $title)
@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
@endpush
@section('content')
    <div class="panel widget-messages-alt panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title"><i class="panel-title-icon fa fa-laptop"></i>{{ $title }}</span>
        </div> <!-- / .panel-heading -->

        <div class="panel-body no-padding-hr">
            <div class="table-responsive">
                <table id="serversideTable" class="table table-hover table-bordered">
                    <div id="table-loader" class="table-loader"></div>
                    <thead>
                        <tr>
                            <th class="text-center col-md-1">No</th>
                            <th class="text-center col-md-3">NIM</th>
                            <th class="text-center col-md-3">Nama Mahasiswa</th>
                            <th class="text-center ">Nilai</th>
                            <th class="text-center ">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <form action="#" id="form_edit" enctype="multipart/form-data" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel">Input Nilai Kompre Mahasiswa</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id_edit" name="mahasiswa_id">
                        <div class="form-group">
                            <div class="row">
                                <label class="control-label">NIM Mahasiswa</label>
                                <input class="form-control" id="nim" name="nim" type="text" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="control-label">Nama Mahasiswa</label>
                                <input class="form-control" id="nama" name="nama" type="text" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="control-label">Masukkan Nilai</label>
                                <input class="form-control" id="nilai" name="nilai" type="number"
                                    placeholder="Masukkan Nilai">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="form_submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        $("#mahasiswa_id").select2({
            allowClear: true,
            placeholder: "Pilih NIM"
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
                    data: 'id',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    className: "align-middle"
                },
                {
                    data: 'nim',
                    name: 'nim',
                    'class': 'text-center'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'nilai',
                    name: 'nilai'
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
    </script>
    <script>
        $(document).ready(function() {
            $('#modal_edit').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var nim = button.data('nim');
                var nama = button.data('nama');
                var nilai = button.data('nilai');

                var modal = $(this);
                modal.find('#title_edit').text("Edit");
                modal.find('#id_edit').val(id);
                modal.find('#nim').val(nim);
                modal.find('#nama').val(nama);
                modal.find('#nilai').val(nilai);
            })

            $('#form_edit').submit(function(e) {
                e.preventDefault();
                var fd = new FormData($(this)[0]);

                $.ajax({
                    type: "post",
                    url: "{{ route('komprehensif.dosen.edit') }}",
                    data: fd,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#form_submit').attr('disabled', true);
                    },
                    success: function(response) {
                        console.log(response);
                        Toastify({
                            text: response.text,
                            duration: 3000,
                            close: true,
                            stopOnFocus: true,
                            className: `bg-${response.type}`,
                        }).showToast();
                        $('#modal_edit').modal('hide');
                        $('#form_submit').attr('disabled', false);
                        dataTable.draw();
                    }
                });
            });
        });
    </script>
    <script>
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
                            console.log(data);
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
