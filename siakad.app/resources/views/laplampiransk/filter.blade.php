<form class="form-horizontal form-borderd" name="form-input" id="form-input" method="post">
    {{ csrf_field() }}
    <div class="panel-body no-padding-hr">
        <div
            class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
            <div class="row">
                <label class="col-sm-2 control-label text-danger">Tahun Akademik:</label>
                <div class="col-sm-2">
                    <select class="form-control" name="th_akademik_id" id="th_akademik_id" required>
                        @foreach ($list_thakademik as $row)
                            @php
                                $select = $th_akademik_id == $row->id ? 'selected' : '';
                            @endphp
                            <option value="{{ $row->id }}" {{ $select }}>{{ $row->kode }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('th_akademik_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('th_akademik_id') }}</strong>
                        </span>
                    @endif
                </div>

                <label class="col-sm-2 control-label">Program Studi:</label>
                <div class="col-sm-3">
                    <select class="form-control" name="prodi_id" id="prodi_id">
                        @if (empty($prodi_id))
                            <option value="">-All-</option>
                        @endif
                        @foreach ($list_prodi as $prodi)
                            <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <label class="col-sm-2 control-label">Jenis Kelamin:</label>
                <div class="col-sm-1">
                    <select class="form-control" name="jk_id" id="jk_id">
                        <option value="">-All-</option>
                        @foreach ($jk as $item)
                            <option value="{{ $item->id }}">{{ $item->kode }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
            <div class="row">
                <label class="col-sm-2 control-label">Nomer SK:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="nomer_sk" required>
                </div>
            </div>
        </div>
        <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
            <div class="row">
                <label class="col-sm-2 control-label">Tanggal:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="tanggal" id="tanggal" required>
                </div>
            </div>
        </div>

    </div>

    <div class="panel-footer text-center">
        <button type="submit" name="filter" id="filter" class="btn btn-success btn-flat">
            <i class="fa fa-filter"></i> Cetak</button>
</form>

@push('scripts')
    <script>
        var options = {
            todayBtn: "linked",
            orientation: $('body').hasClass('right-to-left') ? "auto right" : 'auto auto',
            format: "dd-mm-yyyy"
        }

        $('#tanggal').datepicker(options);
    </script>
@endpush
