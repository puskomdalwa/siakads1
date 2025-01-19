@extends('layouts.app')
@section('title',$title)
@section('content')

<div class="panel-heading panel-danger panel-dark">
<span class="panel-title">@yield('title')</span></div>

<div class="panel-body">
	<table class="table table-bordered table-hover table-striped">
		<thead>
			<tr>
				<th class="text-center">No</th>
				<th class="text-center">Judul / Catatan</th>
				<th class="text-center">Status</th>
			</tr>
		</thead>

		<tbody>
			@php
			$no=1;    
			@endphp
			@foreach ($data_judul as $judul)
				<tr>
					<td class="text-center"> {{ $no++ }} </td>
					<td> {!! $judul->judul !!} <br>
					<span class="label label-warning"> {{ $judul->catatan }} </span></td>
					
					<td class="text-center"> {!! $judul->acc=='T' ? 
					'<i class="fa fa-exclamation text-warning"></i>' : '<i class="fa fa-check text-success"></i>' !!} </td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>

<div class="panel-footer text-center text-danger">
	Status Pengajuan Skripsi sedang {{ $pengajuan->status }} Ketua Program Studi.
</div>

@endsection

@push('scripts')
<script>
</script>
@endpush
