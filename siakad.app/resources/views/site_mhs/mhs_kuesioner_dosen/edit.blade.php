@extends('layouts.app')
@section('title',$title)

@section('content')
<div class="panel panel-danger panel-dark">
	<div class="panel-heading">
    <span class="panel-title">@yield('title')</span></div>

	<form class="form-horizontal form-bordered" action="{{url($redirect)}}" method="post">
		{{ csrf_field() }}
		
		<input type="hidden" name="nim" id="nim" value="{{$nim}}">
		<input type="hidden" name="th_akademik_id" id="th_akademik_id" value="{{$th_akademik->id}}">
		<input type="hidden" name="dosen_id" id="nim" value="{{$dosen->id}}">

		@include($folder.'.pertanyaan');

		<div class="form-group{{ $errors->has('kekurangan') ? ' has-error' : '' }} 
			no-margin-hr panel-padding-h no-padding-t no-border-t">
			<div class="row">
				<label class="col-sm-1 control-label">Kekurangan:</label>
				<div class="col-sm-6">
					{!! Form::textarea('kekurangan',null,['class' => 'form-control',
					'id'=>'kekurangan','required'=>'true','rows'=>4]) !!}
					
					@if ($errors->has('kekurangan'))
						<span class="help-block">
						<strong>{{ $errors->first('kekurangan') }}</strong>
						</span>
					@endif
				</div>
			</div>
		</div>

		<div class="form-group{{ $errors->has('kelebihan') ? ' has-error' : '' }} 
			no-margin-hr panel-padding-h no-padding-t no-border-t">
			<div class="row">
				<label class="col-sm-1 control-label">Kelebihan:</label>
				<div class="col-sm-6">
					{!! Form::textarea('kelebihan',null,['class' => 'form-control',
					'id'=>'kelebihan','required'=>'true','rows'=>4]) !!}
					
					@if ($errors->has('kelebihan'))
						<span class="help-block">
						<strong>{{ $errors->first('kelebihan') }}</strong>
						</span>
					@endif
				</div>
			</div>
		</div>

		<div class="panel-footer">
		<div class="col-sm-offset-0">
			<button type="submit" name="save" id="save" class="btn btn-success btn-flat btn-block">
			<i class="fa fa-floppy-o"></i> SIMPAN</button>
		</div></div>
	</form>
</div>
@endsection

@push('scripts')
<script type="text/javascript"></script>
@endpush
