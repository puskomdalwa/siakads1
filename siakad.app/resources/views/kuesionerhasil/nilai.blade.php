<p class="text-info">{{$dosen->kode}} - {{$dosen->nama}}</p>
<table class="table table-hover table-bordered table-condensed table-striped">
  <thead>
    <tr>
      <th class="text-center">No</th>
      <th class="text-center">Pertanyaan</th>
      <th class="text-center">Nilai</th>
    </tr>
  </thead>
  <tbody>
    @php
      $no=1;
      $tot = 0;
    @endphp
    @foreach($list_pertanyaan as $row)
      @php
        $nilai = getKuesionerNilai($dosen->id,$row->id);
      @endphp
      <tr>
        <td class="text-center">{{$no++}}</td>
        <td>{{$row->pertanyaan}}</td>
        <td class="text-center">{{$nilai}}</td>
      </tr>
      @php
        $tot +=$nilai;
      @endphp
    @endforeach
    <tfoot>
      <tr>
        <td colspan="2" class="text-center">Total</td>
        <td class="text-center">{{$tot}}</td>
      </tr>
      <tr>
        <td colspan="2" class="text-center">Rata-rata</td>
        <td class="text-center">{{$tot/$list_pertanyaan->count()}}</td>
      </tr>
    </tfoot>
  </tbody>
</table>
<p class="text-danger">Kekurangan yang harus diperbaiki</p>
<ol>
  @foreach ($kuesioner_jawaban as $value)
    <li>{{$value->kekurangan}}</li>
  @endforeach
</ol>
<p class="text-success">Kelebihan yang harus dipertahankan</p>
<ol>
  @foreach ($kuesioner_jawaban as $value)
    <li>{{$value->kelebihan}}</li>
  @endforeach
</ol>
