@extends('layouts.app')
@section('title', ' Profile ' . $title)

@section('content')
    <div class="profile-full-name">
        <input type="hidden" name="nim" id="nim" value="{{ $data->nim }}">
        {{-- <span class="text-semibold">Profile Mahasiswa : {{ $data->nim }} - {{ $data->nama }}</span>
        {!! strtolower($data->status->nama) == 'aktif'
            ? '<span class="label label-success">' . $data->status->nama . '</span>'
            : '<span class="label label-danger">' . $data->status->nama . '</span>' !!} --}}
    </div>

    <div class="profile-row" style="padding: 0 30px">
        <div class="left-col">
            <div class="panel widget-messages-alt panel-danger panel-dark"
                style="border: none;box-shadow: -2px 2px 24px -5px rgba(0,0,0,0.53) !important;">
                <div style="width: inherit; height:3px; background-color: var(--dalwaColor); border-radius: 10px 10px 0 0">
                </div>
                <div class="panel-body text-center">
                    @if ($picture)
                        <img src="{{ asset('picture_users/' . $picture) }}" class="img-circle" height="120" width="120"
                            alt="">
                    @else
                        <img src="{{ asset('assets/demo/avatars/pria.jpg') }}" class="img-circle" height="120"
                            width="120" alt="">
                    @endif
                    {{-- <div class="image-border">
                    </div> --}}
                    <div style="margin: 10px">{{ $data->nama }}</div>
                </div>
                <div class="panel-footer" style="background-color: var(--dalwaColor); border-radius: 0 0 5px 5px"></div>
            </div>
            <div class="panel widget-messages-alt panel-danger panel-dark"
                style="border: none;box-shadow: -2px 2px 24px -5px rgba(0,0,0,0.53) !important;">
                <div class="panel-heading">
                    <div>
                        <div class="pull-left">Detail</div>
                        <div class="pull-right" style="cursor: pointer;" data-toggle="collapse"
                            data-target="#detail-collapse"><i class="navbar-icon fa fa-bars icon"></i></div>
                    </div>
                    <br>
                </div>
                <div class="panel-body padding-sm collapse" id="detail-collapse" style="overflow: hidden">
                    <strong>NIM</strong>
                    <p>{{ $data->nim }}</p>
                    <hr>
                    <strong>Prodi</strong>
                    <p>{{ $data->prodi->nama }}</p>
                    <hr>
                    <strong>TTL</strong>
                    <p>{{ $data->tempat_lahir }}, {{ @tgl_str($data->tanggal_lahir) }}</p>
                    <hr>
                    <strong>No. Hp</strong>
                    <p>{{ $data->hp }}</p>
                    <hr>
                    <strong>Email</strong>
                    <p>{{ $data->email }}</p>
                    <hr>
                </div>
            </div>
        </div>

        {{-- <div class="right-col">asdasd</div> --}}

        @include('site_mhs.profile.profile.tab')
    </div>
@endsection

@push('css')
    <link href="{{ asset('assets/stylesheets/pages.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@push('demo')
    <script></script>
@endpush

@push('scripts')
    <script type="text/javascript">
        init.push(function() {
            $('#profile-tabs').tabdrop();
            $("#leave-comment-form").expandingInput({
                target: 'textarea',
                hidden_content: '> div',
                placeholder: 'Write message',
                onAfterExpand: function() {
                    $('#leave-comment-form textarea').attr('rows', '3').autosize();
                }
            });
        });
    </script>
@endpush
