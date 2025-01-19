<form class="form-horizontal form-borderd" action="{{url($redirect.'/cetak')}}" method="post" >
	{{ csrf_field() }}
	
	<div class="panel-body no-padding-hr">
		<div class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} 
			no-margin-hr panel-padding-h no-padding-t no-border-t">
			<div class="row">
				<label class="col-sm-1 control-label">T.Akademik:</label>
				<div class="col-sm-2">
					<select class="form-control" name="th_akademik_id" id="th_akademik_id" required>
						<!--
						<option value="">-Pilih-</option>
						@foreach($list_thakademik as $row)
							<option value="{{$row->id}}" >{{$row->kode}}</option>
						@endforeach
						-->
						
						<option value="">-Pilih-</option>
						@foreach($list_thakademik as $row)
							@php $select = $th_akademik_id==$row->id ? 'selected' : ''; @endphp
							<option value="{{$row->id}}" {{$select}} >{{$row->kode}}</option>
						@endforeach
					</select>

					@if ($errors->has('th_akademik_id'))
						<span class="help-block">
						<strong>{{ $errors->first('th_akademik_id') }}</strong></span>
					@endif
				</div>
				
				<!--
				<label class="col-sm-3 control-label">Mahasiswa:</label>
				<div class="col-sm-6">
					<select class="form-control" name="mhs_id" id="mhs_id" required>
						<option value="">-Pilih Mahasiswa-</option> 					
					</select>

					@if ($errors->has('mhs_id'))
						<span class="help-block">
						<strong>{{ $errors->first('mhs_id') }}</strong></span>
					@endif
				</div>
				-->
				
			</div>
		</div>

		<div class="form-group{{ $errors->has('prodi_id') ? ' has-error' : '' }} 
			no-margin-hr panel-padding-h no-padding-t no-border-t">
			<div class="row">
				<label class="col-sm-1 control-label">Prodi:</label>
				<div class="col-sm-5">
					<select class="form-control" name="prodi_id" id="prodi_id" required>
						<option value="">-Pilih Prodi-</option> 					
						@foreach($list_prodi as $prodi)
							<!-- @php $select = $prodi_id==$prodi->id ? 'selected' : ''; @endphp -->
							<option value="{{$prodi->id}}">{{$prodi->nama}}</option>
						@endforeach
					</select>

					@if ($errors->has('prodi_id'))
						<span class="help-block">
						<strong>{{ $errors->first('prodi_id') }}</strong></span>
					@endif
				</div>		
				
				<label class="col-sm-1 control-label">Piutang:</label>
				<div class="col-sm-5">
					<select class="form-control" name="tagihan_id" id="tagihan_id" required>
						<option value="">-Pilih Piutang-</option>
					</select>
					
					@if ($errors->has('tagihan_id'))
						<span class="help-block">
						<strong>{{ $errors->first('tagihan_id') }}</strong></span>
					@endif
				</div>
			</div>
		</div>
	</div>

	<div class="panel-footer">
	<div class="col-sm-offset-1">
		<button type="button" name="filter" id="filter" class="btn btn-danger btn-flat">
		<i class="fa fa-filter"></i> Filter</button>
		
		<button type="submit" name="print" id="print" class="btn btn-info btn-flat">
		<i class="fa fa-print"></i> Print</button>
	</div></div>
</form>
