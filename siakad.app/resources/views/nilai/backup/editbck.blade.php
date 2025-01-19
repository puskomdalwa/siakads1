@extends('layouts.app')
@section('title', 'Input ' . $title)

@section('content')
    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">@yield('title')</span>
            <div class="panel-heading-controls">
                <a href="{{ url($redirect) }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-chevron-circle-left"></i> Kembali</a>
            </div>
        </div>

        <div class="panel-body">
            {!! Form::model($data, [
                'route' => [$redirect . '.update', $data->id],
                'method' => 'PATCH',
                'class' => 'form-horizontal',
                'autocomplete' => 'off',
            ]) !!}
            @include($folder . '.form')
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        var url = "{{ route('nilai.getDataNilai', ['id' => $data->id]) }}";
        var col = [{
                data: 'id',
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                className: "align-middle"
            },
            {
                data: 'nim',
                name: 'nim'
            },
            {
                data: 'mhs_nama',
                name: 'mhs_nama'
            },
            {
                data: 'mhs_jk',
                name: 'mhs_jk'
            },
            {
                data: 'hadir',
                name: 'hadir',
                'orderable': false,
                'searchable': false,
            }
        ];

        var komponen_nilai = @json($komponen_nilai);

        komponen_nilai.forEach(kn => {
            col.push({
                data: kn.nama,
                name: kn.nama,
                'orderable': false,
                'searchable': false,
            })
        });
        col.push({
            data: 'nilai_akhir',
            name: 'nilai_akhir'
        });
        col.push({
            data: 'nilai_bobot',
            name: 'nilai_bobot'
        });
        col.push({
            data: 'nilai_huruf',
            name: 'nilai_huruf'
        });

        var dataTable = $("#serversideTable").DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            search: {
                return: true,
            },
            ajax: {
                url: url,
                beforeSend: function() {
                    addTableLoader('#table-loader');
                },
                complete: function() {
                    deleteTableLoader('#table-loader');
                }
            },
            lengthMenu: [5, 40, 50, 100, 200],
            columns: col,
            "order": [
                [2, "asc"]
            ]
        });

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
