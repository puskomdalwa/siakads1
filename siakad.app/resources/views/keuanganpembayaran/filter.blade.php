<form class="form-horizontal form-borderd" action="{{url($redirect.'/cetak')}}" method="post">
	{{ csrf_field() }}
	
	<div class="panel-body no-padding-hr">
		<div class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label text-danger">Tahun Akademik:</label>
			<div class="col-sm-2">
				<select class="form-control" name="th_akademik_id" id="th_akademik_id" required>
				<option value="">-Pilih-</option>
				@foreach($list_thakademik as $row)
					@php $select = $th_akademik_id==$row->id ? 'selected' : ''; @endphp
					<option value="{{$row->id}}" {{$select}} >{{$row->kode}}</option>
				@endforeach
				</select>

				@if ($errors->has('th_akademik_id'))
					<span class="help-block"><strong>{{ $errors->first('th_akademik_id') }}</strong></span>
				@endif
			</div>						
		</div></div>

		<div class="form-group{{ $errors->has('th_angkatan_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">				
			<label class="col-sm-2 control-label">Tahun Angkatan:</label>
			<div class="col-sm-2">
				<select class="form-control" name="th_angkatan_id" id="th_angkatan_id">
				<option value="">-Pilih-</option>
				@foreach($list_thangkatan as $row)
					@php $select = $th_angkatan_id==$row->id ? 'selected' : ''; @endphp
					<option value="{{$row->id}}" {{$select}} >{{ substr($row->kode,0,4) }}</option>
				@endforeach
				</select>

				@if ($errors->has('th_angkatan_id'))
					<span class="help-block"><strong>{{ $errors->first('th_angkatan_id') }}</strong></span>
				@endif
			</div>		
					
			<label class="col-sm-2 control-label">Kelas:</label>
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
		
		<div class="form-group{{ $errors->has('prodi_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Program Studi:</label>
			<div class="col-sm-6">
				<select class="form-control" name="prodi_id" id="prodi_id">
				<!-- @if($level!='prodi') <option value="">-Pilih-</option> @endif -->
				<option value="">-Pilih-</option>
				@foreach($list_prodi as $prodi)
					@php $select = $prodi_id==$prodi->id ? 'selected' : ''; @endphp
					<option value="{{$prodi->id}}" {{$select}} >{{$prodi->nama}}</option>
				@endforeach
				</select>
				
				@if ($errors->has('prodi_id'))
					<span class="help-block"><strong>{{ $errors->first('prodi_id') }}</strong></span>
				@endif
			</div>			
		</div></div>

		<div class="form-group{{ $errors->has('tagihan_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Untuk Tagihan:</label>
			<div class="col-sm-6">
				<select class="form-control" name="tagihan_id" id="tagihan_id">
				<option value="">-Pilih-</option>
				@foreach($list_tagihan as $tagihan)						
					<option value="{{$tagihan->id}}"> {{$tagihan->kode}} {{$tagihan->nama}} 
					{{ $tagihan->th_angkatan->kode }} </option>
				@endforeach
				</select>
				
				@if ($errors->has('tagihan_id'))
					<span class="help-block"><strong>{{ $errors->first('tagihan_id') }}</strong></span>
				@endif
			</div>
		</div></div>

		<div class="form-group{{ $errors->has('tgl1') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Dari Tanggal:</label>
			<div class="col-sm-2">
				{!! Form::text('tgl1',null,['class' => 'form-control','id'=>'tgl1']) !!}
				@if ($errors->has('tgl1'))
					<span class="help-block"><strong>{{ $errors->first('tgl1') }}</strong></span>
				@endif
			</div>

			<label class="col-sm-2 control-label"> Sampai Tanggal:</label>				
			<div class="col-sm-2">
				{!! Form::text('tgl2',null,['class' => 'form-control','id'=>'tgl2']) !!}
				@if ($errors->has('tgl2'))
					<span class="help-block"><strong>{{ $errors->first('tgl2') }}</strong></span>
				@endif
			</div>
		</div></div>
	</div>

	<div class="panel-footer">
	<div class="col-sm-offset-2">
		<button type="button" name="filter" id="filter" class="btn btn-danger btn-flat">
		<i class="fa fa-filter"></i> Filter</button>
		
		<button type="submit" name="print" id="print" class="btn btn-info btn-flat">
		<i class="fa fa-print"></i> Print</button>
	</div></div>
</form>
