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

        @media screen and (max-width: 768px) {
            .profile-row {
                padding: 0px !important;
            }
        }
    </style>
@endpush
@section('content')

    @include('site_mhs.mhs_skripsi.detail.timeline')
    @include('site_mhs.mhs_skripsi.detail.informasi')
    @include('site_mhs.mhs_skripsi.detail.bimbingan')
    @include('site_mhs.mhs_skripsi.detail.dokumen-skripsi')

@endSection
@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
@endpush
