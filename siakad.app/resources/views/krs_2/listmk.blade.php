<div class="table-responsive">
	<table id="serversideTable" class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="text-center col-md-1">Cek<br/>Semua<br/>
				<input type="checkbox" name="checkAll" id="checkAll" value=""></th>				
				<th class="text-center col-md-1" style="vertical-align:middle">KODE</th>
				<th class="text-center col-md-3" style="vertical-align:middle">MATA KULIAH</th>
				<th class="text-center" style="vertical-align:middle">SKS</th>
				<th class="text-center" style="vertical-align:middle">SMT</th>
				<th class="text-center" style="vertical-align:middle">KLP</th>
				<th class="text-center col-md-3" style="vertical-align:middle">DOSEN</th>
				<th class="text-center " style="vertical-align:middle">RUANG</th>
				<!-- <th class="text-center " style="vertical-align:middle">SISA</th> -->
				<th class="text-center " style="vertical-align:middle">HARI</th>
				<th class="text-center col-md-1" style="vertical-align:middle">WAKTU</th>
				</tr>
		</thead>
	</table>
</div>

@push('demo')
<script>
init.push(function () {
	$('#c-tooltips-demo a').tooltip();
});
</script>
@endpush

@push('scripts')
<script type="text/javascript">
$("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
    var jml = $('#cek_list:checked').length;
    $("#dipilih").html(jml);
});

//$("#nim").on('change',function(){
 //  dataTable.draw();
//});

function cekList(jadwalkuliah_id,sks){
	var sks		  = parseFloat(sks);
	var max_sks	  = parseFloat($("#max_sks").val());
	var sks_total = parseFloat($("#sks_total").val());
	var ceklist	  = $("#cek_list_"+jadwalkuliah_id+":checked").length;
	
	if(ceklist==true){
		var total_sks = sks_total + sks;
	}else{
		var total_sks = sks_total - sks;
	}

	if(parseFloat(total_sks) > max_sks){
		$("#cek_list_"+jadwalkuliah_id).prop("checked",false);
		swal('Error Maksimum SKS!','Batas Maksimum '+max_sks+ ' SKS','error');
		return false;
	}
	
	$("#sks_total").val(total_sks);
}
</script>
@endpush
