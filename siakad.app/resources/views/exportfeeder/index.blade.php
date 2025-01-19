@extends('layouts.app')
@section('title',$title)
@section('content')

<form action=" {{ route('exportfeeder.store') }} " method="POST" class="panel form-horizontal">
    {{ csrf_field() }}
    <div class="panel-heading panel-success panel-dark">
    <span class="panel-title"> @yield('title')</span></div>

    <div class="panel-body">
        <div class="row form-group">
            <label class="col-sm-3 control-label">Tahun Akademik:</label>
            <div class="col-sm-2">
                <select class="form-control" name="th_akademik_id" id="th_akademik_id" required>
				<option value="">-Pilih-</option>
				@foreach ($list_thakademik as $thakademik)
				<option value="{{ $thakademik->id }}"> {{ $thakademik->kode }} </option>
				@endforeach
                </select>
            </div>
        </div>

        <div class="row form-group">
            <label class="col-sm-3 control-label">Table:</label>
            <div class="col-sm-5">
                <select class="form-control" name="table_name" id="table_name" required>
				<option value="">-Pilih-</option>
				@foreach ($list_table as $key=>$value)
				<option value="{{ $key }}"> {{ $value }} </option>
				@endforeach
                </select>
            </div>
        </div>
    </div>
 
    <div class="panel-footer text-center">
        <button type="submit" class="btn btn-info btn-sm"> <i class="fa fa-download"></i> Export</button>
    </div>
</form>
@endsection

@push('scripts')
<script type="text/javascript"></script>
@endpush
