<div class="panel-body no-padding-hr">

  <div class="form-group{{ $errors->has('kode') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Kode:</label>
      <div class="col-sm-2">
        {!! Form::text('kode',null,['class' => 'form-control','id'=>'kode','required'=>'true']) !!}
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
      <label class="col-sm-2 control-label">Nama Lengkap:</label>
      <div class="col-sm-5">
        {!! Form::text('nama',null,['class' => 'form-control','id'=>'nama','required'=>'true']) !!}
        @if ($errors->has('nama'))
            <span class="help-block">
                <strong>{{ $errors->first('nama') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

  <div class="form-group{{ $errors->has('jabatan_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Jabatan:</label>
      <div class="col-sm-3">
        <select class="form-control" name="jabatan_id" id="jabatan_id" required>
          <option value="">-Pilih-</option>
          @foreach($list_jabatan as $row)
            {{$select = !empty($data->jabatan_id)?$data->jabatan_id==$row->id?'selected':'':''}}
            <option value="{{$row->id}}" {{$select}}>{{$row->kode}} - {{$row->nama}}</option>
          @endforeach
        </select>
        @if ($errors->has('jabatan_id'))
            <span class="help-block">
                <strong>{{ $errors->first('jabatan_id') }}</strong>
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

</script>
@endpush
