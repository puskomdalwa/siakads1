<div class="panel-body no-padding-hr">

	<div class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label text-danger">Tahun Akademik:</label>
      <div class="col-sm-1">
        <select class="form-control" name="th_akademik_id" id="th_akademik_id" required>
					@foreach($list_thakademik as $thakademik)
						{{$select = old('th_akademik_id')==$thakademik->id?'selected':''}}
						{{$select = !empty($data->th_akademik_id)?$data->th_akademik_id==$thakademik->id?'selected':'':''}}
            <option value="{{$thakademik->id}}" {{$select}}>{{$thakademik->kode}}</option>
          @endforeach
        </select>
        @if ($errors->has('th_akademik_id'))
            <span class="help-block">
                <strong>{{ $errors->first('th_akademik_id') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

	<div class="form-group{{ $errors->has('nim') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label text-danger">NIM:</label>
      <div class="col-sm-2">
				<div class="input-group">
        	{!! Form::text('nim',null,['class' => 'form-control','id'=>'nim','required'=>'true','autofocus' => 'true']) !!}
						<span class="input-group-btn">
							<button type="button" name="cariMhs" id="cariMhs" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
						</span>
				</div>
        @if ($errors->has('nim'))
            <span class="help-block">
                <strong>{{ $errors->first('nim') }}</strong>
            </span>
        @endif
      </div>
			<label class="col-sm-2 control-label text-danger">Nama Mahasiswa:</label>
      <div class="col-sm-5">
        {!! Form::text('nama_mhs',null,['class' => 'form-control','id'=>'nama_mhs','required'=>'true','readonly' => 'true']) !!}
        @if ($errors->has('nama_mhs'))
            <span class="help-block">
                <strong>{{ $errors->first('nama_mhs') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

	<div class="form-group{{ $errors->has('nama_prodi') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label text-danger">Program Studi:</label>
      <div class="col-sm-3">
        {!! Form::text('nama_prodi',null,['class' => 'form-control','id'=>'nama_prodi','readonly'=>true]) !!}
        @if ($errors->has('nama_prodi'))
            <span class="help-block">
                <strong>{{ $errors->first('nama_prodi') }}</strong>
            </span>
        @endif
      </div>
			<label class="col-sm-2 control-label text-danger">Kelas:</label>
      <div class="col-sm-2">
        {!! Form::text('nama_kelas',null,['class' => 'form-control','id'=>'nama_kelas','readonly'=>true]) !!}
        @if ($errors->has('nama_kelas'))
            <span class="help-block">
                <strong>{{ $errors->first('nama_kelas') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

	<div class="form-group{{ $errors->has('keterangan') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label text-danger">Keterangan:</label>
      <div class="col-sm-8">
        {!! Form::text('keterangan',null,['class' => 'form-control','id'=>'keterangan']) !!}
        @if ($errors->has('keterangan'))
            <span class="help-block">
                <strong>{{ $errors->first('keterangan') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>


</div>
<div class="panel-footer">
  <div class="col-sm-offset-2">
      <button type="submit" name="save" id="save" class="btn btn-success btn-flat"><i class="fa fa-floppy-o"></i> Simpan</button>
  </div>
</div>

@push('demo')
<script>
	init.push(function () {
		$("#form_schadule_id").select2({
			allowClear: true,
			placeholder: "Pilih Kota"
		});

		$("#cariMhs").on('click',function(){
			// swal('Testing','test button','success')
			if(!$("#nim").val())
			{
				swal('Peringatan..!!','NIM tidak boleh kosong','warning');
				$("#nim").focus();
				return false;
			}
			getMhs();
		});

		function getMhs()
		{
			var string = {
	      nim : $("#nim").val(),
	      _token: "{{ csrf_token() }}"
	    };
	    $.ajax({
	      url   : "{{ url($redirect."/getMhs") }}",
	      method : 'POST',
	      data : string,
	      success:function(data){
	        $("#nama_mhs").val(data.nama_mhs);
					$("#nama_prodi").val(data.nama_prodi);
					$("#nama_kelas").val(data.nama_kelas);
	      }
	    });
		}
	});
</script>
@endpush
