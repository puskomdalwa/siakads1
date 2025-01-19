<table>
    <thead>
		<tr>
        <th>Th Akademik</th>
        <th>Program Studi</th>
        <th>Kelas</th>
        <th>NPM</th>
        <th>Nama</th>
        <th>Kode MK</th>
        <th>Nama MK</th>
        <th>SKS</th>
        <th>SMT</th>
        <th>Huruf</th>
        <th>Nilai</th>
		</tr>
    </thead>

    <tbody>
		@foreach($krs as $row)
			<tr>
			<td>{{ $row->th_akademik->kode }}</td>
			<td>{{ $row->mahasiswa->prodi->nama }}</td>
			<td>{{ $row->mahasiswa->kelas->nama }}</td>
			<td>{{ $row->nim }}</td>
			<td>{{ $row->nama_mhs }}</td>
			<td>{{ $row->kode_mk }}</td>
			<td>{{ $row->nama_mk }}</td>
			<td>{{ $row->sks_mk }}</td>
			<td>{{ $row->smt_mk }}</td>
			<td>{{ $row->nilai_huruf }}</td>
			<td>{{ $row->nilai_bobot }}</td>
			</tr>
		@endforeach
    </tbody>
</table>
