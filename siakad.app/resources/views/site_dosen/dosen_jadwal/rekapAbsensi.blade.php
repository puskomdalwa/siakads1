@extends('layouts.app')
@section('title', $title)

@section('content')
    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">{{ $title }}</span>
            <div class="panel-heading-controls">
                <a href="http://192.168.1.10/siakad-baru/siakad/dosen_jadwal" class="btn btn-sm btn-primary">
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
                                {{ $data->prodi->nama }} |
                                {{ $data->kurikulum_matakuliah->matakuliah->kode }} -
                                {{ $data->kurikulum_matakuliah->matakuliah->nama }}
                                ({{ $data->kurikulum_matakuliah->matakuliah->sks }} SKS)
                            </td>
                        </tr>

                        <tr>
                            <td class="panel-padding">Semester | Kelompok | Ruang</td>
                            <td class="panel-padding">
                                {{ $data->kurikulum_matakuliah->matakuliah->smt }} |
                                {{ $data->kelompok->kode }} | {{ $data->ruang_kelas->kode }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="panel panel-success panel-dark">
        <div class="panel-heading">
            <span class="panel-title">Daftar Pertemuan</span>
            <div class="panel-heading-controls">
                <a href=" {{ url($redirect . '/' . $data->id . '/0/absensi') }} " class="btn btn-md btn-primary"
                    id="input-absensi">Input
                    Absensi
                    <i class="fa fa-plus list-group-icon"></i></a>
            </div>
        </div>

        <div class="table-responsive">
            <table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Absensi</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($absen->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center">Belum ada absensi</td>
                        </tr>
                    @else
                        @php
                            $i = 1;
                            $ke = 1;
                        @endphp
                        @foreach ($absen as $row)
                            <tr>
                                <td class="text-center">{{ $i++ }}</td>
                                <td class="text-center">Pertemuan Ke {{ $ke }}</td>
                                <td class="text-center">
                                    {{ !empty($row->tanggal) ? format_long_date($row->tanggal) : date('d M Y') }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-xs" id="c-tooltips-demo">
                                        <a href="{{ url("dosen_jadwal/$data->id/$row->id/absensi") }}"
                                            class="btn btn-primary btn-lg btn-rounded tooltip-primary" data-toggle="tooltip"
                                            data-placement="top" data-original-title="Edit">
                                            <i class="fa fa-pencil"></i> Edit</a>

                                        <a onclick="deleteForm({{ $row->id }}, 'Pertemuan Ke {{ $ke }}')"
                                            class="btn btn-danger btn-lg btn-rounded tooltip-danger delete"
                                            data-toggle="tooltip" data-placement="top" data-original-title="Delete">
                                            <i class="fa fa-times"></i> Delete</a>
                                    </div>
                                </td>
                            </tr>
                            @php
                                $ke++;
                            @endphp
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function deleteForm(id, pertemuan) {
            swal({
                title: "Anda Yakin ?",
                type: "warning",
                text: "Data yang sudah dihapus tidak dapat kembali.",
                showCancelButton: "true",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.value) {
                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ url($redirect) }}" + '/' + id + '/delete-absensi',
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': csrf_token,
                            'pertemuan': pertemuan,
                        },
                        beforeSend: function() {
                            $('.delete').attr('disabled', true);

                        },
                        success: function(data) {
                            swal(data.title, data.message, data.type);
                            location.reload();
                        },
                        error: function() {
                            swal(
                                'Error Deleted!',
                                'Silahkan Hubungi Administrator',
                                'error'
                            )
                        },
                        complete: function() {
                            $('.delete').attr('disabled', false);
                        }
                    });
                }
            });
        }
    </script>
@endpush
