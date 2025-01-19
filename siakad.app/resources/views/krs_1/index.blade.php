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
		<span class="panel-title">Data @yield('title')</span>
		<div class="panel-heading-controls">
			<a href="{{ url($redirect.'/create')}}" class="btn btn-primary">
			<i class="fa fa-plus-square"></i> Create</a>
		</div>
	</div>

	<div class="table-responsive">
		<table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
			<thead>
			<tr>
			<th class="text-center col-md-1">TANGGAL</th>
			<th class="text-center col-md-1">NIM</th>
			<th class="text-center">NAMA MAHASISWA</th>
			<th class="text-center col-md-2">PRODI</th>
			<th class="text-center col-md-1">KELAS</th>
			<th class="text-center col-md-1">KLP</th>
			<th class="text-center col-md-1">SKS</th>
			<th class="col-md-1 text-center">AKSI</th>
			</tr>
			</thead>
		</table>
	</div>
	
	<span class="label label-warning">Ket : Apabila Tombol Print tidak tampil. 
	KRS belum mendapatkan VALIDASI dari Dosen Wali / Pembimbing.</span>
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
	ajax : {
        url : "{{url($redirect)}}"+'/getData',
		data : function (d) {
			d.th_akademik_id = $("#th_akademik_id").val();
			d.th_angkatan_id = $("#th_angkatan_id").val();
			d.prodi_id 		 = $("#prodi_id").val();
			d.kelas_id		 = $("#kelas_id").val();
        }
	},
	columns: [
		{ data: 'tgl',		name: 'tgl','class':'text-center'},
		{ data: 'nim',		name: 'nim','class':'text-center'},
		{ data: 'nama_mhs', name: 'nama_mhs'},
		{ data: 'prodi', 	name: 'prodi'},
		{ data: 'kelas', 	name: 'kelas','class':'text-center'},
		{ data: 'kelompok', name: 'kelompok','class':'text-center'},
		{ data: 'sks', 		name: 'sks','class':'text-center'},
		{ data: 'action', 	name: 'action','orderable':false, 'searchable':false,'class':'text-center' },
	],
	"order": [[ 0, "desc" ]]
});

$("#filter").on('click',function(){
	if(!$("#th_akademik_id").val()){
		swal(
			'Peringatan..!!',
			'Silahkan Pilih Tahun Akademik',
			'warning'
		);
		$("#th_akademik_id").focus();
		return false;
	}
	dataTable.draw();
});

function deleteForm(id) {
	swal({
		title: "Anda Yakin ?",
		type : "warning",
		text : "Data yang sudah dihapus tidak dapat kembali.",
		showCancelButton: "true",
		cancelButtonColor: "#3085d6",
		confirmButtonColor: "#d33",
		confirmButtonText: "Yes, delete it!",
	}).then((result) => {
		if (result.value) {
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url : "{{ url($redirect)}}" + '/' + id,
				type: "POST",
				data: {'_method' : 'DELETE', '_token' : csrf_token },
				success : function(data){
					// table.ajax.reload();
					dataTable.draw();
					swal({
						title: data.title,
						text: data.text,
						// timer: 2000,
						// showConfirmButton: false,
						type : data.type
					});
				},
				error : function(){
					swal(
						'Error Deleted!',
						'Silahkan Hubungi Administrator',
						'error'
					)
				}
			});
		}
	});
}
</script>
@endpush
