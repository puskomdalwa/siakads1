<!--	
<label class="col-sm-2 control-label">T.Akademik Aktif:</label>
<div class="col-sm-1">
	{!! Form::hidden('th_akademik_id',$th_akademik->id,['class' => 'form-control','id'=>'th_akademik_id','required'=>'true','readonly' => 'true']) !!}
	{!! Form::text('th_akademik_kode',$th_akademik->kode,['class' => 'form-control','id'=>'th_akademik_kode','required'=>'true','readonly' => 'true']) !!}
</div>
-->
			
<form class="form-horizontal form-borderd" >
	<div class="panel-body no-padding-hr">
		<div class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">			
			<label class="col-sm-2 control-label text-danger">Tahun Akademik:</label>
			<div class="col-sm-1">
				<select clsass="form-control" name="th_akademik_id" id="th_akademik_id">				
					<!-- <option value="">-Pilih-</option> -->					
					@foreach($list_thakademik as $row)
						@php $select = $th_akademik_id==$row->id ? 'selected' : ''; @endphp 
						<option value="{{$row->id}}" {{$select}} >{{$row->kode}}</option>
					@endforeach
				</select>

				@if ($errors->has('th_akademik_id'))
					<span class="help-block"><strong>{{ $errors->first('th_akademik_id') }}</strong></span>
				@endif
			</div>			
						
			<label class="col-sm-1 control-label">Prodi:</label>
			<div class="col-sm-4">
				<select class="form-control" name="prodi_id" id="prodi_id">
					{{-- @if(empty($prodi_id)) <option value=""> Semua Prodi </option> @endif --}}
					<option value="">-Semua Prodi-</option>
					@foreach($list_prodi as $prodi)
						@php $select = $prodi_id==$prodi->id ? 'selected' : ''; @endphp
						<option value="{{$prodi->id}}" {{$select}} >{{$prodi->nama}}</option>
					@endforeach
				</select>
				
				@if ($errors->has('prodi_id'))
					<span class="help-block"><strong>{{ $errors->first('prodi_id') }}</strong></span>
				@endif
			</div>	

			<label class="col-sm-1 control-label">Kelas:</label>
			<div class="col-sm-2">
				<select class="form-control" name="kelas_id" id="kelas_id">
					<option value="">-Pilih-</option>
					@foreach($list_kelas as $kelas)
						@php $select = $kelas_id==$kelas->id ? 'selected' : ''; @endphp
						<option value="{{$kelas->id}}" {{$select}} >{{$kelas->nama}}</option>
					@endforeach
				</select>

				@if ($errors->has('kelas_id'))
					<span class="help-block"><strong>{{ $errors->first('kelas_id') }}</strong></span>
				@endif
			</div>			
		</div></div>

		<!--
		<div class="form-group{{ $errors->has('kelas_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
			<div class="row">
				<label class="col-sm-2 control-label">Program Studi:</label>
				<div class="col-sm-5">
					<select class="form-control" name="prodi_id" id="prodi_id">
					@if(empty($prodi_id))
						<option value="">-Pilih-</option>
					@endif
					
					@foreach($list_prodi as $prodi)
						<option value="{{$prodi->id}}">{{$prodi->nama}}</option>
					@endforeach
					</select>
					
					@if ($errors->has('prodi_id'))
						<span class="help-block">
						<strong>{{ $errors->first('prodi_id') }}</strong>
						</span>
					@endif
				</div>

				<label class="col-sm-1 control-label">Kelas:</label>
				<div class="col-sm-2">
					<select class="form-control" name="kelas_id" id="kelas_id">
					<option value="">-Pilih-</option>
					@foreach($list_kelas as $kelas)
						<option value="{{$kelas->id}}">{{$kelas->nama}}</option>
					@endforeach
					</select>

					@if ($errors->has('kelas_id'))
						<span class="help-block">
						<strong>{{ $errors->first('kelas_id') }}</strong>
						</span>
					@endif
				</div>
			</div>
		</div>
		-->
	</div>

	<!--
	<div class="panel-footer">
		<div class="col-sm-offset-2">
		<button type="button" name="filter" id="filter" class="btn btn-danger btn-flat">
		<i class="fa fa-filter"></i> Filter</button>
	</div>
	-->
	
</form>