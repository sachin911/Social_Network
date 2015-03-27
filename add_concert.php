<?php
session_start();
ob_start();
ini_set('display_errors',1); 
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html>
<head>
<title>Concert Playlist</title>
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
		<li style="float:right"><a href="logout.php" >Log Out</a></li>
		<form style="float:right;padding-top:3px" action="" method="post" autocomplete="on">
			<li><input type="text" name="search">
			<li><input type="submit" value="search"></form>

		
	</ul>
</div> 
	
<div id="content-container1">
<div id="content-container2">
<div id="aside">
<?php 

include "connectdb.php";

if(isset($_SESSION["user_id"]))
{

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
echo "</div>";

echo "<div id='section-navigation'>";
if(isset($_SESSION["user_id"])) {
 if ($stmt3 = $mysqli->prepare("select  distinct c.cid,description from playlist p,playlist_concerts pc,concert c 
 where p.play_id=pc.play_id  and c.cid=pc.cid and pc.play_id=(select max(play_id) from playlist)")) {
      //$stmt3->bind_param("i",$_GET["var"]);
      $stmt3->execute();
      $stmt3->store_result();
      $stmt3->bind_result($cid,$description);
	    //if there is a match set session variables and send user to homepage
		echo "Concert List for Playlist : <br></table>";
        while ($stmt3->fetch()) {
			echo '<tr><a href="concert.php?var='.$cid.'" name="click">'.$description.'</a><br>';
		}
		echo "</table>";
	}
        else {
        printf("Errormessage: %s\n", $mysqli->error);
        }


echo "<br> Add concerts to your playlist:";
		if ($stmt1 = $mysqli->prepare("select cid,description from concert")) { //ADD QUERY TO REMOVE THOSE ALREADY PRESENT
      $stmt1->execute();
      $stmt1->store_result();
      $stmt1->bind_result($cid,$description);
	    //if there is a match set session variables and send user to homepage
		echo "</br><form action='' method='POST'>
		Concert: <select id='concert' name='concert'>";
        while ($stmt1->fetch()) {
			echo'<option value="'.$cid.'\">'.$description.'</option>';
		}
		echo "<input id=\"concert\" type=\"submit\" value=\"Add Concert !\" /></select></form>";
		 echo '<tr><a href="profile.php"name=\"click\">Done</a><br/>';
		if(isset($_POST["concert"])) {
	$stmt2 = $mysqli->prepare("select max(play_id) from playlist");
		$stmt2->execute();
		$stmt2->bind_result($play_id);
		$stmt2->store_result();
			while($stmt2->fetch()) {
			if ($stmt = $mysqli->prepare("insert into playlist_concerts (play_id,cid) values(?,?)")) {
					$stmt->bind_param("ii",$play_id,$_POST["concert"]);
					$stmt->execute();
					$stmt->store_result();
					header("Location:add_concert.php?var=".$play_id."&var1=".$cid);
			}
			
		else {
    			echo "concert wasnt added to the playlist";
    			printf("Errormessage: %s\n", $mysqli->error);
        }
        }
        }
	}
        else {
        printf("Errormessage: %s\n", $mysqli->error);
        }
}
//if no match then tell them to try again
else {
  sleep(1); //pause a bit to help prevent brute force attacks
  echo "You do not have authority to view this page !";
}

echo'</div>';
  }

?>

</div>


</div>
<div id="footer">
	By- Sachin & Suhas
</div>
</div>
</body>
</html> 
  
