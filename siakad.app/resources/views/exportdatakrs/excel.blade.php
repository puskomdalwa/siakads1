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
                NIM
            </th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FFFF00;">
                Nama </th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:120px;background-color:#FF0000;color:#fff;">
                Semester</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:120px;background-color:#FF0000;color:#fff;">
                Kode Mata Kuliah</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:120px;background-color:#FFFF00;">
                Nama Mata Kuliah</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:120px;background-color:#FF0000;color:#fff;">
                Nama Kelas</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Kode Prodi</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:250px;background-color:#FFFF00;">
                Nama Prodi</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:100px;background-color:#00FF00;">
                Nilai Huruf</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:100px;background-color:#00FF00;">
                Nilai Indeks</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:100px;background-color:#00FF00;">
                Nilai Angka</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($jadwal as $j)
            @php
                $listMhs = App\KRSDetail::orderBy('nim', 'asc')
                    ->where('jadwal_kuliah_id', $j->id)
                    ->get();
                $matakuliah = @$j->kurikulum_matakuliah->matakuliah;
                $prodi = @$j->prodi;
            @endphp
            @foreach ($listMhs as $m)
                <tr>
                    <td style="mso-number-format:\@;vertical-align:middle;text-align:left">{{ @$m->nim }}</td>
                    <td style="vertical-align:middle;text-align:left">{{ @$m->mahasiswa->nama }}</td>
                    <td style="vertical-align:middle;text-align:left">{{ @$matakuliah->smt }}</td>
                    <td style="vertical-align:middle;text-align:left">{{ @$matakuliah->kode }}</td>
                    <td style="vertical-align:middle;text-align:left">{{ @$matakuliah->nama }}</td>
                    <td style="vertical-align:middle;text-align:left">{{ @$j->kelas->nama }}</td>
                    <td style="vertical-align:middle;text-align:left">{{ @$prodi->kode }}</td>
                    <td style="vertical-align:middle;text-align:left">{{ @$prodi->nama }}</td>
                    <td style="vertical-align:middle;text-align:left">{{ @$m->nilai_huruf }}</td>
                    <td style="vertical-align:middle;text-align:left">{{ @$m->nilai_bobot }}</td>
                    <td style="vertical-align:middle;text-align:left">{{ @$m->nilai_akhir }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
