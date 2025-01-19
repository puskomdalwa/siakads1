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
  </div>
 <div class="table-responsive">
   <table id="serversideTable" class="table table-hover table-bordered table-condensed">
     <thead>
       <tr>
         <th class="text-center col-md-1">NIM</th>
         <th class="text-center">Nama Mahasiswa</th>
         <th class="text-center">L/P</th>
         <th class="text-center">Prodi</th>
         <th class="text-center">Kelas</th>
         <th class="text-center">Klp</th>
         <th class="text-center">Status</th>
         <th class="text-center col-md-1">Action</th>
       </tr>
     </thead>
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
var dataTable = $("#serversideTable").DataTable({
  processing: true,
  serverSide: true,
  paging : false,
  // "searching": false,
  ajax      : {
        url : "{{url($redirect)}}"+'/getData',
       data : function (d) {
              d.th_akademik_id = $("#th_akademik_id").val();
              d.th_angkatan_id = $("#th_angkatan_id").val();
              d.prodi_id = $("#prodi_id").val();
        }
   },
  columns: [
      { data: 'nim', name: 'nim','class':'text-center'},
      { data: 'nama', name: 'nama'},
      { data: 'jk', name: 'jk','class':'text-center'},
      { data: 'prodi', name: 'prodi'},
      { data: 'kelas', name: 'kelas','class':'text-center'},
      { data: 'kelompok', name: 'kelompok','class':'text-center'},
      { data: 'status', name: 'status','class':'text-center'},
      { data: 'action', name: 'action','orderable':false, 'searchable':false,'class':'text-center' },
  ],
  "order": [[ 0, "asc" ]]
});

$("#th_angkatan_id").on('change',function(){
  dataTable.draw();
});

$("#prodi_id").on('change',function(){
  dataTable.draw();
});

function getStatus(nim)
{
  // var status_id = $("#status_id_"+id).val();
  // console.log(status_id);
  var string = {
    nim : nim,
    status_id : $("#status_id_"+nim).val(),
    th_akademik_id : $("#th_akademik_id").val(),
    _token: "{{ csrf_token() }}"
  };
  $.ajax({
    url   : "{{ url($folder) }}",
    method : 'POST',
    data : string,
    success:function(data){
      dataTable.draw();
      if(data.type=='success')
      {
        swal(data.title,data.text,data.type)
      }
    }
  });
}


</script>
@endpush
