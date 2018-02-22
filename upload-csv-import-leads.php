<?php include 'config.php'; 
session_start();

if(isset($_POST) && $_FILES['export']['size'] > 0)
{
	$fileName = $_FILES['export']['name'];
	$tmpName  = $_FILES['export']['tmp_name'];
	$fileSize = $_FILES['export']['size'];
	$fileType = $_FILES['export']['type'];

	$ext = pathinfo($fileName, PATHINFO_EXTENSION);

	if($ext === "csv"){

		$current_date_time = date("dmY his");
		$current_date_time = str_replace(" ","-",$current_date_time);
		$fileName = str_replace(".csv","_".$current_date_time.".csv",$fileName);
		
		$target_dir = "upload_csv/";
		$target_file = $target_dir . basename($fileName);
		
		$fp = fopen($tmpName, 'r');
		
		if (move_uploaded_file($_FILES["export"]["tmp_name"], $target_file)){
			$msg = "The file ". basename( $_FILES["export"]["name"]). " has been uploaded.";
		} 
		else{
			$msg = "Sorry, there was an error uploading your file.";
		}

		fclose($fp);

		if(!get_magic_quotes_gpc()){
			$fileName = addslashes($fileName);
		}

		try{	
				// path where your CSV file is located
				//define('CSV_PATH',$_SERVER['HTTP_HOST'].'/upload_csv/');
				
				// Name of your CSV file
				//$csv_file = CSV_PATH.$fileName; 
				$csv_file = 'upload_csv/'.$fileName;
				chmod('upload_csv/'.$fileName, 0777);
				
				if (($handle = fopen($csv_file, "r")) !== FALSE) {
					$filesize1 = filesize($csv_file);			
					$headers = fgetcsv($handle, $filesize1, ',');
					$col = "";
					
					$error_message = "";
					
					while (($data = fgetcsv($handle, $filesize1, ",")) !== FALSE) {
						$col = array_combine($headers, $data);				
						$lead_entered_by = htmlspecialchars($col['Lead Owner*'],ENT_QUOTES); //***** select query, this is for $user_id 
						$lead_owner = htmlspecialchars($col['Lead Owner*'],ENT_QUOTES);
						$company = htmlspecialchars($col['Customer Name*'],ENT_QUOTES);
						$fname = htmlspecialchars($col['First Name*'],ENT_QUOTES);
						$lname = htmlspecialchars($col['Last Name*'],ENT_QUOTES);
						$title = htmlspecialchars($col['Title*'],ENT_QUOTES);
						$email = htmlspecialchars($col['Email*'],ENT_QUOTES);
						$phone = htmlspecialchars($col['Phone*'],ENT_QUOTES);
						$fax = htmlspecialchars($col['Fax'],ENT_QUOTES);
						$mobile = htmlspecialchars($col['Mobile'],ENT_QUOTES);
						$lead_source = htmlspecialchars($col['Lead Source'],ENT_QUOTES);
						$who_which = "";
						$lead_status = htmlspecialchars($col['Lead Stage*'],ENT_QUOTES);
						$industry = htmlspecialchars($col['Industry*'],ENT_QUOTES); 
						$industry_other = "";
						$no_of_emp = htmlspecialchars($col['No. of locations'],ENT_QUOTES);
						$annual_revenue = htmlspecialchars($col['Annual Revenue'],ENT_QUOTES);
						$skypeid = htmlspecialchars($col['Skype ID'],ENT_QUOTES);
						$secondary_email = htmlspecialchars($col['Secondary Email'],ENT_QUOTES);
						$twitter = htmlspecialchars($col['Twitter'],ENT_QUOTES);
						$website = htmlspecialchars($col['Website URL*'],ENT_QUOTES);
						$lead_type = htmlspecialchars($col['Lead Type*'],ENT_QUOTES); //***** select query, this is for $lead_type_id 
						$street = htmlspecialchars($col['Street'],ENT_QUOTES);
						$city = htmlspecialchars($col['City*'],ENT_QUOTES);
						$state = htmlspecialchars($col['State/Province*'],ENT_QUOTES);
						$zipcode = htmlspecialchars($col['Zip Code'],ENT_QUOTES);
						$country = htmlspecialchars($col['Country*'],ENT_QUOTES);
						$date = date('Y-m-d h:m:s');
						$modified_date = date('Y-m-d h:m:s');
						$lead_desc = htmlspecialchars($col['Opportunity Description*'],ENT_QUOTES);
						$lead_status_id = '1';
						$lead_archive_status = "0"; 
						$lead_status_change_date = date('Y-m-d h:m:s');
						$lead_protected_flag  = "0";
						
						$lead_type_id = ""; //***** using $lead_type select query and get id from type_of_lead table and set as $lead_type_id
						$user_id = ""; //***** using $lead_entered_by select query and get id from users table and set as $user_id
						$date_of_first_pres = htmlspecialchars($col['Date of first presentation*'],ENT_QUOTES); //***** change date format 
						
						//***** This is for date of first  presentation
						$change_date = strtotime($date_of_first_pres);
						$date_of_first_presentation = date('Y-m-d h:m:s',$change_date)."<br>";
						
						$array_empty = "";
						$array_not_empty = "";
						
						foreach($data as $k => $v){
							if($v == ""){
								$array_empty = "yes";
							}
							else{
								$array_not_empty = "yes";
							}
						}
											
						if($array_not_empty == "yes"){
								//***** This is for $user_id
								if($lead_entered_by != ""){
										$user_name = explode(" ",$lead_entered_by);
										$user_fname = $user_lname = "";
										if($user_name[0] != ""){ 	$user_fname = $user_name[0]; }
										if($user_name[1] != ""){	$user_lname = $user_name[1]; }	
										$sql1 = "Select user_id,user_fname,user_lname from users where user_fname = '".$user_fname."' AND user_lname = '".$user_lname."'";	
										$result1 = $conn->prepare($sql1);
										$result1->execute();
										$result1->setFetchMode(PDO::FETCH_ASSOC);
										while($row = $result1->fetch()){		$user_id = $row["user_id"]; }				
								}
								
								if($user_id != ""){
										$user_name_msg = array(); 
										if (array_key_exists("'".$user_fname." ".$user_lname."'", $user_name_msg)) {
											 $total = $user_name_msg[$user_fname." ".$user_lname] + 1;
											 $user_name_msg[$user_fname." ".$user_lname] = $total;
										}
										else{
											$user_name_msg[$user_fname." ".$user_lname] = 1;
										}			
										
										//***** This is for $lead_type_id
										if($lead_type != ""){
											$sql2 = "Select lead_type_id,lead_type_name from type_of_lead where lead_type_name = '".$lead_type."'";
											$result2 = $conn->prepare($sql2);
											$result2->execute();
											$result2->setFetchMode(PDO::FETCH_ASSOC);
											while($row = $result2->fetch()){		$lead_type_id = $row["lead_type_id"]; }
										}
										
										// SQL Query to insert data into DataBase
										$sql3 = "INSERT INTO leads (lead_entered_by, lead_owner, company, fname, lname, title, email, phone, fax, mobile, lead_source, who_which, lead_status, date_of_first_presentation, industry, industry_other, no_of_emp, annual_revenue, skypeid, secondary_email, twitter, website, lead_type_id, street, city, state, zipcode, country, lead_desc, date, modified_date, user_id, lead_status_id, lead_archive_status, lead_status_change_date, lead_protected_flag) VALUES ('".$lead_entered_by."','".$lead_owner."','".$company."','".$fname."','".$lname."','".$title."','".$email."','".$phone."','".$fax."','".$mobile."','".$lead_source."','".$who_which."','".$lead_status."','".$date_of_first_presentation."','".$industry."','".$industry_other."','".$no_of_emp."','".$annual_revenue."','".$skypeid."','".$secondary_email."','".$twitter."','".$website."','".$lead_type_id."','".$street."','".$city."','".$state."','".$zipcode."','".$country."','".$lead_desc."','".$date."','".$modified_date."',".$user_id.",".$lead_status_id.",".$lead_archive_status.",'".$lead_status_change_date."',".$lead_protected_flag.")";
										
										$conn->exec($sql3);		
										$error_message  = "<font color='green'> Following leads have been successfully imported: ";
										foreach($user_name_msg as $k=>$v){
											$error_message .=  "<br>".$k." ".$v;
										}	
										$error_message .= "<br>You will now be redirected to all leads.</font>";
								}
								else{
										$error_message = "<font color='red'>Lead Owner ".$user_fname." ".$user_lname." does not exist. Please add lead owner, then import leads.</font>";
								}
						}
					} // while
					fclose($handle);
				}	// read csv		
				if($error_message != "")	{
					echo $error_message;
				}	
				else{
					echo "<font color='red'>No records found.</font>";
				}
		} // 	try
		catch(PDOException $e){
				echo $msg;
		}	// catch
	}
	else{
		echo "<font color='red'>Only .csv files are supported. Please download sample file to review.</font>";
	} // ext check csv?
}
else{
	echo "<font color='red'>Please select File to upload.</font>";
} // is file attached
?>
