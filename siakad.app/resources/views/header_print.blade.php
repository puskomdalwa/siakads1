<table>
    <tr>
		<th class="text-center" width="15%" style="vertical-align:middle;">
        <img src="{{public_path('img/'.$pt->logo)}}" width="100" height="100" alt=""></th>
      
		<th class="{{ $class }}" width="85%" style="vertical-align:middle;color: #000;">
			<span style="font-size:22px;font-weight: bold">{{@$pt->nama}}</span><br>
			
			@if($prodi)
				<span style="font-size:18px;font-weight: bold">PROGRAM STUDI 				
				{{ @strtoupper($prodi->nama) }} ({{ @strtoupper($prodi->jenjang) }})
				</span><br>
			@else
				<br>
			@endif
			
			<span style="font-size:12px;font-weight: bold">
			SK. Mendiknas RI Nomor {{@$pt->sk}}</span><br>
			<span class="alamat" style="font-size:10px;">
			Alamat : {{@$pt->alamat}} - {{@$pt->kota->name}}. Telp:{{@$pt->telp}}</span><br>
			<span class="email" style="font-size:10px;">
			Email : {{@$pt->email}} Website : {{@$pt->website}}</span>
		</th>
    </tr>
</table>
<hr>

<style>
.x {
	background: #000;
	color: #fff;
	vertical-align: middle;
}
</style>
