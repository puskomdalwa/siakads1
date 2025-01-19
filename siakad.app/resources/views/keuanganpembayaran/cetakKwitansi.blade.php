<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="{{public_path('css/cetak.css')}}">

<table class="judul">
    <tr>
		<th class="text-center" width="15%">
        <img src="{{public_path('img/'.$pt->logo)}}" width="80" alt=""></th>
	  
		<th class="text-left" width="65%"><span class="judul_besar">
		KWITANSI PEMBAYARAN MAHASISWA</span><br/>
		TAHUN AKADEMIK {{$th_akademik->nama}} {{$th_akademik->semester}}<br/>
		{{$pt->nama}}<br/>
		<span class="alamat">Alamat : {{$pt->alamat}} - {{$pt->kota->name}}. 
		Telp:{{$pt->telp}} Email : {{$pt->email}}</span></th>
    </tr>
</table>
<hr>
<br/>

<table style="font-size:12px;">
<thead>
    <tr>
		<td width="15%">Nomor Bukti</td>
		<td>: {{$data->nomor}}</td>
		<td width="10%">Tanggal</td>
		<td>: {{format_long_date($data->tanggal)}}</td>
    </tr>
	
    <tr>
		<td width="10%">NIM</td>
		<td>: <b>{{$data->nim}}</b></td>
		<td width="10%">Nama</td>
		<td>: {{@$data->mahasiswa->nama}}</td>
    </tr>
	
    <tr>
		<td width="10%">Program Studi</td>
		<td>: {{@$data->mahasiswa->prodi->jenjang}} - 
		{{@$data->mahasiswa->prodi->nama}} </td>
		
		<td width="10%">Kelas</td>
		<td>: {{@$data->mahasiswa->kelas->nama}}</td>
    </tr>
	
    <tr>
		<td width="10%">Pembayaran</td>
		<td>: {{@$data->tagihan->nama}} 
		{{@$data->tagihan->th_akademik->kode}} Semester {{@$data->smt}}</td>
    </tr>
	
    <tr>
		<td width="10%">Jumlah</td>
		<td>: <b>Rp. {{number_format($data->jumlah)}}.-</b></td>
    </tr>
	
    <tr>
		<td width="10%">Terbilang</td>
		<td>: <i>{{terbilang($data->jumlah,3)}} Rupiah</i></td>
    </tr>
</thead>
</table>
<hr/>
<br/>

<table>
	<tr>
		<td width="33%" class="text-center">
			Yang Menerima, <br/>Keuangan</br><br/><br/><br/>
			<b><u>{{Auth::user()->name}}</u></b>
		</td>

		<td width="33%" class="text-center">
			{{-- <b>Mengetahui</b><br/>Ketua Program Studi<br/><br/><br/><br/>
			<b><u>{{$data->prodi->nama_kepala}}</u></b> <br/>
			NIDN : {{!empty($data->prodi->nidn_kepala)?$data->prodi->nidn_kepala:'-'}} --}}
		</td>

		<td width="33%" class="text-center">
			<b>{{$pt->kota->name}}, {{format_long_date(date('Y-m-d'))}}</b><br/>
			Penyetor, <br/><br/><br/><br/>
			<b><u>{{@$data->mahasiswa->nama}}</u></b>
		</td>
	</tr>
</table>

Ketentuan :
Setoran diakui sah apabila telah dibubuhi cap dan tanda tangan Bagian Keuangan.

@include('footer_print')
