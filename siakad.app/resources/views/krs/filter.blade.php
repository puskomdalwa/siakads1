<form class="form-horizontal form-borderd" >
	<div class="panel-body no-padding-hr">
		<div class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label text-danger">Tahun Akademik:</label>
			<div class="col-sm-2">
				<select class="form-control" name="th_akademik_id" id="th_akademik_id">
				<option value="">-Pilih-</option>
				@foreach($ta_thakademik as $row)
					@php $select = $th_akademik_id==$row->id ? 'selected' : ''; @endphp
					<option value="{{$row->id}}" {{$select}} >{{$row->kode}}</option>
				@endforeach
				</select>

				@if ($errors->has('th_akademik_id'))
					<span class="help-block"><strong>{{ $errors->first('th_akademik_id') }}</strong></span>
				@endif
			</div>			
			
			<label class="col-sm-2 control-label text-danger">Tahun Angkatan:</label>
			<div class="col-sm-2">
				<select class="form-control" name="th_angkatan_id" id="th_angkatan_id">
				<option value="">Semua</option>
				@foreach($list_thakademik as $row)
					@php $select = $th_akademik_id==$row->id ? 'selected' : ''; @endphp
					<option value="{{$row->id}}" {{$select}} >{{$row->nama}}</option>
				@endforeach
				</select>

				@if ($errors->has('th_angkatan_id'))
					<span class="help-block"><strong>{{ $errors->first('th_angkatan_id') }}</strong></span>
				@endif
			</div>
		</div></div>			
			
		<div class="form-group{{ $errors->has('kelas_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Kelas:</label>
			<div class="col-sm-2">
				<select class="form-control" name="kelas_id" id="kelas_id">
					@if(empty($kelas_id)) <option value="">-Pilih-</option> @endif
					
					@foreach($list_kelas as $kelas)
						<option value="{{$kelas->id}}">{{$kelas->nama}}</option>
					@endforeach
				</select>
				
				@if ($errors->has('kelas_id'))
					<span class="help-block"><strong>{{ $errors->first('kelas_id') }}</strong></span>
				@endif
			</div>	

			<label class="col-sm-2 control-label">Program Studi:</label>
			<div class="col-sm-4">
				<select class="form-control" name="prodi_id" id="prodi_id">
					@if(empty($prodi_id)) <option value="">-Pilih-</option> @endif
					
					@foreach($list_prodi as $prodi)
						<option value="{{$prodi->id}}">{{$prodi->nama}}</option>
					@endforeach
				</select>
				
				@if ($errors->has('prodi_id'))
					<span class="help-block"><strong>{{ $errors->first('prodi_id') }}</strong></span>
				@endif
			</div>	
			
			<div class="col-sm-offset-0">
			<button type="button" name="filter" id="filter" class="btn btn-danger btn-flat">
			<i class="fa fa-filter"></i> Filter</button>
			</div>
		</div></div>		
	</div>	
	
	<!--
	<div class="panel-footer text-center">
	<div class="col-sm-offset-0">
		<button type="button" name="filter" id="filter" class="btn btn-danger btn-flat">
		<i class="fa fa-filter"></i> Filter</button>
	</div></div>	
	-->
	
</form>
