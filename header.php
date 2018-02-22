<?php 
header('P3P: CP=”NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM”');
session_set_cookie_params(3600,"/");
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
ob_start();
include 'config.php';
if(isset($_SESSION['sess_user_id'])){
$user_id = $_SESSION['sess_user_id'];
}
// Report all errors except E_NOTICE   
error_reporting(E_ALL ^ E_NOTICE);
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta charset="utf-8" /> 
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>IceCOLD | CRM Dashboard</title>
    <link rel="shortcut icon" type="image/png" href="images/favicon.png" />
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet" />
	<link href="css/bootstrap-multiselect.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet" />
	<link href="css/metisMenu.min.css" rel="stylesheet" />
    <!-- Morris Charts CSS -->
    <link href="css/plugins/morris.css" rel="stylesheet" />
    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link href="css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	<link href="style.css" rel="stylesheet" type="text/css" />
	<script src="js/jquery.js"></script>
	<script src="js/jquery.dataTables.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-multiselect.js"></script>
	<script src="js/custom.js"></script>
	<script src="js/sb-admin-2.js"></script>
	<script src="js/metisMenu.min.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<link href="css/bootstrap-datepicker.standalone.css" rel="stylesheet" />
	<script src="js/moment.js"></script>
	<script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.9/sorting/datetime-moment.js"></script>
	<script type="text/javascript" src="js/jquery.fancybox.js"></script>
	<link rel="stylesheet" type="text/css" href="css/jquery.fancybox.css" media="screen" />
<script type="text/javascript">
$.fn.datepicker.defaults.format = "yyyy-mm-dd";
$(document).ready(function() {
  $.ajaxSetup({ cache: false });
});
$(document).ready(function() {
  $.fancybox({
    padding: 0,
	openEffect : 'elastic',
	openSpeed  : 150,
	closeEffect : 'elastic',
	closeSpeed  : 150,
	closeClick : false,
	helpers : {
		overlay : null
	}
  });
});
</script>
</head>
<body>
<?php include_once("analyticstracking.php") ?>
<div id="loading-image">Please wait...</div>
<?php include 'function.php';?>
<div id="wrapper">
<div class="row">
<!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
         <a class="navbar-brand" href="home.php"><img src="images/logo1.jpg"></a>
         <a href="#" onClick="totalLeads();" class="totalleads"><i class="fa fa-table fa-fw"></i>Leads <span class="badge"><?php echo $count;?></span></a>
        </div>
