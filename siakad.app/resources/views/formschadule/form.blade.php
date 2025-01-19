<div class="panel-body no-padding-hr">
    <div
        class="form-group{{ $errors->has('kode') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Kode:</label>
            <div class="col-sm-1">
                {!! Form::text('kode', null, [
                    'class' => 'form-control',
                    'id' => 'kode',
                    'required' => 'true',
                    'autofocus' => 'true',
                ]) !!}

                @if ($errors->has('kode'))
                    <span class="help-block">
                        <strong>{{ $errors->first('kode') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('nama') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Nama:</label>
            <div class="col-sm-3">
                {!! Form::text('nama', null, ['class' => 'form-control', 'id' => 'nama', 'required' => 'true']) !!}

                @if ($errors->has('nama'))
                    <span class="help-block"><strong>{{ $errors->first('nama') }}</strong></span>
                @endif
            </div>

            <label class="col-sm-2 control-label">Semester:</label>
            <div class="col-sm-2">
                {!! Form::text('semester', null, [
                    'class' => 'form-control',
                    'id' => 'semester',
                    'required' => 'true',
                    'autofocus' => 'true',
                ]) !!}

                @if ($errors->has('semester'))
                    <span class="help-block"><strong>{{ $errors->first('semester') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('tgl_mulai') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Tanggal Mulai:</label>
            <div class="col-sm-3">
                {!! Form::text('tgl_mulai', !empty($data->tgl_mulai) ? date('d-m-Y', strtotime($data->tgl_mulai)) : null, [
                    'class' => 'form-control',
                    'id' => 'tgl_mulai',
                    'required' => 'true',
                ]) !!}

                @if ($errors->has('tgl_mulai'))
                    <span class="help-block"><strong>{{ $errors->first('tgl_mulai') }}</strong></span>
                @endif
            </div>

            <label class="col-sm-2 control-label">Tanggal Selesai:</label>
            <div class="col-sm-3">
                {!! Form::text('tgl_selesai', !empty($data->tgl_selesai) ? date('d-m-Y', strtotime($data->tgl_selesai)) : null, [
                    'class' => 'form-control',
                    'id' => 'tgl_selesai',
                    'required' => 'true',
                ]) !!}

                @if ($errors->has('tgl_selesai'))
                    <span class="help-block"><strong>{{ $errors->first('tgl_selesai') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('aktif') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Aktif:</label>
            <div class="col-sm-2">
                <div id="switchers-colors-square" class="form-group-margin">
                    <input type="checkbox" name="aktif" id="aktif" data-class="switcher-success"
                        {{ !empty($data->aktif) ? ($data->aktif == 'Y' ? 'checked' : '') : '' }}>
                </div>

                @if ($errors->has('aktif'))
                    <span class="help-block"><strong>{{ $errors->first('aktif') }}</strong></span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="panel-footer">
    <div class="col-sm-offset-2">
        <button type="submit" name="save" id="save" class="btn btn-success btn-flat">
            <i class="fa fa-floppy-o"></i> Simpan</button>
    </div>
</div>

@push('demo')
    <script>
        init.push(function() {
            // Colors
            $('#switchers-colors-square > input').switcher({
                theme: 'square'
            });

            var options = {
                todayBtn: "linked",
                orientation: $('body').hasClass('right-to-left') ? "auto right" : 'auto auto',
                format: "dd-mm-yyyy"
            }

            $('#tgl_mulai').datepicker(options);
            $('#tgl_selesai').datepicker(options);
        });
    </script>
@endpush
