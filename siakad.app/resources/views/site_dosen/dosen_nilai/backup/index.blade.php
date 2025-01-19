@extends('layouts.app')
@section('title', $title)

@section('content')
    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title"><b>@yield('title')</b></span></br>

            @if ($tgl >= $form->tgl_mulai && $tgl <= $form->tgl_selesai)
                <h4 class="note-title"><b>
                        <center>Mohon Diperhatikan !!!, &nbsp;&nbsp;<?= $form->nama ?>
                            Mulai Tanggal{{ tgl_jam($form->tgl_mulai) }} s/d {{ tgl_jam($form->tgl_selesai) }}</center>
                    </b></h4>
            @else
                @if ($tgl > $form->tgl_selesai)
                    <h3><b>
                            <center> Mohon Maaf, Pengisian Nilai Sudah Ditutup !!! </center>
                        </b></h3>
                @else
                    <h3><b>
                            <center> Mohon Maaf, Pengisian Nilai Belum Dibuka !!! </br>
                                Mulai Dibuka Tanggal {{ tgl_jam($form->tgl_mulai) }} s/d {{ tgl_jam($form->tgl_selesai) }}
                            </center>
                        </b></h3>
                @endif
            @endif

            <div class="text-right">
                <a href="{{ url($redirect) }}" class="btn btn-primary"><i class="fa fa-refresh"></i> Refresh</a>
            </div>
        </div>

        <input type="hidden" name="kode" value="{{ $kode }}">

        <div class="table-responsive">
            <table id="serversideTable" class="table table-hover table-bordered">
                <div id="table-loader" class="table-loader"></div>
                <thead>
                    <tr>
                        <th class="text-center col-md-1">Kode</th>
                        <th class="text-center">Mata Kuliah</th>
                        <th class="text-center">SKS</th>
                        <th class="text-center">Smt</th>
                        <th class="text-center">Kelompok</th>

                        <!--
                         <th class="text-center">Taka</th>
                         <th class="text-center">Dosen</th>
                         -->

                        <th class="text-center">Hari</th>
                        <th class="text-center">Ruang</th>
                        <th class="text-center col-md-1">Waktu</th>
                        <th class="text-center">Mhs</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('demo')
    <script>
        init.push(function() {
            $('#c-tooltips-demo a').tooltip();
        });
    </script>
@endpush

@push('scripts')
    <script type="text/javascript">
        var dataTable = $("#serversideTable").DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            paging: false,
            search: {
                return: true,
            },

            // "searching": false,

            ajax: {
                url: "{{ url($redirect) }}" + '/getData',
                data: function(d) {
                    d.kode = $("#kode").val();
                },
                beforeSend: function() {
                    addTableLoader('#table-loader');
                },
                complete: function() {
                    deleteTableLoader('#table-loader');
                }
            },
            columns: [{
                    data: 'kd_mk',
                    name: 'kd_mk',
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
                //{ data: 'kurikulum', 	name: 'kurikulum','class':'text-center'},
                //{ data: 'dosen', 		name: 'dosen'},//
                {
                    data: 'hari',
                    name: 'hari',
                    'class': 'text-center'
                },
                {
                    data: 'ruang_kelas',
                    name: 'ruang',
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
                    data: 'status',
                    name: 'status',
                    'class': 'text-center'
                },

                @if ($tgl >= $form->tgl_mulai && $tgl <= $form->tgl_selesai || $kode == '80211')
                    {
                        data: 'action',
                        name: 'action',
                        'orderable': false,
                        'searchable': false,
                        'class': 'text-center'
                    },
                @else
                    {
                        data: 'nilai',
                        name: 'nilai',
                        'class': 'text-center'
                    },
                    //{ data: 'action', name: 'action','orderable':false, 'searchable':false,'class':'text-center' },
                @endif
            ],
            "order": [
                [3, "asc"],
                [0, "asc"]
            ]
        });
    </script>
@endpush
