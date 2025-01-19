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
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:200px;background-color:#FF0000;color:#fff;">
                Nama</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:200px;background-color:#FF0000;color:#fff;">
                Kelompok</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:200px;background-color:#FF0000;color:#fff;">
                Status</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Tempat Lahir</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Tanggal Lahir</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Jenis Kelamin</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                NIK
            </th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Agama</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                NISN</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                jalur Pendaftaran</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                NPWP</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Kewarganegaraan</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                jenis Pendaftaran</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Tanggal Masuk Kuliah</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Mulai Semester</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                jalan</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">RT
            </th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">RW
            </th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                Nama Dusun</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Kelurahan</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Kecamatan</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                Kode Pos</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Kabupaten</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Provinsi</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                Telp Rumah</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">No
                Hp</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:200px;background-color:#00FF00;">
                Email</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Terima KPS</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">No
                KPS</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                NIK Ayah</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                Nama Ayah</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                Tanggal Lahir Ayah</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                Pendidikan Ayah</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                Pekerjaan Ayah</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                Penghasilan Ayah</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                NIK Ibu</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Nama Ibu</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                Tanggal Lahir Ibu</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                Pendidikan Ibu</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                Pekerjaan Ibu</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                Penghasilan Ibu</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                Nama Wali</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                Tanggal Lahir Wali</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                Pendidikan Wali</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                Pekerjaan Wali</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#00FF00;">
                Penghasilan Wali</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Kode Prodi</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:250px;background-color:#FFFF00;">
                Nama Prodi</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#92D050;">
                SKS Diakui</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#92D050;">
                Kode PT Asal</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#92D050;">
                Nama PT Asal</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#92D050;">
                Kode Prodi Asal</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#92D050;">
                Nama Prodi Asal</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Jenis Pembiayaan</th>
            <th
                style="border:1px solid #000;height:25px;vertical-align:middle;width:150px;background-color:#FF0000;color:#fff;">
                Jumlah Biaya Masuk</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($mahasiswa as $m)
            <tr>
                <td style="mso-number-format:\@;vertical-align:middle;text-align:left">{{ $m->nim }}</td>
                <td style="vertical-align:middle;text-align:left">{{ $m->nama }}</td>
                <td style="vertical-align:middle;text-align:left">{{ @$m->kelompok->perwalian->kelompok->kode }}</td>
                <td style="vertical-align:middle;text-align:left">{{ @$m->status->nama }}</td>
                <td style="vertical-align:middle;text-align:left">{{ $m->tempat_lahir }}</td>
                <td style="vertical-align:middle;text-align:left">{{ $m->tanggal_lahir }}</td>
                <td style="vertical-align:middle;text-align:left">
                    {{ $m->jk_id != null ? $m->jk->kode : 'Tidak Diketahui' }}</td>
                <td style="mso-number-format:\@;vertical-align:middle;text-align:left">{{ $m->nik }}</td>
                <td style="vertical-align:middle;text-align:left">
                    {{ $m->agama_id != null ? $m->agama->nama : 'Islam' }}</td>
                <td style="vertical-align:middle;text-align:left">{{ $m->nisn }}</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">{{ $m->tanggal_masuk }}</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">{{ $m->kelurahan }}</td>
                <td style="vertical-align:middle;text-align:left">{{ $m->kecamatan }}</td>
                <td style="vertical-align:middle;text-align:left">{{ $m->kodepos }}</td>
                <td style="vertical-align:middle;text-align:left">{{ $m->kota }}</td>
                <td style="vertical-align:middle;text-align:left">{{ $m->propinsi }}</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">{{ $m->hp }}</td>
                <td style="vertical-align:middle;text-align:left">{{ $m->email }}</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">{{ $m->nik_ayah }}</td>
                <td style="vertical-align:middle;text-align:left">{{ $m->nama_ayah }}</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">{{ $m->nik_ibu }}</td>
                <td style="vertical-align:middle;text-align:left">{{ $m->nama_ibu }}</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">{{ $m->prodi->kode }}</td>
                <td style="vertical-align:middle;text-align:left">{{ $m->prodi->nama }}</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
                <td style="vertical-align:middle;text-align:left">-</td>
            </tr>
        @endforeach
    </tbody>
</table>
