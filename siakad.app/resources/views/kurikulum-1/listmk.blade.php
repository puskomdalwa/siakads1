<div class="panel panel-success panel-dark">
	<div class="panel-heading">
	<span class="panel-title">List Matakuliah</span></div>

	<div class="table-responsive">
		<div class="table-header">
		<div class="table-caption text-danger">
		Cek List : <span id="dipilih"></span>
		</div></div>
    
		<table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
			<thead>
				<tr>
				<th class="text-center col-md-1">
				<input type="checkbox" name="checkAll" id="checkAll" value="">
				</th>
				<th class="text-center col-md-1">Kode</th>
				<th class="text-center">Nama Matakuliah</th>
				<th class="text-center col-md-1">SKS</th>
				<th class="text-center col-md-1">smt</th>
				<th class="text-center col-md-1">Aktif</th>
				<th class="text-center col-md-2">Prodi</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

@push('demo')
<script>
	init.push(function () {
		$('#c-tooltips-demo a').tooltip();
	});
</script>
@endpush

@push('scripts')
<script type="text/javascript">
$("#checkAll").click(function(){
	$('input:checkbox').not(this).prop('checked', this.checked);
	var jml = $('#cek_list:checked').length;
	$("#dipilih").html(jml);
});

var dataTable = $("#serversideTable").DataTable({
	processing: true,
	serverSide: true,
	paging : false,
	ajax : {
		url : "{{url($redirect)}}"+'/getDataMK',
		data : function (d) {
			d.prodi_id = $("#prodi_id").val();
			d.kurikulum_id = $("#kurikulum_id").val();
        }
	},
	columns: [
		{ data: 'cek_list', name: 'cek_list','class':'text-center','orderable':false, 'searchable':false},
		{ data: 'kode', name: 'kode','class':'text-center'},
		{ data: 'nama', name: 'nama'},
		{ data: 'sks', name: 'sks','class':'text-center'},
		{ data: 'smt', name: 'smt','class':'text-center'},
		{ data: 'aktif', name: 'aktif','class':'text-center'},
		{ data: 'prodi', name: 'prodi'},
	],
	"order": [[ 0, "DESC" ]]
});

$("#th_akademik_id").on('change',function(){
	dataTable.draw();
});
$("#prodi_id").on('change',function(){
	dataTable.draw();
});

function cekList(kd_id,kurikulum_id){
	var jml = $('#cek_list:checked').length;
	$("#dipilih").html(jml);
}

function deleteDetail(id){
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
				url : "{{ url($redirect)}}" + '/' + id + '/deleteDetail',
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
