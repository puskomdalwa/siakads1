<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="{{public_path('css/cetak.css')}}">

<table class="judul">
	<tr>
		<th class="text-center" width="15%">
		<img src="{{public_path('img/'.$pt->logo)}}" width="80" alt="">
		</th>
	
		<th class="text-left" width="65%"><span class="judul_besar">LAPORAN YUDISIUM MAHASISWA</span><br/>
		TAHUN AKADEMIK {{$th_akademik->nama}} {{$th_akademik->semester}}<br/>
		{{$pt->nama}}<br/>
		<span class="alamat">Alamat : {{$pt->alamat}} - {{$pt->kota->name}}. Telp:{{$pt->telp}} Email : {{$pt->email}}</span>
		</th>
	</tr>
</table>
<hr><br/>

<table class="data">
	<thead>
		<tr>
        <th class="text-center" width="2%">No</th>
        <th class="text-center" width="4%">NIM</th>
        <th class="text-center" width="20%">Nama</th>
        <th class="text-center" width="2%">L/P</th>
        <th class="text-center" width="15%">Program Studi</th>
        <th class="text-center" width="5%">Kelas</th>
        <th class="text-center" width="5%">Tanggal</th>
        <th class="text-center" width="5%">Ukuran Toga</th>
        <th class="text-center" width="5%">Jml SKS</th>
        <th class="text-center" width="5%">IPK</th>
        <th class="text-center" width="5%">Tgl SK<br/>Yudisium</th>
        <th class="text-center" width="10%">SK Yudisium</th>
        <th class="text-center" width="10%">No Seri Ijazah</th>
		</tr>
	</thead>

	<tbody>
		@php
		$no=1;
		@endphp
		
		@foreach($data as $row)
			<tr>
			<td class="text-center">{{$no++}}</td>
			<td class="text-center">{{$row->nim}}</td>
			<td>{{$row->mahasiswa->nama}}</td>
			<td class="text-center">{{@$row->mahasiswa->jk->kode}}</td>
			<td>{{@$row->mahasiswa->prodi->nama}}</td>
			<td>{{@$row->mahasiswa->kelas->nama}}</td>
			<td class="text-center">{{tgl_str($row->tanggal)}}</td>
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
<br/>

<table>
	<tr>
		<td width="33%" class="text-center">
		{{-- <b>Disetujui/Disahkan</b><br/>
		Pembimbing Akademik<br/><br/><br/><br/>
		<b><u>{{$data->mahasiswa->rombel->rombel->dosen_wali->nama}}</u></b> <br/>
		NIDN : {{!empty($data->mahasiswa->rombel->rombel->dosen_wali->nama->nidn)?
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

@include('footer_print')