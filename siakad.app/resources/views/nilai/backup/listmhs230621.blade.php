@php
    //date_default_timezone_set('Asia/Jakarta');
    //$batas = mktime(date("d"),date("m"),date("Y"));
    //$batas = date('2022-09-24');
    
    //$tgl = $tgl1;
    
    //$tgl1 = date('Y-m-d H:i:s');
    //$tgl_mulai   = $form->tgl_mulai;
    //$tgl_selesai = $form->tgl_selesai;
    
    //$level = strtolower(Auth::user()->level->level);
@endphp

<div class="panel panel-success panel-dark">
    <div class="panel-heading">
        @if ($level == 'admin' || $level == 'baak')
            <span class="panel-title">Data Mahasiswa</span>
    </div>
    @endif

    <div class="table-responsive">
        <table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
            <thead>
                <tr>
                    <!--
     <th class="text-center col-md-1" rowspan="3" valign="middle" style="vertical-align:middle">NIM</th>
     <th class="text-center col-md-3" rowspan="3" style="vertical-align:middle" >Nama Mahasiswa</th>
     -->

                    <th width="10px" class="text-center col-md-1" rowspan="3" valign="middle">NO</th>
                    <th class="text-center col-md-1" rowspan="3" valign="middle">NIM</th>
                    <th class="text-center col-md-3" rowspan="3">Nama Mahasiswa</th>
                    <th class="text-center" rowspan="3">L/P</th>
                    <th class="text-center" rowspan="3">Hadir</th>

                    <th class="text-center" colspan="{{ $komponen_nilai->count() }}">Nilai</th>

                    <th class="text-center col-md-1" rowspan="3">Nilai [Akhir]</th>
                    <th class="text-center col-md-1" rowspan="3">Nilai [Bobot]</th>
                    <th class="text-center col-md-1" rowspan="3">Nilai [Huruf]</th>
                </tr>

                <tr>
                    @foreach ($komponen_nilai as $row)
                        <th class="text-center">{{ $row->nama }}</th>
                    @endforeach
                </tr>

                <tr>
                    @foreach ($komponen_nilai as $row)
                        <th class="text-center">{{ $row->bobot }}%</th>
                    @endforeach
                </tr>

                <tr>
                    <!-- <th class="text-center" rowspan="2" style="vertical-align:middle">Akhir</th>
     <th class="text-center" rowspan="2" style="vertical-align:middle">Huruf</th>
     <th class="text-center" rowspan="2" style="vertical-align:middle">Bobot</th>
     -->
                </tr>
            </thead>

            <tbody>
                @php
                    $nu = 0;
                    $acc_krs = '';
                    $tglx = Carbon\Carbon::now();
                @endphp

                @foreach ($list_mhs as $mhs)
                    @php
                        $nu++;
                        $acc_krs = acc_krs($mhs->th_akademik_id, $mhs->nim);
                        // echo $acc_krs;
                    @endphp

                    @if ($acc_krs == 'Setujui')
                        <tr>
                            <!--
       <td class="text-center" valign="middle">
       <input type="hidden" name="input[{{ $mhs->id }}][id]" id="id_{{ $mhs->id }}" value="{{ $mhs->id }}">
       {{ $mhs->nim }}
       </td>
     
       <td valign="middle">{{ $mhs->mahasiswa->nama }}</td>
       <td class="text-center">{{ $mhs->mahasiswa->jk->kode }}</td>
       <!-- <td class="text-center">{{ $level }}</td> -->
                            <!-- <td class="text-center">{{ substr($mhs->mahasiswa->th_akademik->kode, 0, 4) }}</td> -->

                            <td class="text-center" valign="middle">{{ number_format($nu, 0) }}</td>
                            <td class="text-center" valign="middle">
                                <input type="hidden" name="input[{{ $mhs->id }}][id]"
                                    id="id_{{ $mhs->id }}" value="{{ $mhs->id }}"> {{ $mhs->nim }}
                            </td>

                            <td valign="middle">{{ $mhs->mahasiswa->nama }}</td>
                            <td class="text-center">{{ $mhs->mahasiswa->jk->kode }}</td>
                            <!-- <td class="text-center">{{ jmlabsmhs($data->id, $mhs->nim) }}</td> -->
                            <td class="text-center">{{ jmlabsmhs($data->id, $mhs->nim) . '/' . jmlabsdos($data->id) }}
                            </td>

                            @if ($level != 'baak' && $level != 'admin')
                                @if ($tgl >= $form->tgl_mulai && $tgl <= $form->tgl_selesai)
                                    @foreach ($komponen_nilai as $row)
                                        <td class="text-center col-md-1">
                                            @if ($row->id > 1)
                                                <input type="text"
                                                    name="input[{{ $mhs->id }}][{{ $row->nama }}]"
                                                    id="{{ $row->nama }}_{{ $mhs->id }}"
                                                    value="{{ getNilai($mhs->id, $row->id) }}"
                                                    class="form-control text-center"
                                                    onkeypress="return hanyaAngka(event)"
                                                    onkeyup="hitungNilai({{ $mhs->id }})">
                                            @else
                                                <input type="text"
                                                    name="input[{{ $mhs->id }}][{{ $row->nama }}]"
                                                    id="{{ $row->nama }}_{{ $mhs->id }}"
                                                    value="{{ $cekSkripsi ? 100 : getNilaiAbs($data->id, $mhs->nim) }}"
                                                    class="form-control text-center"
                                                    onkeypress="return hanyaAngka(event)"
                                                    onkeypress="hitungNilai({{ $mhs->id }})" readonly="true">
                                            @endif
                                        </td>

                                        <!--
          <td class="text-center col-md-1">
           <input type="text" name="input[{{ $mhs->id }}][{{ $row->nama }}]" id="{{ $row->nama }}_{{ $mhs->id }}"
           value="{{ getNilai($mhs->id, $row->id) }}" class="form-control text-center"
           onkeypress='return hanyaAngka(event)' onkeyup="hitungNilai({{ $mhs->id }})">
          </td>
          -->
                                    @endforeach
                                @else
                                    @foreach ($komponen_nilai as $row)
                                        <td class="text-center col-md-1">
                                            @if ($row->id > 1)
                                                <input type="text"
                                                    name="input[{{ $mhs->id }}][{{ $row->nama }}]"
                                                    id="{{ $row->nama }}_{{ $mhs->id }}"
                                                    value="{{ getNilai($mhs->id, $row->id) }}"
                                                    class="form-control text-center"
                                                    onkeypress="return hanyaAngka(event)"
                                                    onkeyup="hitungNilai({{ $mhs->id }})">
                                            @else
                                                <input type="text"
                                                    name="input[{{ $mhs->id }}][{{ $row->nama }}]"
                                                    id="{{ $row->nama }}_{{ $mhs->id }}"
                                                    value="{{ $cekSkripsi ? 100 : getNilaiAbs($data->id, $mhs->nim) }}"
                                                    class="form-control text-center"
                                                    onkeypress="return hanyaAngka(event)"
                                                    onkeypress="hitungNilai({{ $mhs->id }})" readonly="true">
                                            @endif
                                        </td>
                                    @endforeach
                                @endif
                            @else
                                @foreach ($komponen_nilai as $row)
                                    <td class="text-center col-md-1">
                                        @if ($row->id > 1)
                                            <input type="text"
                                                name="input[{{ $mhs->id }}][{{ $row->nama }}]"
                                                id="{{ $row->nama }}_{{ $mhs->id }}"
                                                value="{{ getNilai($mhs->id, $row->id) }}"
                                                class="form-control text-center" onkeypress="return hanyaAngka(event)"
                                                onkeyup="hitungNilai({{ $mhs->id }})">
                                        @else
                                            <input type="text"
                                                name="input[{{ $mhs->id }}][{{ $row->nama }}]"
                                                id="{{ $row->nama }}_{{ $mhs->id }}"
                                                value="{{ $cekSkripsi ? 100 : getNilaiAbs($data->id, $mhs->nim) }}"
                                                class="form-control text-center" onkeypress="return hanyaAngka(event)"
                                                onkeypress="hitungNilai({{ $mhs->id }})" readonly="true">
                                        @endif
                                    </td>
                                @endforeach
                            @endif

                            <td class="text-center col-md-1">
                                <input type="text" name="input[{{ $mhs->id }}][nilai_akhir]"
                                    id="nilai_akhir_{{ $mhs->id }}"
                                    value="{{ !empty($mhs->nilai_akhir) ? $mhs->nilai_akhir : null }}"
                                    class="form-control text-center" readonly="true">
                            </td>

                            <td class="text-center col-md-1">
                                <input type="text" name="input[{{ $mhs->id }}][nilai_huruf]"
                                    id="nilai_huruf_{{ $mhs->id }}"
                                    value="{{ !empty($mhs->nilai_huruf) ? $mhs->nilai_huruf : null }}"
                                    class="form-control text-center" readonly="true">
                            </td>

                            <td class="text-center col-md-1">
                                <input type="text" name="input[{{ $mhs->id }}][nilai_bobot]"
                                    id="nilai_bobot_{{ $mhs->id }}"
                                    value="{{ !empty($mhs->nilai_bobot) ? $mhs->nilai_bobot : null }}"
                                    class="form-control text-center" readonly="true">
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- @php $level = strtolower(Auth::user()->level->level); @endphp -->

    @if ($level != 'admin' && $level != 'baak')
        @if ($form->tgl_mulai && $tgl <= $form->tgl_selesai)
            <div class="panel-body no-padding-hr">
                <div class="panel-footer">
                    <div class="col-sm-offset-1 col-md-10">
                        <button type="submit" name="save" id="save"
                            class="btn btn-success btn-flat btn-block">
                            <i class="fa fa-floppy-o"></i> Simpan Nilai</button>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="panel-body no-padding-hr">
            <div class="panel-footer">
                <div class="col-sm-offset-1 col-md-10">
                    <button type="submit" name="save" id="save" class="btn btn-success btn-flat btn-block">
                        <i class="fa fa-floppy-o"></i> Simpan Nilai</button>
                </div>
            </div>
        </div>
    @endif
