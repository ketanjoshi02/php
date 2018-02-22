<?php include 'config.php'; session_start();

if(isset($_POST) && isset($_POST['docu_id']) && $_POST['docu_id'] >= 0){	
	$id = $_POST['docu_id'];
	$lead_id = $_SESSION['leadID'];		
	$user_id = $_SESSION['sess_user_id'];
	$date = date('Y-m-d h:m:s');
	
	$sql = "UPDATE lead_attachment_detail SET status = 'deleted', deleted_user_id = ".$user_id.", modified_date = '".$date."' WHERE id = ".$id." AND lead_id = ".$lead_id." AND user_id = ".$user_id;
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	
	echo 1;
}
else{
	if(isset($_POST) && $_FILES['document']['size'] > 0){
		$lead_id = $_POST['doc_cat'];
		
		$target_dir = "uploads_lead_details/";
		$target_file = $target_dir . basename($_FILES["document"]["name"]);
		
		$fileName = $_FILES['document']['name'];
		$tmpName  = $_FILES['document']['tmp_name'];
		$fileSize = $_FILES['document']['size'];
		$fileType = $_FILES['document']['type'];
		
		$fp = fopen($tmpName, 'r');
		
		try {					
			if (move_uploaded_file($_FILES["document"]["tmp_name"], $target_file)) {
				
				$lead_id = $_SESSION['leadID'];		
				$user_id = $_SESSION['sess_user_id'];
				$date = date('Y-m-d h:m:s');
							
				$sql = "INSERT INTO lead_attachment_detail (lead_id, user_id, attachment_path, status, notification, added_date, 	modified_date)
				VALUES ('$lead_id', '$user_id', '$target_file', 'enabled', '0', '$date', '$date')";
				$conn->exec($sql);
				
				$last_insert_id = $conn->lastInsertId();	
					
				$user_file_extn = explode(".", strtolower($fileName));
				$extn = $user_file_extn[1];
			
				if($extn == "pdf"){
					$msg = $msg = '<div style="float: left; margin: 10px 20px 20px 0px;"><div style="position: relative;"><a style="float: right; position: absolute; top: 0px; right: 0px;" href="javascript:void(0);" onclick="delete_document("'.this.'","'.$last_insert_id.'");"><img src="images/close_icon.png"></a><a target="_blank" style="color: blue; float: left;" href="uploads_lead_details/'.$fileName.'"><img style="" src="images/pdf_icon.png"><br><font style="float: left; margin-top: 8px; font-weight: bold;">'.$fileName.'</font></a></div></div>';
				}	
				else if($extn == "csv"){
					$msg = '<div style="float: left; margin: 10px 20px 20px 0px;"><div style="position: relative;"><a style="float: right; position: absolute; top: 0px; right: 0px;" href="javascript:void(0);" onclick="delete_document("'.this.'","'.$last_insert_id.'");"><img src="images/close_icon.png"></a><a target="_blank" style="color: blue; float: left;" href="uploads_lead_details/'.$fileName.'"><img style="" src="images/csv_icon.png"><br><font style="float: left; margin-top: 8px; font-weight: bold;">'.$fileName.'</font></a></div></div>';
				}	
				else if($extn == "xls" || $extn == "xlsx"){
					$msg = '<div style="float: left; margin: 10px 20px 20px 0px;"><div style="position: relative;"><a style="float: right; position: absolute; top: 0px; right: 0px;" href="javascript:void(0);" onclick="delete_document("'.this.'","'.$last_insert_id.'");"><img src="images/close_icon.png"></a><a target="_blank" style="color: blue; float: left;" href="uploads_lead_details/'.$fileName.'"><img style="" src="images/xls_icon.png"><br><font style="float: left; margin-top: 8px; font-weight: bold;">'.$fileName.'</font></a></div></div>';
				}	
				else if($extn == "doc" || $extn == "docx"){
					$msg = '<div style="float: left; margin: 10px 20px 20px 0px;"><div style="position: relative;"><a style="float: right; position: absolute; top: 0px; right: 0px;" href="javascript:void(0);" onclick="delete_document("'.this.'","'.$last_insert_id.'");"><img src="images/close_icon.png"></a><a target="_blank" style="color: blue; float: left;" href="uploads_lead_details/'.$fileName.'"><img style="" src="images/doc_icon.png"><br><font style="float: left; margin-top: 8px; font-weight: bold;">'.$fileName.'</font></a></div></div>';
				}	
				else if($extn == "ppt" || $extn == "pptx"){
					$msg = '<div style="float: left; margin: 10px 20px 20px 0px;"><div style="position: relative;"><a style="float: right; position: absolute; top: 0px; right: 0px;" href="javascript:void(0);" onclick="delete_document("'.this.'","'.$last_insert_id.'");"><img src="images/close_icon.png"></a><a target="_blank" style="color: blue; float: left;" href="uploads_lead_details/'.$fileName.'"><img style="" src="images/ppt_icon.png"><br><font style="float: left; margin-top: 8px; font-weight: bold;">'.$fileName.'</font></a></div></div>';
				}	
				else{
					$msg = '<div style="float: left; margin: 10px 20px 20px 0px;"><div style="position: relative;"><a style="float: right; position: absolute; top: 0px; right: 0px;" href="javascript:void(0);" onclick="delete_document("'.this.'","'.$last_insert_id.'");"><img src="images/close_icon.png"></a><a target="_blank" style="color: blue; float: left;" href="uploads_lead_details/'.$fileName.'"><font style="float: left; margin-top: 8px; font-weight: bold;">'.$fileName.'</font></a></div></div>';
				}
				echo $msg;
			} 
			else {
				$msg = "Sorry, there was an error uploading your file.";
			}
			fclose($fp);	
		}
		catch(PDOException $e){
			echo "<br>" . $msg . "<br>" . $e->getMessage();
		}
	}
	else{
		echo "Please select File to upload.";
	}
}	

/*if(!get_magic_quotes_gpc()){
	$fileName = addslashes($fileName);
}*/
?>