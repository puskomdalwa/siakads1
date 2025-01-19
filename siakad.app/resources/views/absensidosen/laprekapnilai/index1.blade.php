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
$("#filter").on('click',function() {
	if(!$("#th_akademik_id").val()) {
		swal('Tahun Akademik..!!','Tidak Boleh Kosong','warning');
		return false;
	}
	
	if(!$("#prodi_id").val()) {
		swal('Program Studi..!!','Tidak Boleh Kosong','warning');
		return false;
	}
  
	if(!$("#kelas_id").val()) {
		swal('Kelas..!!','Tidak Boleh Kosong','warning');
		return false;
	}
	getData();
});

function getData() {
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
</script>
@endpush
