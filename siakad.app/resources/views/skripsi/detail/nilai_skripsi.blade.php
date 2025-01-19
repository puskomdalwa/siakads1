<div class="container" id="nilai_skripsi">
    <div class="card">
        <h6 class="title">Nilai Skripsi</span></h6>
        <div id="update_nilai_skripsi">
            <p class="card-subtitle">Silahkan mengisi Nilai Skripsi</p>
            <hr>
            <form id="form_nilai_skripsi" action="{{ route('skripsi.simpanNilaiSkripsi', ['id' => $id]) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="form-group">
                    <label for="nilai_skripsi_angka">Nilai Angka</label>
                    <input type="number" class="form-control" oninput="setNilaiHuruf(this)" name="nilai_angka" value="{{ $pengajuan->nilai_angka }}"
                        id="nilai_skripsi_angka" step="any">
                    <label for="nilai_skripsi_huruf">Nilai Huruf</label>
                    <input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()"
                        name="nilai_huruf" value="{{ $pengajuan->nilai_huruf }}" id="nilai_skripsi_huruf"
                        step="any">
                </div>
                <button type="submit" class="btn btn-primary" style="display: none" id="form_nilai_skripsi_submit"><i
                        class="fa fa-plus-circle" aria-hidden="true"></i> Simpan</button>
                <button type="button" class="btn btn-danger" style="display: none" id="kosongkan_nilai_skripsi"
                    onclick="kosongkanNilaiSkripsi()"><i class="fa fa-minus-circle" aria-hidden="true"></i> Delete
                    Nilai Skripsi</button>
            </form>
        </div>

    </div>
</div>

@push('scripts')
    <script>
        let bobotNilai = @json($bobotNilai);
        $('#form_nilai_skripsi').on('keydown', function(event) {
            if (event.keyCode == 13) { // 13 is the keycode for the Enter key
                event.preventDefault(); // Prevent the default form submission
                return false; // Also prevent propagation and default behaviour
            }
        });

        function setNilaiHuruf(inputElement){
            let nilai = inputElement.value;
            let huruf = getNilaiHuruf(nilai);
            $('#nilai_skripsi_huruf').val(huruf);
        }

        function getNilaiHuruf(inputNilai) {
            for (let i = 0; i < bobotNilai.length; i++) {
                const nilai = bobotNilai[i];
                if (inputNilai >= nilai.nilai_min && inputNilai <= nilai.nilai_max) {
                    return nilai.nilai_huruf;
                }
            }
            return null; // Return null if no range matches
        }


        $('#form_nilai_skripsi').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('skripsi.simpanNilaiSkripsi', ['id' => $id]) }}",
                data: new FormData(this),
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#form_nilai_skripsi_submit').attr('disabled', true);
                    $('#form_nilai_skripsi_submit').html('Tunggu sebentar...');
                },
                success: function(response) {
                    $('#form_nilai_skripsi_submit').attr('disabled', false);
                    $('#form_nilai_skripsi_submit').html(
                        '<i class="fa fa-plus-circle" aria-hidden="true"></i> Simpan');
                    refresh();
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        close: true,
                        stopOnFocus: true,
                        className: `bg-${response.color}`,
                    }).showToast();
                    updateStatusSkripsi('Selesai', 'success');
                }
            });
        });

        function kosongkanNilaiSkripsi() {
            swal({
                title: "Anda Yakin Mengkosongkan Nilai Skripsi ?",
                type: "warning",
                text: "Nilai skripsi akan dikosongkan",
                showCancelButton: "true",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#d33",
                confirmButtonText: "Iya",
            }).then((result) => {
                if (result.value) {
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ url($redirect) }}" + '/detail/{{ $id }}/kosongkanNilaiSkripsi',
                        type: "POST",
                        data: {
                            '_method': 'PUT',
                            '_token': csrfToken
                        },
                        success: function(response) {
                            Toastify({
                                text: response.message,
                                duration: 3000,
                                close: true,
                                stopOnFocus: true,
                                className: `bg-${response.color}`,
                            }).showToast();
                            if (response.status) {
                                $('#nilai_skripsi_angka').val(null);
                                $('#nilai_skripsi_huruf').val(null);
                                updateStatusSkripsi('Ujian Skripsi', 'success');
                            }
                        },
                    });
                }
            });
        }
    </script>
@endpush
