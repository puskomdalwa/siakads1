<div class="right-col">
    @if (session('status'))
    <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert">×</a> {{ session('status') }}
    </div>
    @endif
    <div class="panel-heading" id="panel-heading-desktop">
        <span class="panel-title">@yield('title')</span>
        <div class="panel-heading-controls">
            <a href="{{ url($redirect) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-chevron-circle-left"></i> Kembali </a>
        </div>
    </div>

    <hr class="profile-content-hr no-grid-gutter-h">
    <div class="profile-content">
        <ul id="profile-tabs" class="nav nav-tabs">
            <li class="active"><a href="#profile-tabs-biodata" data-toggle="tab">Biodata</a></li>
            <li><a href="#profile-tabs-perwalian" data-toggle="tab">Perwalian</a></li>
            <li><a href="#profile-tabs-mengajar" data-toggle="tab">History Mengajar</a></li>
            <li><a href="#profile-tabs-nilai" data-toggle="tab">History Nilai</a></li>
            <li><a href="#profile-tabs-skripsi" data-toggle="tab">History Skripsi</a></li>
        </ul>

        <div class="tab-content tab-content-bordered panel-padding">
            @include('dosen.profile.biodata')
            @include('dosen.profile.perwalian')
            @include('dosen.profile.mengajar')
            @include('dosen.profile.nilai')
            @include('dosen.profile.skripsi')
        </div> <!-- / .tab-content -->
    </div>
</div>