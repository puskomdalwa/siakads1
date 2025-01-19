@extends('layouts.app')
@section('title', $title)

@section('content')
    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">Filter @yield('title')</span>
        </div>

        @include($folder . '.filter')
    </div>

    <div class="panel panel-success panel-dark">

        <div class="panel-heading">
            <span class="panel-title">Data @yield('title')</span>
        </div>

        <div class="table-responsive">
            <table id="serversideTable" class="table table-hover table-bordered">
                <div id="table-loader" class="table-loader"></div>
                <thead>
                    <tr>
                        <th class="text-center col-md-1">NIM</th>
                        <th class="text-center col-md-4">NAMA MAHASISWA</th>
                        <th class="text-center col-md-1">PRODI</th>
                        <th class="text-center col-md-1">KELAS</th>
                        <th class="text-center col-md-1">SEMESTER</th>
                        <th class="text-center col-md-1">STATUS</th>
                        <th class="text-center col-md-1">KETERANGAN</th>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="panel-body">
            <div id="preload" class="text-center" style="display:none">
                <img src="{{ asset('img/load.gif') }}" alt="">
            </div>
            <div id="detail"></div>
        </div>

    </div>
@endsection


@push('demo')
    <script>
        init.push(function() {
            $('#c-tooltips-demo a').tooltip();
        });
    </script>
@endpush

@push('scripts')
    <script type="text/javascript">
        var dataTable = $("#serversideTable").DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('catatanKrs.sudahKrs.getData') }}",
                data: function(d) {
                    d.prodi_id = $("#prodi_id").val();
                    d.kelas_id = $("#kelas_id").val();
                    d.th_akademik_id = $("#th_akademik_id").val();
                    d.th_angkatan_id = $("#th_angkatan_id").val();
                    d.jk_id = $("#jk_id").val();
                },
                beforeSend: function() {
                    addTableLoader('#table-loader');
                },
                complete: function() {
                    deleteTableLoader('#table-loader');
                }
            },
            columns: [{
                    data: 'mhs_nim',
                    name: 'mhs_nim',
                    'class': 'text-center'
                },
                {
                    data: 'mhs_nama',
                    name: 'mhs_nama'
                },
                {
                    data: 'prodi_nama',
                    name: 'prodi_nama',
                    'class': 'text-center'
                },
                {
                    data: 'kelas_nama',
                    name: 'kelas_nama',
                    'class': 'text-center'
                },
                {
                    data: 'mhs_semester',
                    name: 'mhs_semester',
                    'class': 'text-center'
                },
                {
                    data: 'mhs_status',
                    name: 'mhs_status',
                    'class': 'text-center'
                },
                {
                    data: 'mhs_keterangan',
                    name: 'mhs_keterangan',
                    'class': 'text-center'
                }
            ],
            "order": [
                [0, "desc"]
            ]
        });

        $("#filter").on('click', function() {
            if (!$("#th_akademik_id").val()) {
                swal('Peringatan..!!', 'Silahkan Tahun Akademik', 'warning');
                $("#th_akademik_id").focus();
                return false;
            }

            if (!$("#prodi_id").val()) {
                swal('Peringatan..!!', 'Silahkan Pilih Program Studi', 'warning');
                $("#prodi_id").focus();
                return false;
            }

            dataTable.draw();
        });
    </script>
@endpush


<?php
/*
<!-- @if (($level == 'admin') || ($level == 'admin')) 
	@endif
-->


@if (($level == 'admin') || ($level =='admin'))	
	<div class="note note-danger">
		<h3 class="note-title"> <b>Perhatian !!! </b></h3>		
		@if (($tgl >= $buka_form->tgl_mulai) && ($tgl <= $buka_form->tgl_selesai))
			<h3 class="note-title"> <<b><center>
		
			Mulai Tanggal {{$buka_form->tgl_mulai}} s/d {{$buka_form->tgl_selesai}} </center></b></h3>
		@else
			@if ($tgl >= $buka_form->tgl_selesai)
				<h3><b><center> Mohon Maaf, Pengisian KRS Sudah Ditutup !!! </center></b></h3>		
			@else
				<h3><b><center> Mohon Maaf, Pengisian KRS Belum Dibuka !!! </br>
				Mulai Dibuka Tanggal {{$buka_form->tgl_mulai}} s/d {{$buka_form->tgL_selesai}} </center></b></h3>		
			@endif
		@endif			
	</div>	
@else
	
@endif

---------------------------------------------------------------------------------------------

$("#th_akademik_id").on('click',function(){
	if(!$("#kelas_id").val()){
		swal('Peringatan..!!','Silahkan Pilih Kelas','warning');
		$("#kelas_id").focus();
		return false;
	}	

	if(!$("#prodi_id").val()){
		swal('Peringatan..!!','Silahkan Pilih Program Studi','warning');
		$("#th_akademik_id").focus();
		return false;
	}	
	dataTable.draw();
});

$("#kelas_id").on('click',function(){
	if(!$("#th_akademik_id").val()){
		swal('Peringatan..!!','Silahkan Tahun Akademik','warning');
		$("#th_akademik_id").focus();
		return false;
	}	

	if(!$("#prodi_id").val()){
		swal('Peringatan..!!','Silahkan Pilih Program Studi','warning');
		$("#th_akademik_id").focus();
		return false;
	}	
	dataTable.draw();
});

$("#prodi_id").on('click',function(){
	if(!$("#th_akademik_id").val()){
		swal('Peringatan..!!','Silahkan Tahun Akademik','warning');
		$("#th_akademik_id").focus();
		return false;
	}	

	if(!$("#kelas_id").val()){
		swal('Peringatan..!!','Silahkan Pilih Kelas','warning');
		$("#kelas_id").focus();
		return false;
	}	
	dataTable.draw();
});
*/
?>
