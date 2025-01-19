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
			<th class="text-center">TANGGAL</th>
			<th class="text-center">UKURAN TOGA</th>
			<th class="text-center">JML SKS</th>
			<th class="text-center">IPK</th>
			<th class="text-center">TGL SK<br/>Yudisium</th>
			<th class="text-center">SK YUDISIUM</th>
			<th class="text-center">NO SERI IJAZAH</th>
			</tr>
		</thead>

		<tbody>
			@php
			$no=1;    
			@endphp

			@foreach($data as $row)
				<tr>
					<td class="text-center"> {{ $no++ }} </td>
					<td class="text-center">{{$row->nim}}</td>
					<td>{{$row->mahasiswa->nama}}</td>
					<td class="text-center">{{$row->mahasiswa->jk->kode}}</td>
					<td>{{@$row->mahasiswa->prodi->nama}}</td>
					<td>{{@$row->mahasiswa->kelas->nama}}</td>
					<td class="text-center">{{@tgl_str($row->tanggal)}}</td>
					<td class="text-center">{{$row->ukuran_toga}}</td>
					<td class="text-center">{{$row->jml_sks}}</td>
					<td class="text-center">{{$row->ipk}}</td>
					<td class="text-center">{{!empty($row->tgl_sk_yudisium)?tgl_str($row->tgl_sk_yudisium):null}}</td>
					<td class="text-center">{{$row->sk_yudisium}}</td>
					<td class="text-center">{{$row->no_seri_ijazah}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div></div>
