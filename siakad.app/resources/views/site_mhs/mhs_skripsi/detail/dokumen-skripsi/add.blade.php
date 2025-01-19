<!-- Modal Add -->
<div class="modal fade" id="modal_add_dokumen_skripsi" tabindex="-1" aria-labelledby="modal_add_dokumen_skripsi"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_add_dokumen_skripsi">Upload Dokumen Skripsi <span
                        id="title_tambah_dokumen_skripsi"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('mhs_skripsi.storeDokumenSkripsi', ['id' => $id]) }}" id="form_add_dokumen_skripsi"
                method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <input type="hidden" name="judul_id" value="{{ $id }}">
                    <div class="form-group form-check">
                        <label class="control-label">Dokumen Skripsi Final:</label>
                        <input type="file" class="form-control" name="dokumen_skripsi" id="dokumen_skripsi"
                            accept=".pdf">
                        <small id="emailHelp" class="form-text text-muted">* Maksimal ukuran file 5 Mb, PDF</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="form_add_dokumen_skripsi_submit">Tambah Dokumen
                        Skripsi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('#modal_add_dokumen_skripsi').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var judul = button.data('judul');

            var modal = $(this);
            modal.find('#id').val(id);
        })

        $('#form_add_dokumen_skripsi').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('mhs_skripsi.storeDokumenSkripsi', ['id' => $id]) }}",
                data: new FormData(this),
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#form_add_dokumen_skripsi_submit').attr('disabled', true);
                    $('#form_add_dokumen_skripsi_submit').html('Tunggu sebentar...');
                },
                success: function(response) {
                    $('#form_add_dokumen_skripsi_submit').attr('disabled', false);
                    $('#form_add_dokumen_skripsi_submit').html('Tambah Dokumen Skripsi');
                    $('#modal_add_dokumen_skripsi').modal('hide');
                    if (typeof refresh !== 'undefined') {
                        refresh();
                    }
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
