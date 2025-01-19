<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="{{public_path('css/cetak.css')}}">

@include('header_print')

<div style="font-size:12px;">
	<h3 class="text-center" style="font-size:14px;margin:3px;">
	LAPORAN KHS MAHASISWA</h3>
	<h3 class="text-center" style="font-size:14px;margin:3px;;">
	TAHUN AKADEMIK {{$th_akademik->nama}} {{$th_akademik->semester}}</h3>
	<br/>

	<table class="data">
		<thead>
			<tr>
				<th class="text-center" width="10">NO</th>
				<th class="text-center" width="70px;">NIM</th>
				<th class="text-center">NAMA MAHASISWA</th>
				<th class="text-center" width="10px;">L/P</th>
				<th class="text-center">PRODI</th>
				<th class="text-center">KELAS</th>
				<th class="text-center" width="70px;">KELOMPOK</th>
				<th class="text-center" width="70px;">TANGGAL</th>
				<th class="text-center" width="10px;">SKS</th>
			</tr>
		</thead>
		
		<tbody>
			@php $no=1; @endphp
			@foreach($data as $row)
				@php $sks = getSKS($row->th_akademik_id,$row->nim); @endphp		
				<tr>
					<td class="text-center">{{$no++}}</td>
					<td class="text-center">{{$row->nim}}</td>
					<td>{{$row->mahasiswa->nama}}</td>
					<td class="text-center">{{@$row->mahasiswa->jk->kode}}</td>
					<td>{{@$row->mahasiswa->prodi->nama}}</td>
					<td>{{@$row->mahasiswa->kelas->nama}}</td>
					<td class="text-center">{{@$row->mahasiswa->kelompok->perwalian->kelompok->kode}}</td>
					<td class="text-center">{{tgl_str($row->tanggal)}}</td>
					<td class="text-center">{{$sks}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	<br/>

	<table>
		<tr>
			<td width="33%" class="text-center">
				{{-- <b>Disetujui/Disahkan</b><br/>
				Pembimbing Akademik<br/><br/><br/><br/>
				<b><u>{{$data->mahasiswa->rombel->rombel->dosen_wali->nama}}</u></b> <br/>
				NIDN : {{!empty($data->mahasiswa->rombel->rombel->dosen_wali->nama->nidn) ? 
				$data->mahasiswa->rombel->rombel->dosen_wali->nama->nidn:'-'}} --}}
			</td>
			
			<td width="33%" class="text-center">
				{{-- <b>Mengetahui</b><br/>
				Ketua Program Studi<br/><br/><br/><br/>
				<b><u>{{$data->prodi->nama_kepala}}</u></b> <br/>
				NIDN : {{!empty($data->prodi->nidn_kepala)?$data->prodi->nidn_kepala:'-'}} --}}
			</td>
			
			<td width="33%" class="text-center">
				<!-- <b>{{$pt->kota->name}}, {{format_long_date(date('Y-m-d'))}}</b><br/> -->
				<b>Raci Bangil, {{format_long_date(date('Y-m-d'))}}</b><br/>
				Yang Melaporkan, <br/><br/><br/><br/>
				<b><u>{{Auth::user()->name}}</u></b>
			</td>
		</tr>
	</table>
</div>

@include('footer_print')
