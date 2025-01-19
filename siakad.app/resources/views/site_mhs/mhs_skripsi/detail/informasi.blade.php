<div class="container" id="informasi">
    <div class="card">
        <h6 class="title">Detail Skripsi {{ $mahasiswa->nama }}</h6>
        <p class="card-subtitle">Detail informasi terkait detail skripsi yang berisi dari file proposal skripsi sampai
            selesai.
        </p>
        <blockquote class="bquote">
            <div class="tx-orange tx-uppercase text-justify">
                <p></p>
                <p id="judul_skripsi">{{ strtoupper($skripsi->judul) }}</p>
                <p></p>
            </div>
            <footer class="tx-black">Judul Skripsi</footer>
        </blockquote>
    </div>

    <section id="biodata">
        <div class="profile-row" style="padding: 0 30px">
            <div class="left-col">
                <div class="panel widget-messages-alt panel-danger panel-dark"
                    style="border: none;box-shadow: -2px 2px 24px -5px rgba(0,0,0,0.53) !important;">
                    <div
                        style="width: inherit; height:3px; background-color: var(--dalwaColor); border-radius: 10px 10px 0 0">
                    </div>
                    <div class="panel-body text-center">
                        @if ($picture)
                            <img src="{{ asset('picture_users/' . $picture) }}" class="img-circle" height="120"
                                width="120" alt="">
                        @else
                            <img src="{{ asset('assets/demo/avatars/pria.jpg') }}" class="img-circle" height="120"
                                width="120" alt="">
                        @endif
                        {{-- <div class="image-border">
                        </div> --}}
                        <div style="margin: 10px">{{ $mahasiswa->nama }}</div>
                    </div>
                    <div class="panel-footer"
                        style="background-color: var(--dalwaColor); border-radius: 0 0 5px 5px;text-align: center;color: #fff;text-decoration: underline;">
                        {{ $mahasiswa->nim }}
                    </div>
                </div>

                <div class="panel widget-messages-alt panel-danger panel-dark"
                    style="border: none;box-shadow: -2px 2px 24px -5px rgba(0,0,0,0.53) !important;">
                    <div class="panel-heading">
                        <div>
                            <div class="pull-left">Detail</div>
                            <div class="pull-right" style="cursor: pointer;" data-toggle="collapse"
                                data-target="#detail-collapse"><i class="navbar-icon fa fa-bars icon"></i></div>
                        </div>
                        <br>
                    </div>
                    <div class="panel-body padding-sm collapse" id="detail-collapse" style="overflow: hidden">
                        <strong>NIM</strong>
                        <p>{{ $mahasiswa->nim }}</p>
                        <hr>
                        <strong>Prodi</strong>
                        <p>{{ $mahasiswa->prodi->nama }}</p>
                        <hr>
                        <strong>TTL</strong>
                        <p>{{ $mahasiswa->tempat_lahir }}, {{ @tgl_str($mahasiswa->tanggal_lahir) }}</p>
                        <hr>
                        <strong>No. Hp</strong>
                        <p>{{ $mahasiswa->hp }}</p>
                        <hr>
                        <strong>Email</strong>
                        <p>{{ $mahasiswa->email }}</p>
                        <hr>
                    </div>
                </div>
            </div>

            <div class="right-col">
                <div id="status_skripsi" class="panel widget-messages-alt panel-danger panel-dark"
                    style="border: none;box-shadow: -2px 2px 24px -5px rgba(0,0,0,0.53) !important;">
                    <div class="panel-heading">
                        <div>
                            <div class="pull-left">Status Skripsi</div>
                        </div>
                        <br>
                    </div>
                    <div class="panel-body padding-sm" style="overflow: hidden">
                        Status skripsi dari <b>{{ $mahasiswa->nama }}</b> :
                        <b>{{ strtoupper($statusPengajuan) }}</b><br>
                        <button class="btn btn-{{ @$color }}"
                            style="margin-top:5px">{{ strtoupper($statusPengajuan) }}</button>
                    </div>
                </div>

                <div id="informasi_ujian">
                    @if ($statusPengajuan == 'Ujian Proposal')
                        <div class="panel widget-messages-alt panel-danger panel-dark"
                            style="border: none;box-shadow: -2px 2px 24px -5px rgba(0,0,0,0.53) !important;">
                            <div class="panel-heading">
                                <div>
                                    <div class="pull-left" id="informasi_ujian_proposal_title">Ujian Proposal
                                        <span
                                            class="badge badge-{{ $colorUjianProposal }}">{{ strtoupper($ujianProposal->status) }}</span>
                                    </div>
                                </div>
                                <br>
                            </div>
                            <div class="panel-body padding-sm" style="overflow: hidden">
                                <div>Berikut informasi ujian proposal skripsi yang akan dilaksanakan
                                    <b>{{ $mahasiswa->nama }}</b> :
                                </div>
                                <table style="margin-left: 10px;margin-top: 10px">
                                    @foreach ($dosenPengujiUjianProposal as $item)
                                        <tr>
                                            <td>{{ ucfirst($item->jabatan) }}</td>
                                            <td style="padding-left: 10px">:</td>
                                            <td style="padding-left: 10px"><b
                                                    id="informasi_ujian_proposal_penguji_1">({{ $item->dosen->prodi->alias }})
                                                    {{ $item->dosen->nama }}</b>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>Jadwal Ujian</td>
                                        <td style="padding-left: 10px">:</td>
                                        <td style="padding-left: 10px"><b
                                                id="informasi_ujian_proposal_jadwal">{{ date('d-m-Y H:i', strtotime($ujianProposal->jadwal)) }}</b>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endif
                    @if ($statusPengajuan == 'Ujian Skripsi' || $statusPengajuan == 'Selesai')
                        <div class="panel widget-messages-alt panel-danger panel-dark"
                            style="border: none;box-shadow: -2px 2px 24px -5px rgba(0,0,0,0.53) !important;">
                            <div class="panel-heading">
                                <div>
                                    <div class="pull-left" id="informasi_ujian_proposal_title">Ujian Skripsi
                                        <span
                                            class="badge badge-{{ $colorUjianSkripsi }}">{{ strtoupper($ujianSkripsi->status) }}</span>
                                    </div>
                                </div>
                                <br>
                            </div>
                            <div class="panel-body padding-sm" style="overflow: hidden">
                                <div>Berikut informasi ujian proposal skripsi yang akan dilaksanakan
                                    <b>{{ $mahasiswa->nama }}</b> :
                                </div>
                                <table style="margin-left: 10px;margin-top: 10px">
                                    @foreach ($dosenPengujiUjianSkripsi as $item)
                                        <tr>
                                            <td>{{ ucfirst($item->jabatan) }}</td>
                                            <td style="padding-left: 10px">:</td>
                                            <td style="padding-left: 10px"><b
                                                    id="informasi_ujian_proposal_penguji_1">({{ $item->dosen->prodi->alias }})
                                                    {{ $item->dosen->nama }}</b>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>Jadwal Ujian</td>
                                        <td style="padding-left: 10px">:</td>
                                        <td style="padding-left: 10px"><b
                                                id="informasi_ujian_proposal_jadwal">{{ date('d-m-Y H:i', strtotime($ujianSkripsi->jadwal)) }}</b>
                                        </td>
                                    </tr>
                                </table>
                                @if ($ujianSkripsi->status == 'lolos')
                                    @if (@$skripsi->pengajuan->nilai_huruf)
                                        <blockquote class="bquote" style="margin-top: 10px">
                                            <div class="tx-orange tx-uppercase text-justify">
                                                <p>Selamat sudah <b>LULUS</b> skripsi dengan nilai:
                                                    <b>{{ @$skripsi->pengajuan->nilai_angka }}
                                                        ({{ @$skripsi->pengajuan->nilai_huruf }})</b>
                                                </p>
                                            </div>
                                        </blockquote>
                                    @else
                                        <blockquote class="bquote" style="margin-top: 10px">
                                            <div class="tx-orange tx-uppercase text-justify">
                                                <p>Menunggu dosen penguji untuk input nilai skripsi</p>
                                            </div>
                                        </blockquote>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
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
                        <div>Dokumen proposal bisa didownload di bawah ini.</div>
                        <div>
                            <a href="{{ route('mhs_skripsi.downloadProposal', ['id' => $skripsi->id]) }}"
                                class="btn btn-success" style="margin-top:5px"><i class="fa fa-file"
                                    aria-hidden="true"></i> Download Proposal</a>
                        </div>
                        <div style="margin-top: 5px">Jika ingin mengubah proposal skripsi, klik tombol <b>Ganti Dokumen
                                Proposal</b>, edit skripsi hanya bisa dilakukan jika status skripsi <b>Baru, Diperiksa,
                                dan Ujian Proposal</b></div>
                        <div>
                            @if ($statusPengajuan == 'Baru' || $statusPengajuan == 'Diperiksa' || $statusPengajuan == 'Ujian Proposal')
                                <a id="btn_edit_skripsi" class="btn btn-warning" style="cursor:pointer;margin-top:5px" data-toggle="modal"
                                    data-target="#modal_edit" data-id="{{ $skripsi->id }}"
                                    data-judul="{{ $skripsi->judul }}"><i class="fa fa-pencil"
                                        aria-hidden="true"></i>
                                    Ganti Dokumen Proposal</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@if ($statusPengajuan == 'Baru' || $statusPengajuan == 'Diperiksa' || $statusPengajuan == 'Ujian Proposal')
    @include('site_mhs.mhs_skripsi.index.edit')
@endif
