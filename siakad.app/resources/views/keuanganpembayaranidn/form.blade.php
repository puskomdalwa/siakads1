<div class="note note-warning">
	<h4 class="note-title">Perhatian</h4>
	Transaksi Pembayaran Mahasiswa akan mengubah status mahasiswa menjadi <b>AKTIF</b>.<br/>
	Silahkan masukan <b>NIM</b> dan Klik tombol <b>CARI</b>.
	
	@if (Session::has('id'))
		{{ \Illuminate\Support\Facades\Session::get('id') }}
	@endif
</div>

<div class="panel-body no-padding-hr">
	<div class="form-group{{ $errors->has('nomor') ? ' has-error' : '' }} 
	no-margin-hr panel-padding-h no-padding-t no-border-t">
	<div class="row">
		<label class="col-sm-2 control-label">Nomor:</label>
		<div class="col-sm-1">
			{!! Form::text('nomor',$nomor,['class' => 'form-control',
			'id'=>'nomor','required'=>'true','readonly'=>true]) !!}
			
			@if ($errors->has('nomor'))
				<span class="help-block">
				<strong>{{ $errors->first('nomor') }}</strong></span>
			@endif
		</div>
		
		<label class="col-sm-1 control-label">Tanggal:</label>
		<div class="col-sm-2">
			<div class="input-group">
				{!! Form::text('tanggal',!empty($data->tanggal)?tgl_str($data->tanggal):date('d-m-Y'),
				['class' => 'form-control','id'=>'tanggal','required'=>'true']) !!}
				
				<span class="input-group-addon bg-primary no-border">
				<i class="fa fa-calendar"></i></span>
			</div>

			@if ($errors->has('tanggal'))
				<span class="help-block">
				<strong>{{ $errors->first('tanggal') }}</strong></span>
			@endif
		</div>

		<label class="col-sm-1 control-label">Taka:</label>
		<div class="col-sm-1">
			<select class="form-control" name="th_akademik_id" id="th_akademik_id" required>
			{{-- <option value="">-Pilih-</option> --}}
			@foreach($list_thakademik as $thakademik)
				{{$select = (old('th_akademik_id')==$thakademik->id?'selected':(!empty($data->th_akademik_id)?($data->th_akademik_id==$thakademik->id?'selected':''):''))}}
				<option value="{{$thakademik->id}}" {{$select}}>{{$thakademik->kode}}</option>
			@endforeach
			</select>
	
			@if ($errors->has('th_akademik_id'))
				<span class="help-block">
				<strong>{{ $errors->first('th_akademik_id') }}</strong></span>
			@endif
		</div>
	</div></div>

	<div class="form-group{{ $errors->has('nim') ? ' has-error' : '' }} 
	no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">NIM:</label>
		<div class="col-sm-4">
			<div class="input-group">
				{!! Form::text('nim',null,['class' => 'form-control',
				'id'=>'nim','required'=>'true','autofocus' => 'true']) !!}
				
				<span class="input-group-btn">
				<button type="button" name="cariMhs" id="cariMhs" class="btn btn-primary">
				<i class="fa fa-search"></i> Cari</button></span>
			</div>

			@if ($errors->has('nim'))
				<span class="help-block">
				<strong>{{ $errors->first('nim') }}</strong></span>
			@endif
		</div>
		
		<label class="col-sm-1 control-label">Nama :</label>
		<div class="col-sm-5">
			{!! Form::text('nama_mhs',null,['class' => 'form-control',
			'id'=>'nama_mhs','required'=>'true','readonly' => 'true']) !!}
			
			@if ($errors->has('nama_mhs'))
				<span class="help-block">
				<strong>{{ $errors->first('nama_mhs') }}</strong></span>
			@endif
		</div>
    </div></div>
				
	<div class="form-group{{ $errors->has('nim') ? ' has-error' : '' }} 
	no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">		
		<!--
		<label class="col-sm-1 control-label">Nama :</label>
		<div class="col-sm-5">
			{!! Form::text('nama_mhs',null,['class' => 'form-control',
			'id'=>'nama_mhs','required'=>'true','readonly' => 'true']) !!}
			@if ($errors->has('nama_mhs'))
				<span class="help-block">
				<strong>{{ $errors->first('nama_mhs') }}</strong></span>
			@endif
		</div>
		-->
    </div></div>

	<div class="form-group{{ $errors->has('nama_prodi') ? ' has-error' : '' }} 
	no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Program Studi:</label>
		<div class="col-sm-5">
			{!! Form::text('nama_prodi',null,['class' => 'form-control',
			'id'=>'nama_prodi','required'=>'true','readonly' => 'true']) !!}
			
			@if ($errors->has('nama_prodi'))
				<span class="help-block">
				<strong>{{ $errors->first('nama_prodi') }}</strong></span>
			@endif
		</div>	
    </div></div>

	<div class="form-group{{ $errors->has('tagihan_id') ? ' has-error' : '' }} 
	no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Angkatan:</label>
		<div class="col-sm-1">
			{!! Form::text('angkatan',null,['class' => 'form-control text-center',
			'id'=>'angkatan','required'=>'true','readonly' => 'true']) !!}
			
			@if ($errors->has('angkatan'))
				<span class="help-block">
				<strong>{{ $errors->first('angkatan') }}</strong></span>
			@endif
		</div>

		<label class="col-sm-1 control-label">Kelas:</label>
		<div class="col-sm-1">
			{!! Form::text('nama_kelas',null,['class' => 'form-control',
			'id'=>'nama_kelas','required'=>'true','readonly' => 'true']) !!}
			
			@if ($errors->has('nama_kelas'))
				<span class="help-block">
				<strong>{{ $errors->first('nama_kelas') }}</strong></span>
			@endif
		</div>
		
		<label class="col-sm-1 control-label">Semester:</label>
		<div class="col-sm-1">
			{!! Form::text('smt',null,['class' => 'form-control text-center',
			'id'=>'smt','required'=>'true','readonly' => 'true']) !!}
			
			@if ($errors->has('smt'))
            <span class="help-block">
			<strong>{{ $errors->first('smt') }}</strong>
            </span>
			@endif
		</div>
	</div></div>
	
	<div class="form-group{{ $errors->has('tagihan_id') ? ' has-error' : '' }} 
	no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Tagihan:</label>
		<div class="col-sm-5">
			<select class="form-control" name="tagihan_id" id="tagihan_id" required>
			<option value="">-Pilih-</option>
			</select>
			
			@if ($errors->has('tagihan_id'))
				<span class="help-block">
				<strong>{{ $errors->first('tagihan_id') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('jml_sks') ? ' has-error' : '' }} 
	no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Jumlah SKS:</label>
		<div class="col-sm-1">
			{!! Form::number('jml_sks',0,['class' => 'form-control text-right',
			'id'=>'jml_sks','required'=>true]) !!}
			
			@if ($errors->has('jml_sks'))
				<span class="help-block">
				<strong>{{ $errors->first('jml_sks') }}</strong></span>
			@endif
		</div>

		<label class="col-sm-1 control-label">Kali SKS:</label>
		<div class="col-sm-1">
			{!! Form::text('x_sks',null,['class' => 'form-control text-center',
			'id'=>'x_sks','readonly'=>true,'required'=>true]) !!}
			
			@if ($errors->has('x_sks'))
				<span class="help-block">
				<strong>{{ $errors->first('x_sks') }}</strong></span>
			@endif
		</div>
    </div></div>

	<div class="form-group{{ $errors->has('jumlah_tagihan') ? ' has-error' : '' }} 
	no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
		<label class="col-sm-2 control-label">Jumlah Tagihan:</label>
		<div class="col-sm-2">
			{!! Form::hidden('tmp_jumlah_tagihan',null,['class' => 'form-control text-right',
			'id'=>'tmp_jumlah_tagihan','readonly'=>true,'required'=>true]) !!}
			
			{!! Form::text('jumlah_tagihan',null,['class' => 'form-control text-right',
			'id'=>'jumlah_tagihan','readonly'=>true,'required'=>true]) !!}
			
			@if ($errors->has('jumlah_tagihan'))
				<span class="help-block">
				<strong>{{ $errors->first('jumlah_tagihan') }}</strong></span>
			@endif
		</div>

		<label class="col-sm-1 control-label">Dibayar:</label>
		<div class="col-sm-2">
			{!! Form::number('jumlah',null,['class' => 'form-control text-right',
			'id'=>'jumlah','required'=>'true']) !!}
			
			@if ($errors->has('jumlah'))
				<span class="help-block">
				<strong>{{ $errors->first('jumlah') }}</strong></span>
			@endif
		</div>
    </div></div>
