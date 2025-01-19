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
                Semester
            </th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                NIDN</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:250px;background-color:#FF0000;">
                Nama Dosen</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:200px;background-color:#FF0000;color:#fff;">
                Kode Mata Kuliah</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:400px;background-color:#FF0000;">
                Nama Mata Kuliah</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:200px;background-color:#FF0000;color:#fff;">
                Nama Kelas/Kelompok</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:100px;background-color:#FF0000;color:#fff;">
                Tatap Muka</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:200px;background-color:#00FF00;">
                Tatap Muka Realisasi</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:100px;background-color:#FF0000;color:#fff;">
                Kode Prodi</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:350px;background-color:#00FF00;">
                Nama Prodi</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Sks Mata Kuliah</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Jenis Evaluasi</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($data as $dosen)
            @foreach ($dosen as $item)
                <tr>
                    <td style="text-align:left;vertical-align:middle">{{ $item->th_akademik_kode }}</td>
                    <td style="text-align:left;vertical-align:middle">{{ $item->dosen_nidn }}</td>
                    <td style="text-align:left;vertical-align:middle">{{ $item->dosen_nama }}</td>
                    <td style="text-align:left;vertical-align:middle">{{ $item->matakuliah_kode }}</td>
                    <td style="text-align:left;vertical-align:middle">{{ $item->matakuliah_nama }}</td>
                    <td style="text-align:left;vertical-align:middle">{{ $item->kelompok_kode }}</td>
                    <td style="text-align:left;vertical-align:middle">{{ $item->tatap_muka }}</td>
                    <td style="text-align:left;vertical-align:middle"></td>
                    <td style="text-align:left;vertical-align:middle">{{ $item->prodi_kode }}</td>
                    <td style="text-align:left;vertical-align:middle">{{ $item->prodi_nama }}</td>
                    <td style="text-align:left;vertical-align:middle">{{ $item->matakuliah_sks }}</td>
                    <td style="text-align:left;vertical-align:middle"></td>
                </tr>
            @endforeach
        @endforeach

    </tbody>
</table>
