<div class="container" id="dokumen_skripsi">
    <div class="card">
        <h6 class="title">Dokumen Skripsi {{ $mahasiswa->nama }}</h6>
    </div>

    <div id="upload_proposal" class="panel widget-messages-alt panel-danger panel-dark"
        style="border: none;box-shadow: -2px 2px 24px -5px rgba(0,0,0,0.53) !important;">
        <div class="panel-heading">
            <div>
                <div class="pull-left">Dokumen Proposal</div>
            </div>
            <br>
        </div>
        <div class="panel-body padding-sm" style="overflow: hidden">
            <div>
                <a href="{{ route('dosen_skripsi.downloadProposal', ['id' => $skripsi->id]) }}" class="btn btn-success"
                    style="margin-top:5px"><i class="fa fa-file" aria-hidden="true"></i>
                    Download Proposal</a>
            </div>
        </div>
    </div>

    @if ($statusPengajuan == 'Ujian Skripsi' || $statusPengajuan == 'Selesai')
        <div id="upload_skripsi" class="panel widget-messages-alt panel-danger panel-dark"
            style="border: none;box-shadow: -2px 2px 24px -5px rgba(0,0,0,0.53) !important;">
            <div class="panel-heading">
                <div>
                    <div class="pull-left">Dokumen Skripsi Final</div>
                </div>
                <br>
            </div>
            <div class="panel-body padding-sm" style="overflow: hidden">
                <div>
                    <a href="{{ route('dosen_skripsi.downloadSkripsi', ['id' => $skripsi->id]) }}"
                        class="btn btn-primary" style="margin-top:5px"><i class="fa fa-file" aria-hidden="true"></i>
                        Download Skripsi Final</a>
                </div>
            </div>
        </div>
    @endif
</div>
