@extends('layouts.app')

@section('title', $title)
@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link href="{{ asset('assets/stylesheets/pages.min.css') }}" rel="stylesheet" type="text/css">
    <style>
        .container {
            background-color: white;
            padding: 10px;
            width: 99%;
            margin-bottom: 20px
        }

        .title {
            font-size: 14px;
            text-transform: uppercase;
            font-weight: 700;
            color: #32415a;
        }

        .table-responsive {
            padding: 10px !important;
        }

        .header-skripsi {
            display: flex;
            justify-content: space-between;
        }

        #biodata {
            margin-top: 50px
        }
    </style>
@endpush
@section('content')
    <div style="display: flex;justify-content: flex-end;margin-bottom:10px">
        <a href="{{ route('skripsi.detail', ['id' => $id]) }}" class="btn btn-primary">Kembali</a>
    </div>
    <div class="container" id="informasi">
        <div class="card">
            <h6 class="title">Detail Skripsi {{ $mahasiswa->nama }}</h6>
            <p class="card-subtitle">Detail informasi terkait detail skripsi yang berisi dari file proposal skripsi sampai
                selesai.
            </p>
            <blockquote class="bquote">
                <div class="tx-orange tx-uppercase text-justify">
                    <p></p>
                    <p id="judul_skripsi">{{ strtoupper($judul->judul) }}</p>
                    <p></p>
                </div>
                <footer class="tx-black">Judul Skripsi</footer>
            </blockquote>
        </div>
    </div>

    <div class="container" id="bimbingan">
        <div class="card">
            <h6 class="title">Detail Bimbingan {{ $mahasiswa->nama }}</h6>
            <p class="card-subtitle">Detail informasi terkait detail bimbingan skripsi, bimbingan skripsi <b>hanya bisa
                    diedit / dihapus</b> ketika status <span class="badge badge-warning">BELUM ACC</span></p>
            <p class="card-subtitle">Hanya bisa menambahkan data bimbingan ketika status skripsi <span
                    class="btn btn-success">Bimbingan</span></p>
            <p class="card-subtitle">Untuk mengedit atau menghapus bimbingan bisa dengan klik tombol <span
                    class="btn btn-primary">KLIK</span></p>
            <button class="btn btn-success" style="margin-bottom: 5px" onclick="refreshBimbingan()"><i class="fa fa-refresh"
                    aria-hidden="true"></i> Refresh</button>

            <div id="bimbingan_skripsi" class="panel widget-messages-alt panel-danger panel-dark"
                style="border: none;box-shadow: -2px 2px 24px -5px rgba(0,0,0,0.53) !important;">
                <div class="panel-heading">
                    <div>
                        <div class="pull-left">Bimbingan Skripsi</div>
                    </div>
                    <br>
                </div>
                <div class="panel-body padding-sm" style="overflow: hidden">
                    <div class="table-responsive">
                        <table id="serversideTable" class="table table-bordered table-hover">
                            <div id="table-loader" class="table-loader"></div>
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 10px">No</th>
                                    <th class="text-center" style="width: 100px">Tanggal</th>
                                    <th class="text-center" style="width: 100px">Dosen</th>
                                    <th class="text-center" style="width: 100px">Jabatan</th>
                                    <th class="text-center" style="width: 1000px">Uraian</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            @if ($statusPengajuan == 'Bimbingan')
                <div id="tambah_bimbingan" class="panel widget-messages-alt panel-danger panel-dark"
                    style="border: none;box-shadow: -2px 2px 24px -5px rgba(0,0,0,0.53) !important;">
                    <div class="panel-heading">
                        <div>
                            <div class="pull-left">Tambah Data Bimbingan Skripsi</div>
                        </div>
                        <br>
                    </div>
                    <div class="panel-body padding-sm" style="overflow: hidden">
                        <form id="form_tambah_bimbingan"
                            action="{{ route('skripsi.tambahBimbingan', ['id' => $id, 'judulId' => $judulId]) }}"
                            method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="judul_id" value="{{ $judulId }}">
                            <div class="form-group">
                                <label>Uraian Bimbingan</label>
                                <input type="text" class="form-control" name="uraian" aria-describedby="uraian"
                                    required>
                            </div>
                            <div class="form-group">
                                <label>Pembimbing</label>
                                <select class="form-control" name="pembimbing_id" required id="pembimbing_id_tambah">
                                    <option value="">-Pilih-</option>
                                    @foreach ($pembimbing as $item)
                                        <option value="{{ $item->id }}">-{{ $item->dosen->nama }}-{{ $item->jabatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Waktu Bimbingan</label>
                                <input type="datetime-local" class="form-control" name="tanggal" aria-describedby="tanggal"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary" id="form_tambah_submit_bimbingan"><i
                                    class="fa fa-plus-circle" aria-hidden="true"></i> Tambah
                                Bimbingan</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('skripsi.edit-bimbingan')
@endSection
@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        $("#pembimbing_id_tambah").select2({
            allowClear: true,
            placeholder: "Pilih Pembimbing"
        });
        var dataTable = $("#serversideTable").DataTable({
            // responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            search: {
                return: true,
            },
            ajax: {
                url: "{{ route('skripsi.getDataBimbingan', ['id' => $id, 'judulId' => $judulId]) }}",
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
                    data: 'tanggal',
                    name: 'tanggal',
                },
                {
                    data: 'dosen_nama',
                    name: 'dosen_nama',
                },
                {
                    data: 'jabatan',
                    name: 'jabatan',
                },
                {
                    data: 'uraian',
                    name: 'uraian'
                },
                {
                    data: 'acc',
                    name: 'acc'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                },
            ],
            "order": [
                [0, "desc"]
            ]
        });

        $('#form_tambah_bimbingan').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('skripsi.tambahBimbingan', ['id' => $id, 'judulId' => $judulId]) }}",
                data: new FormData(this),
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#form_tambah_submit_bimbingan').attr('disabled', true);
                    $('#form_tambah_submit_bimbingan').html('Tunggu sebentar...');
                },
                success: function(response) {
                    $('#form_tambah_submit_bimbingan').attr('disabled', false);
                    $('#form_tambah_submit_bimbingan').html(
                        '<i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Bimbingan');
                    refreshBimbingan();
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        close: true,
                        stopOnFocus: true,
                        className: `bg-${response.color}`,
                    }).showToast();
                }
            });
        });

        function refreshBimbingan() {
            dataTable.ajax.reload();
        }

        function deleteForm(idBimbingan) {
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
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: '{{ url($redirect) }}/detail/{{ $id }}/bimbingan/{{ $judulId }}/deleteBimbingan/' +
                            idBimbingan,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': csrfToken
                        },
                        success: function(data) {
                            refreshBimbingan();
                            swal({
                                title: data.title,
                                text: data.text,
                                type: data.type
                            });
                        },
                        error: function() {
                            swal(
                                'Proses menghapus ERROR!',
                                'Silahkan Hubungi Administrator',
                                'error'
                            )
                        }
                    });
                }
            });
        }

        function updateStatus(idBimbingan, status) {
            status = status.toUpperCase();
            swal({
                title: `Anda Yakin ${status}?`,
                type: "warning",
                text: "Data yang sudah dihapus tidak dapat kembali.",
                showCancelButton: "true",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#d33",
                confirmButtonText: `Ya, ${status}`,
            }).then((result) => {
                if (result.value) {
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ route('skripsi.updateStatusBimbingan', ['id' => $id, 'judulId' => $judulId]) }}",
                        type: "POST",
                        data: {
                            '_method': 'PUT',
                            '_token': csrfToken,
                            'id': idBimbingan,
                            'status': status
                        },
                        success: function(data) {
                            console.log(data);
                            refreshBimbingan();
                            swal({
                                title: data.title,
                                text: data.text,
                                type: data.type
                            });
                        },
                        error: function() {
                            swal(
                                'Proses update ERROR!',
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
