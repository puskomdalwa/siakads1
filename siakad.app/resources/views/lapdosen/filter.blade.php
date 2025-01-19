<form class="form-horizontal form-borderd" name="form-input" id="form-input" action="{{route($redirect.'.cetak')}}" method="post" target="blank">
	{{ csrf_field() }}
	
	<div class="panel-body no-padding-hr">
		<div class="form-group{{ $errors->has('prodi_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Program Studi:</label>
			<div class="col-sm-5">
				<select class="form-control" name="prodi_id" id="prodi_id">
					@if(empty($prodi_id)) <option value="">-Pilih Program Studi-</option> @endif
					@foreach($list_prodi as $prodi)
						<option value="{{$prodi->id}}">{{$prodi->nama}}</option>
					@endforeach
				</select>
			</div>

			@if ($errors->has('prodi_id'))
				<span class="help-block"><strong>{{ $errors->first('prodi_id') }}</strong></span>
			@endif

			<label class="col-sm-1 control-label">Status:</label>
			<div class="col-sm-2">
				<select class="form-control" name="status_id" id="status_id">
					<option value="">Semua-</option>
					@foreach($list_status as $row)
						<option value="{{$row->id}}">{{$row->nama}}</option>
					@endforeach
				</select>
			</div>
				
			<button type="submit" name="cetak" id="cetak" class="btn btn-info btn-flat">
			<i class="fa fa-print"></i> Cetak</button>
		</div></div>

		<!--
		<div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Status:</label>
			<div class="col-sm-3">
			<select class="form-control" name="status_id" id="status_id">
			<option value="">-All-</option>
			@foreach($list_status as $row)
			<option value="{{$row->id}}">{{$row->nama}}</option>
			@endforeach
			</select>
			</div>
		</div></div>
		-->
	</div>

	<!-- 
	<div class="panel-footer text-center">
	<button type="button" name="filter" id="filter" class="btn btn-danger btn-flat">
		<i class="fa fa-filter"></i> Filter</button>
		
		<button type="submit" name="cetak" id="cetak" class="btn btn-info btn-flat">
		<i class="fa fa-print"></i> Print</button>
	</div>
	-->
</form>
