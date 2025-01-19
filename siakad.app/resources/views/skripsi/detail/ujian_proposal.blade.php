<div class="container" id="ujian_proposal">
    <div class="card">
        <h6 class="title">Ujian Proposal <span id="status_ujian_proposal_badge"></span></h6>
        <div id="update_ujian_proposal">
            <p class="card-subtitle">Silahkan menjadwalkan ujian proposal</p>
            <hr>
            <div class="alert alert-info">
                <strong>Perhatian!</strong> Dosen penguji harus dipilih dengan merata, sistem akan memunculkan
                <b>ERROR</b>
                ketika ada dosen yang <b>tidak merata</b>.
            </div>
            <form id="form_ujian_proposal" action="{{ route('skripsi.updateUjianProposal', ['id' => $id]) }}"
                method="POST">
                {{ csrf_field() }}
                <div class="form-group">
                    <input type="datetime-local" class="form-control" name="jadwal"
                        value="{{ @$ujianProposal->jadwal }}" id="jadwal" step="any">
                </div>
                <div class="form-group">
                    <label>Penguji 1</label>
                    <select class="form-control select2" name="penguji_1_id" required id="penguji_1_id">
                        <option value="">-Loading-</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Penguji 2</label>
                    <select class="form-control select2" name="penguji_2_id" required id="penguji_2_id">
                        <option value="">-Loading-</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="display: none" id="form_ujian_proposal_submit"><i
                        class="fa fa-plus-circle" aria-hidden="true"></i> Simpan</button>
                <button type="button" class="btn btn-danger" style="display: none" id="delete_ujian_proposal"
                    onclick="deleteUjianProposal()"><i class="fa fa-minus-circle" aria-hidden="true"></i> Delete
                    Ujian Proposal</button>
            </form>
        </div>

        <div id="update_status_ujian_proposal" style="margin-top: 20px;display:none">
            <p class="card-subtitle">Silahkan update status ujian proposal</p>
            <hr>
            <button type="button" class="btn btn-success" id="update_status_ujian_proposal_btn"
                onclick="updateStatusUjianProposal('lolos')"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                Ujian Proposal LOLOS</button>
            <button type="button" class="btn btn-danger" id="update_status_ujian_proposal_btn"
                onclick="updateStatusUjianProposal('tidak lolos')"><i class="fa fa-minus-circle" aria-hidden="true"></i>
                Ujian Proposal TIDAK LOLOS</button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('#form_ujian_proposal').on('keydown', function(event) {
            if (event.keyCode == 13) { // 13 is the keycode for the Enter key
                event.preventDefault(); // Prevent the default form submission
                return false; // Also prevent propagation and default behaviour
            }
        });
        $('#form_ujian_proposal').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('skripsi.updateUjianProposal', ['id' => $id]) }}",
                data: new FormData(this),
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#form_ujian_proposal_submit').attr('disabled', true);
                    $('#form_ujian_proposal_submit').html('Tunggu sebentar...');
                },
                success: function(response) {
                    $('#form_ujian_proposal_submit').attr('disabled', false);
                    $('#form_ujian_proposal_submit').html(
                        '<i class="fa fa-plus-circle" aria-hidden="true"></i> Simpan');
                    refresh();
                    getDosenPenguji();
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        close: true,
                        stopOnFocus: true,
                        className: `bg-${response.color}`,
                    }).showToast();
                    updateStatusSkripsi('Ujian Proposal', 'success');
                    if (response.status) {
                        updateBadgeStatusUjianProposal(response.data.status_ujian_proposal);
                    }
                }
            });
        });

        function getDosenPenguji() {
            $.ajax({
                type: "get",
                url: "{{ route('skripsi.getDosenPenguji', ['id' => $id]) }}",
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        $("#penguji_1_id").empty();
                        $("#penguji_2_id").empty();
                        let content = `<option value="">-Pilih-</option>`;
                        response.data.dosen.forEach(element => {
                            content += `<option value="${element.id}">(${element.kode}) (${element.alias}) ${element.nama} |
                                menguji: ${element.jumlah}</option>`;
                        });
                        $('#penguji_1_id').append(content);
                        $('#penguji_2_id').append(content);

                        let penguji1 = '';
                        if (response.data.penguji_1) {
                            $("#penguji_1_id").val(response.data.penguji_1.mst_dosen_id).change();
                            penguji1 = $('#penguji_1_id').find(":selected").text().split('|')[0];
                        } else {
                            $("#penguji_1_id").val('').change();
                        }
                        let penguji2 = '';
                        if (response.data.penguji_2) {
                            $("#penguji_2_id").val(response.data.penguji_2.mst_dosen_id).change();
                            penguji2 = $('#penguji_2_id').find(":selected").text().split('|')[0];;
                        } else {
                            $("#penguji_2_id").val('').change();
                        }
                        let jadwal = "";
                        let status = "";
                        if (response.data.jadwal) {
                            jadwal = formatDate(response.data.jadwal.jadwal);
                            status = response.data.jadwal.status;
                        }

                        if (penguji1, penguji2, jadwal) {
                            showInformasiUjian('Ujian Proposal', penguji1, penguji2, jadwal, status);
                        }
                    }
                }
            });
        }

        function updateStatusUjianProposal(status) {
            swal({
                title: `Anda Yakin ${status.toUpperCase()} ?`,
                type: "warning",
                text: `Ujian Proposal akan di${status}`,
                showCancelButton: "true",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#d33",
                confirmButtonText: "Iya",
            }).then((result) => {
                if (result.value) {
                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ route('skripsi.updateStatusUjianProposal', ['id' => $id]) }}",
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
                            getDosenPenguji();
                            if (response.status) {
                                let data = response.data;
                                updateBadgeStatusUjianProposal(data.status);

                                if (data.status == "lolos") {
                                    swal({
                                        title: "Pilih Pembimbing Skripsi",
                                        text: "Silahkan pilih Pembimbing Skripsi",
                                        type: 'warning'
                                    });
                                    $('html, body').animate({
                                        scrollTop: $('#acc_judul').offset().top
                                    }, 1000);
                                    $('#form_acc_judul_submit').show();
                                    $('#delete_pembimbing').show();
                                }

                                if (data.status == "tidak lolos") {
                                    $('#form_acc_judul_submit').hide();
                                    $('#delete_pembimbing').hide();
                                }
                            }
                        }
                    });
                }
            });
        }

        function updateBadgeStatusUjianProposal(status) {
            $('#status_ujian_proposal_badge').empty();
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
            $('#status_ujian_proposal_badge').append(
                `<span class="badge badge-${color}">${status}</span>`
            );
        }

        function deleteUjianProposal() {
            swal({
                title: "Anda Yakin ?",
                type: "warning",
                text: "Penguji akan dihapus",
                showCancelButton: "true",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#d33",
                confirmButtonText: "Iya",
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "get",
                        url: "{{ route('skripsi.deleteUjianProposal', ['id' => $id]) }}",
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
                            getDosenPenguji();
                            updateStatusSkripsi('Diperiksa', 'warning');
                            $('#jadwal').val('');
                            if (response.status) {
                                updateBadgeStatusUjianProposal(response.data.status_ujian_proposal);
                            }
                        }
                    });
                }
            });
        }
    </script>
@endpush
