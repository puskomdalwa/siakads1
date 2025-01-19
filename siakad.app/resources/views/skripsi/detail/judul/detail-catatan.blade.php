<!-- Modal Edit -->
<div class="modal fade" id="modal_catatan" tabindex="-1" aria-labelledby="modal_catatan" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_catatan">Detail Catatan <span id="title_edit"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="catatan_judul" style="overflow-x: scroll"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('#modal_catatan').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);

            var catatan = button.data('catatan');
            var modal = $(this);
            modal.find('#catatan_judul').html(catatan);
        })
    </script>
@endpush
