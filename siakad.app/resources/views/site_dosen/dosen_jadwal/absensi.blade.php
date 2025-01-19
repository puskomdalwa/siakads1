@extends('layouts.app')
@section('title', $title)

@section('content')
    <form action="{{ url($redirect . '/simpanabsensi') }}" method="POST" id="simpan-absensi">
        <input name="_token" type="hidden" value="{{ csrf_token() }}">
        <input type="hidden" name="trans_jadwal_kuliah_id" id="trans_jadwal_kuliah_id" value="{{ $data->id }}">
        <input type="hidden" name="trans_absensi_mhs" id="trans_absensi_mhs"
            value="{{ !empty($absen->id) ? $absen->id : 0 }}">

        <div class="panel panel-danger panel-dark">
            <div class="panel-heading">
                <span class="panel-title">@yield('title')</span>
                <div class="panel-heading-controls">
                    <a href="{{ route('dosen_jadwal.rekapAbsensi', ['id' => $data->id]) }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-chevron-circle-left"></i> Kembali</a>
                </div>
            </div>

            <div class="panel-body">
                <div class="row">
                    <table class="table table-bordered table-striped table-hover">
                        <tbody>
                            <tr>
                                <td width="22%" class="panel-padding">Program Studi | Matakuliah</td>
                                <td class="panel-padding">
                                    {{ @$data->prodi->nama }} |
                                    {{ @$data->kurikulum_matakuliah->matakuliah->kode }} -
                                    {{ @$data->kurikulum_matakuliah->matakuliah->nama }}
                                    ({{ @$data->kurikulum_matakuliah->matakuliah->sks }} SKS)
                                </td>
                            </tr>

                            <tr>
                                <td class="panel-padding">Semester | Kelompok | Ruang</td>
                                <td class="panel-padding">
                                    {{ @$data->kurikulum_matakuliah->matakuliah->smt }} |
                                    {{ @$data->kelompok->kode }} | {{ @$data->ruang_kelas->kode }}
                                </td>
                            </tr>

                            <tr>
                                <td class="panel-padding">Hari | Jam | Tanggal</td>
                                <td class="panel-padding">
                                    <!-- {{ 'ke: ' . pertemuanke($data->id) }} - -->
                                    {{ @$data->hari->nama }} | {{ @$data->jam_kul->nama }} |
                                    {{ !empty($absen->tanggal) ? format_long_date($absen->tanggal) : date('d M Y') }}
                                </td>
                            </tr>

                            <tr>
                                <td class="panel-padding">Materi Perkuliahan</td>
                                <td class="panel-padding">
                                    <textarea name="materi" id="materi" cols="20" rows="5" class="form-control">
                                        @if ($absen)
{{ $absen->materi }}
@else
Pertemuan ke {{ $pertemuanKe }} :
@endif
                                    </textarea>
                                    @if ($errors->has('materi'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('materi') }}</strong></span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel widget-chat">
            <div class="panel-heading">
                <span class="panel-title"><i class="panel-title-icon fa fa-users"></i>Daftar Mahasiswa</span>
                <div class="panel-heading-controls">
                    <a href="{{ url($redirect . '/' . $data->id . '/rekapAbsensi') }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-file"></i> Lihat Rekap Absensi</a>
                </div>
            </div> <!-- / .panel-heading -->

            <div class="panel-body ">
                <button type="submit" class="btn btn-lg btn-block btn-info" id="submit-atas"> Simpan Kehadiran
                    <i class="fa fa-floppy-o"></i> </button><br>
                <div style="display:flex;
                overflow-x: auto;
                margin-bottom: 10px;">
                    <button class="btn btn-md btn-success" style="margin-bottom:10px;margin-left:5px" id="hadir-semua">
                        <i class="fa fa-check"></i> Ceklist Hadir Semua
                    </button>
                    <button class="btn btn-md btn-primary" style="margin-bottom:10px;margin-left:5px" id="alpa-semua">
                        <i class="fa fa-check"></i> Ceklist Alpa Semua
                    </button>
                    <button class="btn btn-md btn-info" style="margin-bottom:10px;margin-left:5px" id="sakit-semua">
                        <i class="fa fa-check"></i> Ceklist Sakit Semua
                    </button>
                    <button class="btn btn-md btn-dark" style="margin-bottom:10px;margin-left:5px" id="ijin-semua">
                        <i class="fa fa-check"></i> Ceklist Ijin Semua
                    </button>
                    <div style="margin-left: auto;">
                        <button class="btn btn-md btn-danger" style="margin-bottom:10px;margin-left:5px;" id="hapus-cek">
                            <i class="fa fa-check"></i> Hapus Ceklist
                        </button>
                    </div>
                </div>
                <div class="table-responsive" style="overflow-x: auto; max-width: 100%;">
                    <table class="table table-bordered table-hover table-striped table-responsive">
                        <thead>
                            <tr>
                                <th width="2%" class="panel-padding-h text-center valign-middle" rowspan="2">No</th>
                                <th class="panel-padding-h text-center valign-middle" rowspan="2">Mahasiswa</th>
                                <th class="panel-padding-h text-center" colspan="4">Status</th>
                            </tr>

                            <tr>
                                <th class="panel-padding-h" width="2%">Hadir</th>
                                <th class="panel-padding-h" width="2%">Alpa</th>
                                <th class="panel-padding-h" width="2%">Sakit</th>
                                <th class="panel-padding-h" width="2%">Ijin</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $no = 0;
                                $absen_id = !empty($absen->id) ? $absen->id : 0;
                            @endphp

                            @foreach ($mhs as $row)
                                <tr>
                                    @php $no++; @endphp
                                    <td class="panel-padding valign-middle">{{ number_format($no, 0) }}</td>
                                    @php
                                        if ($row->absensi_status) {
                                            $status = $row->absensi_status;
                                        } else {
                                            $status = 'Alpha';
                                        }
                                    @endphp

                                    <td class="panel-padding">
                                        <input type="hidden" name="absen[{{ $no }}][nim]"
                                            value=" {{ $row->nim }} ">
                                        <strong>{{ @$row->mahasiswa->nama }} {!! @$row->mahasiswa->spm == "iya" ? " <span class='badge badge-warning'>SPM</span>" : "" !!}</strong> <br />
                                        {{ $row->nim }} - {{ @$row->mahasiswa->prodi->alias }} -
                                        {{ @$row->mahasiswa->jk->nama == 'Laki-laki' ? 'L' : 'P' }}
                                    </td>

                                    <td class="panel-padding valign-middle text-center">
                                        <label class="px-single">
                                            <input type="radio" id="hadir"
                                                name="absen[{{ $no }}][status]" value="Hadir"
                                                class="px hadir" {{ $status == 'Hadir' ? 'checked' : '' }}>
                                            <span class="lbl"></span></label>
                                    </td>

                                    <td class="panel-padding valign-middle text-center">
                                        <label class="px-single">
                                            <input type="radio" name="absen[{{ $no }}][status]"
                                                value="Alpa" class="px alpa" {{ $status == 'Alpa' ? 'checked' : '' }}>
                                            <span class="lbl"></span></label>
                                    </td>

                                    <td class="panel-padding valign-middle text-center">
                                        <label class="px-single">
                                            <input type="radio" name="absen[{{ $no }}][status]"
                                                value="Sakit" class="px sakit"
                                                {{ $status == 'Sakit' ? 'checked' : '' }}>
                                            <span class="lbl"></span></label>
                                    </td>

                                    <td class="panel-padding valign-middle text-center">
                                        <label class="px-single">
                                            <input type="radio" name="absen[{{ $no }}][status]"
                                                value="Ijin" class="px ijin" {{ $status == 'Ijin' ? 'checked' : '' }}>
                                            <span class="lbl"></span></label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-lg btn-block btn-info" id="submit-bawah"> Simpan Kehadiran
                    <i class="fa fa-floppy-o"></i> </button>

            </div> <!-- / .panel-body -->
        </div>
    </form>
