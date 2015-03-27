<?php
session_start();
ob_start();
?>
<html>
<body>

<?php

include "connectdb.php";

if(isset($_SESSION["user_id"])) {

	
	  if($stmt = $mysqli->prepare("select status from participation where user_id=? and cid=?")){
      $stmt->bind_param("si",$_SESSION["user_id"],$_SESSION["cid"]);
      $stmt->execute();
      $stmt->bind_result($status);
	  $stmt->store_result();
	  if($stmt->fetch()){
	  if($stmt2 = $mysqli->prepare("delete from participation where user_id= ? and cid=?;"))
	  {
      $stmt2->bind_param("si",$_SESSION["user_id"],$_SESSION["cid"]);
      $stmt2->execute();
	  $stmt2->store_result();

	  }
	  else echo"error  ".$mysqli->error;
		}
	
	  $stmt2 = $mysqli->prepare("Insert into participation(user_id,cid,status,part_time) values(?,?,?,now())");
      $stmt2->bind_param("sis",$_SESSION["user_id"],$_SESSION["cid"],$_GET["var"]);
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