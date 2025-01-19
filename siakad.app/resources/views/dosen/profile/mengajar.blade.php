<div class="tab-pane fade widget-followers" id="profile-tabs-mengajar">
	<form class="form-horizontal form-borderd" action="#">
		{{ csrf_field() }}
		<div class="panel-body no-padding-hr">
		<div class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} 
			no-margin-hr panel-padding-h no-padding-t no-border-t">
			<div class="row">
				<label class="col-sm-2 control-label">Tahun Akademik:</label>
				<div class="col-sm-2">
					<select class="form-control" name="th_akademik_id_dosen" id="th_akademik_id_dosen" required>
					<option value="">-Pilih-</option>
					@foreach($list_thakademik as $thakademik)
						<option value="{{$thakademik->id}}" >{{$thakademik->kode}}</option>
					@endforeach
					</select>
					
					@if ($errors->has('th_akademik_id'))
						<span class="help-block">
						<strong>{{ $errors->first('th_akademik_id') }}</strong>
						</span>
					@endif
				</div>
			</div>
		</div></div>
	</form>
	
	@include('dosen.profile.index_mengajar')
</div>
