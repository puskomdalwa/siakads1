@extends('layouts.app')
@section('title', 'Edit Biodata')

@section('content')
    <div class="panel widget-messages-alt panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title"><i class="panel-title-icon fa fa-envelope"></i>Edit Biodata</span>
        </div> <!-- / .panel-heading -->

        <form method="POST" action="{{ route('biodata.dosen.update') }}" class="form-horizontal form-bordered">
            {{ csrf_field() }}
            <div class="panel-body no-padding-hr">

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
                        <label class="col-sm-2 control-label">Kode:</label>
                        <div class="col-sm-2">
                            <input class="form-control" id="kode" value="{{ $data->kode }}" name="kode"
                                type="text" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                    <div class="row">
                        <label class="col-sm-2 control-label">NIDN:</label>
                        <div class="col-sm-2">
                            <input class="form-control" id="nidn" name="nidn" type="number"
                                value="{{ $data->nidn }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                    <div class="row">
                        <label class="col-sm-2 control-label">Nama Lengkap:</label>
                        <div class="col-sm-4">
                            <input class="form-control" id="nama" name="nama" value="{{ $data->nama }}"
                                type="text">
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
                        <label class="col-sm-2 control-label">Tampat Lahir:</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="tempat_lahir" id="tempat_lahir" required>
                                <option value="">-Pilih-</option>
                                @foreach ($list_kota as $kota)
                                    {{ $select = old('tempat_lahir') == $kota->name ? 'selected' : '' }}
                                    {{ $select = !empty($data->tempat_lahir) ? ($data->tempat_lahir == $kota->name ? 'selected' : '') : '' }}
                                    <option value="{{ $kota->name }}" {{ $select }}>{{ $kota->name }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </div>

                <div class="form-group no-margin-hr panel-padding-h no-padding-t no-border-t">
                    <div class="row">
                        <label class="col-sm-2 control-label">Tanggal Lahir:</label>
                        <div class="col-sm-2">
                            <input class="form-control" id="tanggal_lahir" name="tanggal_lahir" type="text"
                                value="{{ tgl_str($data->tanggal_lahir) }}">
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
                            <select class="form-control" name="kota" id="kota_id" required>
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
