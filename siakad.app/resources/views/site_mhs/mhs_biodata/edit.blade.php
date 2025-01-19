@extends('layouts.app')
@section('title', 'Edit Biodata')

@section('content')
    <div class="panel widget-messages-alt panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title"><i class="panel-title-icon fa fa-envelope"></i>Edit Biodata</span>
        </div> <!-- / .panel-heading -->

        <form method="POST" action="{{ route('biodata.mhs.update') }}" class="form-horizontal form-bordered">
            {{ csrf_field() }}
            <div class="panel-body no-padding-hr">

                <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                    <div class="row">
                        <label class="col-sm-2 control-label">Tahun Akademik:</label>
                        <div class="col-sm-2">
                            <input class="form-control" id="th_akademik" required="true" name="th_akademik" type="text"
                                value="{{ $data->th_akademik->kode }}" readonly>
                            <input class="form-control" id="th_akademik_id" required="true" name="th_akademik_id"
                                type="hidden" value="{{ $data->th_akademik->id }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                    <div class="row">
                        <label class="col-sm-2 control-label">Status:</label>
                        <div class="col-sm-2">
                            <input class="form-control" id="status" required="true" name="status" type="text"
                                value="{{ $data->status->nama }}" readonly>
                            <input class="form-control" id="status" required="true" name="status_id" type="hidden"
                                value="{{ $data->status->id }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                    <div class="row">
                        <label class="col-sm-2 control-label">Tanggal Masuk:</label>
                        <div class="col-sm-2">
                            <input class="form-control" id="tanggal_masuk" value="{{ $data->tanggal_masuk }}"
                                name="tanggal_masuk" type="text" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                    <div class="row">
                        <label class="col-sm-2 control-label">NIM:</label>
                        <div class="col-sm-2">
                            <input class="form-control" id="nim" value="{{ $data->nim }}" name="nim"
                                type="text" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                    <div class="row">
                        <label class="col-sm-2 control-label">NIK:</label>
                        <div class="col-sm-2">
                            <input class="form-control" id="nik" name="nik" type="number"
                                value="{{ $data->nik }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                    <div class="row">
                        <label class="col-sm-2 control-label">Nama Lengkap:</label>
                        <div class="col-sm-4">
                            <input class="form-control" id="nama" name="nama" value="{{ $data->nama }}"
                                type="text" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                    <div class="row">
                        <label class="col-sm-2 control-label">Jenis Kelamin:</label>
                        <div class="col-sm-2">
                            <select class="form-control" name="jk_id" id="jk_id" required>
                                <option value="">-Pilih-</option>
                                @foreach ($list_jk as $jk)
                                    {{ $select = old('jk_id') == $jk->id ? 'selected' : '' }}
                                    {{ $select = !empty($data->jk_id) ? ($data->jk_id == $jk->id ? 'selected' : '') : '' }}
                                    <option value="{{ $jk->id }}" {{ $select }}>{{ $jk->nama }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </div>

                <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                    <div class="row">
                        <label class="col-sm-2 control-label">Tempat Lahir:</label>
                        <div class="col-sm-4">
                            <input class="form-control" id="tempat_lahir" name="tempat_lahir"
                                value="{{ $data->tempat_lahir }}" type="text" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                    <div class="row">
                        <label class="col-sm-2 control-label">Tanggal Lahir:</label>
                        <div class="col-sm-2">
                            <input class="form-control" id="tanggal_lahir" name="tanggal_lahir" type="text"
                                value="{{ tgl_str($data->tanggal_lahir) }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                    <div class="row">
                        <label class="col-sm-2 control-label">Agama:</label>
                        <div class="col-sm-2">
                            <select class="form-control" name="agama_id" id="agama_id">
                                <option value="">-Pilih-</option>
                                @foreach ($list_agama as $agama)
                                    {{ $select = old('agama_id') == $agama->id ? 'selected' : '' }}
                                    {{ $select = !empty($data->agama_id) ? ($data->agama_id == $agama->id ? 'selected' : '') : '' }}
                                    <option value="{{ $agama->id }}" {{ $select }}>{{ $agama->nama }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </div>

                <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                    <div class="row">
                        <label class="col-sm-2 control-label">Alamat:</label>
                        <div class="col-sm-9">
                            <input class="form-control" id="alamat" name="alamat" type="text"
                                value="{{ $data->alamat }}">
                        </div>
                    </div>
                </div>

                <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                    <div class="row">
                        <label class="col-sm-2 control-label">Kota:</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="kota_id" id="kota_id">
                                <option value="">-Pilih-</option>
                                @foreach ($list_kota as $kota)
                                    {{ $select = old('kota_id') == $kota->id ? 'selected' : '' }}
                                    {{ $select = !empty($data->kota_id) ? ($data->kota_id == $kota->id ? 'selected' : '') : '' }}
                                    <option value="{{ $kota->id }}" {{ $select }}>{{ $kota->name }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </div>

                <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                    <div class="row">
                        <label class="col-sm-2 control-label">Email:</label>
                        <div class="col-sm-4">
                            <input class="form-control" id="email" name="email" type="email"
                                value="{{ $data->email }}">
                        </div>
                    </div>
                </div>

                <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                    <div class="row">
                        <label class="col-sm-2 control-label">HP:</label>
                        <div class="col-sm-4">
                            <input class="form-control" id="hp" name="hp" type="number"
                                value="{{ $data->hp }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-footer">
                <div class="col-sm-offset-2">
                    <button type="submit" class="btn btn-success btn-flat">
                        <i class="fa fa-floppy-o"></i> Simpan</button>
                </div>
            </div>

        </form>
    </div>
@endsection
@push('demo')
    <script>
        init.push(function() {
            var options = {
                todayBtn: "linked",
                orientation: $('body').hasClass('right-to-left') ? "auto right" : 'auto auto',
                format: "dd-mm-yyyy"
            }

            $('#tanggal_lahir').datepicker(options);

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
