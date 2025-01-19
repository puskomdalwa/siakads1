@php
    date_default_timezone_set('Asia/Jakarta');
    //$batas = mktime(date("d"),date("m"),date("Y"));

    $tgl = date('Y-m-d H:i:s');
    $tgl_mulai = $buka_form->tgl_mulai;
    $tgl_selesai = $buka_form->tgl_selesai;

    //$level = strtolower(Auth::user()->level->level);

@endphp

<div class="panel-body no-padding-hr">
    <div
        class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Tahun Akademik:</label>
            <div class="col-sm-2">
                {!! Form::hidden('th_akademik_id', $th_akademik->id, [
                    'class' => 'form-control',
                    'id' => 'th_akademik_id',
                    'required' => 'true',
                    'readonly' => 'true',
                ]) !!}

                {!! Form::text('th_akademik_kode', $th_akademik->kode, [
                    'class' => 'form-control',
                    'id' => 'th_akademik_kode',
                    'required' => 'true',
                    'readonly' => 'true',
                ]) !!}

                @if ($errors->has('th_akademik_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('th_akademik_id') }}</strong></span>
                @endif
            </div>

            <label class="col-sm-1 control-label">Semester:</label>
            <div class="col-sm-1">
                {!! Form::text('smt', null, [
                    'class' => 'form-control text-center',
                    'id' => 'smt',
                    'required' => 'true',
                    'readonly' => 'true',
                ]) !!}

                @if ($errors->has('smt'))
                    <span class="help-block">
                        <strong>{{ $errors->first('smt') }}</strong></span>
                @endif
            </div>

            <label class="col-sm-2 control-label">Tanggal:</label>
            <div class="col-sm-2">
                {!! Form::text('tanggal', date('d-m-Y'), [
                    'class' => 'form-control',
                    'id' => 'tanggal',
                    'required' => 'true',
                    'readonly' => 'true',
                ]) !!}

                @if ($errors->has('tanggal'))
                    <span class="help-block">
                        <strong>{{ $errors->first('tanggal') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('nim') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">NIM:</label>
            <div class="col-sm-2">
                {!! Form::text('nim', $nim, [
                    'class' => 'form-control',
                    'id' => 'nim',
                    'required' => 'true',
                    'readonly' => 'true',
                ]) !!}

                @if (!empty($data->nim))
                    <span class="text-danger"><b>NIM jangan di Edit.</b></span>
                @endif

                @if ($errors->has('nim'))
                    <span class="help-block">
                        <strong>{{ $errors->first('nim') }}</strong></span>
                @endif
            </div>

            <label class="col-sm-1 control-label">Angkatan:</label>
            <div class="col-sm-1">
                {!! Form::text('th_angkatan', null, [
                    'class' => 'form-control text-center',
                    'id' => 'th_angkatan',
                    'required' => 'true',
                    'readonly' => 'true',
                ]) !!}

                @if ($errors->has('th_angkatan'))
                    <span class="help-block">
                        <strong>{{ $errors->first('th_angkatan') }}</strong></span>
                @endif
            </div>

            <label class="col-sm-2 control-label">Status:</label>
            <div class="col-sm-2">
                {!! Form::text('status', null, [
                    'class' => 'form-control',
                    'id' => 'status',
                    'required' => 'true',
                    'readonly' => 'true',
                ]) !!}

                @if ($errors->has('status'))
                    <span class="help-block">
                        <strong>{{ $errors->first('status') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('jenis_kelamin') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Jenis Kelamin:</label>
            <div class="col-sm-2">
                {!! Form::text('jenis_kelamin', null, [
                    'class' => 'form-control',
                    'id' => 'jenis_kelamin',
                    'required' => 'true',
                    'readonly' => 'true',
                ]) !!}

                @if ($errors->has('jenis_kelamin'))
                    <span class="help-block">
                        <strong>{{ $errors->first('jenis_kelamin') }}</strong></span>
                @endif
            </div>

            <label class="col-sm-1 control-label">Kelompok:</label>
            <div class="col-sm-2">
                {!! Form::hidden('kelompok_id', null, [
                    'class' => 'form-control',
                    'id' => 'kelompok_id',
                    'required' => 'true',
                    'readonly' => 'true',
                ]) !!}

                {!! Form::text('kelompok', null, [
                    'class' => 'form-control',
                    'id' => 'kelompok',
                    'required' => 'true',
                    'readonly' => 'true',
                ]) !!}

                @if ($errors->has('kelompok'))
                    <span class="help-block">
                        <strong>{{ $errors->first('kelompok') }}</strong>
                    </span>
                @endif
            </div>

            <label class="col-sm-1 control-label">Kelas:</label>
            <div class="col-sm-2">
                {!! Form::hidden('kelas_id', null, [
                    'class' => 'form-control',
                    'id' => 'kelas_id',
                    'required' => 'true',
                    'readonly' => 'true',
                ]) !!}

                {!! Form::text('nama_kelas', null, [
                    'class' => 'form-control',
                    'id' => 'nama_kelas',
                    'required' => 'true',
                    'readonly' => 'true',
                ]) !!}

                @if ($errors->has('nama_kelas'))
                    <span class="help-block">
                        <strong>{{ $errors->first('nama_kelas') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('nama_prodi') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Program Studi:</label>
            <div class="col-sm-8">
                {!! Form::hidden('prodi_id', null, [
                    'class' => 'form-control',
                    'id' => 'prodi_id',
                    'required' => 'true',
                    'readonly' => 'true',
                ]) !!}

                {!! Form::text('nama_prodi', null, [
                    'class' => 'form-control',
                    'id' => 'nama_prodi',
                    'required' => 'true',
                    'readonly' => 'true',
                ]) !!}

                @if ($errors->has('nama_prodi'))
                    <span class="help-block">
                        <strong>{{ $errors->first('nama_prodi') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('keuangan') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">Keuangan:</label>
            <div class="col-sm-8">
                {!! Form::text('keuangan', null, [
                    'class' => 'form-control',
                    'id' => 'keuangan',
                    'required' => 'true',
                    'readonly' => 'true',
                ]) !!}

                @if ($errors->has('keuangan'))
                    <span class="help-block">
                        <strong>{{ $errors->first('keuangan') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div
        class="form-group{{ $errors->has('max_sks') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
        <div class="row">
            <label class="col-sm-2 control-label">SKS Maksimal:</label>
            <div class="col-sm-1">
                {!! Form::text('max_sks', env('MAX_SKS', 24), [
                    'class' => 'form-control text-center',
                    'id' => 'max_sks',
                    'required' => 'true',
                    'readonly' => 'true',
                ]) !!}

                @if ($errors->has('max_sks'))
                    <span class="help-block">
                        <strong>{{ $errors->first('max_sks') }} .'/'.
                            {{ $errors->first('sks_total') }}</strong></span>
                @endif
            </div>

            <label class="col-sm-2 control-label">SKS Total:</label>
            <div class="col-sm-1">
                {!! Form::text('sks_total', null, [
                    'class' => 'form-control text-center',
                    'id' => 'sks_total',
                    'required' => 'true',
                    'readonly' => 'true',
                ]) !!}

                @if ($errors->has('sks_total'))
                    <span class="help-block">
                        <strong>{{ $errors->first('sks_total') }}</strong></span>
                @endif
            </div>
        </div>
    </div>
</div>


@if ($tgl >= $tgl_mulai && $tgl <= $tgl_selesai)
    @include($folder . '.listmk')

    <div class="panel-footer">
        <div class="col-sm-offset-0">
            <button type="submit" name="save" id="save" class="btn btn-success btn-flat btn-block">
                <i class="fa fa-floppy-o"></i> SIMPAN KRS</button>
        </div>
    </div>

    <center>
        <span class="label label-warning">Jika tombol SIMPAN tidak terlihat, silahkan Anda hubungi Bagian Keuangan.
            Kemungkinan Anda belum melaksanakan Kewajiban Keuangan atau input KRS belum dimulai.</span>
    </center>
@endif

@if ($errors->count() > 0)
    <div id="ERROR_COPY" style="display:none" class="alert alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }} </br>
        @endforeach
    </div>
@endif

@push('demo')
    <!-- <script></script> -->
@endpush

@push('scripts')
    <script type="text/javascript">
        var has_errors = {{ $errors->count() > 0 ? 'true' : 'false' }};
        if (has_errors) {
            swal({
                title: 'Errors',
                type: 'error',
                html: jQuery("#ERROR_COPY").html(),
                showCloseButton: true,
            });
        }

        var dataTable = $("#serversideTable").DataTable({
            processing: true,
            serverSide: true,
            paging: false,
            "searching": false,
            ajax: {
                url: "{{ url($redirect) }}" + '/getDataMK',
                data: function(d) {
                    d.th_akademik_id = $("#th_akademik_id").val();
                    d.prodi_id = $("#prodi_id").val();
                    d.kelas_id = $("#kelas_id").val();
                    d.kelompok_id = $("#kelompok_id").val();
                    d.nim = $("#nim").val();
                }
            },
            createdRow: function(row, data, dataIndex) {
                $('td:eq(5)', row).attr('id', 'kelompok_' + data.id);
            },
            columns: [{
                    data: 'cek_list',
                    name: 'cek_list',
                    'class': 'text-center valign-middle',
                    'orderable': false,
                    'searchable': false
                },
                {
                    data: 'kode_mk',
                    name: 'kode_mk',
                    'class': 'text-center valign-middle'
                },
                {
                    data: 'nama_mk',
                    name: 'nama_mk',
                    'class': 'valign-middle'
                },
                {
                    data: 'sks_mk',
                    name: 'sks_mk',
                    'class': 'text-center valign-middle',
                    'orderable': false
                },
                {
                    data: 'smt_mk',
                    name: 'smt_mk',
                    'class': 'text-center valign-middle'
                },
                {
                    data: 'kelompok',
                    name: 'kelompok',
                    'class': 'text-center valign-middle',
                    'orderable': false
                },
                {
                    data: 'dosen',
                    name: 'dosen',
                    'class': 'valign-middle',
                    'orderable': false
                },
                {
                    data: 'hari',
                    name: 'hari',
                    'class': 'text-center valign-middle'
                },
                {
                    data: 'waktu',
                    name: 'waktu',
                    'class': 'text-center valign-middle',
                    'orderable': false
                },
                {
                    data: 'ruang',
                    name: 'ruang',
                    'class': 'text-center valign-middle',
                    'orderable': false,
                    'searchable': false
                },
                {
                    data: 'quota',
                    name: 'quota',
                    'class': 'text-center valign-middle',
                    'orderable': false,
                    'searchable': false
                },
            ],
            "order": [
                [4, "asc"],
                [1, "asc"]
            ]
        });

        getMhs();

        $("#nim").on('change', function() {
            getMhs();
        });

        function getMhs() {
            var string = {
                nim: $("#nim").val(),
                _token: "{{ csrf_token() }}"
            };
            $.ajax({
                url: "{{ url($redirect . '/getMhs') }}",
                method: 'POST',
                data: string,
                success: function(data) {
                    $("#jenis_kelamin").val(data.jenis_kelamin);
                    $("#status").val(data.status);
                    $("#smt").val(data.smt);
                    $("#th_angkatan").val(data.th_angkatan);

                    if (data.prodi) {
                        $("#prodi_id").val(data.prodi.id);
                        $("#nama_prodi").val(data.prodi.nama);
                    } else {
                        $("#prodi_id").val('');
                        $("#nama_prodi").val('');
                    }

                    if (data.kelas) {
                        $("#kelas_id").val(data.kelas.id);
                        $("#nama_kelas").val(data.kelas.nama);
                    } else {
                        $("#kelas_id").val('');
                        $("#nama_kelas").val('');
                    }

                    if (data.kelompok) {
                        $("#kelompok_id").val(data.kelompok.id);
                        $("#kelompok").val(data.kelompok.kode);
                    } else {
                        $("#kelompok_id").val('');
                        $("#kelompok").val('');
                    }

                    $("#sks_total").val(data.sks_total);
                    $("#keuangan").val(data.keuangan);

                    //$("#keuangan")."xxx ".show();

                    if (data.keuangan) {
                        $("#save").show();
                    } else {
                        $("#save").hide();
                    }

                    // dataTable.draw();
                    dataTable.ajax.reload(function(json) {
                        if (json.cek_krs == false) {
                            $('#ceklist_semua').show();
                            $('#hapus_ceklist_semua').show();
                        } else {
                            $('#ceklist_semua').hide();
                            $('#hapus_ceklist_semua').hide();
                        }
                    });
                }
            });
        }
    </script>
@endpush
