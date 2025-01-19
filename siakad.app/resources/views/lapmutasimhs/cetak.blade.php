<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="{{public_path('css/cetak.css')}}">

<table class="judul">
    <tr>
      <th class="text-center" width="15%">
        <img src="{{public_path('img/'.$pt->logo)}}" width="80" alt="">
      </th>
      <th class="text-left" width="65%"><span class="judul_besar">LAPORAN MUTASI MAHASISWA</span><br/>
        TAHUN AKADEMIK {{$th_akademik->nama}} {{$th_akademik->semester}}<br/>
        {{$pt->nama}}<br/>
        <span class="alamat">Alamat : {{$pt->alamat}} - {{$pt->kota->name}}. Telp:{{$pt->telp}} Email : {{$pt->email}}</span>
      </th>
    </tr>
</table>
<hr>
<br/>

<table class="data">
  <thead>
      <tr>
        <th class="text-center" width="10">No</th>
        <th class="text-center" width="50">NIM</th>
        <th class="text-center" width="120">Nama</th>
        <th class="text-center" width="5">L/P</th>
        <th class="text-center" width="50">Program Studi</th>
        <th class="text-center" width="10">Kelas</th>
        <th class="text-center" width="10">Kelompok</th>
        <th class="text-center" width="10">Email</th>
        <th class="text-center" width="10">HP</th>
        <th class="text-center" width="15">Tanggal</th>
        <th class="text-center" width="30">Status</th>
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
        <td>{{@$row->mahasiswa->nama}}</td>
        <td class="text-center">{{@$row->mahasiswa->jk->kode}}</td>
        <td>{{@$row->mahasiswa->prodi->nama}}</td>
        <td>{{@$row->mahasiswa->kelas->nama}}</td>
        <td class="text-center">{{@$row->mahasiswa->kelompok->perwalian->kelompok->kode}}</td>
        <td>{{@$row->mahasiswa->email}}</td>
        <td class="text-center">{{@$row->mahasiswa->hp}}</td>
        <td class="text-center">{{@tgl_str($row->tanggal)}}</td>
        <td class="text-center">{{@$row->status->nama}}</td>
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
      NIDN : {{!empty($data->mahasiswa->rombel->rombel->dosen_wali->nama->nidn)?$data->mahasiswa->rombel->rombel->dosen_wali->nama->nidn:'-'}} --}}
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
