<div class="widget-article-comments tab-pane no-padding no-border fade in active" id="profile-tabs-biodata">
    <table class="table table-hover">
        <tbody>
            <tr style="border: none">
                <td style="border: none" class="col-md-2">Status</td>
                <td style="border: none">: {!! strtolower($data->status->nama) == 'aktif'
                    ? '<span class="label label-success">' . $data->status->nama . '</span>'
                    : '<span class="label label-danger">' . $data->status->nama . '</span>' !!}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Kode</td>
                <td style="border: none">: {{ $data->kode }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">NIDN</td>
                <td style="border: none">: {{ $data->nidn }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Nama Lengkap</td>
                <td style="border: none">: {{ $data->nama }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Jenis Kelamin</td>
                <td style="border: none">: {{ $data->jk->nama }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Tempat Lahir</td>
                <td style="border: none">: {{ $data->tempat_lahir }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Tanggal Lahir</td>
                <td style="border: none">: {{ tgl_str($data->tanggal_lahir) }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Alamat</td>
                <td style="border: none">: {{ $data->alamat }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Kota</td>
                <td style="border: none">: {{ @$data->kota->name }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">Email</td>
                <td style="border: none">: {{ $data->email }}</td>
            </tr>

            <tr style="border: none">
                <td style="border: none" class="col-md-2">HP</td>
                <td style="border: none">: {{ $data->hp }}</td>
            </tr>
        </tbody>
    </table>
    <a href="{{ route('biodata.dosen.edit') }}" class="btn btn-md btn-primary" style="width: 100%;margin-top:20px">
        <i class="fa fa-pencil"></i> Edit Biodata </a>
</div>