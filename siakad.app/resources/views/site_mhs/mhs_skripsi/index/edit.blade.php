<!-- Modal Edit -->
<div class="modal fade" id="modal_edit" tabindex="-1" aria-labelledby="modal_edit" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_edit">Edit Skripsi <span id="title_edit"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('mhs_skripsi.updateProposal') }}" id="form_edit" method="POST"
                enctype="multipart/form-data">
                <div class="modal-body">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="judul">Judul Skripsi</label>
                        <input type="text" class="form-control" id="judul" name="judul" required
                            aria-describedby="judul">
                    </div>
                    <div class="form-group form-check">
                        <label class="control-label">Proposal Skripsi:</label>
                        <input type="file" class="form-control" name="dokumen_proposal" id="dokumen_proposal"
                            accept=".doc,.pdf">
                        <small id="emailHelp" class="form-text text-muted">* Maksimal ukuran file 5 Mb</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="form_edit_submit">Edit Skripsi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('#modal_edit').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var judul = button.data('judul');

            var modal = $(this);
            modal.find('#id').val(id);
            modal.find('#judul').val(judul);
            modal.find('#title_edit').text(`(${judul})`);
        })

        $('#form_edit').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('mhs_skripsi.updateProposal') }}",
                data: new FormData(this),
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#form_edit_submit').attr('disabled', true);
                    $('#form_edit_submit').html('Tunggu sebentar...');
                },
                complete: function() {
                    $('#form_edit_submit').attr('disabled', false);
                    $('#form_edit_submit').html('Edit Skripsi');
                },
                success: function(response) {
                    $('#modal_edit').modal('hide');
                    if (typeof refresh !== 'undefined') {
                        refresh();
                    }
                    if (response.status) {
                        var judulSkripsiElement = document.getElementById("judul_skripsi");
                        if (judulSkripsiElement) {
                            $('#judul_skripsi').html(response.data.judul.toUpperCase());
                        }
                        if ($('#btn_edit_skripsi').length) {
                            $('#btn_edit_skripsi').data('judul', response.data.judul);
                        }
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
