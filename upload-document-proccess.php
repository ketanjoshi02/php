<?php include 'config.php'; session_start();
//print_r($_POST);
$user_id = $_SESSION['sess_user_id'];


if(isset($_POST) && $_FILES['document']['size'] > 0)
{
	$category_id = $_POST['doc_cat'];
	$doc_desc = htmlspecialchars($_POST['doc_desc'],ENT_QUOTES);
	switch ($category_id) {
		case 1:
			$category="Sales";
			break;
		case 2:
			$category="Technical";
			break;
		case 3:
			$category="Pricing";
			break;
		case 4:
			$category="Policies";
			break;
		case 5:
			$category="Other";
			break;
		default:
			break;
	}

	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["document"]["name"]);
	
	$fileName = $_FILES['document']['name'];
	$tmpName  = $_FILES['document']['tmp_name'];
	$fileSize = $_FILES['document']['size'];
	$fileType = $_FILES['document']['type'];
	

	$fp      = fopen($tmpName, 'r');
	//$content = fread($fp, filesize($tmpName));
	//$content = addslashes($content);


	if (move_uploaded_file($_FILES["document"]["tmp_name"], $target_file)) {
        $msg = "The file ". basename( $_FILES["document"]["name"]). " has been uploaded to ".$category." Category";
    } else {
        $msg = "Sorry, there was an error uploading your file.";
    }
	fclose($fp);
	
	if(!get_magic_quotes_gpc())
	{
		$fileName = addslashes($fileName);
	}
	
	try {	
		$sql = "INSERT INTO document (doc_name, doc_type, doc_size, user_id, doc_category_id, doc_date, doc_desc)
		VALUES ('$fileName', '$fileType', '$fileSize', '$user_id', '$category_id', now(), '$doc_desc')";
		// use exec() because no results are returned
		$conn->exec($sql);
		
		echo $msg;

			/*require_once "PHPMailer/PHPMailerAutoload.php";

			//PHPMailer Object
			$mail = new PHPMailer;

			//From email address and name
			$mail->From = $_SESSION['sess_useremail'];
			$mail->FromName = $_SESSION['sess_user_name'];

			//To address and name
			//$mail->addAddress("ketan.joshi@wsisrdev.net", "Ketan Joshi");
			try{
				$q="SELECT user_email from users WHERE user_id <> ?";
				$st = $conn->prepare($q);
	            $st->execute(array($_SESSION['sess_user_id']) );

	            $st->setFetchMode(PDO::FETCH_ASSOC);            
	            while ($row = $st->fetch() ) {
	            	//$mail->addAddress($row['user_email']); //Recipient email addresses.
	            }
			}catch(PDOException $e)
			{
			echo "<br>" . $e->getMessage();
			}
			

			//Address to which recipient will reply
			//$mail->addReplyTo("ketan.joshi@wsisrdev.net", "Reply");
			$mail->addAddress("krinal@searchresultsmedia.com", "Krinal Mehta"); //Recipient email addresses.
			//CC and BCC
			//$mail->addCC("ketan.joshi@wsisrdev.net");
			$mail->addBCC("ketan.joshi@wsisrdev.net");

			//Send HTML or Plain Text email
			$mail->isHTML(true);

			$mail->Subject = "Document added in the CRM.";
			$mail->Body = "<i>".$msg."</i>";
			$mail->AltBody = $msg;

			if(!$mail->send()) 
			{
			    echo "Mailer Error: " . $mail->ErrorInfo;
			} 
			else 
			{
			    //echo "Email has been sent successfully";
			}*/
			
		}
	catch(PDOException $e)
		{
		echo "<br>" . $msg . "<br>" . $e->getMessage();
		}
	//echo "<br>File $fileName uploaded<br>";	
}else{
	echo "Please select File to upload.";
}
?>