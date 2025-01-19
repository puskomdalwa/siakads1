<div class="panel-body no-padding-hr">
	<div class="form-group{{ $errors->has('nim') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">NIM:</label>
			<div class="col-sm-2">
				{!! Form::text('nim',$nim,['class' => 'form-control',
				'id'=>'nim','required'=>'true','readonly' => 'true']) !!}
			
				@if ($errors->has('nim'))
					<span class="help-block">
					<strong>{{ $errors->first('nim') }}</strong></span>
				@endif
			</div>
			
			<label class="col-sm-2 control-label">Nama Mahasiswa:</label>
			<div class="col-sm-4">
				{!! Form::text('nama',$data_mhs['nama_mhs'],
				['class' => 'form-control','id'=>'nama','required'=>'true','readonly' => 'true']) !!}
				
				@if ($errors->has('nama'))
				<span class="help-block">
				<strong>{{ $errors->first('nama') }}</strong></span>
				@endif
			</div>
		</div>
	</div>

	<div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Jenis Kelamin:</label>
		<div class="col-sm-2">
			{!! Form::text('jk',$data_mhs['jk'],
			['class' => 'form-control','id'=>'jk','required'=>'true','readonly' => 'true']) !!}
		</div>
		
		<label class="col-sm-2 control-label">Status:</label>
		<div class="col-sm-2">
			{!! Form::text('status',$data_mhs['status'],
			['class' => 'form-control','id'=>'status','required'=>'true','readonly' => 'true']) !!}
		</div>
    </div></div>

	<div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">Tahun Angkatan:</label>
		<div class="col-sm-1">
			{!! Form::text('th_angkatan',$data_mhs['th_angkatan'],
			['class' => 'form-control','id'=>'th_angkatan','required'=>'true','readonly' => 'true']) !!}
		</div>
		
		<label class="col-sm-3 control-label">Program Studi:</label>
		<div class="col-sm-3">
			{!! Form::text('prodi',$data_mhs['prodi'],
			['class' => 'form-control','id'=>'prodi','required'=>'true','readonly' => 'true']) !!}
		</div>
    </div></div>

	<div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Kelas:</label>
		<div class="col-sm-2">
			{!! Form::text('kelas',$data_mhs['kelas'],
			['class' => 'form-control','id'=>'kelas','required'=>'true','readonly' => 'true']) !!}
		</div>
			
		<label class="col-sm-2 control-label">Kelompok:</label>
		<div class="col-sm-1">
			{!! Form::text('kelompok',$data_mhs['kelompok'],
			['class' => 'form-control','id'=>'kelompok','required'=>'true','readonly' => 'true']) !!}
		</div>
	</div></div>

	<div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Keuangan:</label>
		<div class="col-sm-5">
			{!! Form::text('keuangan',$data_mhs['keuangan'],
			['class' => 'form-control','id'=>'keuangan','required'=>'true','readonly' => 'true']) !!}
		</div>
	</div></div>

	<div class="form-group{{ $errors->has('jml_sks') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Total SKS:</label>
			<div class="col-sm-1">
				{!! Form::text('jml_sks',@$data->jml_sks,
				['class' => 'form-control','id'=>'jml_sks','required'=>'true']) !!}
				
				@if ($errors->has('jml_sks'))
					<span class="help-block">
					<strong>{{ $errors->first('jml_sks') }}</strong></span>
				@endif
			</div>
		  
			<label class="col-sm-1 control-label">IPK:</label>
			<div class="col-sm-1">
				{!! Form::text('ipk',@$data->ipk,
				['class' => 'form-control','id'=>'ipk','required'=>'true']) !!}
				
				@if ($errors->has('ipk'))
					<span class="help-block">
					<strong>{{ $errors->first('ipk') }}</strong></span>
				@endif
			</div>
		</div>
	</div>

	<div class="form-group{{ $errors->has('judul_skripsi') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Judul Skripsi:</label>
			<div class="col-sm-7">
				{!! Form::textarea('judul_skripsi',@$data->judul_skripsi,
				['class' => 'form-control','id'=>'judul_skripsi','required'=>'true']) !!}

				@if ($errors->has('judul_skripsi'))
					<span class="help-block">
					<strong>{{ $errors->first('judul_skripsi') }}</strong></span>
				@endif
			</div>
		</div>
	</div>

	<div class="form-group{{ $errors->has('ukuran_toga') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Ukuran Toga:</label>
			<div class="col-sm-2">
				<select class="form-control" name="ukuran_toga" id="ukuran_toga" required>
				<option value="">-Pilih-</option>
				@foreach($list_toga as $toga)
					{{$select = (old('ukuran_toga')==$toga->kode?'selected':(!empty($data->ukuran_toga)?
					($data->ukuran_toga==$toga->kode?'selected':null):null))}}
					<option value="{{$toga->kode}}" {{$select}}>{{$toga->kode}}</option>
				@endforeach
				</select>
		
				@if ($errors->has('ukuran_toga'))
					<span class="help-block">
					<strong>{{ $errors->first('ukuran_toga') }}</strong></span>
				@endif
			</div>
		</div>
	</div>

	<div class="form-group{{ $errors->has('motto') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Motto:</label>
			<div class="col-sm-5">
				{!! Form::text('motto',@$data->motto,['class' => 'form-control','id'=>'motto']) !!}
				
				@if ($errors->has('motto'))
					<span class="help-block">
					<strong>{{ $errors->first('motto') }}</strong></span>
				@endif
			</div>
		</div>
	</div>

	<div class="panel-footer">
	<div class="col-sm-offset-2">
		<button type="submit" name="save" id="save" class="btn btn-success btn-flat">
		<i class="fa fa-floppy-o"></i> Simpan</button>
	</div></div>
</div>

@push('demo')
<script>
init.push(function (){
});
</script>
@endpush

@push('scripts')
<script type="text/javascript"></script>
@endpush
