@extends('layouts.app')
@section('title', $title)

@section('content')
    <div class="panel widget-messages-alt panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title"><i class="panel-title-icon fa fa-laptop"></i>{{ $title }}</span>
        </div> <!-- / .panel-heading -->

        <div class="panel-body no-padding-hr">
            <div class="table-responsive">
                <table id="serversideTable" class="table table-hover table-bordered">
                    <div id="table-loader" class="table-loader"></div>
                    <thead>
                        <tr>
                            <th class="text-center col-md-1">No</th>
                            <th class="text-center col-md-3">Dosen Penguji</th>
                            <th class="text-center ">Nilai</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="panel widget-messages-alt panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title"><i class="panel-title-icon fa fa-laptop"></i>Cetak</span>
        </div> <!-- / .panel-heading -->

        <div class="panel-body">
            <div style="margin-bottom: 10px">Klik tombol cetak untuk mencetak nilai komprehensif</div>
            <div>
                <form action="{{ route('komprehensif.mhs.cetak') }}" method="post">
                    {{ csrf_field() }}
                    <button class="btn btn-success">Cetak</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            var dataTable = $("#serversideTable").DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                search: {
                    return: true,
                },
                ajax: {
                    url: "{{ url($redirect) }}" + '/getData',
                    beforeSend: function() {
                        addTableLoader('#table-loader');
                    },
                    complete: function() {
                        deleteTableLoader('#table-loader');
                    }
                },
                columns: [{
                        data: 'id',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        className: "align-middle"
                    },
                    {
                        data: 'dosen',
                        name: 'dosen'
                    },
                    {
                        data: 'nilai',
                        name: 'nilai'
                    },
                ],
                "order": [
                    [0, "asc"]
                ]
            });
        });
    </script>
@endpush
