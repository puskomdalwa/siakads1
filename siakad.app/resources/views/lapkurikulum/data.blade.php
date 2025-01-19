<div class="panel panel-success panel-dark">
    <div class="panel-heading">
      <span class="panel-title">Data</span>
    </div>
    <div class="panel-body">
  
     <div class="table-responsive">
       <table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
         <thead>
           <tr>
            <th class="text-center" style="vertical-align:middle" >No</th>
             <th class="text-center">Th Akademik</th>
             <th class="text-center">Prodi</th>
             <th class="text-center">Nama</th>
             <th class="text-center">Angkatan</th>
             <th class="text-center">Mata Kuliah</th>
           </tr>
           

         </thead>
         <tbody>
           @php
           $no=1;
           @endphp
           @foreach($data as $row)
           @php
           $mk = App\KurikulumMataKuliah::where('kurikulum_id',$row->id)->count();    
           @endphp
             <tr>
               <td class="text-center">{{ $no++ }}</td>
               <td class="text-center">{{@$row->th_akademik->kode}}</td>
               <td class="text-center">{{@$row->prodi->nama}}</td>
               <td>{{@$row->nama}}</td>
               <td class="text-center"> 
                @php
                $angkatan = App\KurikulumAngkatan::where('kurikulum_id',$row->id)->get();    
                @endphp
                @foreach ($angkatan as $r)
                   <span class="label label-success"> {{ $r->th_angkatan->kode }} </span>
                @endforeach
               </td>
               <td class="text-center">{{@$mk}}</td>
             </tr>
           @endforeach
         </tbody>
       </table>
     </div>
   </div>
  </div>
  