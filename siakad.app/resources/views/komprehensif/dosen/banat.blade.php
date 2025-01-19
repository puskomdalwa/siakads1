<div class="panel widget-messages-alt panel-danger panel-dark">
    <div class="panel-heading">
        <span class="panel-title"><i class="panel-title-icon fa fa-laptop"></i>{{ $title }} Banat</span>
    </div> <!-- / .panel-heading -->
    <div class="panel-body no-padding-hr">
        <div class="table-responsive">
            <table id="table-banat" class="table table-hover table-bordered">
                <div id="table-loader-banat" class="table-loader"></div>
                <thead>
                    <tr>
                        <th class="text-center col-md-2">Penguji Ke</th>
                        <th class="text-center col-md-2">Foto</th>
                        <th class="text-center col-md-6">Dosen</th>
                        <th class="text-center">Edit</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i <= $jumlahDosen; $i++)
                        @php
                            $kompreDosen = \App\KompreDosen::where('penguji', $i)->where('jenis_kelamin', 'P')->first();
                            $cekKompre = false;
                            if ($kompreDosen) {
                                if ($kompreDosen->dosen_id) {
                                    $cekKompre = true;
                                }
                            }
                        @endphp
                        <tr>
                            <td class="text-center">{{ $i }}</td>
                            <td class="text-center">
                                @if ($cekKompre)
                                    @php
                                        $user = \App\User::where('username', $kompreDosen->dosen->kode)->first();
                                        $picture = $user->picture
                                            ? asset('picture_users/' . $user->picture)
                                            : asset('img/logo.png');
                                    @endphp
                                    <img src="{{ $picture }}">
                                @else
                                    Belum ada dosen
                                @endif
                            </td>
                            <td class="text-center">
                                {{ $cekKompre ? @$kompreDosen->dosen->nama.'-'.@$kompreDosen->dosen->kode : 'Belum ada dosen' }}
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Klik <span
                                            class="caret"></span></button>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a data-toggle="modal" data-target="#modal_edit_penguji"
                                                data-id="{{ @$kompreDosen->id }}"
                                                data-dosen_id="{{ @$kompreDosen->dosen_id }}"
                                                data-penguji="{{ $i }}"
                                                data-jenis_kelamin="P"
                                                >
                                                Edit</a>
                                        </li>
                                        @if ($cekKompre)
                                            <li class="divider"></li>
                                            <li>
                                                <form method="POST">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="id"
                                                        value="{{ @$kompreDosen->id }}">

                                                    <button
                                                        style="width: 100%; background-color: rgb(224, 81, 81);color:#fff;border: none"
                                                        type="submit" onclick="deleteForm(event)">Delete</button>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        let tableBanat = $('#table-banat').DataTable({
            "order": [
                [0, "asc"]
            ]
        });
    </script>
@endpush
