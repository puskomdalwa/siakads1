@extends('layouts.app')
@section('title',$title)
@section('content')

<div class="panel panel-danger panel-dark">
  <div class="panel-heading">
    <span class="panel-title">@yield('title') Jumlah Mahasiswa {{ $data->count() }} </span>
    
  </div>
</div>

<div class="row">
    @foreach ($data as $row)
    <div class="col-md-4">
      <div class="panel panel-success panel-dark panel-body-colorful widget-profile widget-profile-centered">
          <div class="panel-heading">
            @php 
            $pic = App\User::where('username',$row->pengajuan->mahasiswa->nim)->first()->picture;
            @endphp
            @if($pic)
            <img src="{{ asset('picture_users/'.$pic) }}" alt="" class="widget-profile-avatar">
            @else
              <img src="{{ asset('assets/demo/avatars/2.jpg') }}" alt="" class="widget-profile-avatar">
            @endif
              <div class="widget-profile-header">
                  <span>{{ $row->pengajuan->mahasiswa->nim }} </span><br>
                  <span> {{ strtoupper($row->pengajuan->mahasiswa->nama) }} </span><br>
                  {{ $row->pengajuan->mahasiswa->jk->nama }}<br>
                  {{ $row->pengajuan->mahasiswa->prodi->nama }}
              </div>
          </div> <!-- / .panel-heading -->
          <div class="panel-body">
              <div class="widget-profile-text" style="padding: 0;">
                {!! $row->judul !!}
              </div>
          </div>
      </div>
  </div> 
    @endforeach
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

</script>
@endpush
