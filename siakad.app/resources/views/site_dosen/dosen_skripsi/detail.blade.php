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
    </style>
@endpush
@section('content')
    <div style="display: flex;justify-content: flex-end;margin-bottom:10px">
        <a href="{{ route('dosen_skripsi.index') }}" class="btn btn-primary">Kembali</a>
    </div>

    @include('site_dosen.dosen_skripsi.detail.informasi')
    @if ($statusPengajuan == 'Ujian Proposal')
        @include('site_dosen.dosen_skripsi.detail.ujian_proposal')
    @endif
    @if ($statusPengajuan == 'Ujian Skripsi')
        @include('site_dosen.dosen_skripsi.detail.ujian_skripsi')
    @endif
    @if ($statusPengajuan == 'Ujian Skripsi' || $statusPengajuan == 'Selesai')
        @include('site_dosen.dosen_skripsi.detail.nilai_skripsi')
    @endif
    @if ($statusPengajuan == 'Bimbingan')
        @include('site_dosen.dosen_skripsi.detail.bimbingan')
    @endif
    @include('site_dosen.dosen_skripsi.detail.dokumen_skripsi')

@endSection
@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
@endpush
