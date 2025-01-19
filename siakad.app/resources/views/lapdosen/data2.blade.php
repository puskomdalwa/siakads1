<div class="table-responsive">
    @foreach ($data['list_prodi'] as $prodi)
        <div class="table-light">
            <div class="table-header">
                <div class="table-caption text-center"> DOSEN <br>
                PROGRAM STUDI {{strtoupper($prodi->nama)}} ({{$prodi->jenjang}})</div>
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
							<!-- <td class="text-center">{{@$row->status->nama}}</td> -->
						</tr>    
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</div>
