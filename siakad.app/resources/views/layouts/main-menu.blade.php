<div id="main-menu" role="navigation">
    <div id="main-menu-inner">
        <div class="menu-content top" id="menu-content-demo">
            <!-- Menu custom content demo
  Javascript: html/assets/demo/demo.js
  -->

            <div>
                <div class="text-bg" style="cursor: pointer" data-toggle="modal" data-target="#exampleModal">
                    <span
                        style="color: #000;font-weight: 500; font-size: 18px">{{ Illuminate\Support\Str::limit(Auth::user()->name, 10) }}</span>
                </div>

                @if (!empty(Auth::user()->picture))
                    <img src="{{ asset('picture_users/' . Auth::user()->picture) }}" alt="" class=""
                        style="object-fit: cover;object-position:top">
                @else
                    <img src="{{ asset('assets/demo/avatars/user.jpg') }}" alt="" class="">
                @endif

                <div class="btn-group">

                    <a href="{{ url('userprofile') }}" class="btn btn-xs btn-primary btn-outline dark"><i
                            class="fa fa-user"></i></a>
                    <a href="{{ url('editpassword') }}" class="btn btn-xs btn-primary btn-outline dark"><i
                            class="fa fa-cog"></i></a>
                    <a href="{{ route('logout') }}" class="btn btn-xs btn-danger btn-outline dark"
                        onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();"><i
                            class="fa fa-power-off"></i></a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>

                <a href="#" class="close">&times;</a>
            </div>
        </div>

        {!! Menu::render('navbar', 'navigation') !!}
    </div> <!-- / #main-menu-inner -->
</div> <!-- / #main-menu -->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>

            </div>
            <div class="modal-body">
                <table>
                    <tr>
                        <td>Nama </td>
                        <td style="padding-left: 10px">: {{ Auth::user()->name }}</td>
                    </tr>
                    @if (isset(Auth::user()->mahasiswa->nim))
                        <tr>
                            <td>NIM </td>
                            <td style="padding-left: 10px">:
                                {{ isset(Auth::user()->mahasiswa->nim) ? Auth::user()->mahasiswa->nim : null }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td>Level </td>
                        <td style="padding-left: 10px">:
                            {{ isset(Auth::user()->level->level) ? Auth::user()->level->level : null }}</td>
                    </tr>
                    @if (Auth::user()->level_id == 5)
                        <tr>
                            <td>Program Studi </td>
                            <td style="padding-left: 10px">:
                                {{ isset(Auth::user()->prodi->nama) ? Auth::user()->prodi->nama : null }}</td>
                        </tr>
                    @endif
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
