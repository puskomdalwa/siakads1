@push('css')
    <style>
        td {
            vertical-align: middle !important;
        }
    </style>
@endpush
<div class="table-responsive">
    <button type="button" class="btn btn-sm btn-success" id="ceklist_semua" onclick="setCheckList(true)"
        style="display:none;margin: 2px 0;">Checklist Semua</button>
    <button type="button" class="btn btn-sm btn-danger" id="hapus_ceklist_semua" onclick="setCheckList(false)"
        style="display:none;margin: 2px 0;">Hapus Checklist Semua</button>

    <table id="serversideTable"
        class="table table-hover table-bordered table-condensed table-striped table-sm-responsive">
        <thead>
            <tr>
                <th class="text-center valign-middle">CHECK<br />LIST</th>
                <th class="text-center valign-middle" style="vertical-align:middle">KODE</th>
                <th class="text-center col-md-2 valign-middle" style="vertical-align:middle">MATA KULIAH</th>
                <th class="text-center valign-middle" style="vertical-align:middle">SKS</th>
                <th class="text-center valign-middle" style="vertical-align:middle">SMT</th>
                <th class="text-center valign-middle" style="vertical-align:middle">KLP</th>
                <th class="text-center col-md-2 valign-middle" style="vertical-align:middle">DOSEN</th>
                <th class="text-center valign-middle" style="vertical-align:middle">HARI</th>
                <th class="text-center valign-middle" style="vertical-align:middle">WAKTU</th>
                <th class="text-center valign-middle" style="vertical-align:middle">RUANG<br>KAPASITAS</th>
                <th class="text-center valign-middle" style="vertical-align:middle">ISI<br>SISA</th>
            </tr>
        </thead>
    </table>
</div>

@push('demo')
    <script>
        init.push(function() {
            $('#c-tooltips-demo a').tooltip();
        });
    </script>
@endpush

