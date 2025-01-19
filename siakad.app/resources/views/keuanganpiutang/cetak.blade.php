<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="{{URL::asset('/css/cetak.css')}}">

<table class="judul">
    <tr>
		<th class="text-center" width="15%">
        <img src="{{URL::asset('img/'.$pt->logo)}}" width="80" alt="">
		</th>
		
		<th class="text-left" width="65%"><span class="judul_besar">
		LAPORAN PEMBAYARAN MAHASISWA</span><br/>
        TAHUN AKADEMIK {{$th_akademik->nama}} {{$th_akademik->semester}}<br/>
        {{$pt->nama}}<br/>
		
        <span class="alamat">Alamat : {{$pt->alamat}} - {{$pt->kota->name}}. 
		Telp:{{$pt->telp}} Email : {{$pt->email}}</span></th>
	</tr>
</table>
<hr>
<br/>

<table class="data">
	<thead>
		<tr>
			<th class="text-center col-md-1">No</th>
			<th class="text-center col-md-1">NIM</th>
			<th class="text-center col-md-2">Nama</th>
			<th class="text-center">L/P</th>
			<th class="text-center col-md-1">Prodi</th>
			<th class="text-center col-md-1">Kelas</th>
			<th class="text-center">Kelompok</th>
			<th class="text-center col-md-2">Tagihan</th>
			<th class="text-center col-md-1">Piutang</th>
		</tr>
	</thead>
  
	<tbody>
		@php
			$no=1;
			$total = 0;
		@endphp
		
		@foreach($data as $row)
			@php $sisa = $row->jumlah - $row->total_bayar; @endphp

			@if($sisa>0)
				<tr>
				<td class="text-center">{{$no++}}</td>
				<td class="text-center">{{$row->nim}}</td>
				<td>{{$row->nama}}</td>
				<td class="text-center">{{$row->jk->kode}}</td>
				<td class="text-center">{{$row->prodi->nama}}</td>
				<td class="text-center">{{$row->kelas->nama}}</td>
				<td class="text-center">{{@$row->kelompok->perwalian->kelompok->kode}}</td>
				<td>{{$row->nama_tagihan}}</td>
				<td class="text-right">{{number_format($sisa)}}</td>
				</tr>
			@endif
		  
			@php
				$total +=$sisa;
			@endphp
		@endforeach
	</tbody>
	
	<tfoot>
		<tr>
		<td colspan="8" class="text-right">Total</td>
		<td class="text-right">{{number_format($total)}}</td></tr>
	</tfoot>
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

@include('footer_print')
