@extends('layouts.app')
@section('title',$title)

@section('content')
<form action=" {{ route($redirect.'.store') }} " class="form-horizontal" method="post" target="blank">
    {{ csrf_field() }}
	
    <div class="panel widget-messages-alt panel-danger panel-dark">
        <div class="panel-heading">
		<span class="panel-title"><i class="panel-title-icon fa fa-envelope"></i>{{$title}}</span></div>
        
        <div class="panel-body">
            <u><h1 class="text-center">SURAT KETERANGAN AKTIF KULIAH </h1></u>
            <i><span class="text-center">Nomor : Dicatat oleh BAAK. </span></i>
            <br>

            Kepala Biro Administrasi Akademik Kemahasiswaan (BAAK) {{$pt->nama}} Menyatakan bahwa :
            <table class="table table-striped table-hover">
                <tbody>
                    <tr><td width="200px">Nama Lengkap</td>
					<td>: <strong>{{ strtoupper($mhs_aktif->nama) }}</strong> </td></tr>
                    
					<tr> <td>NIM</td><td>: {{ strtoupper($mhs_aktif->nim) }} </td></tr>
                    <tr><td>Jenis Kelamin</td><td>: {{ strtoupper(@$mhs_aktif->jk->nama) }} </td></tr>

                    <tr><td>Program Studi</td><td>: <strong>{{ strtoupper($mhs_aktif->prodi->jenjang) }}
					{{ strtoupper($mhs_aktif->prodi->nama) }}</strong> 
					</td></tr>
                    
                    <tr><td>Semester</td><td>: {{ @getSemesterMahasiswa($mhs_aktif->th_akademik->kode,$mhs_aktif->nim) }} 
					({{ @terbilang(getSemesterMahasiswa($mhs_aktif->th_akademik->kode,$mhs_aktif->nim),1) }})
					</td></tr>
                </tbody>
            </table>
			
            Dengan ini menyatakan bahwa 

            @if($mhs_aktif->jk->kode=='L')
                mahasiswa
            @else
                mahasiswi
            @endif

            yang tersebut namannya di atas sedang Aktif Kuliah pada Jurusan 
            {{ strtoupper($mhs_aktif->prodi->nama) }} semester {{ @getSemesterMahasiswa($mhs_aktif->th_akademik->kode,$mhs_aktif->nim) }}
            ({{ @terbilang(getSemesterMahasiswa($mhs_aktif->th_akademik->kode,$mhs_aktif->nim),1) }}).
        </div>
        
        <div class="panel-footer text-center">
            <button type="submit" class="btn btn-primary"><i class="fa fa-print"></i> Cetak </button>
        </div>
    </div>
</form>

<div class="note note-warning">
<h4 class="note-title">Perhatian !!!!</h4>
Silahkan cetak mandiri. Untuk Tanda Tangan dan Cap Kampus silahkan Hubungi BAAK.
</div>

@endsection
