@extends('layouts.app')
@section('title', $title)

@section('content')
    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">@yield('title')</span>
        </div>

        <div class="panel-body no-padding-hr">
            <form class="form-horizontal form-borderd" action="{{ route($redirect . '.export') }}" name="form-input"
                id="form-input" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                    <label class="col-sm-2 control-label">Program Studi:</label>
                    <div class="col-sm-5">
                        <select class="form-control" name="prodi_id" id="prodi_id">
                            @if (empty($prodi_id))
                                <option value="">-Semua Program Studi-</option>
                            @endif
                            @foreach ($list_prodi as $prodi)
                                <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <label class="col-sm-2 control-label">Tahun Akademik:</label>
                    <div class="col-sm-2">
                        <select class="form-control" name="th_akademik_id" id="th_akademik_id">
                            <option value="">-Semua Tahun Akademik-</option>
                            @foreach ($list_thakademik as $row)
                                <option value="{{ $row->id }}">{{ $row->kode }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
        </div>
        <div class="panel-footer">
            <div class="col-sm-offset-5">
                <button type="submit" name="exportdatadosenmengajar" id="exportdatadosenmengajar" class="btn btn-info btn-flat">
                    <i class="fa fa-download"></i> Export</button>
            </div>
        </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript"></script>
@endpush
