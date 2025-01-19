<div class="table-responsive">
    <table id="nilaiTable" class="table table-vcenter table-bordered table-condensed" style="width: 100%">
        <div id="table-loader-nilai" class="table-loader" style="width: 77%;"></div>
        <thead>
            <tr>
                <th></th>
                <th class="text-center">Kode</th>
                <th class="text-center">Matakuliah</th>
                <th class="text-center col-md-1">SKS</th>
                <th class="text-center col-md-1">SMT</th>
                <th class="text-center">Ruang</th>
                <th class="text-center col-md-1">Hari</th>
                <th class="text-center col-md-1">Waktu</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th colspan="3" style="text-align:right">Total</th>
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

@push('demo')
    <script>
        init.push(function() {
            $('#c-tooltips-demo a').tooltip();
        });
    </script>
@endpush

@push('scripts')
    <script src="{{ asset('js/handlebars-v4.0.5.js') }}"></script>
    <script id="details-template" type="text/x-handlebars-template">
    <div class="table-header">
     <span class="text-info"><b>Detail Mahasiswa</b></span>
   </div>
   <table class="table details-table table-vcenter table-condensed table-bordered table-hover table-striped" id="posts-@{{id}}">
       <thead>
       <tr>
           <th class="text-center col-md-1">NIM</th>
           <th class="text-center">Nama</th>
           <th class="text-center col-md-1">L/P</th>
           <th class="text-center col-md-1">N.Akhir</th>
           <th class="text-center col-md-1">N.Huruf</th>
           <th class="text-center col-md-1">N.Mutu</th>
       </tr>
       </thead>
   </table>
</script>
    <script type="text/javascript">
        var template = Handlebars.compile($("#details-template").html());
        var dataTable = $("#nilaiTable").DataTable({
            processing: true,
            serverSide: true,
            search: {
                return: true,
            },
            ajax: {
                url: "{{ url($redirect) }}" + '/getDataNilai',
                data: function(d) {
                    d.dosen_id = $("#dosen_id").val();
                    d.th_akademik_id_nilai = $("#th_akademik_id_nilai").val();
                },
                beforeSend: function() {
                    addTableLoader('#table-loader-nilai');
                },
                complete: function() {
                    deleteTableLoader('#table-loader-nilai');
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

        $('#nilaiTable tbody').on('click', 'td.details-control', function() {
            // alert('test');
            var tr = $(this).closest('tr');
            var row = dataTable.row(tr);
            var tableId = 'posts-' + row.data().id;
            // console.log(tableId);
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(template(row.data())).show();
                initTable(tableId, row.data());
                tr.addClass('shown');
                tr.next().find('td').addClass('no-padding bg-gray');
            }
        });

        function initTable(tableId, data) {
            $('#' + tableId).DataTable({
                processing: true,
                serverSide: true,
                paging: false,
                "searching": false,
                ajax: data.details_url,
                columns: [{
                        data: 'nim',
                        name: 'nim',
                        'class': 'text-center'
                    },
                    {
                        data: 'nama_mhs',
                        name: 'nama_mhs'
                    },
                    {
                        data: 'sex',
                        name: 'sex',
                        'class': 'text-center'
                    },
                    {
                        data: 'nilai_akhir',
                        name: 'nilai_akhir',
                        'class': 'text-center'
                    },
                    {
                        data: 'nilai_huruf',
                        name: 'nilai_huruf',
                        'class': 'text-center'
                    },
                    {
                        data: 'nilai_bobot',
                        name: 'nilai_bobot',
                        'class': 'text-center'
                    },
                ],
                "order": [
                    [0, "asc"]
                ]
            })
        }

        $("#th_akademik_id_nilai").on('change', function() {
            dataTable.draw();
        });
    </script>
@endpush
