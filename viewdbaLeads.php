<?php  include 'config.php';
session_start();
    if(!isset($_SESSION['sess_useremail'])){
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
		session_unset();
		session_destroy();
      header('Location: index.php?err=2');
    }else{

?>

<div class="table-responsive panel panel-primary">
	<div class="panel-heading">
		<a class="pull-right add-new" onclick="addNewLead('<?php echo $_POST['comp_id']; ?>');">Add lead</a>
		<h3 class="panel-title">Leads</h3>

	</div>
	<div class="panel-body" id="dvData">
		<table class="table table-hover table-condensed" id="leads">
			<thead>
				<tr>
					<th>Lead Id</th>
					<th>Lead Owner</th>
					<th>DBA Name</th>
					<th>Customer</th>
					<th>Last Comment Date</th>
					<th>Created Date</th>
					<th>Lead Stage</th>
					<th>Stage Date</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
			<?php
			try {
					if($_SESSION['sess_userrole']!=8){
						$sql = "SELECT * FROM leads	JOIN users ON users.user_id=leads.user_id WHERE 
						leads.lead_archive_status=0 && users.user_company_id=?";
						$stmt  = $conn->prepare($sql);
						$stmt->execute(array($_POST['comp_id']));
					}else{
                        $sql = "SELECT *  FROM leads JOIN users ON users.user_id=leads.user_id WHERE 
                            leads.lead_archive_status=0 && leads.user_id=? "; 
                        $stmt = $conn->prepare($sql);
                        $stmt->execute(array($_SESSION['sess_user_id']));
                    }
			
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				while ($row = $stmt->fetch()) {
				$timestamp = strtotime($row['date']);
				$date = date('d-M-Y', $timestamp);
				?>
				<tr>
					<td>
						<b><?php echo $row['lead_id'];?></b>
					</td>
					<td>
						<?php echo $row['lead_owner'];?>
					</td>
					<td>
						<?php
							if ($row['user_id']==0) {
								echo "No DBA name associated yet!";
							}else{
								try{
									$sql="SELECT user_company_name FROM users JOIN user_company ON user_company.user_company_id=users.user_company_id
											WHERE users.user_id=?";
									$query= $conn->prepare($sql);
									$query->execute(array($row['user_id']));
									$result = $query->fetch(PDO::FETCH_ASSOC);
									echo $result['user_company_name'];

								}catch(PDOException $e) {
									echo "Error: " . $e->getMessage();
								}
							}
						?>
					</td>
					<td>
						<a href="#" onClick="lead_edit_form('<?php echo $row['lead_id'];?>','<?php echo $row['lead_owner'];?>','<?php echo $_SERVER['PHP_SELF'];?>','<?php echo "";?>','<?php echo "";?>');"><?php echo wordwrap($row['company'],20,"<br>\n"); ?></a>
					</td>
					<td>
						<?php 
							   try{
									$sql = "Select comment_date From lead_comments where lead_id = ".$row['lead_id']." order by comment_id DESC";
									$query= $conn->prepare($sql);
									$query->execute();
									$result = $query->fetch(PDO::FETCH_ASSOC);
									
									if(isset($result['comment_date']) && $result['comment_date'] != ""){
										$timestamp = strtotime($result['comment_date']);
										echo date('d-M-Y', $timestamp);
									}	
								}
								catch(PDOException $e) {
									echo "Error: " . $e->getMessage();
								}
						?>
					</td>
					<td>
						<?php echo $date;?>
					</td>
					<td>
						<div style="float:left;width:85%;margin-right:3px">
						<?php
							echo $row['lead_status'];
						?>
						</div>
  						<div style="float:right;width:10%;">
  						<?php 
							$retval = ($row['lead_protected_flag']==0) ? "<i style='color:red;' class='fa fa-unlock'></i>" : "<i style='color:green;' class='fa fa-lock'></i>" ;
							echo $retval;	
						?>	
  						</div>
					</td>
					<td>
						<?php
							echo date("d-M-Y", strtotime($row['lead_status_change_date']));
						?>	
						
					</td>
					<td><a href="#" onclick="confirmDelete('<?php echo $row['lead_id'];?>'); "><span class="glyphicon glyphicon-trash"></span></a></td>
				</tr>
				<?php }
			}
			catch(PDOException $e) {
			    echo "Error: " . $e->getMessage();
			}
			?>
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $.fn.dataTable.moment( 'D-MMM-YYYY' );

    $('#leads').DataTable({
		"processing": true,
		"sDom": '<"top"lif>rt<"bottom"lp><"clear">',	
	});
} );


</script>
<?php
}
?>
