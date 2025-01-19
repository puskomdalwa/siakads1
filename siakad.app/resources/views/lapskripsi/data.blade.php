<div class="panel panel-success panel-dark">
  <div class="panel-heading">
    <span class="panel-title">Data</span>
  </div>
  <div class="panel-body">

   <div class="table-responsive">
     <table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
       <thead>
         <tr>
          <th class="text-center">No</th>
           <th class="text-center col-md-1">NIM</th>
           <th class="text-center">Nama Mahasiswa</th>
           <th class="text-center">L/P</th>
           <th class="text-center">Prodi</th>
           <th class="text-center">Tanggal</th>
           <th class="text-center">Judul Skripsi</th>
           <th class="text-center">Pembimbing</th>
         </tr>
       </thead>
       <tbody>
         @php
         $no=1;    
         @endphp
         @foreach($data as $row)
           <tr>
             <td class="text-center"> {{ $no++ }} </td>
             <td class="text-center">{{$row->pengajuan->nim}}</td>
             <td>{{@$row->pengajuan->mahasiswa->nama}}</td>
             <td class="text-center">{{@$row->pengajuan->mahasiswa->jk->kode}}</td>
             <td>{{@$row->pengajuan->mahasiswa->prodi->nama}}</td>
             <td class="text-center"> {{ @tgl_Nojam($row->updated_at) }} </td>
             <td> {!! $row->judul !!} </td>
             <td>
               @php
               $pembimbing = App\SkripsiPembimbing::where('skripsi_pengajuan_id',$row->skripsi_pengajuan_id)->get();    
               @endphp
               <ol>
               @foreach ($pembimbing as $r)
                   <li> {{ @$r->dosen->nama }} => {{ $r->jabatan }} </li>
               @endforeach
              </ol>
             </td>
           </tr>
         @endforeach
       </tbody>
     </table>
   </div>
 </div>
</div>
