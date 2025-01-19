{{-- <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> --}}
<link rel="stylesheet" href="{{ public_path('css/cetak.css') }}">

@include('header_print')

<div style="font-size:12px;">
    <h3 class="text-center" style="font-size:14px;margin:3px;">DAFTAR HADIR PESERTA</h3>
    <h3 class="text-center" style="font-size:14px;margin:3px;">UJIAN AKHIR SEMESTER (UAS) DAN UJIAN KUALIFIKASI MUTU (UKM)
    </h3>
    <h3 class="text-center" style="font-size:14px;margin:3px;">UNIVERSITAS ISLAM INTERNASIONAL DARULLUGHAH WADDA'WAH</h3>
    <h3 class="text-center" style="font-size:14px;margin:3px;">SEMESTER {{ $th_akademik->semester }} TAHUN AKADEMIK
        {{ $th_akademik->nama }}</h3><br>

    <table>
        <thead>
            <tr>
                <td style="width: 20%">Ruang</td>
                <td style="width: 1%">:</td>
                <td style="width: 29%">{{ @$jadwal->kelas->nama }} / {{ @$jadwal->kelompok->kode }}</td>
                <td style="width: 20%">Mata Kuliah</td>
                <td style="width: 1%">:</td>
                <td style="width: 29%">{{ @$jadwal->kurikulum_matakuliah->matakuliah->kode }} -
                    {{ @$jadwal->kurikulum_matakuliah->matakuliah->nama }}</td>
            </tr>
            <tr>
                <td>Prodi / Semester</td>
                <td>:</td>
                <td>{{ @$jadwal->prodi->kode }} / {{ @$jadwal->kurikulum_matakuliah->matakuliah->smt }}</td>
                <td>Dosen Pengampu</td>
                <td>:</td>
                <td>{{ @$jadwal->dosen->kode }} - {{ @$jadwal->dosen->nama }}</td>
            </tr>
        </thead>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th class="text-center" width="2%">No</th>
                <th class="text-center" width="10%">NIM</th>
                <th class="text-center" width="23%">NAMA</th>
                <th class="text-center" width="15%" colspan="2">TANDA TANGAN</th>
                <th class="text-center" width="5%">NILAI</th>
                <th class="text-center" width="15%">KETERANGAN</th>
            </tr>
        </thead>

        <tbody>
            @php
                $no = 1;
            @endphp

            @foreach ($data as $row)
                <tr>
                    <td class="text-center">{{ $no }}.</td>
                    <td class="text-center">{{ $row->nim }}</td>
                    <td>{{ @$row->mahasiswa->nama }}</td>
                    <td style="border-right: none;" class="text-left">{{ $no % 2 == 0 ? $no : '' }}</td>
                    <td style="border-left: none;" class="text-left">{{ $no % 2 == 1 ? $no : '' }}</td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                </tr>
                @php
                    $no++;
                @endphp
            @endforeach
        </tbody>
    </table>
</div>
<br />
<br />

<table>
    <tr>
        <td width="33%" class="text-center">
        </td>

        <td width="33%" class="text-center">
        </td>

        <td width="33%" class="text-center">
            Pengawas Ruangan <br><br><br><br><br>
            ...........................................................
        </td>
    </tr>
</table>

@include('footer_print')
