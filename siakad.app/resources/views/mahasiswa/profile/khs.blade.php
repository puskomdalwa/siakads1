<div class="tab-pane fade widget-followers" id="profile-tabs-khs">
    <form class="form-horizontal form-borderd" action="#">
        {{ csrf_field() }}

        <div class="panel-body no-padding-hr">
            <div
                class="form-group{{ $errors->has('th_akademik_id') ? ' has-error' : '' }} no-margin-hr panel-padding-h no-padding-t no-border-t">
                <div class="row">
                    <label class="col-sm-2 control-label">Tahun Akademik:</label>
                    <div class="col-sm-2">
                        <select class="form-control" name="th_akademik_id_khs" id="th_akademik_id_khs" required>
                            <option value="">-Pilih-</option>
                            @foreach ($list_thakademik as $thakademik)
                                <option value="{{ $thakademik->id }}">{{ $thakademik->kode }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('th_akademik_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('th_akademik_id') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="row" style="margin-top: 10px">
                    <label class="col-sm-2 control-label">Sumber Data:</label>
                    <div class="col-sm-2">
						<div class="custom-control custom-radio">
							<input type="radio" id="sumber_data_khs" name="sumber_data" class="custom-control-input" value="khs" checked>
                            <label class="custom-control-label" for="sumber_data_khs">KHS</label>
                        </div>
						<div class="custom-control custom-radio">
							<input type="radio" id="sumber_data_master" name="sumber_data" class="custom-control-input" value="master">
							<label class="custom-control-label" for="sumber_data_master">Master</label>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @include('mahasiswa.profile.index_khs')
</div> <!-- / .tab-pane -->
