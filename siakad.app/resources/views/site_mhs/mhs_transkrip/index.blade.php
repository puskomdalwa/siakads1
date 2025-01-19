@extends('layouts.app')
@section('title',$title)

@section('content')
<form action=" {{ route($redirect.'.store') }} " class="form-horizontal" method="post" target="blank">
<!-- <form action=" {{ route($redirect.'.store') }} " class="form-horizontal" method="POST" name="formInput" id="formInput" > -->
    {{ csrf_field() }}
	
    <div class="panel widget-messages-alt panel-danger panel-dark">
        <div class="panel-heading">
		<span class="panel-title"><i class="panel-title-icon fa fa-envelope"></i>{{$title}}</span></div>
	
		<div class="panel-body">
            <u><h1 class="text-center">TRANSKRIP NILAI SEMENTARA</h1></u>
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
						<th class="text-center" style="vertical-align:middle" rowspan="2">ID</th>
						<th class="text-center" style="vertical-align:middle" rowspan="2">NO</th>
						<th class="text-center" style="vertical-align:middle" rowspan="2">SEMESTER</th>
						<th class="text-center" style="vertical-align:middle" rowspan="2">KODE</th>
						<th class="text-center" style="vertical-align:middle" rowspan="2">MATA KULIAH</th>
						<th class="text-center" style="vertical-align:middle" rowspan="2">SKS</th>
						<th class="text-center" style="vertical-align:middle" colspan="2">NILAI</th>
						<th class="text-center" style="vertical-align:middle" rowspan="2">S X N</th>
                    </tr>

                    <tr>
						<th class="text-center" style="vertical-align:middle">ANGKA</th>
						<th class="text-center" style="vertical-align:middle">HURUF</th>
                    </tr>
                </thead>

                <tbody>
                    @php
						$no=1;
						$tsks =0;
						$tsn=0;    
                    @endphp

                    @foreach($data as $row)
						@php $sn = $row->nilai_bobot * $row->sks_mk; @endphp
						<tr>
							<td class="text-center"> {{ $row->id }} </td>
							<td class="text-center"> {{ $no++ }} </td>
							<td class="text-center"> {{ strtoupper($row->smt_mk) }} </td>
							<td class="text-center"> {{ strtoupper($row->kode_mk) }} </td>
							<td> {{ strtoupper($row->nama_mk) }} </td>
							<td class="text-center"> {{ strtoupper($row->sks_mk) }} </td>
							<td class="text-center"> {{ number_format($row->nilai_bobot,2) }} </td>
							<td class="text-center"> {{ $row->nilai_huruf }} </td>
							<td class="text-center"> {{ number_format($sn,2) }} </td>
						</tr>
						
						@php
							$tsks +=$row->sks_mk;
							$tsn +=$sn;    
						@endphp
                    @endforeach
                </tbody>
				
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-center">Total</td>
                        <td class="text-center"> {{ @$tsks }} </td>
                        <td></td>
                        <td></td>
                        <td class="text-center"> {{ @$tsn }} </td>
                    </tr>
					
                    <tr>
                        <td colspan="4" class="text-center">IPK</td>
                        <td colspan="4" class="text-center"> {{ @number_format($tsn/$tsks,2) }} </td>
                    </tr>
                </tfoot>
            </table>            
        </div>
        
        <div class="panel-footer text-center">
            <button type="button" class="btn btn-info" name="btnKirim" id="btnKirim">
            <i class="fa fa-share"></i> Kirim Permohonan </button>
			
			<!--
			@php $nim = '202085200107'; @endphp	
			-->
			
			
			<button type="submit" class="btn btn-info" name="btnCetak" id="btnCetak">
				<i class="fa fa-print"></i> Cetak </a> 
			</button>
			
			<!--
			<div class="panel-footer text-center">
				<button type="submit" class="btn btn-primary"><i class="fa fa-print"></i> Cetak </button>
			</div>
		
			<button type="submit" class="btn btn-xs btn-info" name="btnCeak" id="btnCetak">
				<i class="fa fa-print"></i> 
				<a href="{{url('mhs_transkrip/'.$nim.'/cetakTranskrip')}}" class="btn btn-xs btn-info" target="_blank"> Cetak </a> 
			</button>
			-->
			
			<!--
			<a href="{{url('MhsTranskrip/'.$nim.'/cetakTranskrip')}}" class="btn btn-xs btn-info" target="_blank"> 
			<i class="fa fa-print"></i>  Cetak </a>
			</button>
			-->

        </div>
    </div>
</form>

<div class="note note-warning">
    <h4 class="note-title">Perhatian !!!!</h4>
    Silahkan Kirim Permohonan Terlebih dahulu untuk mencetak Transkrip Nilai Sementara.
</div>
@endsection

@push('scripts')
<script>
$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});

$("#btnKirim").on('click',function(){
	var dt = $('#formInput').serialize();
	// console.log(dt);
	$.ajax({
		data: dt,
		url : "{{ url($redirect) }}"+'/getKirim',
		type: "POST",
		dataType: 'json',
		success: function (data) {
			Swal.fire(data.title,data.info,data.status);
		},
		error: function (data) {
			Swal.fire('ERROR','Silahkan hubungi Administrator','error');
		}
	});
});
</script>
@endpush