@endsection

@push('css')
    <link href="{{ asset('assets/stylesheets/widgets.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@push('scripts')
    <script type="text/javascript">
        init.push(function() {
            if (!$('html').hasClass('ie8')) {
                $('#materi').summernote({
                    height: 200,
                    tabsize: 2,
                    codemirror: {
                        theme: 'monokai'
                    }
                });
            }
        });

        $('#submit-atas').click(function(e) {
            e.preventDefault();
            $('#materi').val($('.note-editable').html());
            $(this).prop('disabled', true);
            $('#submit-bawah').prop('disabled', true);
            document.getElementById('simpan-absensi').submit()
        });

        $('#submit-bawah').click(function(e) {
            e.preventDefault();
            $('#materi').val($('.note-editable').html());
            $(this).prop('disabled', true);
            $('#submit-atas').prop('disabled', true);
            document.getElementById('simpan-absensi').submit()
        });

        $('#hadir-semua').click(function(e) {
            e.preventDefault();
            swal({
                title: "Apakah Anda Yakin?",
                type: "info",
                text: "ingin ceklist HADIR untuk semua mahasiswa",
                showCancelButton: "true",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#5DBC5D",
                confirmButtonText: "Ya, Ceklist",
            }).then((result) => {
                if (result.value) {
                    $("input:radio.hadir").prop("checked", true);
                }
            });
        });
        $('#alpa-semua').click(function(e) {
            e.preventDefault();
            swal({
                title: "Apakah Anda Yakin?",
                type: "info",
                text: "ingin ceklist Alpa untuk semua mahasiswa",
                showCancelButton: "true",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#5DBC5D",
                confirmButtonText: "Ya, Ceklist",
            }).then((result) => {
                if (result.value) {
                    $("input:radio.alpa").prop("checked", true);
                }
            });
        });
        $('#sakit-semua').click(function(e) {
            e.preventDefault();
            swal({
                title: "Apakah Anda Yakin?",
                type: "info",
                text: "ingin ceklist Sakit untuk semua mahasiswa",
                showCancelButton: "true",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#5DBC5D",
                confirmButtonText: "Ya, Ceklist",
            }).then((result) => {
                if (result.value) {
                    $("input:radio.sakit").prop("checked", true);
                }
            });
        });
        $('#ijin-semua').click(function(e) {
            e.preventDefault();
            swal({
                title: "Apakah Anda Yakin?",
                type: "info",
                text: "ingin ceklist Ijin untuk semua mahasiswa",
                showCancelButton: "true",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#5DBC5D",
                confirmButtonText: "Ya, Ceklist",
            }).then((result) => {
                if (result.value) {
                    $("input:radio.ijin").prop("checked", true);
                }
            });
        });
        $('#hapus-cek').click(function(e) {
            e.preventDefault();
            swal({
                title: "Anda Yakin?",
                type: "warning",
                text: "ingin ingin menghapus semua ceklist",
                showCancelButton: "true",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus",
            }).then((result) => {
                if (result.value) {
                    $("input:radio.px").prop("checked", false);
                }
            });
        });
    </script>
@endpush
