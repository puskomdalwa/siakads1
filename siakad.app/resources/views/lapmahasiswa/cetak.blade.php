{{-- <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> --}}
<link rel="stylesheet" href="{{ public_path('css/cetak.css') }}">

@include('header_print')
<div style="font-size:12px;">
    <h3 class="text-center" style="font-size:14px;">LAPORAN MAHASISWA</h3>
    <table class="data">
        <thead>
            <tr>
                <th class="text-center" width="10">No</th>
                <th class="text-center" width="50">NIM</th>
                <th class="text-center" width="120">Nama</th>
                <th class="text-center" width="5">L/P</th>
                <th class="text-center" width="50">Program Studi</th>
                <th class="text-center" width="10">Kelas</th>
                <th class="text-center" width="10">Kelompok</th>
                <th class="text-center" width="30">Status</th>
            </tr>
        </thead>

        <tbody>
            @php $no=1; @endphp
            @foreach ($data as $row)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">{{ @$row->nim }}</td>
                    <td>{{ @$row->nama }}</td>
                    <td class="text-center">{{ @$row->jk->kode }}</td>
                    <td class="text-center">{{ @$row->prodi->nama }}</td>
                    <td class="text-center">{{ @$row->kelas->nama }}</td>
                    <td class="text-center">{{ @$row->kelompok->perwalian->kelompok->kode }}</td>
                    <td class="text-center">{{ @$row->status->nama }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br />

    <table>
        <tr>
            <td width="33%" class="text-center">
                {{-- <b>Disetujui/Disahkan</b><br/>
				Pembimbing Akademik<br/><br/><br/><br/>
				<b><u>{{$data->mahasiswa->rombel->rombel->dosen_wali->nama}}</u></b> <br/>
				NIDN : {{!empty($data->mahasiswa->rombel->rombel->dosen_wali->nama->nidn)?$data->mahasiswa->rombel->rombel->dosen_wali->nama->nidn:'-'}} --}}
            </td>

            <td width="33%" class="text-center">
                {{-- <b>Mengetahui</b><br/>
				Ketua Program Studi<br/><br/><br/><br/>
				<b><u>{{$data->prodi->nama_kepala}}</u></b> <br/>
				NIDN : {{!empty($data->prodi->nidn_kepala)?$data->prodi->nidn_kepala:'-'}} --}}
            </td>

            <td width="33%" class="text-center">
                <b>{{ $pt->kota->name }}, {{ format_long_date(date('Y-m-d')) }}</b><br />
                Yang Melaporkan, <br /><br /><br /><br />
                <b><u>{{ Auth::user()->name }}</u></b>
            </td>
        </tr>
    </table>
</div>

@include('footer_print')
