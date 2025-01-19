<div class="right-col">
	<hr class="profile-content-hr no-grid-gutter-h">
	<div class="profile-content">
		<ul id="profile-tabs" class="nav nav-tabs">
			<li class="active"><a href="#profile-tabs-biodata" data-toggle="tab">Biodata</a></li>
			<li><a href="#profile-tabs-prodi" data-toggle="tab">Program Studi</a></li>
			<li><a href="#profile-tabs-orangtua" data-toggle="tab">Orang Tua</a></li>
			<li><a href="#profile-tabs-krs" data-toggle="tab">KRS</a></li>
			<li><a href="#profile-tabs-khs" data-toggle="tab">KHS</a></li>
			<li><a href="#profile-tabs-keuangan" data-toggle="tab">Keuangan</a></li>
		</ul>

		<div class="tab-content tab-content-bordered panel-padding">
			@include('mahasiswa.profile.biodata')
			@include('mahasiswa.profile.prodi')
			@include('mahasiswa.profile.orangtua')
			@include('mahasiswa.profile.krs')
			@include('mahasiswa.profile.khs')
			@include('mahasiswa.profile.keuangan')
		</div> <!-- / .tab-content -->
	</div>
</div>
