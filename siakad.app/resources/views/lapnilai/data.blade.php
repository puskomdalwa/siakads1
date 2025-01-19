<div class="table-responsive">
<div class="table-light">
	<div class="table-header">
	<div class="table-caption">DAFTAR MATA KULIAH DAN DOSEN</div>
	</div>

	<table id="serversideTable" class="table table-hover table-bordered table-striped">
		<thead>
			<tr>
				<th class="text-center">NO</th>
				<th class="text-center">KODE</th>
				<th class="text-center">MATA KULIAH</th>
				<th class="text-center">SKS</th>
				<th class="text-center">SMT</th>
				<!-- <th class="text-center">KLP</th> -->
				<th class="text-center">DOSEN</th>
				<th class="text-center">RUANG</th>
				<th class="text-center">HARI</th>
				<th class="text-center">WAKTU</th>
				<th class="text-center">MHS</th>
				<th class="text-center">CETAK</th>
			</tr>
		</thead>
		
		<tbody>
			@php $no=1; @endphp
			@foreach($data as $row)
				@php
					$krs_jml_mhs_acc = getKRSJmlMhsACC($row->id);
					$krs_jml_mhs 	 = getKRSJmlMhs($row->id);
					$jml_mhs		 = $krs_jml_mhs - $krs_jml_mhs_acc;
				@endphp

				<tr>
					<td class="text-center"> {{ $no++ }} </td>
					<td class="text-center"> {{@$row->kurikulum_matakuliah->matakuliah->kode}} </td>
					<td>{{@strtoupper($row->kurikulum_matakuliah->matakuliah->nama)}} </td>
					<td class="text-center"> {{@$row->kurikulum_matakuliah->matakuliah->sks}} </td>
					<td class="text-center"> {{@$row->smt}} </td>
					<!-- <td class="text-center"> {{@$row->kelompok->kode}} </td> -->
					<td>{{@$row->dosen->nama}} </td>
					<td class="text-center"> {{@$row->ruang_kelas->kode}} </td>
					<td class="text-center"> {{@strtoupper($row->hari->nama)}} </td>
					
					<!-- <td class="text-center">{{@$row->jam_mulai}} s.d {{@$row->jam_selesai}}</td> -->
					<td class="text-center"> {{@$row->jam_kul->nama}} </td>
					<td class="text-center"> {{@$jml_mhs}} </td>
					
					<td class="text-center">
					<a href="{{url($redirect.'/'.$row->id.'/cetak')}}" class="btn btn-sm btn-info" target="_blank"> 
					<i class="fa fa-print"></i> </a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div></div>
