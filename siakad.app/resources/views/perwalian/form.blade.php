<div class="alert alert-dark">Catatan : Judul Warna Merah jangan di ubah.</div>

<div class="panel-body no-padding-hr">
	<input type="hidden" name="perwalian_id" id="perwalian_id" value="{{!empty($data->id)?$data->id:null}}">
	<div class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label {{ !empty($data) ? 'text-danger' : '' }} ">Tahun Angkatan:</label>
			<div class="col-sm-2">
				<select class="form-control" name="th_akademik_id" id="th_akademik_id" required>
				<option value="">-Pilih-</option>
				@foreach($list_thakademik as $thakademik)
					@if(!empty($data->th_akademik_id))
						@if($data->th_akademik_id==$thakademik->id)
							@php
							$select = 'selected';
							@endphp
						@else
							@php
							$select = '';
							@endphp
						@endif
					@else
						@if(old('th_akademik_id')==$thakademik->id)
							@php
							$select = 'selected';
							@endphp
						@else
							@php
							$select = '';
							@endphp  
						@endif
					@endif
					
					{{-- {{$select = !empty($data->th_akademik_id)?$data->th_akademik_id==$thakademik->id?'selected':'':old('th_akademik_id')==$thakademik->id?'selected':''}} --}}
					<option value="{{$thakademik->id}}" {{$select}}>{{$thakademik->kode}}</option>
				@endforeach
				</select>
				
				@if ($errors->has('th_akademik_id'))
				<span class="help-block">
				<strong>{{ $errors->first('th_akademik_id') }}</strong></span>
				@endif
			</div>

			<label class="col-sm-2 control-label {{ !empty($data) ? 'text-danger' : '' }}">Program Studi:</label>
			<div class="col-sm-4">
				<select class="form-control" name="prodi_id" id="prodi_id" required>
				<option value="">-Pilih-</option>
				@foreach($list_prodi as $prodi)
					@if(!empty($data->prodi_id))
						@if($data->prodi_id==$prodi->id)
							@php
							$select = 'selected';
							@endphp
						@else
							@php
							$select = '';
							@endphp
						@endif
					@else
						@if(old('prodi_id')==$prodi->id)
							@php
							$select = 'selected';
							@endphp
						@else
							@php
							$select = '';
							@endphp  
						@endif
					@endif
					
					{{-- {{$select = !empty($data->prodi_id)?$data->prodi_id==$prodi->id?'selected':'':old('prodi_id')==$prodi->id?'selected':''}} --}}
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

	<div class="form-group{{ $errors->has('prodi_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label {{ !empty($data) ? 'text-danger' : '' }}">Kelas:</label>
			<div class="col-sm-2">
				<select class="form-control" name="kelas_id" id="kelas_id" required>
				<option value="">-Pilih-</option>
				@foreach($list_kelas as $kelas)
					@if(!empty($data->kelas_id))
						@if($data->kelas_id==$kelas->id)
							@php
							$select = 'selected';
							@endphp
						@else
							@php
							$select = '';
							@endphp
						@endif
					@else
						@if(old('kelas_id')==$kelas->id)
							@php
							$select = 'selected';
							@endphp
						@else
							@php
							$select = '';
							@endphp  
						@endif
					@endif
					
					{{-- {{$select = !empty($data->kelas_id)?$data->kelas_id==$kelas->id?'selected':'':old('kelas_id')==$kelas->id?'selected':''}} --}}
					<option value="{{$kelas->id}}" {{$select}}>{{$kelas->nama}}</option>
				@endforeach
				</select>

				@if ($errors->has('kelas_id'))
				<span class="help-block">
				<strong>{{ $errors->first('kelas_id') }}</strong></span>
				@endif
			</div>

			<label class="col-sm-2 control-label {{ !empty($data) ? 'text-danger' : '' }}">Kelompok:</label>
			<div class="col-sm-2">
				<select class="form-control" name="kelompok_id" id="kelompok_id" required>
				<option value="">-Pilih-</option>
				@foreach($list_kelompok as $kelompok)
					@if(!empty($data->kelompok_id))
						@if($data->kelompok_id==$kelompok->id)
							@php
							$select = 'selected';
							@endphp
						@else
							@php
							$select = '';
							@endphp
						@endif
					@else
						@if(old('kelompok_id')==$kelompok->id)
							@php
							$select = 'selected';
							@endphp
						@else
							@php
							$select = '';
							@endphp  
						@endif
					@endif
					
					{{-- {{$select = !empty($data->kelompok_id)?$data->kelompok_id==$kelompok->id?'selected':'':old('kelompok_id')==$kelompok->id?'selected':''}} --}}
					<option value="{{$kelompok->id}}" {{$select}}>{{$kelompok->kode}}</option>
				@endforeach
				</select>

				@if ($errors->has('kelompok_id'))
				<span class="help-block">
				<strong>{{ $errors->first('kelompok_id') }}</strong></span>
				@endif
			</div>
		</div>
	</div>

	<div class="form-group{{ $errors->has('dosen_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Dosen Wali:</label>
			<div class="col-sm-6">
				<select class="form-control" name="dosen_id" id="dosen_id" required>
				<option value="">-Pilih-</option>
				@foreach($list_dosen as $dosen)
					@if(!empty($data->dosen_id))
						@if($data->dosen_id==$dosen->id)
							@php
							$select = 'selected';
							@endphp
						@else
							@php
							$select = '';
							@endphp
						@endif
					@else
						@if(old('dosen_id')==$dosen->id)
							@php
							$select = 'selected';
							@endphp
						@else
							@php
							$select = '';
							@endphp  
						@endif
					@endif
				
					{{-- {{$select = !empty($data->dosen_id)?$data->dosen_id==$dosen->id?'selected':'':old('dosen_id')==$dosen->id?'selected':''}} --}}
					<option value="{{$dosen->id}}" {{$select}}>{{$dosen->nama}}</option>
				@endforeach
				</select>
			
				@if ($errors->has('dosen_id'))
				<span class="help-block">
				<strong>{{ $errors->first('dosen_id') }}</strong></span>
				@endif
			</div>
		</div>
	</div>
</div>

<div class="panel-footer">
<div class="col-sm-offset-2">
<button type="submit" name="save" id="save" class="btn btn-success btn-flat">
<i class="fa fa-floppy-o"></i> Simpan</button>
</div></div>

@if ($errors->has('cek_list'))
<div class="alert alert-danger alert-dismissable">
<strong>{{ $errors->first('cek_list') }}</strong>
</div>
@endif
@include($folder.'.datamhs')

@push('demo')
<script>
init.push(function () {
	$("#dosen_id").select2({
		allowClear: true,
		placeholder: "Pilih Dosen"
	});
});
</script>
@endpush
