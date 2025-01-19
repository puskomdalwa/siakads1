<div class="note note-info">
    <h4 class="note-title">Informasi Checklist</h4>
    Icon <i class="fa fa-square-o"></i> = Data siap dipilih.<br />
    Icon <i class="fa fa-times-circle text-danger"></i> = Data siap dihapus.<br />
    Icon <i class="fa fa-check-square-o text-success"></i> = Sudah ada link dengan Jadwal. Data tidak boleh
    dihapus.<br />
</div>

<div class="panel-body no-padding-hr">
    <input type="hidden" name="kurikulum_id" id="kurikulum_id" value="{{ !empty($data->id) ? $data->id : null }}">

    <div
        class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-3 control-label">Tahun Akademik:</label>
            <div class="col-sm-3">
                <select class="form-control" name="th_akademik_id" id="th_akademik_id" required>
                    <!-- <option value="">-Pilih-</option> -->
                    @foreach ($list_thakademik as $thakademik)
                        {{ $select = (!empty($data->th_akademik_id) ? ($data->th_akademik_id == $thakademik->id ? 'selected' : '') : old('th_akademik_id') == $thakademik->id) ? 'selected' : '' }}
                        <option value="{{ $thakademik->id }}" {{ $select }}>{{ $thakademik->nama }} {{ $thakademik->semester }}</option>
                    @endforeach
                </select>

                @if ($errors->has('th_akademik_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('th_akademik_id') }}</strong></span>
                @endif
            </div>

            <!-- <label class="col-sm-3 control-label">Nama Matakuliah yang diajarkan:</label> -->
            <label class="col-sm-1 control-label">Kurikulum:</label>
            <div class="col-sm-3">
                {!! Form::text('nama', null, [
                    'class' => 'form-control',
                    'id' => 'nama',
                    'required' => 'true',
                    'autofocus' => 'true',
                ]) !!}

                @if ($errors->has('nama'))
                    <span class="help-block">
                        <strong>{{ $errors->first('nama') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('nama') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-3 control-label">Program Studi:</label>
            <div class="col-sm-7">
                <select class="form-control" name="prodi_id" id="prodi_id" required>
                    <option value="">-Pilih-</option>
                    @foreach ($list_prodi as $prodi)
                        {{ $select = (!empty($data->prodi_id) ? ($data->prodi_id == $prodi->id ? 'selected' : '') : old('prodi_id') == $prodi->id) ? 'selected' : '' }}
                        <option value="{{ $prodi->id }}" {{ $select }}>{{ $prodi->nama }}</option>
                    @endforeach
                </select>

                @if ($errors->has('prodi_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('prodi_id') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('th_angkatan_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-3 control-label">Tahun Angkatan:</label>
            <div class="col-sm-7">
                <select multiple="multiple" class="form-control" name="th_angkatan_id[]" id="th_angkatan_id">
                    @foreach ($list_thangkatan as $row)
                        @php
                            if (!empty($data->id)) {
                                $th_angkatan = App\KurikulumAngkatan::where('kurikulum_id', $data->id)
                                    ->where('th_akademik_id', $row->id)
                                    ->first();
                                $select = !empty($th_angkatan) ? 'selected' : null;
                            } else {
                                $select = null;
                            }
                        @endphp

                        <option value="{{ $row->id }}" {{ $select }}>{{ substr($row->kode, 0, 4) }}
                        </option>
                    @endforeach
                </select>

                @if ($errors->has('th_angkatan_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('th_angkatan_id') }}</strong></span>
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

@if ($errors->has('cek_list'))
    <div class="alert alert-danger alert-dismissable">
        <strong>{{ $errors->first('cek_list') }}</strong>
    </div>
@endif

@include($folder . '.listmk')

@push('demo')
    <script>
        init.push(function() {
            // Multiselect
            $("#th_angkatan_id").select2({
                placeholder: "Pilih Angkatan..."
            });

            $("#th_akademik_id").select2({
                placeholder: "Pilih..."
            });

            $("#prodi_id").select2({
                placeholder: "Pilih..."
            });
        });
    </script>
@endpush
