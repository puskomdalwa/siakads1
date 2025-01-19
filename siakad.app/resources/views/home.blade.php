@extends('layouts.app')
@section('title','Dashboard')

@section('content')
<div class="panel panel-danger panel-dark" style="display:none;">
    <div class="panel-heading">Dashboard</div>
    <div class="panel-body">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
		
        <!-- Selamat Datang {{Auth::user()->name}} -->
    </div>
</div>

@if($level=='admin'||$level=='baak'||$level=='baak (hanya lihat)'||$level=='pimpinan'|| $level=='prodi'||$level=='staf'||$level=='keuangan')
	@include('dashboard')
	@if($level=='admin')@include('layouts.useronline')@endif		
@elseif($level=='dosen')
	@include('site_dosen.profile.index')	
@elseif($level=='mahasiswa')
	@include('site_mhs.profile.index')	
@else
@endif

@endsection
