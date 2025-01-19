@extends('layouts.app')
@section('title',$title)

@section('content')

<div class="panel panel-danger panel-dark">
	<div class="panel-heading">
	<span class="panel-title">@yield('title')</span>
	</div>

	<div class="panel-body no-padding-hr">
    <div class="table-responsive">
		<table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
			<thead>
				<tr>
				<th class="text-center">Pertanyaan</th>
				<th class="text-center col-md-1">Nilai</th>
				</tr>
			</thead>

			<tfoot>
				<tr>
				<th colspan="1" style="text-align:right">Total (Nilai Total/Jumlah Pertanyaan)</th>
				<th></th>
				</tr>
			</tfoot>
		</table>
    </div>
	</div>
</div>

<div class="panel panel-danger panel-dark">
	<div class="panel-heading">
    <span class="panel-title">Kekurangan dan Kelebihan</span>
	</div>

  <div class="panel-body">
    <p>Kekurangan yang harus diperbaiki : </p>
    <ol>
      @foreach($kuesioner_jawaban as $jawab)
        <li>{{$jawab->kekurangan}}</li>
      @endforeach
    </ol>
    <br/>
    <p>Kelebihan yang harus dipertahankan : </p>
    <ol>
      @foreach($kuesioner_jawaban as $jawab)
        <li>{{$jawab->kelebihan}}</li>
      @endforeach
    </ol>
  </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">

var dataTable = $("#serversideTable").DataTable({
  processing: true,
  serverSide: true,
  paging : false,
	"searching": false,
  ajax : "{{url($redirect)}}"+'/getData',
  columns: [
      { data: 'pertanyaan', name: 'pertanyaan'},
      { data: 'nilai', name: 'nilai','class':'text-center'},
  ],
  "order": [[ 1, "desc" ]],
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
            .column( 1 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        // Total over this page
        pageTotal = api
            .column( 1, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        // Update footer
        // $( api.column( 4 ).footer() ).html(
        //     ''+pageTotal +' ( '+ total +' )'
        // );
        $( api.column( 1 ).footer() ).html(total/{{$kuesioner_tanya}});
    }
});

</script>
@endpush
