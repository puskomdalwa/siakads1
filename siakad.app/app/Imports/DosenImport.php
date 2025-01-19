<?php
namespace App\Imports;

use App\Dosen;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;
use App\Prodi;
use App\Ref;

class DosenImport implements ToModel, WithHeadingRow {
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row){
        $prodi = Prodi::where('kode',$row['kode_prodi'])->first();
        if($prodi){
			return new Dosen([
				'prodi_id'	=> $prodi->id,
				'kode'      => $row['kode'],
				'nidn'      => $row['nidn'],
				'nama'      => $row['nama'],
				'jk_id'     => $this->getJK($row['jenis_kelamin']),
				'dosen_status_id' => 23,
				'user_id'   => Auth::user()->id
			]);
        }
    }

    private function getJK($kode){
		$jk = Ref::where('table','JenisKelamin')->where('kode',$kode)->first();
		if($jk){
			return $jk->id;
		}else{
			return null;
		}
    }
}
