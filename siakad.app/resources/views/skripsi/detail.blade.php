@extends('layouts.app')

@section('title', $title)
@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link href="{{ asset('assets/stylesheets/pages.min.css') }}" rel="stylesheet" type="text/css">
    <style>
        .container {
            background-color: white;
            padding: 10px;
            width: 99%;
            margin-bottom: 20px
        }

        .title {
            font-size: 14px;
            text-transform: uppercase;
            font-weight: 700;
            color: #32415a;
        }

        .table-responsive {
            padding: 10px !important;
        }

        .header-skripsi {
            display: flex;
            justify-content: space-between;
        }

        #biodata {
            margin-top: 50px
        }

        .mrow {
            width: 100%;
            overflow: hidden;
        }

        .mcol {
            width: 50%;
            float: left;
        }
    </style>
@endpush
@section('content')
    <div style="display: flex;justify-content: flex-end;margin-bottom:10px">
        <a href="{{ route('skripsi.index') }}" class="btn btn-primary">Kembali</a>
    </div>

    @include('skripsi.detail.informasi')

    @include('skripsi.detail.judul')

    @include('skripsi.detail.ujian_proposal')

    @include('skripsi.detail.acc_judul')

    @include('skripsi.detail.ujian_skripsi')

    @include('skripsi.detail.nilai_skripsi')
@endSection
@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        var dataTable = null;

        $(document).ready(function() {
            getDosenPembimbing();
            getDosenPenguji();
            getDosenPengujiSkripsi();

            let statusUjianProposal = "{{ $ujianProposal ? $ujianProposal->status : 'Kosong' }}";
            updateBadgeStatusUjianProposal(statusUjianProposal);
            let statusUjianSkripsi = "{{ $ujianSkripsi ? $ujianSkripsi->status : 'Kosong' }}";
            updateBadgeStatusUjianSkripsi(statusUjianSkripsi);

            updateStatusSkripsi("{{ $statusPengajuan }}", "{{ $color }}");
            dataTable = $("#serversideTable").DataTable({
                // responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                search: {
                    return: true,
                },
                ajax: {
                    url: "{{ route('skripsi.getDataJudul', ['id' => $id]) }}",
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
                        data: 'judul',
                        name: 'judul',
                    },
                    {
                        data: 'catatan',
                        name: 'catatan',
                    },
                    {
                        data: 'dokumen_proposal',
                        name: 'dokumen_proposal',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'dokumen_skripsi',
                        name: 'dokumen_skripsi',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'acc',
                        name: 'acc'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                    },
                ],
                "order": [
                    [0, "desc"]
                ]
            });

        });

        function refresh() {
            dataTable.ajax.reload();
        }

        function formatDate(dateTimeString) {
            var date = new Date(dateTimeString);
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            var hours = date.getHours();
            var minutes = date.getMinutes();
            var seconds = date.getSeconds();

            // Add leading zeros if necessary
            day = (day < 10 ? '0' : '') + day;
            month = (month < 10 ? '0' : '') + month;
            hours = (hours < 10 ? '0' : '') + hours;
            minutes = (minutes < 10 ? '0' : '') + minutes;
            seconds = (seconds < 10 ? '0' : '') + seconds;

            return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes;
        }
    </script>
@endpush
