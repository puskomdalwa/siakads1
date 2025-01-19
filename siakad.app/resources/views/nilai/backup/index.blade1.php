@extends('layouts.app')
@section('title',$title)

@php
	//date_default_timezone_set('Asia/Jakarta');
	// $batas= mktime(date("d"),date("m"),date("Y"));
	// $batas = date('2022-09-23');	
	
	//$tgl = tgl_jam($tgl1); 
	
	//$tgl = date('Y-m-d H:i:s');
	//$tgl_mulai   = $buka_form->tgl_mulai;
	//$tgl_selesai = $buka_form->tgl_selesai;	
@endphp

@section('content')
<div class="panel panel-danger panel-dark">
	@if (($level == 'admin') || ($level == 'baak'))
		<div class="panel-heading">
		<span class="panel-title">Filter @yield('title')
		<!-- ===> Batas Akhir Pengisian Niilai : 31/01/2022 -->
		</span></div>
		
		@include($folder.'.filter')
	@endif	
</div>

<div class="panel panel-success panel-dark">
	@php
		$buka = 1;
	@endphp
	
	<div class="panel-heading">
		
		@if (($level== 'admin') || ($level == 'baak'))
			<span class="panel-title">Data @yield('title')</span>	
			<div class="text-right">
			<a href="{{url($redirect)}}" class="btn btn-primary"><i class="fa fa-refresh"></i> Refresh</a>
			</div>				
		@else
			<h3><b><center> Mohon Maaf !!!, Input/Pengisian Nilai Khusus Admin & BAAk !!! </center></b></h3>		
			
			
		@endif
				
		<!--
		<div class="panel-heading-controls">
			<a href="{{ url($redirect)}}" class="btn btn-primary"><i class="fa fa-refresh"></i> Refresh</a>
		</div>
		-->		
	</div>

	@if($buka==1)
		<div class="table-responsive">
			<table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
				<thead>
					<tr>
					<th class="text-center col-md-1">Kode</th>
					<th class="text-center col-md-3">Mata Kuliah</th>
					<th class="text-center">SKS</th>
					<th class="text-center">smt</th>
					<th class="text-center">Klp</th>
					<!-- <th class="text-center">Kurikulum</th> -->
					<th class="text-center col-md-3">Dosen</th>
					<th class="text-center">Hari</th -->
					<th class="text-center col-md-1">Waktu</th>
					<th class="text-center">Mhs</th>
					<th class="text-center">Sts</th>
					<th class="text-center">Action</th>
					</tr>
				</thead>
			</table>
		</div>

		<span class="label label-warning text-primary">	Status :<br>
		<i class="fa fa-check text-success"></i> : Sudah Isi Nilai, &nbsp;&nbsp;
		<i class="fa fa-times text-danger"></i> Belum Isi Nilai. </span>
	@endif
</div>
@endsection

@push('demo')
<script>
init.push(function () {
	$('#c-tooltips-demo a').tooltip();
});
</script>
@endpush

@push('scripts')
<script type="text/javascript">
var dataTable = $("#serversideTable").DataTable({
	processing: true,
	serverSide: true,
	paging : false,
	"searching": false,
	ajax : {
        url : "{{url($redirect)}}"+'/getData',
		data : function (d) {
			d.th_akademik_id = $("#th_akademik_id").val();
			d.prodi_id = $("#prodi_id").val();
			d.kelas_id = $("#kelas_id").val();
        }
	},
	columns: [
		{ data: 'kd_mk', 		name: 'kd_mk','class':'text-center'},
		{ data: 'nama_mk', 		name: 'nama_mk'},
		{ data: 'sks_mk',		name: 'sks_mk','class':'text-center'},
		{ data: 'smt_mk',		name: 'smt_mk','class':'text-center'},
		{ data: 'kelompok',		name: 'kelompok','class':'text-center'},
		//{ data: 'kurikulum',	name: 'kurikulum','class':'text-center'},
		{ data: 'dosen',		name: 'dosen'},
		{ data: 'hari',			name: 'hari','class':'text-center'},
		//{ data: 'ruang_kelas', 	name: 'ruang','class':'text-center'},
		{ data: 'waktu', 		name: 'waktu','class':'text-center'},
		{ data: 'jml_mhs',		name: 'jml_mhs','class':'text-center'},
		{ data: 'status',		name: 'status','class':'text-center'},
		
		//{ data: 'action',		name: 'action','orderable':false, 'searchable':false,'class':'text-center' },
		//{ data: 'tutup', name: 'tutup','orderable':false, 'searchable':false,'class':'text-center' },
		
		@if (($level == 'admin') || ($level == 'baak')) 
			{ data: 'action', name: 'action','orderable':false, 'searchable':false,'class':'text-center' },
		@else
			@if (($tgl >= $form->tgl_mulai) && ($tgl <= $form->tgl_selesai)) 
			//	{ data: 'action', name: 'action','orderable':false, 'searchable':false,'class':'text-center' },
			@else
				//	{ data: 'tutup', name: 'tutup','class':'text-center' },
				//{ data: 'tutup', name: 'tutup','orderable':false, 'searchable':false,'class':'text-center' },
			@endif
		@endif
	],
	"order": [[ 3, "asc" ],[ 0, "asc" ]]
});

$("#th_akademik_id").on('change',function(){
	dataTable.draw();
});

$("#prodi_id").on('change',function(){
	dataTable.draw();
});

$("#kelas_id").on('change',function(){
	dataTable.draw();
});
</script>
@endpush

<!--
@if (($tgl >= $form->tgl_mulai) && ($tgl <= $form->tgl_selesai))) 
	<h4 class="note-title"><b><center>Mohon Diperhatikan !!!, &nbsp;&nbsp;<?=$form->nama?> 
	Mulai Tanggal {{tgl_nojam($form->tgl_mulai)}} 00:00:00 s/d {{tgl_nojam($form->tgl_selesai)}} 00:00:00 </center></b></h3>
@else
	<?php // $buka = 0 ?>
	@if ($tgl >= $form->tgl_selesai)
		<h3><b><center> Mohon Maaf !!!, Input/Pengisian Nilai Sudah Ditutup !!! </center></b></h3>		
	@else
		<h3><b><center> Mohon Maaf !!!, Input/Pengisian Nilai Belum Dibuka !!! </br>{{ $tgl}} - {{ $form->tgl_mulai}}
		Mulai Dibuka Tanggal {{$form->tgl_mulai}} s/d {{$form->tgl_selesai}} </center></b></h3>		
	@endif
@endif
-->
