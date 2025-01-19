<div class="tab-pane fade" id="profile-tabs-prodi">
  <table class="table table-bordered table-hover table-striped">
    <tbody>
      <tr>
        <td class="col-md-2">Program Studi</td>
        <td>: {{@$data->prodi->nama}}</td>
      </tr>
      <tr>
        <td class="col-md-2">NIDN Kepala</td>
        <td>: {{@$data->prodi->nidn_kepala}}</td>
      </tr>
      <tr>
        <td class="col-md-2">Nama Kepala</td>
        <td>: {{@$data->prodi->nama_kepala}}</td>
      </tr>
      <tr>
        <td class="col-md-2">Kelas</td>
        <td>: {{@$data->kelas->nama}}</td>
      </tr>
      <tr>
        <td class="col-md-2">Kelompok</td>
        <td>: {{@$data->kelompok->perwalian->kelompok->kode}}</td>
      </tr>
      <tr>
        <td class="col-md-2">Dosen Wali</td>
        <td>: {{@$data->kelompok->perwalian->dosen->nama}}</td>
      </tr>
    </tbody>
  </table>
</div> <!-- / .tab-pane -->
