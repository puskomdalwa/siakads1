@extends('layouts.app')
@section('title',$title)
@section('content')

<div class="panel panel-danger panel-dark">
  <div class="panel-heading">
    <span class="panel-title">@yield('title')</span>
  </div>

  <div class="panel-body">
    <canvas id="myChart" width="400" height="200"></canvas>
  </div>

</div>
@endsection


@push('scripts')
<script src="{{asset('js/Chart.min.js')}}" charset="utf-8"></script>
<script type="text/javascript">
var ctx = document.getElementById("myChart");
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo $json_label?>,
        datasets: <?php echo $json_datasets?>
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
            text: 'Berdasarkan Tahun Angkatan '
        }
    }
});
</script>
@endpush
