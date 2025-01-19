<div class="right-col">
	<hr class="profile-content-hr no-grid-gutter-h">
	<div class="profile-content">
		<ul id="profile-tabs" class="nav nav-tabs">
			<li class="active"><a href="#profile-tabs-biodata" data-toggle="tab">Biodata</a></li>
			<li><a href="#profile-tabs-perwalian" data-toggle="tab">Perwalian</a></li>
			<li><a href="#profile-tabs-mengajar" data-toggle="tab">History Mengajar</a></li>
		</ul>

		<div class="tab-content tab-content-bordered panel-padding">
			@include('dosen.profile.biodata')
			@include('dosen.profile.perwalian')
			@include('dosen.profile.mengajar')
		</div> <!-- / .tab-content -->
	</div>
</div>
