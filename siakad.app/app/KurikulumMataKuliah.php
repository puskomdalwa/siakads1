<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class KurikulumMataKuliah extends Model{
	protected $table = 'trans_kurikulum_matakuliah';

	public function kurikulum(){
		return $this->belongsTo('App\Kurikulum','kurikulum_id');
	}

	public function matakuliah(){
		return $this->belongsTo('App\MataKuliah','matakuliah_id');
	}
}
