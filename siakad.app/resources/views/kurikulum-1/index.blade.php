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
		<a href="{{ url($redirect.'/create')}}" class="btn btn-primary"><i class="fa fa-plus-square"></i> Create</a></div>
	</div>
	
	<div class="table-responsive">
		<table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
			<thead>
				<tr>
				<th></th>
				<th class="text-center col-md-2">Tahun<br/>Akademik</th>
				<th class="text-center">Program Studi</th>
				<th class="text-center">Nama Kurikulum</th>
				<th class="text-center">Angkatan</th>
				<th class="text-center col-md-1">Jumlah<br/>Matakuliah</th>
				<th class="col-md-1 text-center">Action</th>
				</tr>
			</thead>
		</table>
	</div>
</div>
@endsection

@push('css')
<style media="screen">
.text-putih {
	color:#fff;
}
td.details-control {
	background : url('../img/details_open.png') no-repeat center center;
	cursor : pointer;
	width : 18px;
}
tr.shown td.details-control {
	background: url('../img/details_close.png') no-repeat center center;
}
</style>
@endpush

@push('demo')
<script>
	init.push(function () {
		$('#c-tooltips-demo a').tooltip();
	});
</script>
@endpush

@push('scripts')
<script src="{{asset('js/handlebars-v4.0.5.js')}}"></script>

<script id="details-template" type="text/x-handlebars-template">
	<div class="table-header">
	<span class="text-info"><b>Detail Matakuliah</b></span>
	</div>

	<table class="table details-table table-vcenter table-condensed table-bordered table-hover table-striped" id="posts-@{{id}}">
		<thead>
			<tr>
			<th class="text-center col-md-1">Kode</th>
			<th class="text-center">Mata Kuliah</th>
			<th class="text-center col-md-1">SKS</th>
			<th class="text-center col-md-1">smt</th>
			<th class="text-center col-md-1">Aktif</th>
			<th class="text-center col-md-1">Prodi</th>
			</tr>
		</thead>
		
		<tfoot>
			<tr>
			<th colspan="2">Total</th>
			<th></th>
			</tr>
		</tfoot>
	</table>
</script>

<script type="text/javascript">
var template = Handlebars.compile($("#details-template").html());
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
			// d.kelas_id = $("#kelas_id").val();
        }
	},
	columns: [
		{
			"className": 'details-control',
			"orderable": false,
			"searchable": false,
			"data": null,
			"defaultContent": ''
		},
		{ data: 'th_akademik', 	name: 'th_akademik','class':'text-center'},
		{ data: 'prodi', 		name: 'prodi'},
		{ data: 'nama', 		name: 'nama'},
		{ data: 'angkatan', 	name: 'angkatan'},
		{ data: 'jml_mk', 		name: 'jml_mk','class':'text-center'},
		{ data: 'action', 		name: 'action','orderable':false, 'searchable':false,'class':'text-center' },
	],
	"order": [[ 0, "desc" ]]
});

$('#serversideTable tbody').on('click', 'td.details-control', function () {
    var tr = $(this).closest('tr');
    var row = dataTable.row(tr);
    var tableId = 'posts-' + row.data().id;
    // console.log(tableId);
    if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
    } else {
        // Open this row
        row.child(template(row.data())).show();
        initTable(tableId, row.data());
        tr.addClass('shown');
        tr.next().find('td').addClass('no-padding bg-gray');
    }
});

function initTable(tableId, data) {
    $('#' + tableId).DataTable({
        processing: true,
        serverSide: true,
        paging : false,
        "searching": false,
        ajax: data.details_url,
        columns: [
            { data: 'kode_mk', name: 'kode_mk','class':'text-center' },
            { data: 'nama_mk', name: 'nama_mk'},
            { data: 'sks_mk',  name: 'sks_mk','class':'text-center'  },
            { data: 'smt_mk',  name: 'smt_mk','class':'text-center' },
            { data: 'aktif',   name: 'aktif','class':'text-center' },
            { data: 'prodi',   name: 'prodi','class':'text-center' },
        ],
        "order": [[ 0, "asc" ]],
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
			.column( 2 )
			.data()
			.reduce( function (a, b) {
					return intVal(a) + intVal(b);
				}, 0 
			);

			// Total over this page
			pageTotal = api
			.column( 2, { page: 'current'} )
			.data()
			.reduce( function (a, b) {
				return intVal(a) + intVal(b);
				}, 0 
			);

			// Update footer
			// $( api.column( 4 ).footer() ).html(
			//     ''+pageTotal +' ( '+ total +' )'
			// );

			$( api.column( 2 ).footer() ).html(total);
		}
    })
}

$("#filter").on('click',function(){
	// if(!$("#th_akademik_id").val())
	// {
	//   swal('Peringatan..!!','Silahkan Pilih Tahun Angkatan','warning');
	//   $("#th_akademik_id").focus();
	//   return false;
	// }
	dataTable.draw();
});

$("#th_akademik_id").change(function(){
	dataTable.draw();
});

$("#prodi_id").change(function(){
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
					swal('Error Deleted!', 
						'Silahkan Hubungi Administrator', 'error'
					)
				}
			});
		}
	});
}
</script>
@endpush
