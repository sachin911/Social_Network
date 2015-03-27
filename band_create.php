<?php
session_start();
ob_start();
?>
<html>
<body>
<?php
ini_set('display_errors',1); 
error_reporting(E_ALL); 
date_default_timezone_set('America/New_York');
include "connectdb.php";
$bname=$_POST["bname"];
$desc=$_POST["desc"];
 $errors = array();
echo $bname." ".$desc." ".$_SESSION["user_id"];
  if(isset($_SESSION["user_id"])) {
	  if($stmt = $mysqli->prepare("insert into band(band_name,description,bdate,admin_id) values(?,?,now(),?);")){	  
      $stmt->bind_param("sss",$bname,$desc,$_SESSION["user_id"]);
//	  $stmt->store_result();
      if (!$stmt->execute()){ 
		ECHO "Error with data entered"; header("refresh 5;band_create.html");
	  }
	  else {
	  	  if($stmt2 = $mysqli->prepare("select max(bid) from band;")){	  
				      $stmt2->execute();
					  $stmt2->bind_result($bid);
					  if($stmt2->fetch()) {
					  	$_SESSION["bid"]=$bid;
					  	header("Location:genre.php");
					  	}
		}
		else    printf("Errormessage: %s\n", $mysqli->error);
		}
	  }
	  else echo "Query error"; header("refresh 5;band_create.html");
	  }
	  else
	  {
	  echo "You Do not have authority to view this page !!";
	  }
	  
?>
</body>
</html>