<form class="form-horizontal form-borderd" name="form-input" id="form-input" action="{{route($redirect.'.cetak')}}" method="post" target="blank">
	{{ csrf_field() }}
	
	<div class="panel-body no-padding-hr">
		<div class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label text-danger">Tahun Angkatan: </label>
			<div class="col-sm-2">
				<select class="form-control" name="th_akademik_id" id="th_akademik_id" required>
					{{-- <option value="">-Pilih-</option> --}}
					@foreach($list_thakademik as $row)
						<option value="{{$row->id}}">{{$row->nama}}</option>
					@endforeach
				</select>

				@if ($errors->has('th_akademik_id'))
					<span class="help-block"><strong>{{ $errors->first('th_akademik_id') }}</strong></span>
				@endif
			</div>
			
			<label class="col-sm-1 control-label">Kelas:</label>
			<div class="col-sm-2">
				<select class="form-control" name="kelas_id" id="kelas_id">
					@if(empty($kelas_id)) <option value="">-Pilih Kelas-</option> @endif
					@foreach($list_kelas as $row)
						<option value="{{$row->id}}">{{$row->nama}}</option>
					@endforeach
				</select>
			</div>
			
			<label class="col-sm-1 control-label">Status:</label>
			<div class="col-sm-2">
				<select class="form-control" name="status_id" id="status_id">
					@if(empty($status_id)) <option value="">-Pilih Status-</option> @endif
					@foreach($list_status as $row)
						<option value="{{$row->id}}">{{$row->nama}}</option>
					@endforeach
				</select>
			</div>			
		</div></div>

		<div class="form-group{{ $errors->has('prodi_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
		<!-- <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t"> -->
		<div class="row">
			<label class="col-sm-2 control-label">Program Studi:</label>
			<div class="col-sm-5">			
				<select class="form-control" name="prodi_id" id="prodi_id">
					@if(empty($prodi_id)) <option value="">-Pilih Program Studi-</option> @endif
					@foreach($list_prodi as $prodi)
						<!-- <option value="{{$prodi->id}}">{{$prodi->nama}}</option> -->
						{{$select = old('prodi_id')==$row->id?'selected':''}}
						<option value="{{$prodi->id}}" {{$select}} >{{$prodi->nama}}</option>
					@endforeach
				</select>
			</div>			

			<label class="col-sm-1 control-label">Kelompok:</label>
			<div class="col-sm-3">
				<select class="form-control" name="kelompok_id" id="kelompok_id">
					<!--
					<option value="">-Pilih Kelompok-</option>				
					@foreach($list_kelompok as $kelompok)
						<option value="{{$kelompok->id}}">{{$kelompok->nama}}</option>
					@endforeach
					-->
				</select>
				
				@if ($errors->has('kelompok_id'))
					<span class="help-block"><strong>{{$errors->first('kelompok_id')}}</strong></span>
				@endif
			</div>	
		</div></div>		
	
		<!--
		<div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Status:</label>
			<div class="col-sm-2">
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
	
	<div class="panel-footer text-center">
		<!--
		<button type="button" name="filter" id="filter" class="btn btn-danger btn-flat">
		<i class="fa fa-filter"></i> Filter</button>
		-->
		
		<button type="submit" name="cetak" id="cetak" value="pdf" class="btn btn-info btn-flat">
		<i class="fa fa-print"></i> Cetak PDF</button>
		
		<button type="submit" name="cetak" id="cetak" value="excel" class="btn btn-info btn-flat">
		<i class="fa fa-th"></i> Cetak Excel</button>
	</div>
</form>
