<?php
session_start();
ob_start();
include "connectdb.php";
?>
<!DOCTYPE html>
<html>
<head><title>Band</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script language="JavaScript">
</script>
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
		<?php
if(isset($_SESSION["user_id"])) {
	$_SESSION["bid"]=$_GET["var"];
	$bid=$_SESSION['bid'];
//echo $bid;
	  if($stmt = $mysqli->prepare("select admin_id from band bid=?")){
      $stmt->bind_param("i",$bid);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($admin);
	  if($stmt->fetch()) {  
	  	  if($admin==$_SESSION["user_id"]){
		echo "<li ><a href='genre.php' >Add Genre</a></li>";
		}
		else{
		}
		
	}
	}
}
else {
 echo "You do not have the authority to view this page !!";
 }

?>
		
<li style="float:right"><a href="logout.php" >Log Out</a></li>
		<form style="float:right;padding-top:3px" action="" method="post" autocomplete="on">
			<li><input type="text" name="search">
			<li><input type="submit" value="search"></form>
		<form  id="follow">
<?php
if(isset($_SESSION["user_id"])) {
	$_SESSION["bid"]=$_GET["var"];
	$bid=$_SESSION['bid'];
//echo $bid;
	  if($stmt = $mysqli->prepare("select f.fid from fan f,fan_band fb,user u where f.fid=fb.fid  and f.user_id=u.user_id and fb.bid=? and u.user_id=?")){
      $stmt->bind_param("is",$bid,$_SESSION['user_id']);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($fid);
		$count=0;
	  	while($stmt->fetch()) {
	  	$count++;
	  	}

	  if($count==1){
		echo "<a class=\"button\" href=\"like.php?var=".$_GET["var"]."\">UnLike</a>";
		}
		else echo "<a class=\"button\" href=\"like.php?var=".$_GET["var"]."\">Like</a>";
		
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


<?php
if(isset($_SESSION["user_id"])) {
 echo '<div id="aside">';
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
 
 if($stmt = $mysqli->prepare("select u.user_id,uname from user u,fan f,fan_band fb,band b where u.user_id=f.user_id and f.fid=fb.fid and b.bid=fb.bid and b.bid=?")){
      $stmt->bind_param("i",$_SESSION["bid"]);
      $stmt->execute();
      $stmt->bind_result($user_id,$uname);
      echo "<br>The members of band are-<br>";
      echo '<table>';
      while($stmt->fetch()) {
		echo '<tr> <a href="user.php?var='.$user_id.'">'.$uname.'</a></tr><br>';
      
      }
      echo "</table>";
 }
 else {
 echo "no members yet";
 }
 
 echo"</div>";    
 
echo "<div id='section-navigation'>";
echo "<BR>Band details of ".$_GET["var"]." is shown below -<br>";
	  if($stmt = $mysqli->prepare("select b.band_name,u.uname,b.bdate,b.description from band b,user u where b.admin_id=u.user_id and b.bid=?;")){
      $stmt->bind_param("i",$_GET["var"]);
      $stmt->execute();
      $stmt->bind_result($band_name,$admin,$bdate,$desc);

      echo "<form><table>";
	  while($stmt->fetch()) {
					//echo"hello";
				echo "<tr> ";
				echo "<td>Band is :$band_name</td></tr><tr><td>Details about the band :$desc</td></tr><tr><td> This page is owned by $admin</td></tr><tr><td>This page was created on $bdate</td>";
				echo "</tr>\n";
				
			}    
		
				echo "</table><br>";
				//unset($_SESSION["user_id"]);
				}
				else {
						printf("Errormessage: %s\n", $mysqli->error);
				}
			
		}
		else {
		echo "You do not have the authority to view this page !!";
		}
		
		echo" </form ></div> <div id='content'>";
			echo "<form style='padding_left:30px' method=\"POST\" action =\"band_post.php\" class=\"myform\"> "; 
			echo "<table>";	
			echo"<u>Band Posts </u>-<br>";
			echo "<input style='width:60%;height:32px' type='text' name='user_post' class='mytext' ><br>";
			echo "<input type='submit' value='Post !'>";
			echo "</table>";
			echo "</form><form>";
		if($stmt = $mysqli->prepare("select p.pid,u.user_id,u.uname,b.admin_id,p.description,u.trust_score,p.like_count,b.bid,b.band_name 
			from posts p,band_posts bp,user u,band b where bp.pid=p.pid and p.user_id=u.user_id and bp.bid=b.bid and bp.bid=? order by p.pid desc;")){
			$stmt->bind_param("s",$bid);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($pid,$user_id,$uname,$admin_id,$post,$trust_score,$like_count,$bid,$band_name);
			echo "<table>";
			while($stmt->fetch()){
			if($user_id==$admin_id) {
			echo '<tr><br></tr><hr style="float:left;width:60%"><br><tr><u>'.$band_name.'</u> has posted<br/></tr>
				<tr><div style="height:40px;width:60%;border:1px solid #ccc;overflow-y:auto;word-wrap: break-word;background-color:#ffffff">'.$post.'</div></tr>';
				if($stmt1 = $mysqli->prepare("select * from user_likes where user_id=? and pid=?")){
			$stmt1->bind_param("ss",$_SESSION['user_id'],$pid);
			$stmt1->execute();
			$count=0;
			while($stmt1->fetch()) {
			$count++;
			//echo "count:".$count;
			}
			if($count==0){	
				echo'<tr><a style="float:left" id="like" href="rateArticle.php?var1='.$pid.'&var2=1"  title="like">Like</a>';
						
				} else {
				echo '<tr><a style="float:left" id="unlike" href="rateArticle.php?var1='.$pid.'&var2=-1"  title="unlike">unLike</a>';
				
				}
			echo'<div style="float:right;width:80%">likes '.$like_count.'</div></tr>';				
		}
		}
		else {
			echo '<tr><br></tr><hr style="float:left;width:60%"><br><tr><u>'.$uname.'</u> has posted<br/></tr>
					<tr><div style="height:40px;width:60%;border:1px solid #ccc;overflow-y:auto;word-wrap: break-word;background-color:#ffffff">'.$post.'</div></tr>';
				if($stmt1 = $mysqli->prepare("select * from user_likes where user_id=? and pid=?")){
			$stmt1->bind_param("ss",$_SESSION['user_id'],$pid);
			$stmt1->execute();
			$count=0;
			while($stmt1->fetch()) {
			$count++;
			//echo "count:".$count;
			}
			if($count==0){	
				echo'<tr><div style="float:left"> <a  id="like" href="rateArticle.php?var1='.$pid.'&var2=1" data="24" title="like">Like</a></div>';
						
				} else {
				echo '<tr><div style="float:left"><a id="unlike" href="rateArticle.php?var1='.$pid.'&var2=-1" data="24" title="unlike">unLike</a></div>';
				
				}
			echo'<div style="float:right;width:80%">likes '.$like_count.'</div></tr>';
		}
		}
		
		//  <input type=\"text\" readonly=\"readonly\" value='$post'style=\"color:#000000;background-color:#ffffff\">
		
			}
      echo "</table></form>";
      }
      else{
      echo "the band page doesnt have any post yet";
      }
 echo'</div>';

 
?>

</div></div>

 			<div id="footer">
				By- Sachin & Suhas
			</div>
</body>
</html>
