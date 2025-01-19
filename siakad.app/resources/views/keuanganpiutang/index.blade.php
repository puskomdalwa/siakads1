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
	<span class="panel-title">Data @yield('title')</span></div>

	<div class="table-responsive">
		<table id="serversideTable" class="table table-bordered table-condensed ">
			<thead>
				<tr>
				<th class="text-center col-md-1">NIM</th>
				<th class="text-center col-md-4">Nama</th>
				<th class="text-center">L/P</th>
				<th class="text-center col-md-1">Prodi</th>
				<th class="text-center col-md-1">Kelas</th>
				<th class="text-center">Klp</th>
				<th class="text-center col-md-2">Tagihan</th>
				<th class="text-center col-md-1">Jumlah</th>
				<th class="text-center col-md-1">Bayar</th>
				<th class="text-center col-md-1">Piutang</th>
				</tr>
			</thead>

			<tfoot>
				<tr>
				<th colspan="9" style="text-align:right">Total</th>
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
});
</script>
@endpush

@push('scripts')
<script type="text/javascript">
// $.fn.dataTable.ext.search.push(
//     function( settings, data, dataIndex ) {
//         // var min = parseInt( $('#min').val(), 10 );
//         // var max = parseInt( $('#max').val(), 10 );
//         var sisa = parseFloat(data[9]); // use data for the age column
//
//         // if ( ( isNaN( min ) && isNaN( max ) ) ||
//         //      ( isNaN( min ) && age <= max ) ||
//         //      ( min <= age   && isNaN( max ) ) ||
//         //      ( min <= age   && age <= max ) )
//         if ( sisa > 0 )
//         {
//             return true;
//         }
//         return false;
//         swal('test','test','success');
//     }
// );

$(document).ready(function () {
	var dataTable = $("#serversideTable").DataTable({
		processing: true,
		serverSide: true,
		ajax : {
			url : "{{url($redirect)}}"+'/getData',
			data: function (d) {
				d.th_akademik_id = $("#th_akademik_id").val();
				d.prodi_id		 = $("#prodi_id").val();
				d.mhs_id 		 = $("#mhs_id").val();
				d.tagihan_id	 = $("#tagihan_id").val();
			}
		},
		columns: [
			{ data: 'nim', 			name: 'nim','class':'text-center'},
			{ data: 'nama', 		name: 'nama'},
			{ data: 'jk', 			name: 'jk','class':'text-center'},
			{ data: 'prodi', 		name: 'prodi'},
			{ data: 'kelas', 		name: 'kelas','class':'text-center'},
			{ data: 'klp', 			name: 'klp','class':'text-center'},
			{ data: 'tagihan', 		name: 'tagihan'},
			{ data: 'jml_tagihan', 	name: 'jml_tagihan','class':'text-right'},
			{ data: 'jml_bayar', 	name: 'jml_bayar','class':'text-right'},
			{ data: 'sisa', 		name: 'sisa','class':'text-right'},
		],
		"order": [[ 0, "asc" ]],
		"footerCallback": function ( row, data, start, end, display ) {
			var api = this.api(), data;
			// Remove the formatting to get integer data for summation
			var intVal = function ( i ) {
				return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 :
				typeof i === 'number' ? i : 0;
			};

			// Total over all pages
			total = api
			.column( 9 )
			.data()
			.reduce( function (a, b) {
				return intVal(a) + intVal(b);
			}, 0 );

			// Total over this page
			pageTotal = api
			.column( 9, { page: 'current'} )
			.data()
			.reduce( function (a, b) {
				return intVal(a) + intVal(b);
			}, 0 );
				
			$( api.column( 9 ).footer() ).html(new Intl.NumberFormat('in-RP', 
				{ style: 'currency', currency: 'IDR' }).format(total));
			// swal('footer','footer','success');
		}
	});

	var filteredData = dataTable
	.column(9)
	.data()
	.filter(function(value,index) {
		// return value > 0 ? true : false;
		swal('test',value,'success');
	});

	$("#filter").on('click',function(){
		if(!$("#th_akademik_id").val()){
			swal('Tahun Akademik','Tidak Boleh Kosong','warning');
			return false;
		}
		
		if(!$("#prodi_id").val()){
			swal('Program Studi','Tidak Boleh Kosong','warning');
			return false;
		}
		
		if(!$("#tagihan_id").val()){
			swal('Tagihan','Tidak Boleh Kosong','warning');
			return false;
		}
	
		dataTable.draw();
	});
});
	
$("#th_akademik_id").on('change',function(){
	listTagihan();
	listMahasiswa();
});

$("#prodi_id").on('change',function(){
	listTagihan();
	listMahasiswa();	
});


//("#tagihan_id").on('click',function(){
//	listTagihan();
//});

function listTagihan(){
	var string = {
		prodi_id		: $("#prodi_id").val(),
		th_akademik_id 	: $("#th_akademik_id").val(),
		_token: "{{ csrf_token() }}"
	};
	$.ajax({
		url   	: "{{ url($redirect."/listTagihan") }}",
		method 	: 'POST',
		data 	: string,
		success:function(data){
			$("#tagihan_id").html(data);
		}
	});
}

function listMahasiswa(){
	var string = {
		prodi_id	   : $("#prodi_id").val(),
		th_akademik_id : $("#th_akademik_id").val(),
		_token: "{{ csrf_token() }}"
	};
	$.ajax({
		url		: "{{ url($redirect."/listMahasiswa") }}",
		method 	: 'POST',
		data 	: string,
		success:function(data){
			$("#mhs_id").html(data);
		}
	});
}

function deleteForm(id){
	swal({
		title: "Anda Yakin ?",
		type : "warning",
		text : "Data yang sudah dihapus tidak dapat kembali.",
		showCancelButton  : "true",
		cancelButtonColor : "#3085d6",
		confirmButtonColor: "#d33",
		confirmButtonText : "Yes, delete it!",
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
