<div class="table-responsive">
    @foreach ($data['list_prodi'] as $prodi)
        <div class="table-light">
            <div class="table-header">
                <div class="table-caption text-center"> DOSEN <br>
                    PROGRAM STUDI {{ strtoupper($prodi->nama) }} ({{ $prodi->jenjang }})</div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" width="10px">NO</th>
                        <th class="text-center" width="11px">KODE/NIDN</th>
                        <!-- <th class="text-center" width="11px">NIDN</th> -->
                        <th class="text-center" width="240px">NAMA DOSEN</th>
                        <th class="text-center" width="10px">L/P</th>
                        <th class="text-center" width="220px">TEMPAT/TGL LAHIR</th>
                        <th class="text-center" width="50px">EMAIL</th>
                        <th class="text-center" width="30px">HP</th>
                        <th class="text-center" width="30px">NILAI</th>
                        <!-- <th class="text-center" width="50px">STATUS</th> -->
                    </tr>
                </thead>

                <tbody>
                    @php $no=1; @endphp
                    @foreach ($data['rows'][$prodi->id] as $row)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td class="text-center">{{ $row->kode }}</td>
                            <!-- <td class="text-center">{{ $row->nidn }}</td> -->
                            <td> {{ $row->nama }} </td>
                            <td class="text-center"> {{ @$row->jk->kode }} </td>
                            <td> {{ $row->tempat_lahir }}, {{ @tgl_str($row->tanggal_lahir) }} </td>
                            <td> {{ $row->email }} </td>
                            <td class="text-center"> {{ $row->hp }} </td>
                            @php
                                $jadwalKuliah = \App\JadwalKuliah::where('trans_jadwal_kuliah.dosen_id', $row->id)
                                    ->where('trans_jadwal_kuliah.th_akademik_id', $data['th_akademik_id'])
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
                                        \DB::raw(
                                            'COUNT(CASE WHEN trans_krs.acc_pa = "Setujui" THEN 1 END) as jumlah_krs',
                                        ),
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
                                    <td class="text-center"> <span class="badge badge-info">Tidak ada jadwal</span> </td>
                                @else
                                    <td class="text-center"> <span class="badge badge-success">Sudah</span> </td>
                                @endif
                            @else
                                <td class="text-center"> <span class="badge badge-danger">Belum</span> </td>
                            @endif
                            <!-- <td class="text-center">{{ @$row->status->nama }}</td> -->
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</div>