</div>

<div class="panel-footer">
<div class="col-sm-offset-2">
	<button type="submit" name="save" id="save" class="btn btn-success btn-flat">
	<i class="fa fa-floppy-o"></i> Simpan</button>
	
	@if(!empty(old('nomor')))
		@php
		$nomor = old('nomor');
		@endphp
		
		<a href="{{url($folder.'/'.$nomor.'/cetakKwitansi')}}"
		name="cetak" id="cetak" class="btn btn-info btn-flat">
		<i class="fa fa-print"></i> Cetak</a>
	@endif
	
	<a href="{{url($folder.'/create')}}" class="btn btn-warning btn-flat"> 
	<i class="fa fa-file-o"></i> Baru</a>
</div></div>

@push('demo')
<script>
init.push(function () {
	$('#switchers-colors-square > input').switcher({ theme: 'square' });
	var options = {
		todayBtn: "linked",
		orientation: $('body').hasClass('right-to-left') ? "auto right" : 'auto auto',
		format: "dd-mm-yyyy"
	}
	$('#tanggal').datepicker(options);

	$("#cariMhs").on('click',function(){
		// swal('Testing','test button','success')
		if(!$("#nim").val()){
			swal('Peringatan..!!','NIM tidak boleh kosong','warning');
			$("#nim").focus();
			return false;
		}
		getMhs();
	});

	function getMhs(){
		var string = {
			nim	   : $("#nim").val(),
			_token : "{{ csrf_token() }}"
		};
		$.ajax({
			url    : "{{ url($redirect."/getMhs") }}",
			method : 'POST',
			data   : string,
			success:function(data){
				$("#nama_mhs").val(data.nama_mhs);
				$("#angkatan").val(data.angkatan);
				$("#nama_prodi").val(data.nama_prodi);
				$("#nama_kelas").val(data.nama_kelas);
				$("#tagihan_id").html(data.list_tagihan);
				$("#smt").val(data.smt);
			}
		});
	}

	$("#tagihan_id").on('change',function(){
		getJmlTagihan();
	});
	
	function getJmlTagihan(){
		var jml_sks = $("#jml_sks").val() || 0;
		var string  = {
			nim : $("#nim").val(),
			tagihan_id : $("#tagihan_id").val(),
			_token: "{{ csrf_token() }}"
		};
		$.ajax({
			url    : "{{ url($redirect."/getJmlTagihan") }}",
			method : 'POST',
			data   : string,
			success:function(data){
				$("#x_sks").val(data.x_sks);
				$("#jml_sks").val(1);
				$("#jumlah_tagihan").val(data.jumlah_tagihan);
				$("#tmp_jumlah_tagihan").val(data.jumlah_tagihan);
			}
		});
	}

	$("#jml_sks").on('keyup',function(){
		var x_sks		   = $("#x_sks").val();
		var jml_sks		   = $("#jml_sks").val() || 1;
		var jumlah_tagihan = $("#tmp_jumlah_tagihan").val();
		
		if(x_sks=='Y'){
			var jml_tagihan = parseFloat(jml_sks) * parseFloat(jumlah_tagihan);
		}else{
			var jml_tagihan = jumlah_tagihan;
		}
		$("#jumlah_tagihan").val(jml_tagihan);
	});
});
</script>
@endpush
