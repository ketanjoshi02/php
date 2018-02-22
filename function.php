<?php
function EmailAndDomainMatchError($EmailFormFieldName, $URLdomainFormFieldName)
{
   // Returns empty string if match is okay.
   //    Otherwise, an error message.


   // Check both fields contain information.
   if( empty($URLdomainFormFieldName) or empty($EmailFormFieldName) ) { return 'Both Email and URL/domain must be provided.'; }

   // Extract domain name from email address.
   $addypieces = explode('@',$EmailFormFieldName);
   $emailname = strtolower(trim($addypieces[0]));
   $emaildomain = strtolower(trim($addypieces[1]));

   // Some email address error checking.
   if( count($addypieces)!=2 or strpos($EmailFormFieldName,',') or (!strpos($EmailFormFieldName,'.')) ) { return 'Incorrect Email.'; }

   // Extract domain name from URL.
   $domain = preg_replace('/^https?:\/\//i','',$URLdomainFormFieldName);
   $domain = preg_replace('/^www\.?/i','',$domain);
   $domain = preg_replace('/\/.*$/','',$domain);
   $domain = strtolower(trim($domain));

   // Some domain name error checking.
   if( empty($domain) or strpos($domain,',') or (!strpos($domain,'.')) ) { return 'Incorrect Domain.'; }

   // Validate match.
   if( ! preg_match("/$emaildomain$/",$domain) ) { return 'Email domain must be same as URL domain. Example: '.$emailname.'@'.$domain; }

   return '';
}

if(isset($_REQUEST['submit-password']))
{	

	$new_pass = md5($_REQUEST['password2']);
	
	try{
		 $sql = "UPDATE users SET user_password='".$new_pass."', password_change_date = now() WHERE user_id='".$_SESSION['sess_user_id']."'";
		     // Prepare statement
   		 $stmt = $conn->prepare($sql);

		// execute the query
		$stmt->execute();
	
		// echo a message to say the UPDATE succeeded
		//echo $stmt->rowCount() . " records UPDATED successfully";
		echo '<script>alert("Password updated successfully");</script>';
		

		$msgbodyforemail='
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<style type="text/css">
			.cta {
			  background: #a0b566;
			  padding: 10px;
			  margin: 0 auto;
			  width: 120px;
			  color: #fff;
			}
		</style>
	</head>
	<body style="background: #a7bebe none repeat scroll 0 0;
			  font-family: verdana;
			  font-size: 17px;
			  padding: 150px;">
		<div style=" background: #ffffff none repeat scroll 0 0;
			  margin: 0 auto;
			  max-width: 720px;
			  padding: 50px;
			  width: 500px;
			  box-sizing: border-box;">
			  <div style="margin: 0 auto;
				  width: 177px;">
		      <a href="http://www.ecocoolcrm.com/"><img src="http://www.ecocoolcrm.com/images/ecwlogo.png" /></a>
		    </div>
			<h1>Hello '.$_SESSION['sess_user_name'].',</h1>
	    <p>
	       Your password has been successfully changed. This is just a notification and no additional action is required from your end.
	    </p>
	    <p>
	    	If you haven\'t requested a password reset, please contact crmsupport@ecocoolworld.com
	    </p>
	    <h2>
	      Thanks,
	    </h2>
	    <p>
			CRM Support,<br>
			EcoCoolWorld LLC
	    </p>
		</div>
	</body>
</html>';
require_once "PHPMailer/PHPMailerAutoload.php";

		//PHPMailer Object
		$mail = new PHPMailer;

		//From email address and name
		$mail->From = "crmsupport@ecocoolworld.com";
		$mail->FromName = "CRM Support";

		//To address and name
		//$mail->addAddress("ketan.joshi@wsisrdev.net", "Ketan Joshi");
		$mail->addAddress($_SESSION['sess_useremail'], $_SESSION['sess_user_name']);


		//Send HTML or Plain Text email
		$mail->isHTML(true);

		$mail->Subject = "Password Changed.";
		$mail->Body = $msgbodyforemail;
		//$mail->AltBody = "";

		if(!$mail->send()) 
		{
		    echo "Mailer Error: " . $mail->ErrorInfo;
		} 
		else 
		{
		    //echo "Message has been sent successfully";
			try{
			$sql = "UPDATE logged_in_session SET logged_out_time = now() WHERE user_id =:user_id && logged_out_time='0000-00-00 00:00:00'";
			$stmt = $conn->prepare($sql);
			$stmt->execute(array(':user_id'=> $_SESSION['sess_user_id']));

				//echo "successfully logout!";
			}
			catch(PDOException $e)
			{
				echo "unsuccessfully logout!";
				echo $sql . "<br>" . $e->getMessage();
			}
			//$conn=null;
			//session_unset();
			//session_destroy();
			header('Location: index.php?err=3');
		}

	}catch(PDOException $e){
		 echo $sql . "<br>" . $e->getMessage();
	}
}
//reset password start
if(isset($_REQUEST['reset-password']))
{	

	$new_pass = md5($_REQUEST['password2']);

	
	try{
		 $sql = "UPDATE users SET user_password='".$new_pass."', password_change_date = now() WHERE user_id='".$_REQUEST['user']."'";
		     // Prepare statement
   		 $stmt = $conn->prepare($sql);
		// execute the query
		$stmt->execute();

		header('Location: index.php?err=3');
	}catch(PDOException $e){
		 echo $sql . "<br>" . $e->getMessage();
	}
}
//reset password end


















