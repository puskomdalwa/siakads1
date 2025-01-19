<div class="table-responsive">
    @foreach ($data['list_prodi'] as $prodi)
            <h3 class="text-center">MATA KULIAH</h3>
            <h3 class="text-center">PROGRAM STUDI {{ strtoupper($prodi->nama) }} ({{ $prodi->jenjang }})</h3>
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
                            <th class="text-center" width="120px">KODE</th>
                            <th class="text-center" width="300px">NAMA MATA KULIAH</th>
                            <th class="text-center" width="10px">SKS</th>
                            <th class="text-center" width="50px">AKTIF</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $no=1;
                        $tsks = 0;    
                        @endphp
                        @foreach ($data['rows'][$prodi->id][$smt->smt] as $row)
                        <tr>
                            <td class="text-center"> {{ $no++ }} </td>
                            <td class="text-center"> {{ $row->kode }} </td>
                            <td> {{ strtoupper($row->nama) }} </td>
                            <td class="text-center"> {{ @$row->sks }} </td>
                            <td class="text-center">{{@$row->aktif}}</td>
                        </tr> 
                        @php
                        $tsks +=$row->sks;    
                        @endphp   
                        @endforeach
                    </tbody>
                </table>
                <div class="table-footer text-center">
                    Total SKS {{ $tsks }}
                </div>
            </div>
        @endforeach    
    @endforeach
</div>