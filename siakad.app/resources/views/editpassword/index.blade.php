@extends('layouts.app')
@section('title',$title)

@section('content')
<div class="panel panel-danger panel-dark">
	<div class="panel-heading">
	<span class="panel-title">@yield('title')</span></div>

	{!! Form::model($data,['route' => [$redirect.'.update',$data->id],
		'method'=>'PATCH','class'=>'form-horizontal form-bordered']) !!}

	<div class="panel-body no-padding-hr">
		<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} 
			no-margin-hr panel-padding-h no-padding-t no-border-t">
			<div class="row">
				<label class="col-sm-2 control-label">Password:</label>
				<div class="col-sm-3">
					<input type="password" name="password" class="form-control">
					@if ($errors->has('password'))
						<span class="help-block">
						<strong>{{ $errors->first('password') }}</strong></span>
					@endif
				</div>
			</div>
		</div>

		<div class="form-group{{ $errors->has('password-confirm') ? ' has-error' : '' }} 
			no-margin-hr panel-padding-h no-padding-t no-border-t">
			<div class="row">
				<label class="col-sm-2 control-label">Confirm Password:</label>
				<div class="col-sm-3">
					<input type="password" name="password_confirmation" class="form-control">
					@if ($errors->has('password-confirm'))
						<span class="help-block">
						<strong>{{ $errors->first('password-confirm') }}</strong></span>
					@endif
				</div>
			</div>
		</div>
	</div>

	<div class="panel-footer">
    <div class="col-sm-offset-2">
        <button type="submit" name="save" id="save" class="btn btn-success btn-flat">
		<i class="fa fa-floppy-o"></i> Update</button>
    </div></div>
	
	{!! Form::close() !!}
</div>
@endsection
