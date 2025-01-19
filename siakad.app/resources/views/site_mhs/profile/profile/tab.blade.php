<div class="right-col">
    @if (session('status'))
        <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert">×</a> {{ session('status') }}
        </div>
    @endif
    <hr class="profile-content-hr no-grid-gutter-h">
    <div class="profile-content" style="box-shadow: -2px 2px 20px -5px rgba(0,0,0,0.53) !important;">
        <ul id="profile-tabs" class="nav nav-tabs">
            <li class="active"><a href="#profile-tabs-biodata" data-toggle="tab">Biodata</a></li>
            <li><a href="#profile-tabs-prodi" data-toggle="tab">Program Studi</a></li>
            <li><a href="#profile-tabs-orangtua" data-toggle="tab">Orang Tua</a></li>
            <li><a href="#profile-tabs-krs" data-toggle="tab">KRS</a></li>
            <li><a href="#profile-tabs-khs" data-toggle="tab">KHS</a></li>
            <li><a href="#profile-tabs-keuangan" data-toggle="tab">Keuangan</a></li>
        </ul>

        <div class="tab-content tab-content-bordered panel-padding">
            @include('site_mhs.profile.profile.biodata')
            @include('site_mhs.profile.profile.prodi')
            @include('site_mhs.profile.profile.orangtua')
            @include('site_mhs.profile.profile.krs')
            @include('site_mhs.profile.profile.khs')
            @include('site_mhs.profile.profile.keuangan')
        </div> <!-- / .tab-content -->
    </div>
</div>
