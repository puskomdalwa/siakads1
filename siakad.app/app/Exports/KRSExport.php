<?php
namespace App\Exports;

use App\KRS;
use App\KRSDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class KRSExport implements FromView {
    use Exportable;

    public function ThAkademikId(int $thakademikid){
        $this->thakademikid = $thakademikid;
        return $this;
    }

    public function view(): View {
		$prodi_id = @$this->prodi_id;
        return view('exports.krs', [
            'krs' => KRSDetail::where('th_akademik_id',$this->thakademikid)
            
			// ->where('prodi_id',$this->prodi_id)
            // ->when($prodi_id, function ($query) use ($prodi_id) {
            //     return $query->where('prodi_id',$this->prodi_id);
            // })
			
            ->orderBy('nim','asc')
            ->orderBy('kode_mk','asc')->get()
        ]);
    }
}
