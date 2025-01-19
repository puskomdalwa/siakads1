	<div class="panel-body no-padding-hr">

	<div class="form-group{{ $errors->has('username') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">Username:</label>
		<div class="col-sm-4">
			{!! Form::text('username',null,['class' => 'form-control','id'=>'username','required'=>'true','autofocus' => 'true']) !!}
			@if ($errors->has('username'))
				<span class="help-block"><strong>{{ $errors->first('username') }}</strong></span>
			@endif
		</div>
	</div></div>

	<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">Name:</label>
		<div class="col-sm-5">
			{!! Form::text('name',null,['class' => 'form-control','id'=>'name','required'=>'true']) !!}
			@if ($errors->has('name'))
				<span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
			@endif
		</div>
	</div></div>

	<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">Email:</label>
		<div class="col-sm-5">
			{!! Form::email('email',null,['class' => 'form-control','id'=>'email','required'=>'true']) !!}
			@if ($errors->has('email'))
				<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
			@endif
		</div>
	</div></div>

	<div class="form-group{{ $errors->has('level_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">Level:</label>
		<div class="col-sm-2">
			{!! Form::select('level_id',$list_level,null,['class' => 'form-control','id'=>'level_id','required'=>'true']) !!}
			@if ($errors->has('level_id'))
				<span class="help-block"><strong>{{ $errors->first('level_id') }}</strong></span>
			@endif
		</div>

		<label class="col-sm-1 control-label">Kelamin:</label>
		<div class="col-sm-2">
			<select class="form-control" name="jekel_id" id="jekel_id">
			@foreach($list_jekel as $jekel)
				{{$select = (old('jekel_id')==$jekel->id?'selected':(!empty($data->jk_id)?($data->jk_id==$jekel->id?'selected':''):''))}}
				<option value="{{$jekel->id}}" {{ $select }} >{{$jekel->nama}}</option>
			@endforeach
			</select>
						
			@if ($errors->has('jekel_id'))
				<span class="help-block"><strong>{{ $errors->first('jekel_id') }}</strong></span>
			@endif
		</div>
	</div></div>

	<div class="form-group{{ $errors->has('prodi_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">Program Studi:</label>
		<div class="col-sm-5">
			<select class="form-control" name="prodi_id" id="prodi_id">
			<option value="">-Tidak Ada-</option>
			@foreach($list_prodi as $prodi)
				{{$select = (old('prodi_id')==$prodi->id?'selected':(!empty($data->prodi_id)?($data->prodi_id==$prodi->id?'selected':''):''))}}
				<option value="{{$prodi->id}}" {{ $select }} >{{$prodi->nama}}</option>
			@endforeach
			</select>

			@if ($errors->has('prodi_id'))
				<span class="help-block"><strong>{{ $errors->first('prodi_id') }}</strong></span>
			@endif
		</div>
	</div></div>

	<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t" >
	<div class="row">
		<label for="password" class="col-md-2 control-label">Password</label>
		<div class="col-md-5">
			<input id="password" type="password" class="form-control" name="password">
			@if ($errors->has('password'))
				<span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
			@endif
		</div>
	</div></div>

	<div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label for="password-confirm" class="col-md-2 control-label">Confirm Password</label>
		<div class="col-md-5">
			<input id="password-confirm" type="password" class="form-control" name="password_confirmation">
		</div>
	</div></div>

</div>
	
<div class="panel-footer">
<div class="col-sm-offset-2">
	<button type="submit" name="save" id="save" class="btn btn-success btn-flat">
	<i class="fa fa-floppy-o"></i> Simpan</button>
</div></div>
