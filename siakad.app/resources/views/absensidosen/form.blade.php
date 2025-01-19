@push('css')
<style media="screen">
.text-bold {
	font-weight: bold;
}

/* #serversideTable th {
	background-color: #d0e6be;;
	color: #000;
	font-weight: bold;
	border-color: #000 !important;
}

#serversideTable td {
	border-color: #000 !important;
} */
</style>
@endpush

<input type="hidden" name="jadwal_kuliah_id" value="{{$data->id}}">
<div class="note note-warning">
<div class="table-responsive">
	<table  class="table table-bordered table-striped" style="clear:both">
		<tbody>
			<tr><td width="12.5%">Tahun Akademik</td>
			<td width="37.5%" class="text-bold">: {{$data->th_akademik->kode}}</td>
			<td width="12.5%">Program Studi</td>
			<td width="37.5%" class="text-bold">: {{$data->prodi->nama}}</td></tr>
			
			<tr><td>Kurikulum</td>
			<td class="text-bold">: {{$data->kurikulum_matakuliah->kurikulum->nama}}</td>			
			<td>Semester/Mshasiswa</td>
			<td class="text-bold">: {{$data->kurikulum_matakuliah->matakuliah->smt}} / {{$jmlmhs}}</td></tr>
			
			<tr><td>Mata Kuliah</td>
			<td class="text-bold">: {{$data->kurikulum_matakuliah->matakuliah->kode}} - 
			{{$data->kurikulum_matakuliah->matakuliah->nama}} 
			({{$data->kurikulum_matakuliah->matakuliah->sks}} SKS)</td>			
			<td>Kelas / Kelomok</td>
			<td class="text-bold">: {{$data->kelas->nama}} / {{$data->kelompok->kode}}</td></tr>
			
			<tr><td>Dosen</td>
			<td class="text-bold">: {{$data->dosen->kode}} - {{$data->dosen->nama}}</td>			
			<td>Ruangan</td>
			<td class="text-bold">: {{$data->ruang_kelas->nama}}</td></tr>
			
			<tr><td>Hari</td>
			<td class="text-bold">: {{$data->hari->nama}}</td>			
			<td>Waktu</td>
			<td class="text-bold">: {{$data->jam_kuliah_id}}</td></tr>			
			<!-- <td class="text-bold">: {{$data->jam_mulai}} s.d {{$data->jam_selesai}}</td></tr> -->
		</tbody>
	</table>
</div></div>

<!--
<div class="panel-body no-padding-hr">
<div class="panel-footer">
<div class="col-sm-offset-1 col-md-10">
<button type="submit" name="save" id="save" class="btn btn-success btn-flat btn-block">
<i class="fa fa-floppy-o"></i> Simpan Nilai</button>
</div></div></div>
-->

@php
	$level = strtolower(Auth::user()->level->level);
@endphp

<!--
@if ($level == 'admin' OR $level == 'baak')
	<div class="panel-body no-padding-hr">
	<div class="panel-footer">
	<div class="col-sm-offset-1 col-md-10">
		<button type="submit" name="save" id="save" class="btn btn-success btn-flat btn-block">
		<i class="fa fa-floppy-o"></i> Simpan Nilai</button>
	</div></div></div>
@endif
-->

@include($folder.'/listmhs')

