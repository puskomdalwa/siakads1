<form class="form-horizontal form-borderd">
    <div class="panel-body no-padding-hr">
        <div
            class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
            <div class="row">
                <label class="col-sm-2 control-label text-danger">T.Akademik:</label>
                <div class="col-sm-3">
                    <select class="form-control" id="th_akademik_id">
                        {{-- <option value="">-Pilih-</option> --}}
                        @foreach ($list_thakademik as $row)
                            <option value="{{ $row->id }}">{{ $row->nama }} {{ $row->semester }} ({{ $row->kode }})</option>
                        @endforeach
                    </select>

                    @if ($errors->has('th_akademik_id'))
                        <span class="help-block"><strong>{{ $errors->first('th_akademik_id') }}</strong></span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>
