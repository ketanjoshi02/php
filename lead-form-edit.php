<?php 
include 'config.php'; session_start();
// time_since() counts how many seconds,minutes, hours...etc ago
if($_SESSION['sess_userrole']==1 || $_SESSION['sess_userrole']==3){
	$editabledec=999999;
}else{
	$editabledec= -1;
}
function time_since($since) {
	$chunks = array(
		array(60 * 60 * 24 * 365 , 'year'),
		array(60 * 60 * 24 * 30 , 'month'),
		array(60 * 60 * 24 * 7, 'week'),
		array(60 * 60 * 24 , 'day'),
		array(60 * 60 , 'hour'),
		array(60 , 'minute'),
		array(1 , 'second')
	);
	for ($i = 0, $j = count($chunks); $i < $j; $i++) {
		$seconds = $chunks[$i][0];
		$name = $chunks[$i][1];
		if (($count3 = floor($since / $seconds)) != 0) {
			break;
		}
	}
	$print = ($count3 == 1) ? '1 '.$name : "$count3 {$name}s";
	return $print;
}
//to remove notification when lead is visited
try{
	$sql="UPDATE lead_stage_detail SET enabled=0 WHERE lead_id=?";
	$stmt=$conn->prepare($sql);
	$stmt->execute(array($_POST['lead_id']));
}catch(PDOException $e) { 
	echo "Error: " . $e->getMessage();
}
if(isset($_POST['lead_id'])){
	$_SESSION['leadID'] = $lead_id = $_POST['lead_id'];
	try{
		$sql="SELECT * FROM leads JOIN type_of_lead ON leads.lead_type_id=type_of_lead.lead_type_id WHERE lead_id=?"; 
		$stmt=$conn->prepare($sql);
		$stmt->execute(array($_SESSION['leadID']));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$_SESSION['Edit-form']=1;
		$_SESSION['sess_lead_type_id']=$result['lead_type_id'];
		try{
			$q="SELECT * FROM lead_status_detail JOIN users ON users.user_id=lead_status_detail.user_id WHERE lead_status_detail.lead_id=:lead_id ORDER BY lead_status_timestamp DESC LIMIT 1";
			$query=$conn->prepare($q);
			$query->execute(array(':lead_id'=>$_SESSION['leadID']));
			$row = $query->fetch(PDO::FETCH_ASSOC);
?> 
<div style="float:right; margin-right:5px;">
<?php
			if($query->rowCount() > 0){ 
				if($result['lead_status_id']==1) {		
				  	echo "<img src='images/warning2.png' width='16' height='16' /> Lead is Pending By ".$row['user_fname']." ".$row['user_lname']." AT: ".date("d-M-Y  g:i:s A", strtotime($row['lead_status_timestamp'])).".";
				}elseif ($result['lead_status_id']==2) {
					echo "<img src='images/tick.png' width='16' height='16' /> Lead is Approved By ".$row['user_fname']." ".$row['user_lname']." AT: ".date("d-M-Y  g:i:s A", strtotime($row['lead_status_timestamp'])).".";
				}elseif ($result['lead_status_id']==3) {
					echo "<img src='images/untick.png' width='16' height='16' /> Lead is Rejected By ".$row['user_fname']." ".$row['user_lname']." AT: ".date("d-M-Y  g:i:s A", strtotime($row['lead_status_timestamp'])).".";
				}elseif($result['lead_status_id']==4){
					echo "<img src='images/assignment.png' width='16' height='16' /> Lead is Assigned to ".$result['lead_owner'];
				}else{}
			}else{
				if($result['lead_status_id']==1) {		
				  	echo "<img src='images/warning2.png' width='16' height='16' /> Lead is Pending.";
				}elseif ($result['lead_status_id']==2) {
					echo "<img src='images/tick.png' width='16' height='16' /> Lead is Approved.";
				}elseif ($result['lead_status_id']==3) {
					echo "<img src='images/untick.png' width='16' height='16' /> Lead is Rejected.";
				}elseif($result['lead_status_id']==4){
					echo "<img src='images/assignment.png' width='16' height='16' /> Lead is Assigned to ".$result['lead_owner'];
				}else{}
			} 
?>
<!-- added by dipika start -->
<input type="hidden" name="rowcount" id="rowcount" value="<?php echo $query->rowCount(); ?>" />
<input type="hidden" name="lead_status_id" id="lead_status_id" value="<?php echo $result['lead_status_id']; ?>" />
<input type="hidden" name="lead_status_id_details" id="lead_status_id_details" value="<?php echo $row['user_fname']." ".$row['user_lname']." AT: ".date("d-M-Y  g:i:s A", strtotime($row['lead_status_timestamp'])); ?>" />
<input type="hidden" name="lead_owner" id="lead_owner" value="<?php echo $result['lead_owner']; ?>" />
<!-- added by dipika end -->
</div>
<?php
		}catch(PDOException $e) { 
			echo "Error: " . $e->getMessage();
		}
?>
<div class="clear"></div>
<?php 
if($result['lead_protected_flag']==0){
	echo "<i style='color:red;' class='fa fa-unlock fa-3x'></i> Lead is not protected!";
}
else{
	echo "<i style='color:green;' class='fa fa-lock fa-3x'></i> Lead is protected!";
}
?>
<!-- added by dipika start -->
<input type="hidden" name="lead_protected" id="lead_protected" value="<?php echo $result['lead_protected_flag']; ?>" />
<div id="ad_div" style="float: right; margin: 20px 6px 15px;">
	<a href="#" title="Print" onclick="printContent('ad_div')"><i class="fa fa-print fa-3x" aria-hidden="true"></i></a>
</div>
<!-- added by dipika over -->
<div class="row">
	<div class="col-sm-12">
	  <div class="pull-right">
		 Lead id: <b><?php echo $_SESSION['leadID']; ?></b>
	  </div>
	</div>
</div>
<form class="form-horizontal" action="#" method="post" id="myLeadForm">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<p style="float:right;">
				Last Updated on date: 
				<i>
				<?php 
					if($result['modified_date']!=""){
						echo date("d-M-Y", strtotime($result['modified_date']));
					?>
						<input type="hidden" name="modified_date" id="modified_date" value="<?php echo date("d-M-Y", strtotime($result['modified_date'])); ?>" />
					<?php	
					}
					else{
						echo date("d-M-Y", strtotime($result['date']));
					?>
						<input type="hidden" name="modified_date" id="modified_date" value="<?php echo date("d-M-Y", strtotime($result['date'])); ?>" />
					<?php	
					}
				?>
				</i>
			</p>
			<p style="margin: 0px auto; float: right; width: 464px;">
				Last Stage change date: 
				<i>
				<?php 
					echo date("d-M-Y", strtotime($result['lead_status_change_date']));
				?>
				</i>
				<input type="hidden" name="lead_status_change_date" id="lead_status_change_date" value="<?php echo date("d-M-Y", strtotime($result['lead_status_change_date'])); ?>" />
			</p>
			<h3 class="panel-title">
				Lead Information
			</h3>
		</div>
		<div class="row btns">
			<div class="col-sm-2">
				<?php if($result['lead_archive_status']==0){ ?>
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="float:left;" >Assign Lead</button>
				<?php } ?>
			</div>
			<!-- added and above div structure changed by dipika start -->
			<div class="col-sm-2">
				<?php if($_SESSION['sess_userrole'] == 1){
							if($result['watch_list'] == "yes"){ $disabled = "disabled"; } else { $disabled = ""; } ?>
							<button type="button" class="btn btn-primary" id="watch_list" style="float:left;" onclick="add_to_watch_list(<?php echo $_SESSION['leadID']; ?>);" <?php echo $disabled; ?>>Add to Watch List</button> 
				<?php } ?>
			</div>
			<!-- added and above div structure changed by dipika end -->
			<div class="col-sm-3">
				<?php if( $result['lead_archive_status']==0 ){ ?>
					<button type="button" class="btn btn-primary" style="padding:8px 40px" id="update-lead">Update</button>
				<?php } ?>
			</div>
			<div class="col-sm-5">
				<?php	if( ($_SESSION['sess_userrole']==1 || $_SESSION['sess_userrole']==2 || $_SESSION['sess_userrole']==4) && $result['lead_archive_status']==0 ){ ?>
					<div class="lead_status_btn">
						<select id="btnApproval" class="form-control">
							<option value="<?php echo $result['lead_status_id'];?>">Lead Status</option>
							<option value="1">Pending</option>
							<option value="2">Approved</option>
							<option value="3">Reject</option>
						</select>
					</div>
				<?php 
					}elseif( ($_SESSION['sess_dbatype'] != 1 && $_SESSION['sess_userrole']==5) && $result['lead_archive_status']==0 ){
						if( ($result['lead_type_id']==1 || $result['lead_type_id']==2 || $result['lead_type_id']==5 || $result['lead_type_id']==6) && $result['user_id'] != $_SESSION['sess_user_id']){
				?>
					<div class="lead_status_btn">
						<select id="btnApproval" class="form-control">
							<option value="<?php echo $result['lead_status_id'];?>">Lead Status</option>
							<option value="1">Pending</option>
							<option value="2">Approved</option>
							<option value="3">Reject</option>
						</select>
					</div>
				<?php
						}else{}
					}else{} 
					?>		
			</div>								
		</div>	
		<!-- div added by dipika start -->
		<div id="print_form">		
			<div class="row panel-body">			
				<div class="col-sm-6">
					<div class="form-group">
						<label for="entered-by" class="control-label col-xs-5">Lead Entered By: <span class="red"></span> </label>
						<div class="col-xs-7">
							<input type="text" name="entered-by" class="form-control" id="entered-by" value="<?php echo $result['lead_entered_by'];?>" readonly="true" />
						</div>
					</div>
					<div class="form-group">
						<label for="owner-name" class="control-label col-xs-5">Lead Owner: <span class="red">*</span> </label>
						<div class="col-xs-7">
							<input type="text" name="owner-name" class="form-control" id="owner-name" value="<?php echo $result['lead_owner'];?>" readonly="true" />
						</div>
					</div>
					<div class="form-group">
						<label for="fname" class="control-label col-xs-5">First Name <span class="red">*</span> </label>
						<div class="col-xs-7">
							<input type="text" name="fname" class="form-control" id="fname" value="<?php echo $result['fname']; ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label for="lname" class="control-label col-xs-5">Last Name <span class="red">*</span> </label>
						<div class="col-xs-7">
							<input type="text" name="lname" class="form-control" id="lname" value="<?php echo $result['lname']; ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label for="title" class="control-label col-xs-5">Title <span class="red">*</span></label>
						<div class="col-xs-7">
							<input type="text" name="title" class="form-control" id="title" value="<?php echo $result['title']; ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="control-label col-xs-5">Email <span class="red">*</span> </label>
						<div class="col-xs-7">
							<input type="email" name="email" class="form-control" id="email" value="<?php echo $result['email']; ?>" required />
							<div id="email_domain_match_error"></div>
						</div>
					</div>
					<div class="form-group">
						<label for="secondary-email" class="control-label col-xs-5">Secondary Email </label>
						<div class="col-xs-7">
							<input type="text" name="secondary-email" class="form-control" id="secondary-email" value="<?php echo $result['secondary_email']; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label for="skypeid" class="control-label col-xs-5">Skype ID </label>
						<div class="col-xs-7">
							<input type="text" name="skypeid" class="form-control" id="skypeid" value="<?php echo $result['skypeid']; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label for="phone" class="control-label col-xs-5">Phone <span class="red">*</span> </label>
						<div class="col-xs-7">
							<input type="text" name="phone" class="form-control" id="phone" value="<?php echo $result['phone']; ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label for="fax" class="control-label col-xs-5">Fax </label>
						<div class="col-xs-7">
							<input type="text" name="fax" class="form-control" id="fax" value="<?php echo $result['fax']; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label for="mobile" class="control-label col-xs-5">Alternate# </label>
						<div class="col-xs-7">
							<input type="text" name="mobile" class="form-control" id="mobile" value="<?php echo $result['mobile']; ?>" />
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="company" class="control-label col-xs-5">Customer Name <span class="red">*</span> </label>
						<div class="col-xs-7">
							<input type="text" name="company" class="form-control" id="company" value="<?php echo $result['company']; ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label for="lead-source" class="control-label col-xs-5">Lead Source </label>
						<div class="col-xs-7">
							<select name="lead-source" class="form-control" id="lead-source" >
								<option value="<?php echo $result['lead_source']; ?>"><?php echo $result['lead_source']; ?></option>
								<option value="" disabled="disabled">----------------</option>
								<option value="Advertisement">Advertisement</option>
								<option value="Cold Call">Cold Call</option>
								<option value="Internal Referral">Internal Referral</option>
								<option value="External Referral">External Referral</option>
								<option value="Website">Website</option>
								<option value="Partner">Partner</option>
								<option value="Public Relations">Public Relations</option>
								<option value="Sales Mail Alias">Sales Mail Alias</option>
								<option value="Seminar Partner">Seminar Partner</option>
								<option value="Seminar-Internal">Seminar-Internal</option>
								<option value="Trade Show">Trade Show</option>
								<option value="Web Research">Web Research</option>
								<option value="Chat">Chat</option>
							</select>
						</div>
					</div>
					<div id="txtByWho">
					<?php 
						if($result['who_which']!=""){
					?>
						<div class="form-group">
							<label for="lead-source-who" class="control-label col-xs-5">Who/Which? : </label>
							<div class="col-xs-7">	
								<input type="text" name="lead-source-who" id="lead-source-who" class="form-control" value="<?php echo $result['who_which']; ?>" required />
							</div>
						</div>
					<?php 
						}
					?>
					</div>
					<div class="form-group">
						<label for="lead-status" class="control-label col-xs-5">Lead Stages <span class="red">*</span> </label>
						<div class="col-xs-7">
							<select name="lead-status" class="form-control" id="lead-status">
								<option value="<?php echo $result['lead_status']; ?>"><?php echo $result['lead_status']; ?></option>
								<option value="" disabled="disabled">----------------</option>
								<option value="1.Discovery">1.Discovery</option>
								<option value="2.Scheduled">2.Scheduled</option>
								<option value="3.Qualified">3.Qualified</option>
								<option value="4.Proposal">4.Proposal</option>
								<option value="5.PoP">5.PoP</option>
								<option value="6.Decisioning">6.Decisioning</option>
								<option value="7.Signed">7.Signed</option>
								<option value="8.Installation">8.Installation</option>
								<option value="9.Completed">9.Completed</option>
								<option value="10.NoAction">10.NoAction</option>				
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="date-of-presentation" class="control-label col-xs-5">Date of First Presentation: <span class="red">*</span> </label>
						<div class="col-xs-7">
						<?php 
							if($result['date_of_first_presentation']=='0000-00-00 00:00:00'){
						?>
							<div class="input-group date" data-provide="datepicker">
								<input type="text" name="date-of-presentation" class="form-control" id="date-of-presentation" required />
								<div class="input-group-addon">
								<span class="glyphicon glyphicon-th"></span>
							 </div>
							</div>
						<?php
							}else{
						?>
						<div class="input-group date" data-provide="datepicker">
							<input type="text" name="date-of-presentation" class="form-control" id="date-of-presentation" value="<?php echo date("d-M-Y", strtotime($result['date_of_first_presentation']) ); ?>" readonly="readonly" required />
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-arrow-right" id="edit-dofp"></span>
							 </div>
							</div>
						<?php 
							}
							if ($_SESSION['sess_userrole']==1 || $_SESSION['sess_userrole']==3 || $_SESSION['sess_userrole']==2 || $_SESSION['sess_userrole']==5 || $_SESSION['sess_userrole']==6) {
						?>
							<a href="#" onclick="editDOFP();" style="color: #3a7ead;">edit</a>
						<?php	
							}
						?>
						</div>
					</div>
					<div class="form-group">
						<label for="follow-up-date" class="control-label col-xs-5">Follow-Up Date: </label>
						<?php if($result['follow_up_date'] == '0000-00-00'){ $follow_up_date = ""; $add_class = "col-xs-7"; } else { $follow_up_date = date("d-M-Y", strtotime($result['follow_up_date'])); $add_class = "col-xs-6"; }?>
						<div class = <?php echo $add_class; ?>>
							<div class="input-group date" data-provide="datepicker">
								<input type="text" name="follow-up-date" class="form-control" id="follow-up-date" value="<?php echo $follow_up_date; ?>" required />
								<div class="input-group-addon">
								 <span class="glyphicon glyphicon-th"></span>
							  </div>
							</div>
						</div>
						<?php if($follow_up_date != "") { ?>
							<div style="padding: 7px 0 0; display: inline-block;">
								<a onclick="clear_follow_up_date('<?php echo $result['lead_id']; ?>');" href="javascript:void(0);" style="color: blue; text-decoration: none; font-size: 14px; margin: 0 0 0 -10px;">Clear</a>
							</div>
						<?php } ?>
					</div>
					<div class="form-group">
						<label for="type-of-lead" class="control-label col-xs-5">Lead Type <span class="red">*</span> </label>
						<div class="col-xs-7">
							<select name="type-of-lead" id="type-of-lead" class="form-control">
									<option value="<?php echo $result['lead_type_id']; ?>"><?php echo $result['lead_type_name']; ?></option>
									<option value="" disabled="disabled">----------------</option>
									<option value="1">Local</option>
									<option value="2">Regional</option>
									<option value="9">Multi-Regional</option>
									<option value="3">National</option>
								<option value="4">Multinational</option>
								<option value="8">OEM</option>
								  <optgroup label="Government">
										<option value="5">Local Government</option>
										<option value="6">State Government</option>
										<option value="7">Federal Government</option>
								  </optgroup>
							</select>
							<div id="lead-type-approval"></div>
						</div>
					</div>
					<div class="form-group">
						<label for="industry" class="control-label col-xs-5">Primary Industry Type <span class="red">*</span> </label>
						<div class="col-xs-7">
							<select name="industry" class="form-control" id="industry">
								<option value="<?php echo $result['industry']; ?>"><?php echo $result['industry']; ?></option>
								<option value="" disabled="disabled">----------------</option>
								<option value="Agricultural Processing">Agricultural Processing</option>
								<option value="Auto-Car Dealership">Auto-Car Dealership</option>
								<option value="Data Centers">Data Centers</option>
								<option value="Entertainment">Entertainment</option>
								<option value="Food Distribution">Food Distribution</option>
								<option value="Government">Government</option>
								<option value="Grocery/Supermarket">Grocery/Supermarket</option>
								<option value="Healthcare">Healthcare</option>
								<option value="Hospitality">Hospitality</option>
								<option value="Manufacturing">Manufacturing</option>
								<option value="OEM">OEM</option>
								<option value="Real Estate-Office Building Mgt">Real Estate-Office Building Mgt</option>
								<option value="Refrigerated Storage-Distribution Center">Refrigerated Storage-Distribution Center</option>
								<option value="Refrigerated Transportation">Refrigerated Transportation</option>
								<option value="Restaurant">Restaurant</option>
								<option value="Retail Cooling-Refrigeration">Retail Cooling-Refrigeration</option>
								<option value="Telecom">Telecom</option>
								<option value="Warehouse-Distribution Center">Warehouse-Distribution Center</option>
								<option value="Other">Other</option>
							</select>
						</div>
					</div>
					<div id="txtOther">
					<?php 
						if($result['industry_other']!=""){
							if($result['industry']==="Other"){
					?>
						<div class="form-group">
							<label for="other" class="control-label col-xs-5">Your Industry <span class="red">*</span> </label>
							<div class="col-xs-7">	
								<input type="text" name="other" id="other" class="form-control" value="<?php echo $result['industry_other']; ?>" required />
							</div>
						</div>
					<?php 
						}
					}
					?>
					</div>
					<div class="form-group">
						<label for="no-of-employees" class="control-label col-xs-5">No. of Locations </label>
						<div class="col-xs-7">
							<select name="no-of-employees" class="form-control" id="no-of-employees">
								<option value="<?php echo $result['no_of_emp']; ?>"><?php echo $result['no_of_emp']; ?></option>
								<option value="" disabled="disabled">----------------</option>
								<option value="1-50">1-50</option>
								<option value="51-200">51-200</option>
								<option value="201-500">201-500</option>
								<option value="501 and above">501 and Above</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="annual-revenue" class="control-label col-xs-5">Annual Revenue (USD) </label>
						<div class="col-xs-7">
							<select name="annual-revenue" class="form-control" id="annual-revenue">
								<option value="<?php echo $result['annual_revenue']; ?>"><?php echo $result['annual_revenue']; ?></option>
								<option value="" disabled="disabled">----------------</option>
								<option value="1 - 500,000">1 - 500,000</option>
								<option value="500,001 - 1,000,000">500,001 - 1,000,000</option>
								<option value="1,000,001 - 5,000,000">1,000,001 - 5,000,000</option>
								<option value="5,000,001 - 10,000,000">5,000,001 - 10,000,000</option>
								<option value="10,000,000 +">10,000,000 +</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="twitter" class="control-label col-xs-5">Twitter </label>
						<div class="col-xs-7">
							<input type="text" name="twitter" class="form-control" id="twitter" value="<?php echo $result['twitter']; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label for="website" class="control-label col-xs-5">Website URL <span class="red">*</span> </label>
						<div class="col-xs-7">
							<input type="text" name="website" class="form-control" id="website" value="<?php echo $result['website']; ?>" required />
							<div id="domain_email_match_error"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-heading">
				<h3 class="panel-title">
					Address Information
				</h3>
			</div>
			<div class="row panel-body">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="street" class="control-label col-xs-5">Street </label>
						<div class="col-xs-7">
							<input type="text" name="street" class="form-control" id="street" value="<?php echo $result['street']; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label for="city" class="control-label col-xs-5">City <span class="red">*</span></label>
						<div class="col-xs-7">
							<input type="text" name="city" class="form-control" id="city" value="<?php echo $result['city']; ?>" required />
						</div>
					</div>
					<div class="form-group">
						<label for="state" class="control-label col-xs-5">State/Province </label>
						<div class="col-xs-7">
							<input type="text" name="state" class="form-control" id="state" value="<?php echo $result['state']; ?>" />
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="zip-code" class="control-label col-xs-5">Zip Code </label>
						<div class="col-xs-7">
							<input type="text" name="zip-code" class="form-control" id="zip-code" value="<?php echo $result['zipcode']; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label for="country" class="control-label col-xs-5">Country <span class="red">*</span> </label>
						<div class="col-xs-7">
							<select name="country" class="form-control" id="country">
								<option value="<?php echo $result['country']; ?>"><?php echo $result['country']; ?></option>
								<option value="" disabled="disabled">----------------</option>
								<option value="United States">United States</option>
								<option value="Canada">Canada</option>
								<option value="India">India</option>
								<option value="Afghanistan">Afghanistan</option>
								<option value="Aland Islands">Aland Islands</option>
								<option value="Albania">Albania</option>
								<option value="Algeria">Algeria</option>
								<option value="American Samoa">American Samoa</option>
								<option value="Andorra">Andorra</option>
								<option value="Angola">Angola</option>
								<option value="Anguilla">Anguilla</option>
								<option value="Antarctica">Antarctica</option>
								<option value="Antigua and Barbuda">Antigua and Barbuda</option>
								<option value="Argentina">Argentina</option>
								<option value="Armenia">Armenia</option>
								<option value="Aruba">Aruba</option>
								<option value="Australia">Australia</option>
								<option value="Austria">Austria</option>
								<option value="Azerbaijan">Azerbaijan</option>
								<option value="Bahamas">Bahamas</option>
								<option value="Bahrain">Bahrain</option>
								<option value="Bangladesh">Bangladesh</option>
								<option value="Barbados">Barbados</option>
								<option value="Belarus">Belarus</option>
								<option value="Belgium">Belgium</option>
								<option value="Belize">Belize</option>
								<option value="Benin">Benin</option>
								<option value="Bermuda">Bermuda</option>
								<option value="Bhutan">Bhutan</option>
								<option value="Bolivia, Plurinational State of">Bolivia, Plurinational State of</option>
								<option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
								<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
								<option value="Botswana">Botswana</option>
								<option value="Bouvet Island">Bouvet Island</option>
								<option value="Brazil">Brazil</option>
								<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
								<option value="Brunei Darussalam">Brunei Darussalam</option>
								<option value="Bulgaria">Bulgaria</option>
								<option value="Burkina Faso">Burkina Faso</option>
								<option value="Burundi">Burundi</option>
								<option value="Cambodia">Cambodia</option>
								<option value="Cameroon">Cameroon</option>
								<option value="Cape Verde">Cape Verde</option>
								<option value="Cayman Islands">Cayman Islands</option>
								<option value="Central African Republic<">Central African Republic</option>
								<option value="Chad">Chad</option>
								<option value="Chile">Chile</option>
								<option value="China">China</option>
								<option value="Christmas Island">Christmas Island</option>
								<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
								<option value="Colombia">Colombia</option>
								<option value="Comoros">Comoros</option>
								<option value="Congo">Congo</option>
								<option value="Congo, the Democratic Republic of the">Congo, the Democratic Republic of the</option>
								<option value="Cook Islands">Cook Islands</option>
								<option value="Costa Rica">Costa Rica</option>
								<option value="Cote d'Ivoire">Cote d'Ivoire</option>
								<option value="Croatia">Croatia</option>
								<option value="Cuba">Cuba</option>
								<option value="Curacao">Curacao</option>
								<option value="Cyprus">Cyprus</option>
								<option value="Czech Republic">Czech Republic</option>
								<option value="Denmark">Denmark</option>
								<option value="Djibouti">Djibouti</option>
								<option value="Dominica">Dominica</option>
								<option value="Dominican Republic">Dominican Republic</option>
								<option value="Ecuador">Ecuador</option>
								<option value="Egypt">Egypt</option>
								<option value="El Salvador">El Salvador</option>
								<option value="Equatorial Guinea">Equatorial Guinea</option>
								<option value="Eritrea">Eritrea</option>
								<option value="Estonia">Estonia</option>
								<option value="Ethiopia">Ethiopia</option>
								<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
								<option value="Faroe Islands">Faroe Islands</option>
								<option value="Fiji">Fiji</option>
								<option value="Finland">Finland</option>
								<option value="France">France</option>
								<option value="French Guiana">French Guiana</option>
								<option value="French Polynesia">French Polynesia</option>
								<option value="French Southern Territories">French Southern Territories</option>
								<option value="Gabon">Gabon</option>
								<option value="Gambia">Gambia</option>
								<option value="Georgia">Georgia</option>
								<option value="Germany">Germany</option>
								<option value="Ghana">Ghana</option>
								<option value="Gibraltar">Gibraltar</option>
								<option value="Greece">Greece</option>
								<option value="Greenland">Greenland</option>
								<option value="Grenada">Grenada</option>
								<option value="Guadeloupe">Guadeloupe</option>
								<option value="Guam">Guam</option>
								<option value="Guatemala">Guatemala</option>
								<option value="Guernsey">Guernsey</option>
								<option value="Guinea">Guinea</option>
								<option value="Guinea-Bissau">Guinea-Bissau</option>
								<option value="Guyana">Guyana</option>
								<option value="Haiti">Haiti</option>
								<option value="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>
								<option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
								<option value="Honduras">Honduras</option>
								<option value="Hong Kong">Hong Kong</option>
								<option value="Hungary">Hungary</option>
								<option value="Iceland">Iceland</option>
								<option value="Indonesia">Indonesia</option>
								<option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
								<option value="Iraq">Iraq</option>
								<option value="Ireland">Ireland</option>
								<option value="Isle of Man">Isle of Man</option>
								<option value="Israel">Israel</option>
								<option value="Italy">Italy</option>
								<option value="Jamaica">Jamaica</option>
								<option value="Japan">Japan</option>
								<option value="Jersey">Jersey</option>
								<option value="Jordan">Jordan</option>
								<option value="Kazakhstan">Kazakhstan</option>
								<option value="Kenya">Kenya</option>
								<option value="Kiribati">Kiribati</option>
								<option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
								<option value="Korea, Republic of">Korea, Republic of</option>
								<option value="Kuwait">Kuwait</option>
								<option value="Kyrgyzstan">Kyrgyzstan</option>
								<option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
								<option value="Latvia">Latvia</option>
								<option value="Lebanon">Lebanon</option>
								<option value="Lesotho">Lesotho</option>
								<option value="Liberia">Liberia</option>
								<option value="Libya">Libya</option>
								<option value="Liechtenstein">Liechtenstein</option>
								<option value="Lithuania">Lithuania</option>
								<option value="Luxembourg">Luxembourg</option>
								<option value="Macao">Macao</option>
								<option value="Macedonia, the former Yugoslav Republic of">Macedonia, the former Yugoslav Republic of</option>
								<option value="Madagascar">Madagascar</option>
								<option value="Malawi">Malawi</option>
								<option value="Malaysia">Malaysia</option>
								<option value="Maldives">Maldives</option>
								<option value="Mali">Mali</option>
								<option value="Malta">Malta</option>
								<option value="Marshall Islands">Marshall Islands</option>
								<option value="Martinique">Martinique</option>
								<option value="Mauritania">Mauritania</option>
								<option value="Mauritius">Mauritius</option>
								<option value="Mayotte">Mayotte</option>
								<option value="Mexico">Mexico</option>
								<option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
								<option value="Moldova, Republic of">Moldova, Republic of</option>
								<option value="Monaco">Monaco</option>
								<option value="Mongolia">Mongolia</option>
								<option value="Montenegro">Montenegro</option>
								<option value="Montserrat">Montserrat</option>
								<option value="Morocco">Morocco</option>
								<option value="Mozambique">Mozambique</option>
								<option value="Myanmar">Myanmar</option>
								<option value="Namibia">Namibia</option>
								<option value="Nauru">Nauru</option>
								<option value="Nepal">Nepal</option>
								<option value="Netherlands">Netherlands</option>
								<option value="New Caledonia">New Caledonia</option>
								<option value="New Zealand">New Zealand</option>
								<option value="Nicaragua">Nicaragua</option>
								<option value="Niger">Niger</option>
								<option value="Nigeria">Nigeria</option>
								<option value="Niue">Niue</option>
								<option value="Norfolk Island">Norfolk Island</option>
								<option value="Northern Mariana Islands">Northern Mariana Islands</option>
								<option value="Norway">Norway</option>
								<option value="Oman">Oman</option>
								<option value="Pakistan">Pakistan</option>
								<option value="Palau">Palau</option>
								<option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
								<option value="Panama">Panama</option>
								<option value="Papua New Guinea">Papua New Guinea</option>
								<option value="Paraguay">Paraguay</option>
								<option value="Peru">Peru</option>
								<option value="Philippines">Philippines</option>
								<option value="Pitcairn">Pitcairn</option>
								<option value="Poland">Poland</option>
								<option value="Portugal">Portugal</option>
								<option value="Puerto Rico">Puerto Rico</option>
								<option value="Qatar">Qatar</option>
								<option value="Reunion">Reunion</option>
								<option value="Romania">Romania</option>
								<option value="Russian Federation">Russian Federation</option>
								<option value="Rwanda">Rwanda</option>
								<option value="Saint Barthelemy">Saint Barthelemy</option>
								<option value="Saint Helena, Ascension and Tristan da Cunha">Saint Helena, Ascension and Tristan da Cunha</option>
								<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
								<option value="Saint Lucia">Saint Lucia</option>
								<option value="Saint Martin (French part)">Saint Martin (French part)</option>
								<option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
								<option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
								<option value="Samoa">Samoa</option>
								<option value="San Marino">San Marino</option>
								<option value="Sao Tome and Principe">Sao Tome and Principe</option>
								<option value="Saudi Arabia">Saudi Arabia</option>
								<option value="Senegal">Senegal</option>
								<option value="Serbia">Serbia</option>
								<option value="Seychelles">Seychelles</option>
								<option value="Sierra Leone">Sierra Leone</option>
								<option value="Singapore">Singapore</option>
								<option value="Sint Maarten (Dutch part)">Sint Maarten (Dutch part)</option>
								<option value="Slovakia">Slovakia</option>
								<option value="Slovenia">Slovenia</option>
								<option value="Solomon Islands">Solomon Islands</option>
								<option value="Somalia">Somalia</option>
								<option value="South Africa">South Africa</option>
								<option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
								<option value="South Sudan">South Sudan</option>
								<option value="Spain">Spain</option>
								<option value="Sri Lanka">Sri Lanka</option>
								<option value="Sudan">Sudan</option>
								<option value="Suriname">Suriname</option>
								<option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
								<option value="Swaziland">Swaziland</option>
								<option value="Sweden">Sweden</option>
								<option value="Switzerland">Switzerland</option>
								<option value="Syrian Arab Republic">Syrian Arab Republic</option>
								<option value="Taiwan, Province of China">Taiwan, Province of China</option>
								<option value="Tajikistan">Tajikistan</option>
								<option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
								<option value="Thailand">Thailand</option>
								<option value="Timor-Leste">Timor-Leste</option>
								<option value="Togo">Togo</option>
								<option value="Tokelau">Tokelau</option>
								<option value="Tonga">Tonga</option>
								<option value="Trinidad and Tobago">Trinidad and Tobago</option>
								<option value="Tunisia">Tunisia</option>
								<option value="Turkey">Turkey</option>
								<option value="Turkmenistan">Turkmenistan</option>
								<option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
								<option value="Tuvalu">Tuvalu</option>
								<option value="Uganda">Uganda</option>
								<option value="Ukraine">Ukraine</option>
								<option value="United Arab Emirates">United Arab Emirates</option>
								<option value="United Kingdom">United Kingdom</option>
								<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
								<option value="Uruguay">Uruguay</option>
								<option value="Uzbekistan">Uzbekistan</option>
								<option value="Vanuatu">Vanuatu</option>
								<option value="Venezuela, Bolivarian Republic of">Venezuela, Bolivarian Republic of</option>
								<option value="Viet Nam">Viet Nam</option>
								<option value="Virgin Islands, British">Virgin Islands, British</option>
								<option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
								<option value="Wallis and Futuna">Wallis and Futuna</option>
								<option value="Western Sahara">Western Sahara</option>
								<option value="Yemen">Yemen</option>
								<option value="Zambia">Zambia</option>
								<option value="Zimbabwe">Zimbabwe</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="created_date" class="control-label col-xs-5">Created Date </label>
						<div class="col-xs-7">
							<input type="text" name="created_date" class="form-control" id="created_date" value="<?php echo date("d-M-Y  g:i:s A", strtotime($result['date'])); ?>" readonly="true" />
						</div>
					</div>
				</div>
				<div id="approval-warning"></div>
			</div>
			<div class="panel-heading">
				<h3 class="panel-title">Opportunity Description </h3>
			</div>
			<div class="row panel-body">
				<div class="col-sm-12">
					<div class="form-group">
						<label for="description" class="control-label col-xs-2">Description <span class="red">*</span></label>
						<div class="col-xs-10">
							<div class="reason"></div>
							<textarea name="description" class="form-control" id="description" readonly="true"><?php echo $result['lead_desc']; ?></textarea>
							<span id="characters"><h6></h6></span>
						</div>
					</div>
				</div>				
			</div>
			<div class="chat-panel panel panel-green">
				<div class="panel-heading">
					<?php 
					$cururl = $_POST['cururl'];
					$comment_about = "lead";
					$watch_list_selected = "";
					$comment_title = 'General Comments';
					if($cururl == "/managewatchlist.php"){
						$watch_list_selected = 'selected="selected"';
						$comment_title = 'Watch List Comments';
						$comment_about = "watch_list";
					}
					?> 
					<i class="fa fa-comments fa-fw"></i><span class="comment_title"><?php echo $comment_title; ?></span>
					<select name="comment_type" class="comment_type" id="comment_type">
						<option value="lead_comment">Lead Comment</option>
						<option value="follow_up_date_comment">Follow-Up Date Comment</option>
						<?php if($cururl == "/managewatchlist.php" && $_SESSION['sess_userrole'] == 1){
						if($result['watch_list'] == "yes"){ ?>							
							<option value="watch_list_comment" <?php echo $watch_list_selected; ?>>Watch List Comment</option>
						<?php }
						} ?>	
					</select>
			 	</div>
			 	<div class="panel-body">
					<ul class="chat" id="comments">
				 		<?php 
						try{
							if($comment_about == "lead"){
								$sql= "SELECT * FROM lead_comments JOIN users ON lead_comments.user_id=users.user_id WHERE lead_id = ?";
								$comm_name = 'comment_text';
								$comm_date = 'comment_date';
							}
							elseif($comment_about == "watch_list"){
								$sql= "SELECT * FROM watch_list_comment as wl JOIN users as u ON wl.user_id = u.user_id WHERE wl.status = 'enabled' AND wl.lead_id = ?";
								$comm_name = 'comment';
								$comm_date = 'modified_date';
							}
							$stmt = $conn->prepare($sql);
							$stmt->execute(array($lead_id));
							$stmt->setFetchMode(PDO::FETCH_ASSOC);
							while ($row = $stmt->fetch()) { 
								$comment_text = $row[$comm_name];
								$date = date("d-M-Y",strtotime($row[$comm_date]));
								?>
								<li class="left clearfix">
									<span class="chat-img pull-left">
										<img class="img-circle" alt="User Avatar" src="images/user.png" height="48" width="48" />
									</span>
									<div class="chat-body clearfix">
										<div class="header">
											<strong class="primary-font"><?php echo $row['user_fname']." ".$row['user_lname'];?></strong>
											<small class="pull-right text-muted">
												<i class="fa fa-clock-o fa-fw"></i> <?php echo $date; ?>
											</small>
										</div>
										<p><?php echo $comment_text; ?></p>
									</div>
								</li>
							<?php 
							}
						}
						catch(PDOException $e){ echo $sql . "<br>" . $e->getMessage(); } ?>
					</ul>
			  	</div>
			  	<?php if( $result['lead_archive_status']==0 ){ ?>
			  	<div class="panel-footer">
				  	<div class="input-group">
					 	<input type="text" class="form-control input-sm" name="comment_text" id="btn-input" placeholder="Enter Your message here..." />
					 	<span class="input-group-btn">
							<button id="btn-comment" class="btn btn-warning btn-sm btn-comment" type="button" style="background-color: rgb(92, 184, 92); border: 1px solid rgb(92, 184, 92);">Comment</button>
						</span>
					</div>
					<div class="comment-charcters"><h6></h6></div>
			  		<div class="desc_reason"></div>
				</div>
				<?php } ?>
			</div>
			
			<!-- upload files added by dipika start -->
			<div class="panel panel-yellow" style="margin: 0 13px 25px;">
				<div class="panel-heading">
					<i class="fa fa-upload fa-fw"></i>
					<span class="panel-title">Upload Document</span>
				</div>
				<div class="row panel-body">
				  <div class="row">
					 <div class="col-sm-4 col-sm-offset-1">
						<div class="form-group">
							  <label for="document">Upload your Document</label>
							  <input type="file" name="document" id="document">
							  <progress id="progressBar" value="0" max="100" style="width:500px; margin:25px 0 0; display:none;"></progress>
							  <p id="loaded_n_total" style="display:none;"></p>
						</div>
					 </div>
					 <div class="col-sm-3">
						<button onclick="uploadFile();" name="upload" id="upload" class="btn btn-primary" type="button" style="background-color: rgb(240, 173, 78); border: 1px solid rgb(240, 173, 78); margin: 16px 0px 0px; padding: 8px 40px;">Upload</button>
					 </div>
					 <!-- edit time display uploaded files added by dipika start -->
					 <div class="col-sm-12">
					 	<div id="status" style="margin-left: 90px; margin-top: 20px;">
						<?php 	
							$upoad_query = "SELECT * FROM lead_attachment_detail WHERE lead_id =".$_SESSION['leadID']." AND user_id = ".$_SESSION['sess_user_id']." AND status = 'enabled' order by id DESC";
							$query1 = $conn->prepare($upoad_query);
							$query1->execute();
							$query1->setFetchMode(PDO::FETCH_ASSOC);
							
							while($row = $query1->fetch()) { 
								$file_name_details = explode("/", $row['attachment_path']);								
								$fileName = $file_name_details[1];
								$user_file_extn = explode(".", strtolower($fileName));
								$extn = $user_file_extn[1];
								$id = $row['id'];
								if($extn == "pdf"){
									$msg = '<div style="float: left; margin: 10px 20px 20px 0px;"><div style="position: relative;"><a style="float: right; position: absolute; top: 0px; right: 0px;" href="javascript:void(0);" onclick="delete_document('.this.','.$id.');"><img src="images/close_icon.png"></a><a target="_blank" style="color: blue; float: left;" href="uploads_lead_details/'.$fileName.'"><img style="" src="images/pdf_icon.png"><br><font style="float: left; margin-top: 8px; font-weight: bold;">'.$fileName.'</font></a></div></div>';
								}	
								else if($extn == "csv"){
									$msg = '<div style="float: left; margin: 10px 20px 20px 0px;"><div style="position: relative;"><a style="float: right; position: absolute; top: 0px; right: 0px;" href="javascript:void(0);" onclick="delete_document('.this.','.$id.');"><img src="images/close_icon.png"></a><a target="_blank" style="color: blue; float: left;" href="uploads_lead_details/'.$fileName.'"><img style="" src="images/csv_icon.png"><br><font style="float: left; margin-top: 8px; font-weight: bold;">'.$fileName.'</font></a></div></div>';
								}	
								else if($extn == "xls" || $extn == "xlsx"){
									$msg = '<div style="float: left; margin: 10px 20px 20px 0px;"><div style="position: relative;"><a style="float: right; position: absolute; top: 0px; right: 0px;" href="javascript:void(0);" onclick="delete_document('.this.','.$id.');"><img src="images/close_icon.png"></a><a target="_blank" style="color: blue; float: left;" href="uploads_lead_details/'.$fileName.'"><img style="" src="images/xls_icon.png"><br><font style="float: left; margin-top: 8px; font-weight: bold;">'.$fileName.'</font></a></div></div>';
								}	
								else if($extn == "doc" || $extn == "docx"){
									$msg = '<div style="float: left; margin: 10px 20px 20px 0px;"><div style="position: relative;"><a style="float: right; position: absolute; top: 0px; right: 0px;" href="javascript:void(0);" onclick="delete_document('.this.','.$id.');"><img src="images/close_icon.png"></a><a target="_blank" style="color: blue; float: left;" href="uploads_lead_details/'.$fileName.'"><img style="" src="images/doc_icon.png"><br><font style="float: left; margin-top: 8px; font-weight: bold;">'.$fileName.'</font></a></div></div>';
								}	
								else if($extn == "ppt" || $extn == "pptx"){
									$msg = '<div style="float: left; margin: 10px 20px 20px 0px;"><div style="position: relative;"><a style="float: right; position: absolute; top: 0px; right: 0px;" href="javascript:void(0);" onclick="delete_document('.this.','.$id.');"><img src="images/close_icon.png"></a><a target="_blank" style="color: blue; float: left;" href="uploads_lead_details/'.$fileName.'"><img style="" src="images/ppt_icon.png"><br><font style="float: left; margin-top: 8px; font-weight: bold;">'.$fileName.'</font></a></div></div>';
								}	
								else{
									$msg = '<div style="float: left; margin: 10px 20px 20px 0px;"><div style="position: relative;"><a style="float: right; position: absolute; top: 0px; right: 0px;" href="javascript:void(0);" onclick="delete_document('.this.','.$id.');"><img src="images/close_icon.png"></a><a target="_blank" style="color: blue; float: left;" href="uploads_lead_details/'.$fileName.'"><font style="float: left; margin-top: 8px; font-weight: bold;">'.$fileName.'</font></a></div></div>';
								}
								echo $msg;
							}	
						?>	
						</div>	
					 </div>	
					 <!-- edit time display uploaded files added by dipika end -->
				  </div>		
				</div>
			 </div>
			<!-- upload files added by dipika end -->
			
			<div class="panel panel-info">
				<div class="panel-heading">
			  <i class="fa fa-clock-o fa-fw"></i> 
				Lead Action
			</div>
				<div class="panel-body">
				<ul>
			  <?php
			  try{
					$sql="SELECT * FROM assign JOIN users	ON assign.assignedto_userid=users.user_id JOIN leads ON assign.assignedto_userid=leads.lead_id WHERE assign.lead_id=?";
					$stmt = $conn->prepare($sql);
					$stmt->execute(array($_SESSION['leadID']));
					$stmt->setFetchMode(PDO::FETCH_ASSOC);
					if($stmt->rowCount()!=0){
					while ($row = $stmt->fetch()) {    			
					  $timestamp = strtotime($row['assign_date']);
								$date = date('d-M-Y  g:i A', $timestamp);
			  ?>
					<li>
					<p>
					  Lead assigned to  
						<strong>
						<?php 
							echo $row['user_fname']." ".$row['user_lname'];
						?>
						</strong> 
						by 
						<i>
						<?php 
							echo $row['assigned_by'];
						?>
						</i> 
						on date
						<small class="text-muted">
								<i class="fa fa-clock-o"></i> 
								<?php 
									echo $date;
								?>
							</small>
					</p>
				</li>
				 <?php 
					}
				 }else{
				 ?>
					<li>
					<p>
						Default:
						Lead assigned to 
						<strong>
						<?php 
							echo $result['lead_owner'];
						?>
						</strong> 
						on date
						<small class="text-muted">
							<i class="fa fa-clock-o"></i> 
								<?php 
									echo date("d-M-Y  g:i A", strtotime($result['date'])); 
								?>
						</small>
					</p>
				</li>
			  <?php	
				}
			  }catch(PDOException $e) {
						echo "Error: " . $e->getMessage();
					}
			  ?>     	
			  </ul>
			  <ul>
			  <?php
			  try{
				 $sql="SELECT * FROM lead_stage_detail	WHERE lead_id=? ORDER BY lead_stage_details_id ASC";
					$stmt = $conn->prepare($sql);
					$stmt->execute(array($_SESSION['leadID']));
				 $stmt->setFetchMode(PDO::FETCH_ASSOC);
				 while ($row = $stmt->fetch()) {
					$times = strtotime($row['lead_stage_date']);
							$date_stage = date('d-M-Y  g:i A', $times);
					?>
					<li>
					<p>
						Lead stage updated to 
						<b>
						<?php 
							echo $row['lead_stage']; 
						?>
						</b>
						by
						<?php
						try{
								$q="SELECT user_fname,user_lname FROM users WHERE user_id=?";
								$qu=$conn->prepare($q);
								$qu->execute(array($row['user_id']));
								$res = $qu->fetch(PDO::FETCH_ASSOC);
							echo $res['user_fname']." ".$res['user_lname'];
						}catch(PDOException $e){
									echo "Error: " . $e->getMessage();
								}
						?>
						on date
						<small class="text-muted">
							<i class="fa fa-clock-o"></i> 
							<?php 
								echo $date_stage;
							?>
						</small>
							.
					</p>
				 </li>
					<?php 
					}
					}catch(PDOException $e) {
							echo "Error: " . $e->getMessage();
						}
			  ?>
			  </ul>
			  <ul>
			  <?php
				try{
					$sql="SELECT archived_leads.lead_archived_time, users.user_fname, users.user_lname FROM archived_leads JOIN users ON users.user_id = archived_leads.user_id WHERE archived_leads.lead_id=?";
					$stmt = $conn->prepare($sql);
					$stmt->execute(array($_SESSION['leadID']));
					$row=$stmt->fetch(PDO::FETCH_ASSOC);
					if($stmt->rowCount()==0){
					}else{
						$arctime = strtotime($row['lead_archived_time']);
								$date_arc = date('d-M-Y  g:i A', $arctime);
					?>
					<li>
					<p>
						Lead has been deleted by 
						<b>
						<?php 
							echo $row['user_fname']." ".$row['user_lname']; 
						?>
						</b>
						on date
						<small class="text-muted">
							<i class="fa fa-clock-o"></i> 
							<?php
								echo $date_arc;
							?>
						</small>		                			
						.
					</p>
				</li>
					<?php 
							}
					}catch(PDOException $e) {
							echo "Error: " . $e->getMessage();
						}
			  ?>
			  </ul>
	
	
			  <ul>
			  <?php
			  try{
				 $sql="SELECT * FROM opportunity_desc_detail WHERE lead_id=? ORDER BY desc_change_date DESC";
					$stmt = $conn->prepare($sql);
					$stmt->execute(array($_SESSION['leadID']));
				 $stmt->setFetchMode(PDO::FETCH_ASSOC);
				 while ($row = $stmt->fetch()) {
					$times = strtotime($row['desc_change_date']);
							$date_stage = date('d-M-Y  g:i A', $times);
					?>
					<li>
					<p>
						Opportunity description updated by 
						<b>
						<?php 
							echo $row['desc_change_user']; 
						?>
						</b>
						on date
						<small class="text-muted">
							<i class="fa fa-clock-o"></i> 
							<?php 
								echo $date_stage;
							?>
						</small>
							.
					</p>
				 </li>
					<?php 
					}
					}catch(PDOException $e) {
					echo "Error: " . $e->getMessage();
				}
			  ?>
			  </ul>
	
			  <ul>
			  <?php
			  try{
				 $sql="SELECT * FROM date_of_first_presentation_detail WHERE lead_id=? ORDER BY change_date DESC";
					$stmt = $conn->prepare($sql);
					$stmt->execute(array($_SESSION['leadID']));
				 $stmt->setFetchMode(PDO::FETCH_ASSOC);
				 while ($row = $stmt->fetch()) {
					$times = strtotime($row['change_date']);
							$date_stage = date('d-M-Y  g:i A', $times);
					?>
					<li>
					<p>
						Date of first presentation updated by 
						<b>
						<?php 
							echo $row['date_change_user']; 
						?>
						</b>
						on date
						<small class="text-muted">
							<i class="fa fa-clock-o"></i> 
							<?php 
								echo $date_stage;
							?>
						</small>
							.
					</p>
				 </li>
					<?php 
					}
					}catch(PDOException $e) {
					echo "Error: " . $e->getMessage();
				}
			  ?>
			  </ul>
			  <ul>
			  	<?php
			  try{
				 $sql="SELECT lead_attachment_detail.added_date, lead_attachment_detail.modified_date as doc_modified_date, lead_attachment_detail.user_id as doc_added_user, lead_attachment_detail.deleted_user_id, lead_attachment_detail.status FROM lead_attachment_detail JOIN leads on leads.lead_id=lead_attachment_detail.lead_id WHERE lead_attachment_detail.lead_id=?";
					$stmt = $conn->prepare($sql);
					$stmt->execute(array($_SESSION['leadID']));
				 $stmt->setFetchMode(PDO::FETCH_ASSOC);
				 while ($row = $stmt->fetch()) {
					$times = strtotime($row['added_date']);
							$date_added = date('d-M-Y  g:i A', $times);
					$times2 = strtotime($row['doc_modified_date']);
							$date_modified = date('d-M-Y  g:i A', $times2);

						?>
						<li>
						<p>
							Document added by 
							<b>
							<?php
								try{
									$q="SELECT user_fname, user_lname FROM users WHERE user_id=?";
									$st=$conn->prepare($q);
									$st->execute(array($row['doc_added_user']));
									$st->setFetchMode(PDO::FETCH_ASSOC);
									while($res = $st->fetch()){
										echo $res['user_fname']." ".$res['user_lname'];
									}
									}catch(PDOException $e) {
										echo "Error: " . $e->getMessage();
									}
								
								//echo $row['date_change_user']; 
							?>
							</b>
							on date
							<small class="text-muted">
								<i class="fa fa-clock-o"></i> 
								<?php 
									echo $date_added;
								?>
							</small>
								.
						</p>
					 </li>
					<?php

					if ($row['deleted_user_id'] != 0 && $row['status'] == 'deleted') {
						?>
						<li>
						<p>
							Document deleted by 
							<b>
							<?php
								try{
									$q="SELECT user_fname, user_lname FROM users WHERE user_id=?";
									$st=$conn->prepare($q);
									$st->execute(array($row['deleted_user_id']));
									$st->setFetchMode(PDO::FETCH_ASSOC);
									while($res = $st->fetch()){
										echo $res['user_fname']." ".$res['user_lname'];
									}
									}catch(PDOException $e) {
										echo "Error: " . $e->getMessage();
									}
								
								//echo $row['date_change_user']; 
							?>
							</b>
							on date
							<small class="text-muted">
								<i class="fa fa-clock-o"></i> 
								<?php 
									echo $date_modified;
								?>
							</small>
								.
						</p>
					 </li>
						<?php
					} 
					}
					}catch(PDOException $e) {
					echo "Error: " . $e->getMessage();
				}
			  ?>
			  </ul>
			</div>
				<div class="panel-footer"></div>
			</div>
		</div>
		<!-- div added by dipika end -->
	</div>
</form>
<div class="clear"></div>
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">  
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="padding:25px 50px;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4>
        	<span class="glyphicon glyphicon-user"></span> 
        	Select users to assign
        </h4>
      </div>
      <div class="modal-body" style="padding:40px 50px;">   
		  	<form class="form-inline" method="POST" action="#" id="myAssignLeadForm">
					<div class="form-group">
						<label for="users-list">Users List: </label>
						<select id="users-list" name="multiselect[]" multiple="multiple">
					<?php 
						include 'config.php';
						try{
							if($_SESSION['sess_dbatype'] == 1 && $_SESSION['sess_userrole'] <= 4){
								$sql = "SELECT user_id, user_fname, user_lname FROM users";
								$stmt  = $conn->prepare($sql);
								$stmt->execute();	
						 	}else{
						 		$sql = "SELECT user_id, user_fname, user_lname FROM users WHERE user_company_id = :company_id";
								$stmt  = $conn->prepare($sql);
								$stmt->execute(array(':company_id' => $_SESSION['sess_usercomp_id']));
						 	}
					 		$stmt->setFetchMode(PDO::FETCH_ASSOC);
							while ($result = $stmt->fetch()) {
						?>
							<option value="<?php echo $result['user_id'];?>"><?php echo $result['user_fname']." ".$result['user_lname']; ?></option>
						<?php	
							}
						?>
						</select>
					</div>
					<?php
					 	}catch(PDOException $e) {
							echo "Error: " . $e->getMessage();
						}
					?>
					<input type="hidden" value="<?php echo $_SESSION['leadID']; ?>" id="leadsvalue" name="leadsvalue" />
					<button type="button" class="btn btn-default assign-user" id="assign-user" data-dismiss="modal" value="Assign">Assign</button>
		  	</form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
      </div>
    </div>  
  </div>
</div> 
<!--Modal end -->
<?php 	 
	}catch(PDOException $e) { 
		echo "Error: " . $e->getMessage();
	}	
?>
<?php
}else{}
?>

