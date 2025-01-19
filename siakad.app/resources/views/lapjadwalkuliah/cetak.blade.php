<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="{{public_path('css/cetak.css')}}">

@include('header_print')
<div style="font-size: 12px;">
	<h3 class="text-center" style="margin:2px;">JADWAL KULIAH SEMESTER {{ strtoupper($th_akademik->semester) }}<br></h3>
	<h3 class="text-center" style="margin:2px;">TAHUN AKADEMIK {{ $th_akademik->nama }}</h3>
	<h3 class="text-center" style="margin:2px;">PROGRAM STUDI {{ strtoupper($dt_prodi->nama) }} ({{ $dt_prodi->jenjang }}) </h3>

	@foreach($list_smt as $smt)
		<div class="table-light">
			<div class="table-header">
				@if ( $smt->smt%2==0)
					<div class="table-caption text-left"> <br> SEMESTER: {{ $smt->smt }} (Genap) </div>
				@else
					<div class="table-caption text-left"> <br> SEMESTER: {{ $smt->smt }} (Ganjil) </div>
				@endif
			</div>
			
			<table class="data">
				<thead>
					<tr>
						<th class="text-center" width="10px">NO</th>
						<th class="text-center" width="50px">KODE MK</th>
						<th class="text-center" width="210px">MATA KULIAH</th>
						<th class="text-center" width="10px">SKS</th>
						<th class="text-center" width="250px">DOSEN</th>
						<th class="text-center" width="50px">KELAS</th>
						<th class="text-center" width="90px">RUANG</th>
						<th class="text-center" width="50px">HARI</th>
						<th class="text-center" width="50px">WAKTU</th>
					</tr>
				</thead>
				
				<tbody>
					@php
						$no=1;
						$tsks =0;
						$list_jadwal = App\JadwalKuliah::where('th_akademik_id',$th_akademik->id)
						->where('prodi_id',$prodi_id)
						->where('smt',$smt->smt)
						->orderBy('hari_id')
						->with(['th_akademik','prodi','kelas','jamkul'])
						->get();
					@endphp
					
					@foreach ($list_jadwal as $jadwal)
						<tr>
							<td class="text-center"> {{ $no++ }} </td>
							<td class="text-center"> {{ @strtoupper($jadwal->kurikulum_matakuliah->matakuliah->kode) }} </td>
							<td>{{ @strtoupper($jadwal->kurikulum_matakuliah->matakuliah->nama) }}</td>
							<td class="text-center"> {{ @$jadwal->kurikulum_matakuliah->matakuliah->sks }} </td>
							<td>{{ @$jadwal->dosen->nama }}</td>
							<td class="text-center"> {{ @strtoupper($jadwal->kelas->nama) }} </td>
							<td class="text-center"> {{ @strtoupper($jadwal->ruang_kelas->nama) }} </td>
							<td class="text-center"> {{ @strtoupper($jadwal->hari->nama) }} </td>
							<!-- <td class="text-center"> {{@$jadwal->jam_mulai}} s.d {{@$jadwal->jam_selesai}} </td> -->
							<td class="text-center"> {{ @$jadwal->jamkul->nama }} </td>							
						</tr>
						
						@php $tsks += @$jadwal->kurikulum_matakuliah->matakuliah->sks; @endphp
					@endforeach
				</tbody>
				
				<tfoot>
					<tr>
						<td class="text-center" colspan="3">JUMLAH</td>
						<td class="text-center"> {{ $tsks }} </td>
						<td colspan="5"></td>
					</tr>
				</tfoot>
			</table>    
		</div>
	@endforeach
</div>
<br/>

<table>
	<tr>
		<td width="33%" class="text-center">
			{{-- <b>Disetujui/Disahkan</b><br/>
			Pembimbing Akademik<br/><br/><br/><br/>
			<b><u>{{$data->mahasiswa->rombel->rombel->dosen_wali->nama}}</u></b> <br/>
			NIDN : {{!empty($data->mahasiswa->rombel->rombel->dosen_wali->nama->nidn) ?
			$data->mahasiswa->rombel->rombel->dosen_wali->nama->nidn : '-'}} --}}
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