<?php
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
header('Content-type: application/vnd-ms-excel');
header("Content-Disposition: attachment; filename=rekap-krs-$prodi->nama-$thAkademik->kode-$jenisKelamin.xls");
?>
<table border="1">
    <thead>
        <tr>
            <th style="font-weight:bold;vertical-align: center;text-align:center;background-color:#8DB4E2;">
                NO</th>
            <th style="font-weight:bold;vertical-align: center;text-align:center;background-color:#8DB4E2;">
                NIM</th>
            <th style="font-weight:bold;vertical-align: center;width:250px;text-align:center;background-color:#8DB4E2;">
                NAMA</th>
            <th style="font-weight:bold;vertical-align: center;width:250px;text-align:center;background-color:#8DB4E2;">
                JENIS KELAMIN</th>
            <th style="font-weight:bold;vertical-align: center;width:50px;text-align:center;background-color:#8DB4E2;">
                SEMESTER
            </th>
            <th style="font-weight:bold;vertical-align: center;width:100px;text-align:center;background-color:#8DB4E2;">
                STATUS</th>
            <th style="font-weight:bold;vertical-align: center;width:100px;text-align:center;background-color:#8DB4E2;">
                KETERANGAN</th>
        </tr>

    </thead>
    <tbody>
        @php
            $i = 1;
        @endphp
        @foreach ($mahasiswa as $m)
            <tr>
                <td>{{ $i++ }}</td>
                <td style="mso-number-format:\@">{{ $m->mhs_nim }} </td>
                <td>{{ $m->mhs_nama }}</td>
                <td>{{ isset($m->jk->nama) ? $m->jk->nama : '' }} </td>
                <td>{{ $m->mhs_semester }}</td>
                @if ($m->mhs_status == 'AKTIF')
                    <td style="background-color: rgb(89, 255, 89);">{{ $m->mhs_status }}</td>
                @else
                    <td>{{ $m->mhs_status }}</td>
                @endif
                @if ($m->mhs_keterangan == 'SUDAH ISI KRS')
                    <td style="background-color: rgb(89, 255, 89);">{{ $m->mhs_keterangan }}</td>
                @else
                    <td>{{ $m->mhs_keterangan }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
@if ($jenisKelamin == 'Putra Putri')
    <br><br>
    LAKI-LAKI
    <br>
    <table border="1">
        <thead>
            <tr>
                <th style="font-weight:bold;vertical-align: center;text-align:center;background-color:#8DB4E2;">
                    NO</th>
                <th
                    style="font-weight:bold;vertical-align: center;width:250px;text-align:center;background-color:#8DB4E2;">
                    SEMESTER</th>
                <th
                    style="font-weight:bold;vertical-align: center;width:50px;text-align:center;background-color:#8DB4E2;">
                    JUMLAH MHS
                </th>
                <th
                    style="font-weight:bold;vertical-align: center;width:100px;text-align:center;background-color:#8DB4E2;">
                    MAHASISWA AKTIF</th>
                <th
                    style="font-weight:bold;vertical-align: center;width:100px;text-align:center;background-color:#8DB4E2;">
                    SUDAH KRS</th>
                <th
                    style="font-weight:bold;vertical-align: center;width:100px;text-align:center;background-color:#8DB4E2;">
                    BELUM KRS</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @foreach ($dataPutra as $m)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $m->semester }}</td>
                    <td>{{ $m->jumlahMhs }}</td>
                    <td>{{ $m->jumlahMhsAktif }}</td>
                    <td>{{ $m->jumlahSudahKrs }}</td>
                    <td>{{ $m->jumlahBelumKrs }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    PEREMPUAN
    <br>
    <table border="1">
        <thead>
            <tr>
                <th style="font-weight:bold;vertical-align: center;text-align:center;background-color:#8DB4E2;">
                    NO</th>
                <th
                    style="font-weight:bold;vertical-align: center;width:250px;text-align:center;background-color:#8DB4E2;">
                    SEMESTER</th>
                <th
                    style="font-weight:bold;vertical-align: center;width:50px;text-align:center;background-color:#8DB4E2;">
                    JUMLAH MHS
                </th>
                <th
                    style="font-weight:bold;vertical-align: center;width:100px;text-align:center;background-color:#8DB4E2;">
                    MAHASISWA AKTIF</th>
                <th
                    style="font-weight:bold;vertical-align: center;width:100px;text-align:center;background-color:#8DB4E2;">
                    SUDAH KRS</th>
                <th
                    style="font-weight:bold;vertical-align: center;width:100px;text-align:center;background-color:#8DB4E2;">
                    BELUM KRS</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @foreach ($dataPutri as $m)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $m->semester }}</td>
                    <td>{{ $m->jumlahMhs }}</td>
                    <td>{{ $m->jumlahMhsAktif }}</td>
                    <td>{{ $m->jumlahSudahKrs }}</td>
                    <td>{{ $m->jumlahBelumKrs }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    SEMUA
    <br>
    <table border="1">
        <thead>
            <tr>
                <th style="font-weight:bold;vertical-align: center;text-align:center;background-color:#8DB4E2;">
                    NO</th>
                <th
                    style="font-weight:bold;vertical-align: center;width:250px;text-align:center;background-color:#8DB4E2;">
                    SEMESTER</th>
                <th
                    style="font-weight:bold;vertical-align: center;width:50px;text-align:center;background-color:#8DB4E2;">
                    JUMLAH MHS
                </th>
                <th
                    style="font-weight:bold;vertical-align: center;width:100px;text-align:center;background-color:#8DB4E2;">
                    MAHASISWA AKTIF</th>
                <th
                    style="font-weight:bold;vertical-align: center;width:100px;text-align:center;background-color:#8DB4E2;">
                    SUDAH KRS</th>
                <th
                    style="font-weight:bold;vertical-align: center;width:100px;text-align:center;background-color:#8DB4E2;">
                    BELUM KRS</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @foreach ($data as $m)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $m->semester }}</td>
                    <td>{{ $m->jumlahMhs }}</td>
                    <td>{{ $m->jumlahMhsAktif }}</td>
                    <td>{{ $m->jumlahSudahKrs }}</td>
                    <td>{{ $m->jumlahBelumKrs }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <br><br>
    <table border="1">
        <thead>
            <tr>
                <th style="font-weight:bold;vertical-align: center;text-align:center;background-color:#8DB4E2;">
                    NO</th>
                <th
                    style="font-weight:bold;vertical-align: center;width:250px;text-align:center;background-color:#8DB4E2;">
                    SEMESTER</th>
                <th
                    style="font-weight:bold;vertical-align: center;width:50px;text-align:center;background-color:#8DB4E2;">
                    JUMLAH MHS
                </th>
                <th
                    style="font-weight:bold;vertical-align: center;width:100px;text-align:center;background-color:#8DB4E2;">
                    MAHASISWA AKTIF</th>
                <th
                    style="font-weight:bold;vertical-align: center;width:100px;text-align:center;background-color:#8DB4E2;">
                    SUDAH KRS</th>
                <th
                    style="font-weight:bold;vertical-align: center;width:100px;text-align:center;background-color:#8DB4E2;">
                    BELUM KRS</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @foreach ($data as $m)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $m->semester }}</td>
                    <td>{{ $m->jumlahMhs }}</td>
                    <td>{{ $m->jumlahMhsAktif }}</td>
                    <td>{{ $m->jumlahSudahKrs }}</td>
                    <td>{{ $m->jumlahBelumKrs }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
