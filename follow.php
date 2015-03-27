<?php
session_start();
ob_start();
include "connectdb.php";
?>

<html>
<body>

<?php

if(isset($_SESSION["user_id"])) {

	
	  if($stmt = $mysqli->prepare("select follower,following from follows where follower= ? and following=?")){
      $stmt->bind_param("ss",$_SESSION["user_id"],$_GET["var"]);
      $stmt->execute();
      $stmt->bind_result($follow, $follower);
	  	$stmt->store_result();
	  if($stmt->fetch()){
	  if($stmt2 = $mysqli->prepare("delete from follows where follower= ? and following=?;"))
	  {
      $stmt2->bind_param("ss",$_SESSION["user_id"],$_GET["var"]);
      $stmt2->execute();
	  $stmt2->store_result();

	  }
	  else echo"error  ".$mysqli->error;
		}
		else{
		$stmt2 = $mysqli->prepare("Insert into follows(follower,following,fdate) values(?,?,now())");
      $stmt2->bind_param("ss",$_SESSION["user_id"],$_GET["var"]);
      $stmt2->execute();
	  $stmt2->store_result();
		
		}
		header("Location:user.php?var=".$_GET["var"]);
	}
	
}
else{
	echo" You do not have permission to view this page !!";
}

?>	 

 </body>
</html>