<div class="panel panel-success panel-dark">
    <div class="panel-heading">
        <span class="panel-title">Data Skripsi Bimbingan</span>
    </div>

    <div class="table-responsive">
        <table id="table_bimbingan" class="table table-hover table-bordered">
            <div id="table-loader-bimbingan" class="table-loader"></div>
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
        var dataTable = $("#table_bimbingan").DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            search: {
                return: true,
            },
            ajax: {
                url: "{{ url($redirect) }}" + '/getData',
                data: function(d) {
                    d.th_akademik_id = $("#th_akademik_id").val();
                    d.prodi_id = $("#prodi_id").val();
                },
                beforeSend: function() {
                    addTableLoader('#table-loader-bimbingan');
                },
                complete: function() {
                    deleteTableLoader('#table-loader-bimbingan');
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
