<?php 
 require 'config.php';

 session_start();

 $username = "";
 $password = "";
 
 if(isset($_POST['email'])){
  $username = $_POST['email'];
 }
 if (isset($_POST['password'])) {
  //$password = $_POST['user_password'];
	$password = md5($_POST['password']); 
 }
 

 $q = 'SELECT * FROM users JOIN user_company ON user_company.user_company_id=users.user_company_id WHERE user_email=:username AND user_password=:password';

 $query = $conn->prepare($q);

 $query->execute(array(':username' => $username, ':password' => $password));


 if($query->rowCount() == 0){
  header('Location: index.php?err=1');
 }else{

  $row = $query->fetch(PDO::FETCH_ASSOC);

  session_regenerate_id();
  $_SESSION['sess_user_id'] = $row['user_id'];
  $_SESSION['sess_user_name'] = $row['user_fname']." ".$row['user_lname'];
  $_SESSION['sess_useremail'] = $row['user_email'];
  $_SESSION['sess_userrole'] = $row['user_role_id'];
  $_SESSION['sess_usercomp_id'] = $row['user_company_id'];
  $_SESSION['sess_dbatype']=$row['dbatype_id'];

  $_SESSION['sess_pswd_cnfrm']=$_POST['password'];
  try{

     $que = $conn->prepare("SELECT * FROM logged_in_session WHERE user_id=:userid");
     $que->execute(array(':userid' => $row['user_id']));
     $result = $que->fetch(PDO::FETCH_ASSOC);
     $_SESSION['last_login']=$result['logged_in_time'];
    if($que->rowCount()==0){
        $sql="INSERT INTO logged_in_session (user_id, logged_in_time)
              VALUES (:user_id, now())";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(':user_id' => $row['user_id']));
    }else{
        $sql="UPDATE logged_in_session SET logged_in_time = now(), logged_out_time='0000-00-00 00:00:00' WHERE user_id=:user_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(':user_id' => $row['user_id']));  
    }
  }catch(PDOException $excep) {
    echo "Error: " . $excep->getMessage();
  }
 
  
  $datetime1 = new DateTime( date("Y-m-d h:i:s") );
  $datetime2 = new DateTime( $row["password_change_date"] );
  $interval = $datetime1->diff($datetime2);
  $datediff = $interval->format('%a');

  if($row['user_created_date']==$row['password_change_date']){
    $_SESSION['passwordneedstobechanged']=999;
    unset($_SESSION['sess_useremail']);   
  }elseif($datediff >= 180){
    $_SESSION['passwordneedstobechanged']=999;
    unset($_SESSION['sess_useremail']);   
  }else{
    $_SESSION['passwordneedstobechanged']=0; 
  }
    header('Location: home.php');
 }
?>