<!--
@php
	date_default_timezone_set('Asia/Jakarta');
	$tgl = date('Y-m-d');
	
	//$batas= mktime(date("d"),date("m"),date("Y"));
	//$batas = date('2021-08-25');
	//$batas = date('2022-03-04');
	//echo $batas;

	$level = strtolower(Auth::user()->level->level);
@endphp
-->

<div class="panel panel-success panel-dark">
	<div class="panel-heading">
	<span class="panel-title">Data Mahasiswa</span></div>
	
	<div class="table-responsive">
		<table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th class="text-center" rowspan="3" style="vertical-align:middle">No.</th>
					<th class="text-center" rowspan="3" valign="middle" style="vertical-align:middle">Tanggal</th>
					<th class="text-center col-md-8" rowspan="3" style="vertical-align:middle">Materi Kuliah</th>				
					<th class="text-center" colspan="4" style="vertical-align:middle">ABSENSI</th>

					<!-- <th class="text-center col-md-1" rowspan="3" style="vertical-align:middle">Nilai Akhir</th>
					<th class="text-center col-md-1" rowspan="3" style="vertical-align:middle">Nilai Huruf</th>
					<th class="text-center col-md-1" rowspan="3" style="vertical-align:middle">Nilai Bobot</th>	-->
					
				</tr>
				
				<tr>
					<!-- @foreach($komponen_nilai as $row)
						<th class="text-center">{{$row->nama}}</th>
					@endforeach -->
				
					<th class="text-center">HADIR</th>
					<th class="text-center">ALPA</th>
					<th class="text-center">SAKIT</th>
					<th class="text-center">IJIN</th>
					<th class="text-center">LAIN</th>
				</tr>

				<!--
				<tr>
					@foreach($komponen_nilai as $row)
						<th class="text-center">{{$row->bobot}}%</th>
					@endforeach
				</tr>
				-->
				
				<tr>
					<!-- <th class="text-center" rowspan="2" style="vertical-align:middle">Akhir</th>
					<th class="text-center" rowspan="2" style="vertical-align:middle">Huruf</th>
					<th class="text-center" rowspan="2" style="vertical-align:middle">Bobot</th>
					-->
				</tr>
			</thead>				

			<tbody>
				@php $no=0; @endphp
				@foreach($list_abs as $abs)
					<tr>
						@php 
							$no++; 
							$hadir = jmlabshadir($abs->id);
							$alpa  = jmlabsalpa($abs->id);
							$sakit = jmlabssakit($abs->id);
							$ijin  = jmlabsijin($abs->id);
							$lain  = jmlabslain($abs->id);
						@endphp
						
						<td class="text-center" valign="middle">{{number_format($no,0)}}</td>
						<td class="text-center" valign="middle">{{tgl_str($abs->tanggal)}}</td>
						<td class="text-left">{{$abs->materi}}</td>
						<td class="text-center">{{$hadir}}</td>
						<td class="text-center">{{$alpa}}</td>
						<td class="text-center">{{$sakit}}</td>
						<td class="text-center">{{$ijin}}</td>
						<td class="text-center">{{$lain}}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
	
{{-- var {{$row->nama}} = parseFloat($("#{{$row->nama}}_"+id).val()); --}}

@push('demo')
<script>
init.push(function () {
	// $('#serversideTable').dataTable();
});
</script>
@endpush

@push('scripts')
<script type="text/javascript">
function hanyaAngka(evt) {
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 &&  (charCode <= 45 || charCode > 57) && (charCode >46 ) )
	return false;
	return true;
}

function hitungNilai(id){
	var nilai_akhir = 0;
	@foreach($komponen_nilai as $row)
	nilai_akhir += parseFloat($("#{{$row->nama}}_"+id).val()||0) * parseFloat({{$row->bobot}})/100 ;
	@endforeach

	@foreach($komponen_nilai as $row)
	if(parseFloat($("#{{$row->nama}}_"+id).val()) > 100){
		swal('Error Nilai',' Range Nilai 0 s.d 100','error');
		$("#{{$row->nama}}_"+id).val(0);
		return false;
	}
	@endforeach

	$("#nilai_akhir_"+id).val(nilai_akhir);
	getBobotNilai(nilai_akhir,id);
}

function getBobotNilai(nilai_akhir,id){
	var string = {
		nilai_akhir : nilai_akhir,
		_token: "{{ csrf_token() }}"
	};

	$.ajax({
		url    : "{{ url($folder."/getBobotNilai") }}",
		method : 'POST',
		data   : string,
		success:function(data){
			// console.log(data);
			$("#nilai_huruf_"+id).val(data.nilai_huruf);
			$("#nilai_bobot_"+id).val(data.nilai_bobot);
		}
	});
}
</script>
@endpush

