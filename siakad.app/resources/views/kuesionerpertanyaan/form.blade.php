<div class="panel-body no-padding-hr">

  <div class="form-group{{ $errors->has('pertanyaan') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Pertanyaan:</label>
      <div class="col-sm-6">
        {!! Form::text('pertanyaan',null,['class' => 'form-control','id'=>'pertanyaan','required'=>'true','autofocus' => 'true']) !!}
        @if ($errors->has('pertanyaan'))
            <span class="help-block">
                <strong>{{ $errors->first('pertanyaan') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

  <div class="form-group{{ $errors->has('aktif') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Aktif:</label>
      <div class="col-sm-4">
        <label class="checkbox-inline">
          <input type="checkbox" name="aktif" id="aktif" class="px" {{!empty($data->aktif)?'checked':null}} >
          <span class="lbl">Ya</span>
        </label>
        @if ($errors->has('aktif'))
            <span class="help-block">
                <strong>{{ $errors->first('aktif') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </div>

  <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
    <div class="row">
      <label class="col-sm-2 control-label">Pilihan:</label>
      <div class="col-sm-10">
        <div class="table-responsive">
          <table class="table table-bordered" id="dynamic_field">
            @if(!empty($data->pilihan))
              @php
              $no=0;
              @endphp
              @foreach($data->pilihan as $row)
              <tr id="row{{$no}}" class="dynamic-added">
                  <td class="col-md-4"><input type="text" value="{{$row->pilihan}}" name="input[pilihan][]" placeholder="Masukan Pilihan..." class="form-control name_list" /></td>
                  <td class="col-md-1"><input type="text" value="{{$row->nilai}}" name="input[nilai][]" placeholder="Nilai.."  class="form-control name_list" /></td>
                  @if($no==0)
                    <td class="col-md-1"><button type="button" name="add" id="add" class="btn btn-success"> <i class="fa fa-plus"></i> Tambah</button></td>
                  @else
                    <td class="col-md-1"><button type="button" name="remove" id="{{$no}}" class="btn btn-danger btn_remove"> <i class="fa fa-trash-o"></i> Hapus</button></td>
                  @endif
              </tr>
              @php
              $no++;
              @endphp
              @endforeach
              <input type="hidden" name="jml_input" id="jml_input" value="{{count($data->pilihan)}}">
            @else
              <tr>
                  <td class="col-md-4"><input type="text" name="input[pilihan][]" placeholder="Masukan Pilihan..." class="form-control name_list" /></td>
                  <td class="col-md-1"><input type="number" name="input[nilai][]" placeholder="Nilai.."  class="form-control name_list" /></td>
                  <td class="col-md-1"><button type="button" name="add" id="add" class="btn btn-success"> <i class="fa fa-plus"></i> Tambah</button></td>
              </tr>
              <input type="hidden" name="jml_input" id="jml_input" value="1">
            @endif
          </table>
        </div>
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

	});
</script>
@endpush

@push('scripts')
  <script type="text/javascript">
  // swal('test','test','success');
  var i= parseFloat($("#jml_input").val());

  $('#add').click(function(){
       i++;
       var outputHtml = '<tr id="row'+i+'" class="dynamic-added">';
       outputHtml = outputHtml+'<td class="col-md-4"><input type="text" name="input[pilihan][]" placeholder="Masukan Pilihan..." class="form-control name_list" /></td>';
       outputHtml = outputHtml+'<td class="col-md-1"><input type="number" name="input[nilai][]" placeholder="Nilai.."  class="form-control name_list" /></td>';
       outputHtml = outputHtml+'<td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove"><i class="fa fa-trash-o"></i> Hapus</button></td>';
       outputHtml = outputHtml+'</tr>';
       $('#dynamic_field').append(outputHtml);
  });

  $(document).on('click', '.btn_remove', function(){
     var button_id = $(this).attr("id");
     $('#row'+button_id+'').remove();
   });
  </script>
@endpush
