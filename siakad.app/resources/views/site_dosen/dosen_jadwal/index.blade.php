@extends('layouts.app')
@section('title', $title)

@section('content')
    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">@yield('title')</span>
        </div>

        <form class="form-horizontal">
            {{ csrf_field() }}

            <input type="hidden" name="kode" id="kode" value="{{ $kode }}">
            <input type="hidden" name="th_akademik_id" id="th_akademik_id" value="{{ $th_akademik->id }}">
        </form>

        <div class="panel-body no-padding-hr">
            <div class="table-responsive">
                <table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
                    <thead>
                        <tr>
                            <th class="text-center col-md-1">Kode</th>
                            <th class="text-center col-md-4">Nama Matakuliah</th>
                            <th class="text-center">SKS</th>
                            <th class="text-center">smt</th>
                            <th class="text-center">Klp</th>
                            <th class="text-center ">Ruang</th>
                            <th class="text-center ">Hari</th>
                            <th class="text-center col-md-2">Waktu</th>
                            <th class="text-center ">Jml Mhs</th>
                            <th class="text-center ">Absensi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        var dataTable = $("#serversideTable").DataTable({
            processing: true,
            serverSide: true,
            paging: false,
            "searching": false,
            ajax: {
                url: "{{ url($redirect) }}" + '/getData',
                data: function(d) {
                    d.th_akademik_id = $("#th_akademik_id").val();
                    d.kode = $("#kode").val();
                }
            },
            columns: [{
                    data: 'kode_mk',
                    name: 'kode_mk',
                    'class': 'text-center'
                },
                {
                    data: 'nama_mk',
                    name: 'nama_mk'
                },
                {
                    data: 'sks_mk',
                    name: 'sks_mk',
                    'class': 'text-center'
                },
                {
                    data: 'smt_mk',
                    name: 'smt_mk',
                    'class': 'text-center'
                },
                {
                    data: 'kelompok',
                    name: 'kelompok',
                    'class': 'text-center'
                },
                {
                    data: 'ruang',
                    name: 'ruang',
                    'class': 'text-center'
                },
                {
                    data: 'hari',
                    name: 'hari',
                    'class': 'text-center'
                },
                {
                    data: 'waktu',
                    name: 'waktu',
                    'class': 'text-center'
                },
                {
                    data: 'jml_mhs',
                    name: 'jml_mhs',
                    'class': 'text-center'
                },
                {
                    data: 'absensi',
                    name: 'absensi',
                    'class': 'text-center'
                },
            ],
            "order": [
                [3, "asc"],
                [0, "asc"]
            ]
        });
    </script>
@endpush
