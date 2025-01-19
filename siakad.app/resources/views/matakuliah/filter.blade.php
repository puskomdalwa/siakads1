<form class="form-horizontal form-borderd" >
	<div class="panel-body no-padding-hr">
	<div class="form-group{{ $errors->has('prodi_id') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-3 control-label">Program Studi:</label>
			<div class="col-sm-5">
				<select class="form-control" name="prodi_id" id="prodi_id">
					@if(empty($prodi_id)) <option value="">-Semua Prodi-</option> @endif
					@foreach($list_prodi as $prodi)
					{{$select = old('prodi_id')==$prodi->id ? 'selected':''}}
					<option value="{{$prodi->id}}"{{$select}}>{{$prodi->nama}}</option>
					@endforeach
				</select>

				@if ($errors->has('prodi_id'))
					<span class="help-block">
					<strong>{{ $errors->first('prodi_id') }}</strong></span>
				@endif
			</div>

			<label class="col-sm-1 control-label">Semester:</label>
			<div class="col-sm-1">
				<select class="form-control" name="smt" id="smt">
					<option value="">-Semua-</option>					
					@foreach($list_smt as $smt)
						{{$select = old('smt')==$smt->smt ? 'selected':''}}
						<option value="{{$smt->smt}}" {{$select}} >{{$smt->smt}}</option>
					@endforeach
				</select>

				@if ($errors->has('smt'))
					<span class="help-block">
					<strong>{{ $errors->first('smt') }}</strong></span>
				@endif
			</div>
		</div></div>
	</div>

	<!--
	<div class="panel-footer">
	<div class="col-sm-offset-2">
		<button type="button" name="filter" id="filter" class="btn btn-danger btn-flat">
		<i class="fa fa-filter"></i> Filter</button>
	</div></div>
	-->
</form>
