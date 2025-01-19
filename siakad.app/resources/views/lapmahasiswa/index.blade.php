@extends('layouts.app')
@section('title',$title)

@section('content')
<div class="panel panel-danger panel-dark">
	<div class="panel-heading">
	<span class="panel-title">Filter @yield('title')</span></div>
	
	@include($folder.'.filter')
</div>

<div class="panel panel-success panel-dark">
	<div class="panel-heading">
	<span class="panel-title">Data</span></div>

	<div class="panel-body">
		<div id="preload" class="text-center" style="display:none">
		<img src="{{ asset('img/load.gif') }}" alt=""></div>
		<div id="detail"></div>
	</div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
$("#filter").on('click',function(){
	if(!$("#th_akademik_id").val()){
		swal('Tahun Akademik..!!','Tidak Boleh Kosong','warning');
		return false;
	}
	getData();
});

function getData(){
	$("#detail").fadeOut();
	$("#preload").fadeIn();
	var string = $("#form-input").serialize();
	$.ajax({
		url: "{{ url($redirect)}}",
		method: 'POST',
		data: string,
		success:function(data){
			$("#detail").fadeIn();
			$("#preload").fadeOut();
			$("#detail").html(data);
		}
	});
}

$("#th_akademik_id").on('change',function(){
	getData();
});

$("#kelas_id").on('change',function(){
	getData();
});

$("#status_id").on('change',function(){
	getData();
});

$("#kelompok_id").on('change',function(){
	if(!$("#th_akademik_id").val()){
		swal('Tahun Akademik..!!','Tidak Boleh Kosong','warning');
		return false;
	}
	getData();
});

$("#prodi_id").on('change',function(){
	listKelompok();
});

function listKelompok(){
	var prodi_id = $("#prodi_id").val();
	var url = "{{url($redirect.'/getListKelompok')}}";
	$.get(url + '/' + prodi_id, function (data) {
		$("#kelompok_id").html(data);
		getData();
	});
}


//$("#prodi_id").on('change',function(){
//	listKurikulum();
//});

/*
function listKurikulum(){
	var prodi_id = $("#prodi_id").val();
	var url = "{{url($redirect.'/getListKurikulum')}}";
	$.get(url + '/' + prodi_id, function (data) {
		$("#kurikulum_id").html(data);
		getData();
	});
}
*/

</script>
@endpush
