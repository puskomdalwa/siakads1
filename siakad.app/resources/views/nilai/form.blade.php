@php
    //date_default_timezone_set('Asia/Jakarta');
    
    // $batas= mktime(date("d"),date("m"),date("Y"));
    // $batas = date('2022-09-23');
    
    //$tgl = tgl_jam($tgl1);
    
    //$tgl = date('Y-m-d H:i:s');
    //$tgl_mulai   = $form->tgl_mulai;
    //$tgl_selesai = $form->tgl_selesai;
    
    //$level = strtolower(Auth::user()->level->level);
    
    $buka = 1;
@endphp

@push('css')
    <style media="screen">
        .text-bold {
            font-weight: bold;
        }

        /* #serversideTable th {
         background-color: #d0e6be;;
         color: #000;
         font-weight: bold;
         border-color: #000 !important;
        }

        #serversideTable td {
         border-color: #000 !important;
        } */
    </style>
@endpush

<input type="hidden" name="jadwal_kuliah_id" value="{{ $data->id }}">

<div class="note note-warning">
    <div class="table-responsive">
        <table class="table table-bordered table-striped" style="clear:both">
            <tbody>
                <tr>
                    <td width="12.5%">Tahun Akademik</td>
                    <td width="47.5%" class="text-bold">: {{ $data->th_akademik->kode }}</td>
                    <td width="12.5%">Program Studi</td>
                    <td width="27.5%" class="text-bold">: {{ $data->prodi->nama }}</td>
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
                    <td>Hari</td>
                    <td class="text-bold">: {{ $data->hari->nama }}</td>
                    <td>Waktu</td>
                    <td class="text-bold">: {{ $data->jamkul->nama }}</td>
                    <!-- <td class="text-bold">: {{ $data->jam_mulai }} s.d {{ $data->jam_selesai }}</td> -->
                </tr>
            </tbody>
        </table>
    </div>

    @if ($level != 'admin' && $level != 'baak' && $level != 'baak (hanya lihat)' && $level != 'prodi')
        $buka = 0;
        @if ($tgl >= $form->tgl_mulai && $tgl <= $form->tgl_selesai)
            <h4><b>
                    <center> Mohon Diperhatikan !!! <br />
                        Batas Waktu Pengisian Nilai Tanggal: {{ $form->tgl_mulai }} s/d {{ $form->tgl_selesai }}
                        (Mohon Nilai Diisi Dengan Angka Bulat)</h4>
            </center></b></h4>
        @else
            @if ($tgl >= $form->tgl_selesai)
                <h3><b>
                        <center> Mohon Maaf, Pengisian Nilai Sudah Ditutup !!! </center>
                    </b></h3>
            @else
                <h3><b>
                        <center> Mohon Maaf, Pengisian Nilai Belum Dibuka !!! </center>
                    </b></h3>
            @endif
        @endif
    @endif
</div>

@if ($buka)
    @include($folder . '/listmhs')
@endif
