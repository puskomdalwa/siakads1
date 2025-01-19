<link rel="stylesheet" href="{{public_path('css/cetak.css')}}">

@include('header_print')

<div style="font-size:12px;">
	<h3 class="text-center" style="font-size:14px;margin:3px;">NILAI MAHASISWA</h3>
	<h3 class="text-center" style="font-size:14px;margin:3px;;">TAHUN AKADEMIK {{$th_akademik->nama}} {{$th_akademik->semester}}</h3>
	<br/>
	
	<table>
		<thead>
			<tr>
				<td width="13%">Mata Kuliah</td>
				<td>: {{$jadwal->kurikulum_matakuliah->matakuliah->kode}} - 
				{{$jadwal->kurikulum_matakuliah->matakuliah->nama}}</td>
				
				<td width="13%">Hari/Waktu</td>
				<td>: {{$jadwal->hari->nama}} / {{$jadwal->jamkul->nama}}</td>
			</tr>

			<tr>
				<td>Dosen</td><td>: {{$jadwal->dosen->kode}} - {{$jadwal->dosen->nama}}</td>
				<td>Ruang</td><td>: {{$jadwal->ruang_kelas->nama}}</td>
			</tr>

			<tr>
				<td>Program Studi</td><td>: {{$jadwal->prodi->kode}} - {{$jadwal->prodi->nama}}</td>
				<td>Kelas / Kelompok</td><td>: {{$jadwal->kelas->nama}} / {{$jadwal->kelompok->kode}}</td>
			</tr>
		</thead>
	</table>

	<table class="data">
		<thead>
			<tr>
				<th class="text-center" width="10" rowspan="2">NO</th>
				<th class="text-center" width="10%" rowspan="2">NIM</th>
				<th class="text-center" rowspan="2">NAMA MAHASISWA</th>
				<th class="text-center" width="5%" rowspan="2">L/P</th>
				<th class="text-center" colspan="{{$komponen_nilai->count()+3}}">NILAI</th>
			</tr>
		
			<tr>
				@foreach($komponen_nilai as $kn)
					<th class="text-center" width="5%">{{$kn->nama}}<br/>{{$kn->bobot}}%</th>
				@endforeach
				
				<th class="text-center" width="5%">AKHIR</th>
				<th class="text-center" width="5%">HURUF</th>
				<th class="text-center" width="5%">BOBOT</th>
			</tr>
		</thead>
 
		<tbody>
			@php $no=1; @endphp
			@foreach($data as $row)
				@php $sks = @getSKS($row->th_akademik_id,$row->nim); @endphp

				<tr>
					<td class="text-center">{{$no++}}</td>
					<td class="text-center">{{$row->nim}}</td>
					<td>{{@$row->mahasiswa->nama}}</td>
					<td class="text-center">{{@$row->mahasiswa->jk->kode}}</td>

					@foreach($komponen_nilai as $kn)
						@php $krs_detail_nilai = @getKRSDetailNilai($row->id,$kn->id); @endphp
						<td class="text-center">{{$krs_detail_nilai}}</td>
					@endforeach
				
					<td class="text-center">{{$row->nilai_akhir}}</td>
					<td class="text-center">{{$row->nilai_huruf}}</td>
					<td class="text-center">{{$row->nilai_bobot}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	<br/>

	<table>
		<tr>
			<td width="33%" class="text-center">
				<b>Disetujui</b><br/>
				Kepala Program Studi<br/>
				{{$jadwal->prodi->nama}}
				<br/><br/><br/>
				<b><u>{{$jadwal->prodi->nama_kepala}}</u></b> <br/>
				NIDN : {{$jadwal->prodi->nidn_kepala}}
			</td>
		
			<td width="33%" class="text-center">
				{{-- <b>Mengetahui</b><br/>
				Ketua Program Studi<br/><br/><br/><br/>
				<b><u>{{$data->prodi->nama_kepala}}</u></b> <br/>
				NIDN : {{!empty($data->prodi->nidn_kepala)?$data->prodi->nidn_kepala:'-'}} --}}
			</td>

			<td width="33%" class="text-center">
				<b>{{$pt->kota->name}}, {{format_long_date(date('Y-m-d'))}}</b><br/>
				Dosen, <br/><br/><br/><br/>
				<b><u>{{$jadwal->dosen->nama}}</u></b><br/>
				NIDN : {{$jadwal->dosen->nidn}}
			</td>
		</tr>
	</table>
	
	@include('footer_print')
