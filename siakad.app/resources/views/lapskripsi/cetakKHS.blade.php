<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="{{public_path('css/cetak.css')}}">

<table class="judul">
    <tr>
      <th class="text-center" width="15%">
        <img src="{{public_path('img/'.$pt->logo)}}" width="80" alt="">
      </th>
      <th class="text-left" width="65%"><span class="judul_besar">KARTU HASIL STUDI (KHS) MAHASISWA</span><br/>
        TAHUN AKADEMIK {{$th_akademik->nama}} {{$th_akademik->semester}}<br/>
        {{$pt->nama}}<br/>
        <span class="alamat">Alamat : {{$pt->alamat}} - {{$pt->kota->name}}. Telp:{{$pt->telp}} Email : {{$pt->email}}</span>
      </th>
    </tr>
</table>
<hr>
<br/>
<table>
  <thead>
    <tr>
      <td width="13%">NIM</td>
      <td>: {{$krs->nim}}</td>
      <td width="13%">Tahun Akademik</td>
      <td>: {{$krs->th_akademik->kode}}</td>
    </tr>
    <tr>
      <td>Nama Mahasiswa</td>
      <td>: {{$krs->mahasiswa->nama}}</td>
      <td>Kelas / Semester</td>
      <td>: {{$krs->kelas->nama}} / {{$krs->smt}}</td>
    </tr>
    <tr>
      <td>Program Studi</td>
      <td>: {{$krs->prodi->nama}}</td>
      <td>IP smt Lalu</td>
      <td>: {{getIP($krs->nim,$krs->th_akademik_id,$krs->smt-1)}}</td>
    </tr>
  </thead>
</table>
<table class="data">
  <thead>
      <tr>
        <th class="text-center" width="2%">No</th>
        <th class="text-center" width="5%">Kode</th>
        <th class="text-center" width="25%">Mata Kuliah</th>
        <th class="text-center" width="2%">SKS</th>
        <th class="text-center" width="5%">NILAI</th>
        <th class="text-center" width="5%">ANGKA (A)</th>
        <th class="text-center" width="5%">SKS X A</th>
      </tr>
  </thead>
  <tbody>
    @php
    $no=1;
    $t_sks = 0;
    $t_nilai = 0;
    @endphp
    @foreach($data as $row)
      @php
      $nilai = $row->sks_mk * $row->nilai_bobot;
      @endphp
      <tr>
        <td class="text-center">{{$no++}}</td>
        <td class="text-center">{{$row->kode_mk}}</td>
        <td>{{$row->nama_mk}}</td>
        <td class="text-center">{{$row->sks_mk}}</td>
        <td class="text-center">{{$row->nilai_huruf}}</td>
        <td class="text-center">{{$row->nilai_bobot}}</td>
        <td class="text-center">{{$nilai}}</td>
      </tr>
      @php
      $t_sks +=$row->sks_mk;
      $t_nilai += $nilai;
      @endphp
    @endforeach
  </tbody>
  <tfoot>
    <tr>
      <td class="text-center" colspan="3">Jumlah SKS</td>
      <td class="text-center">{{$t_sks}}</td>
      <td colspan="2"></td>
      <td class="text-center">{{$t_nilai}}</td>
    </tr>
    <tr>
      <td class="text-center" colspan="3">IP</td>
      <td class="text-center">{{number_format($t_nilai/$t_sks,2)}}</td>
      <td colspan="3"></td>
    </tr>
  </tfoot>
</table>
<br/>
<table>
  <tr>
    <td width="33%" class="text-center">
      <b>Mengetahui</b><br/>
      Biro Akademik<br/><br/><br/><br/>
      <b><u>{{$biro->nama}}</u></b> <br/>
      NIP : {{$biro->kode}}
    </td>
    <td width="33%" class="text-center">
      <b>Disetujui</b><br/>
      Kepala Program Studi<br/><br/><br/><br/>
      <b><u>{{$krs->prodi->nama_kepala}}</u></b> <br/>
      NIP : {{@$krs->prodi->nidn_kepala}}
    </td>
    <td width="33%" class="text-center">
      <b>{{$pt->kota->name}}, {{format_long_date(date('Y-m-d'))}}</b><br/>
      Mahasiswa, <br/><br/><br/><br/>
      <b><u>{{$krs->mahasiswa->nama}}</u></b><br/>
      NIM : {{$krs->mahasiswa->nim}}
    </td>
  </tr>
</table>
@include('footer_print')
