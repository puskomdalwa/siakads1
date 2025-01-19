<div class="container" id="acc_judul">
    <div class="card">
        <h6 class="title">ACC Judul dan Pembimbing</h6>
        <p class="card-subtitle">Judul lain yang tidak diACC otomatis akan terganti status dengan tidak acc</p>
        <p class="card-subtitle">Tombol simpan dan hapus akan muncul ketika status skripsi adalah <b>BIMBINGAN</b> atau
            setelah <b>Lolos Ujian Proposal</b></p>
        <p class="card-subtitle">Jika ingin mengeditnya, bisa langsung rubah dan klik tombol <b>simpan</b></p>
        <hr>
        <div class="alert alert-info">
            <strong>Perhatian!</strong> Dosen pembimbing harus dipilih dengan merata, sistem akan memunculkan
            <b>ERROR</b>
            ketika ada dosen yang <b>tidak merata</b>.
        </div>
        <div id="acc_judul_pembimbing">
            <form id="form_acc_judul" action="{{ route('skripsi.accJudul', ['id' => $id]) }}" method="POST">
                {{ csrf_field() }}
                <div class="form-group">
                    <label>Judul Skripsi</label>
                    <select class="form-control select2" name="judul_id" required id="judul_id">
                        <option value="">-Pilih-</option>
                        @foreach ($judul as $item)
                            <option value="{{ $item->id }}" {{ @$skripsiAcc->id == $item->id ? 'selected' : '' }}>
                                -{{ strip_tags($item->judul) }}-</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Pembimbing 1</label>
                    <select class="form-control select2" name="mst_dosen_id_1" required id="mst_dosen_id_1">
                        <option value="">-Loading-</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Pembimbing 2</label>
                    <select class="form-control select2" name="mst_dosen_id_2" required id="mst_dosen_id_2">
                        <option value="">-Loading-</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Catatan</label>
                    <textarea class="form-control" name="catatan" id="" cols="30" rows="5">{{ @$skripsiAcc->catatan }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="display: none" id="form_acc_judul_submit"><i
                        class="fa fa-plus-circle" aria-hidden="true"></i> Simpan</button>
                <button type="button" class="btn btn-danger" style="display: none" id="delete_pembimbing"
                    onclick="deletePembimbing()"><i class="fa fa-minus-circle" aria-hidden="true"></i> Delete
                    Pembimbing</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('#form_acc_judul').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('skripsi.accJudul', ['id' => $id]) }}",
                data: new FormData(this),
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#form_acc_judul_submit').attr('disabled', true);
                    $('#form_acc_judul_submit').html('Tunggu sebentar...');
                },
                success: function(response) {
                    $('#form_acc_judul_submit').attr('disabled', false);
                    $('#form_acc_judul_submit').html(
                        '<i class="fa fa-plus-circle" aria-hidden="true"></i> Simpan');
                    refresh();
                    getDosenPembimbing();
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        close: true,
                        stopOnFocus: true,
                        className: `bg-${response.color}`,
                    }).showToast();

                    let judul = response.data.judul;
                    if (judul) {
                        updateJudulSkripsi(judul.judul);
                    } else {
                        updateJudulSkripsi('');
                    }

                    updateStatusSkripsi('Bimbingan', 'success');
                }
            });
        });

        function getDosenPembimbing() {
            $.ajax({
                type: "get",
                url: "{{ route('skripsi.getDosenPembimbing', ['id' => $id]) }}",
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        $("#mst_dosen_id_1").empty();
                        $("#mst_dosen_id_2").empty();
                        let content = `<option value="">-Pilih-</option>`;
                        response.data.dosen.forEach(element => {
                            content += `<option value="${element.id}">(${element.kode}) (${element.alias}) ${element.nama} |
                                membimbing: ${element.jumlah}</option>`;
                        });
                        $('#mst_dosen_id_1').append(content);
                        $('#mst_dosen_id_2').append(content);
                        if (response.data.pembimbing_1) {
                            $("#mst_dosen_id_1").val(response.data.pembimbing_1.mst_dosen_id).change();
                        } else {
                            $("#mst_dosen_id_1").val('').change();
                        }
                        if (response.data.pembimbing_2) {
                            $("#mst_dosen_id_2").val(response.data.pembimbing_2.mst_dosen_id).change();
                        } else {
                            $("#mst_dosen_id_2").val('').change();
                        }
                    }
                }
            });
        }

        function deletePembimbing() {
            swal({
                title: "Anda Yakin ?",
                type: "warning",
                text: "Pembimbing akan dihapus",
                showCancelButton: "true",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#d33",
                confirmButtonText: "Iya",
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "get",
                        url: "{{ route('skripsi.deletePembimbing', ['id' => $id]) }}",
                        dataType: "json",
                        success: function(response) {
                            Toastify({
                                text: response.message,
                                duration: 3000,
                                close: true,
                                stopOnFocus: true,
                                className: `bg-${response.color}`,
                            }).showToast();
                            getDosenPembimbing();
                            updateStatusSkripsi('Ujian Proposal', 'success');
                        }
                    });
                }
            });
        }
    </script>
@endpush
