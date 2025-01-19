<div class="table-responsive">
    @foreach ($data['list_prodi'] as $prodi)
        
    
    <h3 class="text-center">KURIKULUM</h3>
    <h3 class="text-center">PROGRAM STUDI {{ @strtoupper($prodi->prodi->nama) }} ({{ $prodi->prodi->jenjang }}) </h3>

        @foreach ($data['list_thakademik'] as $akademik)
        
        <h4 class="text-center"> TAHUN AKADEMIK {{ @$akademik->th_akademik->kode }} </h4>

            @foreach ($data['rows'][$prodi->prodi_id][$akademik->th_akademik_id] as $row)
                
                <div class="text-center" style="font-size:16px;"> ANGKATAN : 
                    @foreach ($data['angkatan'][$row->id] as $angkatan)
                    <span class="badge badge-success">{{ @$angkatan->th_angkatan->kode }}</span> &nbsp;
                    @endforeach    
                </div>

            <h4 class="text-center">NAMA : {{ @strtoupper($row->nama) }} </h4>
            @foreach ($data['list_smt'] as $smt)
                
                <div class="table-light">
                    <div class="table-header">
                        <div class="table-caption text-center">
                            SEMESTER {{ $smt->smt }}
                        </div>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" width="10px">NO</th>
                                <th class="text-center" width="50px">KODE</th>
                                <th class="text-center" width="300px">NAMA MATA KULIAH</th>
                                <th class="text-center" width="10px">SKS</th>
                                <th class="text-center" width="20px">AKTIF</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $no=1;  
                            $tsks=0;  
                            @endphp
                            @foreach ($data['mk'][$row->id][$smt->smt] as $mk)
                                <tr>
                                    <td class="text-center"> {{ $no++ }} </td>
                                    <td class="text-center"> {{ @strtoupper($mk->matakuliah->kode) }} </td>
                                    <td> {{ @strtoupper($mk->matakuliah->nama) }} </td>
                                    <td class="text-center"> {{ @strtoupper($mk->matakuliah->sks) }} </td>
                                    <td class="text-center"> {{ @strtoupper($mk->matakuliah->aktif) }} </td>
                                </tr>    
                                @php
                                $tsks +=$mk->matakuliah->sks;
                                @endphp
                            @endforeach
                            
                        </tbody>
                    </table>
                    <div class="table-footer text-center">
                        TOTAL SKS : {{ $tsks }}
                    </div>
                </div>
                @endforeach
            @endforeach
        @endforeach
    @endforeach

</div>