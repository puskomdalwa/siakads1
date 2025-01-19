{{-- <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> --}}
<link rel="stylesheet" href="{{ public_path('css/cetak.css') }}">

@include('header_print')

<div style="font-size:12px;">
    <h3 class="text-center" style="font-size:14px;margin:3px;">ABSENSI MAHASISWA</h3>
    <h3 class="text-center" style="font-size:14px;margin:3px;">TAHUN AKADEMIK {{ $th_akademik->nama }}
        {{ $th_akademik->semester }}</h3><br>

    <table>
        <thead>
            <tr>
                <td>Mata Kuliah</td>
                <td>: {{ @$jadwal->kurikulum_matakuliah->matakuliah->kode }} -
                    {{ @$jadwal->kurikulum_matakuliah->matakuliah->nama }}</td>
                <td>Hari/Waktu</td>
                <td>: {{ @$jadwal->hari->nama }} / {{ @$jadwal->jam_mulai }} s.d
                    {{ @$jadwal->jam_selesai }}</td>
            </tr>

            <tr>
                <td>Dosen</td>
                <td>: {{ @$jadwal->dosen->kode }} - {{ @$jadwal->dosen->nama }}</td>
                <td>Ruang</td>
                <td>: {{ @$jadwal->ruang_kelas->nama }}</td>
            </tr>

            <tr>
                <td>Program Studi</td>
                <td>: {{ @$jadwal->prodi->kode }} - {{ @$jadwal->prodi->nama }}</td>
                <td>Kelas / Kelompok</td>
                <td>: {{ @$jadwal->kelas->nama }} / {{ @$jadwal->kelompok->kode }}</td>
            </tr>
        </thead>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th class="text-center" width="10" rowspan="3">No</th>
                <th class="text-center" width="10%" rowspan="3">NIM</th>
                <th class="text-center" rowspan="3">NAMA MAHASISWA</th>
                <th class="text-center" width="5%" rowspan="3">L/P</th>
                <th class="text-center" colspan="15">PERTEMUAN KE / TANGGAL</th>
            </tr>

            <tr>
                <th class="text-center" width="5%">1</th>
                <th class="text-center" width="5%">2</th>
                <th class="text-center" width="5%">3</th>
                <th class="text-center" width="5%">4</th>
                <th class="text-center" width="5%">5</th>
                <th class="text-center" width="5%">6</th>
                <th class="text-center" width="5%">UTS</th>
                <th class="text-center" width="5%">8</th>
                <th class="text-center" width="5%">9</th>
                <th class="text-center" width="5%">10</th>
                <th class="text-center" width="5%">11</th>
                <th class="text-center" width="5%">12</th>
                <th class="text-center" width="5%">13</th>
                <th class="text-center" width="5%">UAS</th>
                <th class="text-center" width="5%">KET</th>
            </tr>

            <tr>
                <th class="text-center">/</th>
                <th class="text-center">/</th>
                <th class="text-center">/</th>
                <th class="text-center">/</th>
                <th class="text-center">/</th>
                <th class="text-center">/</th>
                <th class="text-center">/</th>
                <th class="text-center">/</th>
                <th class="text-center">/</th>
                <th class="text-center">/</th>
                <th class="text-center">/</th>
                <th class="text-center">/</th>
                <th class="text-center">/</th>
                <th class="text-center">/</th>
                <th class="text-center"></th>
            </tr>
        </thead>

        <tbody>
            @php
                $no = 1;
                $acc_krs = '';
            @endphp

            @foreach ($data as $row)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">{{ $row->nim }}</td>
                    <td>{{ @$row->mahasiswa->nama }}</td>
                    <td class="text-center">{{ @$row->mahasiswa->jk->kode }}</td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<br />

<table>
    <tr>
        <td width="33%" class="text-center">
            <b>Disetujui</b><br />
            Ketua Program Studi<br />
            {{ @$jadwal->prodi->nama }}
            <br /><br /><br />
            <b><u>{{ @$jadwal->prodi->nama_kepala }}</u></b> <br />
            NIDN : {{ @$jadwal->prodi->nidn_kepala }}
        </td>

        <td width="33%" class="text-center">
            {{-- <b>Mengetahui</b><br/>
			Ketua Program Studi<br/><br/><br/><br/>
			<b><u>{{$data->prodi->nama_kepala}}</u></b> <br/>
			NIDN : {{!empty($data->prodi->nidn_kepala)?$data->prodi->nidn_kepala:'-'}} --}}
        </td>

        @php
            $kota = $pt->kota->name;
            $kota = 'Raci';
        @endphp

        <td width="33%" class="text-center">
            <b>{{ $kota }}, {{ format_long_date(date('Y-m-d')) }}</b><br />
            Dosen Pengampu Matakuliah, <br /><br /><br /><br />
            <b><u>{{ @$jadwal->dosen->nama }}</u></b><br />
            NIDN : {{ @$jadwal->dosen->nidn }}
        </td>
    </tr>
</table>

@include('footer_print')