<style>
.comment_type{
	float:right;
}
</style>



<!-- this is added by dipika for upload file start -->
<script type="text/javascript">

function _(el){
	return document.getElementById(el);
}
function uploadFile(){	
	$('#progressBar').css("display","");
	$('#loaded_n_total').css("display","");
	var file = _("document").files[0];
	// alert(file.name+" | "+file.size+" | "+file.type);
	var formdata = new FormData($('form')[0]);
	formdata.append("document", file);
	var ajax = new XMLHttpRequest();
	ajax.upload.addEventListener("progress", progressHandler, false);
	ajax.addEventListener("load", completeHandler, false);
	ajax.addEventListener("error", errorHandler, false);
	ajax.addEventListener("abort", abortHandler, false);
	ajax.open("POST", "upload-document-lead-detail-page.php");
	ajax.send(formdata);
}
function progressHandler(event){
	//_("loaded_n_total").innerHTML = "Uploaded "+event.loaded+" bytes of "+event.total;
	var percent = (event.loaded / event.total) * 100;
	_("progressBar").value = Math.round(percent);
	_("loaded_n_total").innerHTML = Math.round(percent)+"% uploaded... please wait";
}
function completeHandler(event){
	var old_html = _("status").innerHTML;
	_("status").innerHTML = event.target.responseText + old_html;
	_("progressBar").value = 0;
	$('#progressBar').css("display","none");
	$('#loaded_n_total').css("display","none");
	$('form')[0].reset();
}
function errorHandler(event){
	_("loaded_n_total").innerHTML = "Upload Failed"+event.target.responseText;
	$('form')[0].reset();
}
function abortHandler(event){
	_("loaded_n_total").innerHTML = "Upload Aborted"+event.target.responseText;
	$('form')[0].reset();
}
</script>
<!-- this is added by dipika for upload file end -->


