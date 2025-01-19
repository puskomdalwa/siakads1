@extends('layouts.app')
@section('title', $title)

@section('content')
    <div class="panel panel-danger panel-dark">
        @include($folder . '.filter')
    </div>
    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">@yield('title')</span>
            <div class="panel-heading-controls">
                <a href="{{ url($redirect) }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-chevron-circle-left"></i> Kembali </a>
            </div>
        </div>

        <div class="panel-body">
            <div id="preload" class="text-center" style="display:none">
                <img src="{{ asset('img/load.gif') }}" alt="">
            </div>
            <div id="detail"></div>
            <div class="row" id="data_jadwal">

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            var i = document.querySelectorAll(".select2");
            init.push(function() {
                $(i).select2({
                    allowClear: true,
                    placeholder: "Lihat Absensi"
                });
            });


            $("#th_akademik_id").select2({
                placeholder: "Pilih..."
            });

            setData();
            $('#th_akademik_id').change(function(e) {
                e.preventDefault();
                setData();
            });
        });

        function setData() {
            $.ajax({
                type: "get",
                url: "{{ route('dosen_jadwal.getData') }}",
                data: {
                    th_akademik_id: $('#th_akademik_id').val()
                },
                beforeSend: function() {
                    $('#data_jadwal').empty();
                    $("#detail").fadeOut();
                    $("#preload").fadeIn();
                },
                success: function(response) {
                    $('#data_jadwal').empty();
                    let content = ``;
                    response.data.forEach(row => {
                        content += `
                                    <div class="col-md-4">
                                        <div class="panel panel-success panel-dark widget-profile">
                                            <div class="panel-heading">
                                                <div class="widget-profile-bg-icon"><i class="fa fa-bell-o"></i></div>

                                                @if (!empty(Auth::user()->picture))
                                                    <img src="{{ asset('picture_users/' . Auth::user()->picture) }}" alt=""
                                                        class="widget-profile-avatar">
                                                @else
                                                    <img src="{{ asset('assets/demo/avatars/logo.jpg') }}" alt=""
                                                        class="widget-profile-avatar">
                                                @endif

                                                <div class="widget-profile-header">
                                                    <span> {{ $dosen->nama }} </span><br>
                                                    {{ $dosen->kode }} - {{ $dosen->nidn }}
                                                </div>
                                            </div>

                                            <div class="list-group">
                                                <li class="list-group-item">
                                                    <i class="fa fa-tags list-group-icon"></i> ${row.prodi.nama}
                                                    <span class="badge badge-warning"> ${row.prodi.jenjang}</span>
                                                </li>

                                                <li class="list-group-item">
                                                    <i class="fa fa-envelope-o list-group-icon"></i>
                                                    ${row.kurikulum_matakuliah.matakuliah.nama}
                                                    <span class="badge badge-info">
                                                        ${row.kurikulum_matakuliah.matakuliah.kode}
                                                    </span>
                                                </li>

                                                <li class="list-group-item">
                                                    <i class="fa fa-tasks list-group-icon"></i>
                                                    Smt  ${row.kurikulum_matakuliah.matakuliah.smt}  /
                                                    ${row.kurikulum_matakuliah.matakuliah.sks} SKS
                                                    <span class="badge badge-warning"> Kelompok ${row.kelompok} </span>
                                                </li>

                                                <li class="list-group-item">
                                                    <i class="fa fa-users list-group-icon"></i>
                                                    Ruang {{ @$row->ruang_kelas->kode }} / ${row.ruang} Orang
                                                    <span class="badge badge-primary">
                                                        Isi
                                                        ${row.jml_mhs}
                                                        Mhs
                                                    </span>
                                                </li>

                                                <li class="list-group-item">
                                                    <i class="fa fa-calendar list-group-icon"></i> Hari ${row.hari}
                                                    <span class="badge badge-danger">
                                                        `;
                        if (row.jam_kuliah_id != null) {
                            content += row.jamkul.nama;
                        } else {
                            content += `${row.jam_mulai} s.d ${row.jam_selesai}`;
                        }
                        content += `
                                                    </span>
                                                </li>

                                                <li class="list-group-item">
                                                    <i class="fa fa-check-circle-o list-group-icon"></i>

                                                    <label>Sudah Absen </label>
                                                    <span class="badge badge-danger">
                                                        ${row.pertemuan_ke} Kali
                                                    </span>
                                                </li>
                                            
                                                <li class="list-group-item">
                                                    <a href="${row.url_rekap}"
                                                        class="list-group-item text-center bg-info" style="color: #fff"
                                                        id="rekap-absensi">Rekap
                                                        Absensi
                                                        <i class="fa fa-file list-group-icon" style="color: #fff"></i></a>
                                                </li>
                                                <li class="list-group-item">
                                                    <a href="${row.url_absensi}"
                                                        class="list-group-item text-center active" id="input-absensi" onclick="load()">Input
                                                        Absensi
                                                        <i class="fa fa-check-square-o list-group-icon"></i></a>

                                                </li>
                                            </div>
                                        </div>
                                    </div>
                        `;
                    });
                    $('#data_jadwal').append(content);
                },
                complete: function() {
                    $("#detail").fadeIn();
                    $("#preload").fadeOut();
                }
            });
        }
    </script>
@endpush
