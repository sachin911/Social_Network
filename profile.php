<?php
session_start();
ob_start();
ini_set('display_errors',1); 
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile</title>
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
		<li ><a href="profile.php" >Home</a></li>
		<li ><a href="edit_profile.php" >Edit Profile</a></li>
		<li ><a href="create_playlist.php" >Create Playlist</a></li>
		<li ><a href="band_create.html" >Create Band</a></li>
		<li ><a href="concert_create.php" >Create Concert</a></li>	
		<li style="float:right"><a href="logout.php" >Log Out</a></li>
		<form style="float:right;padding-top:3px" action="" method="post" autocomplete="on">
			<li><input type="text" name="search">
			<li><input type="submit" value="search"></form>

		
	</ul>
</div> 
	
<div id="content-container1">
<div id="content-container2">
<div id="section-navigation">
<form >
<?php 

include "connectdb.php";
if(isset($_SESSION["user_id"])) {
//$user_id=$_SESSION['user_id'];
echo '<tr>Welcome '.$_SESSION["uname"].'<tr><br><tr> Your Profile details are-</tr>';
//echo "<br>Your profile details is shown below<br>";
//echo "<pre>".var_dump($_SESSION)."</pre>";
	  if($stmt = $mysqli->prepare("select user_id,uname,email_addr from user where user_id = ?")){
      $stmt->bind_param("s",$_SESSION["user_id"]);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($user_id, $uname, $email_addr);
      echo "<table>";
	  if($stmt->fetch()) {
	        //echo"hello";
        echo "<br><tr><a href='user.php?var=$user_id'>User id: $user_id</a></tr><br><tr>Name: $uname</tr><br><tr>Email: $email_addr";
	    echo "</tr>\n";
		
	  }      
		echo "</table>";
	    //$stmt->free_result();
		}
		else {
		        printf("Errormessage: %s\n", $mysqli->error);
		}
 }
 else {
	header("Refresh 0:error.php");
 }
   if(isset($_SESSION["user_id"])) {

          if($stmt1 = $mysqli->prepare("select c.cid,c.description from concert c where c.cid in (select cid from participation where user_id=? and status='yes' and part_time BETWEEN DATE_SUB(now(), INTERVAL 3 MONTH) AND now() union
			select cid from (select p.cid,count(p.cid) max_concert from concert c,(
			select f.follower user_id from follows f,user u where u.user_id=f.following and u.user_id=?) temp,participation p
			where p.status='yes' and p.user_id=temp.user_id and p.cid=c.cid and p.cid not in (select cid from participation p 
			where p.user_id =? and p.status='yes') group by p.cid) as temp having count(cid)>=max(max_concert) union
			select bc.cid from fan f,fan_genre fg,genre g,band_genre bg,band_concert bc where f.user_id=? and f.fid=fg.fid and 
			fg.gen_id=g.gen_id and g.gen_id=bg.gen_id and bg.bid=bc.bid)")){
      $stmt1->bind_param("ssss",$_SESSION["user_id"],$_SESSION["user_id"],$_SESSION["user_id"],$_SESSION["user_id"]); 
      $stmt1->execute();
      $stmt1->store_result();
      $stmt1->bind_result($cid,$desc);
      echo"<br><u>Recommended Concerts for you are</u> - <table>";
      while($stmt1->fetch()) {
       echo"<br><tr>";
        echo "<a href='concert.php?var=$cid'>$desc</a>";
	   echo"</tr>";
      }
      echo"</table>";
        }
        else {
        printf("Errormessage: %s\n", $mysqli->error);
        }

  
  }
  //if not then display login form
  else {
	header("Location:error.php");
  }

?>
</form>

<!-- 
<form action="search.php" method="post" autocomplete="on">
Search: <input type="text" name="search"><br>
  <input type="submit" value="search">
</form>
 -->
</div>

<div id="aside">
<form action ="user.php" method="POST">
<?php
	if(isset($_SESSION["user_id"])) {
		//$user_id=$_SESSION['user_id'];
		
		if($stmt2 = $mysqli->prepare("select follower,uname from follows,user where follower=user_id and following = ?")){
      $stmt2->bind_param("s",$_SESSION["user_id"]);
      $stmt2->execute();
      $stmt2->store_result();
      $stmt2->bind_result($follower,$follower_name);
      echo "<u>Followers </u>-<br><table>";
	  while($stmt2->fetch()) {
	    echo "<tr><br>";
        echo "<a href=\"user.php?var=$follower\"   name=\"click\">$follower_name</a>";
	    echo "</tr>\n";
		
	  } 

		echo "</table><br>";
	    //end of followers	
		
		}
		else {// error with followers
		        printf("Errormessage: %s\n", $mysqli->error);
		}
		}
		else {
		        printf("Errormessage: %s\n", $mysqli->error);
		}
	

if($stmt2 = $mysqli->prepare("select following,uname,playlist_name 
		from follows,user,playlist p where following=user.user_id and p.user_id=follows.following and follower =?")){
      $stmt2->bind_param("s",$_SESSION["user_id"]);
      $stmt2->execute();
      $stmt2->bind_result($following,$following_name,$playlist_name);
      echo 'Friends Playlist :<table align="rightmost">';
	  while($stmt2->fetch()) {
	        echo "<br>";
	    echo "<tr> ";
        echo '<a href="playlist.php?var='.$following.'&var1='.$playlist_name.'"name="click">'.$following_name.'\'s playlist :'.$playlist_name.'</a>';
	    echo "</tr>\n";
		
	  } 

		echo "</table><br>";
	    //end of followers	
		
		}
		else {// error with followers
		        printf("Errormessage: %s\n", $mysqli->error);
		}
		
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
		
		

echo"</form></div>";
echo"<div id='content' ><form >Latest Posts in your favorite bands page -<br>";

		if($stmt = $mysqli->prepare("select uname,t2.pid pid,t2.poster,t2.description,like_count from user u,
		(select p.pid pid,p.user_id poster,p.description description,like_count from band_posts bp,posts p,
		(select t.fid fid,bid,uname,t.viewer viewer,logout_time from fan_band fb,
		(select fid,uname,u.user_id viewer,logout_time from fan f ,user u where f.user_id=u.user_id and u.user_id=?) t 
		where t.fid=fb.fid) temp
		where temp.bid=bp.bid and p.pid=bp.pid and p.pdate > logout_time) as t2
		where t2.poster=u.user_id;")){
			$stmt->bind_param("s",$_SESSION['user_id']);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($uname,$pid,$user_id,$post,$like_count);
			echo '<table align="center">';
			while($stmt->fetch()){
			echo '<tr><br></tr><hr style="float:left;width:60%"><br><tr><u>'.$uname.'</u> has posted<br/></tr>
				<tr><div style="height:40px;width:60%;border:1px solid #ccc;overflow-y:auto;word-wrap: break-word;background-color:#ffffff">'.$post.'</div></tr>
				<tr><div style="float:right;width:60%">likes '.$like_count.'</div></tr>';
		}
			echo "</table>";
			}
			else{
			echo "the band page doesnt have any post yet";
			} 
	
echo"</form></div>	";	
?>


<div id="footer">
	By- Sachin & Suhas
</div>


</div>
</div>
</div>

</body>
</html>
