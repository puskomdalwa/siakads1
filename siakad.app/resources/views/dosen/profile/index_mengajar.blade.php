<div class="table-responsive">
    <table id="mengajarTable" class="table table-bordered table-hover" style="width: 100%">
        <div id="table-loader-mengajar" class="table-loader" style="width: 77%;"></div>
        <thead>
            <tr>
                <th class="text-center"></th>
                {{-- <th class="text-center col-md-1">No</th> --}}
                <th class="text-center">Kode</th>
                <th class="text-center">Matakuliah</th>
                <th class="text-center">SKS</th>
                <th class="text-center">SMT</th>
                <th class="text-center">Ruang</th>
                <th class="text-center">Hari</th>
                <th class="text-center">Waktu</th>
            </tr>
        </thead>

        <tfoot>
            <tr>
                <th colspan="3" style="text-align:center">Total</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>

@push('css')
    <style media="screen">
        .text-putih {
            color: #fff;
        }

        td.details-control {
            background: url("{{ asset('img/details_open.png') }}") no-repeat center center;
            cursor: pointer;
            width: 18px;
        }

        tr.shown td.details-control {
            background: url("{{ asset('img/details_open.png') }}") no-repeat center center;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('js/handlebars-v4.0.5.js') }}"></script>
    <script id="details-templateMengajar-mengajar" type="text/x-handlebars-templateMengajar">
    <div class="table-header">
    <span class="text-info"><b>Detail Absensi</b></span>
    </div>
    <table class="table details-table table-vcenter table-condensed table-bordered table-hover table-striped" id="postsMengajar-@{{id}}">
    <thead>
    <tr>
        <th class="text-center">Pertemuan</th>
        <th class="text-center">Tanggal</th>
    </tr>
    </thead>
    </table>
</script>
@endpush

@push('scripts')
    <script type="text/javascript">
        var templateMengajar = Handlebars.compile($("#details-templateMengajar-mengajar").html());
        var dataTableMengajar = $("#mengajarTable").DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            search: {
                return: true,
            },
            ajax: {
                url: "{{ url($redirect) }}" + '/getDataMengajar',
                data: function(d) {
                    d.dosen_id = $("#dosen_id").val();
                    d.th_akademik_id_dosen = $("#th_akademik_id_dosen").val();
                },
                beforeSend: function() {
                    addTableLoader('#table-loader-mengajar');
                },
                complete: function() {
                    deleteTableLoader('#table-loader-mengajar');
                }
            },
            paging: false,
            "searching": false,
            columns: [{
                    "className": 'details-control',
                    "orderable": false,
                    "searchable": false,
                    "data": null,
                    "defaultContent": ''
                },
                {
                    data: 'kode_mk',
                    name: 'kode_mk',
                    'class': 'text-center'
                },
                {
                    data: 'nama_mk',
                    name: 'nama_mk'
                },
                {
                    data: 'sks_mk',
                    name: 'sks_mk',
                    'class': 'text-center'
                },
                {
                    data: 'smt',
                    name: 'smt',
                    'class': 'text-center'
                },
                {
                    data: 'ruang',
                    name: 'ruang',
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
                [1, "asc"]
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
                t_sks = api
                    .column(3)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $(api.column(3).footer()).html(t_sks);
            }
        });

        $('#mengajarTable tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = dataTableMengajar.row(tr);

            var tableId = 'postsMengajar-' + row.data().id;
            // console.log(tableId);
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(templateMengajar(row.data())).show();
                initTableMengajar(tableId, row.data());
                tr.addClass('shown');
                tr.next().find('td').addClass('no-padding bg-gray');
            }
        });

        function initTableMengajar(tableId, data) {

            console.log(data);

            $('#' + tableId).DataTable({
                processing: true,
                serverSide: true,
                paging: false,
                "searching": false,
                ajax: data.details_url,
                columns: [{
                        data: 'materi',
                        name: 'materi',
                        'class': 'text-center'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                ],
                "order": [
                    [0, "asc"]
                ]
            })
        }

        $("#th_akademik_id_dosen").on('change', function() {
            dataTableMengajar.draw();
        });
    </script>
@endpush
