<form class="form-horizontal form-borderd" name="form-input" id="form-input" action="{{route($redirect.'.cetak')}}" method="post" target="blank">
	{{ csrf_field() }}

	<div class="panel-body no-padding-hr">
	<div class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-4 control-label">Program Studi:</label>
		<div class="col-sm-4">
			<select class="form-control" name="prodi_id" id="prodi_id">
				@if(empty($prodi_id)) <option value="">-All-</option> @endif
				@foreach($list_prodi as $prodi)
					<option value="{{$prodi->id}}">{{$prodi->nama}}</option>
				@endforeach
			</select>
		</div>

		<button type="submit" name="cetak" id="cetak" class="btn btn-info btn-flat">
		<i class="fa fa-print"></i> Print</button>
	</div></div></div>

	<!--
	<div class="panel-footer text-center">
		<button type="button" name="filter" id="filter" class="btn btn-danger btn-flat">
		<i class="fa fa-filter"></i> Filter</button>
		
		<button type="submit" name="cetak" id="cetak" class="btn btn-info btn-flat">
		<i class="fa fa-print"></i> Print</button>
	</div>
	-->
	
</form>
