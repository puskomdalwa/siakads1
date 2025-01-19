<div class="panel-body no-padding-hr">

  <div class="form-group{{ $errors->has('kode') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Kode:</label>
      <div class="col-sm-1">
        {!! Form::text('kode',null,['class' => 'form-control','id'=>'kode','required'=>'true','autofocus' => 'true']) !!}
        @if ($errors->has('kode'))
            <span class="help-block">
                <strong>{{ $errors->first('kode') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

  <div class="form-group{{ $errors->has('nama') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Nama:</label>
      <div class="col-sm-4">
        {!! Form::text('nama',null,['class' => 'form-control','id'=>'nama','required'=>'true']) !!}
        @if ($errors->has('nama'))
            <span class="help-block">
                <strong>{{ $errors->first('nama') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

  <div class="form-group{{ $errors->has('bobot') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Bobot (%):</label>
      <div class="col-sm-1">
        {!! Form::number('bobot',null,['class' => 'form-control','id'=>'bobot','required'=>'true','min'=>1,'max'=>100]) !!}
        @if ($errors->has('bobot'))
            <span class="help-block">
                <strong>{{ $errors->first('bobot') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

</div>
<div class="panel-footer">
  <div class="col-sm-offset-2">
      <button type="submit" name="save" id="save" class="btn btn-success btn-flat"><i class="fa fa-floppy-o"></i> Simpan</button>
  </div>
</div>


@push('demo')
<script>
	init.push(function () {
		// Colors
		$('#switchers-colors-square > input').switcher({ theme: 'square' });

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
