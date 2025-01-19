<div class="tab-pane fade widget-followers" id="profile-tabs-perwalian">
<div class="table-responsive">
    <table id="perwalianTable" class="table table-bordered table-condensed table-hover table-striped">
		<thead>
			<tr>
				<th class="text-center">NIM</th>
				<th class="text-center">Nama Mahasiswa</th>
				<th class="text-center col-md-1">L/P</th>
				<th class="text-center col-md-1">Prodi</th>
				<th class="text-center col-md-1">Kelas</th>
				<th class="text-center col-md-1">Kelomp</th>
				<th class="text-center col-md-1">Status</th>
				<th class="text-center">KRS</th>
				<th class="text-center">ACC</th>
			</tr>
		</thead>
    </table>
</div></div>

@push('scripts')
<script type="text/javascript">
var dataTablePerwalian = $("#perwalianTable").DataTable({
	processing : true,
	serverSide : true,
	paging	   : false,
	"searching": true,
	ajax : {
		url : "{{url($redirect)}}"+'/getDataPerwalian',
		data : function (d) {
			d.dosen_id = $("#dosen_id").val();
        }
	},
	columns: [
		{ data: 'nim',	  	name: 'nim','class':'text-center'},
		{ data: 'nama_mhs', name: 'nama_mhs'},
		{ data: 'jk',		name: 'jk','class':'text-center'},
		{ data: 'prodi',	name: 'prodi','class':'text-center'},
		{ data: 'kelas',	name: 'kelas','class':'text-center'},
		{ data: 'kelompok', name: 'kelompok','class':'text-center'},
		{ data: 'status',	name: 'status','class':'text-center'},
		{ data: 'krs',	  	name: 'krs','class':'text-center'},
		{ data: 'krs_acc',  name: 'krs_acc','class':'text-center'},
	],
	"order": [[ 0, "asc" ]]
});
</script>
@endpush
