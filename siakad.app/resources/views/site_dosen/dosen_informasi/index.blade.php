@extends('layouts.app')
@section('title',$title)

@section('content')
<div class="panel widget-messages-alt panel-danger panel-dark">
	<div class="panel-heading">
	<span class="panel-title"><i class="panel-title-icon fa fa-envelope"></i>{{$title}}</span>
	</div> <!-- / .panel-heading -->
	
	<div class="panel-body padding-sm">
		<div class="messages-list">
			@foreach($data as $row)
				<div class="message">
					@if(!empty($row->pengguna->picture))
						{{-- <img src="assets/demo/avatars/2.jpg" alt="" class="message-avatar"> --}}
						<img src="{{asset('picture_users/'.$row->pengguna->picture)}}" alt="" class="message-avatar">
					@else
						<img src="{{asset('assets/demo/avatars/deddyrusdiansyah.jpg')}}" alt="" class="message-avatar">
						{{-- <img src="assets/demo/avatars/2.jpg" alt="" class="message-avatar"> --}}
					@endif

					<a href="#" class="message-subject">{{$row->judul}}</a>
					
					<div class="message-description">
						Dari : <a href="#">{{$row->pengguna->name}}</a>	&nbsp;&nbsp;·&nbsp;&nbsp;
						Tanggal Posting : {{tgl_str($row->tanggal)}}
					</div> <!-- / .message-description -->
				</div> <!-- / .message -->
				{!! $row->isi !!}
				<hr/>
			@endforeach
		</div> <!-- / .messages-list -->

		{{-- <a href="#" class="messages-link">MORE MESSAGES</a> --}}
		{{ $data->links() }}
	</div> <!-- / .panel-body -->
</div>
@endsection
