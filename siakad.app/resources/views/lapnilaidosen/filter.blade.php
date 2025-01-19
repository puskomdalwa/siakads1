<form class="form-horizontal form-borderd" name="form-input" id="form-input" action="{{ route($redirect . '.cetak') }}"
    method="post" target="blank">
    {{ csrf_field() }}

    <div class="panel-body no-padding-hr">
        <div
            class="form-group{{ $errors->has('prodi_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
            <div class="row">
                <label class="col-sm-2 control-label">Program Studi:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="prodi_id" id="prodi_id">
                        @if (empty($prodi_id))
                            <option value="">-Semua Program Studi-</option>
                        @endif
                        @foreach ($list_prodi as $prodi)
                            <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($errors->has('prodi_id'))
                    <span class="help-block"><strong>{{ $errors->first('prodi_id') }}</strong></span>
                @endif

                <label class="col-sm-1 control-label">Status:</label>
                <div class="col-sm-2">
                    <select class="form-control" name="status_id" id="status_id">
                        <option value="">Semua-</option>
                        @foreach ($list_status as $row)
                            <option value="{{ $row->id }}">{{ $row->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row" style="margin-top: 10px">
                <label class="col-sm-2 control-label">Tahun Akademik:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="th_akademik_id" id="th_akademik_id">
                        @foreach ($th_akademik as $ta)
                            <option value="{{ $ta->id }}"
                                {{ $ta->id == $th_akademik_aktif->id ? 'selected' : '' }}>{{ $ta->nama }} /
                                {{ $ta->semester }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-5">
                    <button type="button" name="tampil" id="tampil" onclick="getData()" class="btn btn-primary btn-flat">
                        <i class="fa fa-print"></i> Tampilkan</button>
                    <button type="submit" name="cetak" id="cetak" class="btn btn-info btn-flat">
                        <i class="fa fa-print"></i> Cetak</button>
                </div>
            </div>
        </div>
    </div>

    <!--
  <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
  <div class="row">
   <label class="col-sm-2 control-label">Status:</label>
   <div class="col-sm-3">
   <select class="form-control" name="status_id" id="status_id">
   <option value="">-All-</option>
   @foreach ($list_status as $row)
<option value="{{ $row->id }}">{{ $row->nama }}</option>
@endforeach
   </select>
   </div>
  </div></div>
  -->
    </div>

    <!--
 <div class="panel-footer text-center">
 <button type="button" name="filter" id="filter" class="btn btn-danger btn-flat">
  <i class="fa fa-filter"></i> Filter</button>
  
  <button type="submit" name="cetak" id="cetak" class="btn btn-info btn-flat">
  <i class="fa fa-print"></i> Print</button>
 </div>
 -->
</form>
