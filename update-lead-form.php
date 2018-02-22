<?php 
include 'config.php';
session_start();
// If the form was submitted, scrub the input (server-side validation)
$company = '';
$fname = '';
$lname = '';
$title = '';
$email = '';
$phone = '';
$lead_status= '';
$date_of_first_presentation='';
$type_of_lead = '';
$who_which='';
$industry= '';
$website = '';
$country = '';
$output = '';
$city='';
$desc = '';

if($_POST) {
// collect all input and trim to remove leading and trailing whitespaces  
	$company = trim($_POST['company']);
	$fname = trim($_POST['fname']);
	$lname = trim($_POST['lname']);
	$title = trim($_POST['title']);
	$email = trim($_POST['email']);
	$phone = trim($_POST['phone']);
	$who_which = trim($_POST['lead-source-who']);
	$lead_status= trim($_POST['lead-status']);
	$date_of_first_presentation=trim($_POST['date-of-presentation']);
	$type_of_lead = trim($_POST['type-of-lead']);
	$industry= trim($_POST['industry']);
	$website = trim($_POST['website']);
	$country = trim($_POST['country']);
	$city = trim($_POST['city']);
	$desc = trim($_POST['description']);

  $errors = array();
  
// Validate the input
  if (strlen($company) == 0)
    array_push($errors, "You have not entered Customer Name");

  if (strlen($fname) == 0)
    array_push($errors, "You have not entered First Name");
  
  if (strlen($lname) == 0)
    array_push($errors, "You have not entered Last Name");

  if (strlen($title) == 0)
    array_push($errors, "You have not entered Title");
  
  if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    array_push($errors, "You have not specify a valid Email address");
  
  if (strlen($phone) == 0)
    array_push($errors, "You have not entered Phone Number");

  if (strlen($lead_status) == 0)
    array_push($errors, "You have not selected Lead Stage");

  if ($lead_status=="1.Discovery") {
  }else{
	  if (strlen($date_of_first_presentation) == 0)
	    array_push($errors, "You have not entered date of your first Presentation");
	}

  if (strlen($type_of_lead) == 0)
    array_push($errors, "You have not selected Lead Type");
  
  if (strlen($industry) == 0)
    array_push($errors, "You have not selected Industry Name");
  
  if (strlen($website) == 0)
    array_push($errors, "You have not entered Website URL Name");
 
  if (strlen($country) == 0)
    array_push($errors, "You have not selected a Country");
    
  if (strlen($city) == 0)
    array_push($errors, "You have not entered a City");

  if (strlen($desc) == 0)
    array_push($errors, "You have not entered a opportunity description");

  //$lead_owner = $_REQUEST['entered-by'];
	$company =  htmlspecialchars($_REQUEST['company'],ENT_QUOTES);
	$fname =   htmlspecialchars($_REQUEST['fname'],ENT_QUOTES);
	$lname =   htmlspecialchars($_REQUEST['lname'],ENT_QUOTES);
	$title =  htmlspecialchars($_REQUEST['title'],ENT_QUOTES);
	$email =  htmlspecialchars($_REQUEST['email'],ENT_QUOTES);
	$phone =  htmlspecialchars($_REQUEST['phone'],ENT_QUOTES);
	$fax =  htmlspecialchars($_REQUEST['fax'],ENT_QUOTES);
	$mobile =  htmlspecialchars($_REQUEST['mobile'],ENT_QUOTES);
	$lead_source =  htmlspecialchars($_REQUEST['lead-source'],ENT_QUOTES);
	if(isset($_REQUEST['lead-source-who'])){
		$who_which =  htmlspecialchars($_REQUEST['lead-source-who'],ENT_QUOTES);
	}else{
		$who_which="";
	}
	$lead_status =  htmlspecialchars($_REQUEST['lead-status'],ENT_QUOTES);
	if (count($errors) == 0) {
		if ($lead_status!='1.Discovery') {
			$lead_protected_flag = 1;
		}else{
			$lead_protected_flag = 0;
		}
	}else{
		$lead_protected_flag = 0;
	}
	if($_POST['date-of-presentation']!=0){
		$date_of_first_presentation= date("Y-m-d", strtotime($_POST['date-of-presentation']) );
	}else{
		$date_of_first_presentation=0;
	}
	$industry =  htmlspecialchars($_REQUEST['industry'],ENT_QUOTES);
	if(isset($_REQUEST['other'])){
		$industry_other =  htmlspecialchars($_REQUEST['other'],ENT_QUOTES);
	}else{
		$industry_other="";
	}
	$no_of_emp =  htmlspecialchars($_REQUEST['no-of-employees'],ENT_QUOTES);
	$annual_revenue =  htmlspecialchars($_REQUEST['annual-revenue'],ENT_QUOTES);
	$skypeid =  htmlspecialchars($_REQUEST['skypeid'],ENT_QUOTES);
	$secondary_email =  htmlspecialchars($_REQUEST['secondary-email'],ENT_QUOTES);
	$twitter =  htmlspecialchars($_REQUEST['twitter'],ENT_QUOTES);
	$website =  htmlspecialchars($_REQUEST['website'],ENT_QUOTES);
	$type_of_lead = htmlspecialchars($_REQUEST['type-of-lead'],ENT_QUOTES);
	$street =  htmlspecialchars($_REQUEST['street'],ENT_QUOTES);
	$city =  htmlspecialchars($_REQUEST['city'],ENT_QUOTES);
	$state =   htmlspecialchars($_REQUEST['state'],ENT_QUOTES);
	$country =  htmlspecialchars($_REQUEST['country'],ENT_QUOTES);
	$zipcode =  htmlspecialchars($_REQUEST['zip-code'],ENT_QUOTES);
	$desc = htmlspecialchars($_REQUEST['description'],ENT_QUOTES);
	
	if($_SESSION['sess_lead_type_id']===$type_of_lead){
		try{
			$q="SELECT lead_status_id from leads WHERE lead_id=?";
			$stmt = $conn->prepare($q);
			$stmt->execute(array($_SESSION['leadID']));	
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$lead_approve_status = $result['lead_status_id'];
		}catch(PDOException $e){ 
			echo "Error: " . $e->getMessage();
		}	
	}elseif($_SESSION['sess_userrole'] == 8){
		try{
			$q="SELECT lead_status_id from leads WHERE lead_id=?";
			$stmt = $conn->prepare($q);
			$stmt->execute(array($_SESSION['leadID']));	
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			if($type_of_lead == 3 || $type_of_lead == 4 || $type_of_lead == 7 || $type_of_lead == 8 || $type_of_lead == 9){
				$lead_approve_status = 1;
			}elseif(isset($_SESSION['warning-approval'])){
				if($_SESSION['warning-approval']>1){
					$lead_approve_status = 1;
				}else{
				$lead_approve_status = $result['lead_status_id'];
				}
			}else{
				$lead_approve_status = $result['lead_status_id'];
			}
		}catch(PDOException $e){ 
			echo "Error: " . $e->getMessage();
		}	
	}elseif (count($errors) != 0) {
		$lead_approve_status = 1;
	}else{
		if($type_of_lead == 3 || $type_of_lead == 4 || $type_of_lead == 7 || $type_of_lead == 8 || $type_of_lead == 9){
			$lead_approve_status = 1;
		}elseif(isset($_SESSION['warning-approval'])){
			if($_SESSION['warning-approval']>1){
				$lead_approve_status = 1;
			}else{
				$lead_approve_status = 2;
			}
		}else{
			$lead_approve_status = 2;
		}
	}	
	try{
		$sql = "UPDATE leads SET company = '".$company."', fname = '".$fname."', lname = '".$lname."', title = '".$title."', email = '".$email."',phone = '".$phone."',fax = '".$fax."',mobile = '".$mobile."',lead_source = '".$lead_source."', who_which = '".$who_which."',lead_status = '".$lead_status."', date_of_first_presentation = '".$date_of_first_presentation."', industry = '".$industry."', industry_other = '".$industry_other."', no_of_emp = '".$no_of_emp."',annual_revenue = '".$annual_revenue."',skypeid = '".$skypeid."',secondary_email = '".$secondary_email."',twitter = '".$twitter."',website = '".$website."', lead_type_id = '".$type_of_lead."',street = '".$street."',city = '".$city."',state = '".$state."',country = '".$country."',zipcode = '".$zipcode."', lead_desc = '".$desc."', modified_date = now(), lead_status_id='".$lead_approve_status."', lead_protected_flag='".$lead_protected_flag."'  WHERE lead_id='".$_SESSION['leadID']."'";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
// If no errors were found, proceed with storing the user input
		if (count($errors) == 0) {
//array_push($errors, "No errors were found. Thanks!");
  	echo '<script type="text/javascript">
				$(document).ready(function(){
					$("#display-return-msg").html("Lead information UPDATED successfully!");
					$("#display-return-msg").fadeIn("slow").delay(5000).fadeOut("slow");
				});
			</script>'; 
		}else{
//Prepare errors for output
		  $output = 'Lead will be saved as draft because: ';
		  foreach($errors as $val) {
		    $output .= "<p class='output'>$val</p>";
		  }
//echo $output;
			echo '<script type="text/javascript">
				$(document).ready(function(){
					$("#display-return-msg").html("'.$output.'");
					$("#display-return-msg").fadeIn("slow").delay(5000).fadeOut("slow");
				});
			</script>';
	  }	
	}catch(PDOException $e){ 
		echo "Error: " . $e->getMessage();
	}
}
// time_since() counts how many seconds,minutes, hours...etc ago
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
//Detail page displays in form if POST!
if(isset($_SESSION['leadID'])){
	//$_SESSION['leadID'] = $lead_id = $_POST['lead_id'];
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
				  	echo "<img src='images/warning2.png' width='16' height='16' /> Lead is Pending By ".$row['user_fname']." ".$row['user_fname']." AT: ".date("d-M-Y  g:i:s A", strtotime($row['lead_status_timestamp'])).".";
				}elseif ($result['lead_status_id']==2) {
					echo "<img src='images/tick.png' width='16' height='16' /> Lead is Approved By ".$row['user_fname']." ".$row['user_fname']." AT: ".date("d-M-Y  g:i:s A", strtotime($row['lead_status_timestamp'])).".";
				}elseif ($result['lead_status_id']==3) {
					echo "<img src='images/untick.png' width='16' height='16' /> Lead is Rejected By ".$row['user_fname']." ".$row['user_fname']." AT: ".date("d-M-Y  g:i:s A", strtotime($row['lead_status_timestamp'])).".";
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
		}else{
			echo "<i style='color:green;' class='fa fa-lock fa-3x'></i> Lead is protected!";
		}
