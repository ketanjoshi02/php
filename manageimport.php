<?php include 'config.php';
session_start();
?>

<form method="post" enctype="multipart/form-data" class="form-horizontal"  role="form">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">Upload CSV File</h3>
		</div>
		<div class="row panel-body">
			<div class="row">
				<div class="col-sm-6 col-sm-offset-4">
					<div class="form-group">
						<label for="export">Upload your CSV file</label>
						<span>(supported file format: .csv only)</span>
						<input type="file" id="export" name="export">
						<progress id="progressBar" value="0" max="100" style="width:300px;"></progress>
						<div id="status_progress"></div>
						<div id="status"></div>
						<p id="loaded_n_total"></p>
					</div>
				</div>
			</div>	        
			<div class="row">
				<div class="col-sm-6">
					<button type="button" class="btn btn-primary" id="upload" name="upload" onclick="uploadFile();">Upload</button>
				</div>
				<div class="col-sm-3">
					<a href="javascript:void(0);" onclick="downloadFile();" class="download">Downoad CSV File Format</a>
				</div>	
			</div>
		</div>
	</div>
</form>
									  
<style type="text/css">
	.download{
		color: #286090;
		cursor: pointer;
		display: inline-block;
		font-size: 15px;
		margin-top: 12px;
	}
	.download:hover,.download:focus{
		color: #286090;
		text-decoration: none;
		outline: medium none;
	}
</style>
									  
<script type="text/javascript">
// This is for download
function downloadFile(){
   window.location.href = '/download_csv/csv_file_format.csv';
}

// This is for upload file 
function _(el){
	return document.getElementById(el);
}
function uploadFile(){
	var file = _("export").files[0];
	// alert(file.name+" | "+file.size+" | "+file.type);
	var formdata = new FormData($('form')[0]);
	formdata.append("export", file);
	var ajax = new XMLHttpRequest();
	ajax.upload.addEventListener("progress", progressHandler, false);
	ajax.addEventListener("load", completeHandler, false);
	ajax.addEventListener("error", errorHandler, false);
	ajax.addEventListener("abort", abortHandler, false);
	ajax.open("POST", "upload-csv-import-leads.php");
	ajax.send(formdata);
}
function progressHandler(event){
	//_("loaded_n_total").innerHTML = "Uploaded "+event.loaded+" bytes of "+event.total;
	var percent = (event.loaded / event.total) * 100;
	_("progressBar").value = Math.round(percent);
	_("status_progress").innerHTML = Math.round(percent)+"% uploaded... please wait";
}
function completeHandler(event){
	_("status").innerHTML = event.target.responseText;
	_("progressBar").value = 0;
	$('form')[0].reset();	
	
	_("status_progress").innerHTML = "";
	var str = event.target.responseText;
	if(str.split(/\s+/).slice(2,8).join(" ") == "Following leads have been successfully imported:"){
		var delay = 3000;
		setTimeout(function(){ totalLeads(); }, delay);
	}
}
function errorHandler(event){
	_("status").innerHTML = "Upload Failed"+event.target.responseText;
	$('form')[0].reset();
}
function abortHandler(event){
	_("status").innerHTML = "Upload Aborted"+event.target.responseText;
	$('form')[0].reset();
}
</script>