</div>
</div>

{{-- var {{$row->nama}} = parseFloat($("#{{$row->nama}}_"+id).val()); --}}

@push('demo')
    <script>
        init.push(function() {
            // $('#serversideTable').dataTable();
        });
    </script>
@endpush

@push('scripts')
    <script type="text/javascript">
        function hanyaAngka(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode <= 45 || charCode > 57) && (charCode > 46))
                return false;
            return true;
        }

        function hitungNilai(id) {
            var nilai_akhir = 0;
            @foreach ($komponen_nilai as $row)
                nilai_akhir += parseFloat($("#{{ $row->nama }}_" + id).val() || 0) * parseFloat({{ $row->bobot }}) /
                    100;
            @endforeach

            @foreach ($komponen_nilai as $row)
                if (parseFloat($("#{{ $row->nama }}_" + id).val()) > 100) {
                    swal('Error Nilai', ' Range Nilai 0 s.d 100', 'error');
                    $("#{{ $row->nama }}_" + id).val(0);
                    return false;
                }
            @endforeach

            $("#nilai_akhir_" + id).val(nilai_akhir);
            getBobotNilai(nilai_akhir, id);
        }

        function getBobotNilai(nilai_akhir, id) {
            var string = {
                nilai_akhir: nilai_akhir,
                _token: "{{ csrf_token() }}"
            };
            $.ajax({
                url: "{{ url($folder . '/getBobotNilai') }}",
                method: 'POST',
                data: string,
                success: function(data) {
                    // console.log(data);
                    $("#nilai_huruf_" + id).val(data.nilai_huruf);
                    $("#nilai_bobot_" + id).val(data.nilai_bobot);
                }
            });
        }
    </script>
