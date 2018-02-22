<?php include 'config.php';
session_start();

function array_combine_($keys, $values){
	 $result = array();
	 foreach ($keys as $i => $k) {
		  $result[$k][] = $values[$i];
	 }
	 array_walk($result, create_function('&$v', '$v = (count($v) == 1)? array_pop($v): $v;'));
	 return $result;
}

$display_fields = 'l.lead_id, uc.user_company_name, l.lead_entered_by, l.lead_owner, l.company, l.fname, l.lname, l.title, l.email, l.secondary_email, l.skypeid, l.phone, l.fax, l.mobile, l.lead_source,  l.lead_status, IF(l.date_of_first_presentation = "0000-00-00 00:00:00", "", l.date_of_first_presentation), lt.lead_type_name, l.industry, IF(l.no_of_emp = "0", "", l.no_of_emp), IF(l.annual_revenue = "0", "", l.annual_revenue), l.twitter, l.website, l.street, l.city, l.state, l.zipcode, l.country, l.lead_desc, l.date, ls.lead_status_name, IF(l.lead_archive_status = 0, "No", "Yes"), l.lead_status_change_date, IF(l.lead_protected_flag = 0, "No", "Yes"), max(lcs.comment_date)';

/* AJAX check  */
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	
	if(isset($_POST['lead_filter_option']) && $_POST['lead_filter_option'] == "dba"){
				
		$sql1 = "Select user_company_id,user_company_name from user_company";
		
		$result1 = $conn->prepare($sql1);
		$result1->execute();
		$result1->setFetchMode(PDO::FETCH_ASSOC);
		
		if($result1->rowCount() > 0){
			while ($row = $result1->fetch() ) {
				$send_filter_list[] = $row;
			}
			echo json_encode($send_filter_list);
		}
		else
			echo json_encode(0);	
	}
	elseif(isset($_POST['lead_filter_option']) && $_POST['lead_filter_option'] == "lead_owner"){
				
		$sql1 = "Select lead_owner from leads group by lead_owner";
		
		$result1 = $conn->prepare($sql1);
		$result1->execute();
		$result1->setFetchMode(PDO::FETCH_ASSOC);
		
		if($result1->rowCount() > 0){
			while ($row = $result1->fetch() ) {
				$send_filter_list[] = $row;
			}
			echo json_encode($send_filter_list);
		}
		else
			echo json_encode(0);	
	}
	elseif(isset($_POST['lead_filter_option']) && $_POST['lead_filter_option'] == "lead_source"){
				
		$sql1 = "Select lead_source from leads group by lead_source";
		
		$result1 = $conn->prepare($sql1);
		$result1->execute();
		$result1->setFetchMode(PDO::FETCH_ASSOC);
		
		if($result1->rowCount() > 0){
			while ($row = $result1->fetch() ) {
				$send_filter_list[] = $row;
			}
			echo json_encode($send_filter_list);
		}
		else
			echo json_encode(0);	
	}
	elseif(isset($_POST['lead_filter_option']) && $_POST['lead_filter_option'] == "lead_stage"){
				
		$sql1 = "Select lead_stage from lead_stage_detail group by lead_stage";
		
		$result1 = $conn->prepare($sql1);
		$result1->execute();
		$result1->setFetchMode(PDO::FETCH_ASSOC);
		
		if($result1->rowCount() > 0){
			while ($row = $result1->fetch() ) {
				$send_filter_list[] = $row;
			}
			echo json_encode($send_filter_list);
		}
		else
			echo json_encode(0);	
	}
	elseif(isset($_POST['lead_filter_option']) && $_POST['lead_filter_option'] == "lead_status"){
				
		$sql1 = "Select * from lead_status";
		
		$result1 = $conn->prepare($sql1);
		$result1->execute();
		$result1->setFetchMode(PDO::FETCH_ASSOC);
		
		if($result1->rowCount() > 0){
			while ($row = $result1->fetch() ) {
				$send_filter_list[] = $row;
			}
			echo json_encode($send_filter_list);
		}
		else
			echo json_encode(0);	
	}	
	else{
			$lead_option = $_POST['lead_option'];
			$lead_filter_option = $_POST['lead_filter_option'];
			if($lead_filter_option[0] == ""){
				unset($lead_filter_option[0]);
			}
			$lead_filter_option_detail = $_POST['lead_filter_option_detail'];
			if($lead_filter_option_detail[0] == ""){
				unset($lead_filter_option_detail[0]);
			}
			
			if($lead_option == "all_leads" && empty($lead_filter_option) && empty($lead_filter_option_detail)){
				$sql = "SELECT ".$display_fields." FROM leads as l left join type_of_lead as lt on l.lead_type_id = lt.lead_type_id left join lead_status as ls on l.lead_status_id = ls.lead_status_id left join lead_comments as lcs on lcs.lead_id = l.lead_id left join users as u on l.user_id = u.user_id left join user_company as uc on u.user_company_id = uc.user_company_id group by l.lead_id";
			}
			else if($lead_option == "all_enabled_leads" && empty($lead_filter_option) && empty($lead_filter_option_detail)){
				$sql = "SELECT ".$display_fields." FROM leads as l left join type_of_lead as lt on l.lead_type_id = lt.lead_type_id left join lead_status as ls on l.lead_status_id = ls.lead_status_id left join lead_comments as lcs on lcs.lead_id = l.lead_id left join users as u on l.user_id = u.user_id left join user_company as uc on u.user_company_id = uc.user_company_id WHERE lead_archive_status = 0 group by l.lead_id";
			}	
			else if($lead_option == "all_deleted_leads" && empty($lead_filter_option) && empty($lead_filter_option_detail)){
				$sql = "SELECT ".$display_fields." FROM leads as l left join type_of_lead as lt on l.lead_type_id = lt.lead_type_id left join lead_status as ls on l.lead_status_id = ls.lead_status_id left join lead_comments as lcs on lcs.lead_id = l.lead_id left join users as u on l.user_id = u.user_id left join user_company as uc on u.user_company_id = uc.user_company_id WHERE lead_archive_status = 1 group by l.lead_id";
			}
			else {
				if(!empty($lead_filter_option) && !empty($lead_filter_option_detail)){	
					
					//$fire_query = "select ".$display_fields." from leads as l left join type_of_lead as lt on l.lead_type_id = lt.lead_type_id left join lead_status as ls on  l.lead_status_id = ls.lead_status_id where ";
					
					$fire_query = "select ".$display_fields." from leads as l left join type_of_lead as lt on l.lead_type_id = lt.lead_type_id left join lead_status as ls on  l.lead_status_id = ls.lead_status_id left join lead_comments as lcs on lcs.lead_id = l.lead_id left join users as u on l.user_id = u.user_id left join user_company as uc on u.user_company_id = uc.user_company_id where ";
						
					$filter_option_array = array_combine_($lead_filter_option, $lead_filter_option_detail);
															
					foreach($filter_option_array as $k => $v){
						if(is_array($v))
							$values = implode("','",$v);
						else
							$values = $v;
														
						if($k == "dba"){
							$conditions .= "u.user_company_id In ('".$values."') AND ";
							//$fire_query = "select ".$display_fields." from leads as l left join type_of_lead as lt on l.lead_type_id = lt.lead_type_id left join lead_status as ls on  l.lead_status_id = ls.lead_status_id left join users as u on l.user_id = users.user_id left join user_company as uc on u.user_company_id = uc.user_company_id where ";
						}	
						else if($k == "lead_owner")	
							$conditions .= "l.lead_owner In ('".$values."') AND ";
						else if($k == "lead_source")
							$conditions .= "l.lead_source In ('".$values."') AND ";
						else if($k == "lead_stage")	
							$conditions .= "l.lead_status In ('".$values."') AND ";
						else if($k == "lead_status")
							$conditions .= "l.lead_status_id In (".$values.") AND ";		
					}
					
					if($lead_option == "all_enabled_leads"){
						$sql = $fire_query.$conditions."l.lead_archive_status = 0 group by l.lead_id";
					}
					else if($lead_option == "all_deleted_leads"){
						$sql = $fire_query.$conditions."l.lead_archive_status = 1 group by l.lead_id";
					}
					else{
						$sql = $fire_query.trim($conditions," AND ");
					}	
				}
			}
			//echo $sql;
			//exit;
			$result1 = $conn->prepare($sql);
			$result1->execute();
			$result1->setFetchMode(PDO::FETCH_ASSOC);
			
			if($result1->rowCount() > 0)
				echo json_encode(1); 
			else
				echo json_encode(0);
	}		
}
else{	
	
	$lead_option = $_POST['lead_option'];
	$lead_filter_option = $_POST['lead_filter_option'];
	if($lead_filter_option[0] == ""){
		unset($lead_filter_option[0]);
	}
	$lead_filter_option_detail = $_POST['lead_filter_option_detail'];
	if($lead_filter_option_detail[0] == ""){
		unset($lead_filter_option_detail[0]);
	}
	
	if($lead_option == "all_leads" && empty($lead_filter_option) && empty($lead_filter_option_detail)){
		$sql = "SELECT ".$display_fields." FROM leads as l left join type_of_lead as lt on l.lead_type_id = lt.lead_type_id left join lead_status as ls on l.lead_status_id = ls.lead_status_id left join lead_comments as lcs on lcs.lead_id = l.lead_id left join users as u on l.user_id = u.user_id left join user_company as uc on u.user_company_id = uc.user_company_id group by l.lead_id";
	}
	else if($lead_option == "all_enabled_leads" && empty($lead_filter_option) && empty($lead_filter_option_detail)){
		$sql = "SELECT ".$display_fields." FROM leads as l left join type_of_lead as lt on l.lead_type_id = lt.lead_type_id left join lead_status as ls on l.lead_status_id = ls.lead_status_id left join lead_comments as lcs on lcs.lead_id = l.lead_id left join users as u on l.user_id = u.user_id left join user_company as uc on u.user_company_id = uc.user_company_id WHERE lead_archive_status = 0 group by l.lead_id";
	}	
	else if($lead_option == "all_deleted_leads" && empty($lead_filter_option) && empty($lead_filter_option_detail)){
		$sql = "SELECT ".$display_fields." FROM leads as l left join type_of_lead as lt on l.lead_type_id = lt.lead_type_id left join lead_status as ls on l.lead_status_id = ls.lead_status_id left join lead_comments as lcs on lcs.lead_id = l.lead_id left join users as u on l.user_id = u.user_id left join user_company as uc on u.user_company_id = uc.user_company_id WHERE lead_archive_status = 1 group by l.lead_id";
	}
	else {
		if(!empty($lead_filter_option) && !empty($lead_filter_option_detail)){	
			
			$filter_option_array = array_combine_($lead_filter_option, $lead_filter_option_detail);
			
			//$fire_query = "select * from leads as l left join type_of_lead as lt on l.lead_type_id = lt.lead_type_id left join lead_status as ls on l.lead_status_id = ls.lead_status_id where ";
			
			$fire_query = "select ".$display_fields." from leads as l left join type_of_lead as lt on l.lead_type_id = lt.lead_type_id left join lead_status as ls on  l.lead_status_id = ls.lead_status_id left join lead_comments as lcs on lcs.lead_id = l.lead_id left join users as u on l.user_id = u.user_id left join user_company as uc on u.user_company_id = uc.user_company_id where ";
			
			foreach($filter_option_array as $k => $v){
				if(is_array($v))
					$values = implode("','",$v);
				else
					$values = $v;
						
				if($k == "dba"){
					$conditions .= "u.user_company_id In ('".$values."') AND ";
					//$fire_query = "select ".$display_fields." from leads as l left join type_of_lead as lt on l.lead_type_id = lt.lead_type_id left join lead_status as ls on  l.lead_status_id = ls.lead_status_id left join users on l.user_id = u.user_id where ";
				}	
				else if($k == "lead_owner")	
					$conditions .= "l.lead_owner In ('".$values."') AND ";
				else if($k == "lead_source")
					$conditions .= "l.lead_source In ('".$values."') AND ";
				else if($k == "lead_stage")	
					$conditions .= "l.lead_status In ('".$values."') AND ";
				else if($k == "lead_status")
					$conditions .= "l.lead_status_id In (".$values.") AND ";		
			}
			
			if($lead_option == "all_enabled_leads"){
				$sql = $fire_query.$conditions."l.lead_archive_status = 0 group by l.lead_id";
			}
			else if($lead_option == "all_deleted_leads"){
				$sql = $fire_query.$conditions."l.lead_archive_status = 1 group by l.lead_id";
			}
			else{
				$sql = $fire_query.trim($conditions," AND ");
			}	
		}
	}
	
	//echo $sql;
	//exit;
		
	// Select records from leads table for export 			 
	/*$column_name = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME`='leads'";
	$fields = $conn->prepare($column_name);
	$fields->execute();
	$fields->setFetchMode(PDO::FETCH_ASSOC);*/
	
	// sql query goes  here
	$result = $conn->prepare($sql);
	$result->execute();
	$result->setFetchMode(PDO::FETCH_ASSOC);
	
	// output headers so that the file is downloaded rather than displayed
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=CRM_Leads.csv');
	
	// create a file pointer connected to the output stream
	$output = fopen('php://output', 'w');
	
	// output the column headings
	$field_array = array('Lead ID','DBA Name','Lead Entered By','Lead Owner','Customer Name','First Name','Last Name','Title','Email','Secondary Email','Skype ID','Phone','Fax','Alternate#','Lead Source','Lead Stages','Date of First Presentation','Lead Type','Primary Industry Type','No. of Locations','Annual Revenue (USD)','Twitter','Website URL','Street','City','State/Province','Zip Code','Country','Description', 'Lead Created Date', 'Lead Status', 'Lead Deleted', 'Lead Stage Change Date', 'Lead Protected', 'Last Comment Date');
		
	/*while ($fields_name = $fields->fetch() ) {
		$field_array[] = ucwords(str_replace("_"," ",$fields_name['COLUMN_NAME']));
	}*/	
		
	fputcsv($output, $field_array);
	
	// loop over the rows, outputting them
	while ($row = $result->fetch() ) {
		fputcsv($output, $row);
	}
}	

/*$sql = "SELECT lead_entered_by, lead_owner, company, fname, lname, title, email, phone, fax, mobile, lead_source, who_which, lead_status, date_of_first_presentation, industry, industry_other, no_of_emp, annual_revenue, skypeid, secondary_email, twitter, website, lead_type_id, street, city, state, zipcode, country, lead_desc, date, modified_date, user_id, lead_status_id, lead_archive_status, lead_status_change_date, lead_protected_flag FROM leads WHERE user_id = ".$user_id." AND lead_type_id = ".$lead_type_id;*/
?>

