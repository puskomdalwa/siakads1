
  
     <div class="table-responsive">
       <table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
         <thead>
           <tr>
            <th class="text-center" style="vertical-align:middle" >No</th>
             <th class="text-center">Th Akademik</th>
             <th class="text-center">Prodi</th>
             <th class="text-center">Kelas</th>
             <th class="text-center">Kelompok</th>
             <th class="text-center">Dosen Wali</th>
             <th class="text-center">Mahasiswa</th>
           </tr>
           

         </thead>
         <tbody>
           @php
           $no=1;
           @endphp
           @foreach($data as $row)
           @php
           $mhs = App\PerwalianDetail::where('perwalian_id',$row->id)->count();    
           @endphp
             <tr>
               <td class="text-center">{{ $no++ }}</td>
               <td class="text-center">{{@$row->th_akademik->kode}}</td>
               <td class="text-center">{{@$row->prodi->nama}}</td>
               <td class="text-center">{{@$row->kelas->nama}}</td>
               <td class="text-center">{{@$row->kelompok->nama}}</td>
               <td>{{@$row->dosen->nama}}</td>
               <td class="text-center">{{@$mhs}}</td>
             </tr>
           @endforeach
         </tbody>
       </table>
     </div>
   
  