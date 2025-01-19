<table>
    <thead>
		<tr>
        <th>Th Akademik</th>
        <th>Program Studi</th>
        <th>Kelas</th>
        <th>Status</th>
        <th>NPM</th>
        <th>NIK</th>
        <th>Nama</th>
        <th>Jenis Kelamin</th>
        <th>Tempat Lahir</th>
        <th>Tanggal Lahir</th>
        <th>Agama</th>
        <th>Alamat</th>
        <th>Kota</th>
        <th>Email</th>
        <th>HP</th>
		</tr>
    </thead>
    
	<tbody>
		@foreach($mahasiswa as $row)
			<tr>
            <td>{{ $row->th_akademik->kode }}</td>
            <td>{{ $row->prodi->nama }}</td>
            <td>{{ $row->kelas->nama }}</td>
            <td>{{ $row->status->nama }}</td>
            <td>{{ $row->nim }}</td>
            <td>{{ $row->nik }}</td>
            <td>{{ $row->nama }}</td>
            <td>{{ $row->jk->nama }}</td>
            <td>{{ $row->tempat_lahir }}</td>
            <td>{{ tgl_str($row->tanggal_lahir) }}</td>
            <td>{{ $row->agama->nama }}</td>
            <td>{{ $row->alamat }}</td>
            <td>{{ $row->kota->name }}</td>
            <td>{{ $row->email }}</td>
            <td>{{ $row->hp }}</td>
			</tr>
		@endforeach
    </tbody>
</table>
