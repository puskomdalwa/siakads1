@extends('layouts.app')

@section('title', $title)
@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        .container {
            background-color: white;
            padding: 10px;
            width: 99%
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
    </style>
@endpush
@section('content')
    <div class="container">
        <div class="card">
            <h6 class="title">Skripsi Mahasiswa</h6>
            <p class="card-subtitle">Berisi informasi terkait pengajuan skripsi mahasiswa.
            </p>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">×</span></button>

                <h4><b>Perhatian!</b></h4>
                @if ($cekSkripsiMahasiswa['status'])
                    <ol>
                        <li>Mahasiswa yang akan program skripsi di KRS, <b>WAJIB</b> upload Proposal Skripsi di SIAKAD</li>
                        <li>Jumlah maksimal upload skripsi adalah <b>3 Judul</b></li>
                        <li>Klik <b>Judul Skripsi *yang tercetak tebal</b> atau klik tombol <b>Klik -> Detail</b>, untuk
                            melihat
                            informasi detail dari Skripsi</li>
                        <li><b>Detail</b> skripsi berisi <b>dokumen proposal</b>, <b>bimbingan</b>, dan <b>dokumen
                                skripsi</b>
                        </li>
                        <li>Klik tombol <b>Tambah Skripsi</b> untuk mengajukan skripsi baru</li>
                        <li>Isi semua kolom yang ada dan upload file proposal skripsi dalam format <b>.doc, .docx., atau
                                .pdf</b></li>
                        <li>Jika ingin merubah proposal skripsi, klik tombol <b>Klik -> Edit Skripsi</b></li>
                        <li><b>Tambah Skripsi</b> dan <b>Edit Skripsi</b> hanya bisa dilakukan jika status skripsi
                            <b>Baru</b>
                        </li>
                        <li>Informasi lebih lanjut bisa menghubungi BAAK atau staf prodi masing-masing</li>
                    </ol>
                @else
                    <p>{{ strtoupper($cekSkripsiMahasiswa['message']) }}</p>
                @endif
            </div>

            <section id="skripsi">
                <button class="btn btn-success" style="margin-bottom: 5px" onclick="refresh()"><i class="fa fa-refresh"
                        aria-hidden="true"></i> Refresh</button>
                <div class="header-skripsi">
                    <h6 class="title">Data Skripsi</h6>
                    @if (@$pengajuan->status == 'Baru' || ($pengajuan == null && $cekSkripsiMahasiswa['status']))
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modal_add"><i
                                class="fa fa-plus-circle" aria-hidden="true"></i> Tambah
                            Skripsi</button>
                    @endif

                </div>
                <div class="table-responsive">
                    <table id="serversideTable" class="table table-bordered table-hover">
                        <div id="table-loader" class="table-loader"></div>
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10px">No</th>
                                <th class="text-center">Skripsi</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    @if (@$pengajuan->status == 'Baru' || ($pengajuan == null && $cekSkripsiMahasiswa['status']))
        @include('site_mhs.mhs_skripsi.index.add')
    @endif

    @include('site_mhs.mhs_skripsi.index.edit')
@endSection
@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script type="text/javascript">
        var dataTable = $("#serversideTable").DataTable({
            // responsive: true,
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
                    data: 'skripsi_judul_judul',
                    name: 'skripsi_judul_judul',
                    'orderable': false,
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    'orderable': false,
                    'searchable': false,
                },
            ],
            "order": [
                [0, "desc"]
            ]
        });

        function refresh() {
            dataTable.ajax.reload();
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
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ url($redirect) }}" + '/delete/' + id,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            'id': id,
                            '_token': csrfToken
                        },
                        success: function(data) {
                            refresh();
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
    </script>
@endpush
