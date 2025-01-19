<div class="panel-body no-padding-hr">
	<div class="form-group{{ $errors->has('prodi_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Program Studi:</label>
		<div class="col-sm-5">
			<select class="form-control" name="prodi_id" id="prodi_id" required>
			{{-- <option value="">-Pilih-</option> --}}
			@foreach($list_prodi as $prodi)
				{{$select = old('prodi_id')==$prodi->id?'selected':''}}
				{{$select = !empty($data->prodi_id)?$data->prodi_id==$prodi->id?'selected':'':''}}
				<option value="{{$prodi->id}}" {{$select}}>{{$prodi->nama}}</option>
			@endforeach
			</select>

			@if ($errors->has('prodi_id'))
			<span class="help-block">
			<strong>{{ $errors->first('prodi_id') }}</strong>
			</span>
			@endif
		</div>
    </div></div>

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
	</div></div>

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
	</div></div>

	<div class="form-group{{ $errors->has('sks') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">SKS:</label>
		<div class="col-sm-1">
			{!! Form::number('sks',null,['class' => 'form-control','id'=>'sks','required'=>'true']) !!}			
			@if ($errors->has('sks'))
			<span class="help-block">
			<strong>{{ $errors->first('sks') }}</strong>
			</span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('smt') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Semester:</label>
		<div class="col-sm-1">
			{!! Form::number('smt',null,['class' => 'form-control','id'=>'smt','required'=>'true']) !!}			
			@if ($errors->has('smt'))
			<span class="help-block">
			<strong>{{ $errors->first('smt') }}</strong>
			</span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('aktif') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Aktif:</label>
		<div class="col-sm-2">
			<div id="switchers-colors-square" class="form-group-margin">
			<input type="checkbox" name="aktif" id="aktif" data-class="switcher-success" {{!empty($data->aktif)?$data->aktif=='Y'?'checked':'':''}}>
			</div>
			
			@if ($errors->has('aktif'))
			<span class="help-block">
			<strong>{{ $errors->first('aktif') }}</strong></span>
			@endif
		</div>
    </div></div>
</div>

<div class="panel-footer text-center">
	<button type="submit" name="save" id="save" class="btn btn-success btn-flat">
	<i class="fa fa-floppy-o"></i> Simpan</button>
	<a href=" {{ url($redirect.'/create') }} " class="btn btn-warning btn-flat">
	<i class="fa fa-file-o"></i> Baru </a>
</div>

@push('demo')
<script>
	init.push(function () {
		// Colors
		$('#switchers-colors-square > input').switcher({ theme: 'square' });
	});
</script>
@endpush
