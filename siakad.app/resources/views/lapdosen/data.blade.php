
	<div class="table-responsive">
		<table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th class="text-center">No</th>
					<th class="text-center col-md-1">Kode</th>
					<th class="text-center">Nama Dosen</th>
					<th class="text-center">L/P</th>
					<th class="text-center">Tempat, Tgl Lahir</th>
					<th class="text-center">Email</th>
					<th class="text-center">HP</th>
					<th class="text-center">Program Studi</th>
					<th class="text-center">Status</th>
				</tr>
			</thead>

			<tbody>
				@php $no=1; @endphp
				@foreach($data as $row)
					<tr>
						<td class="text-center"> {{ $no++ }} </td>
						<td class="text-center">{{$row->kode}}</td>
						<td>{{$row->nama}}</td>
						<td class="text-center">{{$row->jk->kode}}</td>
						<td>{{$row->tempat_lahir}}, {{@tgl_str($row->tanggal_lahir)}}</td>
						<td>{{$row->email}}</td>
						<td class="text-center">{{$row->hp}}</td>
						<td>{{$row->prodi->nama}}</td>
						<td class="text-center">{{$row->status->nama}}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
   </div>
