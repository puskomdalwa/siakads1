<div class="table-responsive">
<div class="table-light">
	<div class="table-header">
	<div class="table-caption">DAFTAR MAHASISWA</div>
	</div>

   <table id="serversideTable" class="table table-hover table-bordered table-striped">
		<thead>
			<tr>
				<th class="text-center" style="vertical-align:middle" rowspan="4">NO</th>
				<th class="text-center col-md-1" rowspan="4" style="vertical-align:middle">NIM</th>
				<th class="text-center" rowspan="4" style="vertical-align:middle">NAMA MAHASISWA</th>
				<th class="text-center" rowspan="4" style="vertical-align:middle">L/P</th>
				<th class="text-center" rowspan="4" style="vertical-align:middle">STATUS</th>
				<th class="text-center" colspan="{{ $list_thakademik->count()+$list_thakademik->count() }}">SEMESTER</th>
			</tr>

			<tr>
				@foreach ($list_thakademik as $thakademik)
					<th class="text-center" colspan="2"> {{ $thakademik->kode }} </th>
				@endforeach     
			</tr>
			
			<tr>
				@foreach ($list_thakademik as $thakademik)
					<th class="text-center" colspan="2"> {{ getSMT($th_akademik_angkatan,$thakademik->kode) }}  </th>
				@endforeach
			</tr>
			
			<tr>
				@foreach ($list_thakademik as $thakademik)
					<th class="text-center">SKS</th>
					<th class="text-center">IP</th>
				@endforeach
			</tr>
		</thead>
        
		<tbody>
			@php
			$no=1;
			@endphp
			
			@foreach($data as $row)
				@php
				$sks = @getSKS($row->th_akademik_id,$row->nim);
				@endphp

				<tr>
				<td class="text-center"> {{ $no++ }} </td>
				<td class="text-center">{{$row->nim}}</td>
				<td>{{@$row->nama}}</td>
				<td class="text-center">{{@$row->jk->kode}}</td>
				<td class="text-center">{{@$row->status->nama}}</td>
				@foreach ($list_thakademik as $thakademik)
					<th class="text-center">{{ TSKS($thakademik->id,$row->nim) }}</th>
					<th class="text-center">{{ TIP($thakademik->id,$row->nim) }}</th>
				@endforeach
				</tr>
			@endforeach
		</tbody>
	</table>
</div></div>
  