<?php
namespace App\Imports;

use App\Mahasiswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;
use App\Prodi;
use App\Ref;
use App\ThAkademik;

class KeuanganPembayaranImport implements ToModel, WithHeadingRow {
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row){
		$th_akademik = ThAkademik::where('kode',$row['th_akademik'])->first();
		if($th_akademik){
			$prodi = Prodi::where('kode',$row['kode_prodi'])->first();
			if($prodi){
				$kelas = Ref::where('table','Kelas')->where('kode',$row['kode_kelas'])->first();
				if($kelas){
					return new Mahasiswa([
						'th_akademik_id'=> $th_akademik->id,
						'tanggal_masuk' => $row['anggal_masuk'],
						'prodi_id'		=> $prodi->id,
						'kelas_id'  	=> $kelas->id,
						'status_id'     => 18,
						'nik'	    	=> $row['nik'],
						'nim'      		=> $row['nim'],
						'nama'      	=> $row['nama'],
						'jk_id'       	=> $this->getJK($row['jenis_kelamin']),
						'tempat_lahir' 	=> $row['tempat_lahir'],
						'tanggal_lahir' => $row['tanggal_lahir'],
						'agama_id' 	   	=> $row['agama'],
						'alamat'    	=> $row['alamat'],
						'kota_id'    	=> $row['kota_id'],
						'email'    		=> $row['email'],
						'hp'    		=> $row['hp'],
						'nama_ayah'    	=> $row['nama_ayah'],
						'nama_ibu'    	=> $row['nama_ibu'],
						'user_id'   	=> Auth::user()->id
					]);
				}
			}
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
