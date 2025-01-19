<div class="panel-body no-padding-hr">
	<div class="form-group{{ $errors->has('kode') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">Kode:</label>
		<div class="col-sm-1">
			{!! Form::text('kode',null,['class' => 'form-control','id'=>'kode','required'=>'true','autofocus' => 'true']) !!}
			@if ($errors->has('kode'))
			<span class="help-block">
			<strong>{{ $errors->first('kode') }}</strong></span>
			@endif
		</div>
		
		<label class="col-sm-1 control-label">Alias:</label>
		<div class="col-sm-1">
			{!! Form::text('alias',null,['class' => 'form-control','id'=>'alias','required'=>'true','autofocus' => 'true']) !!}
			@if ($errors->has('alias'))
			<span class="help-block">
			<strong>{{ $errors->first('alias') }}</strong></span>
			@endif
		</div>
	</div></div>
	
	<div class="form-group{{ $errors->has('nama') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Nama:</label>
		<div class="col-sm-4">
			{!! Form::text('nama',null,['class' => 'form-control','id'=>'nama','required'=>'true']) !!}
			@if ($errors->has('nama'))
			<span class="help-block">
			<strong>{{ $errors->first('nama') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('jenjang') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Jenjang:</label>
		<div class="col-sm-1">
			{!! Form::text('jenjang',null,['class' => 'form-control','id'=>'jenjang','required'=>'true']) !!}
			@if ($errors->has('jenjang'))
			<span class="help-block">
			<strong>{{ $errors->first('jenjang') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('akreditasi') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Akreditasi:</label>
		<div class="col-sm-1">
			{!! Form::text('akreditasi',null,['class' => 'form-control','id'=>'akreditasi','required'=>'true']) !!}
			@if ($errors->has('akreditasi'))
			<span class="help-block">
			<strong>{{ $errors->first('akreditasi') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('nidn_kepala') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">NIDN Kepala:</label>
		<div class="col-sm-2">
			{!! Form::text('nidn_kepala',null,['class' => 'form-control','id'=>'nidn_kepala']) !!}
			@if ($errors->has('nidn_kepala'))
			<span class="help-block">
			<strong>{{ $errors->first('nidn_kepala') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('nama_kepala') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Nama Kepala:</label>
		<div class="col-sm-5">
			{!! Form::text('nama_kepala',null,['class' => 'form-control','id'=>'nama_kepala']) !!}
			@if ($errors->has('nama_kepala'))
			<span class="help-block">
			<strong>{{ $errors->first('nama_kepala') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('max_sks_skripsi') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">Max SKS Skripsi:</label>
		<div class="col-sm-2">
			{!! Form::number('max_sks_skripsi',null,['class' => 'form-control','id'=>'max_sks_skripsi','required'=>'true']) !!}
			@if ($errors->has('max_sks_skripsi'))
			<span class="help-block">
			<strong>{{ $errors->first('max_sks_skripsi') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('color') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Warna Grafik:</label>
		<div class="col-sm-2">
			{!! Form::text('color',null,['class' => 'form-control','id'=>'color']) !!}
			<span> <a href="https://www.w3schools.com/cssref/css_colors.asp" target="_blank">Referensi Warna</a> </span>
			@if ($errors->has('color'))
			<span class="help-block">
			<strong>{{ $errors->first('color') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('aktif') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Aktif:</label>
		<div class="col-sm-2">
			<div id="switchers-colors-square" class="form-group-margin">
			<input type="checkbox" name="aktif" id="aktif" 
			data-class="switcher-success" {{!empty($data->aktif)?$data->aktif=='Y'?'checked':'':''}}>
			</div>

			@if ($errors->has('aktif'))
			<span class="help-block">
			<strong>{{ $errors->first('aktif') }}</strong></span>
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
	// Colors
	$('#switchers-colors-square > input').switcher({ theme: 'square' });
});
</script>
@endpush
