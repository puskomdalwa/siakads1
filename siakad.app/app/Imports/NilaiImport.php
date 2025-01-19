<?php
namespace App\Imports;

use App\KRSDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;
use App\Prodi;
use App\Ref;
use App\ThAkademik;

class NilaiImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row){
      $th_akademik = ThAkademik::where('kode',$row['th_akademik'])->first();
      if($th_akademik){
        return new KRSDetail([
          'th_akademik_id'  => $th_akademik->id,
          'nim'      => $row['nim'],
          'nama_mhs'      => $row['nama_mhs'],
          'kode_mk'      => $row['kode_mk'],
          'nama_mk'      => $row['nama_mk'],
          'sks_mk'      => $row['sks_mk'],
          'smt_mk'      => $row['smt_mk'],
          'nilai_bobot'      => $row['nilai_bobot'],
          'nilai_huruf'      => $row['nilai_huruf'],
          'user_id'   => Auth::user()->id
        ]);
      }
    }
}

