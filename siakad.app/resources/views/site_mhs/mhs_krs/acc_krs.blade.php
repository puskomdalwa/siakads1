@extends('layouts.app')
@section('title',$title)

@section('content')
<div class="panel panel-danger panel-dark">
	<div class="panel-heading">
	<span class="panel-title">@yield('title') sudah di VALIDASI</span></div>

	<div class="panel-body no-padding-hr">
    <div class="note note-warning">
		<h2 class="note-title">Perhatian ..!!</h2>
		<h4>Selamat, KRS Anda sudah di VALIDASI oleh Dosen Pembimbing Akademik. 
		<b> <a href=" {{ url('mhs_jadwal') }} "> Klik disini untuk Jadwal</a> </b></h4>
	</div></div>
</div>
@endsection
