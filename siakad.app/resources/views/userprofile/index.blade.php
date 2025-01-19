@extends('layouts.app')
@section('title',$title)

@section('content')
<div class="row">
	<div class="col-md-8">
		<div class="panel panel-danger panel-dark">
			<div class="panel-heading">
				<span class="panel-title">@yield('title')</span>
			</div>

			{!! Form::model($data,['route' => [$redirect.'.update',$data->id],
				'files'=>true,'method'=>'PATCH','class'=>'form-horizontal']) !!}

			<div class="panel-body no-padding-hr">
				<div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
					<div class="row">
						<label class="col-sm-2 control-label">Username:</label>
						<div class="col-sm-10">
							{!! Form::text('username',null,['class' => 'form-control',
								'id'=>'username','required'=>'true','readonly' => 'true']) !!}
						</div>
					</div>
				</div>

				<div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
					<div class="row">
						<label class="col-sm-2 control-label">Nama:</label>
						<div class="col-sm-10">
							{!! Form::text('name',null,['class' => 'form-control','id'=>'name',
								'required'=>'true','readonly' => 'true']) !!}
						</div>
					</div>
				</div>

				<div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
					<div class="row">
						<label class="col-sm-2 control-label">Level:</label>
						<div class="col-sm-10">
							{!! Form::text('level',$data->level->level,
								['class' => 'form-control','id'=>'level','required'=>'true','readonly' => 'true']) !!}
						</div>
					</div>
				</div>

				<div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
					<div class="row">
						<label class="col-sm-2 control-label">Program Studi:</label>
						<div class="col-sm-10">
							{!! Form::text('prodi',@$data->prodi->nama,
								['class' => 'form-control','id'=>'prodi','required'=>'true','readonly' => 'true']) !!}
						</div>
					</div>
				</div>
				
				<div class="form-group{{ $errors->has('nohp') ? ' has-error' : '' }} 
					no-margin-hr panel-padding-h no-padding-t no-border-t">
					<div class="row">
						<label class="col-sm-2 control-label">No Handphone:</label>
						<div class="col-sm-3">
							<input type="text" name="nohp" class="form-control">
							
							@if ($errors->has('nohp'))
								<span class="help-block">
								<strong>{{ $errors->first('nohp') }}</strong></span>
							@endif
						</div>
					</div>
				</div>
				
				<div class="form-group{{ $errors->has('image') ? ' has-error' : '' }} 
					no-margin-hr panel-padding-h no-padding-t no-border-t">
					<div class="row">
						<label class="col-sm-2 control-label">Picture:</label>
						<div class="col-sm-10">
							{!! Form::file('image',null,['class' => 'form-control',
								'id'=>'image','required'=>'true']) !!}
							
							@if ($errors->has('image'))
								<span class="help-block">
								<strong>{{ $errors->first('image') }}</strong>
								</span>
							@endif
						</div>
					</div>
				</div>
			</div>

			<div class="panel-footer text-center">
				<div class="col-sm-offset-2">
				<button type="submit" name="save" id="save" class="btn btn-success btn-flat">
				<i class="fa fa-floppy-o"></i> Update</button>
				</div>
			</div>
			
			{!! Form::close() !!}
		</div>
	</div>

	<div class="col-md-4">
		<div class="panel-heading">
		<span class="panel-title">Profile Picture</span>
		</div>
		
		@if(!empty($data->picture))
			<div class="panel-body text-center">
				{{-- <div class="panel profile-photo"> --}}
				<img src="{{ asset('picture_users/'.$data->picture) }}" alt="" class="img-thumbnail">
				{{-- </div> --}}
			</div>
		@endif 
		
		Ukuran Foto tidak boleh lebih dari 2Mb.
	</div>
</div> 
@endsection
