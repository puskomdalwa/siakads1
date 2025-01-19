@extends('layouts.app')
@section('title', $title)
@include('sweetalert::alert')
@section('content')

    <div class="panel panel-danger panel-dark">
        <div class="panel-heading">
            <span class="panel-title">@yield('title')</span>
            {{-- <div class="panel-heading-controls">
      <a href="{{ url($redirect.'/create')}}" class="btn btn-primary"><i class="fa fa-plus-square"></i> Create</a>
    </div> --}}
        </div>

        <div class="table-responsive">
            <table id="serversideTable" class="table table-hover table-bordered">
                <div id="table-loader" class="table-loader"></div>
                <thead>
                    <tr>
                        <th class="col-md-1 text-center">ID</th>
                        <th class="text-center">Level</th>
                        {{-- <th class="col-md-1 text-center">Action</th> --}}
                    </tr>
                </thead>
        </div>
    </div>
@endsection


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
            ajax: {
                url: "{{ url($redirect) }}" + '/getData',
                beforeSend: function() {
                    addTableLoader('#table-loader');
                },
                complete: function() {
                    deleteTableLoader('#table-loader');
                }
            },
            columns: [{
                    data: 'id',
                    name: 'id',
                    'class': 'text-center'
                },
                {
                    data: 'level',
                    name: 'level'
                },
                // { data: 'action', name: 'action','orderable':false, 'searchable':false,'class':'text-center' },
            ],
            "order": [
                [0, "asc"]
            ]
        });

        function deleteForm(id) {
            swal({
                title: "Are you sure?",
                type: "warning",
                text: "You won\'t be able to revert this!",
                showCancelButton: "true",
                cancelButtonColor: "#3085d6",
                confirmButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then(function() {
                // var csrf_token = $('meta[name]="csrf-token"]').attr('content');
                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                // console.log(csrf_token);
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
                            title: "Delete Success",
                            text: "ID " + id + ' ' + data.info + ".",
                            timer: 2000,
                            showConfirmButton: false,
                            type: data.status
                        });
                    },
                    error: function() {
                        swal(
                            'Error Deleted!',
                            'Your file not deleted.',
                            'error'
                        )
                    }
                });

            });
        }
    </script>
@endpush
