<div class="panel-body no-padding-hr">
	<div class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Tahun Akademik:</label>
			<div class="col-sm-2">
				{!! Form::hidden('th_akademik_id',$th_akademik->id,['class' => 'form-control',
				'id'=>'th_akademik_id','required'=>'true','readonly' => 'true']) !!}
				
				{!! Form::text('th_akademik_kode',$th_akademik->kode,['class' => 'form-control',
				'id'=>'th_akademik_kode','required'=>'true','readonly' => 'true']) !!}
				
				@if ($errors->has('th_akademik_id'))
					<span class="help-block">
					<strong>{{ $errors->first('th_akademik_id') }}</strong></span>
				@endif
			</div>
			
			<label class="col-sm-1 control-label">Tanggal:</label>
			<div class="col-sm-2">
				{!! Form::text('tanggal',date('d-m-Y'),['class' => 'form-control',
				'id'=>'tanggal','required'=>'true','readonly' => 'true']) !!}
				
				@if ($errors->has('tanggal'))
					<span class="help-block">
					<strong>{{ $errors->first('tanggal') }}</strong></span>
				@endif
			</div>
			
			<label class="col-sm-1 control-label">SKS Maks:</label>
			<div class="col-sm-1">
				{!! Form::text('max_sks',env('MAX_SKS',24),['class' => 'form-control',
				'id'=>'max_sks','required'=>'true','readonly' => 'true']) !!}
			
				@if ($errors->has('max_sks'))
				<span class="help-block">
				<strong>{{ $errors->first('max_sks') }}</strong></span>
				@endif
			</div>
		</div>
	</div>

	<div class="form-group{{ $errors->has('nim') ? ' has-error' : '' }}
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">NIM / Nama:</label>
			<div class="col-sm-7">
				<select class="form-control" name="nim" id="nim" autofocus="true" placeholder="Cari Mahasiswa...">
				<option value="">Cari NIM / Mahasiswa...</option>
				@foreach($list_mhs as $mhs)
					{{$select = (old('nim')==$mhs->nim?'selected':(!empty($nim)?($nim==$mhs->nim?'selected':null):null))}}
					<option value="{{$mhs->nim}}" {{$select}}>{{$mhs->nim}} - {{$mhs->nama}}</option>
				@endforeach
				</select>

				@if(!empty($data->nim))
					<span class="text-danger"><b>NIM jangan di Edit.</b></span>
				@endif

				@if ($errors->has('nim'))
					<span class="help-block">
					<strong>{{ $errors->first('nim') }}</strong></span>
				@endif
			</div>
		</div>
	</div>

	<div class="form-group{{ $errors->has('jenis_kelamin') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Jenis Kelamin:</label>
			<div class="col-sm-1">
				{!! Form::text('jenis_kelamin',null,['class' => 'form-control',
				'id'=>'jenis_kelamin','required'=>'true','readonly' => 'true']) !!}
				
				@if ($errors->has('jenis_kelamin'))
					<span class="help-block">
					<strong>{{ $errors->first('jenis_kelamin') }}</strong></span>
				@endif
			</div>

			<label class="col-sm-1 control-label">Status:</label>
			<div class="col-sm-1">
				{!! Form::text('status',null,['class' => 'form-control',
				'id'=>'status','required'=>'true','readonly' => 'true']) !!}
				
				@if ($errors->has('status'))
					<span class="help-block">
					<strong>{{ $errors->first('status') }}</strong></span>
				@endif
			</div>

			<label class="col-sm-1 control-label">Angkatan:</label>
			<div class="col-sm-1">
				{!! Form::text('th_angkatan',null,['class' => 'form-control',
				'id'=>'th_angkatan','required'=>'true','readonly' => 'true']) !!}
				@if ($errors->has('th_angkatan'))
					<span class="help-block">
					<strong>{{ $errors->first('th_angkatan') }}</strong></span>
				@endif
			</div>
			
			<label class="col-sm-1 control-label">Kelompok:</label>
			<div class="col-sm-1">
				{!! Form::hidden('kelompok_id',null,['class' => 'form-control',
				'id'=>'kelompok_id','required'=>'true','readonly' => 'true']) !!}
				
				{!! Form::text('kelompok',null,['class' => 'form-control',
				'id'=>'kelompok','required'=>'true','readonly' => 'true']) !!}
		
				@if ($errors->has('kelompok'))
				<span class="help-block">
				<strong>{{ $errors->first('kelompok') }}</strong></span>
				@endif
			</div>			
		</div>
	</div>

	<div class="form-group{{ $errors->has('nama_prodi') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Program Studi:</label>
			<div class="col-sm-7">
				{!! Form::hidden('prodi_id',null,['class' => 'form-control',
				'id'=>'prodi_id','required'=>'true','readonly' => 'true']) !!}
				
				{!! Form::text('nama_prodi',null,['class' => 'form-control',
				'id'=>'nama_prodi','required'=>'true','readonly' => 'true']) !!}

				@if ($errors->has('nama_prodi'))
				<span class="help-block">
				<strong>{{ $errors->first('nama_prodi') }}</strong></span>
				@endif
			</div>	
		</div>
	</div>

	<div class="form-group{{ $errors->has('semester') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Semester:</label>
			<div class="col-sm-1">
				{!! Form::text('smt',null,['class' => 'form-control',
				'id'=>'smt','required'=>'true','readonly' => 'true']) !!}
				
				@if ($errors->has('smt'))
				<span class="help-block">
				<strong>{{ $errors->first('smt') }}</strong></span>
				@endif
			</div>
				
			<label class="col-sm-1 control-label">SKS Total:</label>
			<div class="col-sm-1">
				{!! Form::text('sks_total',null,['class' => 'form-control',
				'id'=>'sks_total','required'=>'true','readonly' => 'true']) !!}
			
				@if ($errors->has('sks_total'))
				<span class="help-block">
				<strong>{{ $errors->first('sks_total') }}</strong></span>
				@endif
			</div>
		
			<label class="col-sm-1 control-label">Kelas:</label>
			<div class="col-sm-3">
				{!! Form::hidden('kelas_id',null,['class' => 'form-control',
				'id'=>'kelas_id','required'=>'true','readonly' => 'true']) !!}
				
				{!! Form::text('nama_kelas',null,['class' => 'form-control',
				'id'=>'nama_kelas','required'=>'true','readonly' => 'true']) !!}

				@if ($errors->has('nama_kelas'))
				<span class="help-block">
				<strong>{{ $errors->first('nama_kelas') }}</strong></span>
				@endif
			</div>				
		</div>
	</div>

	<div class="form-group{{ $errors->has('keuangan') ? ' has-error' : '' }} 
		no-margin-hr panel-padding-h no-padding-t no-border-t">
		<div class="row">
			<label class="col-sm-2 control-label">Keuangan:</label>
			<div class="col-sm-7">
				{!! Form::text('keuangan',null,['class' => 'form-control',
				'id'=>'keuangan','required'=>'true','readonly' => 'true']) !!}
			
				@if ($errors->has('keuangan'))
				<span class="help-block">
				<strong>{{ $errors->first('keuangan') }}</strong></span>
				@endif
			</div>		
		</div>
	</div>
