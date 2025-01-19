@extends('layouts.app')
@section('title',$title)

@section('content')
<div class="panel panel-danger panel-dark">
	<div class="panel-heading">
	<span class="panel-title">@yield('title')</span></div>

	<div class="panel-body no-padding-hr">
    <div class="note note-danger">
		<h2 class="note-title">Perhatian !!!</h2>
		<h4>Mohon Maaf, @yield('title') Sudah <b>DITUTUP !!! </b> 
		&nbsp;&nbsp; ==>> &nbsp;&nbsp Per Tanggal <b>{{tgl_str($buka_form->tgl_selesai)}}</b></h4>
	</div> </div>
</div>
@endsection
