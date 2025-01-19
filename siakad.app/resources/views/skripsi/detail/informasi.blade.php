@push('css')
    <style>
        @media screen and (max-width: 768px) {
            .profile-row {
                padding: 0px !important;
            }
        }
    </style>
@endpush

<div class="container" id="informasi">
    <div class="card">
        <h6 class="title">Detail Skripsi {{ $mahasiswa->nama }}</h6>
        <p class="card-subtitle">Detail informasi terkait detail skripsi yang berisi dari file proposal skripsi sampai
            selesai.
        </p>
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
                        <span id="span_status_skripsi">
                            <b>{{ strtoupper($statusPengajuan) }}</b><br>
                            <button id="btn_status_skripsi" class="btn btn-{{ @$color }}"
                                style="margin-top:5px">{{ strtoupper($statusPengajuan) }}</button>
                        </span>
                    </div>
                </div>

                <div id="informasi_ujian"></div>

                <div id="judul_terpilih" class="panel widget-messages-alt panel-danger panel-dark"
                    style="border: none;box-shadow: -2px 2px 24px -5px rgba(0,0,0,0.53) !important;">
                    <div class="panel-heading">
                        <div>
                            <div class="pull-left">Judul Skripsi ACC</div>
                        </div>
                        <br>
                    </div>
                    <div class="panel-body padding-sm" style="overflow: hidden">
                        <blockquote class="bquote">
                            <div class="tx-orange tx-uppercase text-justify">
                                @if ($skripsiAcc)
                                    <p id="judul_skripsi">{{ strtoupper($skripsiAcc->judul) }}</p>
                                @else
                                    <p id="judul_skripsi">Belum ada judul yang diACC</pi>
                                @endif
                            </div>
                            @if ($skripsiAcc)
                                <footer class="tx-black" id="footer_judul_skripsi">Judul Skripsi yang diACC</footer>
                            @else
                                <footer class="tx-black" id="footer_judul_skripsi"></footer>
                            @endif
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
    <script>
        function updateJudulSkripsi(judulSkripsi) {
            if (judulSkripsi) {
                $('#judul_skripsi').html(judulSkripsi.toUpperCase());
                $('#footer_judul_skripsi').html('Judul Skripsi yang diACC');
            } else {
                $('#judul_skripsi').html('Belum ada judul yang diACC');
                $('#footer_judul_skripsi').html('');
            }
        }

        function updateStatusSkripsi(status, color) {
            status = status.toUpperCase();
            let content = `<b>${status}</b><br>
            <button id="btn_status_skripsi" class="btn btn-${color}"
            style="margin-top:5px">${status}</button>`;
            $('#span_status_skripsi').empty();
            $('#span_status_skripsi').append(content);

            if (status == "BARU" || status == "SELESAI") {
                $('#form_ujian_proposal_submit').hide();
                $('#delete_ujian_proposal').hide();
                $('#update_status_ujian_proposal').hide();

                $('#form_acc_judul_submit').hide();
                $('#delete_pembimbing').hide();

                $('#form_ujian_skripsi_submit').hide();
                $('#delete_ujian_skripsi').hide();
                $('#update_status_ujian_skripsi').hide();

                $('#form_nilai_skripsi_submit').hide();
                $('#kosongkan_nilai_skripsi').hide();
                
                if (status == "SELESAI") {
                    $('#form_nilai_skripsi_submit').show();
                    $('#kosongkan_nilai_skripsi').show();
                }
            }

            if (status == "BIMBINGAN") {
                hideInformasiUjian();
                $('#form_ujian_proposal_submit').hide();
                $('#delete_ujian_proposal').hide();
                $('#update_status_ujian_proposal').hide();

                $('#form_acc_judul_submit').show();
                $('#delete_pembimbing').show();

                $('#form_ujian_skripsi_submit').show();
                $('#delete_ujian_skripsi').show();
                $('#update_status_ujian_skripsi').show();

                $('#form_nilai_skripsi_submit').hide();
                $('#kosongkan_nilai_skripsi').hide();
            }

            if (status == "DIPERIKSA") {
                hideInformasiUjian();
                $('#form_ujian_proposal_submit').show();
                $('#delete_ujian_proposal').show();
                $('#update_status_ujian_proposal').show();

                $('#form_acc_judul_submit').hide();
                $('#delete_pembimbing').hide();

                $('#form_ujian_skripsi_submit').hide();
                $('#delete_ujian_skripsi').hide();
                $('#update_status_ujian_skripsi').hide();

                $('#form_nilai_skripsi_submit').hide();
                $('#kosongkan_nilai_skripsi').hide();
                $('#kosongkan_nilai_skripsi').hide();
            }

            if (status == "UJIAN PROPOSAL") {
                getDosenPenguji();
                $('#form_ujian_proposal_submit').show();
                $('#delete_ujian_proposal').show();
                $('#update_status_ujian_proposal').show();

                $('#form_acc_judul_submit').hide();
                $('#delete_pembimbing').hide();

                let statusUjianProposal = $('#status_ujian_proposal_badge .badge').text();
                if (statusUjianProposal == "lolos") {
                    $('#form_acc_judul_submit').show();
                    $('#delete_pembimbing').show();
                }

                $('#form_ujian_skripsi_submit').hide();
                $('#delete_ujian_skripsi').hide();
                $('#update_status_ujian_skripsi').hide();

                $('#form_nilai_skripsi_submit').hide();
                $('#kosongkan_nilai_skripsi').hide();
            }

            if (status == "UJIAN SKRIPSI") {
                getDosenPengujiSkripsi();
                $('#form_ujian_skripsi_submit').show();
                $('#delete_ujian_skripsi').show();
                $('#update_status_ujian_skripsi').show();

                $('#form_ujian_proposal_submit').hide();
                $('#delete_ujian_proposal').hide();
                $('#update_status_ujian_proposal').hide();

                $('#form_acc_judul_submit').hide();
                $('#delete_pembimbing').hide();

                $('#form_nilai_skripsi_submit').hide();
                $('#kosongkan_nilai_skripsi').hide();

                let statusUjianSkripsi = $('#status_ujian_skripsi_badge .badge').text();
                if (statusUjianSkripsi == "lolos") {
                    $('#form_nilai_skripsi_submit').show();
                    $('#kosongkan_nilai_skripsi').show();
                }
            }
        }

        function showInformasiUjian(title, penguji1, penguji2, jadwal, status) {

            if ($('#btn_status_skripsi').html().toLowerCase() != title.toLowerCase()) {
                return;
            }
            let content = `
            <div class="panel widget-messages-alt panel-danger panel-dark"
                    style="border: none;box-shadow: -2px 2px 24px -5px rgba(0,0,0,0.53) !important;">
                    <div class="panel-heading">
                        <div>
                            <div class="pull-left" id="informasi_ujian_proposal_title">${title}
                                <span id="informasi_ujian_proposal_status"></span>
                                </div>
                        </div>
                        <br>
                    </div>
                    <div class="panel-body padding-sm" style="overflow: hidden">
                        <div>Berikut informasi ujian proposal skripsi yang akan dilaksanakan
                            <b>{{ $mahasiswa->nama }}</b> :
                        </div>
                        <table style="margin-left: 10px;margin-top: 10px">
                            <tr>
                                <td>Penguji 1</td>
                                <td style="padding-left: 10px">:</td>
                                <td style="padding-left: 10px"><b>${penguji1}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>Penguji 2</td>
                                <td style="padding-left: 10px">:</td>
                                <td style="padding-left: 10px"><b>${penguji2}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>Jadwal Ujian</td>
                                <td style="padding-left: 10px">:</td>
                                <td style="padding-left: 10px"><b>${jadwal}</b>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            `;
            $('#informasi_ujian').empty();
            $('#informasi_ujian').append(content);
            updateBadgeStatusInformasiUjian(status);
        }

        function updateBadgeStatusInformasiUjian(status) {
            $('#informasi_ujian_proposal_status').empty();
            let color = 'secondary';
            if (status == "lolos") {
                color = 'success'
            }
            if (status == "tidak lolos") {
                color = 'danger'
            }
            if (status == "belum ujian") {
                color = 'warning'
            }
            $('#informasi_ujian_proposal_status').append(
                `<span class="badge badge-${color}">${status.toUpperCase()}</span>`
            );
        }

        function hideInformasiUjian() {
            $('#informasi_ujian').empty();
        }
    </script>
@endpush
