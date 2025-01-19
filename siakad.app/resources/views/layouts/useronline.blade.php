@php
    $user = new App\User();
    $useronline = $user->allOnline();
@endphp

<div class="panel panel-dark panel-light-green">
    <div class="panel-heading">
        <span class="panel-title"><i class="panel-title-icon fa fa-smile-o"></i>Online users</span>
        <div class="panel-heading-controls">
            <span class="badge badge-danger ">User Online : {{ $user->allOnline()->count() }}</span>
        </div>
    </div>

    <div class="panel-body">
        <div class="table-responsive">
            <table id="userTables" class="table table-hover">
                <thead>
                    <tr>
                        <th class="text-center">NO</th>
                        <th>USERNAME</th>
                        <th>FULL NAME</th>
                        <th>E-MAIL</th>
                        <th>LEVEL</th>
                        <th>PRODI</th>
                        <th>TANGGAL</th>
                    </tr>
                </thead>

                <tbody class="valign-middle">
                    @php $no=1; @endphp
                    @foreach ($useronline as $row)
                        <tr>
                            <td class="text-center"> {{ $no++ }} </td>
                            <td>
                                @if (!empty($row->picture))
                                    <img src="{{ 'picture_users/' . $row->picture }}" alt=""
                                        style="width:26px;height:26px;" class="rounded">&nbsp;&nbsp;
                                @else
                                    <img src="{{ asset('img/logo.png') }}" alt=""
                                        style="width:26px;height:26px;" class="rounded">&nbsp;&nbsp;
                                @endif

                                <a href="javascript:void(0);" title="">{{ $row->username }}</a>
                            </td>

                            <td> {{ $row->name }} </td>
                            <td> {{ $row->email }} </td>
                            <td> {{ @$row->level->level }} </td>
                            <td> {{ @$row->prodi->nama }} </td>
                            <td> {{ @$row->updated_at }} </td>
                            <!-- <td> {{ @$row->jk }} </td> -->
                            <!-- <td> {{ @date('d-mm-Y H:i:s') }} </td> -->
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@push('scripts')
    <script type="text/javascript">
        $("#userTables").DataTable({
            responsive: true,
            autoWidth: false,
            search: {
                return: true,
            },
        });
    </script>
@endpush
