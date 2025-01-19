<div class="table-responsive">
	<table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="text-center">NO</th>
				<th class="text-center col-md-1">NIM</th>
				<th class="text-center">NAMA MAHASISWA</th>
				<th class="text-center">L/P</th>
				<th class="text-center">PROGRAM STUDI</th>
				<th class="text-center">KELAS</th>
				<th class="text-center">KELOMPOK</th>
				<th class="text-center">TANGGAL</th>
				<th class="text-center">JUMLAH SKS</th>
				<th class="text-center">CETAK</th>
			</tr>
		</thead>

		<tbody>
			@php $no=1; @endphp			
			@foreach($data as $row)
				@php $sks = @getSKS($row->th_akademik_id,$row->nim); @endphp
				
				<tr>
					<td class="text-center"> {{ $no++ }} </td>
					<td class="text-center">{{$row->nim}}</td>
					<td>{{@$row->mahasiswa->nama}}</td>
					<td class="text-center">{{@$row->mahasiswa->jk->kode}}</td>
					<td>{{@$row->mahasiswa->prodi->nama}}</td>
					<td>{{@$row->mahasiswa->kelas->nama}}</td>
					<td class="text-center">{{@$row->mahasiswa->kelompok->perwalian->kelompok->kode}}</td>
					<td class="text-center">{{@tgl_str($row->tanggal)}}</td>
					<td class="text-center">{{$sks}}</td>
					
					<td class="text-center">
						<a href="{{url('lapkrs/'.$row->id.'/cetakKRS')}}" class="btn btn-xs btn-info" target="_blank"> 
						<i class="fa fa-print"></i></a>
					</td>
				</tr>
				
			@endforeach
		</tbody>
	</table>
</div>
