<div class="panel-body no-padding-hr">
	<div class="form-group{{ $errors->has('nim') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">NIM / Nama Mahasiswa:</label>
		<div class="col-sm-6">
			<select class="form-control" name="nim" id="nim">
			<option value="">Cari NIM atau Nama...</option>
				@foreach($list_mhs as $mhs)
					{{$select = (old('nim')==$mhs->nim ? 'selected':(!empty($data->nim) ? 
					($data->nim==$mhs->nim ? 'selected':null):null))}}
					<option value="{{$mhs->nim}}" {{$select}}>{{$mhs->nim}} - {{$mhs->nama}} </option>
				@endforeach
			</select>
			
			@if ($errors->has('nim'))
				<span class="help-block"><strong>{{ $errors->first('nim') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">Jenis Kelamin:</label>
		<div class="col-sm-2">
			{!! Form::text('jk',null,['class' => 'form-control','id'=>'jk','required'=>'true','readonly' => 'true']) !!}
		</div>

		<label class="col-sm-2 control-label">Status:</label>
		<div class="col-sm-2">
			{!! Form::text('status',null,['class' => 'form-control','id'=>'status','required'=>'true','readonly' => 'true']) !!}
		</div>
	</div></div>

	<div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">Tahun Angkatan:</label>
		<div class="col-sm-1">
			{!! Form::text('th_angkatan',null,['class' => 'form-control','id'=>'th_angkatan','required'=>'true','readonly' => 'true']) !!}
		</div>

		<label class="col-sm-3 control-label">Program Studi:</label>
		<div class="col-sm-3">
			{!! Form::text('prodi',null,['class' => 'form-control','id'=>'prodi','required'=>'true','readonly' => 'true']) !!}
		</div>
	</div></div>

	<div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">Kelas:</label>
		<div class="col-sm-2">
			{!! Form::text('kelas',null,['class' => 'form-control','id'=>'kelas','required'=>'true','readonly' => 'true']) !!}
		</div>
		
		<label class="col-sm-2 control-label">Kelompok:</label>
		<div class="col-sm-1">
			{!! Form::text('kelompok',null,['class' => 'form-control','id'=>'kelompok','required'=>'true','readonly' => 'true']) !!}
		</div>
	</div></div>

	<div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">Keuangan:</label>
		<div class="col-sm-5">
		{!! Form::text('keuangan',null,['class' => 'form-control','id'=>'keuangan','required'=>'true','readonly' => 'true']) !!}
		</div>
	</div></div>

	<div class="form-group{{ $errors->has('jml_sks') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">Total SKS:</label>
		<div class="col-sm-1">
			{!! Form::text('jml_sks',null,['class' => 'form-control','id'=>'jml_sks','required'=>'true']) !!}
			@if ($errors->has('jml_sks'))
				<span class="help-block"><strong>{{ $errors->first('jml_sks') }}</strong></span>
			@endif
		</div>

		<label class="col-sm-1 control-label">IPK:</label>
		<div class="col-sm-1">
			{!! Form::text('ipk',null,['class' => 'form-control','id'=>'ipk','required'=>'true']) !!}
			@if ($errors->has('ipk'))
				<span class="help-block"><strong>{{ $errors->first('ipk') }}</strong></span>
			@endif
		</div>
	</div></div>

	<div class="form-group{{ $errors->has('judul_skripsi') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">Judul Skripsi:</label>
		<div class="col-sm-7">
			{!! Form::textarea('judul_skripsi',null,['class' => 'form-control','id'=>'judul_skripsi','required'=>'true']) !!}
			@if ($errors->has('judul_skripsi'))
				<span class="help-block">
					<strong>{{ $errors->first('judul_skripsi') }}</strong>
				</span>
			@endif
		</div>
	</div></div>

	<div class="form-group{{ $errors->has('ukuran_toga') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">Ukuran Toga:</label>
		<div class="col-sm-2">
			<select class="form-control" name="ukuran_toga" id="ukuran_toga" required>
			<option value="">-Pilih-</option>
			@foreach($list_toga as $toga)
				{{$select = (old('ukuran_toga')==$toga->kode?'selected':(!empty($data->ukuran_toga)?($data->ukuran_toga==$toga->kode?'selected':null):null))}}
				<option value="{{$toga->kode}}" {{$select}}>{{$toga->kode}}</option>
			@endforeach
			</select>
			
			@if ($errors->has('ukuran_toga'))
				<span class="help-block"><strong>{{ $errors->first('ukuran_toga') }}</strong></span>
			@endif
		</div>
	</div></div>

	<div class="form-group{{ $errors->has('motto') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">Motto:</label>
		<div class="col-sm-5">
			{!! Form::text('motto',null,['class' => 'form-control','id'=>'motto']) !!}
			@if ($errors->has('motto'))
				<span class="help-block"><strong>{{ $errors->first('motto') }}</strong></span>
			@endif
		</div>
	</div></div>

	<div class="form-group{{ $errors->has('tgl_sk_yudisium') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">Tanggal SK Yudisium:</label>
		<div class="col-sm-2">
			{!! Form::text('tgl_sk_yudisium',!empty($data->tgl_sk_yudisium)?tgl_str($data->tgl_sk_yudisium):null,['class' => 'form-control','id'=>'tgl_sk_yudisium']) !!}
			@if ($errors->has('tgl_sk_yudisium'))
				<span class="help-block"><strong>{{ $errors->first('tgl_sk_yudisium') }}</strong></span>
			@endif
		</div>

		<label class="col-sm-2 control-label">SK Yudisium:</label>
		<div class="col-sm-3">
			{!! Form::text('sk_yudisium',null,['class' => 'form-control','id'=>'sk_yudisium']) !!}
			@if ($errors->has('sk_yudisium'))
				<span class="help-block">
				<strong>{{ $errors->first('sk_yudisium') }}</strong>
				</span>
			@endif
		</div>
	</div></div>

	<div class="form-group{{ $errors->has('nomor_seri_ijazah') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">Nomor Seri Ijazah:</label>
		<div class="col-sm-4">
			{!! Form::text('nomor_seri_ijazah',null,['class' => 'form-control','id'=>'nomor_seri_ijazah']) !!}
			@if ($errors->has('nomor_seri_ijazah'))
				<span class="help-block">
				<strong>{{ $errors->first('nomor_seri_ijazah') }}</strong>
				</span>
			@endif
		</div>
	</div></div>

	<div class="panel-footer">
	<div class="col-sm-offset-2">
		<button type="submit" name="save" id="save" class="btn btn-success btn-flat">
		<i class="fa fa-floppy-o"></i> Simpan</button>
	</div></div>
</div>

@push('demo')
<script type="text/javascript">
init.push(function (){
	// swal('test','test','success');
	$("#nim").select2({
		allowClear: true,
		placeholder: "Cari NIM atau Nama..."
	});

	var options = {
		todayBtn: "linked",
		orientation: $('body').hasClass('right-to-left') ? "auto right" : 'auto auto',
		format: "dd-mm-yyyy"
	}

	$('#tgl_sk_yudisium').datepicker(options);

	getMhs();
  
	$("#nim").on('change',function(){
		getMhs();
	});

	function getMhs(){
		var string = {
			nim : $("#nim").val(),
			_token: "{{ csrf_token() }}"
		};

		// console.log(string);
		$.ajax({
			url   : "{{ url($folder.'/getMhs') }}",
			method : 'POST',
			data : string,
			success:function(data){
				$("#jk").val(data.jk);
				$("#status").val(data.status);
				$("#th_angkatan").val(data.th_angkatan);
				$("#prodi").val(data.prodi);
				$("#kelas").val(data.kelas);
				$("#kelompok").val(data.kelompok);
				$("#keuangan").val(data.keuangan);

				if(!data.keuangan){
					$("#save").hide();
				}else{
					$("#save").show();
				}
			}
		});
	}
});
</script>
@endpush

@push('scripts')
<script type="text/javascript">
// getMhs();
// swal('test','test','success');

</script>
@endpush