<!-- /.navbar-header -->
        <?php 
        if (isset($_SESSION['sess_useremail'])) {
        ?>        
        <div style="text-align:center;">
        	<span class="text-muted" style="background-color:#e9d4d3;padding: 2px;">STAGING SERVER</span>
			<span class="text-muted large">Welcome</span>
			<?php
				echo $_SESSION['sess_user_name'];
				if($_SESSION['last_login']==''){
			?>
				<span class="text-muted small">
				, Last login: 
					<?php echo "First Login.";?>
				 ago
				</span>
			<?php
				}else{
			?>
			<span class="text-muted small">
				, Last login: 
					<?php echo time_since(time() - strtotime($_SESSION['last_login']));?>
				 ago
			</span>
			<?php
			}
			?>
        </div>
        <ul class="user_access">
         	<li>
         	<span>
         	<?php 
         		try{
         			$queryforuserrole="SELECT user_role_name from user_role WHERE user_role_id=?";
         			$userrolestmt=$conn->prepare($queryforuserrole);
         			$userrolestmt->execute(array($_SESSION['sess_userrole']));
         			$resultuserrole=$userrolestmt->fetch(PDO::FETCH_ASSOC);
         			echo "ROLE: ".$resultuserrole['user_role_name'];
         		}catch(PDOException $e)
				{
					echo $sql . "<br>" . $e->getMessage();
				}
         	?>
         	</span>
         	</li>
         	<li>
         	<?php if($_SESSION['sess_dbatype']==1){ ?>
		    	<span>DBA TYPE: Corporate</span>
			<?php }elseif($_SESSION['sess_dbatype']==2){ ?>
				<span>DBA TYPE: Master</span>
			<?php }elseif($_SESSION['sess_dbatype']==3){ ?>    
			    <span>DBA TYPE: Distributor</span>    
			<?php }elseif($_SESSION['sess_dbatype']==4){ ?>
			    <span>DBA TYPE: Dealer</span>
			<?php } ?>
			</li>
		</ul>
			<ul class="nav navbar-top-links navbar-right">
				<li class="dropdown">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="false">
                        <i class="fa fa-envelope fa-fw"></i><span class="badge"><?php if( ($lead_assignedto_notification+$lead_assignedfrom_notification)==0 ){}else{ echo ($lead_assignedto_notification+$lead_assignedfrom_notification); } ?></span><i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                    	<?php
                    		if( ($lead_assignedto_notification+$lead_assignedfrom_notification)==0 ){
                    	?>
                    	<li>
                    		<a href="#">
                    		<div>
                    		No new lead assigned to you recently.
                    		</div>
                    		</a>
                    	</li>
                    	<?php
                    		}else{
                    		if($lead_assignedto_notification != 0){
                    			try{
                    				$sql="SELECT leads.lead_id, leads.company,users.user_fname,users.user_lname,assign.assigned_by, assign.assign_date FROM assign
										JOIN leads ON leads.lead_id = assign.lead_id
										JOIN users ON users.user_id = assign.assignedfrom_userid
										WHERE assign.notifyto_enabled =1
										AND assign.assignedto_userid=? 
										ORDER BY assign.assign_date DESC";
									$query  = $conn->prepare($sql);
									$query->execute(array($user_id));
									$query->setFetchMode(PDO::FETCH_ASSOC);
									while ($row = $query->fetch()) {
										$timestamp = strtotime($row['assign_date']);
										$ass_to_date = date('d-M-Y', $timestamp);
                    	?>
		                        <li>
		                            <a href="#" onClick="lead_edit_form('<?php echo $row['lead_id'];?>','<?php ?>','<?php echo $_SERVER['PHP_SELF'];?>','<?php echo "";?>','<?php echo "";?>');">
		                                <div>
		                                    <strong>Lead assigned to you.</strong>
		                                    <span class="pull-right text-muted">
		                                        <em><?php echo $ass_to_date;?></em>
		                                    </span>
		                                    <div class="divider"></div>
		                                    <div class="clear"></div>
		                                </div>
		                                <div>
		                                	<span>Customer : </span>
		                                	<span class="pull-right text-muted">
		                                        <em><?php echo $row['company'];?></em>
		                                    </span>
		                               	</div>
		                               	<div>
		                                	<span>Assigned From : </span>
		                                	<span class="pull-right text-muted">
		                                        <em><?php echo $row['user_fname']." ".$row['user_lname'];?></em>
		                                    </span>
		                               	</div>
		                               	<div>
		                                	<span>Assigned By : </span>
		                                	<span class="pull-right text-muted">
		                                        <em><?php echo $row['assigned_by'];?></em>
		                                    </span>
		                               	</div>
		                            </a>
		                        </li>
		                        <li class="divider"></li>
			                        <?php
			                        	}
	                        		}catch(PDOException $e)
									{
										echo $sql . "<br>" . $e->getMessage();
									}
                    			}
                    			if($lead_assignedfrom_notification != 0){
                    				try{
                    				$sql="SELECT leads.lead_id, leads.company,users.user_fname,users.user_lname,assign.assigned_by, assign.assign_date FROM assign
										JOIN leads ON leads.lead_id = assign.lead_id
										JOIN users ON users.user_id = assign.assignedto_userid
										WHERE assign.notifyto_enabled =1
										AND assign.assignedfrom_userid=? 
										ORDER BY assign.assign_date DESC";
									$query  = $conn->prepare($sql);
									$query->execute(array($user_id));
									$query->setFetchMode(PDO::FETCH_ASSOC);
									while ($row = $query->fetch()) {
										$timestamp = strtotime($row['assign_date']);
										$ass_frm_date = date('d-M-Y', $timestamp);
                    	?>
		                        <li>
		                            <a href="#" onClick="lead_edit_form('<?php echo $row['lead_id'];?>','<?php ?>','<?php echo $_SERVER['PHP_SELF'];?>','<?php echo "";?>','<?php echo "";?>');">
		                                <div>
		                                    <strong>Lead has been reassigned.</strong>
		                                    <span class="pull-right text-muted">
		                                        <em><?php echo $ass_frm_date;?></em>
		                                    </span>
		                                    <div class="divider"></div>
		                                    <div class="clear"></div>
		                                </div>
		                                <div>
		                                	<span>Customer : </span>
		                                	<span class="pull-right text-muted">
		                                        <em><?php echo $row['company'];?></em>
		                                    </span>
		                               	</div>
		                               	<div>
		                                	<span>Assigned To : </span>
		                                	<span class="pull-right text-muted">
		                                        <em><?php echo $row['user_fname']." ".$row['user_lname'];?></em>
		                                    </span>
		                               	</div>
		                               	<div>
		                                	<span>Assigned By : </span>
		                                	<span class="pull-right text-muted">
		                                        <em><?php echo $row['assigned_by'];?></em>
		                                    </span>
		                               	</div>
		                            </a>
		                        </li>
		                        <li class="divider"></li>
			                        <?php
			                        	}
	                        		}catch(PDOException $e)
									{
										echo $sql . "<br>" . $e->getMessage();
									}

                    			}
                    		}
                        ?>
                    </ul>
                    <!-- /.dropdown-messages -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="false">
                        <i class="fa fa-tasks fa-fw"></i> <span class="badge"><?php if($lead_stage_update_notification==0){}else{ echo $lead_stage_update_notification;}?></span> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-tasks">
                    <?php
                    if( $lead_stage_update_notification==0 ){
                    ?>
                    	<li>
                    		<a href="#">
                    		<div>
                    		Lead Stage has not been updated recently on any lead.
                    		</div>
                    		</a>
                    	</li>
                	<?php
                	}else{
                    try{
						$sql="SELECT leads.lead_id, leads.company, lead_stage_detail.lead_stage_date, lead_stage_detail.lead_stage, users.user_fname, users.user_lname FROM lead_stage_detail  
							JOIN leads ON leads.lead_id=lead_stage_detail.lead_id
							JOIN users ON users.user_id = lead_stage_detail.user_id 
							WHERE lead_stage_detail.enabled=1 AND leads.user_id=?
							ORDER BY lead_stage_detail.lead_stage_date DESC";
						$query  = $conn->prepare($sql);
						$query->execute(array($user_id) );
						$query->setFetchMode(PDO::FETCH_ASSOC);
						while ($row = $query->fetch()) {
						?>
                        <li>
                            <a href="#" onClick="lead_edit_form('<?php echo $row['lead_id'];?>','<?php ?>','<?php echo $_SERVER['PHP_SELF'];?>','<?php echo "";?>','<?php echo "";?>');">
                                <div>
                                    <p>
                                        <strong>Customer : </strong><i><?php echo $row['company'];?></i>
                                        <span class="pull-right text-muted"><?php echo $row['lead_stage'];?></span>
                                    </p>
                                    <?php
                                    if($row['lead_stage']=="10.NoAction"){
                                    	$prog = '0';
                                    }
                                    elseif ($row['lead_stage']=="9.Completed") {
                                    	$prog = '100';
                                    }elseif ($row['lead_stage']=="8.Installation") {
                                    	$prog = '85';
                                    }else{
                                    	$prog = substr($row['lead_stage'],0,1)."0";
									}
									?>                                    
                                    <div class="progress progress-striped active">
                                        <div style="<?php echo 'width:'.$prog;?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo $prog;?>" role="progressbar" class="progress-bar progress-bar-success">
                                            <?php
                                			if($row['lead_stage']=="10.NoAction"){
                                			}else{
                                			?>
                                        		<span><?php echo $prog;?>% Complete (success)</span>
                                			<?php
                                			}
                                			?>	
                                        </div>
                                    </div>
                                    <p>
                                    	<strong>Lead stage updated by:</strong>
                                    </p>
                                    <p>
                                    	<span class="pull-left text-muted">
                                    	<?php
                                    		echo $row['user_fname']." ".$row['user_lname'];
                                    	?>
                                    	</span>
                                    	<span class="pull-right text-muted">
                                    	<?php
                                    		echo time_since(time() - strtotime($row['lead_stage_date']));
                                    	?>
                                    	ago
                                    	</span>
                                    </p>
                                    <div class="clear"></div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <?php
						}
					}catch(PDOException $e) {
					    echo "Error: " . $e->getMessage();
					}
				}
                    ?>
                    </ul>
                    <!-- /.dropdown-tasks -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="false" title="Notification">
                        <i class="fa fa-bell fa-fw"></i> <?php if($new_lead_notification==0){}else{?><span class="badge lead_notify"><?php echo $new_lead_notification;?></span><?php } ?> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
						<?php 			
							try{
								if ($_SESSION['sess_userrole']!=8) {
									if($_SESSION['sess_dbatype'] == 1){
								
										$sql = "SELECT * FROM leads WHERE 
										(lead_status_id = 1 && (leads.lead_type_id = 3 || leads.lead_type_id = 4 || leads.lead_type_id = 7 || leads.lead_type_id = 8 || leads.lead_type_id = 9) && lead_archive_status=0) 
										|| (leads.user_id IN (SELECT user_id FROM users WHERE user_company_id = :comp_id &&  user_role_id = 8) && lead_status_id = 1) && lead_archive_status=0 
										|| leads.user_id = 0 && lead_archive_status=0 && lead_status_id=1
										|| lead_status_id=1 && lead_archive_status=0 && leads.user_id IN (SELECT user_id FROM users WHERE user_role_id != 8)
										ORDER BY leads.date DESC LIMIT 5";	
										$stmt  = $conn->prepare($sql);
										$stmt->execute(array(':comp_id' => $_SESSION['sess_usercomp_id']));
									}else{
										$sql = "SELECT * FROM leads WHERE 
										lead_status_id = 1 
										&& (leads.lead_type_id = 1 || leads.lead_type_id = 2 || leads.lead_type_id = 5 || leads.lead_type_id = 6) 
										&& leads.user_id IN (SELECT user_id FROM users WHERE user_company_id = :comp_id && user_role_id = 8) && lead_archive_status=0
										ORDER BY leads.date DESC LIMIT 5";
										$stmt  = $conn->prepare($sql);
										$stmt->execute(array(':comp_id' => $_SESSION['sess_usercomp_id']));
									}
								}
								else{
									$sql = "SELECT * FROM leads WHERE user_id = :user_id && lead_status_id = 1 ORDER BY leads.date DESC LIMIT 5";
									$stmt  = $conn->prepare($sql);
									$stmt->execute(array(':user_id' => $user_id));
								}
								
								// this is for deleted leads
								if($_SESSION['sess_userrole'] == 1) {
									$sql2 = "SELECT * FROM archived_leads WHERE user_id = ".$user_id." and notify_status = 0 ORDER BY lead_archive_id DESC";
									$stmt2  = $conn->prepare($sql2);
									$stmt2->execute();
									$stmt2->setFetchMode(PDO::FETCH_ASSOC);
									while ($row2 = $stmt2->fetch()) {
									?>
										<li>
											<a href="#" onClick="lead_edit_form('<?php echo $row2['lead_id'];?>','<?php ?>','<?php echo $_SERVER['PHP_SELF'];?>','<?php echo "";?>','<?php echo "";?>');lead_notifi_counter_change(this,'<?php echo $row2['lead_archive_id'];?>');">
												<div>
													<i style="color:gray; padding-right:5px;"class="fa fa-trash" aria-hidden="true"></i> Lead <em><?php //echo $row['company'];?></em> deleted.
													<span class="pull-right text-muted small"><?php echo time_since(time() - strtotime($row2['lead_archived_time']));?> ago</span>
												</div>
											</a>
										</li>
										<br />
										<li class="divider"></li>
									<?php	
									}
								}
								else if($_SESSION['sess_userrole'] == 2){
									$sql2 = "SELECT * FROM archived_leads as al left join users as u on u.user_id = al.user_id and u.user_role_id = 2 WHERE al.user_id = ".$user_id." and al.notify_status = 0 ORDER BY al.lead_archive_id DESC";
									$stmt2  = $conn->prepare($sql2);
									$stmt2->execute();
									$stmt2->setFetchMode(PDO::FETCH_ASSOC);
									while ($row2 = $stmt2->fetch()) {
									?>
										<li>
											<a href="#" onClick="lead_edit_form('<?php echo $row2['lead_id'];?>','<?php ?>','<?php echo $_SERVER['PHP_SELF'];?>','<?php echo "";?>','<?php echo "";?>');lead_notifi_counter_change(this,'<?php echo $row2['lead_archive_id'];?>');">
												<div>
													<i style="color:gray; padding-right:5px;"class="fa fa-trash" aria-hidden="true"></i> Lead <em><?php //echo $row['company'];?></em> deleted.
													<span class="pull-right text-muted small"><?php echo time_since(time() - strtotime($row2['lead_archived_time']));?> ago</span>
												</div>
											</a>
										</li>
										<br />
										<li class="divider"></li>
									<?php	
									}
								}
								else if($_SESSION['sess_userrole'] == 5){
									$sql2 = "SELECT * FROM archived_leads as al left join users as u on u.user_id = al.user_id and u.user_company_id = ".$_SESSION['sess_dbatype']." WHERE al.notify_status = 0 and al.user_id = ".$user_id." ORDER BY al.lead_archive_id DESC";
									$stmt2  = $conn->prepare($sql2);
									$stmt2->execute();
									$stmt2->setFetchMode(PDO::FETCH_ASSOC);
									while ($row2 = $stmt2->fetch()) {
									?>
										<li>
											<a href="#" onClick="lead_edit_form('<?php echo $row2['lead_id'];?>','<?php ?>','<?php echo $_SERVER['PHP_SELF'];?>','<?php echo "";?>','<?php echo "";?>');lead_notifi_counter_change(this,'<?php echo $row2['lead_archive_id'];?>');">
												<div>
													<i style="color:gray; padding-right:5px;"class="fa fa-trash" aria-hidden="true"></i> Lead <em><?php //echo $row['company'];?></em> deleted.
													<span class="pull-right text-muted small"><?php echo time_since(time() - strtotime($row2['lead_archived_time']));?> ago</span>
												</div>
											</a>
										</li>
										<br />
										<li class="divider"></li>
									<?php	
									}
								}	
								// delete leads end here
								
								// follow-up date notification start
									$sql3 = "SELECT * FROM users as u Right Join leads as l ON l.lead_owner = CONCAT(u.user_fname, ' ', u.user_lname) and l.user_id = ".$user_id." and l.follow_up_date >= CURDATE() WHERE u.user_id = ".$user_id."  and datediff(l.follow_up_date,'".$date."') IN (0,7,14)";
									$stmt3  = $conn->prepare($sql3);
									$stmt3->execute();
									$stmt3->setFetchMode(PDO::FETCH_ASSOC);
									while ($row3 = $stmt3->fetch()) {										
										$date1 = date("Y-m-d");
										$date2 = $row3['follow_up_date'];
										$your_follow_up_date = date('d-M-Y', strtotime($date2));
										$user_name = "User name is not defined.";
										$diplay = "yes";
										
										$diff = abs(strtotime($date2) - strtotime($date1));
										
										$years = floor($diff / (365*60*60*24));
										$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
										$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
										
										if($row3['fname'] != "" && $row3['lname'] != ""){
											$user_name = $row3['fname']." ".$row3['lname'];
										}
										
										if($days == 0){
											$time = "Today";
										}
										else if($days > 0){
											$week = $days / 7;
											if($week == 1){
												$time = "After 1 week";
											}
											else if($week == 2){
												$time = "After 2 week";
											} 
											else{
												$diplay = "no";
											}
										}
										
										if($diplay == "yes"){										
										?>
											<li>
												<a href="#" onClick="lead_edit_form('<?php echo $row3['lead_id'];?>','<?php ?>','<?php echo $_SERVER['PHP_SELF'];?>','<?php echo "";?>','<?php echo "";?>');follow_up_date_counter_change(this,'<?php echo $row3['lead_id'];?>');">
													<div>
														<strong>Follow Up Date</strong>
														<span class="pull-right text-muted">
															 <em><?php echo $your_follow_up_date."(".$time.")";?></em>
														</span>
													</div>													
													<div>
														<span>User : </span>
														<span class="pull-right"><?php echo $user_name; ?></span>
													</div>
												</a>
											</li>		
											<br />
											<li class="divider"></li>
									<?php	
										}
									}
									// follow-up date notification end here
									
								$stmt->setFetchMode(PDO::FETCH_ASSOC);
								while ($row = $stmt->fetch()) {
									?>
								<li>
									<a href="#" onClick="lead_edit_form('<?php echo $row['lead_id'];?>','<?php ?>','<?php echo $_SERVER['PHP_SELF'];?>','<?php echo "";?>','<?php echo "";?>');lead_notifi_counter_change(this,'<?php echo $row['lead_archive_id'];?>');">
										<div>
											<i style="color:green;"class="fa fa-pagelines fa-fw"></i> Lead <em><?php echo $row['company'];?></em> requires action!
											<span class="pull-right text-muted small"><?php echo time_since(time() - strtotime($row['date']));?> ago</span>
										</div>
									</a>
								</li>
								<br />
								<li class="divider"></li>
								<?php	
								}
							}catch(PDOException $e) {
								echo "Error: " . $e->getMessage();
							}
							?>
                        <?php  
							if($new_lead_notification==0){
								?>
							<li>
								<a href="#" class="text-center">
									<strong>No New Notication!</strong>
								</a>
							</li>
							<?php }else{ ?>
							<li>
								<a href="unapproved-leads.php" class="text-center">
									<strong>See All leads to be approved</strong>
									<i class="fa fa-angle-right"></i>
								</a>
							</li>
							<?php } ?>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="false" title="User Profile">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> Welcome, <?php echo $_SESSION['sess_user_name'];?></a>
                        </li>
                        <li><a href="#" class="change-password"><i class="fa fa-sign-out fa-fw"></i> Change Password</a></li>
                        <?php
                        if($_SESSION['sess_userrole']==1){
                        ?>
                        <li><a href="#" class="user-login-report"><i class="fa fa-list-alt fa-fw"></i> User Login Report</a></li>
                        <?php
                    	}
                        ?>
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
        <?php
		}
		?>
    </nav>
   </div>
   <div id="display-return-msg"></div>
	<div id="page-wrapper">
	  <div class="container">
		<div class="row">
          <div class="col-lg-12">