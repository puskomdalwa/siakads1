<div class="container" id="bimbingan">
    <div class="card">
        <h6 class="title">Detail Bimbingan {{ $mahasiswa->nama }}</h6>
        <p class="card-subtitle">Detail informasi terkait detail bimbingan skripsi, bimbingan skripsi <b>hanya bisa
                diedit / dihapus</b> ketika status <span class="badge badge-warning">BELUM ACC</span></p>
        <p class="card-subtitle">Hanya bisa menambahkan data bimbingan ketika status skripsi <span
                class="badge badge-success">Bimbingan</span> dan <span class="badge badge-success">Ujian Skripsi</span>
        </p>
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
                                <th class="text-center" style="width: 100px">Pembimbing</th>
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
        @if ($statusPengajuan == 'Bimbingan' || $statusPengajuan == 'Ujian Skripsi')
            @include('site_mhs.mhs_skripsi.detail.bimbingan.add')
        @endif
    </div>
</div>


@include('site_mhs.mhs_skripsi.detail.bimbingan.edit')


@push('scripts')
    <script>
        var dataTable = $("#serversideTable").DataTable({
            // responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            search: {
                return: true,
            },
            ajax: {
                url: "{{ url($redirect) }}" + "/detail/{{ $id }}/getDataBimbingan",
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
                        url: "{{ url($redirect) }}" + '/detail/{{ $id }}/deleteBimbingan/' +
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
    </script>
@endpush