?>
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
					}else{
						echo date("d-M-Y", strtotime($result['date']));
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
			</p>
			<h3 class="panel-title">
				Lead Information
			</h3>
		</div>
		<div class="row btns">
			<div class="col-sm-4">
			<?php 
				if($result['lead_archive_status']==0){
			?>
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="float:left;" >Assign Lead</button>
			<?php
				} 
			?>
			</div>
			<div class="col-sm-3">
			<?php 
				if( $result['lead_archive_status']==0 ){
			?>
				<button type="button" class="btn btn-primary" style="padding:10px 50px;" id="update-lead">Update</button>
			<?php
			}
			?>
			</div>
			<div class="col-sm-5">
			<?php
				if( ($_SESSION['sess_userrole']==1 || $_SESSION['sess_userrole']==2 || $_SESSION['sess_userrole']==4) && $result['lead_archive_status']==0 ){
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
						<input type="text" name="date-of-presentation" class="form-control" id="date-of-presentation" value="<?php echo date("d-M-Y", strtotime($result['date_of_first_presentation']) ); ?>" readonly="readonly" required />
					<?php 
						}
					?>
					</div>
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
        <i class="fa fa-comments fa-fw"></i>
        Comments
	    </div>
	    <!-- /.panel-heading -->
	    <div class="panel-body">
	      <ul class="chat" id="comments">
		    <?php
		    try{
					$sql= "SELECT * FROM lead_comments JOIN users ON lead_comments.user_id=users.user_id WHERE lead_id = ?";
					$stmt = $conn->prepare($sql);
					$stmt->execute(array($_SESSION['leadID']));
					$stmt->setFetchMode(PDO::FETCH_ASSOC);
					while ($row = $stmt->fetch()) {
				?>
					<li class="left clearfix">
					  <span class="chat-img pull-left">
					    <img class="img-circle" alt="User Avatar" src="images/user.png" height="48" width="48" />
					  </span>
					  <div class="chat-body clearfix">
              <div class="header">
                <strong class="primary-font"><?php echo $row['user_fname']." ".$row['user_lname'];?></strong>
                <small class="pull-right text-muted">
                  <i class="fa fa-clock-o fa-fw"></i> <?php echo date("d-M-Y",strtotime($row['comment_date'])); ?>
                </small>
              </div>
              <p>
			        <?php 
			        	echo $row['comment_text'];
			        ?>
              </p>
            </div>
          </li>
				<?php
					}
			 	}catch(PDOException $e){
					echo $sql . "<br>" . $e->getMessage();
				}
		    ?>
		    </ul>
		  </div>
		  <!-- /.panel-body -->
		  <?php 
		  	if( $result['lead_archive_status']==0 ){
		  ?>
		  <div class="panel-footer">
        <div class="input-group">
          <input type="text" class="form-control input-sm" name="comment_text" id="btn-input" placeholder="Enter Your message here..." />
          <span class="input-group-btn">
            <button id="btn-comment" class="btn btn-warning btn-sm btn-comment" type="button">
                Comment
            </button>
          </span>
        </div>
        <div class="comment-charcters"><h6></h6></div>
        <div class="desc_reason"></div>
    	</div>
		 	<!-- /.panel-footer -->
		  <?php 
		  }
		  ?>
		</div>
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
      </div>
      <div class="panel-footer"></div>
    </div>
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
<script type="text/javascript">
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
//Added comment on the specific lead. 
		$('#btn-comment').click(function(){
			var comment_text = $('#btn-input').val();
		 	$.ajax
			({ 
				url: 'live-comment.php',
				data: {comment_text: comment_text},
				type: 'post',
				beforeSend: function() {
          $("#loading-image").show();
       	},
				success: function(result)
				{
					$("#loading-image").hide();
					$('ul#comments').append(result);
					$('#btn-input').val('');
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
//If description is empty, should not be readonly; else readonly.
		if($('#description').val() == ""){
			$('#description').prop('readonly', false);
			//alert('readonly should be false');	
			 localStorage['desc_readonly']=0;
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
	});
</script>