@extends('layouts.app')
@section('title', $title)

@section('content')
    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">Filter @yield('title')</span>
        </div>

        @include($folder . '.filter')
    </div>

    <div class="panel panel-success panel-dark">
        <div class="panel-heading">
            <span class="panel-title">Data @yield('title')</span>
            <div class="panel-heading-controls">
                <a href="{{ url($redirect . '/create') }}" class="btn btn-primary">
                    <i class="fa fa-plus-square"></i> Create</a>
                <a href="{{ url($redirect) }}" class="btn btn-warning">
                    <i class="fa fa-refresh"></i> Refresh</a>
            </div>
        </div>

        <div class="table-responsive">
            <table id="serversideTable" class="table table-hover table-bordered table-condensed">
                <div id="table-loader" class="table-loader"></div>
                <thead>
                    <tr>
                        <th class="text-center col-md-1">NIM</th>
                        <th class="text-center">Nama Mahasiswa</th>
                        <th class="text-center">L/P</th>
                        <th class="text-center">Prodi</th>
                        <th class="text-center">Kelas</th>
                        <th class="text-center">Klp</th>
                        <th class="text-center">Judul Skripsi</th>
                        <th class="text-center">Tgl SK<br>Yudisium</th>
                        <th class="text-center">SK<br>Yudisium</th>
                        <th class="text-center">Nomor Seri<br /> Ijazah</th>
                        <th class="text-center">Status</th>
                        <th class="text-center col-md-1">Approve</th>
                        <th class="text-center col-md-1">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('demo')
    <script>
        init.push(function() {
            $('#c-tooltips-demo a').tooltip();
            // $.fn.editable.defaults.mode = 'inline';
            // swal('test','test','success');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.sk_yudisium').editable({
                // type: 'text',
                // name: 'sk_yudisium',
                // title: 'SK Yudisium',
                url: "{{ url($redirect) }}" + '/saveSKYudisium',
                type: "POST",
            });

            $('.nomor_seri_ijazah').editable({
                url: "{{ url($redirect) }}" + '/saveNomorSeriIjazah',
                type: "POST",
            });

            $('.tanggal').editable({
                datepicker: {
                    todayBtn: 'linked'
                },
                url: "{{ url($redirect) }}" + '/saveTglSKYudisium',
                type: "POST",
            });
        });
    </script>
@endpush

@push('scripts')
    <script type="text/javascript">
        var dataTable = $("#serversideTable").DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            search: {
                return: true,
            },
            paging: false,
            // "searching": false,
            ajax: {
                url: "{{ url($redirect) }}" + '/getData',
                data: function(d) {
                    d.th_akademik_id = $("#th_akademik_id").val();
                    d.th_angkatan_id = $("#th_angkatan_id").val();
                    d.prodi_id = $("#prodi_id").val();
                },
                beforeSend: function() {
                    addTableLoader('#table-loader');
                },
                complete: function() {
                    deleteTableLoader('#table-loader');
                }
            },
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
                    data: 'jk',
                    name: 'jk',
                    'class': 'text-center'
                },
                {
                    data: 'prodi',
                    name: 'prodi'
                },
                {
                    data: 'kelas',
                    name: 'kelas',
                    'class': 'text-center'
                },
                {
                    data: 'kelompok',
                    name: 'kelompok',
                    'class': 'text-center'
                },
                {
                    data: 'judul_skripsi',
                    name: 'judul_skripsi'
                },
                {
                    data: 'tanggal_sk_yudisium',
                    name: 'tanggal_sk_yudisium',
                    'class': 'text-center'
                },
                {
                    data: 'link_sk_yudisium',
                    name: 'link_sk_yudisium',
                    'class': 'text-center'
                },
                {
                    data: 'link_nomor_seri_ijazah',
                    name: 'link_nomor_seri_ijazah',
                    'class': 'text-center'
                },
                {
                    data: 'status',
                    name: 'status',
                    'class': 'text-center'
                },
                {
                    data: 'approve',
                    name: 'approve',
                    'orderable': false,
                    'searchable': false,
                    'class': 'text-center'
                },
                {
                    data: 'action',
                    name: 'action',
                    'orderable': false,
                    'searchable': false,
                    'class': 'text-center'
                },
            ],
            "order": [
                [0, "asc"]
            ]
        });

        $("#th_angkatan_id").on('change', function() {
            dataTable.draw();
        });

        $("#prodi_id").on('change', function() {
            dataTable.draw();
        });

        function getStatus(id) {
            // var status_id = $("#status_id_"+id).val();
            // console.log(status_id);
            var string = {
                id: id,
                approve: $("#approve_" + id).val(),
                _token: "{{ csrf_token() }}"
            };
            $.ajax({
                url: "{{ url($folder . '/approve') }}",
                method: 'POST',
                data: string,
                success: function(data) {
                    dataTable.draw();
                    if (data.type == 'success') {
                        swal(data.title, data.text, data.type)
                    }
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
    </script>
@endpush
