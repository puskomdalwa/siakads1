<div class="panel panel-success panel-dark">
    <div class="panel-heading">
        <span class="panel-title">
            @if ($tgl >= $form->tgl_mulai && $tgl <= $form->tgl_selesai)
                <h4><b>
                        <center> Mohon Diperhatikan !!! <br />
                            Batas Pengisian Nilai Tanggal: {{ tgl_jam($form->tgl_mulai) }} s/d
                            {{ tgl_jam($form->tgl_selesai) }}
                            (Mohon Diisi Dengan Angka Bulat) </center>
                    </b></h4>
            @else
                @if ($tgl > $form->tgl_selesai)
                    <h3><b>
                            <center> Mohon Maaf, Pengisian Nilai Sudah Ditutup !!! </center>
                        </b></h3>
                @else
                    <h3><b>
                            <center> Mohon Maaf, Pengisian Nilai Belum Dibuka !!! </center>
                        </b></h3>
                @endif
            @endif
        </span>
    </div>
</div>

@if ($tgl >= $form->tgl_mulai && $tgl <= $form->tgl_selesai)
    <div class="panel-body no-padding-hr">
        <div class="panel-footer">
            <div class="col-sm-offset-1 col-md-10">
                <button type="submit" name="save" id="save" class="btn btn-success btn-flat btn-block">
                    <i class="fa fa-floppy-o"></i>&nbsp SIMPAN NILAI</button>
            </div>
        </div>
    </div>
@endif

<div class="table-responsive" style="position: relative">
    <table id="serversideTable" class="table table-hover table-bordered">
        <div id="table-loader" class="table-loader"></div>
        <thead>
            <tr>
                <th class="text-center" valign="middle">NO</th>
                <th class="text-center col-md-1" valign="middle">NIM</th>
                <th class="text-center col-md-3">Nama Mahasiswa</th>
                <th class="text-center">L/P</th>
                <th class="text-center">Hadir</th>

                @foreach ($komponen_nilai as $row)
                    <th class="text-center">{{ $row->nama }} ({{ $row->bobot }})%</th>
                @endforeach

                <th class="text-center col-md-1">Nilai [Akhir]</th>
                <th class="text-center col-md-1">Nilai [Bobot]</th>
                <th class="text-center col-md-1">Nilai [Huruf]</th>
            </tr>
        </thead>
    </table>
</div>

<!--
{{-- var {{$row->nama}} = parseFloat($("#{{$row->nama}}_"+id).val()); --}}
-->

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
                url: "{{ url($redirect . '/getBobotNilai') }}",
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
