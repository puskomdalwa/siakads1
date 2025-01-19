@extends('layouts.app')
@section('title',$title)

@section('content')
<div class="panel panel-danger panel-dark">
	<div class="panel-heading">
	<span class="panel-title">Filter @yield('title')</span></div>
	@include($folder.'.filter')
</div>
<div id="detail"></div>
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
	var string = $("#form-input").serialize();
	$.ajax({
		url   : "{{ url($redirect)}}",
		method : 'POST',
		data : string,
		success:function(data){
			$("#detail").html(data);
		}
	});
}
</script>
@endpush
