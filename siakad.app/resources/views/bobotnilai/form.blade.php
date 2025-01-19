<div class="panel-body no-padding-hr">
	<div class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Tahun Akademik:</label>
		<div class="col-sm-2">
			<select class="form-control" name="th_akademik_id" id="th_akademik_id">
			<option value="">-Pilih-</option>
			@foreach($list_thakademik as $row)
				{{$select = !empty($data->th_akademik_id)? $data->th_akademik_id==$row->id? 'selected':'':''}}
				<option value="{{$row->id}}" {{$select}}>{{$row->kode}}</option>
			@endforeach
			</select>

			@if ($errors->has('th_akademik_id'))
				<span class="help-block">
                <strong>{{ $errors->first('th_akademik_id') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('nilai_min') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Nilai Minimum:</label>
		<div class="col-sm-2">
			{!! Form::number('nilai_min',null,['class' => 'form-control','id'=>'nilai_min','required'=>'true']) !!}
			@if ($errors->has('nilai_min'))
				<span class="help-block">
				<strong>{{ $errors->first('nilai_min') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('nilai_max') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Nilai Maximum:</label>
		<div class="col-sm-2">
			{!! Form::number('nilai_max',null,['class' => 'form-control','id'=>'nilai_max','required'=>'true']) !!}
			@if ($errors->has('nilai_max'))
				<span class="help-block">
				<strong>{{ $errors->first('nilai_max') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('nilai_huruf') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Nilai Huruf:</label>
		<div class="col-sm-1">
			{!! Form::text('nilai_huruf',null,['class' => 'form-control','id'=>'nilai_huruf','required'=>'true']) !!}
			<span class="text-info">[A+,A,A-,B+,B,B-,C+,C,C-,D]</span>
			@if ($errors->has('nilai_huruf'))
				<span class="help-block">
				<strong>{{ $errors->first('nilai_huruf') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('nilai_bobot') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Nilai Bobot:</label>
		<div class="col-sm-2">
			{!! Form::number('nilai_bobot',null,['class' => 'form-control','id'=>'nilai_bobot','required'=>'true']) !!}
			<span class="text-info">0,1,2,3,4</span>
			@if ($errors->has('nilai_bobot'))
				<span class="help-block">
				<strong>{{ $errors->first('nilai_bobot') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('predikat') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Predikat:</label>
		<div class="col-sm-2">
			{!! Form::text('predikat',null,['class' => 'form-control','id'=>'predikat','required'=>'true']) !!}
			@if ($errors->has('predikat'))
				<span class="help-block">
				<strong>{{ $errors->first('predikat') }}</strong></span>
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
</script>
@endpush
