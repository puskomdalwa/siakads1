<form class="form-horizontal form-borderd" >
	<div class="panel-body no-padding-hr">

	<div class="form-group has-success no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<!--
		<label class="col-sm-2 control-label">T.Akademik Aktif:</label>
		<div class="col-sm-1">
		{!! Form::hidden('th_akademik_id',$th_akademik->id,['class' => 'form-control','id'=>'th_akademik_id','required'=>'true','readonly' => 'true']) !!}
		{!! Form::text('th_akademik_kode',$th_akademik->kode,['class' => 'form-control','id'=>'th_akademik_kode','required'=>'true','readonly' => 'true']) !!}
		</div>
		-->
    </div></div>

	<div class="form-group{{ $errors->has('th_angkatan_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">T.Akademik Aktif:</label>
		<div class="col-sm-1">
			{!! Form::hidden('th_akademik_id',$th_akademik->id,['class' => 'form-control','id'=>'th_akademik_id','required'=>'true','readonly' => 'true']) !!}
			{!! Form::text('th_akademik_kode',$th_akademik->kode,['class' => 'form-control','id'=>'th_akademik_kode','required'=>'true','readonly' => 'true']) !!}
		</div>
		
		<label class="col-sm-1 control-label">T.Angkatan:</label>
		<div class="col-sm-2">
			<select class="form-control" name="th_angkatan_id" id="th_angkatan_id">
			<option value="">-Pilih-</option>
			@foreach($list_thangkatan as $row)
			<option value="{{$row->id}}">{{$row->kode}}</option>
			@endforeach
			</select>

			@if ($errors->has('th_angkatan_id'))
			<span class="help-block">
			<strong>{{ $errors->first('th_angkatan_id') }}</strong>
			</span>
			@endif
		</div>

		<label class="col-sm-1 control-label">Prodi:</label>
		<div class="col-sm-4">
			<select class="form-control" name="prodi_id" id="prodi_id">
			@if(empty($prodi_id))
			<option value="">-Pilih-</option>
			@endif
			@foreach($list_prodi as $prodi)
			<option value="{{$prodi->id}}">{{$prodi->nama}}</option>
			@endforeach
			</select>
			
			@if ($errors->has('prodi_id'))
			<span class="help-block">
			<strong>{{ $errors->first('prodi_id') }}</strong>
			</span>
			@endif
		</div>
    </div></div>
</div>
</form>
