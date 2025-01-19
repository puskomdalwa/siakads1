<div class="panel-body no-padding-hr">
  <input type="hidden" name="id" value="{{$data->id}}">
  <div class="form-group{{ $errors->has('kode') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Kode:</label>
      <div class="col-sm-2">
        {!! Form::text('kode',$data->kode,['class' => 'form-control','id'=>'kode','required'=>'true','autofocus' => 'true']) !!}
        @if ($errors->has('kode'))
            <span class="help-block">
                <strong>{{ $errors->first('kode') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

  <div class="form-group{{ $errors->has('judul') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Judul:</label>
      <div class="col-sm-4">
        {!! Form::text('judul',$data->judul,['class' => 'form-control','id'=>'judul','required'=>'true']) !!}
        @if ($errors->has('judul'))
            <span class="help-block">
                <strong>{{ $errors->first('judul') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

  <div class="form-group{{ $errors->has('nama') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Nama:</label>
      <div class="col-sm-8">
        {!! Form::text('nama',$data->nama,['class' => 'form-control','id'=>'nama','required'=>'true']) !!}
        @if ($errors->has('nama'))
            <span class="help-block">
                <strong>{{ $errors->first('nama') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

  <div class="form-group{{ $errors->has('nama') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Nomor SK:</label>
      <div class="col-sm-8">
        {!! Form::text('sk',$data->sk,['class' => 'form-control','id'=>'sk']) !!}
        @if ($errors->has('sk'))
            <span class="help-block">
                <strong>{{ $errors->first('sk') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

  <div class="form-group{{ $errors->has('alamat') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Alamat:</label>
      <div class="col-sm-9">
        {!! Form::text('alamat',$data->alamat,['class' => 'form-control','id'=>'alamat','required'=>'true']) !!}
        @if ($errors->has('alamat'))
            <span class="help-block">
                <strong>{{ $errors->first('alamat') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

  <div class="form-group{{ $errors->has('kota_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Kota:</label>
      <div class="col-sm-5">
        <select name="kota_id" id="kota_id" class="form-control" required>
          <option value="">-Pilih Kota-</option>
          @foreach($list_kota as $kota)
            {{$select = $data->kota_id==$kota->id?'selected':''}}
            <option value="{{$kota->id}}" {{$select}}>{{$kota->name}}</option>
          @endforeach
        </select>
        @if ($errors->has('kota_id'))
            <span class="help-block">
                <strong>{{ $errors->first('kota_id') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

  <div class="form-group{{ $errors->has('telp') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Telepon:</label>
      <div class="col-sm-2">
        {!! Form::text('telp',$data->telp,['class' => 'form-control','id'=>'telp','required'=>'true']) !!}
        @if ($errors->has('telp'))
            <span class="help-block">
                <strong>{{ $errors->first('telp') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

  <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Email:</label>
      <div class="col-sm-3">
        {!! Form::text('email',$data->email,['class' => 'form-control','id'=>'email','required'=>'true']) !!}
        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

  <div class="form-group{{ $errors->has('website') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Website:</label>
      <div class="col-sm-3">
        {!! Form::text('website',$data->website,['class' => 'form-control','id'=>'website','required'=>'true']) !!}
        @if ($errors->has('website'))
            <span class="help-block">
                <strong>{{ $errors->first('website') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

  <div class="form-group{{ $errors->has('nidn_ketua') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">NIDN Ketua:</label>
      <div class="col-sm-2">
        {!! Form::text('nidn_ketua',$data->nidn_ketua,['class' => 'form-control','id'=>'nidn_ketua','required'=>'true']) !!}
        @if ($errors->has('nidn_ketua'))
            <span class="help-block">
                <strong>{{ $errors->first('nidn_ketua') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

  <div class="form-group{{ $errors->has('nama_ketua') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Nama Ketua:</label>
      <div class="col-sm-6">
        {!! Form::text('nama_ketua',$data->nama_ketua,['class' => 'form-control','id'=>'nama_ketua','required'=>'true']) !!}
        @if ($errors->has('nama_ketua'))
            <span class="help-block">
                <strong>{{ $errors->first('nama_ketua') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

  <div class="form-group{{ $errors->has('logo') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Logo:</label>
      <div class="col-sm-5">
        {!! Form::file('logo',null,['class' => 'form-control','id'=>'logo','required'=>'true']) !!}
        @if ($errors->has('logo'))
            <span class="help-block">
                <strong>{{ $errors->first('logo') }}</strong>
            </span>
        @endif
      </div>
      @if(!empty($data->logo))
        <img src="{{asset('img/'.$data->logo)}}" alt="" width="50">
      @endif
    </div>
  </div>

  <div class="form-group{{ $errors->has('favicon') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Favicon:</label>
      <div class="col-sm-5">
        {!! Form::file('favicon',null,['class' => 'form-control','id'=>'favicon','required'=>'true']) !!}
        @if ($errors->has('favicon'))
            <span class="help-block">
                <strong>{{ $errors->first('favicon') }}</strong>
            </span>
        @endif
      </div>
      @if(!empty($data->favicon))
        <img src="{{asset('img/'.$data->favicon)}}" alt="" width="50">
      @endif
    </div>
  </div>

  <div class="form-group{{ $errors->has('background') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Background Login:</label>
      <div class="col-sm-5">
        {!! Form::file('background',null,['class' => 'form-control','id'=>'background','required'=>'true']) !!}
        @if ($errors->has('background'))
            <span class="help-block">
                <strong>{{ $errors->first('background') }}</strong>
            </span>
        @endif
      </div>
      @if(!empty($data->background))
        <img src="{{asset('img/'.$data->background)}}" alt="" width="50">
      @endif
    </div>
  </div>

</div>

<div class="panel-footer">
  <div class="col-sm-offset-2">
      <button type="submit" name="save" id="save" class="btn btn-success btn-flat"><i class="fa fa-floppy-o"></i> Update</button>
  </div>
</div>
