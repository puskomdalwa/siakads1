{{-- <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> --}}
<link rel="stylesheet" href="{{public_path('css/cetak.css')}}">

@include('header_print')
<div style="font-size:12px;">
	<h5 class="text-center" style="font-size:18px;">DAFTAR DOSEN </h5>
	<h5 class="text-center" style="font-size:18px;">PROGRAM STUDI {{ @strtoupper($nmprodi) }} </h5>
		
	<table class="data">
		<thead>
			<tr>
				<th class="text-center" width="2%">NO</th>
				<th class="text-center" width="3%">KODE</th>
				<th class="text-center" width="10%">NAMA DOSEN</th>
				<th class="text-center" width="1%">L/P</th>
				<th class="text-center" width="10%">TEMPAT, TGL LAHIR</th>
				<th class="text-center" width="8%">E-MAIL</th>
				<th class="text-center" width="4%">HP</th>
				<!-- <th class="text-center" width="7%">PROGRAM STUDI</th> -->
				<th class="text-center" width="4%">STATUS</th>
			</tr>
		</thead>

		<tbody>
			@php $no=1; @endphp		
			@foreach($data as $row)
				<tr>
					<td class="text-center">{{$no++}}</td>
					<td class="text-center">{{$row->kode}}</td>
					<td>{{$row->nama}}</td>
					<td class="text-center">{{$row->jk->kode}}</td>
					<td>{{$row->tempat_lahir}}, {{tgl_str($row->tanggal_lahir)}}</td>
					<td>{{$row->email}}</td>
					<td class="text-center">{{$row->hp}}</td>
					<!-- <td>{{$row->prodi->nama}}</td> -->
					<td class="text-center">{{$row->status->nama}}</td>
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
				<b>{{$pt->kota->name}}, {{format_long_date(date('Y-m-d'))}}</b><br/>
				Yang Melaporkan, <br/><br/><br/><br/>
				<b><u>{{Auth::user()->name}}</u></b>
			</td>
		</tr>
	</table>
</div>
@include('footer_print')