</div>

<div class="panel-footer">
	<div class="col-sm-offset-2">
	<button type="submit" name="save" id="save" class="btn btn-success btn-flat">
	<i class="fa fa-floppy-o"></i> Simpan</button>
	</div>
</div>

@include($folder.'/listmk')

@push('demo')
<script>
init.push(function () {
	$("#nim").select2({
		allowClear: true,
		placeholder: "Pilih NIM"
	});
});
</script>
@endpush

@push('scripts')
<script type="text/javascript">
var dataTable = $("#serversideTable").DataTable({
	processing	: true,
	serverSide	: true,
	paging		: false,
	"searching"	: false,
	ajax : {
        url	 : "{{url($redirect)}}"+'/getDataMK',
		data : function (d) {
			d.th_akademik_id 	= $("#th_akademik_id").val();
			d.prodi_id 			= $("#prodi_id").val();
			d.kelas_id 			= $("#kelas_id").val();
			d.kelompok_id 		= $("#kelompok_id").val();
			d.nim 				= $("#nim").val();
        }
	},
	columns: [
		{ data: 'cek_list', name: 'cek_list','class':'text-center','orderable':false, 'searchable':false},
		{ data: 'kode_mk', 	name: 'kode_mk','class':'text-center'},
		{ data: 'nama_mk', 	name: 'nama_mk'},
		{ data: 'sks_mk', 	name: 'sks_mk','class':'text-center','orderable':false},
		{ data: 'smt_mk', 	name: 'smt_mk','class':'text-center'},
		{ data: 'kelompok', name: 'kelompok','class':'text-center','orderable':false},
		{ data: 'dosen', 	name: 'dosen'},
		{ data: 'ruang', 	name: 'ruang','class':'text-center','orderable':false},
		{ data: 'sisa', 	name: 'sisa','class':'text-center','orderable':false},
		{ data: 'hari', 	name: 'hari','class':'text-center'},
		{ data: 'waktu', 	name: 'waktu','class':'text-center','orderable':false},
	],
	"order": [[ 4, "ASC" ],[1,'ASC']]
});

getMhs();

$("#nim").on('change',function(){
	getMhs();
});

function getMhs() {
	var string = {
		nim		: $("#nim").val(),
		_token	: "{{ csrf_token() }}"
	};
	$.ajax({
		url	   	: "{{ url($redirect."/getMhs") }}",
		method	: 'POST',
		data	: string,
		success:function(data){
			$("#jenis_kelamin").val(data.jenis_kelamin);
			$("#status").val(data.status);
			$("#smt").val(data.smt);
			$("#th_angkatan").val(data.th_angkatan);
			
			if(data.prodi){
				$("#prodi_id").val(data.prodi.id);
				$("#nama_prodi").val(data.prodi.nama);
			}else{
				$("#prodi_id").val('');
				$("#nama_prodi").val('');
			}
			
			if(data.kelas){
				$("#kelas_id").val(data.kelas.id);
				$("#nama_kelas").val(data.kelas.nama);
			}else{
				$("#kelas_id").val('');
				$("#nama_kelas").val('');
			}
			
			if(data.kelompok){
				$("#kelompok_id").val(data.kelompok.id);
				$("#kelompok").val(data.kelompok.kode);
			}else{
				$("#kelompok_id").val('');
				$("#kelompok").val('');
			}

			$("#sks_total").val(data.sks_total);
			$("#keuangan").val(data.keuangan);
			
			if(data.keuangan){
 				$("#save").show();
			}else{
				$("#save").hide();
			}
			
			dataTable.draw();
		}
	});
}
</script>
@endpush
