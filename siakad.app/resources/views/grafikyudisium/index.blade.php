@extends('layouts.app')
@section('title',$title)
@section('content')

<div class="panel panel-danger panel-dark">
  <div class="panel-heading">
    <span class="panel-title">@yield('title')</span>
  </div>

  <div class="panel-body">
    <canvas id="canvas" width="300" height="150"></canvas>
  </div>

</div>
@endsection


@push('scripts')
<script src="{{asset('js/Chart.min.js')}}" charset="utf-8"></script>
<script type="text/javascript">
var url = "{{url($folder.'/chart')}}";
var Labels = new Array();
var Data = new Array();
var Color = new Array();
$.get(url, function(response){
  response.forEach(function(data){
      Labels.push(data.nama_prodi);
      Data.push(data.total);
      Color.push(data.color);
  });
  // console.log(Data);
  var ctx = document.getElementById("canvas").getContext('2d');
      var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            datasets: [{
                backgroundColor: Color,
                data: Data
            }],
            labels: Labels
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            },
            title: {
                display: true,
                text: 'Yudisium Mahasiswa Tahun Akdemik '+{{$th_akademik->kode}}
            }
        }
    });
});

</script>
@endpush
