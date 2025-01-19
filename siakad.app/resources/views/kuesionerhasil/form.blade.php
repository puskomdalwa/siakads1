<div class="panel-body no-padding-hr">
	<div class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label text-danger">Tahun Akademik:</label>
		<div class="col-sm-2">
			<select class="form-control" name="th_akademik_id" id="th_akademik_id" required>
			<option value="">-Pilih-</option>
			@foreach($list_thakademik as $row)
            @php
            $select = $th_akademik_id ==$row->id ? 'selected' : '';    
            @endphp
            <option value="{{$row->id}}" {{$select}} >{{$row->kode}}</option>
			@endforeach
			</select>
			
			@if ($errors->has('th_akademik_id'))
            <span class="help-block">
            <strong>{{ $errors->first('th_akademik_id') }}</strong></span>
			@endif
      </div>
	  
		<label class="col-sm-2 control-label">Program Studi:</label>
		<div class="col-sm-5">
			<select class="form-control" name="prodi_id" id="prodi_id">
			@if(empty($prodi_id))
			<option value="">-All-</option>
			@endif
			@foreach($list_prodi as $prodi)
			<option value="{{$prodi->id}}">{{$prodi->nama}}</option>
			@endforeach
			</select>
		</div>

    </div></div>
</div>

<div class="panel-footer">
  <div class="col-sm-offset-2">
  <button type="button" name="filter" id="filter" class="btn btn-success btn-flat">
  <i class="fa fa-filter"></i> Filter</button></div>
</div>


@push('scripts')
<script type="text/javascript">
</script>
@endpush
