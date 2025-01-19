<table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th class="text-center">No</th>
            <th class="text-center">Dosen Pembimbing</th>
            <th class="text-center">Jabatan</th>
            <th class="text-center">Aksi</th>
        </tr>
    </thead>
	
    <tbody>
        @php
			$no=1;    
        @endphp
    
		@foreach ($data as $row)
        <tr>
            <td scope="row">{{ $no++ }}</td>
            <td>{{ $row->dosen->nama }}</td>
            <td>{{ $row->jabatan }}</td>
            
			<td>
                <a onclick="hapusPembimbing({{ $row->id }})" class="btn btn-danger btn-sm">
                    <i class="fa fa-trash-o"></i>
                </a>
            </td>
        </tr>    
        @endforeach
    </tbody>
</table>
