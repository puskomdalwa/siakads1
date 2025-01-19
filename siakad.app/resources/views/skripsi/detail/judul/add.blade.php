<!-- Modal Add-->
<div class="modal fade" id="modal_add" tabindex="-1" aria-labelledby="modal_add" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_add">Tambah Skripsi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('skripsi.tambahJudul', ['id' => $id]) }}" id="form_add" method="POST"
                enctype="multipart/form-data">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label>Judul Skripsi</label>
                        <input type="text" class="form-control" name="judul" aria-describedby="judul" required>
                    </div>
                    <div class="form-group form-check">
                        <label class="control-label">Proposal Skripsi:</label>
                        <input type="file" class="form-control" name="dokumen_proposal" accept=".doc,.pdf">
                        <small id="emailHelp" class="form-text text-muted">* Maksimal ukuran file 5 Mb</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="form_add_submit">Tambah Skripsi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('#form_add').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('skripsi.tambahJudul', ['id' => $id]) }}",
                data: new FormData(this),
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#form_add_submit').attr('disabled', true);
                    $('#form_add_submit').html('Tunggu sebentar...');
                },
                success: function(response) {
                    $('#form_add_submit').attr('disabled', false);
                    $('#form_add_submit').html('Tambah Skripsi');
                    $('#modal_add').modal('hide');
                    refresh();
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
    </script>
@endpush
