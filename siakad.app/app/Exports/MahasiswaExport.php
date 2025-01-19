<?php
namespace App\Exports;

use App\Mahasiswa;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MahasiswaExport implements FromView{ 
    public function __construct(string $thakademikid, string $prodi_id){
        $this->thakademikid = $thakademikid;
        $this->prodi_id = $prodi_id;
    }

    public function view(): View{   
        $prodi_id = @$this->prodi_id;
        return view('exports.mahasiswa',[
            'data' => Mahasiswa::where('th_akademik_id',$this->thakademikid)
            ->when($prodi_id, function ($query) use ($prodi_id) {
                return $query->where('prodi_id',$this->prodi_id);
            })->get()
        ]);        
    }
}
