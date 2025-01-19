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
                Kode Matakuliah</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FFFF00;">
                Nama Matakuliah</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:120px;background-color:#FF0000;color:#fff;">
                Nama Kelas</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                Bahasan</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:250px;background-color:#00FF00;">
                Tanggal Mulai Efektif</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:250px;background-color:#00FF00;">
                Tanggal Akhir Efektif</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:250px;background-color:#00FF00;">
                Lingkup Kelas</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:250px;background-color:#00FF00;">
                Mode Kuliah</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Kode Prodi</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:250px;background-color:#FFFF00;">
                Nama Prodi</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#92D050 ;">
                Sks Tatap Muka</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#92D050 ;">
                Sks Praktek</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#92D050 ;">
                Sks Praktek Lapangan</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#92D050 ;">
                Sks Simulasi</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Nama Dosen</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                NIDN</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($kelas as $k)
            <tr>
                <td>{{ @$k->th_akademik->kode }}</td>
                <td>{{ @$k->kurikulum_matakuliah->matakuliah->kode }}</td>
                <td>{{ @$k->kurikulum_matakuliah->matakuliah->nama }}</td>
                <td>{{ @$k->kelompok->kode }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ @$k->prodi->kode }}</td>
                <td>{{ @$k->prodi->nama }}</td>
                <td>{{ @$k->kurikulum_matakuliah->matakuliah->sks }}</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>{{ @$k->dosen->nama }}</td>
                <td>{{ @$k->dosen->nidn }}</td>
            </tr>
        @endforeach

    </tbody>
</table>
