@extends('layouts.app')
@section('title',$title)

@section('content')
<div class="panel panel-danger panel-dark">
	<div class="panel-heading">
	<span class="panel-title">Filter @yield('title')</span></div>
	
	@include($folder.'.filter')
</div>

<div class="alert alert-dark">
	<button type="button" class="close" data-dismiss="alert">×</button>
	<strong>Perhatian!</strong> 
	<br>Menghapus data pembayaran akan mengubah STATUS Mahasiswa menjadi NON-AKTIF.
</div>

<div class="panel panel-success panel-dark">
	<div class="panel-heading">
		<span class="panel-title">Data @yield('title')</span>
		<div class="panel-heading-controls">
			@if($level!='prodi' && $level!='pimpinan')
			<a href="{{ url($redirect.'/create')}}" class="btn btn-primary">
			<i class="fa fa-plus-square"></i> Create</a>
			@endif
		</div>
	</div>
	
	<div class="table-responsive">
		<table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
			<thead>
				<tr>
				<th class="text-center col-md-1">Tahun<br/> Akademik</th>
				<th class="text-center col-md-1">Nomor</th>
				<th class="text-center col-md-1">Tanggal</th>
				<th class="text-center col-md-1">NIM</th>
				<th class="text-center col-md-2">Nama</th>
				<th class="text-center">L/P</th>
				<th class="text-center col-md-1">Prodi</th>
				<th class="text-center col-md-1">Kelas</th>
				<th class="text-center">Klp</th>
				<th class="text-center col-md-2">Pembayaran</th>
				<th class="text-center col-md-1">Jumlah</th>
				<th class="col-md-1 text-center">Action</th>
				</tr>
			</thead>
		
			<tfoot>
				<tr>
				<th colspan="10" style="text-align:right">Total</th>
				<th></th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
@endsection

@push('demo')
<script>
init.push(function () {
	$('#c-tooltips-demo a').tooltip();
	var options = {
		todayBtn: "linked",
		orientation: $('body').hasClass('right-to-left') ? "auto right" : 'auto auto',
		format: "dd-mm-yyyy"
	}
	$('#tgl1').datepicker(options);
	$('#tgl2').datepicker(options);
});
</script>
@endpush

@push('scripts')
<script type="text/javascript">
$("#prodi_id").select2();
$("#tagihan_id").select2();

var dataTable = $("#serversideTable").DataTable({
	processing: true,
	serverSide: true,
	ajax : {
        url : "{{url($redirect)}}"+'/getData',
		data : function (d) {
			d.th_akademik_id = $("#th_akademik_id").val();
			d.th_angkatan_id = $("#th_angkatan_id").val();
			d.prodi_id		 = $("#prodi_id").val();
			d.kelas_id		 = $("#kelas_id").val();
			d.tgl1			 = $("#tgl1").val();
			d.tgl2			 = $("#tgl2").val();
			d.tagihan_id	 = $("#tagihan_id").val();
        }
	},
	columns: [
		{ data: 'th_akademik', 	name: 'th_akademik','class':'text-center'},
		{ data: 'nomor',		name: 'nomor','class':'text-center'},
		{ data: 'tgl_bayar',	name: 'tgl_bayar','class':'text-center'},
		{ data: 'nim',			name: 'nim','class':'text-center'},
		{ data: 'nama_mhs',		name: 'nama_mhs'},
		{ data: 'jk',			name: 'jk','class':'text-center'},
		{ data: 'alias',		name: 'alias','class':'text-center'},
		{ data: 'kelas',		name: 'kelas','class':'text-center'},
		{ data: 'klp',			name: 'klp','class':'text-center'},
		{ data: 'nama_tagihan', name: 'nama_tagihan'},
		{ data: 'jumlah',		name: 'jumlah','class':'text-right'},
		{ data: 'action',		name: 'action','orderable':false, 'searchable':false,'class':'text-center' },
	],
	"order": [[ 1, "DESC" ]],
	"footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        // Remove the formatting to get integer data for summation
        var intVal = function ( i ) {
            return typeof i === 'string' ?
			i.replace(/[\$,]/g, '')*1 :
			typeof i === 'number' ?
			i : 0;
        };

        // Total over all pages
        total = api
		.column( 10 )
		.data()
		.reduce( function (a, b) {
			return intVal(a) + intVal(b);
		}, 0 );

        // Total over this page
        pageTotal = api
		.column( 10, { page: 'current'} )
		.data()
		.reduce( function (a, b) {
			return intVal(a) + intVal(b);
		}, 0 );

        $( api.column( 10 ).footer() ).html(new Intl.NumberFormat('in-RP', { style: 'currency', currency: 'IDR' }).format(total));
    }
});

$("#filter").on('click',function(){
	if(!$("#th_akademik_id").val()){
		swal(
			'Peringatan..!!',
			'Silahkan Pilih Tahun Angkatan',
			'warning'
		);
		$("#th_akademik_id").focus();
		return false;
	}
	dataTable.draw();
});

function deleteForm(id){
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
				url : "{{ url($redirect)}}" + '/' + id,
				type : "POST",
				data : {'_method' : 'DELETE', '_token' : csrf_token },
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
