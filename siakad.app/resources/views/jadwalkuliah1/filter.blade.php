<form class="form-horizontal form-borderd" >
	<div class="panel-body no-padding-hr">
	<div class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label text-danger">T.Akademik Aktif:</label>
			<div class="col-sm-2">
			<select class="form-control" name="th_akademik_id" id="th_akademik_id">
			{{-- <option value="">-Pilih-</option> --}}
			@foreach($list_thakademik as $row)
				{{$select = old('th_akademik_id')==$row->id?'selected':''}}
				<option value="{{$row->id}}" {{$select}}>{{$row->kode}}</option>
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
				<option value="">-Pilih-</option>
			@endif

			@foreach($list_prodi as $prodi)
				{{$select = old('prodi_id')==$row->id?'selected':''}}
				<option value="{{$prodi->id}}" {{$select}}>{{$prodi->nama}}</option>
			@endforeach
			</select>

			@if ($errors->has('prodi_id'))
				<span class="help-block">
				<strong>{{ $errors->first('prodi_id') }}</strong></span>
			@endif
		</div>
	</div></div>

	<div class="form-group{{ $errors->has('kurikulum_id') ? ' has-error' : '' }}
	no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">Kurikulum:</label>
		<div class="col-sm-9">
			<select class="form-control" name="kurikulum_id" id="kurikulum_id">
			{{-- <option value="">-Pilih-</option>
			@foreach($list_kurikulum as $kurikulum)
				<option value="{{$kurikulum->id}}">{{$kurikulum->nama}}</option>
			@endforeach --}}
			</select>

			@if ($errors->has('kurikulum_id'))
				<span class="help-block">
				<strong>{{ $errors->first('kurikulum_id') }}</strong></span>
			@endif
		</div></div>
	</div></div>
</form>
