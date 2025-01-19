<div class="tab-pane fade in active" id="profile-tabs-biodata" style="border: none">
    <table class="table table-hover" style="border: none">
        <tbody>
            <tr style="border: none">
                <td style="border: none" class="col-md-2">Tahun Akademik</td>
                <td style="border: none">: {{ $data->th_akademik->kode }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Status</td>
                <td style="border: none">: {{ @$data->status->nama }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Tanggal Masuk</td>
                <td style="border: none">: {{ @tgl_str($data->tanggal_masuk) }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">NIM</td>
                <td style="border: none">: {{ $data->nim }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">NIK</td>
                <td style="border: none">: {{ $data->nik }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Nama Lengkap</td>
                <td style="border: none">: {{ $data->nama }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Jenis Kelamin</td>
                <td style="border: none">: {{ @$data->jk->nama }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Tempat Lahir</td>
                <td style="border: none">: {{ $data->tempat_lahir }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Tanggal Lahir</td>
                <td style="border: none">: {{ @tgl_str($data->tanggal_lahir) }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Agama</td>
                <td style="border: none">: {{ @$data->agama->nama }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Alamat</td>
                <td style="border: none">: {{ $data->alamat }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Kota</td>
                <td style="border: none">: {{ @$data->kota->name }}</td>
            </tr>

            <tr style="border: none;">
                <td style="border: none" class="col-md-2">Email</td>
                <td style="border: none">: {{ $data->email }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">HP</td>
                <td style="border: none">: {{ $data->hp }}</td>
            </tr>
        </tbody>
    </table>

    <a href="{{ route('biodata.mhs.edit') }}" class="btn btn-md btn-primary" style="width: 100%;margin-top:20px">
        <i class="fa fa-pencil"></i> Edit Biodata </a>
</div>
