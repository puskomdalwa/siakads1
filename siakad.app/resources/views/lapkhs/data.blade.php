<div class="table-responsive">
<div class="table-light">
	<div class="table-header">
	<div class="table-caption">DAFTAR MAHASISWA</div></div>

	<table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="text-center">NO</th>
				<th class="text-center col-md-1">NIM</th>
				<th class="text-center">NAMA MAHASISWA</th>
				<th class="text-center">L/P</th>
				<th class="text-center">PRODI</th>
				<th class="text-center">KELAS</th>
				<th class="text-center">KELOMPOK</th>
				<th class="text-center">TANGGAL</th>
				<th class="text-center">SKS</th>
				<th class="text-center">CETAK</th>
			</tr>
		</thead>
 
		<tbody>
			@php $no=1; @endphp		
			@foreach($data as $row)
				@php						
					$sks = getSKS($row->th_akademik_id, $row->nim);			
					$acc = App\KRS::where('th_akademik_id', $row->th_akademik_id)
					->where('nim',$row->nim)
					->where('acc_pa','Setujui')
					->first();
				@endphp				
			
				<tr>
					<td class="text-center"> {{$no++}} </td>		
					<td class="text-center"> {{$row->nim}}</td>	
					<td class="text-left">	 {{$row->mahasiswa->nama}}</td>
					<td class="text-center"> {{$row->mahasiswa->jk->kode}}</td>
					<td class="text-center"> {{$row->mahasiswa->prodi->alias}}</td>
					<td class="text-center"> {{$row->mahasiswa->kelas->nama}}</td>
					<td class="text-center"> {{@$row->mahasiswa->kelompok->perwalian->kelompok->kode}}</td>					
					<td class="text-center"> {{tgl_str($row->tanggal)}}</td>									
					<td class="text-center"> {{$sks}}</td>
					
					<td class="text-center">
						@if($acc)
							<a href="{{url('lapkhs/'.$row->id.'/cetakKHS')}}" class="btn btn-xs btn-info" target="_blank"> 
							<i class="fa fa-print"></i> </a>
						@else
							<i class="fa fa-times text-danger"></i>
						@endif
					</td>					
				</tr>				
			@endforeach
		</tbody>
	</table>
	
	<div class="table-footer text-danger">
	Keterangan : Tombol Printer tidak tampil KRS belum di ACC Dosen Wali. </div>	
</div></div>
