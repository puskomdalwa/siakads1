<div class="tab-pane fade" id="profile-tabs-prodi" style="border: none">
    <table class="table table-hover">
        <tbody>
            <tr style="border: none">
                <td style="border: none" class="col-md-2">Program Studi</td>
                <td style="border: none">: {{ @$data->prodi->nama }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Kelas</td>
                <td style="border: none">: {{ @$data->kelas->nama }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Kelompok</td>
                <td style="border: none">: {{ @$data->kelompok->perwalian->kelompok->kode }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" colspan="2" class="bg-dalwa">Ketua Program Studi</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">NIDN</td>
                <td style="border: none">: {{ @$data->prodi->nidn_kepala }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Nama</td>
                <td style="border: none">: {{ @$data->prodi->nama_kepala }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" colspan="2" class="bg-dalwa">Dosen Wali / Pembimbing</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Kode - Nama</td>
                <td style="border: none">: {{ @$data->kelompok->perwalian->dosen->kode }} -
                    {{ @$data->kelompok->perwalian->dosen->nama }}
                </td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Email</td>
                <td style="border: none">: {{ @$data->kelompok->perwalian->dosen->email }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">HP</td>
                <td style="border: none">: {{ @$data->kelompok->perwalian->dosen->hp }}</td>
            </tr>
        </tbody>
    </table>
</div> <!-- / .tab-pane -->
