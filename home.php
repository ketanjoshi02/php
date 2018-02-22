<?php  include 'header.php';
		
		if($_SESSION['passwordneedstobechanged']==999){
			header('Location: password-expired.php');
			break;
		}

		$dbatype = $_SESSION['sess_dbatype'];
			if(!isset($_SESSION['sess_useremail']) ){
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
						<div id="display-area">
						
							<div class="row">
				                <div class="col-lg-3 col-md-6">
				                    <div class="panel panel-primary">
				                        <div class="panel-heading">
				                            <div class="row">
				                                <div class="col-xs-3">
				                                    <i class="fa fa-building fa-5x"></i>
				                                </div>
				                                <div class="col-xs-9 text-right">
				                                    <div class="huge">
				                                    	<?php
				                                    	try{
				                                    		if($_SESSION['sess_userrole']<=4){
					                                    		$sql="SELECT DISTINCT count(*) from user_company";
					                                    		$stmt = $conn->prepare($sql);
				                                    			$stmt->execute();

				                                    			echo $stmt->fetchColumn();
				                                    		}elseif($_SESSION['sess_userrole']==8){
				                                    			$sql="SELECT DISTINCT count(*) from user_company WHERE user_company_id=?";
					                                    		$stmt = $conn->prepare($sql);
					                                    		$stmt->execute(array($_SESSION['sess_usercomp_id']));

					                                    		echo $stmt->fetchColumn();
				                                    		}else{
                                                                $sql="SELECT DISTINCT u.*
                                                                    FROM user_company level1
                                                                    LEFT JOIN user_company level2 ON level2.super_company_id = level1.user_company_id
                                                                    LEFT JOIN user_company level3 ON level3.super_company_id = level2.user_company_id
                                                                    JOIN user_company u ON u.user_company_id IN (level1.user_company_id, level2.user_company_id, level3.user_company_id)
                                                                    WHERE level1.user_company_id = :user_company";
                                                                $stmt = $conn->prepare($sql);
                                                                $stmt->execute(array(':user_company' => $_SESSION['sess_usercomp_id'] ) );
                                                            	
                                                            	echo $stmt->rowCount();
                                                            }
				                                    		

				                                    		
				                                    	}catch(PDOException $e)
														{
															echo $sql . "<br>" . $e->getMessage();
														}
				                                    	?>
				                                    </div>
				                                    <div>Lead Management</div>
				                                </div>
				                            </div>
				                        </div>
				                        <a href="distributor-associate.php">
				                            <div class="panel-footer">
				                                <span class="pull-left">View Details</span>
				                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
				                                <div class="clearfix"></div>
				                            </div>
				                        </a>
				                    </div>
				                </div>
				                <?php 
				                if($_SESSION['sess_userrole']<=4){
				                ?>
				                <div class="col-lg-3 col-md-6">
				                    <div class="panel panel-green">
				                        <div class="panel-heading">
				                            <div class="row">
				                                <div class="col-xs-3">
				                                    <i class="fa fa-envelope fa-5x"></i>
				                                </div>
				                                <div class="col-xs-9 text-right">
				                                    <div class="huge">
				                                    	<?php
                                                        try{
                                                            $sql="SELECT count(*) from leads WHERE user_id=0 && lead_archive_status=0";
                                                            $stmt = $conn->prepare($sql);
                                                            $stmt->execute();

                                                            $x = $stmt->fetchColumn();

                                                            $sql2="SELECT count(*) from distributor_application WHERE enabled = 1";
                                                            $stmt2 = $conn->prepare($sql2);
                                                            $stmt2->execute();

                                                            $y = $stmt2->fetchColumn();

                                                        }catch(PDOException $e)
                                                        {
                                                            echo $sql."<br>".$sql2 . "<br>" . $e->getMessage();
                                                        }
                                                        echo ($x+$y);
                                                        ?>
				                                    </div>
				                                    <div>Web/Email Enquiries</div>
				                                </div>
				                            </div>
				                        </div>
				                        <a href="webmail-inquiry.php">
				                            <div class="panel-footer">
				                                <span class="pull-left">View Details</span>
				                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
				                                <div class="clearfix"></div>
				                            </div>
				                        </a>
				                    </div>
				                </div>
				                <?php } ?>
				                <div class="col-lg-3 col-md-6">
				                    <div class="panel panel-yellow">
				                        <div class="panel-heading">
				                            <div class="row">
				                                <div class="col-xs-3">
				                                    <i class="fa fa-files-o fa-5x"></i>
				                                </div>
				                                <div class="col-xs-9 text-right">
				                                    <div class="huge">
				                                    	<?php
				                                    	try{
				                                    		$sql="SELECT DISTINCT count(*) from document";
				                                    		$stmt = $conn->prepare($sql);
				                                    		$stmt->execute();

				                                    		echo $stmt->fetchColumn();
				                                    	}catch(PDOException $e)
														{
															echo $sql . "<br>" . $e->getMessage();
														}
				                                    	?>
				                                    </div>
				                                    <div>Document Library</div>
				                                </div>
				                            </div>
				                        </div>
				                        <a href="document-library.php">
				                            <div class="panel-footer">
				                                <span class="pull-left">View Details</span>
				                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
				                                <div class="clearfix"></div>
				                            </div>
				                        </a>
				                    </div>
				                </div>
				                <div class="col-lg-3 col-md-6">
				                    <div class="panel panel-red">
				                        <div class="panel-heading">
				                            <div class="row">
				                                <div class="col-xs-3">
				                                    
				                                </div>
				                                <div class="col-xs-9 text-right">
				                                    <div class="huge">
				                                    	<?php
				                                    	try{
				                                    		$sql="SELECT DISTINCT count(*) from icecoldoilcharge";
				                                    		$stmt = $conn->prepare($sql);
				                                    		$stmt->execute();

				                                    		echo $stmt->fetchColumn();
				                                    	}catch(PDOException $e)
														{
															echo $sql . "<br>" . $e->getMessage();
														}
				                                    	?>
				                                    </div>
				                                    <div>Equipment Assessment</div>
				                                </div>
				                            </div>
				                        </div>
				                        <a href="equipment-assessment.php">
				                            <div class="panel-footer">
				                                <span class="pull-left">View Details</span>
				                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
				                                <div class="clearfix"></div>
				                            </div>
				                        </a>
				                    </div>
				                </div>
				            </div>
				            <div class="category-divider"></div>
				            <div class="dbalist"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>

		<div id="copy">&copy; 2015 EcoCOOL World, LLC</div> 	
	</body>
</html>
<?php
}
?>
