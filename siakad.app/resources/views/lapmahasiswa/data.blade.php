<div class="table-responsive">
	<table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="text-center">No</th>
				<th class="text-center col-md-1">NIM</th>
				<th class="text-center">Nama Mahasiswa</th>
				<th class="text-center">L/P</th>
				<th class="text-center">Prodi</th>
				<th class="text-center">Kelas</th>
				<th class="text-center">Kelompok</th>
				<th class="text-center">Email</th>
				<th class="text-center">Hp</th>
				<!-- <th class="text-center">Status</th> -->
			</tr>
		</thead>

		<tbody>
			@php $no=1; @endphp
			@foreach($data as $row)
				<tr>
					<td class="text-center"> {{ $no++ }} </td>
					<td class="text-center">{{$row->nim}}</td>
					<td>{{$row->nama}}</td>
					<td class="text-center">{{$row->jk->kode}}</td>
					<td>{{@$row->prodi->nama}}</td>
					<td>{{@$row->kelas->nama}}</td>
					<td class="text-center">{{@$row->kelompok->perwalian->kelompok->kode}}</td>
					<td>{{@$row->email}}</td>
					<td class="text-center">{{@$row->hp}}</td>
					<!-- <td class="text-center">{{@$row->status->nama}}</td> -->
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
