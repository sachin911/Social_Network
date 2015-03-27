<?php
session_start();
ob_start();
include "connectdb.php";
?>
<html>
<body>
<?php


if(isset($_SESSION["user_id"])) {

	//echo $_GET["var"];
	  if($stmt = $mysqli->prepare("select f.fid from fan f,fan_band fb,user u where f.fid=fb.fid  
	  and f.user_id=u.user_id and fb.bid=? and u.user_id=?")){
      $stmt->bind_param("is",$_GET["var"],$_SESSION['user_id']);
      $stmt->execute();
      $stmt->bind_result($fid);
	  	$stmt->store_result();
	  	$count=0;
	  	while($stmt->fetch()) {
	  	$count++;
	  	}
	  if($count==1){
	  if($stmt2 = $mysqli->prepare("delete from fan_band where fid=? and bid=?;"))
	  {
      $stmt2->bind_param("ii",$fid,$_SESSION['bid']);
      $stmt2->execute();
	  $stmt2->store_result();

	  }
	  else echo"error  ".$mysqli->error;
		//echo "you are already a fan!";
		}
		else{
			  if($stmt = $mysqli->prepare("select f.fid from fan f where f.user_id=?")){
				$stmt->bind_param("s",$_SESSION["user_id"]);
				$stmt->execute();
				$stmt->bind_result($fid);
				$stmt->store_result();
				if(!$stmt->fetch()){
							$stmt2 = $mysqli->prepare("Insert into fan(user_id,fdate) values(?,now())");
							$stmt2->bind_param("s",$_SESSION["user_id"]);
							$stmt2->execute();
							$stmt2->store_result();
							 if($stmt = $mysqli->prepare("select f.fid from fan f where f.user_id=?")){
								$stmt->bind_param("s",$_SESSION["user_id"]);
								$stmt->execute();
								$stmt->bind_result($fid);
							}
							else echo"error  ".$mysqli->error;
							
					}
				}
				else echo"error  ".$mysqli->error;
				
			if($stmt3 = $mysqli->prepare("Insert into fan_band(fid,bid) values(?,?)"))
			{
			$stmt3->bind_param("ii",$fid,$_GET["var"]);
			$stmt3->execute();
			$stmt3->store_result();
			}
			else echo"error  ".$mysqli->error;
					
		}
		header("Location:bands.php?var=".$_GET["var"]);
}		
else echo"error  ".$mysqli->error;
}
else{
	echo" You do not have permission to view this page !!";
}

?>	 

 </body>
</html>