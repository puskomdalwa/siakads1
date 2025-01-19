<div class="table-responsive">
    @foreach ($data['list_prodi'] as $prodi)
    <h3 class="text-center">PERWALIAN</h3>
    <h3 class="text-center">PROGRAM STUDI {{ @strtoupper($prodi->prodi->nama) }} ({{ @$prodi->prodi->jenjang }}) </h3>
        @foreach ($data['list_kelas'] as $kelas)
            <h4 class="text-center">KELAS {{ @strtoupper($kelas->kelas->nama)  }} </h4>
            @foreach ($data['list_kelompok'] as $kelompok)
                @foreach ($data['rows'][$prodi->prodi_id][$kelas->kelas_id][$kelompok->kelompok_id] as $row)
                    
                <div class="table-light">
                    <div class="table-header">
                        <div class="table-caption text-center">
                            TAHUN ANGAKATAN {{ @$row->th_akademik->kode }}<br>
                            KELOMPOK : {{ @strtoupper($row->kelompok->nama) }} <br>
                            DOSEN WALI : {{ @strtoupper($row->dosen->nama) }}  
                            {{-- <br> ID PERWALIAN {{ $row->id }} --}}
                        </div>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" width="10px">NO</th>
                                <th class="text-center" width="50px">NIM</th>
                                <th class="text-center" width="300px">NAMA MAHASISWA</th>
                                <th class="text-center" width="10px">L/P</th>
                                <th class="text-center" width="80px">E-MAIL</th>
                                <th class="text-center" width="80px">HP</th>
                                <th class="text-center" width="50px">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $no=1;    
                            @endphp
                            @foreach($data['detail'][$row->id] as $mhs)
                            
                            <tr>
                                <td class="text-center"> {{ $no++ }} </td>
                                <td class="text-center"> {{ $mhs->nim }} </td>
                                <td> {{ @strtoupper($mhs->mahasiswa->nama) }} </td>
                                <td class="text-center"> {{ @strtoupper($mhs->mahasiswa->jk->kode) }} </td>
                                <td> {{ @strtolower($mhs->mahasiswa->email) }} </td>
                                <td> {{ @strtoupper($mhs->mahasiswa->hp) }} </td>
                                <td class="text-center"> {{ @strtoupper($mhs->mahasiswa->status->nama) }} </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- <div class="table-footer">
                        Footer
                    </div> --}}
                </div>
                @endforeach
            @endforeach
        @endforeach
    @endforeach
</div>