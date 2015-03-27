<?php
session_start();
ob_start();
include "connectdb.php";
?>
<html>
<?php
// echo $_SESSION['cid'];
// echo $_POST['user_post'];

		$stmt5 =$mysqli->prepare("select * from posts where user_id=?");
		$stmt5->bind_param("s",$_SESSION['user_id']);
		  $stmt5->execute();
		  $count1=0;
		  while($stmt5->fetch()) {
		  $count1++;
		  }
		 if($count1>0) { 
		 
		  $stmt3 =$mysqli->prepare("select * from user where user_id=? and trust_score>=2;");
		  $stmt3->bind_param("s",$_SESSION['user_id']);
		  $stmt3->execute();
		  $count=0;
		  while($stmt3->fetch()) {
		  $count++;
		  }
		 if($count==1) {
		 $stmt4 =$mysqli->prepare("update user set trust_score=(select avg(like_count) from posts where user_id=?) where user_id=?");
		$stmt4->bind_param("ss",$_SESSION['user_id'],$_SESSION['user_id']);
		  $stmt4->execute();

		if($stmt1 =$mysqli->prepare("insert into posts value (0,?,?,now(),0)")){ 
	      $stmt1->bind_param("ss",$_SESSION['user_id'],$_POST['user_post']);
		  $stmt1->execute();
		  }
		else {
					echo "the user post wasnt posted ";
				}  
if($stmt2 =$mysqli->prepare("insert into concert_posts value ((SELECT LAST_INSERT_ID()),?)")){ 
		  		$stmt2->bind_param("s",$_SESSION['cid']);
			    $stmt2->execute();
			    echo "successfully posted";
				header("Location:concert.php?var=".$_SESSION["cid"]);
			    

			    }
			    else {
					    echo "the post wasnt posted";
					    }
		}
	else {
	echo "you are not eligible to post content. Improve your trust score";
	header("refresh: 3 ;concert.php?var=".$_SESSION["cid"]);
	}	
}
else {
if($stmt1 =$mysqli->prepare("insert into posts value (0,?,?,now(),0)")){ 
	      $stmt1->bind_param("ss",$_SESSION['user_id'],$_POST['user_post']);
		  $stmt1->execute();
		  }
		else {
					echo "the user post wasnt posted ";
				}  
if($stmt2 =$mysqli->prepare("insert into concert_posts value ((SELECT LAST_INSERT_ID()),?)")){ 
		  		$stmt2->bind_param("s",$_SESSION['cid']);
			    $stmt2->execute();
			    echo "successfully posted";
				header("Location:concert.php?var=".$_SESSION["cid"]);
			    

			    }
			    else {
					    echo "the post wasnt posted";
					    }
}	
			
				
?>	