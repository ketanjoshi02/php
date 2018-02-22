<?php include 'config.php';
session_start();
?>
<form action="/get_leads.php" method="post" name="export_form" id="export_form" class="export_form"> 
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">Download CSV File</h3>
		</div>
		<div class="row panel-body">
			<div class="row">
				<div class="col-sm-4 col-sm-offset-4">
					<div class="form-group export_title">
						Standard Filter :
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4 col-sm-offset-4">
					<div class="form-group">
						<select name="lead_option" class="" id="lead_option">
							<option value="">Select Lead Option</option>
							<option value="all_leads">All Leads</option>
							<option value="all_enabled_leads">Exclude Deleted/Archived Leads</option>
							<option value="all_deleted_leads">Deleted/Archived Leads</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4 col-sm-offset-4">
					<div class="form-group export_title">
						Advanced Filters :
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4 col-sm-offset-4">
					<div class="form-group">
						<div id="lead_filter_option_container"></div>		
					</div>
				</div>
			</div>			
			<div class="row">
				<div class="col-sm-6">
					<input type="button" class="btn btn-primary export" id="export" name="export" value="Download Leads" />
				</div>
				<div class="col-sm-2">	
					<input type="button" class="btn btn-primary export" id="reset" name="reset" value="Reset" />
				</div>
			</div>
		</div>			
	</div>
</form>

<div style="display:none;" class="lead_filter_option_values">
	<div class="lead_filter_option_value">
		<select name="lead_filter_option[]" class="lead_filter_option" id="lead_filter_option">
			<option value="">Select a filter</option>
			<option value="dba">DBA</option>
			<option value="lead_owner">Lead Owner</option>
			<option value="lead_source">Lead Source</option>
			<option value="lead_stage">Lead Stage</option>
			<option value="lead_status">Lead Status</option>
		</select>
		<select name="lead_filter_option_detail[]" class="lead_filter_option_detail" id="lead_filter_option_detail">	
			<option value="">Select Sub Filter</option>
		</select>	
		<div class="add_delete"> 		
			<a href="javascript:void(0);" class="addfilter addmore"><img src="/images/add_more.png" /></a>
			<a href="javascript:void(0);" class="deletefilter addmore" ><img src="/images/delete_more.png" /></a>
		</div>		
	</div>
</div>

