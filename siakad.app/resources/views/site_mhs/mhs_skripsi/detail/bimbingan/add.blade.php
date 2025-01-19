<div id="tambah_bimbingan" class="panel widget-messages-alt panel-danger panel-dark"
    style="border: none;box-shadow: -2px 2px 24px -5px rgba(0,0,0,0.53) !important;">
    <div class="panel-heading">
        <div>
            <div class="pull-left">Tambah Data Bimbingan Skripsi</div>
        </div>
        <br>
    </div>
    <div class="panel-body padding-sm" style="overflow: hidden">
        <form id="form_tambah_bimbingan" action="{{ route('mhs_skripsi.tambahBimbingan', ['id' => $id]) }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="judul_id" value="{{ $id }}">
            <div class="form-group">
                <label>Uraian Bimbingan</label>
                <input type="text" class="form-control" name="uraian" aria-describedby="uraian" required>
            </div>
            <div class="form-group">
                <label>Pembimbing</label>
                <select class="form-control select2" name="pembimbing_id" required id="pembimbing_id_tambah">
                    <option value="">-Pilih-</option>
                    @foreach ($pembimbing as $item)
                        <option value="{{ $item->id }}">
                            -{{ $item->dosen->nama }}-{{ $item->jabatan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Waktu Bimbingan</label>
                <input type="datetime-local" class="form-control" name="tanggal" aria-describedby="tanggal" required>
            </div>
            <button type="submit" class="btn btn-primary" id="form_tambah_submit_bimbingan"><i
                    class="fa fa-plus-circle" aria-hidden="true"></i> Tambah
                Bimbingan</button>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        $('#form_tambah_bimbingan').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('mhs_skripsi.tambahBimbingan', ['id' => $id]) }}",
                data: new FormData(this),
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#form_tambah_submit_bimbingan').attr('disabled', true);
                    $('#form_tambah_submit_bimbingan').html('Tunggu sebentar...');
                },
                success: function(response) {
                    $('#form_tambah_submit_bimbingan').attr('disabled', false);
                    $('#form_tambah_submit_bimbingan').html(
                        '<i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Bimbingan');
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
