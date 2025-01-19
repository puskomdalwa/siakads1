{{-- <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> --}}
<link rel="stylesheet" href="{{ public_path('css/cetak.css') }}">

@include('header_print')

<div style="font-size:12px;">
    <h3 class="text-center" style="font-size:14px;margin:3px;">
        NILAI KOMPREHENSIF</h3>

    <!-- TAHUN AKADEMIK {{ $th_akademik->nama }} {{ $th_akademik->semester }} -->
    <h3 class="text-center" style="font-size:14px;margin:3px;;">
        TAHUN AKADEMIK {{ $th_akademik->nama }}</h3>
    <br />

    <table>
        <thead>
            <tr>
                <td width="14%">NIM</td>
                <td>: {{ $mahasiswa->nim }}</td>
                <td></td>
                <td></td>
                <td width="13%">Tahun Akademik</td>
                <td>: {{ substr($th_akademik->kode, 0, 4) }} / {{ $th_akademik->semester }} </td>
            </tr>

            <tr>
                <td>Nama Mahasiswa</td>
                <td>: {{ $mahasiswa->nama }}</td>
                <td></td>
                <td></td>
                <td>Kelas / Semester</td>
                <td>: {{ $mahasiswa->kelas->nama }} / {{ $smt }}</td>
            </tr>

            <tr>
                <td>Program Studi</td>
                <td>: {{ $prodi->nama }}</td>
            </tr>
        </thead>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th class="text-center" style="background:#d6d6d6;width:20px">NO</th>
                <th class="text-center" style="background:#d6d6d6;">DOSEN PENGUJI</th>
                <th class="text-center" style="background:#d6d6d6;">NIlAI</th>
            </tr>
        </thead>

        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($nilaiKompre as $item)
                <tr>
                    <td class="text-center" style="width:20px">{{ $no++ }}</td>
                    <td class="text-center" style="">{{ $item->kompreDosen->dosen->nama }}</td>
                    <td class="text-center" style="">{{ $item->nilai }}</td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td colspan="2" class="text-center" style="background:#d6d6d6">NILAI DI KHS</td>
                <td class="text-center" style="background:#d6d6d6">{{ (float) $khsKompre->nilai_akhir }}</td>
            </tr>
        </tfoot>
    </table>
</div>
<br />


<table>
    <tr>
        <td width="25%" class="text-center">
            <b>Mengetahui</b><br />
            Ketua Program Studi<br /><br /><br /><br />
            <b><u>{{ @$prodi->nama_kepala }}</u></b> <br />
            NIDN : {{ @$prodi->nidn_kepala }}
        </td>

        <td width="50%" class="text-center">
        </td>

        @php
            $kota = $pt->kota->name;
            $kota = 'Raci';
        @endphp

        <td width="25%" class="text-center">
            <b>{{ $kota }}, {{ format_long_date(date('Y-m-d')) }}</b><br />
            Mahasiswa, <br /><br /><br /><br />
            <b><u>{{ @$mahasiswa->nama }}</u></b><br />
            NIM : {{ @$mahasiswa->nim }}
        </td>
    </tr>
</table>
@include('footer_print')
