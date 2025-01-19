<div class="panel-body no-padding-hr">
    <div
        class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Tahun Angkatan:</label>
            <div class="col-sm-2">
                <select class="form-control" name="th_akademik_id" id="th_akademik_id" required>
                    <option value="">-Pilih-</option>
                    @foreach ($list_thakademik as $thakademik)
                        {{ $select =
                            old('th_akademik_id') == $thakademik->id
                                ? 'selected'
                                : (!empty($data->th_akademik_id)
                                    ? ($data->th_akademik_id == $thakademik->id
                                        ? 'selected'
                                        : null)
                                    : null) }}
                        <option value="{{ $thakademik->id }}" {{ $select }}>{{ $thakademik->kode }}</option>
                    @endforeach
                </select>

                @if ($errors->has('th_akademik_id'))
                    <span class="help-block"><strong>{{ $errors->first('th_akademik_id') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('prodi_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Program Studi:</label>
            <div class="col-sm-4">
                <select class="form-control" name="prodi_id" id="prodi_id" required>
                    @if (empty($prodi_id))
                        <option value="">-Pilih-</option>
                    @endif

                    @foreach ($list_prodi as $prodi)
                        {{ $select =
                            old('prodi_id') == $prodi->id
                                ? 'selected'
                                : (!empty($data->prodi_id)
                                    ? ($data->prodi_id == $prodi->id
                                        ? 'selected'
                                        : null)
                                    : null) }}
                        <option value="{{ $prodi->id }}" {{ $select }}>{{ $prodi->nama }}</option>
                    @endforeach
                </select>

                @if ($errors->has('prodi_id'))
                    <span class="help-block"><strong>{{ $errors->first('prodi_id') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('kelas_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Kelas:</label>
            <div class="col-sm-2">
                <select class="form-control" name="kelas_id" id="kelas_id" required>
                    {{-- <option value="">-Pilih-</option> --}}
                    @foreach ($list_kelas as $kelas)
                        {{-- {{$select = !empty($data->kelas_id)?$data->kelas_id==$kelas->id?'selected':'':''}} --}}
                        {{ $select =
                            old('kelas_id') == $kelas->id
                                ? 'selected'
                                : (!empty($data->kelas_id)
                                    ? ($data->kelas_id == $kelas->id
                                        ? 'selected'
                                        : null)
                                    : null) }}
                        <option value="{{ $kelas->id }}" {{ $select }}>{{ $kelas->nama }}</option>
                    @endforeach
                </select>

                @if ($errors->has('kelas_id'))
                    <span class="help-block"><strong>{{ $errors->first('kelas_id') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('status_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Status:</label>
            <div class="col-sm-2">
                <select class="form-control" name="status_id" id="status_id" required>
                    {{-- <option value="">-Pilih-</option> --}}
                    @foreach ($list_status as $status)
                        {{-- {{$select = !empty($data->status_id)?$data->status_id==$status->id?'selected':'':''}} --}}
                        {{ $select =
                            old('status_id') == $status->id
                                ? 'selected'
                                : (!empty($data->status_id)
                                    ? ($data->status_id == $status->id
                                        ? 'selected'
                                        : null)
                                    : null) }}
                        <option value="{{ $status->id }}" {{ $select }}>{{ $status->nama }}</option>
                    @endforeach
                </select>

                @if ($errors->has('status_id'))
                    <span class="help-block"><strong>{{ $errors->first('status_id') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('tanggal_masuk') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Tanggal Masuk:</label>
            <div class="col-sm-2">
                {!! Form::text('tanggal_masuk', !empty($data->tanggal_masuk) ? tgl_str($data->tanggal_masuk) : null, [
                    'class' => 'form-control',
                    'id' => 'tanggal_masuk',
                ]) !!}

                @if ($errors->has('tanggal_masuk'))
                    <span class="help-block"><strong>{{ $errors->first('tanggal_masuk') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('nim') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">NIM:</label>
            <div class="col-sm-2">
                {!! Form::text('nim', null, [
                    'class' => 'form-control',
                    'id' => 'nim',
                    'required' => 'true',
                    'autofocus' => 'true',
                ]) !!}
                @if ($errors->has('nim'))
                    <span class="help-block"><strong>{{ $errors->first('nim') }}</strong></span>
                @endif
            </div>
        </div>
    </div>
    <hr>

    <div
        class="form-group{{ $errors->has('nik') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">NIK:</label>
            <div class="col-sm-2">
                {!! Form::number('nik', null, ['class' => 'form-control', 'id' => 'nik']) !!}
                @if ($errors->has('nik'))
                    <span class="help-block"><strong>{{ $errors->first('nik') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('nama') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Nama Lengkap:</label>
            <div class="col-sm-5">
                {!! Form::text('nama', null, ['class' => 'form-control', 'id' => 'nama', 'required' => 'true']) !!}
                @if ($errors->has('nama'))
                    <span class="help-block"><strong>{{ $errors->first('nama') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('jk_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Jenis Kelamin:</label>
            <div class="col-sm-2">
                <select class="form-control" name="jk_id" id="jk_id" required>
                    <option value="">-Pilih-</option>
                    @foreach ($list_jk as $jk)
                        {{-- {{$select = !empty($data->jk_id)?$data->jk_id==$jk->id?'selected':'':''}} --}}
                        {{ $select =
                            old('jk_id') == $jk->id
                                ? 'selected'
                                : (!empty($data->jk_id)
                                    ? ($data->jk_id == $jk->id
                                        ? 'selected'
                                        : null)
                                    : null) }}
                        <option value="{{ $jk->id }}" {{ $select }}>{{ $jk->nama }}</option>
                    @endforeach
                </select>

                @if ($errors->has('jk_id'))
                    <span class="help-block"><strong>{{ $errors->first('jk_id') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('tempat_lahir') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Tampat Lahir:</label>
            <div class="col-sm-4">
                {!! Form::text('tempat_lahir', null, ['class' => 'form-control', 'id' => 'tempat_lahir']) !!}
                @if ($errors->has('tempat_lahir'))
                    <span class="help-block"><strong>{{ $errors->first('tempat_lahir') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('tanggal_lahir') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Tanggal Lahir:</label>
            <div class="col-sm-2">
                {!! Form::text('tanggal_lahir', !empty($data->tanggal_lahir) ? tgl_str($data->tanggal_lahir) : null, [
                    'class' => 'form-control',
                    'id' => 'tanggal_lahir',
                ]) !!}

                @if ($errors->has('tanggal_lahir'))
                    <span class="help-block"><strong>{{ $errors->first('tanggal_lahir') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('agama_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Agama:</label>
            <div class="col-sm-3">
                <select class="form-control" name="agama_id" id="agama_id" required>
                    <option value="">-Pilih-</option>
                    @foreach ($list_agama as $agama)
                        {{-- {{$select = !empty($data->agama_id)?$data->agama_id==$agama->id?'selected':'':''}} --}}
                        {{ $select =
                            old('agama_id') == $agama->id
                                ? 'selected'
                                : (!empty($data->agama_id)
                                    ? ($data->agama_id == $agama->id
                                        ? 'selected'
                                        : null)
                                    : null) }}
                        <option value="{{ $agama->id }}" {{ $select }}>{{ $agama->nama }}</option>
                    @endforeach
                </select>

                @if ($errors->has('agama_id'))
                    <span class="help-block"><strong>{{ $errors->first('agama_id') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('alamat') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Alamat:</label>
            <div class="col-sm-9">
                {!! Form::text('alamat', null, ['class' => 'form-control', 'id' => 'alamat']) !!}
                @if ($errors->has('alamat'))
                    <span class="help-block"><strong>{{ $errors->first('alamat') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('kota_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Kota:</label>
            <div class="col-sm-4">
                <select class="form-control" name="kota_id" id="kota_id">
                    <option value="">-Pilih-</option>
                    @foreach ($list_kota as $kota)
                        {{-- {{$select = !empty($data->kota_id)?$data->kota_id==$kota->id?'selected':'':''}} --}}
                        {{ $select =
                            old('kota_id') == $kota->id
                                ? 'selected'
                                : (!empty($data->kota_id)
                                    ? ($data->kota_id == $kota->id
                                        ? 'selected'
                                        : null)
                                    : null) }}
                        <option value="{{ $kota->id }}" {{ $select }}>{{ $kota->name }}</option>
                    @endforeach
                </select>

                @if ($errors->has('kota_id'))
                    <span class="help-block"><strong>{{ $errors->first('kota_id') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('email') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Email:</label>
            <div class="col-sm-4">
                {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email']) !!}
                @if ($errors->has('email'))
                    <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('hp') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">HP:</label>
            <div class="col-sm-4">
                {!! Form::number('hp', null, ['class' => 'form-control', 'id' => 'hp']) !!}
                @if ($errors->has('hp'))
                    <span class="help-block"><strong>{{ $errors->first('hp') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('spm') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">SPM:</label>
            <div class="col-sm-4">
                <select class="form-control" name="spm" id="spm">
                    <option value="tidak" {{ old('spm') == 'tidak' ? 'selected' : '' }}
                        {{ !empty($data->spm) ? ($data->spm == 'tidak' ? 'selected' : null) : null }}>Tidak</option>
                    <option value="iya" {{ old('spm') == 'iya' ? 'selected' : '' }}
                        {{ !empty($data->spm) ? ($data->spm == 'iya' ? 'selected' : null) : null }}>Iya</option>
                </select>

                @if ($errors->has('kota_id'))
                    <span class="help-block"><strong>{{ $errors->first('kota_id') }}</strong></span>
                @endif
            </div>
        </div>
    </div>
    <div
        class="form-group{{ $errors->has('izin') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Izin:</label>
            <div class="col-sm-4">
                <select class="form-control" name="izin" id="izin">
                    <option value="">Pilih sakit / izin</option>
                    <option value="izin" {{ old('izin') == 'izin' ? 'selected' : '' }}
                        {{ !empty($data->izin) ? ($data->izin == 'izin' ? 'selected' : null) : null }}>Izin</option>
                    <option value="sakit" {{ old('izin') == 'sakit' ? 'selected' : '' }}
                        {{ !empty($data->izin) ? ($data->izin == 'sakit' ? 'selected' : null) : null }}>Sakit</option>
                </select>

                @if ($errors->has('kota_id'))
                    <span class="help-block"><strong>{{ $errors->first('kota_id') }}</strong></span>
                @endif
            </div>
        </div>
    </div>
    <hr>

    <div
        class="form-group{{ $errors->has('nik_ayah') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">NIK Ayah:</label>
            <div class="col-sm-3">
                {!! Form::number('nik_ayah', null, ['class' => 'form-control', 'id' => 'nik_ayah']) !!}
                @if ($errors->has('nik_ayah'))
                    <span class="help-block"><strong>{{ $errors->first('nik_ayah') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('nama_ayah') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Nama Ayah:</label>
            <div class="col-sm-5">
                {!! Form::text('nama_ayah', null, ['class' => 'form-control', 'id' => 'nama_ayah']) !!}
                @if ($errors->has('nama_ayah'))
                    <span class="help-block"><strong>{{ $errors->first('nama_ayah') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('nik_ibu') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">NIK Ibu:</label>
            <div class="col-sm-3">
                {!! Form::number('nik_ibu', null, ['class' => 'form-control', 'id' => 'nik_ibu']) !!}
                @if ($errors->has('nik_ibu'))
                    <span class="help-block"><strong>{{ $errors->first('nik_ibu') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('nama_ibu') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Nama Ibu:</label>
            <div class="col-sm-5">
                {!! Form::text('nama_ibu', null, ['class' => 'form-control', 'id' => 'nama_ibu']) !!}
                @if ($errors->has('nama_ibu'))
                    <span class="help-block"><strong>{{ $errors->first('nama_ibu') }}</strong></span>
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

@push('demo')
    <script>
        init.push(function() {
            var options = {
                todayBtn: "linked",
                orientation: $('body').hasClass('right-to-left') ? "auto right" : 'auto auto',
                format: "dd-mm-yyyy"
            }
            $('#tanggal_lahir').datepicker(options);
            $('#tanggal_masuk').datepicker(options);

            $("#kota_id").select2({
                allowClear: true,
                placeholder: "Pilih Kota"
            });

            $("#tempat_lahir").select2({
                allowClear: true,
                placeholder: "Pilih Kota"
            });
        });
    </script>
@endpush
