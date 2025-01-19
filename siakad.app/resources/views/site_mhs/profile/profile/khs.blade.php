<div class="tab-pane fade widget-followers" id="profile-tabs-khs">
	<!-- <form class="form-horizontal form-borderd" name="form-input" id="form-input" action="{{url($redirect.'/profile/cetakKHS')}}" method="post" target="blank"> -->
	<!-- <form action="{{route($redirect.'.cetakKHS')}} " class="form-horizontal" method="post" target="blank"> -->
	<!-- <form class="form-horizontal form-borderd" action="#"> -->
	<!-- <form action="{{url($redirect.'.profile'.'.index_khs')}} " class="form-horizontal" method="post" target="blank"> 
	<form class="form-horizontal form-borderd" action="{{url($redirect.'/profile/cetakKHS')}}" method="post" target="blank">
	-->
	
	<!-- $redirect => mahasiswa -->
	<form class="form-horizontal form-borderd" name="form-input" id="form-input" action="{{route($redirect.'.cetakKHS')}}" method="post" target="blank">
		{{ csrf_field() }}
		
		<div class="panel-body no-padding-hr">
    	<div class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Tahun Akademik: </label>
			<div class="col-sm-2">
				<select class="form-control" name="th_akademik_id_khs" id="th_akademik_id_khs" required>
					<option value="">-Pilih-</option>
					@foreach($list_thakademik as $thakademik)
						<option value="{{$thakademik->id}}" >{{$thakademik->kode}}</option>
					@endforeach
				</select>
				
				@if ($errors->has('th_akademik_id_khs'))
					<span class="help-block">
					<strong>{{ $errors->first('th_akademik_id_khs') }}</strong></span>
				@endif
			</div>
					
			<!--
			<a href="{{url($redirect.'/cetakKHS')}}" class="btn btn-xs btn-info" target="_blank"> 
			<i class="fa fa-print"></i> </a>
			-->

			<?php //$nim = "202185200001"; ?>

			
			<button type="submit" name="cetakKHS" id="cetakKHS" class="btn btn-info btn-flat">
			<i class="fa fa-print"></i> Cetak KHS
			</button>			
			
		</div></div></div>
	</form>
	
	@include('site_mhs.profile.profile.index_khs')
	
	{{--
	@include('mahasiswa.profile.index_khs')
	@include('site_mhs.profile.profile.index_khs')
	--}}
	
</div> 
<!-- / .tab-pane -->
