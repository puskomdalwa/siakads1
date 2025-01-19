<?php
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
header('Content-type: application/vnd-ms-excel');
header('Content-Disposition: attachment; filename=' . $judul . '.xls');
?>
<table border="1">
    <thead>
        <tr>
            <th colspan="9">
                <h3 class="text-center" style="margin:2px;">DAFTAR MAHASISWA</h3>
            </th>
        </tr>
        <tr>
            <th colspan="9">
                <h3 class="text-center" style="margin:2px;">TAHUN AKADEMIK {{ $th_akademik->nama }}</h3>
            </th>
        </tr>
        <tr>
            <th colspan="9">
                <h3 class="text-center" style="margin:2px;">PROGRAM STUDI {{ strtoupper($dt_prodi->nama) }}
                    ({{ $dt_prodi->jenjang }})
                </h3>
            </th>
        </tr>
        <tr></tr>
    </thead>
    <thead>
        <tr>
            <th class="text-center" style="width: 30px">No</th>
            <th class="text-center">NIM</th>
            <th class="text-center">Nama</th>
            <th class="text-center">Tempat Lahir</th>
            <th class="text-center">Tanggal Lahir</th>
            <th class="text-center">NIK</th>
            <th class="text-center">L/P</th>
            <th class="text-center">Kelompok</th>
            <th class="text-center">Status</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($data as $row)
            <tr>
                <td class="text-center" style="width: 30px;vertical-align:middle"> {{ $no++ }} </td>
                <td class="text-center"
                    style="mso-number-format:\@;vertical-align:middle;text-align:right ;width: 150px">
                    {{ @strtoupper($row->nim) }}</td>
                <td style="vertical-align:middle">{{ @strtoupper($row->nama) }}</td>
                <td class="text-center" style="width: 150px;text-align:right;vertical-align:middle">
                    {{ @strtoupper($row->tempat_lahir) }}
                </td>
                <td class="text-center" style="width: 150px; text-align:right;vertical-align:middle">
                    {{ date('d/m/Y', strtotime(@$row->tanggal_lahir)) }}
                </td>
                <td class="text-center"
                    style="mso-number-format:\@;vertical-align:middle;text-align:right ;width: 150px">
                    {{ @$row->nik }} </td>
                <td class="text-center" style="width: 50px;text-align:right;vertical-align:middle">
                    {{ @$row->jk->kode }} </td>
                <td class="text-center" style="width: 100px;text-align:right;vertical-align:middle">
                    {{ @$row->kelompok->perwalian->kelompok->kode }} </td>
                <td class="text-center" style="width: 50px;text-align:right;vertical-align:middle">
                    {{ @$row->status->nama }} </td>
            </tr>
        @endforeach
    </tbody>
</table>
<br>
