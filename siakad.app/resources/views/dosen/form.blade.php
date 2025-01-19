<div class="panel-body no-padding-hr">
	<div class="form-group{{ $errors->has('prodi_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Program Studi:</label>
		<div class="col-sm-3">
			<select class="form-control" name="prodi_id" id="prodi_id" required>
				@foreach($list_prodi as $prodi)
					{{$select = old('prodi_id')==$prodi->id?'selected':''}}
					{{$select = !empty($data->prodi_id)?$data->prodi_id==$prodi->id?'selected':'':''}}
					<option value="{{$prodi->id}}" {{$select}}>{{$prodi->nama}}</option>
				@endforeach
			</select>
			
			@if ($errors->has('prodi_id'))
				<span class="help-block"><strong>{{ $errors->first('prodi_id') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('kode') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Kode:</label>
		<div class="col-sm-2">
			{!! Form::number('kode',null,['class' => 'form-control','id'=>'kode','required'=>'true','autofocus' => 'true']) !!}
			@if ($errors->has('kode'))
				<span class="help-block"><strong>{{ $errors->first('kode') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('nidn') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">NIDN:</label>
		<div class="col-sm-2">
			{!! Form::number('nidn',null,['class' => 'form-control','id'=>'nidn']) !!}
			@if ($errors->has('nidn'))
				<span class="help-block"><strong>{{ $errors->first('nidn') }}</strong></span>
			@endif
		</div>
    </div>
  </div>

	<div class="form-group{{ $errors->has('nama') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Nama Lengkap:</label>
		<div class="col-sm-4">
			{!! Form::text('nama',null,['class' => 'form-control','id'=>'nama','required'=>'true']) !!}
			@if ($errors->has('nama'))
				<span class="help-block"><strong>{{ $errors->first('nama') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('jk_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Jenis Kelamin:</label>
		<div class="col-sm-2">
			<select class="form-control" name="jk_id" id="jk_id" required>
				<option value="">-Pilih-</option>
				@foreach($list_jk as $jk)
					{{$select = old('jk_id')==$jk->id?'selected':''}}
					{{$select = !empty($data->jk_id)?$data->jk_id==$jk->id?'selected':'':''}}
					<option value="{{$jk->id}}" {{$select}}>{{$jk->nama}}</option>
				@endforeach
			</select>
			
			@if ($errors->has('jk_id'))
				<span class="help-block"><strong>{{ $errors->first('jk_id') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('tempat_lahir') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Tampat Lahir:</label>
		<div class="col-sm-4">
			<select class="form-control" name="tempat_lahir" id="tempat_lahir" required>
				<option value="">-Pilih-</option>
				@foreach($list_kota as $kota)
					{{$select = old('tempat_lahir')==$kota->name?'selected':''}}
					{{$select = !empty($data->tempat_lahir)?$data->tempat_lahir==$kota->name?'selected':'':''}}
					<option value="{{$kota->name}}" {{$select}}>{{$kota->name}}</option>
				@endforeach
			</select>

			@if ($errors->has('tempat_lahir'))
				<span class="help-block"><strong>{{ $errors->first('tempat_lahir') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('tanggal_lahir') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Tanggal Lahir:</label>
		<div class="col-sm-2">
			{!! Form::text('tanggal_lahir',!empty($data->tanggal_lahir)?tgl_str($data->tanggal_lahir):null,['class' => 'form-control','id'=>'tanggal_lahir','required'=>'true']) !!}
			@if ($errors->has('tanggal_lahir'))
				<span class="help-block"><strong>{{ $errors->first('tanggal_lahir') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('alamat') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Alamat:</label>
		<div class="col-sm-9">
			{!! Form::text('alamat',null,['class' => 'form-control','id'=>'alamat','required'=>'true']) !!}
			@if ($errors->has('alamat'))
				<span class="help-block"><strong>{{ $errors->first('alamat') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('kota_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Kota:</label>
		<div class="col-sm-4">
			<select class="form-control" name="kota_id" id="kota_id" required>
				<option value="">-Pilih-</option>
				@foreach($list_kota as $kota)
					{{$select = old('kota_id')==$kota->id?'selected':''}}
					{{$select = !empty($data->kota_id)?$data->kota_id==$kota->id?'selected':'':''}}
					<option value="{{$kota->id}}" {{$select}}>{{$kota->name}}</option>
				@endforeach
			</select>

			@if ($errors->has('kota_id'))
				<span class="help-block"><strong>{{ $errors->first('kota_id') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Email:</label>
		<div class="col-sm-4">
			{!! Form::email('email',null,['class' => 'form-control','id'=>'email','required'=>'true']) !!}
			@if ($errors->has('email'))
				<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('hp') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">HP:</label>
		<div class="col-sm-4">
			{!! Form::number('hp',null,['class' => 'form-control','id'=>'hp','required'=>'true']) !!}
			@if ($errors->has('hp'))
				<span class="help-block"><strong>{{ $errors->first('hp') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('dosen_status_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Status:</label>
		<div class="col-sm-2">
			<select class="form-control" name="dosen_status_id" id="dosen_status_id" required>
				<option value="">-Pilih-</option>
				@foreach($list_status as $status)
					{{$select = old('dosen_status_id')==$status->id?'selected':''}}
					{{$select = !empty($data->dosen_status_id)?$data->dosen_status_id==$status->id?'selected':'':''}}
					<option value="{{$status->id}}" {{$select}}>{{$status->nama}}</option>
				@endforeach
			</select>

			@if ($errors->has('dosen_status_id'))
				<span class="help-block"><strong>{{ $errors->first('dosen_status_id') }}</strong></span>
			@endif
		</div>
    </div></div>
</div>

<div class="panel-footer">
<div class="col-sm-offset-2">
<button type="submit" name="save" id="save" class="btn btn-success btn-flat">
<i class="fa fa-floppy-o"></i> Simpan</button>
</div></div>

@push('demo')
<script>
init.push(function () {
	var options = {
		todayBtn: "linked",
		orientation: $('body').hasClass('right-to-left') ? "auto right" : 'auto auto',
		format: "dd-mm-yyyy"
	}
	
	$('#tanggal_lahir').datepicker(options);

	$("#kota_id").select2({
		allowClear: true,
		placeholder: "Pilih Kota"
	});

	$("#tempat_lahir").select2({
		allowClear: true,
		placeholder: "Pilih Kota"
	});
});
</script>
@endpush
