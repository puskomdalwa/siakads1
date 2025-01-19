@extends('layouts.app')
@section('title',' Profile '.$title)

@section('content')
<div class="panel panel-danger panel-dark">
	<div class="panel-heading">
	<span class="panel-title">@yield('title')</span></div>

	<div class="panel-body no-padding-hr">
		<div class="profile-full-name">
			<input type="hidden" name="dosen_id" id="dosen_id" value="{{$data->id}}">
			<span class="text-semibold">{{$data->nama}}</span> {!!strtolower($data->status->nama)=='aktif'?
			'<span class="label label-success">'.$data->status->nama.'</span>':
			'<span class="label label-danger">'.$data->status->nama.'</span>' !!}
		</div>
		
		<div class="profile-row">
			<div class="left-col">
			<div class="profile-block">
				<div class="panel profile-photo">
					{{-- <img src="{{asset('img/logo.png')}}" alt=""> --}}
					@php 
					$picture = @App\User::where('username',$data->kode)->first()->picture;
					@endphp
					
					@if($picture)
						<img src="{{asset('picture_users/'.$picture)}}" alt="">
					@else
						<img src="{{asset('img/logo.png')}}" alt="">
					@endif
				</div><br>
				
				<a href="{{url($redirect.'/'.$data->id.'/getResetPassword')}}" class="btn btn-danger btn-block">
				<i class="fa fa-key"></i>&nbsp;&nbsp;Reset Password</a>
				<a href="{{url($redirect)}}" class="btn btn-primary btn-block">
				<i class="fa fa-times-circle-o"></i>&nbsp;&nbsp;Tutup</a>
			</div></div>
			@include('dosen.profile.tab')
		</div>
	</div>
</div>
@endsection

@push('css')
<link href="{{asset('assets/stylesheets/pages.min.css')}}" rel="stylesheet" type="text/css">
@endpush

@push('demo')
<script type="text/javascript">
  init.push(function () {
    $('#profile-tabs').tabdrop();

    $("#leave-comment-form").expandingInput({
      target: 'textarea',
      hidden_content: '> div',
      placeholder: 'Write message',
      onAfterExpand: function () {
        $('#leave-comment-form textarea').attr('rows', '3').autosize();
      }
    });
  });

</script>
@endpush
