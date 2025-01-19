<div class="panel-body no-padding-hr">
	<div class="form-group{{ $errors->has('tanggal') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Tanggal:</label>
		<div class="col-sm-2">
			{!! Form::text('tanggal',!empty($data->tanggal)?tgl_str($data->tanggal):date('d-m-Y'),
			['class' => 'form-control','id'=>'tanggal','required'=>'true','autofocus' => 'true']) !!}
			
			@if ($errors->has('tanggal'))
				<span class="help-block">
                <strong>{{ $errors->first('tanggal') }}</strong>
				</span>
			@endif
		</div>
    </div>
	</div>

	<div class="form-group{{ $errors->has('judul') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Judul:</label>
			<div class="col-sm-7">
				{!! Form::text('judul',null,['class' => 'form-control','id'=>'judul',
				'required'=>'true','autofocus' => 'true']) !!}
				
				@if ($errors->has('judul'))
					<span class="help-block">
					<strong>{{ $errors->first('judul') }}</strong>
					</span>
				@endif
			</div>
		</div>
	</div>

	<div class="form-group{{ $errors->has('isi') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Isi:</label>
			<div class="col-sm-7">
				{!! Form::textarea('isi',null,['class' => 'form-control','id'=>'isi',
				'required'=>'true']) !!}
				
				@if ($errors->has('isi'))
					<span class="help-block">
					<strong>{{ $errors->first('isi') }}</strong>
					</span>
				@endif
			</div>
		</div>
	</div>

	<div class="form-group{{ $errors->has('user_level_id') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Kepada Level:</label>
			<div class="col-sm-7">
				<select class="form-control" name="user_level_id" id="user_level_id">
				<option value="">-ALL-</option>
				@foreach($list_level as $level)
					{{$select = (old('user_level_id')==$level->id?'selected':(!empty($data->user_level_id)?
					($data->user_level_id==$level->id?'selected':null):null))}}
					<option value="{{$level->id}}" {{$select}}>{{$level->level}}</option>
				@endforeach
				</select>

				@if ($errors->has('user_level_id'))
					<span class="help-block">
					<strong>{{ $errors->first('user_level_id') }}</strong>
					</span>
				@endif
			</div>
		</div>
	</div>
</div>

<div class="panel-footer">
	<div class="col-sm-offset-2">
		<button type="submit" name="save" id="save" class="btn btn-success btn-flat">
		<i class="fa fa-floppy-o"></i> Simpan</button>
	</div>
</div>

@push('demo')
<script>
init.push(function () {
	var options = {
		todayBtn: "linked",
		orientation: $('body').hasClass('right-to-left') ? "auto right" : 'auto auto',
		format: "dd-mm-yyyy"
	}
	$('#tanggal').datepicker(options);
});
</script>
@endpush

@push('scripts')
<script src="//cdn.ckeditor.com/4.6.2/full-all/ckeditor.js"></script>
<script src="{{asset('vendor/unisharp/laravel-ckeditor/adapters/jquery.js')}}"></script>

<script>
$('textarea').ckeditor({
	filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
	filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}',
	filebrowserBrowseUrl	 : '/laravel-filemanager?type=Files',
	filebrowserUploadUrl	 : '/laravel-filemanager/upload?type=Files&_token={{csrf_token()}}'
});
</script>
@endpush
