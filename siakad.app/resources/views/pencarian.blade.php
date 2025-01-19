@extends('layouts.app')
@section('title',$title)
@section('content')

<div class="alert alert-success alert-dark">
    <button type="button" class="close" data-dismiss="alert">×</button>
    Pencarian data dengan kata kunci  "{{ $cari }}"
</div>

<div class="panel search-panel">
<div class="panel-body tab-content">
	<ul class="search-classic tab-pane fade in active" id="search-tabs-all">

		@if($data['user'])
			@foreach ($data['user'] as $user)
				<li>
					<a href=" {{ url('pengguna/'.$user->id.'/edit') }} " class="search-title">{{ $user->username }} - {{ $user->name }} </a>
					<div class="search-content">
						Email <span class="label label-info"> {{ @$user->email }} </span> , 
						Program Studi <span class="label label-info">{{ @$user->prodi->nama }}</span>, 
						Level <span class="label label-info">{{ @$user->level->level }}</span>, 
						Picture <span class="label label-info">{{ @$user->picture }}</span>, 
						Status Aktif <span class="label label-info">{{ @$user->aktif }}</span>
					</div>
					<div class="search-tags">
						<span class="search-tags-text">Data:</span>
						<a href="#" class="label label-success">Users</a>
					</div> <!-- / .search-tags -->
				</li>
			@endforeach
		@endif

		@if($data['mhs'])
			@foreach ($data['mhs'] as $mhs)
				<li>
					<a href=" {{ url('mahasiswa/'.$mhs->id) }} " class="search-title">{{ $mhs->nim }} - {{ $mhs->nama }} </a>
					<div class="search-content">
						Jenis Kelamin <span class="label label-info"> {{ @$mhs->jk->nama }}</span>, 
						Tahun Angkatan <span class="label label-info"> {{ @$mhs->th_akademik->kode }}</span>,
						Program Studi <span class="label label-info"> {{ @$mhs->prodi->nama }}</span>, 
						Status <span class="label label-info"> {{ @$mhs->status->nama }}</span>
					</div>
					<div class="search-tags">
						<span class="search-tags-text">Data:</span>
						<a href="#" class="label label-success">Mahasiswa</a>
					</div> <!-- / .search-tags -->
				</li>
			@endforeach
		@endif

		@if($data['dosen'])
			@foreach ($data['dosen'] as $dosen)
				<li>
					<a href=" {{ url('dosen/'.$dosen->id) }} " class="search-title">{{ $dosen->kode }} - {{ $dosen->nama }} </a>
					<div class="search-content">
						Jenis Kelamin <span class="label label-info"> {{ @$dosen->jk->nama }}</span>, 
						Program Studi <span class="label label-info"> {{ @$dosen->prodi->nama }}</span>, 
						NIDN <span class="label label-info"> {{ $dosen->nidn }}</span>
					</div>
					<div class="search-tags">
						<span class="search-tags-text">Data:</span>
						<a href="#" class="label label-success">Dosen</a>
					</div> <!-- / .search-tags -->
				</li>
			@endforeach
		@endif

		@if($data['prodi'])
			@foreach ($data['prodi'] as $prodi)
				<li>
					<a href=" {{ url('prodi/'.$prodi->id.'/edit') }} " class="search-title">{{ $prodi->kode }} - {{ $prodi->nama }} </a>
					<div class="search-content">
						Kepala Prodi <span class="label label-info"> {{ @$prodi->nama_kepala }}</span>
					</div>
					<div class="search-tags">
						<span class="search-tags-text">Data:</span>
						<a href="#" class="label label-success">Program Studi</a>
					</div> <!-- / .search-tags -->
				</li>
			@endforeach
		@endif

		@if($data['matakuliah'])
			@foreach ($data['matakuliah'] as $matakuliah)
				<li>
					<a href=" {{ url('matakuliah/'.$matakuliah->id.'/edit') }} " class="search-title">{{ $matakuliah->kode }} - {{ $matakuliah->nama }} </a>
					<div class="search-content">
						Program Studi <span class="label label-info"> {{ @$matakuliah->prodi->nama }}</span>, 
						SKS <span class="label label-info"> {{ @$matakuliah->sks }}, Semester {{ $matakuliah->smt }}</span>, 
						Status Aktif <span class="label label-info"> {{ $matakuliah->aktif }}</span>
					</div>
					<div class="search-tags">
						<span class="search-tags-text">Data:</span>
						<a href="#" class="label label-success">Mata Kuliah</a>
					</div> <!-- / .search-tags -->
				</li>
			@endforeach
		@endif

		@if($data['perwalian'])
			@foreach ($data['perwalian'] as $perwalian)
				<li>
					<a href=" {{ url('mahasiswa/'.$perwalian->mahasiswa->id) }} " class="search-title">{{ $perwalian->nim }} - {{ $perwalian->mahasiswa->nama }} </a>
					<div class="search-content">
						Tahun Akademik Perwalian <span class="label label-info"> {{ @$perwalian->perwalian->th_akademik->kode }}</span>,
						 Program Studi <span class="label label-info"> {{ @$perwalian->perwalian->prodi->nama }}</span>, 
						 Kelas <span class="label label-info"> {{ @$perwalian->perwalian->kelas->nama }}</span>, 
						 Kelompok <span class="label label-info"> {{ @$perwalian->perwalian->kelompok->nama }}</span>, 
						 Dosen Wali <span class="label label-info"> {{ @$perwalian->perwalian->dosen->nama }}</span>, 
						 Status Mahasiswa <span class="label label-info"> {{ @$perwalian->mahasiswa->status->nama }}</span>
					</div>
					<div class="search-tags">
						<span class="search-tags-text">Data:</span>
						<a href="#" class="label label-success">Perwalian</a>
					</div> <!-- / .search-tags -->
				</li>
			@endforeach
		@endif

		@if($data['krs'])
			@foreach ($data['krs'] as $krs)
				<li>
					<a href=" {{ url('mahasiswa/'.$krs->mahasiswa->id) }} " class="search-title">{{ $krs->nim }} - {{ $krs->mahasiswa->nama }} </a>
					<div class="search-content">
						Tahun Akademik <span class="label label-info"> {{ @$krs->th_akademik->kode }}</span>,
						Tanggal <span class="label label-info"> {{ @tgl_str($krs->tanggal) }}</span>,
						Semester <span class="label label-info"> {{ @$krs->smt }}</span>,
						ACC Dosen Wali <span class="label label-info"> {{ @$krs->acc_pa }}</span>,
						Jumlah SKS <span class="label label-info"> {{ @TSKS($krs->th_akademik_id,$krs->nim) }}</span>,
					</div>
					<div class="search-tags">
						<span class="search-tags-text">Data:</span>
						<a href="#" class="label label-success">KRS</a>
					</div> <!-- / .search-tags -->
				</li>
			@endforeach
		@endif
		
		@if($data['keuangan'])
			@foreach ($data['keuangan'] as $keuangan)
				<li>
					<a href=" {{ url('mahasiswa/'.$keuangan->mahasiswa->id) }} " class="search-title">{{ $keuangan->nim }} - {{ @$keuangan->mahasiswa->nama }} </a>
					<div class="search-content">
						Prodi <span class="label label-info"> {{ @$keuangan->mahasiswa->prodi->nama }}</span>,
						Nomor <span class="label label-info"> {{ @$keuangan->nomor }}</span>,
						Tanggal <span class="label label-info"> {{ @tgl_str($keuangan->tanggal) }}</span>,
						Tahun Akademik <span class="label label-info"> {{ @$keuangan->th_akademik->kode }}</span>,
						Tagihan <span class="label label-info"> {{ @$keuangan->tagihan->kode }} - {{ @$keuangan->tagihan->nama }} - Rp.{{ @number_format($keuangan->tagihan->jumlah) }}</span>,
						Jumlah Bayar <span class="label label-info"> Rp.{{ @number_format($keuangan->jumlah) }}</span>
					</div>
					<div class="search-tags">
						<span class="search-tags-text">Data:</span>
						<a href="#" class="label label-success">Pembayaran Keuangan</a>
					</div> <!-- / .search-tags -->
				</li>
			@endforeach
		@endif      
	</ul>
</div></div>
@endsection

@push('css')
<link href="{{ asset('assets/stylesheets/pages.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@push('demo')
<script>
</script>
@endpush

@push('scripts')
<script type="text/javascript"></script>
@endpush
