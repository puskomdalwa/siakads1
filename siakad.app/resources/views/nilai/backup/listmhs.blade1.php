@php
	date_default_timezone_set('Asia/Jakarta');
	$tgl = date('Y-m-d');
	
	//$batas= mktime(date("d"),date("m"),date("Y"));
	//$batas = date('2021-08-25');
	
	$batas = date('2022-03-04');
	//echo $batas;

	$mulai = $buka_form->tgl_mulai;
	$akhir = $buka_form->tgl_selesai;
	
	$level = strtolower(Auth::user()->level->level);
@endphp

<div class="panel panel-success panel-dark">
	<div class="panel-heading">
		<span class="panel-title">Data Mahasiswa xxx</span>
	</div>
	
	<span class="panel-title">	
	@if (($level <> 'baak') && ($level <> 'admin')) 	
		@if (($tgl >= $mulai) && ($tgl <= $akhir)) 
			<h4><b><center> Mohon Diperhatikan !!! <br/> 
			<!-- Batas Akhir Waktu Pengisian Nilai Tanggal: 02/03/2022 s/d 03/03/2022 - 24:00:00 (Mohon Nilai Diisi Dengan Angka Bulat) -->
			Batas Akhir Waktu Pengisian Nilai Tanggal: {{tgl_str($buka_form->tgl_mulai)}} s/d {{tgl_str($buka_form->tgl_selesai)}} 
			- 24:00:00 (Mohon Nilai Diisi Dengan Angka Bulat)</h3>
			</center></b></h4>
			<!-- <h5><b><center> Mohon Nilai Diisi Dengan Angka Bulat (Bukan Pecahan) </center></b></h5> -->
		@else
			@if (($tgl >= $akhir))
				<h4><b><center> Mohon Maaf, Pengisian Nilai Sudah Ditutup !!! </center></b></h4>		
			@else
				<h4><b><center> Mohon Maaf, Pengisian Nilai Belum Dibuka !!! </center></b></h4>		
			@endif
		@endif
	@endif
	</span>

	
	@if (($level == 'baak') || ($level == 'admin')) 
		<div class="panel-body no-padding-hr">
		<div class="panel-footer">
		<div class="col-sm-offset-1 col-md-10"> 
			<button type="submit" name="save" id="save" class="btn btn-success btn-flat btn-block">
			<i class="fa fa-floppy-o"></i>&nbsp SIMPAN NILAI 1</button>
		</div></div></div>
	@else
		@if (($tgl >= $mulai) && ($tgl <= $akhir)) 
			<div class="panel-body no-padding-hr">
			<div class="panel-footer">
			<div class="col-sm-offset-1 col-md-10"> 
				<button type="submit" name="save" id="save" class="btn btn-success btn-flat btn-block">
				<i class="fa fa-floppy-o"></i>&nbsp SIMPAN NILAI</button>
			</div></div></div>
		@endif
	@endif
</div></div>	
		
		
<div class="table-responsive">
	<table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="text-center col-md-1" rowspan="3" valign="middle" style="vertical-align:middle">NIM</th>
				<th class="text-center col-md-3" rowspan="3" style="vertical-align:middle" >Nama Mahasiswa</th>
				<th class="text-center" rowspan="3" style="vertical-align:middle">L/P</th>
				<th class="text-center" rowspan="3" style="vertical-align:middle">Angk</th>
				<th class="text-center" colspan="{{$komponen_nilai->count()}}">Nilai</th>
				<th class="text-center col-md-1" rowspan="3" style="vertical-align:middle">Nilai Akhir</th>
				<th class="text-center col-md-1" rowspan="3" style="vertical-align:middle">Nilai Huruf</th>
				<th class="text-center col-md-1" rowspan="3" style="vertical-align:middle">Nilai Bobot</th>					
			</tr>
			
			<tr>
				@foreach($komponen_nilai as $row)
					<th class="text-center">{{$row->nama}}</th>
				@endforeach
			</tr>

			<tr>
				@foreach($komponen_nilai as $row)
					<th class="text-center">{{$row->bobot}}%</th>
				@endforeach
			</tr>
			
			<tr>
				<!-- <th class="text-center" rowspan="2" style="vertical-align:middle">Akhir</th>
				<th class="text-center" rowspan="2" style="vertical-align:middle">Huruf</th>
				<th class="text-center" rowspan="2" style="vertical-align:middle">Bobot</th>
				-->
			</tr>
		</thead>

		<tbody>
			@foreach($list_mhs as $mhs)
				<tr>
					<td class="text-center" valign="middle">
					<input type="hidden" name="input[{{$mhs->id}}][id]" id="id_{{$mhs->id}}" value="{{$mhs->id}}">
					{{$mhs->nim}}
					</td>
			
					<td valign="middle">{{$mhs->mahasiswa->nama}}</td>
					<td class="text-center">{{$mhs->mahasiswa->jk->kode}}</td>
					<!-- <td class="text-center">{{$level}}</td> -->
					
					<td class="text-center">{{substr($mhs->mahasiswa->th_akademik->kode,0,4)}}</td>
					
					<!-- @if ($level <> 'staf') -->

					@if (($level == 'baak') || ($level == 'admin')) 
						@foreach($komponen_nilai as $row)
							<td class="text-center col-md-1">
								<input type="text" name="input[{{$mhs->id}}][{{$row->nama}}]" id="{{$row->nama}}_{{$mhs->id}}" 
								value="{{getNilai($mhs->id,$row->id)}}" class="form-control text-center" 
								onkeypress='return hanyaAngka(event)' onkeyup="hitungNilai({{$mhs->id}})">
							</td>
						@endforeach
					@else
						@foreach($komponen_nilai as $row)
							<td class="text-center col-md-1">
								<input type="text" name="input[{{$mhs->id}}][{{$row->nama}}]" id="{{$row->nama}}_{{$mhs->id}}" 
								value="{{getNilai($mhs->id,$row->id)}}" class="form-control text-center" 
								onkeypress='return hanyaAngka(event)' 
								onkeyup="hitungNilai({{$mhs->id}})" readonly="true"> 
							</td>
						@endforeach
					@endif
					
					<td class="text-center col-md-1">
					<input type="text" name="input[{{$mhs->id}}][nilai_akhir]" id="nilai_akhir_{{$mhs->id}}" 
					value="{{!empty($mhs->nilai_akhir)? $mhs->nilai_akhir:null}}" class="form-control text-center" readonly="true">
					</td>
					
					<td class="text-center col-md-1">
					<input type="text" name="input[{{$mhs->id}}][nilai_huruf]" id="nilai_huruf_{{$mhs->id}}" 
					value="{{!empty($mhs->nilai_huruf)? $mhs->nilai_huruf:null}}" class="form-control text-center" readonly="true">
					</td>
					
					<td class="text-center col-md-1">
					<input type="text" name="input[{{$mhs->id}}][nilai_bobot]" id="nilai_bobot_{{$mhs->id}}" 
					value="{{!empty($mhs->nilai_bobot)? $mhs->nilai_bobot:null}}" class="form-control text-center" readonly="true">
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
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
