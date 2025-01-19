<?php
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
header('Content-type: application/vnd-ms-excel');
header('Content-Disposition: attachment; filename="' . $nama . '"');
?>
<table>
    <thead>
        <tr>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Kode Mata Kuliah
            </th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:200px;background-color:#FF0000;color:#fff;">
                Nama MK</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:100px;background-color:#FF0000;color:#fff;">
                Jenis MK</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:120px;background-color:#FF0000;color:#fff;">
                SKS Tatap Muka</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:100px;background-color:#FF0000;color:#fff;">
                SKS Praktek</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                SKS Prak Lapangan
            </th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:100px;background-color:#FF0000;color:#fff;">
                SKS Simulasi</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                Metode Pembelajaran</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:120px;background-color:#00FF00;">
                Tgl Mulai Efektif</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:120px;background-color:#00FF00;">
                Tgl Akhir Efektif</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:120px;background-color:#FF0000;color:#fff;">
                Kode Prodi</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:250px;background-color:#FFFF00;">
                Nama Prodi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($matkul as $m)
            <tr>
                <td style="vertical-align:middle;text-align:left">{{ $m->kode }}</td>
                <td style="vertical-align:middle;text-align:left">{{ $m->nama }}</td>
                <td style="vertical-align:middle;text-align:left">Wajib Program Studi</td>
                <td style="vertical-align:middle;text-align:left">{{ $m->sks }}</td>
                <td style="vertical-align:middle;text-align:left">0</td>
                <td style="vertical-align:middle;text-align:left">0</td>
                <td style="vertical-align:middle;text-align:left">0</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">
                    {{ $m->prodi_id != null && $m->prodi_id != 2 ? $m->prodi->kode : '' }}
                </td>
                <td style="vertical-align:middle;text-align:left">
                    {{ $m->prodi_id != null && $m->prodi_id != 2 ? $m->prodi->nama : '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
