<div id="modalpembimbing" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modelHeading">Pembimbing</h4>
            </div>
			
            <div class="modal-body">
                <div class="note note-success">
                    <h4 class="note-title" id="lbljudul"></h4>
                </div>
            
				<form action="#" class="form-horizontal" name="formpembimbing" id="formpembimbing">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id">	
                    <input type="hidden" name="pembimbing_id" id="pembimbing_id">						
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group no-margin-hr">
                                <label class="control-label">Pembimbing :</label>
                                <select name="mst_dosen_id" id="mst_dosen_id" class="form-control">
                                    <option value="">-Pilih Dosen-</option>
                                    @foreach($mst_dosen as $dosen)
                                    <option value="{{ $dosen->id }}"> {{ $dosen->nama }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div><!-- col-sm-6 -->
                        
                        <div class="col-sm-3">
						<div class="form-group no-margin-hr">
							<label class="control-label">Jabatan : </label>
							<select name="jabatan" id="jabatan" class="form-control">
								<option value="">-Pilih Pembimbing-</option>
								<option value="Pembimbing I">Pembimbing I</option>
								<option value="Pembimbing II">Pembimbing II</option>
								<option value="Pembimbing III">Pembimbing III</option>
								<option value="Pembimbing IV">Pembimbing IV</option>
							</select>
						</div>
                        </div><!-- col-sm-6 -->

                        <div class="col-sm-3">
						<div class="form-group no-margin-hr">
						<div class="btn-group">
							<button type="button" class="btn btn-success btn-lg" name="simpanpembimbing" id="simpanpembimbing">
								<i class="fa fa-save"></i>
							</button>
		
							<button type="button" class="btn btn-primary btn-lg" name="tambahpembimbing" id="tambahpembimbing">
								<i class="fa fa-plus"></i>
							</button>
						</div></div></div>
                    </div>
				</form>
            </div>

            <div class="modal-footer text-center">
                <div id="detailPembimbing"></div>
            </div>
        </div> <!-- / .modal-content -->
    </div> <!-- / .modal-dialog -->
</div> <!-- / .modal -->
