<!-- 
<h3 class="text-center"> Mahasiswa Aktif Belum Isi KRS. Tahun Akademik : {{ $th_akademik_aktif }} Ganjil</h3>	
-->

<?php
$taka = substr($th_akademik_aktif,0,4); 
if(substr($th_akademik_aktif,4,1)==1){?>
	<h3 class="text-center"> Mahasiswa Aktif Belum Isi KRS. Tahun Akademik : {{ $taka }} (Ganjil) </h3><?php
}else{?>
	<h3 class="text-center"> Mahasiswa Aktif Belum Isi KRS. Tahun Akademik : {{ $taka }} (Genap) </h3><?php
} 
?>

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
				<th class="text-center">TAHUN ANGKATAN</th>
			</tr>
		</thead>
		
		<tbody>
			@php $no = 1; @endphp 			
			@foreach($data as $row)
				<tr>
				<td class="text-center"> {{ $no++ }} </td>
				<td class="text-center">{{$row->nim}}</td>
				<td>{{$row->nama}}</td>
				<td class="text-center">{{@$row->jk->kode}}</td>
				<td>{{@$row->prodi->nama}}</td>
				<td>{{@$row->kelas->nama}}</td>
				<td class="text-center">{{@$row->kelompok->perwalian->kelompok->kode}}</td>
				<td class="text-center"> {{ @$row->th_akademik->kode }} </td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
