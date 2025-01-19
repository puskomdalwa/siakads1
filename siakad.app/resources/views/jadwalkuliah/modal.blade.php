<div id="myModal" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h4 class="modal-title" id="myModalLabel">Jadwal Detail Kelas dan Dosen</h4>
		</div>

		<div class="modal-body">
			<form class="form-horizontal form-borderd">
				{{ csrf_field() }}
				
				@include($folder.'/form')
			</form>
		</div> <!-- / .modal-body -->

		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
			<button type="button" class="btn btn-primary" name="save" id="save">
			<i class="fa fa-floppy-o"></i> Save changes</button>
		</div>
	</div> <!-- / .modal-content -->
	</div> <!-- / .modal-dialog -->
</div>
