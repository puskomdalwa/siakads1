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
@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        td.details-control {
            /* background: url("{{ asset('img/details_open.png') }}") no-repeat center center; */
            cursor: pointer;
            width: 18px;
        }

        .table-detail {
            position: relative;
            margin: 0;
            padding-top: 20px;
        }

        .nilai-not-saved {
            background-color: #fffcbd !important;
        }

        tr.shown td.details-control {
            /* background: url("{{ asset('img/details_open.png') }}") no-repeat center center; */
        }

        .table-detail::-webkit-scrollbar {
            display: none;
        }

        .all-open {
            position: relative;
            margin-bottom: 20px;
        }

        table.table-bordered.dataTable {
            /* border-right-width: 0; */
            display: contents;
        }

        .show-shepherd {
            opacity: 1 !important;
            pointer-events: all !important;
            visibility: visible !important;
        }
    </style>
@endpush

<div class="all-open">
    <button type="button" class="btn btn-success" style="width:100%" id="step_5" onclick="allOpen()">Buka semua detail
        nilai
        <i class="fa fa-hand-o-up" aria-hidden="true"></i></button>
</div>
<div class="panel panel-success panel-dark" id="step_1">
    <div class="panel-heading">
        @if ($level == 'admin' || $level == 'baak' || $level == 'baak (hanya lihat)' || $level == 'prodi')
            <span class="panel-title">Data Mahasiswa</span>
    </div>
    @endif

    <div class="table-responsive">
        <table id="serversideTable" class="table table-hover table-responsive w-100">
            <div id="table-loader" class="table-loader"></div>
            <thead>
                <tr>
                    <th class="text-center">Isi Nilai</th>
                    <th class="text-center" style="width:10px" valign="middle">NO</th>
                    <th class="text-center" valign="middle">NIM</th>
                    <th class="text-center">Nama Mahasiswa</th>
                    <th class="text-center">L/P</th>
                </tr>
            </thead>
        </table>
    </div>

    @if ($nilaiAccess)
        @if ($level != 'admin' && $level != 'baak' && $level != 'prodi')
            @if ($form->tgl_mulai && $tgl <= $form->tgl_selesai)
                <div class="panel-body no-padding-hr">
                    <div class="panel-footer">
                        <div class="col-sm-offset-1 col-md-10">
                            <button type="button" name="save" id="save" onclick="saveAllNilai()"
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
                        <button type="button" name="save" id="save" onclick="saveAllNilai()"
                            class="btn btn-success btn-flat btn-block">
                            <i class="fa fa-floppy-o"></i> Simpan Semua Nilai</button>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="panel-body no-padding-hr">
            <div class="panel-footer">
                <div class="col-sm-offset-1 col-md-10">
                    <button type="button"
                        class="btn btn-primary btn-flat btn-block">
                        <i class="fa fa-floppy-o"></i> Hanya <b>dosen</b> yang bisa simpan nilai</button>
                </div>
            </div>
        </div>
    @endif
</div>
</div>

