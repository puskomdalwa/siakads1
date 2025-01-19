<div class="container" id="ujian_skripsi">
    <div class="card">
        <h6 class="title">Ujian Skripsi <span id="status_ujian_skripsi_badge"></span></h6>
        <div id="update_status_ujian_skripsi" style="margin-top: 20px">
            <p class="card-subtitle">Silahkan update status ujian skripsi</p>
            <hr>
            <button type="button" class="btn btn-success" id="update_status_ujian_skripsi_btn"
                onclick="updateStatusUjianSkripsi('lolos')"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                Ujian Skripsi LOLOS</button>
            <button type="button" class="btn btn-danger" id="update_status_ujian_skripsi_btn"
                onclick="updateStatusUjianSkripsi('tidak lolos')"><i class="fa fa-minus-circle" aria-hidden="true"></i>
                Ujian Skripsi TIDAK LOLOS</button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            updateBadgeStatusUjianSkripsi("{{ $ujianSkripsi->status }}");
        });

        function updateStatusUjianSkripsi(status) {
            swal({
                title: `Anda Yakin ${status.toUpperCase()} ?`,
                type: "warning",
                text: `Ujian Skripsi akan di${status}`,
                showCancelButton: "true",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#d33",
                confirmButtonText: "Iya",
            }).then((result) => {
                if (result.value) {
                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ route('dosen_skripsi.updateStatusUjianSkripsi', ['id' => $pengajuan->id]) }}",
                        type: "POST",
                        data: {
                            '_token': csrf_token,
                            'status': status
                        },
                        dataType: "json",
                        success: function(response) {
                            console.log(response);
                            Toastify({
                                text: response.message,
                                duration: 3000,
                                close: true,
                                stopOnFocus: true,
                                className: `bg-${response.color}`,
                            }).showToast();
                            if (response.status) {
                                let data = response.data;
                                updateBadgeStatusUjianSkripsi(data.status);
                                setInterval(function() {
                                    location.reload();
                                }, 500);
                            }
                        }
                    });
                }
            });
        }

        function updateBadgeStatusUjianSkripsi(status) {
            $('#status_ujian_skripsi_badge').empty();
            $('#informasi_ujian_skripsi_title').empty();
            let color = 'secondary';
            if (status == "lolos") {
                color = 'success'
            }
            if (status == "tidak lolos") {
                color = 'danger'
            }
            if (status == "belum ujian") {
                color = 'warning'
            }
            $('#status_ujian_skripsi_badge').append(
                `<span class="badge badge-${color}">${status}</span>`
            );

            $('#informasi_ujian_skripsi_title').append(
                `Informasi Ujian <span class="badge badge-${color}">${status.toUpperCase()}</span>`
            );
        }
    </script>
@endpush
