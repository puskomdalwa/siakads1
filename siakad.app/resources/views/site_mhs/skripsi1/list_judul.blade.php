<div class="panel-heading">
<span class="panel-title">Daftar Judul</span></div>

<div class="panel-body">
	<table class="table table-bordered table-hover table-striped">
		<thead>
			<tr>
				<th class="text-center">No</th>
				<th class="text-center">Judul</th>
				<th class="text-center">Status</th>
				<th class="text-center">Aksi</th>
			</tr>
		</thead>

		<tbody>
			@php
			$no=1;    
			@endphp
			
			@foreach ($data_judul as $judul)
				<tr>
					<td class="text-center"> {{ $no++ }} </td>
					<td> {!! $judul->judul !!} </td>
					<td class="text-center"> {!! $judul->acc=='T' ? 
					'<i class="fa fa-times text-danger"></i>' : '<i class="fa fa-check text-success"></i>' !!} </td>
					
					<td class="text-center">
						<div class="btn-group btn-group-xs" id="c-tooltips-demo">
							<a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" 
							data-original-title="Edit"  data-id="{{ $judul->id }}" data-original-title="Edit" 
							title="Edit" class="btn btn-primary btn-xs btn-rounded tooltip-primary editBtn"> 
							<i class="fa fa-pencil"></i> </a>
							
							<a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" 
							data-original-title="Hapus"  data-id="{{ $judul->id }}" data-original-title="Delete" 
							title="Hapus" class="btn btn-danger btn-xs btn-rounded tooltip-danger deleteBtn">
							<i class="fa fa-times"></i> </a>
						</div>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>

<div class="panel-footer text-center"> Keterangan : </div>
