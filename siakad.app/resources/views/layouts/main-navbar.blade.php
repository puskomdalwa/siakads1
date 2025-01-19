  <div id="main-navbar" class="navbar navbar-inverse" role="navigation">
      <!-- Main menu toggle -->
      <button type="button" id="main-menu-toggle"><i class="navbar-icon fa fa-bars icon"></i>
          <span class="hide-menu-text">HIDE MENU</span></button>

      <div class="navbar-inner">
          <!-- Main navbar header -->
          <div class="navbar-header">
              <!-- Logo -->
              <a href="{{ url('home') }}" class="navbar-brand" style="color: #fff !important">
                  <strong style="font-size: 18px">{{ config('app.name') }}</strong>
              </a>

              <!-- Main navbar toggle -->
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                  data-target="#main-navbar-collapse">
                  <i class="navbar-icon fa fa-bars"></i></button>
          </div> <!-- / .navbar-header -->

          <div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
              <div>
                  <div class="right clearfix">
                      <ul class="nav navbar-nav pull-right right-navbar-nav">
                          <li>
                              <form class="navbar-form pull-left" method="post" action=" {{ url('pencarian') }} ">
                                  {{ csrf_field() }}
                                  <input type="text" name="cari" id="cari" class="form-control"
                                      placeholder="Cari Data ..." required autocomplete="off">
                              </form>
                          </li>

                          <li class="dropdown">
                              <a href="#" class="dropdown-toggle user-menu" data-toggle="dropdown">
                                  @if (!empty(Auth::user()->picture))
                                      <img src="{{ asset('picture_users/' . Auth::user()->picture) }}"
                                          style="border:1px solid #fff; background:#fff;object-fit:cover;object-position:top"
                                          alt="">
                                  @else
                                      <img src="{{ asset('assets/demo/avatars/user.jpg') }}" alt="">
                                  @endif

                                  <span>{{ Auth::user()->name }}</span>
                              </a>

                              <ul class="dropdown-menu">
                                  <li><a href="{{ url('userprofile') }}">Profile</a></li>
                                  <li><a href="{{ url('editpassword') }}">Ganti Password</a></li>

                                  <li class="divider"></li>
                                  <li><a href="{{ route('logout') }}"
                                          onclick="event.preventDefault();
										document.getElementById('logout-form').submit();">
                                          <i class="dropdown-icon fa fa-power-off"></i>&nbsp;&nbsp;Log Out
                                      </a>

                                      {{-- <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
											{{ csrf_field() }}
										</form> --}}
                                  </li>
                              </ul>
                          </li>
                      </ul> <!-- / .navbar-nav -->
                  </div> <!-- / .right -->
              </div>
          </div> <!-- / #main-navbar-collapse -->
      </div> <!-- / .navbar-inner -->
  </div> <!-- / #main-navbar -->
