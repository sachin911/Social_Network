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
		<li><a href="profile.php" >Home</a></li>	
		<li style="float:right"><a href="logout.php" >Log Out</a></li>
		<form style="float:right;padding-top:3px" action="" method="post" autocomplete="on">
			<li><input type="text" name="search">
			<li><input type="submit" value="search"></form>
	</ul>
</div> 
	
<div id="content-container1">
<div id="content-container2">
<!-- <div id="content" > -->
<div id="pos_mid" >
<!-- <form align="pos_center"> -->
<?php 
echo "<center>";

include "connectdb.php";
if(isset($_SESSION["user_id"])) {
 if($stmt = $mysqli->prepare("select distinct c.cid,description,playlist_name from concert c,
(select cid,playlist_name from playlist p,playlist_concerts pc where p.play_id=pc.play_id and p.user_id=? and p.playlist_name=?) t
where c.cid=t.cid")){
      $stmt->bind_param("ss",$_GET['var'],$_GET['var1']);
      $stmt->execute();
      $stmt->bind_result($cid, $description,$playlist_name);
	  echo '<h2>'.$_GET['var1'].' content </h2>';	
      //echo "<table>"; 
      while($stmt->fetch()) {
          echo "<br>";
	    echo "<tr> ";
        echo "<td><a href=\"concert.php?var=$cid\" name=\"click\">$description</a></td>";
	    echo "</tr>\n";
      }
     // echo "</table><br>";
}

else {
		        printf("Errormessage: %s\n", $mysqli->error);
		}
		}
		else {
		        printf("Errormessage: %s\n", $mysqli->error);
		}

// <!-- </form> -->


echo '</div>';
// echo '</div>';
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
echo '</div>';
echo '</div>';
echo '</div>';
?>
<div id="footer">
	By- Sachin & Suhas
</div>
</div>
</body>
</html>	
