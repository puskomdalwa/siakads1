<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class SkripsiUjianProposal extends Model
{

    protected $table = 'skripsi_ujian_proposal';

    public function pengajuan()
    {
        return $this->belongsTo('App\SkripsiPengajuan', 'skripsi_pengajuan_id', 'id');
    }

    public function ujianProposalDosen(){
        return $this->hasMany('App\SkripsiUjianProposalDosen', 'ujian_proposal_id', 'id');
    }
}
