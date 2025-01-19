			
			@php $th_akademik_aktif = App\ThAkademik::Aktif()->first(); @endphp

			<div class="page-header">
			<div class="row">
				<h1 class="col-xs-12 col-sm-12 text-center text-left-sm">
					<i class="fa fa-home page-header-icon"></i>&nbsp;&nbsp;
					<b>{{$pt->judul}}</b>.&nbsp;&nbsp;
					
					<i class="fa fa-calendar page-header-icon"></i>&nbsp;&nbsp;
					Tahun Akademik <b>{{$th_akademik_aktif->kode}} - {{$th_akademik_aktif->nama}} {{$th_akademik_aktif->semester}}</b>
				</h1>
			</div></div>
