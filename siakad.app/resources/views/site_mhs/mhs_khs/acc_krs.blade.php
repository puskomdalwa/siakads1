@extends('layouts.app')
@section('title',$title)

@section('content')
<div class="panel panel-danger panel-dark">
	<div class="panel-heading">
	<span class="panel-title">@yield('title') belum di VALIDASI</span></div>

	<div class="panel-body no-padding-hr">
    <div class="note note-danger">
		<h2 class="note-title">Perhatian ..!!</h2>
        <h4>Maaf, KRS Anda belum VALIDASI oleh <b>Dosen Pembimbing Akademik.</b> 
		Silahkan hubungi Dosen Pembimbing Akademik Anda.</h4>
        <h3>Status KRS : <b>{{ $acc_krs }}</b></h3>
	</div></div>
</div>
@endsection
