<div class="tab-pane fade widget-followers" id="profile-tabs-perwalian">
    <div style="display: flex;justify-content: end;position: relative;z-index: 999;">
        <button class="btn btn-success" style="margin: 10px 20px 10px 0" onclick="accSemuaKRS()">ACC Semua</button>
    </div>
    <div class="table-responsive">
        <table id="perwalianTable" class="table table-bordered table-hover" style="width: 100%">
            <div id="table-loader-perwalian" class="table-loader" style="width: 77%;"></div>
            <thead>
                <tr>
                    <th class="text-center">Detail KRS</th>
                    <th class="text-center">NIM</th>
                    <th class="text-center">Nama Mahasiswa</th>
                    <th class="text-center">L/P</th>
                    <th class="text-center">Prodi</th>
                    <th class="text-center">Kls</th>
                    <th class="text-center">Klp</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">KRS</th>
                    <th class="text-center">ACC KRS</th>
                </tr>
            </thead>
        </table>
        <span class="badge badge-warning">Keterangan : Apabila kolom ACC KRS masih kosong berarti mahasiswa belum
            melakukan pengisian KRS.</span>
    </div>

</div>

@push('scripts')
    <script src="{{ asset('js/handlebars-v4.0.5.js') }}"></script>

    <script id="details-template-perwalian" type="text/x-handlebars-template">
    <div class="table-responsive">
        <div class="table-header">
            <span class="text-info"><b>Detail Jadwal</b></span>
        </div>
    
        <table class="table details-table table-vcenter table-condensed table-bordered table-hover table-striped" id="postsperwalian-@{{id}}">
            <thead>
                <tr>
                <th class="text-center">KODE</th>
                <th class="text-center">MATAKULIAH</th>
                <th class="text-center">SKS</th>
                <th class="text-center">SMT</th>
                <th class="text-center">DOSEN</th>
                <th class="text-center">RUANG</th>
                <th class="text-center">KUOTA</th>
                <th class="text-center">HARI</th>
                <th class="text-center">WAKTU</th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th colspan="2" style="text-align:right">Total</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</script>

    <script type="text/javascript">
        var templatePerwalian = Handlebars.compile($("#details-template-perwalian").html());

        var dataTablePerwalian = $("#perwalianTable").DataTable({
            responsive: false,
            autoWidth: true,
            processing: true,
            serverSide: true,
            search: {
                return: true,
            },
            ajax: {
                url: "{{ url($redirect) }}" + '/getDataPerwalian',
                data: function(d) {
                    d.dosen_id = $("#dosen_id").val();
                },
            },
            "searching": true,
            columns: [{
                    "className": 'details-control',
                    "orderable": false,
                    "searchable": false,
                    "data": null,
                    "defaultContent": ''
                },
                {
                    data: 'nim',
                    name: 'nim',
                    'class': 'text-center'
                },
                {
                    data: 'nama_mhs',
                    name: 'nama_mhs'
                },
                {
                    data: 'jk',
                    name: 'jk',
                    'class': 'text-center',
                    'orderable': false,
                    'searchable': false,
                },
                {
                    data: 'prodi',
                    name: 'prodi',
                    'class': 'text-center',
                    'orderable': false,
                    'searchable': false,
                },
                {
                    data: 'kelas',
                    name: 'kelas',
                    'class': 'text-center',
                    'orderable': false,
                    'searchable': false,
                },
                {
                    data: 'kelompok',
                    name: 'kelompok',
                    'class': 'text-center',
                    'orderable': false,
                    'searchable': false,
                },
                {
                    data: 'status',
                    name: 'status',
                    'class': 'text-center',
                    'orderable': false,
                    'searchable': false,
                },
                {
                    data: 'krs',
                    name: 'krs',
                    'class': 'text-center',
                    'orderable': false,
                    'searchable': false,
                },
                {
                    data: 'krs_acc_btn',
                    name: 'krs_acc_btn',
                    'class': 'text-center',
                    'orderable': false,
                    'searchable': false,
                },
            ],
            "order": [
                [0, "asc"]
            ]
        });

        $('#perwalianTable tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = dataTablePerwalian.row(tr);
            var tableId = 'postsperwalian-' + row.data().id;
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(templatePerwalian(row.data())).show();
                initTablePerwalian(tableId, row.data());
                tr.addClass('shown');
                tr.next().find('td').addClass('no-padding bg-gray');
            }
        });

        function initTablePerwalian(tableId, data) {
            $('#' + tableId).DataTable({
                responsive: false,
                autoWidth: true,
                processing: true,
                serverSide: true,
                paging: false,
                "searching": false,
                ajax: data.details_url,
                columns: [{
                        data: 'matkul_kode',
                        name: 'matkul_kode',
                        'class': 'text-center'
                    },
                    {
                        data: 'matkul_nama',
                        name: 'matkul_nama'
                    },
                    {
                        data: 'matkul_sks',
                        name: 'matkul_sks',
                        'class': 'text-center'
                    },
                    {
                        data: 'matkul_smt',
                        name: 'matkul_smt',
                        'class': 'text-center'
                    },
                    {
                        data: 'dosen',
                        name: 'dosen',
                        'class': 'text-center'
                    },
                    {
                        data: 'ruang',
                        name: 'ruang',
                        'class': 'text-center'
                    },
                    {
                        data: 'kuota',
                        name: 'kuota',
                        'class': 'text-center'
                    },
                    {
                        data: 'hari',
                        name: 'hari',
                        'class': 'text-center'
                    },
                    {
                        data: 'waktu',
                        name: 'waktu',
                        'class': 'text-center'
                    },
                ],
                "order": [
                    [0, "asc"]
                ],
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;

                    // Remove the formatting to get integer data for summation
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };

                    // Total over all pages
                    total = api
                        .column(2)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Total over this page
                    pageTotal = api
                        .column(2, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    $(api.column(2).footer()).html(total);
                    // swal('footer','footer','success');
                }
            })
        }

        function btnAcc(id) {
            // var acc = $("#btnAcc"+id).val();
            // console.log('id '+id+' val' + acc);
            var string = {
                // nim : $("#nim").val(),
                acc: $("#btnAcc" + id).val(),
                id: id,
                _token: "{{ csrf_token() }}"
            };

            $.ajax({
                url: "{{ url($redirect . '/UpdateAccKRS') }}",
                method: 'POST',
                data: string,
                success: function(data) {
                    // alert('sukess');
                    swal({
                        title: data.title,
                        text: data.text,
                        type: data.type
                    });

                    dataTablePerwalian.draw();
                }
            });
        };

        function accSemuaKRS() {
            swal({
                title: "Anda Yakin ?",
                type: "warning",
                text: `KRS yang belum disetujui akan di acc semua KRSnya"`,
                showCancelButton: "true",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#d33",
                confirmButtonText: "Iya",
            }).then((result) => {
                if (result.value) {
                    var string = {
                        // nim : $("#nim").val(),
                        dosen_id: "{{ $data->id }}",
                        _token: "{{ csrf_token() }}"
                    };
                    $.ajax({
                        url: "{{ url($redirect . '/accKrsSemua') }}",
                        method: 'post',
                        data: string,
                        dataType: 'json',
                        success: function(data) {
                            swal({
                                title: data.title,
                                text: data.text,
                                type: data.type
                            });

                            dataTablePerwalian.draw();
                        }
                    });
                }
            });
        }
    </script>
@endpush
