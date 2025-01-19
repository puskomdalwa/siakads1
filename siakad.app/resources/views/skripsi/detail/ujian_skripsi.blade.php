<div class="container" id="ujian_skripsi">
    <div class="card">
        <h6 class="title">Ujian Skripsi <span id="status_ujian_skripsi_badge"></span></h6>
        <div id="update_ujian_skripsi">
            <p class="card-subtitle">Silahkan menjadwalkan ujian skripsi</p>
            <hr>
            <div class="alert alert-info">
                <strong>Perhatian!</strong> Dosen penguji harus dipilih dengan merata, sistem akan memunculkan
                <b>ERROR</b>
                ketika ada dosen yang <b>tidak merata</b>.
            </div>
            <form id="form_ujian_skripsi" action="{{ route('skripsi.updateUjianSkripsi', ['id' => $id]) }}"
                method="POST">
                {{ csrf_field() }}
                <div class="form-group">
                    <input type="datetime-local" class="form-control" name="jadwal"
                        value="{{ @$ujianSkripsi->jadwal }}" id="jadwal_ujian" step="any">
                </div>
                <div class="form-group">
                    <label>Penguji Skripsi 1</label>
                    <select class="form-control select2" name="penguji_1_id" required id="penguji_skripsi_1_id">
                        <option value="">-Loading-</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Penguji Skripsi 2</label>
                    <select class="form-control select2" name="penguji_2_id" required id="penguji_skripsi_2_id">
                        <option value="">-Loading-</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="display: none" id="form_ujian_skripsi_submit"><i
                        class="fa fa-plus-circle" aria-hidden="true"></i> Simpan</button>
                <button type="button" class="btn btn-danger" style="display: none" id="delete_ujian_skripsi"
                    onclick="deleteUjianSkripsi()"><i class="fa fa-minus-circle" aria-hidden="true"></i> Delete
                    Ujian Skripsi</button>
            </form>
        </div>

        <div id="update_status_ujian_skripsi" style="margin-top: 20px;display:none">
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
        $('#form_ujian_skripsi').on('keydown', function(event) {
            if (event.keyCode == 13) { // 13 is the keycode for the Enter key
                event.preventDefault(); // Prevent the default form submission
                return false; // Also prevent propagation and default behaviour
            }
        });

        $('#form_ujian_skripsi').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('skripsi.updateUjianSkripsi', ['id' => $id]) }}",
                data: new FormData(this),
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#form_ujian_skripsi_submit').attr('disabled', true);
                    $('#form_ujian_skripsi_submit').html('Tunggu sebentar...');
                },
                success: function(response) {
                    $('#form_ujian_skripsi_submit').attr('disabled', false);
                    $('#form_ujian_skripsi_submit').html(
                        '<i class="fa fa-plus-circle" aria-hidden="true"></i> Simpan');
                    refresh();
                    getDosenPengujiSkripsi();
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        close: true,
                        stopOnFocus: true,
                        className: `bg-${response.color}`,
                    }).showToast();
                    updateStatusSkripsi('Ujian Skripsi', 'success');
                    if (response.status) {
                        updateBadgeStatusUjianSkripsi(response.data.status_ujian_skripsi);
                    }
                }
            });
        });

        function getDosenPengujiSkripsi() {
            $.ajax({
                type: "get",
                url: "{{ route('skripsi.getDosenPengujiSkripsi', ['id' => $id]) }}",
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        $("#penguji_skripsi_1_id").empty();
                        $("#penguji_skripsi_2_id").empty();
                        let content = `<option value="">-Pilih-</option>`;
                        response.data.dosen.forEach(element => {
                            content += `<option value="${element.id}">(${element.kode}) (${element.alias}) ${element.nama} |
                                menguji: ${element.jumlah}</option>`;
                        });
                        $('#penguji_skripsi_1_id').append(content);
                        $('#penguji_skripsi_2_id').append(content);

                        let penguji1 = '';
                        if (response.data.penguji_1) {
                            $("#penguji_skripsi_1_id").val(response.data.penguji_1.mst_dosen_id).change();
                            penguji1 = $('#penguji_skripsi_1_id').find(":selected").text().split('|')[0];
                        } else {
                            $("#penguji_skripsi_1_id").val('').change();
                        }
                        let penguji2 = '';
                        if (response.data.penguji_2) {
                            $("#penguji_skripsi_2_id").val(response.data.penguji_2.mst_dosen_id).change();
                            penguji2 = $('#penguji_skripsi_2_id').find(":selected").text().split('|')[0];;
                        } else {
                            $("#penguji_skripsi_2_id").val('').change();
                        }
                        let jadwal = "";
                        let status = "";
                        if (response.data.jadwal) {
                            jadwal = formatDate(response.data.jadwal.jadwal);
                            status = response.data.jadwal.status;
                        }

                        if (penguji1, penguji2, jadwal) {
                            showInformasiUjian('Ujian Skripsi', penguji1, penguji2, jadwal, status);
                        }
                    }
                }
            });
        }

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
                        url: "{{ route('skripsi.updateStatusUjianSkripsi', ['id' => $id]) }}",
                        type: "POST",
                        data: {
                            '_token': csrf_token,
                            'status': status
                        },
                        dataType: "json",
                        success: function(response) {
                            refresh();
                            Toastify({
                                text: response.message,
                                duration: 3000,
                                close: true,
                                stopOnFocus: true,
                                className: `bg-${response.color}`,
                            }).showToast();
                            getDosenPengujiSkripsi();
                            if (response.status) {
                                let data = response.data;
                                updateBadgeStatusUjianSkripsi(data.status);
                            }
                            updateStatusSkripsi('Ujian Skripsi', 'success');
                        }
                    });
                }
            });
        }

        function updateBadgeStatusUjianSkripsi(status) {
            $('#status_ujian_skripsi_badge').empty();
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
        }

        function deleteUjianSkripsi() {
            swal({
                title: "Anda Yakin ?",
                type: "warning",
                text: "PengujiSkripsi akan dihapus",
                showCancelButton: "true",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#d33",
                confirmButtonText: "Iya",
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "get",
                        url: "{{ route('skripsi.deleteUjianSkripsi', ['id' => $id]) }}",
                        dataType: "json",
                        success: function(response) {
                            refresh();
                            Toastify({
                                text: response.message,
                                duration: 3000,
                                close: true,
                                stopOnFocus: true,
                                className: `bg-${response.color}`,
                            }).showToast();
                            getDosenPengujiSkripsi();
                            updateStatusSkripsi('Bimbingan', 'success');
                            $('#jadwal_ujian').val('');
                            if (response.status) {
                                updateBadgeStatusUjianSkripsi(response.data.status_ujian_skripsi);
                            }
                        }
                    });
                }
            });
        }
    </script>
@endpush
