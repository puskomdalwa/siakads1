{{-- @include('sweetalert::alert') --}}

<div class="panel-body no-padding-hr">

  <div class="form-group{{ $errors->has('kode') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Kode:</label>
      <div class="col-sm-2">
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
      <div class="col-sm-6">
        {!! Form::text('nama',null,['class' => 'form-control','id'=>'nama','required'=>'true']) !!}
        @if ($errors->has('nama'))
            <span class="help-block">
                <strong>{{ $errors->first('nama') }}</strong>
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