//total lead count
try{
	if ($_SESSION['sess_userrole']!=8) {
		
		if($_SESSION['sess_dbatype'] == 1){
		
			 $sql = 'SELECT count(*) FROM leads WHERE lead_archive_status=0 AND user_id <> 0';		
			 $query = $conn->prepare($sql);		
			 $query->execute();
		}else{
			$sql = "SELECT count(*) FROM leads WHERE (user_id IN
			(SELECT user_id FROM users WHERE user_company_id =:comp_id )) && lead_archive_status=0";
			$query  = $conn->prepare($sql);
			$query->execute(array(':comp_id' => $_SESSION['sess_usercomp_id']));
		}
	}else{
		$sql = "SELECT count(*) FROM leads WHERE user_id = :user_id && lead_archive_status=0";
		$query  = $conn->prepare($sql);
		$query->execute(array(':user_id' => $user_id));
	}
$count = $query->fetchColumn();
}catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
//new lead notification
try{
	if ($_SESSION['sess_userrole']!=8) {
		if($_SESSION['sess_dbatype'] == 1){
		
			$sql = "SELECT count(*) FROM leads WHERE 
			(lead_status_id = 1 && (leads.lead_type_id = 3 || leads.lead_type_id = 4 || leads.lead_type_id = 7 || leads.lead_type_id = 8 || leads.lead_type_id = 9) && lead_archive_status=0) 
				|| (leads.user_id IN (SELECT user_id FROM users WHERE user_company_id = :comp_id &&  user_role_id = 8) && lead_status_id = 1) && lead_archive_status=0
				|| leads.user_id = 0 && lead_archive_status=0 && lead_status_id=1
				|| lead_status_id=1 && lead_archive_status=0 && leads.user_id IN (SELECT user_id FROM users WHERE user_role_id != 8 )";
			
			$query  = $conn->prepare($sql);
			$query->execute(array(':comp_id' => $_SESSION['sess_usercomp_id']));
		}else{
			$sql = "SELECT count(*) FROM leads WHERE 
			lead_status_id = 1 
			&& (leads.lead_type_id = 1 || leads.lead_type_id = 2 || leads.lead_type_id = 5 || leads.lead_type_id = 6) 
			&& leads.user_id IN (SELECT user_id FROM users WHERE user_company_id = :comp_id && user_role_id = 8) && lead_archive_status=0";
			$query  = $conn->prepare($sql);
			$query->execute(array(':comp_id' => $_SESSION['sess_usercomp_id']));
		}
	}else{
		$sql = "SELECT count(*) FROM leads WHERE user_id = :user_id && lead_status_id = 1 && lead_archive_status=0";
		$query  = $conn->prepare($sql);
		$query->execute(array(':user_id' => $user_id));
	}
	
	// this is for deleted leads
	if($_SESSION['sess_userrole'] == 1) {
		$sql2 = "SELECT count(*) FROM archived_leads WHERE user_id = ".$user_id." and notify_status = 0 ORDER BY lead_archive_id DESC";
		$stmt2  = $conn->prepare($sql2);
		$stmt2->execute();
	}
	else if($_SESSION['sess_userrole'] == 2){
		$sql2 = "SELECT count(*) FROM archived_leads as al left join users as u on u.user_id = al.user_id and u.user_role_id = 2 WHERE al.user_id = ".$user_id." and al.notify_status = 0 ORDER BY al.lead_archive_id DESC";
		$stmt2  = $conn->prepare($sql2);
		$stmt2->execute();
	}
	else if($_SESSION['sess_userrole'] == 5){
		$sql2 = "SELECT count(*) FROM archived_leads as al left join users as u on u.user_id = al.user_id and u.user_company_id = ".$_SESSION['sess_dbatype']." WHERE al.notify_status = 0 and al.user_id = ".$user_id." ORDER BY al.lead_archive_id DESC";
		$stmt2  = $conn->prepare($sql2);
		$stmt2->execute();
	}
	// delete leads end here
	
	// follow-up date notification start
	$date = date("Y-m-d");
	$sql3 = "SELECT count(*),datediff(l.follow_up_date,'".$date."') FROM leads as l Left Join users as u ON l.lead_owner = CONCAT(u.user_fname, ' ', u.user_lname) and l.user_id = ".$user_id." and l.follow_up_date >= CURDATE() WHERE u.user_id = ".$user_id." and datediff(l.follow_up_date,'".$date."') IN (0,7,14)"; 
	$stmt3  = $conn->prepare($sql3);
	$stmt3->execute();
	// follow-up date notification end here
	
if ($_SESSION['sess_userrole'] == 5 || $_SESSION['sess_userrole'] == 2 || $_SESSION['sess_userrole'] == 1) {
	$deleted_leads_count = $stmt2->fetchColumn();
} else {
	$deleted_leads_count = 0;
}

$new_lead_notification = $query->fetchColumn() + $deleted_leads_count + $stmt3->fetchColumn();
}catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
//lead stage updated notification
try{
	$sql="SELECT DISTINCT count(*) FROM lead_stage_detail 
		JOIN leads ON leads.lead_id=lead_stage_detail.lead_id 
		WHERE lead_stage_detail.enabled=1 AND leads.user_id=?";
	$query  = $conn->prepare($sql);
	$query->execute(array($user_id) );

	$lead_stage_update_notification=$query->fetchColumn();
}catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

