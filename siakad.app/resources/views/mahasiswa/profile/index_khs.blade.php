<div class="table-responsive">
    <table id="khsTable" class="table table-hover table-bordered table-condensed table-striped">
        <thead>
            <tr>
                <th class="text-center col-md-1">No</th>
                <th class="text-center col-md-1">Kode</th>
                <th class="text-center col-md-4">Matakuliah</th>
                <th class="text-center col-md-1">SKS</th>
                <th class="text-center col-md-1">Nilai</th>
                <th class="text-center col-md-1">Huruf</th>
                <th class="text-center col-md-1">Mutu</th>
            </tr>
        </thead>

        <tfoot>
            <tr>
                <th colspan="2" style="text-align:right">Total</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>

            <tr>
                <th colspan="2" style="text-align:right">IPK</th>
                <th class="text-center"> <span id="ip"></span> </th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>

@push('scripts')
    <script type="text/javascript">
        var dataTableKHS = $("#khsTable").DataTable({
            processing: true,
            serverSide: true,
            paging: false,
            "searching": false,
            ajax: {
                url: "{{ url($redirect) }}" + '/getDataKHS',
                data: function(d) {
                    d.th_akademik_id = $("#th_akademik_id_khs").val();
                    d.nim = $("#nim").val();
                    d.sumber_data = $("input[name='sumber_data']:checked").val();
                }
            },
            columns: [{
                    data: 'id',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    className: "text-center"
                }, {
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
                    data: 'nilai_bobot',
                    name: 'nilai_bobot',
                    'class': 'text-center'
                },
                {
                    data: 'nilai_huruf',
                    name: 'nilai_huruf',
                    'class': 'text-center'
                },
                {
                    data: 'mutu',
                    name: 'mutu',
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
                        i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                };

                // Total over all pages
                t_sks = api
                    .column(2)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                t_nilai = api
                    .column(3)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                t_mutu = api
                    .column(5)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Total over this page
                pageTSKS = api
                    .column(2, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                pageTNilai = api
                    .column(3, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                pageTMutu = api
                    .column(5, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                $(api.column(2).footer()).html(t_sks);
                $(api.column(3).footer()).html(t_nilai);
                $(api.column(5).footer()).html(t_mutu);

                var ip = t_mutu / t_sks || 0;
                $("#ip").html(ip.toFixed(2));
                // swal('footer','footer','success');
            }
        });

        $("#th_akademik_id_khs").on('change', function() {
            dataTableKHS.draw();
        });
        $("#sumber_data_master").on('change', function() {
            dataTableKHS.draw();
        });
        $("#sumber_data_khs").on('change', function() {
            dataTableKHS.draw();
        });
    </script>
@endpush