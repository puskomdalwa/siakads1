<div class="tab-pane fade widget-followers" id="profile-tabs-krs">
	<!-- 
	<form class="form-horizontal form-borderd" name="form-input" id="form-input" action="#" method="post" target="blank">
	<form class="form-horizontal form-borderd" action="#">
	<form class="form-horizontal form-borderd" action="{{url($redirect.'/mhs_krs')}}" method="post" target="blank">
	-->

	<form class="form-horizontal form-borderd" name="form-input" id="form-input" action="{{route($redirect.'.cetakKRS')}}" method="post" target="blank">
		{{ csrf_field() }}
		
		<div class="panel-body no-padding-hr">
		<div class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Tahun Akademik:</label>
			<div class="col-sm-2">
				<select class="form-control" name="th_akademik_id_krs" id="th_akademik_id_krs" required>
					<option value="">-Pilih-</option>
					@foreach($list_thakademik as $thakademik)
						<option value="{{$thakademik->id}}" >{{$thakademik->kode}}</option>
					@endforeach
				</select>
				
				@if ($errors->has('th_akademik_id_krs'))
					<span class="help-block">
					<strong>{{ $errors->first('th_akademik_id_krs') }}</strong></span>
				@endif
			</div>			
		
			<button type="submit" name="cetakKRS" id="cetakKRS" class="btn btn-info btn-flat">
			<i class="fa fa-print"></i> Cetak KRS 
			</button>			
			
		</div></div></div>
	</form>
		
	@include('site_mhs.profile.profile.index_krs')
	
	{{--
	@include('mahasiswa.profile.index_krs')
	@include('site_mhs.profile.profile.index_krs')
	--}}
	
</div> 

<!-- / .tab-pane -->
<!-- {url('krs/'.$row->id.'/cetak')}}" -->

