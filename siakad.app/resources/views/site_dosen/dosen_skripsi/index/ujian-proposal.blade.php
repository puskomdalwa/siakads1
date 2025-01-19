<div class="panel panel-success panel-dark">
    <div class="panel-heading">
        <span class="panel-title">Data Skripsi Ujian Proposal</span>
    </div>

    <div class="table-responsive">
        <table id="table_ujian_proposal" class="table table-hover table-bordered">
            <div id="table-loader-ujian-proposal" class="table-loader"></div>
            <thead>
                <tr>
                    <th class="text-center" style="width: 10px">No</th>
                    <th class="text-center">Skripsi</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        var dataTableUjianProposal = $("#table_ujian_proposal").DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            search: {
                return: true,
            },
            ajax: {
                url: "{{ url($redirect) }}" + '/getDataUjianProposal',
                data: function(d) {
                    d.th_akademik_id = $("#th_akademik_id").val();
                    d.prodi_id = $("#prodi_id").val();
                },
                beforeSend: function() {
                    addTableLoader('#table-loader-ujian-proposal');
                },
                complete: function() {
                    deleteTableLoader('#table-loader-ujian-proposal');
                }
            },
            columns: [{
                    data: 'id',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    className: "align-middle"
                },
                {
                    data: 'skripsi_judul_judul',
                    name: 'skripsi_judul_judul',
                    'orderable': false,
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    'orderable': false,
                    'searchable': false,
                },
            ],
            "order": [
                [0, "desc"]
            ]
        });
    </script>
@endpush
