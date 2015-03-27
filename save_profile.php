<?php
session_start();
ob_start();
include "connectdb.php";

//$user_id = $_POST['user_id'];
$name = $_POST['name'];
$address = $_POST['address'];
//$email = $_POST['email'];

if($stmt = $mysqli->prepare("update user set address=?,uname=? where user_id=?")){
 $stmt->bind_param("sss",$address,$name,$_SESSION["user_id"]);
      $stmt->execute();
      header("refresh: 0 ;user.php?var=".$_SESSION["user_id"]);
      }
      else {
      echo "could not be updated";
      }
 ?>     
      
     
