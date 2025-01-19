<div class="container" id="dokumen_skripsi">
    <div class="card">
        <h6 class="title">Dokumen Skripsi {{ $mahasiswa->nama }}</h6>
    </div>

    <div id="upload_skripsi" class="panel widget-messages-alt panel-danger panel-dark"
        style="border: none;box-shadow: -2px 2px 24px -5px rgba(0,0,0,0.53) !important;">
        <div class="panel-heading">
            <div>
                <div class="pull-left">Dokumen Skripsi</div>
            </div>
            <br>
        </div>
        <div class="panel-body padding-sm" style="overflow: hidden">
            <div>Upload Dokumen Skripsi dengan klik tombol <b>Upload Dokumen Skripsi</b></div>
            <div>
                @if ($statusPengajuan != 'Selesai')
                    <a class="btn btn-primary" style="cursor:pointer;margin-top:5px" data-toggle="modal"
                        data-target="#modal_add_dokumen_skripsi"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                        Upload
                        Dokumen Skripsi Final</a>
                @endif
                <a href="{{ route('mhs_skripsi.downloadSkripsi', ['id' => $skripsi->id]) }}" class="btn btn-success"
                    style="margin-top:5px"><i class="fa fa-file" aria-hidden="true"></i> Download Skripsi Final</a>
            </div>
            <div style="margin-top: 5px">Jika ingin mengubah dokumen skripsi, klik tombol <b>Upload Dokumen
                    Skripsi</b>, edit skripsi hanya bisa dilakukan jika status skripsi selain <b>Selesai</b></div>
        </div>
    </div>
</div>

@if ($statusPengajuan != 'Selesai')
    @include('site_mhs.mhs_skripsi.detail.dokumen-skripsi.add')
@endif
