<?php
session_start();
ob_start();
ini_set('display_errors',1); 
error_reporting(E_ALL);
include "connectdb.php";
?>

<!DOCTYPE html>
<html>
<head>
<title>Genre</title>
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
			<li><input type="submit" value="search"></li></form>		
	</ul>
</div> 
	
<div id="content-container1">
<div id="content-container2">
<form >
<?php


echo'<div id="aside">';
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

echo '</div></form>';  
  
  
  
  
if(isset($_SESSION["user_id"])) {

echo "<div id='pos_mid'>";
 echo "<center>";
    //check if entry exists in database
    if ($stmt = $mysqli->prepare("select g.gen_name,g.subcat from genre g,band_genre bg where bg.gen_id=g.gen_id and bg.bid=?;")) {
      $stmt->bind_param("i", $_SESSION["bid"]);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($gname,$subcat);
	    //if there is a match set session variables and send user to homepage
		echo "Genre list for band ".$_SESSION["bid"].": <br><table>";
        while ($stmt->fetch()) {
			echo"<tr> $subcat</tr><br>";
		}
		echo "</table>";
	}
        else {
        printf("Errormessage: %s\n", $mysqli->error);
        }
	echo "<br> Add more genre here -<br>";
		if ($stmt = $mysqli->prepare("select g.gen_id,g.gen_name,g.subcat from genre g;")) { 
    //  $stmt->bind_param("i", $_GET["var"]);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($gid,$gname,$subcat);
	    //if there is a match set session variables and send user to homepage
		echo "<form action='' method='POST'> <select id='genre' name='genre'>";
        while ($stmt->fetch()) {
			echo"<option value=".$gid." >".$subcat."</option><br>";
		}
		echo "<br><input id='genre' type='submit' value='Add Genre !' /></select></form>";
		
		if(isset($_POST["genre"])) {

			if ($stmt = $mysqli->prepare("insert into band_genre(bid,gen_id) values(?,?)")) {
					$stmt->bind_param("ii",$_SESSION["bid"],$_POST["genre"]);
					$stmt->execute();
					$stmt->store_result();
					header("Location:genre.php?var=".$_SESSION["bid"]);
			//		$stmt->bind_result($gname,$subcat);
			}
			
			
		}
		else {
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
echo '</div>';  
?>







</div>

</div>
<div id="footer">
	By- Sachin & Suhas
</div>
</div>

</body>
</html>