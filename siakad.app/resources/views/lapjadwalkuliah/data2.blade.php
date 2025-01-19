<div class="table-responsive">
    <h5 class="text-center">JADWAL KULIAH SEMESTER {{ strtoupper($th_akademik->semester) }}<br></h5>
    <h5 class="text-center">TAHUN AKADEMIK {{ $th_akademik->nama }}</h5>
    <h5 class="text-center">PROGRAM STUDI {{ strtoupper($dt_prodi->nama) }} ({{ $dt_prodi->jenjang }}) </h5>

    @foreach ($list_smt as $smt)
        @php
            $cek = [];
        @endphp
        <div class="table-light">
            <div class="table-header">
                <div class="table-caption text-center"> SEMESTER {{ $smt->smt }}
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" width="10px">NO</th>
                        <th class="text-center" width="80px">KODE MK</th>
                        <th class="text-center" width="370px">MATA KULIAH</th>
                        <th class="text-center" width="10px">SKS</th>
                        <th class="text-center" width="280px">DOSEN</th>
                        <th class="text-center" width="80px">KELAS</th>
                        <th class="text-center" width="190px">RUANG</th>
                        <th class="text-center" width="70px">HARI</th>
                        <th class="text-center" width="100px">WAKTU</th>
                        <th class="text-center" width="10px">MHS</th>
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
                        @php
                            $dataCek = "$jadwal->hari_id;$jadwal->jam_kuliah_id;$jadwal->ruang_kelas_id";
                            if (in_array($dataCek, $cek)) {
                                $jadwal->cek = 'bg-primary';
                            } else {
                                $cek[] = $dataCek;
                            }
                            $jamkul = $jadwal->jamkul->nama;
                            $mhs = App\KRSDetail::select('trans_krs_detail.nim')
                                ->join('trans_krs', 'trans_krs.id', '=', 'trans_krs_detail.krs_id')
                                ->where('trans_krs_detail.jadwal_kuliah_id', $jadwal->id)
                                ->where('trans_krs.acc_pa', 'Setujui')
                                ->count();
                        @endphp

                        <tr>
                            <td class="text-center {{ $jadwal->cek }}"> {{ $no++ }} </td>
                            <td class="text-center"> {{ @strtoupper($jadwal->kurikulum_matakuliah->matakuliah->kode) }}
                            </td>
                            <td>{{ @strtoupper($jadwal->kurikulum_matakuliah->matakuliah->nama) }}</td>
                            <td class="text-center"> {{ @$jadwal->kurikulum_matakuliah->matakuliah->sks }} </td>
                            <td>{{ @$jadwal->dosen->nama }}</td>
                            <td class="text-center"> {{ @strtoupper($jadwal->kelas->nama) }} </td>
                            <td class="text-center"> {{ @strtoupper($jadwal->ruang_kelas->nama) }} </td>
                            <td class="text-center"> {{ @strtoupper($jadwal->hari->nama) }} </td>
                            <td class="text-center"> {{ @$jamkul }} </td>
                            <td class="text-center"> {{ @$mhs }}</td>
                        </tr>

                        @php $tsks +=@$jadwal->kurikulum_matakuliah->matakuliah->sks; @endphp
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td class="text-center" colspan="3">JUMLAH</td>
                        <td class="text-center"> {{ $tsks }} </td>
                        <td colspan="6"></td>
                    </tr>
                </tfoot>
            </table>

            <div class="table-footer text-center"> JUMLAH SKS - SEMESTER {{ $smt->smt . ' ' . $tsks }}</div>
        </div>
    @endforeach
</div>
