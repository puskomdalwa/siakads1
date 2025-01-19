<div class="table-responsive">
  <table id="mengajarTable" class="table table-bordered table-condensed table-hover table-striped">
    <thead>
      <tr>
        <th class="text-center">Kode</th>
        <th class="text-center">Matakuliah</th>
        <th class="text-center col-md-1">SKS</th>
        <th class="text-center">Smt</th>
        <th class="text-center col-md-1">Ruang</th>
        <th class="text-center">Hari</th>
        <th class="text-center col-md-1">Waktu</th>
      </tr>
    </thead>
  </table>
</div>

@push('scripts')
<script type="text/javascript">
var dataTableMengajar = $("#mengajarTable").DataTable({
  processing: true,
  serverSide: true,
  paging : false,
  "searching": false,
  ajax      : {
        url : "{{url($redirect)}}"+'/getDataMengajar',
       data : function (d) {
              d.dosen_id = $("#dosen_id").val();
              d.th_akademik_id_dosen = $("#th_akademik_id_dosen").val();
        }
   },
  columns: [
      { data: 'kode_mk', name: 'kode_mk','class':'text-center'},
      { data: 'nama_mk', name: 'nama_mk'},
      { data: 'sks_mk', name: 'sks_mk','class':'text-center'},
      { data: 'smt', name: 'smt','class':'text-center'},
      { data: 'ruang', name: 'ruang','class':'text-center'},
      { data: 'hari', name: 'hari','class':'text-center'},
      { data: 'waktu', name: 'waktu','class':'text-center'},
  ],
  "order": [[ 0, "asc" ]]
});

$("#th_akademik_id_dosen").on('change',function(){
  dataTableMengajar.draw();
});
</script>
@endpush
