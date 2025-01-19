<?php
namespace App\Imports;

use App\MataKuliah;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;
use App\Prodi;

class MataKuliahImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row){
        $prodi = Prodi::where('kode',$row['kode_prodi'])->first();
        if($prodi){
          return new MataKuliah([
            'prodi_id'  => $prodi->id,
            'kode'      => $row['kode'],
            'nama'      => $row['nama'],
            'sks'       => $row['sks'],
            'smt'       => $row['smt'],
            'aktif'     => 'Y',
            'user_id'   => Auth::user()->id
          ]);
        }
    }
}
