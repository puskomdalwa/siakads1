<form class="form-horizontal form-borderd" >
	<div class="panel-body no-padding-hr">
		<div class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
			<div class="row">
				<label class="col-sm-2 control-label text-danger">Tahun Angkatan:</label>
				<div class="col-sm-2">
					<select class="form-control" name="th_akademik_id" id="th_akademik_id">
						{{-- <option value="">-Pilih-</option> --}}
						@foreach($list_thakademik as $row)
							{{$select = old('th_akademik_id')==$row->id?'selected':''}}
							<option value="{{$row->id}}" {{$select}} > {{ $row->nama }}</option>
						@endforeach
						
						<!--
						@if(empty($prodi_id)) <option value="">-Pilih-</option>	@endif		
						@foreach($list_thakademik as $row)
							<option value="{{$row->id}}">{{substr($row->nama,0,4)}}</option>
						@endforeach
						-->
					</select>

					@if ($errors->has('th_akademik_id'))
						<span class="help-block"><strong>{{ $errors->first('th_akademik_id') }}</strong></span>
					@endif
				</div>
				
				<label class="col-sm-1 control-label">Kelas:</label>
				<div class="col-sm-2">
					<select class="form-control" name="kelas_id" id="kelas_id">
						@if(empty($kelas_id)) {{-- <option value="">-Pilih-</option> --}} @endif						
						@foreach($list_kelas as $kelas)
							{{$select = old('kelas_id')==$kelas->id?'selected':''}}
							<option value="{{$kelas->id}}" {{$select}} > {{ $kelas->nama }}</option>
						@endforeach
					</select>
				
					@if ($errors->has('kelas_id'))
						<span class="help-block"><strong>{{ $errors->first('kelas_id') }}</strong></span>
					@endif
				</div>
				
				<label class="col-sm-1 control-label">Status:</label>
				<div class="col-sm-2">
					<select class="form-control" name="status_id" id="status_id">
						<option value="">-SEMUA STATUS-</option>					
						@foreach($list_status as $status)
							{{$select = old('status_id')==$status->id?'selected':''}}
							<option value="{{$status->id}}" {{$select}} >{{$status->nama}}</option>
						@endforeach
					</select>

					@if ($errors->has('status_id'))
						<span class="help-block"><strong>{{ $errors->first('status_id') }}</strong></span>
					@endif
				</div>
			</div>
		</div>

		<div class="form-group{{ $errors->has('prodi_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
			<div class="row">
				<label class="col-sm-2 control-label text-danger">Program Studi:</label>
				<div class="col-sm-8">
					<select class="form-control" name="prodi_id" id="prodi_id">
						@if(empty($prodi_id))<option value="">-SEMUA PRODI-</option> @endif						
						@foreach($list_prodi as $prodi)
							<option value="{{$prodi->id}}">{{$prodi->nama}}</option>
						@endforeach
					</select>

					@if ($errors->has('prodi_id'))
						<span class="help-block"><strong>{{ $errors->first('prodi_id') }}</strong></span>
					@endif
				</div>
			</div>
		</div>
	</div>
	
	<!--
	<div class="panel-footer">
		<div class="col-sm-offset-2">
		<button type="button" name="filter" id="filter" class="btn btn-danger btn-flat">
		<i class="fa fa-filter"></i> Filter</button></div>
	</div>
	-->
	
</form>
