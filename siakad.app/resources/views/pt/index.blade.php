@extends('layouts.app')
@section('title',$title)
@section('content')

<div class="panel panel-danger panel-dark">
  <div class="panel-heading">
    <span class="panel-title">@yield('title')</span>
  </div>

  {!! Form::open(['route' => $redirect.'.store','files'=>'true','class'=>'form-horizontal form-bordered']) !!}
  @include($folder.'.form')
  {!! Form::close() !!}
</div>
@endsection

@push('demo')
<script type="text/javascript">
  init.push(function () {
    $("#kota_id").select2({
			allowClear: true,
			placeholder: "Pilih Kota"
		});
  });
</script>
@endpush

@push('scripts')
<script type="text/javascript">
</script>
@endpush
