<div class="widget-article-comments tab-pane panel no-padding no-border fade in active" 
	id="profile-tabs-biodata">
	<table class="table table-bordered table-hover table-striped">
		<tbody>
			<tr><td class="col-md-2">Tahun Akademik</td><td>: 
			{{$data->th_akademik->kode}}</td></tr>
			
			<tr><td class="col-md-2">Status</td><td>: 
			{{@$data->status->nama}}</td></tr>
			
			<tr><td class="col-md-2">Tanggal Masuk</td><td>: 
			{{@tgl_str($data->tanggal_masuk)}}</td></tr>
			
			<tr><td class="col-md-2">NIM</td><td>: 
			{{$data->nim}}</td></tr>
			
			<tr><td class="col-md-2">NIK</td><td>: 
			{{$data->nik}}</td></tr>
			
			<tr><td class="col-md-2">Nama Lengkap</td><td>: 
			{{$data->nama}}</td></tr>
			
			<tr><td class="col-md-2">Jenis Kelamin</td><td>: 
			{{@$data->jk->nama}}</td></tr>
			
			<tr><td class="col-md-2">Tempat Lahir</td><td>: 
			{{$data->tempat_lahir}}</td></tr>
			
			<tr><td class="col-md-2">Tanggal Lahir</td><td>: 
			{{@tgl_str($data->tanggal_lahir)}}</td></tr>
			
			<tr><td class="col-md-2">Agama</td><td>: 
			{{@$data->agama->nama}}</td></tr>
			
			<tr><td class="col-md-2">Alamat</td><td>: 
			{{$data->alamat}}</td></tr>
			
			<tr><td class="col-md-2">Kota</td><td>: 
			{{@$data->kota->name}}</td></tr>
			
			<tr><td class="col-md-2">Email</td><td>: 
			{{$data->email}}</td></tr>
			
			<tr><td class="col-md-2">HP</td><td>: 
			{{$data->hp}}</td></tr>
		</tbody>
	</table>
</div>
