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
   <table id="serversideTable" class="table table-hover table-bordered">
     <thead>
       <tr>
         <th></th>
         <th class="text-center col-md-2">Kode</th>
         <th class="text-center">Mata Kuliah</th>
         <th class="text-center col-md-1">SKS</th>
         <th class="text-center col-md-1">smt</th>
         <th class="text-center col-md-1">RPS</th>
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
   <div class="label label-info">Detail Jadwal</div>
   <table class="table details-table table-vcenter table-condensed table-bordered table-hover table-striped" id="posts-@{{id}}">
     <thead>
       <tr>
        <th class="text-center col-md-1">Kelas</th>
        <th class="text-center col-md-1">Klp</th>
        <th class="text-center col-md-1">Hari</th>
        <th class="text-center col-md-1">Ruang</th>
        <th class="text-center col-md-2">Waktu</th>
        <th class="text-center">Dosen</th>
        <th class="text-center">File</th>
       </tr>
     </thead>
   </table>
</script>
<script type="text/javascript">
var template = Handlebars.compile($("#details-template").html());
var dataTable = $("#serversideTable").DataTable({
  processing: true,
  serverSide: true,
  paging : false,
  "searching": false,
  ajax      : {
        url : "{{url($redirect)}}"+'/getData',
       data : function (d) {
              d.th_akademik_id = $("#th_akademik_id").val();
              d.prodi_id = $("#prodi_id").val();
              d.kelas_id = $("#kelas_id").val();
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
      { data: 'kd_mk', name: 'kd_mk','class':'text-center'},
      { data: 'nama_mk', name: 'nama_mk'},
      { data: 'sks_mk', name: 'sks_mk','class':'text-center'},
      { data: 'smt_mk', name: 'smt_mk','class':'text-center'},
      { data: 'jml_rps', name: 'jml_rps','class':'text-center'},
  ],
  "order": [[ 4, "asc" ],[ 1, "asc" ]]
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
            { data: 'kelas', name: 'kelas','class':'text-center' },
            { data: 'kelompok', name: 'kelompok','class':'text-center' },
            { data: 'hari', name: 'hari','class':'text-center'  },
            { data: 'ruang', name: 'ruang','class':'text-center' },
            { data: 'waktu', name: 'waktu','class':'text-center' },
            { data: 'dosen', name: 'dosen' },
            { data: 'file_rps', name: 'file_rps' },
        ],
        "order": [[ 0, "asc" ]]
      });
}


$("#th_akademik_id").on('change',function(){
  dataTable.draw();
});

$("#prodi_id").on('change',function(){
  dataTable.draw();
});

$("#kelas_id").on('change',function(){
  dataTable.draw();
});



</script>
@endpush
