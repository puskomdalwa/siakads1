<div class="note note-warning">
	<h4 class="note-title">Perhatian</h4>
	Status Aktif pada Tahun Akademik akan mengubah seluruh mahasiswa Aktif menjadi Non Aktif.
</div>

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

  <div class="form-group{{ $errors->has('semester') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Semester:</label>
      <div class="col-sm-2">
        {!! Form::select('semester',['Ganjil'=>'Ganjil','Genap'=>'Genap'],null,['class' => 'form-control','id'=>'semester','required'=>'true']) !!}
        @if ($errors->has('semester'))
            <span class="help-block">
                <strong>{{ $errors->first('semester') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

  <div class="form-group{{ $errors->has('aktif') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Aktif:</label>
      <div class="col-sm-2">
        <div id="switchers-colors-square" class="form-group-margin">
          <input type="checkbox" name="aktif" id="aktif" data-class="switcher-success" {{!empty($data->aktif)?$data->aktif=='Y'?'checked':'':''}}>
        </div>
        @if ($errors->has('aktif'))
            <span class="help-block">
                <strong>{{ $errors->first('aktif') }}</strong>
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
	});
</script>
@endpush
