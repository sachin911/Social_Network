<?php
session_start();
ob_start();
include "connectdb.php";
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<script language="JavaScript">
function change() 
{
    var elem = document.getElementById("follow_button");
    if (elem.value=="Follow") elem.value = "Unfollow";
    else elem.value = "Follow";
}

</script>
<title>User</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div id="container">
	<div id="header">
		<h1>
			CONCERT_ZONE
		</h1>
	</div>
<div id="navigation">
	<ul>
		<li><a href="profile.php" >Home</a></li>	
		<li><a href="logout.php" style="float:right">Log Out</a></li>
		<form style="float:right;padding-top:3px" action="" method="post" autocomplete="on">
			<li><input type="text" name="search">
			<li><input type="submit" value="search">
			</form>
		<form  id="follow">
<?php
if(isset($_SESSION["user_id"])) {
	if($_SESSION["user_id"]!=$_GET["var"]) {
	  if($stmt = $mysqli->prepare("select follower,following from follows where follower= ? and following=?")){
      $stmt->bind_param("ss",$_SESSION["user_id"],$_GET["var"]);
      $stmt->execute();
	  $stmt->store_result();
      $stmt->bind_result($follow, $follower);
	  if($stmt->fetch()){
		echo "<a class=\"button\" href=\"follow.php?var=".$_GET["var"]."\">UnFollow</a>";
		}
		else echo "<a class=\"button\" href=\"follow.php?var=".$_GET["var"]."\">Follow</a>";
	}
	}
	else {
	}
	}
else {
 echo "You do not have the authority to view this page !!";
 }

?>
</form>
	</ul>
</div> 
	
<div id="content-container1">
<div id="content-container2">
<div id="aside">


<form >
<?php

if(isset($_POST["search"]))	
{	
echo "Search Results--------------------";
require_once( 'SolrPhpClient/Apache/Solr/Service.php' );
require('/usr/local/Cellar/solr/solr-4.3.1/concert/solr/vendor/solarium/solarium/examples/init.php');
  
  $solr = new Apache_Solr_Service( 'localhost', '8983', '/solr' );
  
  if ( ! $solr->ping() ) {
    echo 'Solr service not responding.';
    exit;
  }
  $offset = 0;
  $limit = 10;
  
  //echo $_POST['search'];
  
  $queries = array(
    'uname:'.$_POST['search'].'*',
    'description:'.$_POST['search'].'*',
    'band_name:'.$_POST['search'].'*'
    
  );
foreach ( $queries as $query ) {
    $response = $solr->search( $query, $offset, $limit );
    
    if ( $response->getHttpStatus() == 200 ) { 
      // print_r( $response->getRawResponse() );
      
      if ( $response->response->numFound > 0 ) {
        //echo "$query <br />";

        foreach ( $response->response->docs as $doc ) { 
         // echo "user_name :$doc->uname <br/>"." desc: $doc->description <br />"."band_name:$doc->band_name";
         	 echo "<table>";	
             echo "<tr><td><a href=\"user.php?var=$doc->id\"   name=\"click\">$doc->uname</a></td><tr>";
             echo "<tr><td><a href=\"concert.php?var=$doc->cid\" name=\"click\">$doc->description</a></td><tr>";
             echo "<tr><td><a href=\"bands.php?var=$doc->bid\" name=\"click\">$doc->band_name</a></td><tr>";
             echo "</table>";
        }
        
        echo '<br />';
      }
    }
    else {
      echo $response->getHttpStatusMessage();
    }
  }


}	
unset($_POST["search"]);
echo"</form>";
if($stmt = $mysqli->prepare("select play_id,playlist_name from playlist where user_id=?")){
      $stmt->bind_param("s",$_GET['var']);
      $stmt->execute();
      $stmt->bind_result($play_id,$play_name);
      echo "<br>List of Playlist(s) by this user-<br>";
      echo "<table>";
      while($stmt->fetch()) {
		echo '<tr> <a href="playlist.php?var='.$_GET['var'].'&var1='.$play_name.'">'.$play_name.'</a></tr><br>';
      }
	      	  echo "</table>";
}
else {
echo"no playlist yet";
}


echo "</div>";	
echo"<div id='section-navigation'>";

if(isset($_SESSION["user_id"])) {
$_SESSION["user"]=$_GET["var"];
$var=$_SESSION["user"];
echo "<BR>User details of ".$var." is shown below";
	  if($stmt = $mysqli->prepare("select user_id,uname,email_addr from user where user_id = ?")){
      $stmt->bind_param("s",$var);
      $stmt->execute();
      $stmt->bind_result($user_id, $uname, $email_addr);
		$stmt->store_result();
	  if($stmt->fetch()) {
	  echo "<BR>User details of ".$_GET["var"]." are -<br>";
	   echo "<table>";
	    echo "<tr> ";
        echo "<td>$user_id</td></tr><tr><td>$uname</td></tr><tr><td>$email_addr</td>";
	    echo "</tr>\n";
		
	  }    

		echo "</table><br></div>";
		//unset($_SESSION["user_id"]);
		}
		else {
		        printf("Errormessage: %s\n", $mysqli->error);
		}
      echo "<div id='content'>";	
echo "</form><form style='padding_left:30px' method='POST' action ='user_post.php' ><u>User Posts</u> - <br>
	<input  style='width:60%;height:32px' type='text' name='user_post'  ><br><input type='submit' value='Post !'></form>";
	
 	  if($stmt = $mysqli->prepare("select u.uname as poster,temp.pid,temp.user_id,description,like_count from user u,
(select u.uname,p.pid,p.user_id,description,up.user_id posted_on,like_count from user u,posts p,user_posts up 
where up.user_id=? and p.pid=up.pid and u.user_id=?) temp where temp.user_id=u.user_id order by pid desc;")){
      $stmt->bind_param("ss",$var,$var);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($uname,$pid,$user_id,$description,$like_count);     

      while($stmt->fetch()){
      //if($user_id==$_SESSION['user_id']) {
      echo "<form><table>";
      echo '<tr><br></tr><hr style="float:left;width:60%"><br><tr><u>'.$uname.'</u> has posted</tr>';
      echo   "<tr><div style=\"height:40px;width:60%;border:1px solid #ccc;overflow-y:auto;word-wrap: break-word;background-color:#ffffff\">$description</div>";

	  	  	if($stmt1 = $mysqli->prepare("select * from user_likes where user_id=? and pid=?")){
      $stmt1->bind_param("ss",$_SESSION['user_id'],$pid);
      $stmt1->store_result();
      $stmt1->execute();
      $count=0;
      while($stmt1->fetch()) {
      $count++;
      }
	  if($count==0){	
	  	echo'<tr><a style="float:left" id="like" href="rateArticleUser.php?var1='.$pid.'&var2=1"  title="like">Like</a>';
	  		  	
	  	} else {
	  	echo '<tr><a style="float:left" id="unlike" href="rateArticleUser.php?var1='.$pid.'&var2=-1"  title="unlike">unLike</a>';
	  	
				}
			echo'<div style="float:right;width:80%">likes '.$like_count.'</div></tr>';						
		}
		else {
						printf("Errormessage: %s\n", $mysqli->error);
		}
       echo "</table></form>";
      }
  

      }
      else{
      echo "the user page doesnt have any post yet";
      }	
 }
 else {
 echo "You do not have the authority to view this page !!";
 }
 ?>


</div></div>
 			<div id="footer">
				By- Sachin & Suhas
			</div>
</body>
</html>
