<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class SkripsiUjianProposalDosen extends Model
{

    protected $table = 'skripsi_ujian_proposal_dosen';

    public function ujianProposal()
    {
        return $this->belongsTo('App\SkripsiUjianProposal', 'ujian_proposal_id', 'id');
    }

    public function dosen()
    {
        return $this->belongsTo('App\Dosen', 'mst_dosen_id', 'id');
    }
}
