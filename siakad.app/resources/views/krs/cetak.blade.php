{{-- <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> --}}
<link rel="stylesheet" href="{{public_path('css/cetak.css')}}">

@include('header_print')

<div style="font-size:12px;">
	<h3 class="text-center" style="font-size:14px;">KARTU RENCANA STUDI (KRS) MAHASISWA</h3>
	<table>
		<thead>
			<tr><td width="13%">NIM</td><td>: {{$krs->nim}}</td>
				<td width="13%">Tahun Akademik</td>
				<td>: {{@$krs->th_akademik->kode}}</td>
			</tr>

			<tr><td>Nama Mahasiswa</td>	<td>: {{@$krs->mahasiswa->nama}}</td>
				<td>Kelas / Semester</td>
				<td>: {{@$krs->kelas->nama}} / {{@$krs->smt}}</td>
			</tr>

			<tr><td>Program Studi</td><td>: {{@$krs->prodi->nama}}</td>
				<td>IP smt Lalu</td>
				<td>: {{@getIP($krs->nim,$krs->th_akademik_id,$krs->smt-1)}}</td>
			</tr>
		</thead>
	</table>

	<table class="data">
		<thead>
			<tr>
				<th class="text-center" width="2%" style="background:#d6d6d6;">NO</th>
				<th class="text-center" width="20%" style="background:#d6d6d6;">MATA KULIAH</th>
				<th class="text-center" width="2%" style="background:#d6d6d6;">SKS</th>
				<th class="text-center" width="5%" style="background:#d6d6d6;">HARI</th>
				<th class="text-center" width="10%" style="background:#d6d6d6;">WAKTU</th>
				<th class="text-center" width="5%" style="background:#d6d6d6;">RUANG</th>
				<th class="text-center" width="30%" style="background:#d6d6d6;">DOSEN</th>
			</tr>
		</thead>
		
		<tbody>
			@php
				$no=1;
				$t_sks = 0;
			@endphp
			
			@foreach($data as $row)
				<tr>
					<td class="text-center">{{$no++}}</td>
					<td>{{@$row->jadwal_kuliah->kurikulum_matakuliah->matakuliah->nama}}</td>
					<td class="text-center">{{@$row->jadwal_kuliah->kurikulum_matakuliah->matakuliah->sks}}</td>
					<td class="text-center">{{@$row->jadwal_kuliah->hari->nama}}</td>
					<td class="text-center">{{@$row->jadwal_kuliah->jamkul->nama}}</td>
					<!-- <td class="text-center">{{@$row->jadwal_kuliah->jam_mulai}}-{{@$row->jadwal_kuliah->jam_selesai}}</td> -->
					<td class="text-center">{{@$row->jadwal_kuliah->ruang_kelas->kode}}</td>
					<td class="text-left">{{@$row->jadwal_kuliah->dosen->nama}}</td>
				</tr>

				@php
					$t_sks +=@$row->jadwal_kuliah->kurikulum_matakuliah->matakuliah->sks;
				@endphp
			@endforeach
		</tbody>
	
		<tfoot>
			<tr>
				<td class="text-center" colspan="2" style="background:#d6d6d6;">Jumlah SKS</td>
				<td class="text-center" style="background:#d6d6d6;">{{$t_sks}}</td>
				<td colspan="4" style="background:#d6d6d6;"></td>
			</tr>
		</tfoot>
	</table>
</div>
<br/>

<table>
	<tr>
		<td width="33%" class="text-center">
			<b>Mengetahui</b><br/>
			Ketua Program Studi<br/><br/><br/><br/>
			<b><u>{{@$prodi->nama_kepala}}</u></b> <br/>
			NIDN : {{@$prodi->nidn_kepala}}
		</td>
    
		<td width="33%" class="text-center">
			<b>Disetujui</b><br/>
			Dosen Pembimbing Akademik<br/><br/><br/><br/>
			<b><u>{{@$krs->mahasiswa->kelompok->perwalian->dosen->nama}}</u></b> <br/>
			NIP : {{!empty($krs->mahasiswa->kelompok->perwalian->dosen->kode)?$krs->mahasiswa->kelompok->perwalian->dosen->kode:'-'}}
		</td>

		<td width="33%" class="text-center">
			  <b>{{$pt->kota->name}}, {{format_long_date(date('Y-m-d'))}}</b><br/>
			  Mahasiswa, <br/><br/><br/><br/>
			  <b><u>{{@$krs->mahasiswa->nama}}</u></b><br/>
			  NIM : {{@$krs->mahasiswa->nim}}
		</td>
	</tr>
</table>

@include('footer_print')
