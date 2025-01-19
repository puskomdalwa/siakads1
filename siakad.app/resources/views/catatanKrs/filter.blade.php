<form class="form-horizontal form-borderd">
    <div class="panel-body no-padding-hr">
        <div class="form-group  no-margin-hr panel-padding-h no-padding-t no-border-t">
            <div class="row">
                <label class="col-sm-2 control-label text-danger">Tahun Akademik:</label>
                <div class="col-sm-2">
                    <select class="form-control" name="th_akademik_id" id="th_akademik_id"
                        onchange="document.getElementById('filter').click();">
                        <option value="">-Pilih-</option>
                        @foreach ($ta_thakademik as $row)
                            @php $select = $th_akademik_id==$row->id ? 'selected' : ''; @endphp
                            <option value="{{ $row->id }}" {{ $select }}>{{ $row->kode }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('th_akademik_id'))
                        <span class="help-block"><strong>{{ $errors->first('th_akademik_id') }}</strong></span>
                    @endif
                </div>

                <label class="col-sm-2 control-label">Kelas:</label>
                <div class="col-sm-2">
                    <select class="form-control" name="kelas_id" id="kelas_id"
                        onchange="document.getElementById('filter').click();">
                        @if (empty($kelas_id))
                            <option value="">-Pilih-</option>
                        @endif

                        @foreach ($list_kelas as $kelas)
                            <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('kelas_id'))
                        <span class="help-block"><strong>{{ $errors->first('kelas_id') }}</strong></span>
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
            <div class="row">
                <label class="col-sm-2 control-label">Program Studi:</label>
                <div class="col-sm-8">
                    <select class="form-control" name="prodi_id" id="prodi_id"
                        onchange="document.getElementById('filter').click();">
                        @if (empty($prodi_id))
                            <option value="">-Pilih-</option>
                        @endif

                        @foreach ($list_prodi as $prodi)
                            <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('prodi_id'))
                        <span class="help-block"><strong>{{ $errors->first('prodi_id') }}</strong></span>
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
            <div class="row">
                <label class="col-sm-2 control-label">Jenis Kelamin:</label>
                <div class="col-sm-8">
                    <select class="form-control" name="jk_id" id="jk_id"
                        onchange="document.getElementById('filter').click();">
                        <option value="semua">-Pilih-</option>
                        <option value="8">Laki Laki</option>
                        <option value="9">Perempuan</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                    <button type="button" name="filter" id="filter" class="btn btn-danger btn-flat">
                        <i class="fa fa-filter"></i> Filter</button>
                    @if (request()->routeIs('catatanKrs.harusKrs'))
                        <button type="button" name="excel" id="excel" class="btn btn-success btn-flat">
                            <i class="fa fa-print"></i> Export Excel</button>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!--
 <div class="panel-footer text-center">
 <div class="col-sm-offset-0">
  <button type="button" name="filter" id="filter" class="btn btn-danger btn-flat">
  <i class="fa fa-filter"></i> Filter</button>
 </div></div>
 -->

</form>
