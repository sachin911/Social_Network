<?php
session_start();
ob_start();

ini_set('display_errors',1); 
error_reporting(E_ALL); 
date_default_timezone_set('America/New_York');
include "connectdb.php";
$user_id=$_POST["user_id"];
$uname=$_POST["uname"];
$password=$_POST["password"];
$dob=$_POST["dob"];
$date=date("Y-m-d",strtotime($dob));
$address=$_POST["address"];
$phnum=$_POST["phnum"];
$email_addr=$_POST["email_addr"];
 $errors = array();
//echo $date;
 if (strlen($user_id) == 0)
    array_push($errors, "Please enter an user id");
	
 if (strlen($uname) == 0)
    array_push($errors, "Please enter your name ");
  
  if (strlen($address) == 0) 
    array_push($errors, "Please specify your address ");
    
  if (!filter_var($email_addr, FILTER_VALIDATE_EMAIL))
    array_push($errors, "Please specify a valid email address");
    
    
 if (strlen($phnum) == 0) 
    array_push($errors, "Please enter a valid phone number ");

	if (strlen($password) < 5)
    array_push($errors, "Please enter a password. Passwords must contain at least 5 characters.");
    
  // If no errors were found, proceed with storing the user input

   $output = '';
  foreach($errors as $val) {
    $output .= "<p>$val</p>";
  }
    if (count($errors) != 0) {
	echo $output; 
	   header("refresh: 3; register.html");
  }
    if (count($errors) == 0) {
    array_push($errors, "No errors were found. Thanks!");	
  
  
	  $stmt = $mysqli->prepare(" insert into user(user_id,uname,password,dob,address,phnum,email_addr,join_date,trust_score,logout_time) values(?,?,?,?,?,?,?,now(),6,now())");
      $stmt->bind_param("sssssss",$user_id,$uname,md5($password),$date,$address,$phnum,$email_addr);
      if ($stmt->execute()){
      echo "registration successfull";
      header("Location:login.html");
      	}
		else {
		        printf("Errormessage: %s\n", $mysqli->error);
		}

}


?>
