<div class="table-responsive" style="overflow-x: scroll">
	<table id="krsTable" class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="text-center col-md-1">Kode</th>
				<th class="text-center col-md-4">Matakuliah</th>
				<th class="text-center col-md-1">SKS</th>
				<th class="text-center col-md-1">smt</th>
				<th class="text-center col-md-4">Dosen</th>
				<th class="text-center col-md-1">Ruang</th>
				<th class="text-center col-md-1">Hari</th>
				<th class="text-center col-md-1">Waktu</th>
			</tr>
		</thead>
		
		<tfoot>
			<tr>
				<th colspan="2" style="text-align:right">Total</th>
				<th></th>
			</tr>
		</tfoot>
	</table>
</div>

@push('scripts')
<script type="text/javascript">
var dataTableKRS = $("#krsTable").DataTable({
processing: true,
serverSide: true,
paging : false,
"searching": false,
ajax      : {
	url : "{{url($redirect)}}"+'/getDataKRS',
	data : function (d) {
		d.th_akademik_id = $("#th_akademik_id_krs").val();
		d.nim = $("#nim").val();
	}
 },
columns: [
	{ data: 'kode_mk', name: 'kode_mk','class':'text-center'},
	{ data: 'nama_mk', name: 'nama_mk'},
	{ data: 'sks_mk',  name: 'sks_mk','class':'text-center'},
	{ data: 'smt_mk',  name: 'smt_mk','class':'text-center'},
	{ data: 'dosen',   name: 'dosen'},
	{ data: 'ruang',   name: 'ruang','class':'text-center'},
	{ data: 'hari',    name: 'hari','class':'text-center'},
	{ data: 'waktu',   name: 'waktu','class':'text-center'},
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
		}, 0 );

		// Total over this page
		pageTotal = api
		.column( 2, { page: 'current'} )
		.data()
		.reduce( function (a, b) {
			return intVal(a) + intVal(b);
		}, 0 );
		$( api.column( 2 ).footer() ).html(total);
		// swal('footer','footer','success');
	}
});

$("#th_akademik_id_krs").on('change',function(){
	dataTableKRS.draw();
});
</script>

@endpush
