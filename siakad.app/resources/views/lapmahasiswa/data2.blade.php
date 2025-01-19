<div class="table-responsive">
	@foreach ($data['list_kelas'] as $kelas)
		@foreach($data['list_prodi'] as $prodi)
			<div class="table-light">
				<div class="table-header">
				<div class="table-caption text-center">
					MAHASISWA {{ strtoupper($kelas->nama) }}<br>
					TAHUN AKADEMIK {{ $data['th_akademik']->nama }} {{ strtoupper($data['th_akademik']->semester) }}<br>
					PROGRAM STUDI {{ strtoupper($prodi->nama) }} ({{ strtoupper($prodi->jenjang) }})
				</div></div>
			
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="text-center" width="10px">NO</th>
							<th class="text-center" width="10px">NIM</th>
							<th class="text-center" width="300px">NAMA MAHASISWA</th>
							<th class="text-center" width="10px">L/P</th>
							<th class="text-center" width="100px">KELOMPOK</th>
							<th class="text-center" width="50px">HP</th>
							<th class="text-center" width="120px">KOTA</th>
							<!-- <th class="text-center" width="50px">STATUS</th> -->
						</tr>
					</thead>
					
					<tbody>
						@php $no=1; @endphp
						@foreach ($data['rows'][$kelas->id][$prodi->id] as $row)
							<tr>
								<td class="text-center"> {{ $no++ }} </td>
								<td class="text-center"> {{ $row->nim }} </td>
								<td> {{ strtoupper($row->nama) }} </td>
								<td class="text-center"> {{ @$row->jk->kode }} </td>
								<td class="text-center">{{@$row->kelompok->perwalian->kelompok->kode}}</td>
								<td class="text-center">{{@$row->hp}}</td>
								<td> {{ @$row->kota->name }} </td>
								<!-- <td class="text-center">{{@$row->status->nama}}</td> -->
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		@endforeach
	@endforeach
</div>
