<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="{{public_path('css/cetak.css')}}">

<div style="font-size:12px;margin-left:20px;margin-right:10px;">
    <table class="judul">
        <tr>
			<th class="text-center" width="15%" style="vertical-align:middle">
			<img src="{{public_path('img/'.$pt->logo)}}" width="80" alt=""></th>
			
			<th class="text-center" width="85%" style="vertical-align:top">
				<span class="judul_besar" style="font-size:26px;">{{strtoupper($pt->nama)}}</span><br>
				<span style="font-size:18px;">TAHUN AKADEMIK {{$th_akademik->nama}} {{$th_akademik->semester}}</span><br/>
				<span class="alamat">Alamat : {{$pt->alamat}} - {{$pt->kota->name}}. Telp:{{$pt->telp}} Email : {{$pt->email}}</span>
			</th>
        </tr>
    </table>
    <hr>
	
    <u><h2 class="text-center" style="font-size:16px;margin:0px;">SURAT KETERANGAN AKTIF KULIAH</h2></u>
    <h3 class="text-center" style="font-size:14px;margin:0px;">Nomor : _________________________ </h3>
    <br>

    Kepala Biro Administrasi Akademik Kemahasiswaan (BAAK) {{ $pt->nama }} Menyatakan bahwa :
    <table class="table table-striped table-hover" style="font-size:12px;">
        <tbody>
            <tr><td width="25%">Nama Lengkap</td>
			<td>: <strong>{{ strtoupper($mhs_aktif->nama) }}</strong> </td></tr>

            <tr><td>NIM</td>
			<td>: {{ strtoupper($mhs_aktif->nim) }} </td></tr>

            <tr><td>Jenis Kelamin</td>
			<td>: {{ strtoupper(@$mhs_aktif->jk->nama) }} </td></tr>

            <tr><td>Program Studi</td>
			<td>: <strong>{{ strtoupper($mhs_aktif->prodi->jenjang) }}
			{{ strtoupper($mhs_aktif->prodi->nama) }}
			</strong> </td></tr>
            
            <tr><td>Semester</td>
			<td>: {{ @getSemesterMahasiswa($mhs_aktif->th_akademik->kode,$mhs_aktif->nim) }} 
			({{ @terbilang(getSemesterMahasiswa($mhs_aktif->th_akademik->kode,$mhs_aktif->nim),1) }})
			</td></tr>
        </tbody>
    </table>
    <br>

    Dengan ini menyatakan bahwa 
    @if($mhs_aktif->jk->kode=='L')
        mahasiswa
    @else
        mahasiswi
    @endif

    yang tersebut namannya di atas sedang Aktif Kuliah pada Jurusan 
    {{ strtoupper($mhs_aktif->prodi->nama) }} semester {{ @getSemesterMahasiswa($mhs_aktif->th_akademik->kode,$mhs_aktif->nim) }}
    ({{ @terbilang(getSemesterMahasiswa($mhs_aktif->th_akademik->kode,$mhs_aktif->nim),1) }}).
    <br/><br/>

    <table style="font-size:12px;">
		<tr>
			<td width="40%" class="text-center">			
			</td>
			<td width="60%" class="text-center">
			<b>{{$pt->kota->name}}, {{format_long_date(date('Y-m-d'))}}</b><br/>
			Ka.BAAK <br/><br/><br/><br/>
			<b><u></u></b>
			</td>
		</tr>
    </table>
</div>    
@include('footer_print')
