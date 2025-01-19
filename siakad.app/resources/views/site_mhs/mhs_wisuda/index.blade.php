@extends('layouts.app')
@section('title',$title)

@section('content')
<div class="panel panel-danger panel-dark">
	<div class="panel-heading">
		<span class="panel-title">@yield('title')</span>
	</div>

	{!! Form::open(['route' => $redirect.'.store',
		'class'=>'form-horizontal form-bordered','autocomplete'=>'off']) !!}
		@include($folder.'.form')
	{!! Form::close() !!}
</div>
@endsection

@push('scripts')
<script type="text/javascript"></script>
@endpush
