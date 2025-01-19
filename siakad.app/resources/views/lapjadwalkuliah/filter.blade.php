<form class="form-horizontal form-borderd" name="form-input" id="form-input" action="{{ url($redirect . '/cetak') }}"
    method="post" target="blank">
    {{ csrf_field() }}

    <div class="panel-body no-padding-hr">
        <div
            class="form-group{{ $errors->has('prodi_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
            <div class="row">
                <label class="col-sm-4 control-label">Program Studi:</label>
                <div class="col-sm-4">
                    <select class="form-control" name="prodi_id" id="prodi_id" required>
                        @if (empty($prodi_id))
                            <option value="">-Pilih-</option>
                        @endif
                        @foreach ($list_prodi as $prodi)
                            <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
            <br>
            <div class="row">
                <label class="col-sm-4 control-label">Tahun Akademik:</label>
                <div class="col-sm-4">
                    <select class="form-control" name="th_akademik_id" id="th_akademik_id" required>
                        @if (empty($th_akademik_id))
                            <option value="">-Pilih-</option>
                        @endif
                        @foreach ($list_tahun_akademik as $thakademik)
                            <option value="{{ $thakademik->id }}">{{ $thakademik->nama }} - {{ $thakademik->semester }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-1">
                    <button type="submit" name="cetak" id="cetak" value="excel" class="btn btn-info btn-flat">
                        <i class="fa fa-th"></i> Cetak </button>
                </div>
                <div class="col-sm-1">
                    <button type="button" name="excel" id="excel" value="export"
                        class="btn btn-success btn-flat">
                        <i class="fa fa-th"></i> Excel </button>
                </div>
            </div>
        </div>
    </div>

    <!--
    <div class="panel-footer text-center">
  <button type="button" name="filter" id="filter" class="btn btn-danger btn-flat">
  <i class="fa fa-filter"></i> Filter </button>
  
  <button type="submit" name="cetak" id="cetak" value="excel" class="btn btn-info btn-flat">
  <i class="fa fa-th"></i> Print </button>
    </div>
 -->
</form>
