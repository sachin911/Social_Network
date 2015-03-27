<?php
session_start();
ob_start();
include "connectdb.php";
?>

<!DOCTYPE html>
<html>
<head><title>Concert</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script language="JavaScript">
function myFunction() {
    var x = document.getElementById("mySelect").value;
    document.getElementById("demo").innerHTML = "You selected: " + x;
}
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
		<li><a href="logout.php" style="float:right" >Log Out</a></li>
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
date_default_timezone_set('America/New_York');
if(isset($_SESSION["user_id"])) {

echo "<BR>Concert details of ".$_GET["var"]." is shown below";
	  if($stmt = $mysqli->prepare("select c.cid,c.description,c.vdate,v.place,v.city,v.addressurl,b.band_name,u.uname 
	  from concert c,fan f, user u,band b,band_concert bc,venue v where v.vid=c.vid and c.cid=bc.cid 
	  and bc.bid=b.bid and c.admin_id=f.fid and f.user_id=u.user_id and c.cid=?;")){
      $stmt->bind_param("i",$_GET["var"]);
      $stmt->execute();
      $stmt->bind_result($cid_id,$desc,$vdate,$place,$city,$address_url,$band_name,$admin_name);
	  $_SESSION["cid"]=$_GET["var"];
      echo "<table>";
	  while($stmt->fetch()) {
	        //echo"hello";
	    echo "<tr> ";
        echo "Concert id is :$cid_id</tr><br><tr>Concert details are :$desc</tr><br><tr>This page is owned by :$admin_name</tr><br><tr>This concert is being held on :$vdate <br> at $place $city.</tr><br><tr>Click <a href='$address_url'>$address_url</a> to find directions</tr>";
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

?>
</form>
</div>
<div id="content">	
<form style='padding_left:30px' method="POST" action ="concert_post.php" class="myform"> 
<table>	
	<u>Post Concert Information</u>-<br>
		<input style='width:60%;height:32px' type='text' name='user_post' class='mytext' ><br>
		<input type='submit' value='submit'><br>
		</table>
</form>


<?php
echo "<form>";
	$today = new DateTime("now");
    $var = $_SESSION["cid"];
 	  if($stmt = $mysqli->prepare("select user_id as admin,description,post,pid,poster,temp.like_count,temp.uname,temp.trust_score from fan f,
(select admin_id,description,post,pid,poster,like_count,uname,trust_score from concert c,
(select p.pid,p.user_id as poster,description as post,like_count,cid,uname,trust_score from posts p,concert_posts cp,user u where p.pid=cp.pid and 
u.user_id=p.user_id
) t where c.cid=t.cid and c.cid=?) temp where temp.admin_id=f.fid ;")){
      $stmt->bind_param("i",$var);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($admin,$concert_desc,$post,$pid,$poster,$like_count,$uname,$trust_score);
      echo "<table>";
      while($stmt->fetch()){
      if($_SESSION['user_id']==$admin) {
      echo '<tr><br></tr><hr style="float:left;width:60%"><br><tr><u>'.$uname.' has posted</u><br/></tr>
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
	  	echo'<tr><a id="like" href="rateArticleConcert?var1='.$pid.'&var2=1" data="24" title="like">Like</a>';
	  		  	
	  	} else {
	  	echo '<tr><a id="unlike" href="rateArticleConcert?var1='.$pid.'&var2=-1" data="24" title="unlike">unLike</a>';
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
	  	echo'<tr><a id="like" href="rateArticleConcert.php?var1='.$pid.'&var2=1" data="24" title="like">Like</a>';
	  		  	
	  	} else {
	  	echo '<tr><a id="unlike" href="rateArticleConcert.php?var1='.$pid.'&var2=-1" data="24" title="unlike">unLike</a>';
		}
		echo'<div style="float:right;width:80%">likes '.$like_count.'</div></tr>';		  	
	  	
 }
 }

//  <input type=\"text\" readonly=\"readonly\" value='$post'style=\"color:#000000;background-color:#ffffff\">
 
      }
      echo "</table>";
      }
      else{
      echo "the concert page doesnt have any post yet";
      }
 echo "</form>";  

echo "</div>";


echo '<div id="aside">';   
 
 //search part
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
 
  ?>    

<?php
if(isset($_SESSION["user_id"])) {
	$today = new DateTime("now");
    $var = $_SESSION["cid"];
	$f_today=$today->format('Y-m-d'); //formated today = '2011-03-09'
	$sql_date=substr($vdate,0,9); //I get substring '2008-10-17'
	if($f_today<$sql_date)
	{//code for participation
		if($stmt = $mysqli->prepare("select status from participation where user_id= ? and cid=?")){
		$stmt->bind_param("si",$_SESSION["user_id"],$var);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($status);		
		if($stmt->fetch()){
			echo "<form>Participation: <select onChange=\"parent.location=options[selectedIndex].value;\">
			<option value=\"participation.php?var=yes\">Going</option><option value=\"participation.php?var=no\"";
			if($status=="no") echo " selected"; 
			echo" >Not Going</option><option value=\"participation.php?var=maybe\"\""; if($status=="maybe") echo " selected"; 
			echo">Maybe</option></select></form>";
				}	
		else {
			echo "<form>Participation: <select onChange=\"parent.location=options[selectedIndex].value;\"><option value=yes\"participation.php?var=\"yes\"\">Going</option>
			<option value=\"participation.php?var=no\">Not Going</option><option value=\"participation.php?var=maybe\">Maybe</option></select></form>";
				}
		}
	if($stmt = $mysqli->prepare("select u.user_id,uname 
	from participation p,user u where u.user_id=p.user_id and p.status='yes' and p.cid=?")){
		$stmt->bind_param("i",$var);
		$stmt->execute();
		$stmt->bind_result($user_id,$uname);
		 echo"<br>Users going to this concert-<br>";     
		      echo '<table>';
      while($stmt->fetch()) {
		echo '<tr> <a href="user.php?var='.$user_id.'">'.$uname.'</a></tr><br>';
      
      }
      echo "</table>";
	}
	else {
	echo "<br>no users going yet<br>";
	}
	}
	else
	{
	//code for vote
			if($stmt = $mysqli->prepare("select rating,review from vote where user_id= ? and cid=?")){
		$stmt->bind_param("si",$_SESSION["user_id"],$var);
		$stmt->execute();
		$stmt->bind_result($rating,$review);		
		if($stmt->fetch()){
			echo "<form action=\"vote.php\" method=\"POST\">Vote and Comment<br>
			<input type=\"radio\" name=\"vote\" value=1 "; if($rating==1) echo "checked"; echo">1
			<input type=\"radio\" name=\"vote\" value=2 "; if($rating==2) echo "checked"; echo">2
			<input type=\"radio\" name=\"vote\" value=3 "; if($rating==3) echo "checked"; echo">3
			<input type=\"radio\" name=\"vote\" value=4 "; if($rating==4) echo "checked"; echo">4
			<input type=\"radio\" name=\"vote\" value=5 "; if($rating==5) echo "checked"; echo">5<br>
			<input  type=\"text\" name=\"review\"  ";if($review) echo "value=\"".$review; echo"\"/><br>
			<input id=\"vote\" type=\"submit\" value=\"Vote Now !\" /></form>";
				}	
		else {
			echo "<form action=\"vote.php\" method=\"POST\">Vote and Comment<br><input type=\"radio\" name=\"vote\" value=1>1
			<input type=\"radio\" name=\"vote\" value=2>2
			<input type=\"radio\" name=\"vote\" value=3>3<input type=\"radio\" name=\"vote\" value=4>4
			<input type=\"radio\" name=\"vote\" value=5>5<br><input  type=\"text\" name=\"review\" /><br>
			<input id=\"vote\" type=\"submit\" value=\"Vote Now !\" /></form>";
				}
		}
	
	}
		
	}
else {
 echo "You do not have the authority to view this page !!";
 }

?>
</div>




	</div>
 </div>
  </div>
  			<div id="footer">
				By- Sachin & Suhas
			</div>	
</body>
</html>

