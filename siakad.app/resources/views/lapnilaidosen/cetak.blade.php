{{-- <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> --}}
<link rel="stylesheet" href="{{ public_path('css/cetak.css') }}">

@include('header_print')
<div style="font-size:12px;">
    <h5 class="text-center" style="font-size:18px;">LAPORAN PENGINPUTAN NILAI </h5>
    <h5 class="text-center" style="font-size:18px;">PROGRAM STUDI {{ @strtoupper($nmprodi) }} </h5>

    <table class="data">
        <thead>
            <tr>
                <th class="text-center" width="2%">NO</th>
                <th class="text-center" width="3%">KODE</th>
                <th class="text-center" width="10%">NAMA DOSEN</th>
                <th class="text-center" width="1%">L/P</th>
                <th class="text-center" width="10%">PRODI</th>
                <th class="text-center" width="4%">NILAI</th>
            </tr>
        </thead>

        <tbody>
            @php $no=1; @endphp
            @foreach ($data as $row)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">{{ $row->kode }}</td>
                    <td>{{ $row->nama }}</td>
                    <td class="text-center">{{ $row->jk->kode }}</td>
                    <td class="text-center">{{ $row->prodi->nama }}</td>
                    @php
                        $jadwalKuliah = \App\JadwalKuliah::where('trans_jadwal_kuliah.dosen_id', $row->id)
                            ->where('trans_jadwal_kuliah.th_akademik_id', $th_akademik_id)
                            ->leftJoin(
                                'trans_krs_detail',
                                'trans_jadwal_kuliah.id',
                                '=',
                                'trans_krs_detail.jadwal_kuliah_id',
                            )
                            ->leftJoin('trans_krs', 'trans_krs.id', '=', 'trans_krs_detail.krs_id')
                            ->select([
                                'trans_jadwal_kuliah.id',
                                'trans_jadwal_kuliah.dosen_id',
                                \DB::raw('COUNT(CASE WHEN trans_krs.acc_pa = "Setujui" THEN 1 END) as jumlah_krs'),
                                \DB::raw(
                                    'COUNT(CASE WHEN trans_krs.acc_pa = "Setujui" AND trans_krs_detail.nilai_akhir IS NOT NULL THEN 1 END) as jumlah_krs_bernilai',
                                ),
                            ])
                            ->groupBy(['trans_jadwal_kuliah.id', 'trans_jadwal_kuliah.dosen_id'])
                            ->get();

                        $cek = true;
                        $kosong = count($jadwalKuliah) > 0 ? false : true;
                        foreach ($jadwalKuliah as $key => $jk) {
                            if ($jk->jumlah_krs != $jk->jumlah_krs_bernilai) {
                                $cek = false;
                                break;
                            }
                        }

                    @endphp
                    @if ($cek)
                        @if ($kosong)
                            <td class="text-center" style="background-color: blue">Tidak ada Jadwal</td>
                        @else
                            <td class="text-center" style="background-color: green">Sudah</td>
                        @endif
                    @else
                        <td class="text-center" style="background-color: red">Belum</td>
                    @endif
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
				NIDN : {{!empty($data->mahasiswa->rombel->rombel->dosen_wali->nama->nidn) ?
				$data->mahasiswa->rombel->rombel->dosen_wali->nama->nidn:'-'}} --}}
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
