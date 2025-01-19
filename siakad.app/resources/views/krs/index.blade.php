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
        @php $buka = 1; @endphp

        <div class="panel-heading">
            @if ($level == 'admin' || $level == 'baak')
                <span class="panel-title">Data @yield('title')</span>
                <div class="panel-heading-controls">
                    <a href="{{ url($redirect . '/create') }}" class="btn btn-primary">
                        <i class="fa fa-plus-square"></i> Create</a>
                </div>
            @else
                @if ($tgl >= $form->tgl_mulai && $tgl <= $form->tgl_selesai)
                    <span class="panel-title">Data @yield('title')</span>
                    <div class="panel-heading-controls">
                        <a href="{{ url($redirect . '/create') }}" class="btn btn-primary">
                            <i class="fa fa-plus-square"></i> Create</a>
                    </div>
                @else
                    $buka = 0;
                    @if ($tgl >= $form->tgl_selesai)
                        <span class="panel-title">
                            <h3><b>
                                    <center> Mohon Maaf, Pengisian KRS Sudah Ditutup !!! </center>
                                </b></h3>
                        </span>
                    @else
                        <span class="panel-title">
                            <h3><b>
                                    <center> Mohon Maaf, Pengisian KRS Belum Dibuka !!! </br>
                                        Mulai Dibuka Tanggal {{ $form->tgl_mulai }} s/d {{ $form->tgL_selesai }} </center>
                                </b></h3>
                        </span>
                    @endif
                @endif
            @endif
        </div>

        @if ($buka == 1)
            <div class="table-responsive">
                <table id="serversideTable" class="table table-hover table-bordered">
                    <div id="table-loader" class="table-loader"></div>
                    <thead>
                        <tr>
                            <th class="text-center col-md-2">TANGGAL</th>
                            <th class="text-center col-md-1">NIM</th>
                            <th class="text-center col-md-4">NAMA MAHASISWA</th>
                            <!-- <th class="text-center col-md-1">PRODI</th> -->
                            <th class="text-center col-md-1">KELAS</th>
                            <th class="text-center col-md-1">KELOMPOK </th>
                            <th class="text-center">SKS</th>
                            <th class="text-center">T.A</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <span class="label label-warning">Ket : Apabila Tombol Print tidak tampil.
                KRS belum mendapatkan VALIDASI dari Dosen Pembimbing.</span>

            <div class="panel-body">
                <div id="preload" class="text-center" style="display:none">
                    <img src="{{ asset('img/load.gif') }}" alt="">
                </div>
                <div id="detail"></div>
            </div>
        @endif
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
            search: {
                return: true,
            },
            ajax: {
                url: "{{ url($redirect) }}" + '/getData',
                data: function(d) {
                    d.prodi_id = $("#prodi_id").val();
                    d.kelas_id = $("#kelas_id").val();
                    d.th_akademik_id = $("#th_akademik_id").val();
                    d.th_angkatan_id = $("#th_angkatan_id").val();
                },
                beforeSend: function() {
                    addTableLoader('#table-loader');
                },
                complete: function() {
                    deleteTableLoader('#table-loader');
                }
            },
            columns: [{
                    data: 'tanggal',
                    name: 'tanggal',
                    'class': 'text-center'
                },
                {
                    data: 'nim',
                    name: 'nim',
                    'class': 'text-center'
                },
                {
                    data: 'nama_mhs',
                    name: 'nama_mhs'
                },
                //{ data: 'prodi', 	name: 'prodi','class':'text-center'},
                {
                    data: 'kelas',
                    name: 'kelas',
                    'class': 'text-center'
                },
                {
                    data: 'kelompok',
                    name: 'kelompok',
                    'class': 'text-center'
                },
                {
                    data: 'sks',
                    name: 'sks',
                    'class': 'text-center'
                },
                {
                    data: 'xxxxx',
                    name: 'T.A',
                    'class': 'text-center'
                },
                {
                    data: 'action',
                    name: 'action',
                    'orderable': false,
                    'searchable': false,
                },
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

        function deleteForm(id) {
            swal({
                title: "Anda Yakin ?",
                type: "warning",
                text: "Data yang sudah dihapus tidak dapat kembali.",
                showCancelButton: "true",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.value) {
                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ url($redirect) }}" + '/' + id,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': csrf_token
                        },
                        success: function(data) {
                            // table.ajax.reload();
                            dataTable.draw();
                            swal({
                                title: data.title,
                                text: data.text,
                                // timer: 2000,
                                // showConfirmButton: false,
                                type: data.type
                            });
                        },
                        error: function() {
                            swal('Error Deleted!', 'Silahkan Hubungi Administrator', 'error')
                        }
                    });
                }
            });
        }
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
