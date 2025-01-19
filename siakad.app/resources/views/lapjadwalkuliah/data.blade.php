<div class="panel panel-success panel-dark">
    <div class="panel-heading">
	<span class="panel-title">Data</span></div>

    <div class="panel-body">
	<div class="table-responsive">
		<table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th class="text-center" style="vertical-align:middle" >No</th>
					<th class="text-center">Th Akademik</th>
					<th class="text-center">Prodi</th>
					<th class="text-center">Kelas</th>
					<th class="text-center">Kelompok</th>
					<th class="text-center">SMT</th>
					<th class="text-center">Kurikulum</th>
					<th class="text-center">Mata Kuliah</th>
					<th class="text-center">Dosen</th>
					<th class="text-center">Ruang</th>
					<th class="text-center">Hari</th>
					<th class="text-center">Waktu</th>
					<th class="text-center">Mahasiswa</th>
				</tr>
			</thead>

			<tbody>
				@php
				$no=1;
				@endphp

				@foreach($data as $row)
					@php $mhs = App\KRSDetail::where('jadwal_kuliah_id',$row->id)->count(); @endphp
					<tr>
						<td class="text-center">{{ $no++ }}</td>
						<td class="text-center">{{@$row->th_akademik->kode}}</td>
						<td class="text-center">{{@$row->prodi->nama}}</td>
						<td class="text-center">{{@$row->kelas->nama}}</td>
						<td class="text-center">{{@$row->kelompok->nama}}</td>
						<td class="text-center">{{@$row->smt}}</td>
						<td>{{@$row->kurikulum_matakuliah->kurikulum->nama}}</td>
						<td>{{@$row->kurikulum_matakuliah->matakuliah->nama}}</td>
						<td> {{ @$row->dosen->nama }} </td>
						<td class="text-center">{{@$row->ruang_kelas->nama}}</td>
						<td class="text-center">{{@$row->hari->nama}}</td>
						<td class="text-center">{{@$row->jam_mulai}} s.d {{@$row->jam_selesai}}</td>
						<td class="text-center">{{@$mhs}}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
    </div></div>
</div>
  