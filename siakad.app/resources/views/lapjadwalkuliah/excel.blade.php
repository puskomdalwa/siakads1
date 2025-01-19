<?php
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
header('Content-type: application/vnd-ms-excel');
header('Content-Disposition: attachment; filename=' . $judul . '.xls');
?>
<table>
    <tr>
        <th colspan="2" rowspan="4">
            <img src="{{ asset('img/' . $pt->logo) }}" width="100" height="100" alt="">
        </th>
        <th colspan="3" style="font-size: 20px">Universitas Islam Internasional Darullughah Wadda'wah</th>
    </tr>
    <tr>
        <th colspan="3">SK. Mendiknas RI Nomor 3530 Tahun 2013</th>
    </tr>
    <tr>
        <th colspan="3">Alamat : Jl. Raya Raci No. 51 PO BOX 8 Bangil Pasuruan Jawa Timur - KABUPATEN PASURUAN.
            Telp:0343-745317</th>
    </tr>
    <tr>
        <th colspan="3">Email : inidalwa@yahoo.com Website : http://www.iaidalwa.ac.id</th>
    </tr>
</table>

<hr>
<table>
    <thead>
        <tr>
            <th colspan="9">
                <h3 class="text-center" style="margin:2px;">JADWAL KULIAH SEMESTER
                    {{ strtoupper($th_akademik->semester) }}<br></h3>
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
    </thead>
</table>

<br>

@foreach ($list_smt as $smt)
    <div class="table-light">
        <div class="table-header">
            @if ($smt->smt % 2 == 0)
                <div class="table-caption text-left"> <br> SEMESTER: {{ $smt->smt }} (Genap) </div>
            @else
                <div class="table-caption text-left"> <br> SEMESTER: {{ $smt->smt }} (Ganjil) </div>
            @endif
        </div>

        <table class="data" border="1">
            <thead>
                <tr>
                    <th class="text-center" width="10px">NO</th>
                    <th class="text-center" width="50px">KODE MK</th>
                    <th class="text-center" width="210px">MATA KULIAH</th>
                    <th class="text-center" width="10px">SKS</th>
                    <th class="text-center" width="250px">DOSEN</th>
                    <th class="text-center" width="50px">KELAS</th>
                    <th class="text-center" width="90px">RUANG</th>
                    <th class="text-center" width="50px">HARI</th>
                    <th class="text-center" width="50px">WAKTU</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $no = 1;
                    $tsks = 0;
                    $list_jadwal = App\JadwalKuliah::where('th_akademik_id', $th_akademik->id)
                        ->where('prodi_id', $prodi_id)
                        ->where('smt', $smt->smt)
                        ->orderBy('hari_id')
                        ->with(['th_akademik', 'prodi', 'kelas', 'jamkul'])
                        ->get();
                @endphp

                @foreach ($list_jadwal as $jadwal)
                    <tr>
                        <td class="text-center"> {{ $no++ }} </td>
                        <td class="text-center"> {{ @strtoupper($jadwal->kurikulum_matakuliah->matakuliah->kode) }}
                        </td>
                        <td>{{ @strtoupper($jadwal->kurikulum_matakuliah->matakuliah->nama) }}</td>
                        <td class="text-center"> {{ @$jadwal->kurikulum_matakuliah->matakuliah->sks }} </td>
                        <td>{{ @$jadwal->dosen->nama }}</td>
                        <td class="text-center"> {{ @strtoupper($jadwal->kelas->nama) }} </td>
                        <td class="text-center"> {{ @strtoupper($jadwal->ruang_kelas->nama) }} </td>
                        <td class="text-center"> {{ @strtoupper($jadwal->hari->nama) }} </td>
                        <!-- <td class="text-center"> {{ @$jadwal->jam_mulai }} s.d {{ @$jadwal->jam_selesai }} </td> -->
                        <td class="text-center"> {{ @$jadwal->jamkul->nama }} </td>
                    </tr>

                    @php $tsks += @$jadwal->kurikulum_matakuliah->matakuliah->sks; @endphp
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td class="text-center" colspan="3">JUMLAH</td>
                    <td class="text-center"> {{ $tsks }} </td>
                    <td colspan="5"></td>
                </tr>
            </tfoot>
        </table>
    </div>
@endforeach
