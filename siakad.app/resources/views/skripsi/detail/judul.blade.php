<div class="container" id="judul">
    <div class="card">
        <h6 class="title">Detail Judul Skripsi {{ $mahasiswa->nama }}</h6>
        <p class="card-subtitle">Detail informasi terkait detail judul skripsi, judul skripsi <b>hanya bisa
                diedit / dihapus</b> ketika status <span class="badge badge-danger">TIDAK ACC</span></p>
        <p class="card-subtitle">Menambahkan judul skripsi hanya bisa dilakukan jika status skripsi adalah <span
                class="badge badge-primary">BARU</span></p>
        <p class="card-subtitle">Bisa edit status SELESAI melalui <span class="badge badge-primary">KLIK</span> ketika
            status sudah <b>
                LOLOS UJIAN SKRIPSI dan MENGUPLOAD BERKAS DOKUMEN SKRIPSI</b></p>
        <button class="btn btn-success" style="margin-bottom: 5px" onclick="refresh()"><i class="fa fa-refresh"
                aria-hidden="true"></i> Refresh</button>

        <div id="bimbingan_skripsi" class="panel widget-messages-alt panel-danger panel-dark"
            style="border: none;box-shadow: -2px 2px 24px -5px rgba(0,0,0,0.53) !important;margin-top:10px">
            <div class="panel-heading">
                <span class="panel-title">Judul Skripsi</span>
                <div class="panel-heading-controls">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modal_add"><i
                            class="fa fa-plus-circle" aria-hidden="true"></i> Tambah
                        Skripsi</button>
                </div>
            </div>
            <div class="panel-body padding-sm" style="overflow: hidden">
                <div class="table-responsive">
                    <table id="serversideTable" class="table table-bordered table-hover">
                        <div id="table-loader" class="table-loader"></div>
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10px">No</th>
                                <th class="text-center" style="width: 1000px">Judul</th>
                                <th class="text-center" style="width: 500px">Catatan</th>
                                <th class="text-center" style="width: 100px">Proposal</th>
                                <th class="text-center" style="width: 100px">Skripsi</th>
                                <th class="text-center" style="width: 100px">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@include('skripsi.detail.judul.add')
@include('skripsi.detail.judul.edit')
@include('skripsi.detail.judul.detail-catatan')
@push('scripts')
    <script>
        function accJudul(judulId, status) {
            let text = status == 'Y' ? 'ACC' : 'Tolak';
            swal({
                title: `Anda Yakin ${text} Judul ini ?`,
                type: "warning",
                text: `Judul akan di${text}`,
                showCancelButton: "true",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#d33",
                confirmButtonText: `Iya, ${text}!`,
            }).then((result) => {
                if (result.value) {
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ url($redirect) }}" + '/detail/{{ $id }}/updateStatusJudul',
                        type: "POST",
                        data: {
                            '_token': csrfToken,
                            'status': status,
                            'judul_id': judulId,
                        },
                        success: function(response) {
                            refresh();
                            Toastify({
                                text: response.message,
                                duration: 3000,
                                close: true,
                                stopOnFocus: true,
                                className: `bg-${response.color}`,
                            }).showToast();
                            if (response.status) {
                                if (status == "Y") {
                                    $('#judul_id').val(judulId).change();
                                    if (response.status_pengajuan == 'Diperiksa') {
                                        swal({
                                            title: "Pilih Jadwal Ujian Proposal",
                                            text: "Silahkan pilih Jadwal Ujian Proposal dan Penguji",
                                            type: 'warning'
                                        });
                                        $('html, body').animate({
                                            scrollTop: $('#ujian_proposal').offset().top
                                        }, 1000);

                                        updateStatusSkripsi('Diperiksa', 'warning');
                                    }
                                }
                                if (status == "T") {
                                    $('#judul_id').val('').change();
                                    updateStatusSkripsi('Baru', 'primary');
                                }

                                let judul = response.data.judul;
                                if (judul) {
                                    updateJudulSkripsi(judul.judul);
                                } else {
                                    updateJudulSkripsi('');
                                }

                            }
                        },
                        error: function(xhr, status, error) {
                            swal(
                                'ERROR!',
                                'Silahkan Hubungi Administrator',
                                'error'
                            )
                            console.error('AJAX Error:', status, error);
                        }
                    });
                }
            });
        }

        function deleteForm(idJudul) {
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
                        url: "{{ url($redirect) }}" + '/detail/{{ $id }}/deleteJudul/' +
                            idJudul,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
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
