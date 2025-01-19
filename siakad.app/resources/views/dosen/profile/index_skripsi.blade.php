<div class="table-responsive">
    <table id="skripsiTable" class="table table-bordered table-hover" style="width: 100%">
        <div id="table-loader-skripsi" class="table-loader" style="width: 77%;"></div>
        <thead>
            <tr>
                <th class="text-center col-md-1">Tanggal<br />Daftar</th>
                <th class="text-center col-md-1">NIM</th>
                <th class="text-center">Nama Mahasiswa</th>
                <th class="text-center">L/P</th>
                <th class="text-center">Prodi</th>
                <th class="text-center col-md-1">Tanggal<br />Acc</th>
                <th class="text-center">Judul Skripsi</th>
            </tr>
        </thead>
    </table>
</div>

@push('scripts')
<script type="text/javascript">
var dataTableSkripsi = $("#skripsiTable").DataTable({
    responsive: true,
    autoWidth: false,
    processing: true,
    serverSide: true,
    search: {
        return: true,
    },
    ajax: {
        url: "{{ url($redirect) }}" + '/getDataSkripsi',
        data: function(d) {
            d.dosen_id = $("#dosen_id").val();
            d.th_akademik_id_dosen = $("#th_akademik_id_skripsi").val();
        },
        beforeSend: function() {
            addTableLoader('#table-loader-skripsi');
        },
        complete: function() {
            deleteTableLoader('#table-loader-skripsi');
        }
    },
    paging: false,
    "searching": false,
    columns: [{
            data: 'tgl_pengajuan',
            name: 'tgl_pengajuan',
            'class': 'text-center'
        },
        {
            data: 'mhs_nim',
            name: 'mhs_nim',
            'class': 'text-center'
        },
        {
            data: 'mhs_nama',
            name: 'mhs_nama'
        },
        {
            data: 'mhs_sex',
            name: 'mhs_sex',
            'class': 'text-center'
        },
        {
            data: 'mhs_prodi',
            name: 'mhs_prodi'
        },
        {
            data: 'tgl_acc',
            name: 'tgl_acc',
            'class': 'text-center'
        },
        {
            data: 'txt_judul',
            name: 'txt_judul'
        },
    ],
    "order": [
        [0, "asc"]
    ],
});

$("#th_akademik_id_skripsi").on('change', function() {
    dataTableSkripsi.draw();
});
</script>
@endpush