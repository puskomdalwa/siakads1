@extends('layouts.app')
@section('title',$title)

@section('content')
<div class="panel panel-danger panel-dark">
	<div class="panel-heading">
	<span class="panel-title">Filter @yield('title')</span>
	</div>
	@include($folder.'.filter')
</div>

<div class="panel panel-success panel-dark">
	<div class="panel-heading">
		<span class="panel-title">Data @yield('title')</span>
		<div class="panel-heading-controls">
		<button type="button" class="btn btn-primary" name="refresh" id="refresh">
		<i class="fa fa-refresh"></i> Refresh</button>
		</div>
	</div>

	<div class="table-responsive">
		<table id="serversideTable" class="table table-hover table-bordered table-condensed">
			<thead>
				<tr>
				<th></th>
				<th class="text-center col-md-2">Kode</th>
				<th class="text-center">Mata Kuliah</th>
				<th class="text-center col-md-1">SKS</th>
				<th class="text-center col-md-1">smt</th>
				<th class="text-center col-md-1">Jadwal</th>
				<th class="col-md-1 text-center">Action</th>
				</tr>
			</thead>
		</table>
	</div>
	{{-- @include($folder.'.modal') --}}
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
<div class="table-responsive">
	<div class="table-header">
	<span class="text-info"><b>Detail Jadwal</b></span></div>
   
	<table class="table details-table table-vcenter table-condensed table-bordered table-hover table-striped" id="posts-@{{id}}">
		<thead>
			<tr>
			<th class="text-center col-md-1">Kelas</th>
			<th class="text-center col-md-1">Klp</th>
			<th class="text-center col-md-1">Hari</th>
			<th class="text-center col-md-1">Ruang</th>
			<th class="text-center col-md-2">Waktu</th>
			<th class="text-center">Dosen</th>
			<th class="text-center">Mhs</th>
			<th class="text-center col-md-1">Action</th>
			</tr>
		</thead>
	</table>
</div>
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
			d.kurikulum_id = $("#kurikulum_id").val();
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
		{ data: 'kd_mk', 		name: 'kd_mk','class':'text-center'},
		{ data: 'nama_mk', 		name: 'nama_mk'},
		{ data: 'sks_mk', 		name: 'sks_mk','class':'text-center'},
		{ data: 'smt_mk', 		name: 'smt_mk','class':'text-center'},
		{ data: 'jml_kelompok', name: 'jml_kelompok','class':'text-center'},
		{ data: 'action', 		name: 'action','orderable':false, 'searchable':false,'class':'text-center' },
	],
	"order": [[ 4, "asc" ],[ 1, "asc" ]]
});

$('#serversideTable tbody').on ('click', 'td.details-control', function (){
    var tr	= $(this).closest('tr');
    var row = dataTable.row(tr);
    var tableId = 'posts-' + row.data().id;
    // console.log(tableId);
    if (row.child.isShown()){
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
            { data: 'kelas',	name: 'kelas','class':'text-center' },
            { data: 'kelompok', name: 'kelompok','class':'text-center' },
            { data: 'hari', 	name: 'hari','class':'text-center'  },
            { data: 'ruang', 	name: 'ruang','class':'text-center' },
            { data: 'waktu', 	name: 'waktu','class':'text-center' },
            { data: 'dosen', 	name: 'dosen' },
            { data: 'jml_mhs', 	name: 'jml_mhs','class':'text-center' },
            { data: 'action', 	name: 'action','orderable':false, 'searchable':false,'class':'text-center' },
        ],
        "order": [[ 1, "asc" ]]
	});
}

listKurikulum();

$("#prodi_id").on('change',function(){
	listKurikulum();
});

function listKurikulum(){
	var prodi_id = $("#prodi_id").val();
	var url = "{{url($redirect.'/getListKurikulum')}}";
	$.get(url + '/' + prodi_id, function (data) {
		$("#kurikulum_id").html(data);
		dataTable.draw();
	});
}

$("#kurikulum_id").on('change',function(){
	if(!$("#th_akademik_id").val()){
		swal(
			'Peringatan..!!',
			'Silahkan Pilih Tahun Angkatan',
			'warning'
		);
		$("#th_akademik_id").focus();
		return false;
	}

	if(!$("#prodi_id").val()){
		swal(
			'Peringatan..!!',
			'Silahkan Pilih Program Studi',
			'warning'
		);
		$("#prodi_id").focus();
		return false;
	}
	dataTable.draw();
});

$("#refresh").on('click',function(){
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
