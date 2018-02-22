<?php
	include 'config.php'; session_start();
	
	try {			
		$comment_type = $_POST['comment_type'];
		$lead_id = $_SESSION['leadID'];
		$comment_details = "";
				
		if($comment_type == "lead_comment"){
			$sql= "SELECT * FROM lead_comments as lc JOIN users as u ON lc.user_id = u.user_id WHERE lc.lead_id = ?";
			/*$stmt = $conn->prepare($sql);
			$stmt->execute(array($last_id));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$comment_text = $row['comment_text'];
			$date = date("d-M-Y",strtotime($row['comment_date']));*/
			$comm_name = 'comment_text';
			$comm_date = 'comment_date';
		}
		elseif($comment_type == "follow_up_date_comment"){
			$sql= "SELECT * FROM follow_up_date_comment as fd JOIN users as u ON fd.user_id = u.user_id WHERE fd.status = 'enabled' AND fd.lead_id = ?";
			$comm_name = 'comment';
			$comm_date = 'modified_date';
		}	
		elseif($comment_type == "watch_list_comment"){
			$sql= "SELECT * FROM watch_list_comment as wl JOIN users as u ON wl.user_id = u.user_id WHERE wl.status = 'enabled' AND wl.lead_id = ?";
			$comm_name = 'comment';
			$comm_date = 'modified_date';
			/*$stmt = $conn->prepare($sql);
			$stmt->execute(array($last_id));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$comment_text = $row['comments'];
			$date = date("d-M-Y",strtotime($row['added_date']));*/
		}		
		$stmt = $conn->prepare($sql);
		$stmt->execute(array($lead_id));
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		while ($row = $stmt->fetch()) { 
			$user_name = $row['user_fname']." ".$row['user_lname'];
			$comment_text = $row[$comm_name];
			$date = date("d-M-Y",strtotime($row[$comm_date]));
			$comment_details .= '<li class="left clearfix">
				 <span class="chat-img pull-left">
					  <img class="img-circle" alt="User Avatar" src="images/user.png" height="48" width="48">
				 </span>
				 <div class="chat-body clearfix">
					  <div class="header">
							<strong class="primary-font">'.$user_name.'</strong>
							<small class="pull-right text-muted">
								 <i class="fa fa-clock-o fa-fw"></i> '.$date.'
							</small>
					  </div>
					  <p>'.$comment_text.'</p>
				 </div>
			</li>';
		}
		echo $comment_details;
	}
	catch(PDOException $e){
		echo $sql . "<br>" . $e->getMessage();
	}
	?>

		
		
		
		