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
                <a href="{{ url($redirect . '/create') }}" class="btn btn-primary"><i class="fa fa-plus-square"></i> Create</a>
            </div>
        </div>

        <div class="table-responsive">
            <table id="serversideTable" class="table table-hover table-bordered">
                <div id="table-loader" class="table-loader"></div>
                <thead>
                    <tr>
                        <th class="text-center col-md-1">Prodi</th>
                        <th class="text-center col-md-2">Kode</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center col-md-1">SKS</th>
                        <th class="text-center col-md-1">Smt</th>
                        <th class="text-center col-md-1">Aktif</th>
                        <th class="col-md-1 text-center">Action</th>
                    </tr>
                </thead>

                <tfoot>
                    <tr>
                        <!-- <th colspan="2" style="text-align:right">Total</th> -->
                        <th></th>
                        <th class="text-center">T o t a l</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection

@push('demo')
    <script>
        init.push(function() {
            $('#c-tooltips-demo a').tooltip();
        });
    </script>
@endpush

@push('scripts')
    <script type="text/javascript">
        var dataTable = $("#serversideTable").DataTable({
            // responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            search: {
                return: true,
            },
            ajax: {
                url: "{{ url($redirect) }}" + '/getData',
                data: function(d) {
                    d.prodi_id = $("#prodi_id").val();
                    d.smt = $("#smt").val();
                },
                beforeSend: function() {
                    addTableLoader('#table-loader');
                },
                complete: function() {
                    deleteTableLoader('#table-loader');
                }
            },
            columns: [{
                    data: 'prodi_nama',
                    name: 'prodi_nama',
                    'class': 'text-center'
                },
                {
                    data: 'mk_kode',
                    name: 'mk_kode',
                    'class': 'text-center'
                },
                {
                    data: 'mk_nama',
                    name: 'mk_nama'
                },
                {
                    data: 'mk_sks',
                    name: 'mk_sks',
                    'class': 'text-center'
                },
                {
                    data: 'mk_smt',
                    name: 'mk_smt',
                    'class': 'text-center'
                },
                {
                    data: 'mk_aktif',
                    name: 'mk_aktif',
                    'class': 'text-center'
                },
                {
                    data: 'action',
                    name: 'action',
                    'orderable': false,
                    'searchable': false,
                },
            ],
            "order": [
                [4, "asc"],
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
                total = api
                    .column(2)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Total over this page
                pageTotal = api
                    .column(3, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Update footer

                //$( api.column( 3 ).footer() ).html(
                //    ''+pageTotal +' ( '+ total +' )'
                //);

                $(api.column(2).footer()).html(total);
            }
        });

        $("#filter").on('change', function() {
            if (!$("#prodi_id").val()) {
                swal(
                    'Peringatan..!!',
                    'Silahkan Pilih Program Studi',
                    'warning'
                );
                $("#prodi_id").focus();
                return false;
            }
            dataTable.draw();
        });

        $("#prodi_id").on('change', function() {
            dataTable.draw();
        });

        $("#smt").on('change', function() {
            dataTable.draw();
        });


        //$("#smt").click(function(){
        //   if(!$("#prodi_id").val())
        //   {
        //     swal(
        //         'Peringatan..!!',
        //         'Silahkan Pilih Program Studi',
        //         'warning'
        //     );
        //     $("#prodi_id").focus();
        //     return false;
        //   }
        //   dataTable.draw();
        // });

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
