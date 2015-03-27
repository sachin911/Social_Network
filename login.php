<?php
session_start();
ob_start();
include "connectdb.php";
?>
<!DOCTYPE html>
<html>
<title>Login</title>

<?php


//if the user is already logged in, redirect them back to homepage
if(isset($_SESSION["username"])) {
  echo "You are already logged in. \n";
  echo "You will be redirected in 3 seconds or click <a href=\"profile.php\">here</a>.\n";
  header("profile.php");
}
else {
  //if the user have entered both entries in the form, check if they exist in the database
  if(isset($_POST["user_id"]) && isset($_POST["password"])) {

    //check if entry exists in database
    if ($stmt = $mysqli->prepare("select user_id,uname,password from user where user_id = ? and password = ?")) {
      $stmt->bind_param("ss", $_POST["user_id"],md5($_POST["password"]));
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($user_id, $uname, $password);
	    //if there is a match set session variables and send user to homepage
        if ($stmt->fetch()) {
		  $_SESSION["user_id"] = $user_id;
		  $_SESSION["uname"] = $uname;
		  $_SESSION["password"] = $password;
		  $_SESSION["REMOTE_ADDR"] = $_SERVER["REMOTE_ADDR"]; //store clients IP address to help prevent session hijack
          echo "Login successful. </br>";
          echo "hello ".$_SESSION["uname"]."</br>";
        header("Location:profile.php");
          echo " The recommended concert list are";
          //$stmt->free_result();
          if($stmt1 = $mysqli->prepare("select description from concert c where c.cid in (select cid from participation where user_id=? and status='yes' and part_time BETWEEN DATE_SUB(now(), INTERVAL 3 MONTH) AND now() union
			select cid from (select p.cid,count(p.cid) max_concert from concert c,(
			select f.follower user_id from follows f,user u where u.user_id=f.following and u.user_id=?) temp,participation p
			where p.status='yes' and p.user_id=temp.user_id and p.cid=c.cid and p.cid not in (select cid from participation p 
			where p.user_id =? and p.status='yes') group by p.cid) as temp having count(cid)>=max(max_concert) union
			select bc.cid from fan f,fan_genre fg,genre g,band_genre bg,band_concert bc where f.user_id=? and f.fid=fg.fid and 
			fg.gen_id=g.gen_id and g.gen_id=bg.gen_id and bg.bid=bc.bid)")){
      $stmt1->bind_param("ssss",$user_id,$user_id,$user_id,$user_id); 
      $stmt1->execute();
      $stmt1->bind_result($cid);
      echo"<table>";
      while($stmt1->fetch()) {
       echo"<tr>";
        echo "<td>$cid</td>";
	   echo"</tr>";
      }
      echo"</table>";
        }
        else {
        printf("Errormessage: %s\n", $mysqli->error);
        }
        }
		//if no match then tell them to try again
		else {
		  sleep(1); //pause a bit to help prevent brute force attacks
		  echo "Your username or password is incorrect</br> click <a href=\"login.html\">here</a> to try again.";
		  Header("Refresh :3;login.html");
		}
      $stmt->close();
	  $mysqli->close();
    }  
  }
  //if not then display login form
  else {

  }
}

	
	
 
?>
</html>