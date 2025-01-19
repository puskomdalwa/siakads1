@push('css')
    <style media="screen">
        .text-bold {
            font-weight: bold;
        }

        /*
                #serversideTable th {
                 background-color: #d0e6be;;
                 color: #000;
                 font-weight: bold;
                 border-color: #000 !important;
                }
                #serversideTable td {
                 border-color: #000 !important;
                }
                */
    </style>
@endpush

<input type="hidden" name="jadwal_kuliah_id" value="{{ $data->id }}">

<div class="note note-warning">
    <div class="table-responsive">
        <table class="table table-bordered table-striped" style="clear:both">
            <tbody>
                <tr>
                    <td width="17.5%">Tahun Akademik</td>
                    <td width="32.5%" class="text-bold">: {{ $data->th_akademik->kode }}</td>

                    <td width="17.5%">Program Studi</td>
                    <td width="32.5%" class="text-bold">: {{ $data->prodi->nama }}</td>
                </tr>

                <tr>
                    <td>Kurikulum</td>
                    <td class="text-bold">: {{ $data->kurikulum_matakuliah->kurikulum->nama }}</td>

                    <td>Semester</td>
                    <td class="text-bold">: {{ $data->kurikulum_matakuliah->matakuliah->smt }}</td>
                </tr>

                <tr>
                    <td>Mata Kuliah</td>
                    <td class="text-bold">: {{ $data->kurikulum_matakuliah->matakuliah->kode }} -
                        {{ $data->kurikulum_matakuliah->matakuliah->nama }}
                        ({{ $data->kurikulum_matakuliah->matakuliah->sks }} SKS)</td>

                    <td>Kelas / Kelomok</td>
                    <td class="text-bold">: {{ $data->kelas->nama }} / {{ $data->kelompok->kode }}</td>
                </tr>

                <tr>
                    <td>Dosen</td>
                    <td class="text-bold">: {{ $data->dosen->kode }} - {{ $data->dosen->nama }}</td>

                    <td>Ruangan</td>
                    <td class="text-bold">: {{ $data->ruang_kelas->nama }}</td>
                </tr>

                <tr>
                    <td>Hari / Kehadiran(TM) </td>
                    <td class="text-bold">: {{ $data->hari->nama }} / {{ jmlabsdos($data->id) }} X Hadir </td>

                    <td>Waktu</td>
                    <td class="text-bold">: Jam ke: ({{ $data->jamkul->kode }}) => {{ $data->jamkul->nama }} </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!--
<div class="panel-heading-controls"><a href="{{ url($redirect) }}" class="btn btn-sm btn-primary">
<i class="fa fa-chevron-circle-left"></i> Kembali </a></div>

<div class="panel-footer text-center">
<button type="submit" name="cetak" id="cetak" value="pdf" class="btn btn-info btn-flat">
<i class="fa fa-print"></i> Cetak PDF</button>

<button type="submit" name="cetak" id="cetak" value="excel" class="btn btn-info btn-flat">
<i class="fa fa-th"></i> Cetak Excel</button>
</div>
-->

@include($folder . '/listmhs')