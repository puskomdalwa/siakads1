<div class="panel-body no-padding-hr">
	<div class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label text-danger">Tahun Akademik:</label>
			<div class="col-sm-2">
				<select class="form-control" name="th_akademik_id" id="th_akademik_id" required>
					<option value="">-Pilih-</option>
					@foreach($list_thakademik as $thakademik)
						{{$select = old('th_akademik_id')==$thakademik->id?'selected':''}}
						{{$select = !empty($data->th_akademik_id)?$data->th_akademik_id==$thakademik->id?'selected':'':''}}
						<option value="{{$thakademik->id}}" {{$select}}>{{$thakademik->kode}}</option>
					@endforeach
				</select>
			
				@if ($errors->has('th_akademik_id'))
					<span class="help-block">
					<strong>{{ $errors->first('th_akademik_id') }}</strong></span>
				@endif	
			</div>
		</div>
	</div>

	<div class="form-group{{ $errors->has('th_angkatan_id') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label text-danger">Tahun Angkatan:</label>
			<div class="col-sm-2">
				<select class="form-control" name="th_angkatan_id" id="th_angkatan_id" required>
					<option value="">-Pilih-</option>
					@foreach($list_thangkatan as $thangkatan)
						{{$select = old('th_angkatan_id')==$thangkatan->id?'selected':''}}
						{{$select = !empty($data->th_angkatan_id)?$data->th_angkatan_id==$thangkatan->id?'selected':'':''}}
						<option value="{{$thangkatan->id}}" {{$select}}>{{substr($thangkatan->kode,0,4)}}</option>
					@endforeach
				</select>

				@if ($errors->has('th_angkatan_id'))
					<span class="help-block">
					<strong>{{ $errors->first('th_angkatan_id') }}</strong></span>
				@endif
			</div>
		</div>
	</div>

	<div class="form-group{{ $errors->has('prodi_id') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label text-danger">Program Studi:</label>
			<div class="col-sm-3">
				<select class="form-control" name="prodi_id" id="prodi_id" required>
					<option value="">-Pilih-</option>
					@foreach($list_prodi as $prodi)
						{{$select = old('prodi_id')==$prodi->id?'selected':''}}
						{{$select = !empty($data->prodi_id)?$data->prodi_id==$prodi->id?'selected':'':''}}
						<option value="{{$prodi->id}}" {{$select}}>{{$prodi->nama}}</option>
					@endforeach
				</select>

				@if ($errors->has('prodi_id'))
					<span class="help-block">
					<strong>{{ $errors->first('prodi_id') }}</strong></span>
				@endif
			</div>
		</div>
	</div>

	<div class="form-group{{ $errors->has('kelas_id') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label text-danger">Kelas:</label>
			<div class="col-sm-2">
				<select class="form-control" name="kelas_id" id="kelas_id" required>
					<option value="">-Pilih-</option>
					@foreach($list_kelas as $kelas)
						{{$select = old('kelas_id')==$kelas->id?'selected':''}}
						{{$select = !empty($data->kelas_id)?$data->kelas_id==$kelas->id?'selected':'':''}}
						<option value="{{$kelas->id}}" {{$select}}>{{$kelas->nama}}</option>
					@endforeach
				</select>

				@if ($errors->has('kelas_id'))
					<span class="help-block">
					<strong>{{ $errors->first('kelas_id') }}</strong></span>
				@endif
			</div>
		</div>
	</div>

	<div class="form-group{{ $errors->has('form_schadule_id') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Formulir:</label>
			<div class="col-sm-3">
				<select class="form-control" name="form_schadule_id" id="form_schadule_id" required>
					<option value="">-Pilih-</option>
					@foreach($list_form_schadule as $form_schadule)
						{{$select = old('form_schadule_id')==$form_schadule->id?'selected':''}}
						{{$select = !empty($data->form_schadule_id)?$data->form_schadule_id==$form_schadule->id?'selected':'':''}}
						<option value="{{$form_schadule->id}}" {{$select}}>{{$form_schadule->nama}}</option>
					@endforeach
				</select>
			
				@if ($errors->has('form_schadule_id'))
					<span class="help-block">
					<strong>{{ $errors->first('form_schadule_id') }}</strong></span>
				@endif
			</div>
		</div>
	</div>

	<div class="form-group{{ $errors->has('nama') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label text-danger">Nama Tagihan:</label>
			<div class="col-sm-5">
				{!! Form::text('nama',null,['class' => 'form-control','id'=>'nama',
				'required'=>'true','autofocus' => 'true']) !!}
				
				@if ($errors->has('nama'))
					<span class="help-block">
					<strong>{{ $errors->first('nama') }}</strong></span>
				@endif
			</div>
		</div>
	</div>

	<div class="form-group{{ $errors->has('jumlah') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label text-danger">Jumlah:</label>
			<div class="col-sm-2">
				{!! Form::number('jumlah',null,['class' => 'form-control text-right','id'=>'jumlah']) !!}
				@if ($errors->has('jumlah'))
					<span class="help-block">
					<strong>{{ $errors->first('jumlah') }}</strong></span>
				@endif
			</div>
		</div>
	</div>

	<div class="form-group{{ $errors->has('x_sks') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">X SKS:</label>
			<div class="col-sm-2">
				<div id="switchers-colors-square" class="form-group-margin">
				<input type="checkbox" name="x_sks" 
				id="x_sks" data-class="switcher-success" {{!empty($data->x_sks)?$data->x_sks=='Y'?'checked':'':''}}></div>

				@if ($errors->has('x_sks'))
					<span class="help-block">
					<strong>{{ $errors->first('x_sks') }}</strong></span>
				@endif
			</div>
		</div>
	</div>
</div>

<div class="panel-footer">
	<div class="col-sm-offset-2">
	<button type="submit" name="save" id="save" class="btn btn-success btn-flat">
	<i class="fa fa-floppy-o"></i> Simpan</button></div>
</div>

@push('demo')
<script>
init.push(function () {
	$('#switchers-colors-square > input').switcher({ theme: 'square' });
	$("#form_schadule_id").select2({
		allowClear: true,
		placeholder: "Pilih Kota"
	});
});
</script>
@endpush
