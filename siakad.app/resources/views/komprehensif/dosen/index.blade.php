@extends('layouts.app')
@section('title', $title)
@push('css')
    <style>
        td>img {
            width: 100px;
            height: 100px;
            border-radius: 100%;
            object-fit: cover;
            object-position: center;
        }
    </style>
@endpush
@section('content')
    @include('komprehensif.dosen.banin')
    @include('komprehensif.dosen.banat')

    <!-- Modal -->
    <form action="{{ route('kompre_dosen.update') }}" method="post" id="form_edit_penguji">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="modal fade" id="modal_edit_penguji" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal Title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="form-control" name="id" id="id">
                        <input type="hidden" class="form-control" name="penguji" id="penguji">
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <input type="text" class="form-control" name="jenis_kelamin" id="jenis_kelamin" readonly>
                        </div>
                        <div class="form-group">
                            <label for="dosen_id">Dosen</label>
                            <select class="form-control select2" name="dosen_id" id="dosen_id" required>
                                <option value="">-Pilih-</option>
                                @foreach ($dosen as $item)
                                    <option value="{{ $item->id }}">({{ $item->kode }}) {{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#modal_edit_penguji').on('shown.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var penguji = button.data('penguji');
                var dosen_id = button.data('dosen_id');
                var jenis_kelamin = button.data('jenis_kelamin');

                var modal = $(this)
                modal.find('.modal-title').text('Penguji ' + id)
                modal.find('#id').val(id);
                modal.find('#penguji').val(penguji);
                modal.find('#jenis_kelamin').val(jenis_kelamin);
                modal.find('#dosen_id').val(dosen_id).change();
            });
        });
    </script>

    <script>
        function deleteForm(event) {
            event.preventDefault();
            swal({
                title: "Anda Yakin ?",
                type: "warning",
                text: "Data yang sudah dihapus tidak dapat kembali.",
                showCancelButton: "true",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.value) {
                    $(event.target.form).submit();
                }
            });
        }
    </script>
@endpush