@endpush

<?php
/*
@if ($tgl >= $tgl_mulai && $tgl <= $tgl_selesai)
 @foreach ($komponen_nilai as $row)
<td class="text-center col-md-1">
   @if ($row->id > 1)
<input type="text" name="input[{{ $mhs->id }}][{{ $row->nama }}]"
    id="{{ $row->nama }}_{{ $mhs->id }}" value="{{ getNilai($mhs->id, $row->id) }}"
    class="form-control text-center" onkeypress="return hanyaAngka(event)"
    onkeyup="hitungNilai({{ $mhs->id }})">
@else
<input type="text" name="input[{{ $mhs->id }}][{{ $row->nama }}]"
    id="{{ $row->nama }}_{{ $mhs->id }}" value="{{ $cekSkripsi ? 100 : getNilaiAbs($data->id, $mhs->nim) }}"
    class="form-control text-center" onkeypress="return hanyaAngka(event)"
    onkeypress="hitungNilai({{ $mhs->id }})" readonly="true">
@endif
  </td>
@endforeach
@else
@foreach ($komponen_nilai as $row)
@if ($row->id > 1)
<td class="text-center col-md-1">
    <input type="text" name="input[{{ $mhs->id }}][{{ $row->nama }}]"
    id="{{ $row->nama }}_{{ $mhs->id }}" value="{{ getNilai($mhs->id, $row->id) }}"
    class="form-control text-center" onkeypress='return hanyaAngka(event)'
    onkeyup="hitungNilai({{ $mhs->id }})" readonly="true">
   </td>
@endif
@endforeach
@endif
*/
?>
