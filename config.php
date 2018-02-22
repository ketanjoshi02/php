<?php

$host = "localhost:3306";

$username = "**********";

$password = "**************";

$database = "*************";



// Create connection
 try {
    $conn = new PDO("mysql:host={$host};dbname={$database}", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully"; 
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
   

?>