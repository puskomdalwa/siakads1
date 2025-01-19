<div class="panel-body no-padding-hr">
	<div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Tahun Akademik:</label>
		<div class="col-sm-2">
			{!! Form::hidden('th_akademik_id',$th_akademik->id,['class' => 'form-control','id'=>'th_akademik_id','readonly'=>true]) !!}
			{!! Form::text('th_akademik_kode',$th_akademik->kode,['class' => 'form-control','id'=>'th_akademik_kode','readonly'=>true]) !!}
		</div>
    </div></div>

	<div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Program Studi:</label>
		<div class="col-sm-4">
			{!! Form::hidden('prodi_id',$prodi->id,['class' => 'form-control','id'=>'prodi_id','readonly'=>true]) !!}
			{!! Form::text('nama_prodi',$prodi->nama,['class' => 'form-control','id'=>'nama_prodi','readonly'=>true]) !!}
		</div>
    </div></div>

	<div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Kurikulum:</label>
		<div class="col-sm-4">
			{!! Form::hidden('kurikulum_id',$kurikulum->id,['class' => 'form-control','id'=>'kurikulum_id','readonly'=>true]) !!}
			{!! Form::text('nama_kurikulum',$kurikulum->nama,['class' => 'form-control','id'=>'nama_kurikulum','readonly'=>true]) !!}
		</div>
    </div></div>

	<div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Matakuliah:</label>
		<div class="col-sm-6">
			{!! Form::hidden('kurikulum_matakuliah_id',$kurikulum_matakuliah->id,['class' => 'form-control text-right','id'=>'kurikulum_matakuliah_id','readonly'=>true]) !!}
			{!! Form::text('matakuliah',$kurikulum_matakuliah->matakuliah->kode.' - '.$kurikulum_matakuliah->matakuliah->nama.' ('.$kurikulum_matakuliah->matakuliah->sks.' SKS)',['class' => 'form-control','id'=>'matakuliah','readonly'=>true]) !!}
		</div>
		
		<label class="col-sm-1 control-label">Semster:</label>
		<div class="col-sm-1">
			{!! Form::text('smt',$kurikulum_matakuliah->matakuliah->smt,['class' => 'form-control','id'=>'smt','readonly'=>true]) !!}
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('kelas_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Kelas:</label>
		<div class="col-sm-3">
			@php
			$list_kelas = App\Ref::where('table','Kelas')->get();
			@endphp

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

				{{-- {{$select = (old('kelas_id')==$kelas->id?'selected':(!empty($data->kelas_id)?($data->kelas_id==$kelas->id?'selected':''):''))}} --}}
				<option value="{{$kelas->id}}" {{$select}}>{{$kelas->nama}}</option>
			@endforeach
			</select>
			
			@if ($errors->has('kelas_id'))
				<span class="help-block">
                <strong>{{ $errors->first('kelas_id') }}</strong>
				</span>
			@endif
		</div>
		
		<label class="col-sm-2{{ $errors->has('kelompok_id') ? ' has-error' : '' }} control-label">Kelompok:</label>
		<div class="col-sm-3">
			@php
			$list_kelompok = App\Ref::where('table','Kelompok')->get();
			@endphp
			
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
				
				{{-- {{$select = (old('kelompok_id')==$kelompok->id?'selected':(!empty($data->kelompok_id)?($data->kelompok_id==$kelompok->id?'selected':''):''))}} --}}
				<option value="{{$kelompok->id}}" {{$select}}>{{$kelompok->nama}}</option>
			@endforeach
			</select>
		
			@if ($errors->has('kelompok_id'))
				<span class="help-block">
				<strong>{{ $errors->first('kelompok_id') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('jamkul_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Jam:</label>
		<div class="col-sm-3">
			@php
			$list_jamkuliah = App\Ref::where('table','JamKuliah')->get();
			@endphp

			<select class="form-control" name="jamkul_id" id="hari_id" required>
			<option value="">-Pilih-</option>
			@foreach($list_jamkuliah as $jamkul)
				@if(!empty($data->jamkul_id))
					@if($data->jamkul_id==$jamkul->id)
						@php
						$select = 'selected';
						@endphp
					@else
						@php
						$select = '';
						@endphp
					@endif
				@else
					@if(old('jamkul_id')==$jamkul->id)
						@php
						$select = 'selected';
						@endphp
					@else
						@php
						$select = '';
						@endphp  
					@endif
				@endif
			
				{{-- {{$select = (old('jamkul_id')==$jamkul->id?'selected':(!empty($data->jamkul_id)?($data->jamkul_id==$jamkul->id?'selected':''):''))}} --}}
				<option value="{{$jamkul->id}}" {{$select}}>{{$jamkul->nama}}</option>
			@endforeach
			</select>
			
			@if ($errors->has('hari_id'))
				<span class="help-block">
                <strong>{{ $errors->first('hari_id') }}</strong>
				</span>
			@endif
		</div>
	</div></div>
	
	<div class="form-group{{ $errors->has('hari_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Hari:</label>
		<div class="col-sm-3">
			@php
			$list_hari = App\Ref::where('table','Hari')->get();
			@endphp

			<select class="form-control" name="hari_id" id="hari_id" required>
			<option value="">-Pilih-</option>
			@foreach($list_hari as $hari)
				@if(!empty($data->hari_id))
					@if($data->hari_id==$hari->id)
						@php
						$select = 'selected';
						@endphp
					@else
						@php
						$select = '';
						@endphp
					@endif
				@else
					@if(old('hari_id')==$hari->id)
						@php
						$select = 'selected';
						@endphp
					@else
						@php
						$select = '';
						@endphp  
					@endif
				@endif
			
				{{-- {{$select = (old('hari_id')==$hari->id?'selected':(!empty($data->hari_id)?($data->hari_id==$hari->id?'selected':''):''))}} --}}
				<option value="{{$hari->id}}" {{$select}}>{{$hari->nama}}</option>
			@endforeach
			</select>
			
			@if ($errors->has('hari_id'))
				<span class="help-block">
                <strong>{{ $errors->first('hari_id') }}</strong>
				</span>
			@endif
		</div>
		
		<label class="col-sm-2 control-label">Ruang:</label>
		<div class="col-sm-3">
			@php
			$list_ruang_kelas = App\Ref::where('table','RuangKelas')->get();
			@endphp
			
			<select class="form-control" name="ruang_kelas_id" id="ruang_kelas_id" required>
			<option value="">-Pilih-</option>
			@foreach($list_ruang_kelas as $ruang_kelas)
				@if(!empty($data->ruang_kelas_id))
					@if($data->ruang_kelas_id==$ruang_kelas->id)
						@php
						$select = 'selected';
						@endphp
					@else
						@php
						$select = '';
						@endphp
					@endif
				@else
					@if(old('ruang_kelas_id')==$ruang_kelas->id)
						@php
						$select = 'selected';
						@endphp
					@else
						@php
						$select = '';
						@endphp  
					@endif
				@endif
				{{-- {{$select = (old('ruang_kelas_id')==$ruang_kelas->id?'selected':(!empty($data->ruang_kelas_id)?($data->ruang_kelas_id==$ruang_kelas->id?'selected':''):''))}} --}}
				<option value="{{$ruang_kelas->id}}" {{$select}}>{{$ruang_kelas->nama}}</option>
			@endforeach
       		</select>
		
			@if ($errors->has('ruang_kelas_id'))
				<span class="help-block">
				<strong>{{ $errors->first('ruang_kelas_id') }}</strong>
				</span>
			@endif
		</div>
	</div></div>

	<div class="form-group{{ $errors->has('jam_mulai') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Jam Mulai:</label>
		<div class="col-sm-2">
			<div class="input-group date" id="bs-datepicker-component">
				{!! Form::text('jam_mulai',null,['class' => 'form-control text-center','id'=>'jam_mulai','required'=>true]) !!}
				<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
			</div>

			@if ($errors->has('jam_mulai'))
				<span class="help-block">
                <strong>{{ $errors->first('jam_mulai') }}</strong>
				</span>
			@endif
		</div>
	  
		<label class="col-sm-3 control-label">Jam Selesai:</label>
		<div class="col-sm-2">
			<div class="input-group date" id="bs-datepicker-component">
				{!! Form::text('jam_selesai',null,['class' => 'form-control text-center','id'=>'jam_selesai','required'=>true]) !!}
				<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
			</div>
			
			@if ($errors->has('jam_selesai'))
				<span class="help-block">
                <strong>{{ $errors->first('jam_selesai') }}</strong>
				</span>
			@endif
		</div>
    </div></div>
	
	<div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Dosen:</label>
		<div class="col-sm-7">
			@php
			$list_dosen = App\Dosen::get();
			@endphp
			
			<select class="form-control" name="dosen_id" id="dosen_id" required placeholder="Cari Nama Dosen..">
			<option value="">Cari Nama Dosen....</option>
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
				
				{{-- {{$select = (old('dosen_id')==$dosen->id?'selected':(!empty($data->dosen_id)?($data->dosen_id==$dosen->id?'selected':''):''))}} --}}
				<option value="{{$dosen->id}}" {{$select}}>{{$dosen->nama}}</option>
			@endforeach
			</select>
			
			@if ($errors->has('dosen_id'))
				<span class="help-block">
                <strong>{{ $errors->first('dosen_id') }}</strong>
				</span>
			@endif
		</div>
	</div></div>

	<div class="panel-footer">
	<div class="col-sm-offset-2">
		<button type="submit" name="save" id="save" class="btn btn-success btn-flat">
		<i class="fa fa-floppy-o"></i> Simpan</button>
	</div></div>
</div>

@push('demo')
<script>
	init.push(function () {
	var options2 = {
		minuteStep: 1,
		showSeconds: true,
		showMeridian: false,
		showInputs: false,
		orientation: $('body').hasClass('right-to-left') ? { x: 'right', y: 'auto'} : { x: 'auto', y: 'auto'}
	}
	$('#jam_mulai').timepicker(options2);
	$('#jam_selesai').timepicker(options2);

	$("#dosen_id").select2({
		allowClear: true,
		placeholder: "Pilih Dosen"
	});
});
</script>
@endpush