<script type="text/javascript">
// this is for deeted upoaded document
function delete_document(thisObj,docuId){
	var docu_id = docuId;	
	var delete_doc = confirm("Are you sure you want to delete?");
	if(delete_doc == true) {
		$.ajax
		({ 
			url: 'upload-document-lead-detail-page.php',
			data: {docu_id : docu_id},
			type: 'post',
			success: function(result){
				if(result == 1){
					$(thisObj).parent().parent().remove();
					alert("Document deleted successfully.");
				}	
			}
		});
	}
}

// this is for add lead to watch list 
function add_to_watch_list(leadId){
	var lead_id = leadId;	
	$.ajax
	({ 
		url: 'add_lead_to_watch_list.php',
		data: {lead_id : lead_id},
		type: 'post',
		success: function(result){ 
			alert("Lead has been added to Watch List.");
			$('#watch_list').attr('disabled',true);
		}
	});
}
// funcction added by dipika
function printContent(div_id){
		var DocumentContainer = document.getElementById(div_id);
		/*var html1 = $(".form-horizontal .panel-primary .panel-heading").html(); alert(html1);
		var res = html1.replace("margin-right: 400px;", " "); 
		var res1 = res.replace("margin-right: 230px;", ""); 
		alert(res1);*/
		var html = $("#print_form").html();
		var WindowObject = window.open("", "PrintWindow","width=2000,height=1000,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes");
		var is_chrome = Boolean(WindowObject.chrome);
		var lead_id = <?php echo $_SESSION['leadID']; ?>;
		var modified_date = $("#modified_date").val();
		var lead_status_change_date = $("#lead_status_change_date").val(); 
		var lead_protected_val = $("#lead_protected").val();
		var lead_protected = "";
		if(lead_protected_val == 0){
			lead_protected = "<i style='color:red; float:left;' class='fa fa-unlock fa-3x'></i> Lead is not protected!";
		}
		else{
			lead_protected = "<i style='color:green; float:left;' class='fa fa-lock fa-3x'></i> Lead is protected!";
		}		
		var rowcount = $("#rowcount").val();
		var lead_status_id = $("#lead_status_id").val();
		var lead_status_id_details = $("#lead_status_id_details").val();
		var lead_owner = $("#lead_owner").val();
		var lead_status = ""; 
		if(rowcount > 0){ 
			if(lead_status_id==1) {		
				lead_status = "<img src='images/warning2.png' width='16' height='16' /> Lead is Pending By "+lead_status_id_details+".";
			}
			else if (lead_status_id==2) {
				lead_status = "<img src='images/tick.png' width='16' height='16' /> Lead is Approved By "+lead_status_id_details+".";
			}
			else if (lead_status_id==3) {
				lead_status = "<img src='images/untick.png' width='16' height='16' /> Lead is Rejected By "+lead_status_id_details+".";
			}
			else if(lead_status_id==4){
				lead_status = "<img src='images/assignment.png' width='16' height='16' /> Lead is Assigned to "+lead_owner;
			}
		}
		else{
			if(lead_status_id==1) {		
				lead_status = "<img src='images/warning2.png' width='16' height='16' /> Lead is Pending.";
			}
			else if(lead_status_id==2) {
				lead_status = "<img src='images/tick.png' width='16' height='16' /> Lead is Approved.";
			}
			else if(lead_status_id==3) {
				lead_status = "<img src='images/untick.png' width='16' height='16' /> Lead is Rejected.";
			}
			else if(lead_status_id==4){
				lead_status = "<img src='images/assignment.png' width='16' height='16' /> Lead is Assigned to "+lead_owner;
			}
		} 				
		WindowObject.document.writeln('<html><head><title></title><link rel="stylesheet" href="css/bootstrap.css"><link rel="stylesheet" href="css/sb-admin-2.css"><link type="text/css" rel="stylesheet" href="font-awesome/css/font-awesome.min.css"><link type="text/css" rel="stylesheet" href="style.css"></head><body><div style="text-align:center; font-weight:bold; font-size:15px;">Confidential - IceCOLD CRM</div><div style="float:right; margin-right:5px;">'+lead_status+'</div><div class="clear"></div>'+lead_protected+'<div class="row"><div class="col-sm-12"><div class="pull-right">Lead id: <b>'+lead_id+'</b></div></div></div><div style="margin-top:25px;"><p style="float:right;">Last Updated on date: <i>'+modified_date+'</i></p><p style="margin: 0px auto; float: left; width: 464px;">Last Stage change date: <i>'+lead_status_change_date+'</i></p></div><form id="myLeadForm" method="post" action="#" class="form-horizontal"><div class="panel panel-primary"><div class="panel-heading" style="border-color: #337ab7;"><h3 class="panel-title">Lead Information</h3></div>');
		/*WindowObject.document.writeln(html1);
		WindowObject.document.writeln('</div>');*/
		/*WindowObject.document.writeln('<html><head><title></title><link rel="stylesheet" href="css/bootstrap.css"><link rel="stylesheet" href="css/sb-admin-2.css"><link type="text/css" rel="stylesheet" href="font-awesome/css/font-awesome.min.css"><link type="text/css" rel="stylesheet" href="style.css"></head><body><form id="myLeadForm" method="post" action="#" class="form-horizontal"><div class="panel panel-primary">');*/
		WindowObject.document.writeln(html);
		WindowObject.document.writeln('</div></form></body></html>');
		
		if (is_chrome) {
			  setTimeout(function () {
					WindowObject.document.close();
					WindowObject.focus();
					WindowObject.print();
					WindowObject.close();
			  }, 250);
		}
		else{
			WindowObject.document.close();
			WindowObject.focus();
			WindowObject.print();
			WindowObject.close();
		}	
		//document.getElementById('print_link').style.display='block';
}
	$(document).ready(function(){
//Users list in assign lead modal form
		$('#users-list').multiselect({
			includeSelectAllOption: true,
			enableFiltering: true
		});
//matches weburl with email id displays warning msgs as per definition/conventions on focus out of website field.
		$("#website").blur(function() {
			var email = $( "#email" ).val();
			var domain = $("#website").val();
			$.ajax
			({
				url: 'email-website-match.php',
				data:{email: email, domain: domain},
				type: 'post',
				beforeSend: function() {
          $("#loading-image").show();
       	},
				success: function(result)
				{
					$("#loading-image").hide();
					$('#domain_email_match_error').html(result);
				}
			});
		});
//raise a flag if customer is duplicate, defines by customer/company name, website url, country, city. 
		$("#country").blur(function() {
			var domain = $("#website").val();
			var company = $('#company').val();
			var country = $('#country').val();
			var city = $('#city').val();
			$.ajax
			({
				url: 'comapany-url-combo-check.php',
				data:{company: company, domain: domain, country: country, city: city},
				type: 'post',
				beforeSend: function() {
		      $("#loading-image").show();
		    },
				success: function(result)
				{
					$("#loading-image").hide();
					$('#approval-warning').html(result);
				}
			});
		});
//matches weburl with email id displays warning msgs as per definition/conventions on focus out of email field.
		$("#email").blur(function() {
			var email = $( "#email" ).val();
			var domain = $("#website").val();
			if(domain==''){
			}else{
				$.ajax
				({
					url: 'email-website-match.php',
					data:{email: email, domain: domain},
					type: 'post',
					beforeSend: function() {
            $("#loading-image").show();
        	},
					success: function(result)
					{
						$("#loading-image").hide();
						$('#email_domain_match_error').html(result);
					}	
				});
			}
		});
// Added comment followup date
// added by dipika 
		$("#follow-up-date").on("change" ,function(){ 
			$('.datepicker').css("display","none"); 
			var scrolled = $(".fancybox-inner").scrollTop();
			scrolled = scrolled + 1000;
			$(".fancybox-inner").animate({
				scrollTop: scrolled
			});
			$('.comment_title').text('Follo-Up Date Comments');
			$('#comment_type').val('follow_up_date_comment').attr('selected', 'selected');
			$('#btn-input').focus();
			var comment_type = $('#comment_type').val();
			$.ajax
			({ 
				url: 'live_comment_change_detail.php',
				data: {comment_type: comment_type},
				type: 'post',
				beforeSend: function() {
					$("#loading-image").show();
				},
				success: function(result){
					$("#loading-image").hide();
					$('ul#comments').html(result);					
				}
			});
		});
// Change comment part according dropdown vaue
// added by dipika 
		$("#comment_type").on("change" ,function(){ 
			var comment_type = $('#comment_type').val();
			
			if(comment_type == "lead_comment"){ 
				$('.comment_title').text('General Comments');
			}
			else if(comment_type == "follow_up_date_comment"){
				$('.comment_title').text('Follo Up Date Comments');
			}
			else if(comment_type == "watch_list_comment"){
				$('.comment_title').text('Watch List Comments');
			}
			
			$.ajax
			({ 
				url: 'live_comment_change_detail.php',
				data: {comment_type: comment_type},
				type: 'post',
				beforeSend: function() {
					$("#loading-image").show();
				},
				success: function(result){
					$("#loading-image").hide();
					$('ul#comments').html(result);					
				}
			});
		});		
	
//Added comment on the specific lead. 
// changed by dipika
		$('#btn-comment').click(function(){
			var comment_type = $('#comment_type').val();
			var comment_text = $('#btn-input').val();
		 	$.ajax
			({ 
				url: 'live-comment.php',
				data: {comment_type: comment_type, comment_text: comment_text},
				type: 'post',
				beforeSend: function() {
					$("#loading-image").show();
				},
				success: function(result){
					$("#loading-image").hide();
					$('#btn-input').val('');
					if(result == 0){
						alert("Please write somthing into comment.");
					}
					else{						
						$('ul#comments').append(result);
					}
				}
			});
		});
//If industry is other, then provide a field to enter industry
		$('select#industry').change(function(){
			var other_industry = $( "#industry" ).val();
		 	$.ajax
			({ 
				url: 'industry-textbox-other.php',
				data: { other : other_industry },
				type: 'post',
				beforeSend: function() {
          $("#loading-image").show();
       	},
				success: function(result)
				{
					$("#loading-image").hide();
					$('#txtOther').html(result);
				}
			});
		});
//All leads above multi-regional goes for approval.
		$('select#type-of-lead').change(function(){
			var lead_type = $( "#type-of-lead" ).val();
			if(lead_type == 3 || lead_type == 4 || lead_type == 7 || lead_type == 8 || lead_type == 9){
				$('#lead-type-approval').html('<h6>All Multi-Regional, National, Multinational, OEM and Federal leads are subject to approval, as they are corporate leads.</h6>')
			}else{
				$('#lead-type-approval').html('');
			}
		});
//Assign user to lead via modal box by clicking on assign button on lead detail form.
		$('#assign-user').click(function(){
		 	$.ajax
			({ 
				url: 'post.php',
				data: $('#myAssignLeadForm').serialize(),
				type: 'post',
				beforeSend: function() {
	        $("#loading-image").show();
        },
				success: function(result)
				{	
					$("#loading-image").hide();
					$('#display-return-msg').html(result);
					$('#display-return-msg').fadeIn("slow").delay(5000).fadeOut("slow");	
				}
			});
		});
//Approve lead dropdown in top right corner executed by this function.
		$('select#btnApproval').change(function(){
			var lead_id = '<?php echo $_SESSION['leadID']; ?>';
			var stage = $('#btnApproval').val();
			$.ajax
			({ 
				url: 'approve-lead.php',
				data: {lead_id: lead_id, stage: stage},
				type: 'post',
				beforeSend: function() {
          $("#loading-image").show();
       	},
				success: function(result)
				{
					$("#loading-image").hide();
					$("#display-return-msg").html(result);
					$("#display-return-msg").fadeIn("slow").delay(5000).fadeOut("slow");
				}
			});  
		});
//update lead button
		$('#update-lead').click(function(){
			$.fancybox.open({
        href: "update-lead-form.php",
        type: "ajax",
        ajax: {
          type: "POST",
          data:$('#myLeadForm').serialize(),
					success: function(result)
					{
					}
	      }
	    });
		});
//If lead source require extra field for certain option for who/which
		$('select#lead-source').change(function(){
			var who = $( "#lead-source" ).val();
		 	$.ajax
			({ 
				url: 'lead-source-who.php',
				data: { who : who },
				type: 'post',
				beforeSend: function() {
          $("#loading-image").show();
       	},
				success: function(result)
				{
					$("#loading-image").hide();
					$('#txtByWho').html(result);
				}
			});
		});
		var editabledec = <?php echo $editabledec ?>;
//If description is empty, should not be readonly; else readonly.
		if($('#description').val() == ""){
			$('#description').prop('readonly', false);
			//alert('readonly should be false');	
			 localStorage['desc_readonly']=0;
		}else if(editabledec==999999){
			$('#description').prop('readonly', false);
		}else{
			 localStorage['desc_readonly']=1;
			//alert('readonly should be true');
		}
//Lead stage update function work via this function, dont get confuse with lead status name.
		$('select#lead-status').change(function(){
			var change = $( "#lead-status" ).val();
		 	$.ajax
			({ 
				url: 'lead-status-change.php',
				data: { change : change },
				type: 'post',
				beforeSend: function() {
          $("#loading-image").show();
       	},
				success: function(result)
				{
					$("#loading-image").hide();
					$("#display-return-msg").html(result);
					$("#display-return-msg").fadeIn("slow").delay(5000).fadeOut("slow");
				}
			});
		});
//this function removes scrollbar from description
		$(function() {
      $('textarea').each(function() {
          $(this).height($(this).prop('scrollHeight'));
      });
    });

//Character count for opprtunity description.
		$('textarea').keyup(updateCount);
		$('textarea').keydown(updateCount);
		function updateCount() {
	    var cs = 4500 ;
	    $('textarea#description').keypress(function(e) {
	       	if (e.which < 0x20) {
	            // e.which < 0x20, then it's not a printable character
	            // e.which === 0 - Not a character
	            return; // Do nothing
	        }
	        if (this.value.length == cs) {
	            e.preventDefault();
	        } else if (this.value.length > cs) {
	            // Maximum exceeded
	            this.value = this.value.substring(0, cs);
	        }
	    });
	    $('#characters > h6').text((cs - $(this).val().length )+" character left");
		}
//Character count for Comment input box.
		$('#btn-input').keyup(commUpdateCount);
		$('#btn-input').keydown(commUpdateCount);
		function commUpdateCount() {
	    var cs = 4500 ;
	    $('#btn-input').keypress(function(e) {
	       	if (e.which < 0x20) {
	            // e.which < 0x20, then it's not a printable character
	            // e.which === 0 - Not a character
	            return; // Do nothing
	        }
	        if (this.value.length == cs) {
	            e.preventDefault();
	        } else if (this.value.length > cs) {
	            // Maximum exceeded
	            this.value = this.value.substring(0, cs);
	        }
	    });
	    $('.comment-charcters > h6').text((cs - $(this).val().length )+" character left");
		}
		$("#description").blur(function() {
			var desc = $( "#description" ).val();
			var user_name = "<?php echo $_SESSION['sess_user_name']; ?>";
			$.ajax
			({
				url: 'description-edit.php',
				data:{desc: desc, user_name: user_name},
				type: 'post',
				beforeSend: function() {
		          $("#loading-image").show();
		       	},
				success: function(result)
				{
					$("#loading-image").hide();
					$('#display-return-msg').html(result);
					$("#display-return-msg").fadeIn("slow").delay(5000).fadeOut("slow");
				}
			});
		});

$("#edit-dofp").click(function() {
			var dofp = $( "#date-of-presentation" ).val();
			var user_name = "<?php echo $_SESSION['sess_user_name']; ?>";
			$.ajax
			({
				url: 'dofp-edit.php',
				data:{dofp: dofp, user_name: user_name},
				type: 'post',
				beforeSend: function() {
		          $("#loading-image").show();
		       	},
				success: function(result)
				{
					$("#loading-image").hide();
					$('#display-return-msg').html(result);
					$("#display-return-msg").fadeIn("slow").delay(5000).fadeOut("slow");
				}
			});
		});



	});
function editDOFP(){
	$('#date-of-presentation').val("");
	$('#date-of-presentation').prop('readonly', false);
}
</script>