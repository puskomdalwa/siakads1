<div class="tab-pane fade widget-followers" id="profile-tabs-keuangan">
    <div class="table-responsive">
        <table id="keuanganTable" class="table table-bordered table-condensed " style="width: 100%">
            <thead>
                <tr>
                    <th class="text-center col-md-1">Taka</th>
                    <th class="text-center col-md-1">Prodi</th>
                    <th class="text-center col-md-2">Tagihan</th>
                    <th class="text-center col-md-1">Jumlah</th>
                    <th class="text-center col-md-1">Tgl-DW</th>
                    <th class="text-center col-md-1">Bayar-DW</th>
                    <th class="text-center col-md-1">Tgl-IDN</th>
                    <th class="text-center col-md-1">Bayar-IDN</th>
                    <th class="text-center col-md-1">Piutang</th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th colspan="3" style="text-align:center">Jumlah Tagihan / Bayar / Piutang</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div> <!-- / .tab-pane -->

@push('scripts')
    <script type="text/javascript">
        var dataTableKeuangan = $("#keuanganTable").DataTable({
            processing: true,
            serverSide: true,
            paging: false,
            "searching": false,
            ajax: {
                url: "{{ url($redirect) }}" + '/getDataKeuangan',
                data: function(d) {
                    d.nim = $("#nim").val();
                }
            },
            columns: [{
                    data: 'th_akademik',
                    name: 'th_akademik',
                    'class': 'text-center'
                },
                {
                    data: 'prodi',
                    name: 'prodi',
                    'class': 'text-center'
                },
                {
                    data: 'tagihan',
                    name: 'tagihan'
                },
                {
                    data: 'jml_tagihan',
                    name: 'jml_tagihan',
                    'class': 'text-right'
                },
                {
                    data: 'dw_tanggal',
                    name: 'dw_tanggal'
                },
                {
                    data: 'dw_bayar',
                    name: 'dw_bayar',
                    'class': 'text-right'
                },
                {
                    data: 'idn_tanggal',
                    name: 'idn_tanggal'
                },
                {
                    data: 'idn_bayar',
                    name: 'idn_bayar',
                    'class': 'text-right'
                },
                {
                    data: 'sisa',
                    name: 'sisa',
                    'class': 'text-right'
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
                jml_tagihan = api
                    .column(3)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                dw_jmlbayar = api
                    .column(5)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                idn_jmlbayar = api
                    .column(7)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                hutang = api
                    .column(8)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);


                // Total over this page
                // pageTotal = api
                //     .column( 4, { page: 'current'} )
                //     .data()
                //     .reduce( function (a, b) {
                //         return intVal(a) + intVal(b);
                //     }, 0 );

                // pagePiutang = api
                // .column( 5, { page: 'current'} )
                // .data()
                // .reduce( function (a, b) {
                //     return intVal(a) + intVal(b);
                // }, 0 );    

                $(api.column(3).footer()).html(new Intl.NumberFormat('in-RP', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(jml_tagihan));

                $(api.column(5).footer()).html(new Intl.NumberFormat('in-RP', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(dw_jmlbayar));

                $(api.column(7).footer()).html(new Intl.NumberFormat('in-RP', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(idn_jmlbayar));

                $(api.column(8).footer()).html(new Intl.NumberFormat('in-RP', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(hutang));

                // swal('footer','footer','success');
            }
        });
    </script>
@endpush
