@php
    // Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename="' . $nama . '"');
@endphp

<table>
    <tr>
        <td>Lampiran</td>
        <td>:</td>
    </tr>
    <tr>
        <td>Keputusan Dekan Fakultas Syariah</td>
        <td></td>
    </tr>
    <tr>
        <td>Universitas Islam Internasional Darullughah Wadda'wah</td>
        <td></td>
    </tr>
    <tr>
        <td>Nomor</td>
        <td>: {{ $nomerSk }}</td>
    </tr>
    <tr>
        <td>Tanggal</td>
        <td>: {{ $tanggal }}</td>
    </tr>
    <tr>
        <td>Tentang</td>
        <td>: {{ $tentang }}</td>
    </tr>
    <tr></tr>
</table>


<table>
    <thead>
        <tr>
            <th rowspan="2" style="border:1px solid #000;height:25px;vertical-align:middle;width:40px;">
                NO
            </th>
            <th rowspan="2" style="border:1px solid #000;height:25px;vertical-align:middle;width:250px;">
                NAMA DOSEN</th>
            <th rowspan="2" style="border:1px solid #000;height:25px;vertical-align:middle;width:200px;">
                KODE MK</th>
            <th rowspan="2" style="border:1px solid #000;height:25px;vertical-align:middle;width:400px;">
                MATAKULIAH</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:200px;">
                SKS</th>
            <th rowspan="2" style="border:1px solid #000;height:25px;vertical-align:middle;width:100px;">
                JUR/PRODI</th>
            <th rowspan="2" style="border:1px solid #000;height:25px;vertical-align:middle;width:200px;">
                KELAS</th>
            <th rowspan="2" style="border:1px solid #000;height:25px;vertical-align:middle;width:100px;">
                SMT</th>
            <th colspan="2" style="border:1px solid #000;height:25px;vertical-align:middle;width:350px;">
                SKS (JS)</th>
        </tr>
        <tr>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:200px;">
                JS</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:200px;">
                JUMLAH</th>
            <th style="border:1px solid #000;height:25px;vertical-align:middle;width:200px;">
                TOTAL</th>
        </tr>
    </thead>
    <tbody>

        @php
            $no = 1;
        @endphp
        @foreach ($dataLampiran as $namaDosen => $dosen)
            @php
                $rowspan = count($dosen);
            @endphp
            <tr>
                <td rowspan="{{ $rowspan }}" style="border:1px solid #000;text-align:left;vertical-align:middle">
                    {{ $no++ }}</td>
                <td rowspan="{{ $rowspan }}" style="border:1px solid #000;text-align:left;vertical-align:middle">
                    {{ mb_convert_encoding($namaDosen, 'HTML-ENTITIES', 'UTF-8') }}</td>
                @php
                    $nTotal = 1;
                @endphp
                @foreach ($dosen as $item)
                    @if ($nTotal != 1)
            <tr>
        @endif
        <td style="border:1px solid #000;text-align:left;vertical-align:middle">{{ $item->matakuliah_kode }}
        </td>
        <td style="border:1px solid #000;text-align:left;vertical-align:middle">
            {{ mb_convert_encoding($item->matakuliah_nama, 'HTML-ENTITIES', 'UTF-8') }}
        </td>
        <td style="border:1px solid #000;text-align:left;vertical-align:middle">{{ $item->matakuliah_sks }}
        </td>
        <td style="border:1px solid #000;text-align:left;vertical-align:middle">{{ $item->prodi_alias }}
        </td>
        <td style="border:1px solid #000;text-align:left;vertical-align:middle">{{ $item->kelas }}
        </td>
        <td style="border:1px solid #000;text-align:left;vertical-align:middle">{{ $item->matakuliah_smt }}
        </td>
        <td style="border:1px solid #000;text-align:left;vertical-align:middle">{{ $item->sks }}
        </td>
        @if ($nTotal == 1)
            <td rowspan="{{ $rowspan }}" style="border:1px solid #000;text-align:left;vertical-align:middle">
                {{ $dataLampiranTotal[$item->dosen_nama] }}
            </td>
        @endif
        @if ($nTotal == 1)
            </tr>
        @endif
        @if ($nTotal != 1)
            </tr>
        @endif
        @php
            $nTotal++;
        @endphp
        @endforeach
        </tr>
        @endforeach

    </tbody>
</table>