@push('scripts')
    <script type="text/javascript">
        // $("#nim").on('change',function(){
        //   dataTable.draw();
        // });

        function setCheckList(value) {
            var checkboxes = document.getElementsByName("cek_list[]");
            checkboxes.forEach(function(checkbox) {
                var id = $(checkbox).val();
                var valKelompok = $('#kelompok').val();
                var valKelompokId = $('#kelompok_' + id).html();
                if (valKelompok == valKelompokId) {
                    checkbox.checked = value;
                }
            });
        }

        function cekList(jadwalkuliah_id, sks, hari, jam) {
            if (cekDouble(jadwalkuliah_id)) {
                $("#cek_list_" + jadwalkuliah_id).prop("checked", false);
                swal("Jadwal sudah diinputkan di kelas lain!", "Gagal pilih mata kuliah!", "error");
                return;
            };

            if (cekBentrok(jadwalkuliah_id, hari, jam)) {
                $("#cek_list_" + jadwalkuliah_id).prop("checked", false);
                swal("Jadwal Bentrok!", "Gagal pilih mata kuliah!", "error");
                return;
            }

            cekMatkulTerkini(jadwalkuliah_id, sks);

            var sks = parseFloat(sks);
            var max_sks = parseFloat($("#max_sks").val());
            var sks_total = parseFloat($("#sks_total").val());
            var ceklist = $("#cek_list_" + jadwalkuliah_id + ":checked").length;

            if (ceklist == true) {
                var total_sks = sks_total + sks;
            } else {
                var total_sks = sks_total - sks;
            }

            if (parseFloat(total_sks) > max_sks) {
                $("#cek_list_" + jadwalkuliah_id).prop("checked", false);
                swal('Error Maksimum SKS!', 'Batas Maksimum ' + max_sks + ' SKS', 'error');
                return false;
            }
            $("#sks_total").val(total_sks);
        }

        function cekDouble(jadwalkuliah_id) {
            if ($("#cek_list_" + jadwalkuliah_id).is(":checked")) {
                var namaMk = [];
                var namaMkChecked = [];
                $('.m-chck input[type="hidden"]').each(function() {
                    var data = $(this).val().split(';');
                    var mk = data[0];
                    var id = data[1];
                    namaMk.push(mk);
                    var ceklist = $("#cek_list_" + id).is(":checked");
                    if (ceklist) {
                        namaMkChecked.push(mk); //checkbox centang
                    }
                });

                var dataMkYangDipilih = [];
                var mkYangDipilih = $('#jenis_' + jadwalkuliah_id).val().split(';');
                var namaMkYangDipilih = mkYangDipilih[0];
                namaMkChecked.forEach(e => {
                    if (e == namaMkYangDipilih) {
                        dataMkYangDipilih.push(namaMkYangDipilih);
                    }
                });
                return checkForDuplicates(dataMkYangDipilih);
            }
        }

        function checkForDuplicates(arr) {
            let seen = {};

            for (let i = 0; i < arr.length; i++) {
                let currentValue = arr[i];
                if (seen[currentValue]) {

                    return true;
                }
                seen[currentValue] = true;
            }
            return false;
        }

        function cekBentrok(jadwalkuliah_id, hari, jam) {
            var matkul = document.getElementsByName("jenis_semester_ini[]");
            var matkulCheck = [];
            var jenis = $("#jenis_" + jadwalkuliah_id).val().split(";");
            if (jenis[0].toUpperCase().indexOf("SKRIPSI") !== -1) { // not checking for skripsi
                return false;
            }
            if (jenis[0].toUpperCase().indexOf("KOMPRE") !== -1 && jenis[2] == "0") { // not checking for skripsi
                return false;
            }

            matkul.forEach(data => {
                data = $(data).val().split(';');
                var id = data[1];
                var ceklist = $("#cek_list_" + id).is(":checked");
                if (ceklist) {
                    matkulCheck.push(data);
                }
            });

            jenis = jenis[5];
            var cek = false;
            if (jenis == "ngulang") {
                matkulCheck.forEach(data => {
                    var namaMatkul = data[0];
                    var hariMatkul = data[3];
                    var jamMatkul = data[4];
                    if (namaMatkul.toUpperCase() != "SKRIPSI") {
                        if (hari == hariMatkul && jam == jamMatkul) {
                            cek = true;
                        }
                    }
                });

                var matkulNgulang = document.getElementsByName("jenis_ngulang[]");
                var matkulNgulangChecked = [];
                matkulNgulang.forEach(data => {
                    data = $(data).val().split(';');
                    var mk = data[0];
                    var id = data[1];
                    var ceklist = $("#cek_list_" + id).is(":checked");
                    if (ceklist && id != jadwalkuliah_id) { // not include self
                        if (mk.toUpperCase().indexOf("SKRIPSI") === -1) { // not checking for skripsi
                            matkulNgulangChecked.push(data);
                        }
                    }
                });

                matkulNgulangChecked.forEach(data => {
                    var hariMatkul = data[3];
                    var jamMatkul = data[4];
                    if (hari == hariMatkul && jam == jamMatkul) {
                        cek = true;
                    }
                });
            }
            return cek;
        }

        function cekMatkulTerkini(jadwalkuliah_id, sks) {
            var matkul = document.getElementsByName("jenis_semester_ini[]");
            var namaMk = [];
            var namaMkChecked = [];

            matkul.forEach(data => {
                data = $(data).val().split(';');
                var mk = data[0];
                var id = data[1];
                namaMk.push(mk);
                var ceklist = $("#cek_list_" + id).is(":checked");
                if (ceklist) {
                    namaMkChecked.push(mk);
                }
            });
            namaMk = namaMk.filter(onlyUnique);
            namaMk = namaMk.filter(function(e) {
                return e !== 'SKRIPSI'
            });
            namaMkChecked = namaMkChecked.filter(onlyUnique);

            if (namaMkChecked.length < namaMk.length) {
                var matkulNgulang = document.getElementsByName("jenis_ngulang[]");
                var cekError = false;
                matkulNgulang.forEach(data => {
                    data = $(data).val().split(';');
                    if (data[0].toUpperCase().indexOf("KOMPRE") !== -1 && data[2] ==
                        "0") { // not checking for kompre
                        return false;
                    }
                    var id = data[1];
                    var ceklist = $("#cek_list_" + id).is(":checked");
                    if (ceklist) {
                        cekError = true;
                    }
                });
                if (cekError) {
                    swal({
                            title: "Perhatian",
                            text: "MATKUL Semester ini harus dipilih semua terlebih dahulu, dan matkul mengulang otomatis tidak dicentang!",
                            icon: "warning",
                            showCancelButton: "true",
                            cancelButtonColor: "#3085d6",
                            confirmButtonColor: "#d33",
                            confirmButtonText: "Iya",
                        })
                        .then((result) => {
                            if (result.value) {
                                matkulNgulang.forEach(data => {
                                    data = $(data).val().split(';');
                                    var mk = data[0];
                                    var id = data[1];
                                    var sks = data[2];
                                    var ceklist = $("#cek_list_" + id).is(":checked");
                                    if (ceklist) {
                                        var sksTotal = parseFloat($("#sks_total").val());
                                        $('#sks_total').val(sksTotal - sks);
                                    }
                                    $("#cek_list_" + id).prop("checked", false);
                                });
                            } else {
                                var name = $("#jenis_" + jadwalkuliah_id).attr('name');
                                var sksTotal = parseFloat($("#sks_total").val());
                                if (name != "jenis_ngulang[]") {
                                    $("#cek_list_" + jadwalkuliah_id).prop("checked", true);
                                    $('#sks_total').val(sksTotal + parseFloat(sks));
                                } else {
                                    $("#cek_list_" + jadwalkuliah_id).prop("checked", false);
                                    $('#sks_total').val(sksTotal - parseFloat(sks));
                                }
                            }
                        });
                }

            }
        }

        function onlyUnique(value, index, array) {
            return array.indexOf(value) === index;
        }
    </script>
@endpush
