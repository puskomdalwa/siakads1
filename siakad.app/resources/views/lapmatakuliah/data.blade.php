<div class="table-responsive">
  <table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
    <thead>
      <tr>
      <th class="text-center">No</th>
        <th class="text-center col-md-1">Kode</th>
        <th class="text-center">Matakuliah</th>
        <th class="text-center">SKS</th>
        <th class="text-center">Semester</th>
        <th class="text-center">Program Studi</th>
        <th class="text-center">Status</th>
      </tr>
    </thead>
    <tbody>
      @php
      $no=1;    
      $t_sks = 0;
      @endphp
      @foreach($data as $row)
        <tr>
          <td class="text-center"> {{ $no++ }} </td>
          <td class="text-center">{{$row->kode}}</td>
          <td>{{$row->nama}}</td>
          <td class="text-center">{{$row->sks}}</td>
          <td class="text-center">{{$row->smt}}</td>
          <td>{{$row->prodi->nama}}</td>
          <td class="text-center">{{$row->aktif}}</td>
        </tr>
        @php
        $t_sks += $row->sks;
        @endphp
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td class="text-center" colspan="3">Total</td>
        <td class="text-center"> {{ $t_sks }} </td>
      </tr>
    </tfoot>
  </table>
</div>