try{
	$sql="SELECT count( * ) FROM assign
		JOIN leads ON leads.lead_id = assign.lead_id
		JOIN users ON users.user_id = assign.assignedfrom_userid
		WHERE assign.notifyto_enabled =1
		AND assign.assignedto_userid=?";
	$query  = $conn->prepare($sql);
	$query->execute(array($user_id));

	$lead_assignedto_notification=$query->fetchColumn();
}catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
try{
	$sql="SELECT count( * ) FROM assign
		JOIN leads ON leads.lead_id = assign.lead_id
		JOIN users ON users.user_id = assign.assignedto_userid
		WHERE assign.notifyto_enabled =1
		AND assign.assignedfrom_userid=?";
	$query  = $conn->prepare($sql);
	$query->execute(array($user_id));

	$lead_assignedfrom_notification=$query->fetchColumn();
}catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if(isset($_REQUEST['add-equipment'])){

	$manufacturer=$_REQUEST['compressor-manufacturer'];
	$type=$_REQUEST['compressor-type'];
	$comp_model_no=$_REQUEST['compressor-model-no'];
	$oil_charge_ltr=$_REQUEST['oil-charge-ltr'];
	$oil_charge_us_oz=$_REQUEST['oil-charge-us-oz'];
	$oem=$_REQUEST['oem'];
	$unit_type=$_REQUEST['unit-type'];
	$refrigerant_type=$_REQUEST['refrigerant-type'];
	$refrigerant_group=$_REQUEST['refrigerant-group'];
	$unit_model_no=$_REQUEST['unit-model-no'];
	
	try {	
		$sql = "INSERT INTO icecoldoilcharge (comp_manufacturer, comp_model_no, oil_charge_ltr, oil_charge_us_oz, comp_type, oem, unit_model_no, unit_type, refrigerant_type, refrigerant_group)
						VALUES ('$manufacturer', '$comp_model_no', '$oil_charge_ltr', '$oil_charge_us_oz', '$type', '$oem', '$unit_model_no', '$unit_type', '$refrigerant_type', '$refrigerant_group')";
		// use exec() because no results are returned
		$conn->exec($sql);
		?><script type="text/javascript">alert("New equipment added successfully");</script><?php
		}
	catch(PDOException $e)
		{
		echo $sql . "<br>" . $e->getMessage();
		}
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
function EmailCheckForCRM($EmailFormFieldName)
{
   // Returns empty string if match is okay.
   //    Otherwise, an error message.

   // Check both fields contain information.
   if( empty($EmailFormFieldName) ) { return ' Email must be provided.'; }

   // Extract domain name from email address.
   $addypieces = explode('@',$EmailFormFieldName);
   $emailname = strtolower(trim($addypieces[0]));
   $emaildomain = strtolower(trim($addypieces[1]));

   // Some email address error checking.
   if( count($addypieces)!=2 or strpos($EmailFormFieldName,',') or (!strpos($EmailFormFieldName,'.')) ) { return 'Incorrect Email.'; }

   // Extract domain name from URL.
   //$domain = preg_replace('/^https?:\/\//i','',$URLdomainFormFieldName);
   //$domain = preg_replace('/^www\.?/i','',$domain);
   //$domain = preg_replace('/\/.*$/','',$domain);
   //$domain = strtolower(trim($domain));

   // Some domain name error checking.
   //if( empty($domain) or strpos($domain,',') or (!strpos($domain,'.')) ) { return 'Incorrect Domain.'; }

   // Validate match.
   if( ! preg_match("/$emaildomain$/","ecocoolworld.com") ) { return 'Email must be registerd with EcoCoolWorld. Example: '.$emailname.'@ecocoolworld.com'; }

   return '';
}

?>