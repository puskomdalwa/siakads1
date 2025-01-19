@extends('layouts.app')
@section('title',$title)
@section('content')

<div class="panel panel-danger panel-dark">
  <div class="panel-heading">
    <span class="panel-title">@yield('title')</span>
    <div class="panel-heading-controls">
      <a href="{{ asset('download_format/format_nilai.xlsx') }}" class="btn btn-primary"><i class="fa fa-download"></i> Download Format</a>
    </div>
  </div>

    <div class="panel-body no-padding-hr">
      <form class="form-horizontal form-borderd" name="form-input" id="form-input" action="{{url($redirect)}}" method="post" enctype="multipart/form-data">
      {{ csrf_field() }}


      <div class="form-group{{ $errors->has('import_file') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
          <label class="col-sm-2 control-label">Pilih File:</label>
          <div class="col-sm-6">
            <input type="file" name="import_file" value="" class="form-control" required>
            @if ($errors->has('import_file'))
                <span class="help-block">
                    <strong>{{ $errors->first('import_file') }}</strong>
                </span>
            @endif
          </div>
        </div>
      </div>


    </div>

    <div class="panel-footer">
      <div class="col-sm-offset-2">
          <button type="submit" name="upload" id="upload" class="btn btn-info btn-flat"><i class="fa fa-upload"></i> Upload</button>
      </div>
    </div>
    </form>

</div>
@endsection


@push('scripts')
<script type="text/javascript">


</script>
@endpush
