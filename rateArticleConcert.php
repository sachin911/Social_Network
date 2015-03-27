<?php
session_start();
ob_start();
include "connectdb.php"; 
echo $_GET['var1']; 
echo $_GET['var2'];

$pid= $_GET['var1'];
$like=$_GET['var2'];
$user_id=$_SESSION['user_id'];
echo $user_id;
if($like==1) {
if($stmt =$mysqli->prepare("INSERT INTO `user_likes`(`user_id`, `pid`, `like_count`) VALUES (?,?,?)")){
		$stmt->bind_param("sii",$_SESSION['user_id'],$pid,$like);
		  $stmt->execute();
		  if($stmt1 =$mysqli->prepare("update posts set like_count=(select sum(like_count) from user_likes where pid=?) where pid=?")){
		  $stmt1->bind_param("ii",$pid,$pid);
		  $stmt1->execute();
		  echo "success";
		  header("refresh: 0 ;concert.php?var=".$_SESSION["cid"]);
		  }
		  else {
		  echo "no like user";
		  }
	}
 else{
   		  echo "no like"; 
 }
}
else {
if($stmt =$mysqli->prepare("delete from user_likes where user_id=? and pid=?")){
		$stmt->bind_param("si",$_SESSION['user_id'],$pid);
		  $stmt->execute();
		  if($stmt1 =$mysqli->prepare("update posts set like_count=(select sum(like_count) from user_likes where pid=?) where pid=?")){
		  $stmt1->bind_param("ii",$pid,$pid);
		  $stmt1->execute();
		  echo "success";
		  header("refresh: 0 ;concert.php?var=".$_SESSION["cid"]);
		  }
		  else {
		  echo "no like user";
		  }
	}
 else{
   		  echo "no like"; 
 }
}		  
?>