<style type="text/css">
a.addmore{
	padding: 5px;
}
.add_delete{
	display: inline-block;
}
.export_title{
	background-color: #337ab7;
	color: #ffffff;
	font-size: 15px;
	font-weight: bold;
	padding: 4px;
	width: 85%;
}
.lead_filter_option_detail{
	width:134px;
}
</style>
						
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script type="text/javascript">
	$( document ).ready(function() {
		
		var count = 1;	
		
		add_more_lead_filter_option(count);
		
		$("#export").click(function(){ 
				var lead_option = $("#lead_option").val();
				var lead_filter_option = $(".lead_filter_option").val();
				var lead_filter_option_detail = $(".lead_filter_option_detail").val();
								
				if(lead_filter_option == "" && lead_filter_option_detail == "" && lead_option == ""){
					alert("Please select Standard Filter or Advanced Filters for export leads.");
					return false;
				}
				else if(lead_filter_option != "" && lead_filter_option_detail == ""){
					alert("Please "+$(".lead_filter_option_detail option:first").text()+" for export leads.");
					return false;
				}
				else{
					$.ajax({
						type: "POST",
						dataType: "JSON",
						cache: false,       
						url : document.location.origin + '/get_leads.php',
						data: $("#export_form").serialize(),
						success : function(data){
							if(data == 1){
								$("#export_form").submit();
							}
							else{
								alert("There are no records with this combinations.");
							}
						}
					});	
				}	
		});
		
		$("#reset").click(function(){
			manageExport();
			breadExport();
		});		
	});
	
	function add_more_lead_filter_option(count){
		var clone = $(".lead_filter_option_values").clone();		
			 
		clone.find('.lead_filter_option').attr("id","lead_filter_option"+count);	
		clone.find('.lead_filter_option_detail').attr("id","lead_filter_option_detail"+count);		
		count = count + 1;
		
		$("#lead_filter_option_container").append(clone.find(".lead_filter_option_value"));	
		
		// get stream on change
		$(".lead_filter_option").unbind("change");
		$(".lead_filter_option").on("change", function () {
			if($(this).val() != ""){
				getFilterSubValues($(this),$(this).val());
			}
		});
		
		remove_first_delete_lead_filter_option_detail();
		add_lead_filter_option_detail(count);
		delete_lead_filter_option_detail();
	}
	function getFilterSubValues(thisObj,filter_name){
		$.ajax({	
				type: "POST",
				dataType: "JSON",
				cache: false,       
				url : document.location.origin + '/get_leads.php', // change path on live server
				data : {'lead_filter_option': filter_name},
				beforeSend: function() {
				  $("#loading-image").show();
				},
				success : function(data){
					$("#loading-image").hide();
					if(data != 0){				
						thisObj.parent().find(".lead_filter_option_detail").html('');
						
						if(filter_name == "dba")
							thisObj.parent().find(".lead_filter_option_detail").html('<option value="">Select DBA</option>');
						else if(filter_name == "lead_owner")
							thisObj.parent().find(".lead_filter_option_detail").html('<option value="">Select Lead Owner</option>');
						else if(filter_name == "lead_source")
							thisObj.parent().find(".lead_filter_option_detail").html('<option value="">Select Lead Source</option>');
						else if(filter_name == "lead_stage")
							thisObj.parent().find(".lead_filter_option_detail").html('<option value="">Select Lead Stage</option>');
						else if(filter_name == "lead_status")
							thisObj.parent().find(".lead_filter_option_detail").html('<option value="">Select Lead Status</option>');
							
						$(data).each(function (index) {
							if(filter_name == "dba"){
								if(data[index].user_company_id != undefined && data[index].user_company_name != undefined){
									thisObj.parent().find(".lead_filter_option_detail").append('<option value="'+data[index].user_company_id+'">'+data[index].user_company_name+'</option>');
								}	
							}
							else if(filter_name == "lead_owner"){
								if(data[index].lead_owner != undefined){
									thisObj.parent().find(".lead_filter_option_detail").append('<option value="'+data[index].lead_owner+'">'+data[index].lead_owner+'</option>');
								}	
							}	
							else if(filter_name == "lead_source"){
								if(data[index].lead_source != undefined){
									thisObj.parent().find(".lead_filter_option_detail").append('<option value="'+data[index].lead_source+'">'+data[index].lead_source+'</option>');
								}	
							}	
							else if(filter_name == "lead_stage"){
								if(data[index].lead_stage != undefined){
									thisObj.parent().find(".lead_filter_option_detail").append('<option value="'+data[index].lead_stage+'">'+data[index].lead_stage+'</option>');
								}	
							}	
							else if(filter_name == "lead_status"){
								if(data[index].lead_status_id != undefined && data[index].lead_status_name != undefined){
									thisObj.parent().find(".lead_filter_option_detail").append('<option value="'+data[index].lead_status_id+'">'+data[index].lead_status_name+'</option>');
								}	
							}	
						});
					}
					else{
						thisObj.parent().find(".lead_filter_option_detail").html('<option value="">Select Sub Filter</option>');	
					}
				}            
		 });
	}
	function remove_first_delete_lead_filter_option_detail(){
		var i = 0;			 
		$("#lead_filter_option_container .lead_filter_option_value").each(function(){
			i = i + 1;
		});
		if(i == 1){
			$("#lead_filter_option_container .lead_filter_option_value .add_delete").each(function(){
				$(this).find(".deletefilter").remove();
			});	
		}
	}
	function add_lead_filter_option_detail(count){
		 var j = 0, k = 0;	
		 $(".addfilter").off("click");
		 $(".addfilter").on("click", function () {
			  add_more_lead_filter_option(count);
			  $("#lead_filter_option_container .lead_filter_option_value").each(function(){
					j = j + 1;
			  });
			  if(j == 2){
					$("#lead_filter_option_container .lead_filter_option_value:nth-child(1) .add_delete:nth-child(3) div .deletefilter").each(function(){ 
						k = 1;							
					});
					if(k == 0){
						$("#lead_filter_option_container .lead_filter_option_value:nth-child(1) .add_delete:nth-child(3)").find(".addfilter").parent().append('<a href="javascript:void(0);" class="deletefilter addmore"><img src="/images/delete_more.png" /></a>');
						delete_lead_filter_option_detail();
					}
			  }
			  j = 0, k = 0;
		 });
	}
	function delete_lead_filter_option_detail(){
		var i = 0;	
		$(".deletefilter").off("click");
		$(".deletefilter").on("click", function () {
		  $("#lead_filter_option_container .lead_filter_option_value").each(function(){
				i = i + 1;
		  });
		  if(i > 1){
				$(this).parent().parent().remove();
		  }
		  if(i == 2){
				$("#lead_filter_option_container .lead_filter_option_value .add_delete").each(function(){
					$(this).find(".deletefilter").remove();
				});	
		  }
		  i = 0;	
		});
	}
</script>


<?php /* Select records from type_of_lead table for type of lead
/*$sql2 = "Select lead_type_id,lead_type_name from type_of_lead";
$result2 = $conn->prepare($sql2);
$result2->execute();
$result2->setFetchMode(PDO::FETCH_ASSOC);
?>
<select name="lead_details" class="lead_details" id="lead_details">
<option value="">Select Lead Type</option>
<?php while ($row = $result2->fetch() ) { ?>
<option value="<?php echo $row["lead_type_id"];?>"><?php echo $row["lead_type_name"];?></option>
<?php } ?>	
</select>*/ ?>