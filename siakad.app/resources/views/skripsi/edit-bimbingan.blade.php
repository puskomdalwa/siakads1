<!-- Modal Edit -->
<div class="modal fade" id="modal_edit_bimbingan" aria-labelledby="modal_edit_bimbingan" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_edit_bimbingan">Edit Bimbingan <span id="title_edit"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('skripsi.updateBimbingan', ['id' => $id, 'judulId' => $judulId]) }}"
                id="form_edit_bimbingan" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="uraian">Uraian Bimbingan</label>
                        <input type="text" class="form-control" id="uraian" name="uraian"
                            aria-describedby="uraian">
                    </div>
                    <div class="form-group">
                        <label for="pembimbing_id">Pembimbing</label>
                        <select class="form-control" name="pembimbing_id" id="pembimbing_id">
                            <option value="">-Pilih-</option>
                            @foreach ($pembimbing as $item)
                                <option value="{{ $item->id }}">-{{ $item->dosen->nama }}-{{ $item->jabatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Waktu Bimbingan</label>
                        <input type="datetime-local" class="form-control" id="tanggal" name="tanggal"
                            aria-describedby="tanggal">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="form_edit_submit_bimbingan">Edit
                        Bimbingan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $("#pembimbing_id").select2({
            allowClear: true,
            placeholder: "Pilih Pembimbing"
        });
        $('#modal_edit_bimbingan').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var uraian = button.data('uraian');
            var tanggal = button.data('tanggal');
            var pembimbing_id = button.data('pembimbing_id');

            var modal = $(this);
            modal.find('#id').val(id);
            modal.find('#uraian').val(uraian);
            modal.find('#tanggal').val(tanggal);
            modal.find('#pembimbing_id').val(pembimbing_id).change();
            modal.find('#title_edit').text(`(${tanggal})`);
        })

        $('#form_edit_bimbingan').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('skripsi.updateBimbingan', ['id' => $id, 'judulId' => $judulId]) }}",
                data: new FormData(this),
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#form_edit_submit_bimbingan').attr('disabled', true);
                    $('#form_edit_submit_bimbingan').html('Tunggu sebentar...');
                },
                success: function(response) {
                    $('#form_edit_submit_bimbingan').attr('disabled', false);
                    $('#form_edit_submit_bimbingan').html('Edit Bimbingan');
                    $('#modal_edit_bimbingan').modal('hide');
                    refreshBimbingan();
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
