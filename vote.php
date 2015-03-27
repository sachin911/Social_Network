<?php
session_start();
ob_start();
include "connectdb.php";
?>
<html>
<body>

<?php

if(isset($_SESSION["user_id"])) {

	if($stmt = $mysqli->prepare("select rating,review from vote where user_id= ? and cid=?")){
		$stmt->bind_param("si",$_SESSION["user_id"],$_SESSION["cid"]);
		$stmt->execute();
		$stmt->bind_result($rating,$review);		
	  $stmt->store_result();
	  if($stmt->fetch()){
	  if($stmt2 = $mysqli->prepare("delete from vote where user_id= ? and cid=?;"))
	  {
      $stmt2->bind_param("si",$_SESSION["user_id"],$_SESSION["cid"]);
      $stmt2->execute();
	  $stmt2->store_result();
	  }
	  else echo"error  ".$mysqli->error;
		}
	
	  $stmt2 = $mysqli->prepare("Insert into vote(user_id,cid,rating,review,vdate) values(?,?,?,?,now())");
      $stmt2->bind_param("siss",$_SESSION["user_id"],$_SESSION["cid"],$_POST["vote"],$_POST["review"]);
      $stmt2->execute();
	  $stmt2->store_result();
	  
		
		
	header("Location:concert.php?var=".$_SESSION["cid"]);
	}
	
}
else{
	echo" You do not have permission to view this page !!";
}

?>	 

 </body>
</html>