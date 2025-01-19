<!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> -->
<link rel="stylesheet" href="{{public_path('css/cetak.css')}}">

@include('header_print')

<div style="font-size: 12px;">
  
@foreach ($data['list_prodi'] as $prodi)
    <h3 class="text-center" style="margin:0px">MATA KULIAH</h3>
    <h3 class="text-center" style="margin:0px">PROGRAM STUDI {{ strtoupper($prodi->nama) }} ({{ $prodi->jenjang }})</h3>
    
    @foreach ($data['list_smt'] as $smt)
    <h5 class="text-center" style="margin:10px">SEMESTER {{ $smt->smt }} </h5>
    <table class="data">
        <thead >
            <tr>
                <th class="text-center" width="10px" style="background:#d6d6d6">NO</th>
                <th class="text-center" width="120px" style="background:#d6d6d6">KODE</th>
                <th class="text-center" width="300px" style="background:#d6d6d6">NAMA MATA KULIAH</th>
                <th class="text-center" width="10px" style="background:#d6d6d6">SKS</th>
                <th class="text-center" width="50px" style="background:#d6d6d6">AKTIF</th>
            </tr>
        </thead>
        <tbody>
            @php
            $no=1;
            $tsks =0;
            @endphp
            @foreach($data['rows'][$prodi->id][$smt->smt] as $row)
            <tr>
                <td class="text-center"> {{ $no++ }} </td>
                <td class="text-center"> {{ $row->kode }} </td>
                <td> {{ strtoupper($row->nama) }} </td>
                <td class="text-center"> {{ @$row->sks }} </td>
                <td class="text-center">{{@$row->aktif}}</td>
            </tr> 
            @php
            $tsks +=$row->sks;    
            @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-center">Total</td>
                <td class="text-center"> {{ $tsks }} </td>
                <td></td>
            </tr>
        </tfoot>
     </table>
     @endforeach
@endforeach  
</div>
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
      <b>{{@$pt->kota->name}}, {{format_long_date(date('Y-m-d'))}}</b><br/>
      Yang Melaporkan, <br/><br/><br/><br/>
      <b><u>{{Auth::user()->name}}</u></b>
    </td>
  </tr>
</table>
@include('footer_print')
