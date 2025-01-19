@extends('layouts.app')
@section('title', $title)
@section('content')
    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">Filter @yield('title')</span>
        </div>

        @include($folder . '.filter')

        <div class="panel-footer text-center">
            <button type="button" class="btn btn-info" name="btnRefresh" id="btnRefresh">
                <i class="fa fa-refresh"></i> Refresh
            </button>
        </div>
    </div>

    @include('site_dosen.dosen_skripsi.index.ujian-proposal')
    @include('site_dosen.dosen_skripsi.index.bimbingan')
    @include('site_dosen.dosen_skripsi.index.ujian-skripsi')
@endsection

@push('scripts')
    <script>
        function refreshDataTable() {
            dataTable.draw();
            dataTableUjianProposal.draw();
            dataTableUjianSkripsi.draw();
        }
        $("#th_akademik_id").on('change', function() {
            refreshDataTable();
        });

        $("#prodi_id").on('change', function() {
            refreshDataTable();
        });

        $("#btnRefresh").on('click', function() {
            refreshDataTable();
        });
    </script>
@endpush
