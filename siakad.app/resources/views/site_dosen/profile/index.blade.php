@extends('layouts.app')
@section('title', ' Profile ' . $title)
@push('css')
    <style>
        .jadwal {
            text-align: left;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            border-bottom: 1px rgb(231, 231, 231) solid;
        }

        .jadwal:hover {
            background-color: rgb(240, 240, 240);
        }

        .sekarang {
            background-color: rgba(69, 206, 130, .4);
        }
    </style>
@endpush
@section('content')
    <div class="profile-full-name">
        <input type="hidden" name="dosen_id" id="dosen_id" value="{{ $data->id }}">
        {{-- <span class="text-semibold" id="nama-desktop">{{ $data->nama }}</span>
        <div id="status-desktop">
            {!! strtolower($data->status->nama) == 'aktif'
                ? '<span class="label label-success" >' . $data->status->nama . '</span>'
                : '<span class="label label-danger">' . $data->status->nama . '</span>' !!}
        </div> --}}
    </div>

    <div class="profile-row" style="padding: 0 30px">
        <div class="left-col">
            <div class="panel widget-messages-alt panel-danger panel-dark"
                style="border: none;box-shadow: -2px 2px 24px -5px rgba(0,0,0,0.53) !important;">
                <div style="width: inherit; height:3px; background-color: var(--dalwaColor); border-radius: 10px 10px 0 0">
                </div>
                <div class="panel-body text-center">
                    @if ($picture)
                        <img src="{{ asset('picture_users/' . $picture) }}" class="img-circle"
                            style="object-fit: cover; object-position: top" height="120" width="120" alt="">
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
                <div class="panel-heading" style="cursor: pointer;" data-toggle="collapse" data-target="#detail-collapse">
                    <div>
                        <div class="pull-left">Detail</div>
                        <div class="pull-right">
                            <i class="navbar-icon fa fa-bars icon"></i>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="panel-body padding-sm collapse" id="detail-collapse" style="overflow: hidden">
                    <strong>NIDN</strong>
                    <p>{{ $data->nidn }}</p>
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
            <div class="panel widget-messages-alt panel-danger panel-dark"
                style="border: none;box-shadow: -2px 2px 24px -5px rgba(0,0,0,0.53) !important;">
                <div class="panel-heading" style="cursor: pointer;" id="step_1" data-toggle="collapse"
                    data-target="#jadwal">
                    <div>
                        <div class="pull-left">Jadwal Hari ini</div>
                        <div class="pull-right">
                            <i class="navbar-icon fa fa-bars icon"></i>
                        </div>
                    </div>
                    <br>
                </div>

                <div class="panel-body collapse" style="padding: 0" id="jadwal" style="overflow: hidden">
                    @if (count($jadwal) < 1)
                        <div class="jadwal">
                            <p><strong>Tidak ada jadwal</strong></p>
                        </div>
                    @endif
                    @foreach ($jadwal as $item)
                        @php
                            $sekarang = false;
                            $jam = date('H.00');

                            $jamkul = explode('-', $item->jamkul->nama);
                            $mulai = $jamkul[0];
                            $selesai = $jamkul[1];
                            if ($mulai <= $jam && $selesai >= $jam) {
                                $sekarang = true;
                            }

                            $urlAbsen = route('dosen_jadwal.absensi', ['id' => $item->id, 'absen_id' => 0]);
                        @endphp
                        <div class="jadwal {{ $sekarang ? 'sekarang' : '' }}"
                            onclick="location.href = '{{ $urlAbsen }}' ">
                            <p><strong>{{ @$item->kurikulum_matakuliah->matakuliah->nama }}</strong></p>
                            <p>{{ @$item->hari->nama }}, {{ @$item->jamkul->nama }}</p>
                            <p>{{ @$item->ruang_kelas->nama }}</p>
                            <p>{{ @$item->kelompok->kode }}</p>
                            <button onclick="location.href = '{{ $urlAbsen }}' " class="btn btn-primary"
                                style="width: 100%;"><i class="fa fa-pencil"></i>
                                Absensi sekarang</button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- dosen/profile/tab -->
        @include('dosen.profile.tab')
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
        $(document).ready(function() {
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

            @if (!$statusIntro)
                tour.addStep({
                    text: '<h5>Klik di sini untuk melihat jadwal hari ini</h5>',
                    attachTo: {
                        element: '#step_1',
                        on: 'top'
                    },
                    buttons: [{
                        text: 'Selesai',
                        classes: 'shepherd-button-custom',
                        action: tour.next
                    }]
                });

                tour.start();
            @endif
        });
    </script>
@endpush
