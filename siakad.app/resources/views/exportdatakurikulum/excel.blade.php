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
                Nama Kurikulum
            </th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Mulai Berlaku</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                Jumlah SKS Lulus</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:120px;background-color:#FF0000;color:#fff;">
                Jumlah SKS Wajib</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Jumlah SKS Pilihan</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:120px;background-color:#FF0000;color:#fff;">
                Kode Prodi</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:250px;background-color:#FFFF00;">
                Nama Prodi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($kurikulum as $k)
            <tr>
                <td style="vertical-align:middle;text-align:left">{{ $k->nama }}</td>
                <td style="vertical-align:middle;text-align:left">
                    {{ $k->th_akademik_id != null ? $k->th_akademik->kode : '' }}</td>
                <td style="vertical-align:middle;text-align:left">0</td>
                <td style="vertical-align:middle;text-align:left">{{ $k->sks_wajib }}</td>
                <td style="vertical-align:middle;text-align:left">0</td>
                <td style="vertical-align:middle;text-align:left">{{ $k->prodi_id != null ? $k->prodi->kode : '' }}</td>
                <td style="vertical-align:middle;text-align:left">{{ $k->prodi_id != null ? $k->prodi->nama : '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