@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="{{ asset('js/handlebars-v4.0.5.js') }}"></script>

    <script id="details-template-perwalian" type="text/x-handlebars-template">
        <div class="table-detail w-100" id="table-detail-@{{ id }}">
            <div class="table-header">
                <span class="text-info"><b>Detail Nilai</b></span><span class="text-warning" id="note-detail-@{{ id }}"></span>
            </div>
            
            <form onsubmit="saveNilai(event, @{{ id }})" id="form_save_nilai_@{{ id }}">
                <div style="margin: 20px" id="div-absensi-@{{ id }}">
                    <span>Input Jumlah Absensi (Khusus SPM) </span>:<input type="number" name="input-absensi" id="input-absensi-@{{ id }}" style="margin-left: 5px;width: 40px" 
                        onkeyup="absensiSPM(this.value, @{{ id }})"
                        onchange="absensiSPM(this.value, @{{ id }})">
                </div>
                {{ csrf_field() }}
                <table class="table table-condensed table-bordered table-hover table-striped" id="isi_nilai-@{{id}}">
                    <div id="table-loader-@{{ id }}" class="table-loader"></div>
                    <thead>
                        <tr>
                            <th class="text-center" style="border-left: 1px solid #f5f8f1">Hadir</th>
                            @foreach ($komponen_nilai as $row)
                                <th class="text-center" style="border-left: 1px solid #f5f8fa">{{ $row->nama }} ({{ $row->bobot }})%</th>
                            @endforeach
                            <th class="text-center col-md-1">Nilai [Akhir]</th>
                            <th class="text-center col-md-1">Nilai [Bobot]</th>
                            <th class="text-center col-md-1">Nilai [Huruf]</th>
                        </tr>
                    </thead>
    
                    <tfoot>
                        <tr>
                            <th colspan="9" style="text-align:center">Simpan</th>
                        </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    </script>

    <script type="text/javascript">
        var templatePerwalian = Handlebars.compile($("#details-template-perwalian").html());
        var checkSaveAll = false;
        var dataTable;
        $(document).ready(function() {
            dataTable = initDataTable();

            // addTour('#step_1', 'Ini adalah data nilai mahasiswa');
            // addTour('select[name=serversideTable_length]',
            //     'Atur jumlah yang ditampilkan di sini');
            // addTour('div.dataTables_wrapper div.dataTables_filter input',
            //     'Untuk mencari mahasiswa berdasarkan nama atau nim');
            // addTour('td.details-control', 'Klik tombol detail untuk menampilkan nilai mahasiswanya');
            // addTour('#step_5', 'Untuk membuka semua detail nilai', end = true);
            // tour.start();

            $('#serversideTable tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = dataTable.row(tr);
                var tableId = 'isi_nilai-' + row.data().id;
                if (row.child.isShown()) {
                    // This row is already open - close it
                    var note = $(`#note-detail-${row.data().id}`).html();
                    if (note != "") {
                        swal({
                            title: "Perubahan nilai belum disimpan, apakah yakin ingin menutupnya ? ?",
                            type: "warning",
                            text: "Kalau ada perubahan nilai, jangan lupa disimpan terlebih dahulu sebelum menutupnya !!",
                            showCancelButton: "true",
                        }).then((result) => {
                            if (result.value) {
                                row.child.hide();
                                $('#action-' + row.data().id).removeClass('opened');
                                tr.removeClass('shown');
                            }
                        });
                    } else {
                        row.child.hide();
                        $('#action-' + row.data().id).removeClass('opened');
                        tr.removeClass('shown');
                    }
                } else {
                    // Open this row
                    row.child(templatePerwalian(row.data())).show();
                    initTableIsiNilai(tableId, row.data());
                    tr.addClass('shown');
                    $('#action-' + row.data().id).addClass('opened');
                    tr.next().find('td').addClass('no-padding bg-gray');

                    //spm
                    if (row.data().mhs_spm == "iya") {
                        $(`#div-absensi-${row.data().id}`).css('display', 'block');
                    } else {
                        $(`#div-absensi-${row.data().id}`).css('display', 'none');
                    }
                }
            });
        });

        function initTableIsiNilai(tableId, data) {
            var col = [{
                data: 'hadir',
                name: 'hadir',
                'orderable': false,
                'searchable': false,
            }];

            var komponen_nilai = @json($komponen_nilai);

            komponen_nilai.forEach(kn => {
                col.push({
                    data: kn.nama,
                    name: kn.nama,
                    'orderable': false,
                    'searchable': false,
                })
            });
            col.push({
                data: 'nilai_akhir',
                name: 'nilai_akhir'
            });
            col.push({
                data: 'nilai_bobot',
                name: 'nilai_bobot'
            });
            col.push({
                data: 'nilai_huruf',
                name: 'nilai_huruf'
            });

            $('#' + tableId).DataTable({
                responsive: false,
                autoWidth: true,
                processing: true,
                serverSide: true,
                paging: false,
                "searching": false,
                ajax: {
                    url: data.data_isi_nilai_url,
                    complete: function() {
                        $(`#isi_nilai-${data.id}_info`).hide();
                        // isi_nilai-305728_info
                    }
                },
                columns: col,
                ordering: false,
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;
                    @if ($level == 'admin' || $level == 'baak' || $level == 'prodi')
                        var idKrsDetail = data[0].id;
                        var element =
                            `
                        <div style="display:flex;">
                            <button type="button" onclick="sesuaikan(${idKrsDetail})" class="btn btn-warning" style="width:49%;margin-right:5px">Sesuaikan</button>
                            <button type="submit" id="simpan_nilai-${idKrsDetail}" class="btn btn-success" style="width:50%">Simpan Per Mahasiswa</button>    
                        </div>
                        `;

                        @if (!$nilaiAccess)
                            var element = '<div>Hanya dosen yang bisa simpan nilai</div>';
                        @endif
                    @else
                        var element = '<div>Hanya Admin dan BAAK bisa simpan nilai</div>';
                    @endif
                    $(api.column(1).footer()).html(element);
                    // swal('footer','footer','success');
                }
            })
        }

        function sesuaikan(idKrsDetail) {
            let nilai = [
                $('#Sikap_' + idKrsDetail).val(),
                $('#Tugas_' + idKrsDetail).val(),
                $('#UTS_' + idKrsDetail).val(),
                $('#UAS_' + idKrsDetail).val()
            ];
            let terbesar = Math.max(...nilai);
            $('#Sikap_' + idKrsDetail).val(terbesar);
            $('#Tugas_' + idKrsDetail).val(terbesar);
            $('#UTS_' + idKrsDetail).val(terbesar);
            $('#UAS_' + idKrsDetail).val(terbesar);
            console.log(terbesar);
            hitungNilai(idKrsDetail);
        }

        function saveNilai(event, idKrsDetail) {
            event.preventDefault();

            if (checkSaveAll) {
                saveOperation(idKrsDetail);
            } else {
                swal({
                    title: "Simpan nilai ?",
                    type: "warning",
                    text: "Nilai mahasiswa akan disimpan",
                    showCancelButton: "true",
                }).then((result) => {
                    if (result.value) {
                        saveOperation(idKrsDetail);
                    }
                });
            }
        }

        function saveOperation(idKrsDetail) {
            var csrf_token = "{{ csrf_token() }}";
            var url = "{{ route('nilai.saveNilai', ['id' => $id]) }}";
            $.ajax({
                url: url,
                type: "POST",
                data: $(`#form_save_nilai_${idKrsDetail}`).serializeArray(),
                beforeSend: function() {
                    addTableLoaderWithLoad(`#table-loader-${idKrsDetail}`);
                },
                success: function(data) {
                    Toastify({
                        text: data.text,
                        duration: 3000,
                        close: true,
                        stopOnFocus: true,
                        className: `bg-${data.type}`,
                    }).showToast();
                    deleteTableLoader(`#table-loader-${idKrsDetail}`);
                    $(`#table-detail-${idKrsDetail}`).removeClass('nilai-not-saved');
                    $(`#note-detail-${idKrsDetail}`).html('');

                    if (data.status) {
                        let element = '#mhs_' + data.id;
                        if ($(element).hasClass('alert-danger')) {
                            $(element).removeClass('alert-danger');
                            $(element).addClass('alert-success');
                        }
                    }
                },
                error: function() {
                    deleteTableLoader(`#table-loader-${idKrsDetail}`);
                }
            });
        }

        function saveAllNilai() {
            try {
                swal({
                    title: "Simpan nilai yang belum disimpan semuanya ?",
                    type: "warning",
                    text: "Nilai mahasiswa yang belum disimpan, akan disimpan semuanya yang posisinya detail terbuka",
                    showCancelButton: "true",
                }).then((result) => {
                    if (result.value) {
                        checkSaveAll = true;
                        var rowsData = dataTable.rows().data();
                        $.each(rowsData, function(key, value) {
                            var elementId = 'simpan_nilai-' + value.id;
                            var noteElementId = 'note-detail-' + value.id;
                            if ($('#' + elementId).length && $('#' + noteElementId).html() != "") {
                                $('#' + elementId).click();
                            }
                        });
                        checkSaveAll = false;
                    }
                });
                checkSaveAll = false;
            } catch (error) {
                checkSaveAll = false;
                console.log(error);
            }
        }

        function initDataTable() {
            var url = "{{ route('nilai.getDataNilai', ['id' => $data->id]) }}";
            return $("#serversideTable").DataTable({
                // responsive: true,
                autoWidth: true,
                processing: true,
                serverSide: true,
                search: {
                    return: true,
                },
                order: [
                    [3, "asc"]
                ],
                ajax: {
                    url: url,
                    beforeSend: function() {
                        addTableLoader('#table-loader');
                    },
                    complete: function() {
                        deleteTableLoader('#table-loader');
                        $('#serversideTable').addClass('table-bordered');
                    }
                },
                lengthMenu: [30, 40, 50, 100, 200],
                columns: [{
                        className: 'details-control',
                        orderable: false,
                        searchable: false,
                        data: 'action',
                        defaultContent: ''
                    },
                    {
                        data: 'id',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        className: "align-middle"
                    },
                    {
                        data: 'nim',
                        name: 'nim'
                    },
                    {
                        data: 'mhs_nama',
                        name: 'mhs_nama'
                    },
                    {
                        data: 'mhs_jk',
                        name: 'mhs_jk'
                    },
                ],
            });
        }

        function allOpen() {
            var rowsData = dataTable.rows().data();
            $.each(rowsData, function(key, value) {
                var elementId = 'action-' + value.id;
                if (!$('#' + elementId).hasClass('opened')) {
                    $('#' + elementId).click();
                }
            });
        }

        function deleteForm(id) {
            swal({
                title: "Anda Yakin ?",
                type: "warning",
                text: "Data yang sudah dihapus tidak dapat kembali.",
                showCancelButton: "true",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.value) {
                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ url($redirect) }}" + '/' + id,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': csrf_token
                        },
                        success: function(data) {
                            // table.ajax.reload();
                            dataTable.draw();
                            swal({
                                title: data.title,
                                text: data.text,
                                // timer: 2000,
                                // showConfirmButton: false,
                                type: data.type
                            });
                        },
                        error: function() {
                            swal(
                                'Error Deleted!',
                                'Silahkan Hubungi Administrator',
                                'error'
                            )
                        }
                    });
                }
            });
        }

        function hanyaAngka(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode <= 45 || charCode > 57) && (charCode > 46))
                return false;
            return true;
        }

        function hitungNilai(id) {
            $(`#table-detail-${id}`).addClass('nilai-not-saved');
            $(`#note-detail-${id}`).html(' <b>*Belum Disimpan</b>');
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
                    $("#nilai_huruf_" + id).val(data.nilai_huruf);
                    $("#nilai_bobot_" + id).val(data.nilai_bobot);
                }
            });
        }

        function addTour(idElement, text, end = false) {
            tour.addStep({
                text: `<h5>${text}</h5>`,
                attachTo: {
                    element: idElement,
                    on: 'top'
                },
                classes: idElement == 'td.details-control' ? 'show-shepherd' : '',
                buttons: [{
                    text: end == true ? 'Selesai' : 'Selanjutnya',
                    classes: 'shepherd-button-custom',
                    action: tour.next
                }]
            });
        }

        function absensiSPM(jumlahAbsensi, id) {
            let rekapAbsensi = $(`#rekap_absensi_${id}`).val();
            let max = rekapAbsensi.split('/')[1];
            let nilai = Math.ceil(jumlahAbsensi / max * 100);
            if (nilai > 100) {
                $(`#input-absensi-${id}`).val(max);
                return;
            }
            if (nilai < 0) {
                $(`#input-absensi-${id}`).val(0);
                return;
            }
            $(`#Absensi_${id}`).val(nilai);
            hitungNilai(id)
        }
    </script>
@endpush
