@extends('layouts.app')
@section('title',$title)

@section('content')
<div class="alert alert-dark">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>Perhatian !</strong> 
    <ul><li>Pengajuan Judul Skripsi Maximal 3 Judul.</li>
	<li>Status Pengajuan Skripsi {{ !empty($pengajuan) ? $pengajuan->status : 'Baru' }} </li>
    </ul>
</div>

<input type="hidden" name="id" id="id" value="{{ !empty($pengajuan) ? $pengajuan->id : 0 }}">

<form action="#" class="panel form-horizontal" name="form-input" id="form-input">
	{{ csrf_field() }}
	
	<input type="hidden" name="judul_id" id="judul_id">
    <div class="panel-heading panel-danger panel-dark">
    <span class="panel-title">@yield('title')</span></div>
    
	<div class="panel-body">
        <div class="row form-group">
            <label class="col-sm-2 control-label">Judul Skripsi:</label>
            <div class="col-sm-8">
			<textarea type="text" name="judul" id="judul" class="form-control" required autofocus></textarea>
            </div>
        </div>    
    </div>
	
    <div class="panel-footer text-center">
        <button type="button" name="tambah" id="tambah" class="btn btn-primary">
		<i class="fa fa-plus-square"></i> TAMBAH </button>
        
		<button type="button" name="simpan" id="simpan" class="btn btn-success">
		<i class="fa fa-save"></i> SIMPAN </button>
    </div>
</form>

<div id="detail"></div>
@endsection

@push('scripts')
<script src="{{asset('vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('vendor/unisharp/laravel-ckeditor/adapters/jquery.js')}}"></script>

<script>
$('textarea').ckeditor();
$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});

show();

// {{ url($redirect) }} => "https://siakad.dalwa.ac.id/mhs_skripsi (Route)"

function show(){
	var id = $("#id").val();
	$.get("{{ url($redirect) }}" +'/' + id, function (data) {
		$("#detail").html(data);
	})
}

$("#tambah").click(function(){
	$("#judul_id").val('');
	$("#judul").val('');
	$("#judul").focus();
});

$('#simpan').click(function (e) {
	e.preventDefault();
	if(!$("#judul").val()){
		Swal.fire(
			'ERROR',
			'Judul tidak boleh kosong.!!',
			'error'
		);
		$("#judul").focus();
		return false;
	}

	// var input = $("#form-input").serialize();
	// console.log(input);

	$.ajax({
		data: $('#form-input').serialize(),
		url: "{{ url($redirect) }}",
		type: "POST",
		dataType: 'json',
		success: function (data) {
			Swal.fire(
				data.title,
				data.info,
				data.status
			);
			$("#judul").val('');
			show();
		},
		error: function (data) {
			console.log('Error:', data);
			// toastr.error('Silahkan hubungi Administrator (Affasol)');
			Swal.fire(
			'ERROR',
			'Silahkan hubungi Administrator',
			'error'
			);
		}
	});
});

$('body').on('click', '.editBtn', function () {
	var data_id = $(this).data('id');
	$.get("{{ url($redirect) }}" +'/' + data_id +'/edit', function (data) {
		$('#judul_id').val(data.id);
		$('#judul').val(data.judul);
	})
});

$('body').on('click', '.deleteBtn', function () {
	var data_id = $(this).data("id");
	Swal.fire({
		title: '@yield('title')',
		text: "Anda yakin akan menghapus ID "+data_id+" ini ?",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Ya, Hapus!'
	}).then((result) => {
		if (result.value) {
			$.ajax({
				type: "DELETE",
				url: "{{ url($redirect) }}"+'/'+data_id,
				success: function (data) {
					Swal.fire(
						data.title,
						data.info,
						data.status
					);
					show();
				},
				error: function (data) {
					console.log('Error:', data);
					toastr.error('Silahkan hubungi Administrator');
				}
			});
		}
	})
});
</script>
@endpush
