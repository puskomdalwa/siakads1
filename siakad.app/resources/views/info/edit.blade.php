@extends('layouts.app')
@section('title', 'Edit '.$title)

@section('content')
<div class="panel panel-danger panel-dark">
	<div class="panel-heading">
		<span class="panel-title">@yield('title')</span>
		<div class="panel-heading-controls">
		<a href="{{ url($redirect)}}" class="btn btn-sm btn-primary">
		<i class="fa fa-chevron-circle-left"></i> Kembali</a>
		</div>
	</div>
	
	{!! Form::model($data,['route' => [$redirect.'.update',$data->id],'method'=>'PATCH',
		'class'=>'form-horizontal','autocomplete'=>'off']) !!}
		@include($folder.'.form')
	{!! Form::close() !!}
</div>
@endsection
