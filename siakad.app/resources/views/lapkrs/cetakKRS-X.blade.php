<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="{{public_path('css/cetak.css')}}">

@include('header_print')

<div style="font-size:12px;">
	<h3 class="text-center" style="font-size:14px;margin:3px;">
	KARTU HASIL STUDI</h3>
	
	<!-- TAHUN AKADEMIK {{$th_akademik->nama}} {{$th_akademik->semester}} -->
	<h3 class="text-center" style="font-size:14px;margin:3px;;">
	TAHUN AKADEMIK {{$th_akademik->nama}}</h3>
	<br/>
	
	<table>
		<thead>
			<tr>
				<td width="13%">NIM</td>
				<td>: {{$krs->nim}}</td>
				<td width="13%">Tahun Akademik</td>
				<td>: {{ substr($krs->th_akademik->kode,0,4) }} / {{ $th_akademik->semester }} </td>
			</tr>

			<tr>
				<td>Nama Mahasiswa</td>
				<td>: {{$krs->mahasiswa->nama}}</td>
				<td>Kelas / Semester</td>
				<td>: {{$krs->kelas->nama}} / {{$krs->smt}}</td>
			</tr>

			<tr>
				<td>Program Studi</td>
				<td>: {{$krs->prodi->nama}}</td>
				<td>IP smt Lalu</td>
				<td>: {{getIP($krs->nim,$krs->th_akademik_id,$krs->smt-1)}}</td>
			</tr>
		</thead>
	</table>

	<table class="data">
		<thead>
			<tr>
				<th class="text-center" width="2%" style="background:#d6d6d6;">NO</th>
				<th class="text-center" width="5%" style="background:#d6d6d6;">KODE</th>
				<th class="text-center" width="25%" style="background:#d6d6d6;">MATA KULIAH</th>
				<th class="text-center" width="2%" style="background:#d6d6d6;">SKS</th>
			</tr>
		</thead>

		<tbody>
			@php
				$no=1;
				$t_sks = 0;
			@endphp

			@foreach($data as $row)
				@php $nilai = $row->sks_mk * $row->nilai_bobot; @endphp
				<tr>
					<td class="text-center">{{$no++}}</td>
					<td class="text-center">{{$row->kode_mk}}</td>
					<td>{{$row->nama_mk}}</td>
					<td class="text-center">{{$row->sks_mk}}</td>
				</tr>

				@php $t_sks +=$row->sks_mk; @endphp	
				
			@endforeach
		</tbody>
		
		<tfoot>
			<tr>
				<td class="text-center" colspan="3" style="background:#d6d6d6;">Jumlah SKS</td>
				<td class="text-center" style="background:#d6d6d6;">{{$t_sks}}</td>
			</tr>
		</tfoot>
	</table>
</div>
<br/>

<table>
	<tr>
		<td width="25%" class="text-center">
			<b>Mengetahui</b><br/>
			Ketua Program Studi<br/><br/><br/><br/>
			<b><u>{{@$prodi->nama_kepala}}</u></b> <br/>
			NIDN : {{@$prodi->nidn_kepala}}
		</td>
		
		<td width="50%" class="text-center">
			<b>Disetujui</b><br/>
			Dosen Pembimbing Akademik<br/><br/><br/><br/>
			<b><u>{{@$krs->mahasiswa->kelompok->perwalian->dosen->nama}}</u></b> <br/>
			NIP : {{!empty($krs->mahasiswa->kelompok->perwalian->dosen->kode) ? 
			$krs->mahasiswa->kelompok->perwalian->dosen->kode:'-'}}
		</td>

		@php
			$kota = $pt->kota->name;
			$kota = 'Raci';
		@endphp
		
		<td width="25%" class="text-center">
			<b>{{$kota}}, {{format_long_date(date('Y-m-d'))}}</b><br/>
			Mahasiswa, <br/><br/><br/><br/>
			<b><u>{{@$krs->mahasiswa->nama}}</u></b><br/>
			NIM : {{@$krs->mahasiswa->nim}}
		</td>
	</tr>
</table>

@include('footer_print')
