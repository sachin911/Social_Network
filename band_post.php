<?php
session_start();
ob_start();
include "connectdb.php";
?>
<html>
<?php
if($stmt1 =$mysqli->prepare("insert into posts value (0,?,?,now(),0)")){ 
	      $stmt1->bind_param("ss",$_SESSION['user_id'],$_POST['user_post']);
		  $stmt1->execute();
		  }
		else {
					echo "the user post wasnt posted ";
				}  
if($stmt2 =$mysqli->prepare("insert into band_posts value ((SELECT LAST_INSERT_ID()),?)")){ 
		  		$stmt2->bind_param("i",$_SESSION['bid']);
			    $stmt2->execute();
			    echo "successfully posted";
				header("Location:bands.php?var=".$_SESSION["bid"]);
			    

			    }
			    else {
					    echo "the post wasnt posted";
					    }
?>		
</html>		