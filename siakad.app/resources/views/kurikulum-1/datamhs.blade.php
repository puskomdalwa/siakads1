<div class="table-responsive">
  <span class="label label-danger">Cek List : <span id="dipilih"></span> </span>
  <table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
    <thead>
      <tr>
        <th class="text-center col-md-1">
          <input type="checkbox" name="checkAll" id="checkAll" value="">
        </th>
        <th class="text-center col-md-1">NIM</th>
        <th class="text-center">Nama Mahasiswa</th>
        <th class="text-center col-md-1">L/P</th>
        <th class="text-center col-md-1">Prodi</th>
        <th class="text-center col-md-1">Kelas</th>
        <th class="text-center col-md-1">Kelompok</th>
        <th class="text-center col-md-1">Status</th>
      </tr>
    </thead>
  </table>
</div>

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
  ajax      : {
        url : "{{url($redirect)}}"+'/getDataMhs',
       data : function (d) {
              d.perwalian_id = $("#perwalian_id").val();
              d.th_akademik_id = $("#th_akademik_id").val();
              d.prodi_id = $("#prodi_id").val();
              d.kelas_id = $("#kelas_id").val();
              d.kelompok_id = $("#kelompok_id").val();
        }
   },
  columns: [
      { data: 'cek_list', name: 'cek_list','class':'text-center','orderable':false, 'searchable':false},
      { data: 'nim', name: 'nim','class':'text-center'},
      { data: 'nama', name: 'nama'},
      { data: 'jk', name: 'jk','class':'text-center'},
      { data: 'prodi', name: 'prodi'},
      { data: 'kelas', name: 'kelas','class':'text-center'},
      { data: 'kelompok', name: 'kelompok','class':'text-center'},
      { data: 'status', name: 'status','class':'text-center'},
  ],
  "order": [[ 1, "DESC" ]]
});

$("#th_akademik_id").on('change',function(){
  dataTable.draw();
});
$("#prodi_id").on('change',function(){
  dataTable.draw();
});
$("#kelas_id").on('change',function(){
  dataTable.draw();
});
$("#kelompok_id").on('change',function(){
  dataTable.draw();
});

function cekList(nim,perwalian_id)
{
  // alert(perwalian_id);
  // deleteDetail(nim,perwalian_id);

  var jml = $('#cek_list:checked').length;
  $("#dipilih").html(jml);
}

function deleteDetail(nim,perwalian_id)
{
  var csrf_token = $('meta[name="csrf-token"]').attr('content');
  $.ajax({
    url : "{{ url($redirect)}}" + '/' + nim + '/' + perwalian_id + '/deleteDetail',
    type : "POST",
    data : {'_method' : 'DELETE', '_token' : csrf_token },
    success : function(data){

    }
  });
}
</script>
@endpush
