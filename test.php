
<html>
<head>
    <title>LIGHTBOX EXAMPLE</title>
    <style>
    .black_overlay{
        display: none;
        position: absolute;
        top: 0%;
        left: 0%;
        width: 100%;
        height: 100%;
        background-color: black;
        z-index:1001;
        -moz-opacity: 0.8;
        opacity:.80;
        filter: alpha(opacity=80);
    }
    .white_content {
        display: none;
        position: absolute;
        top: 25%;
        left: 25%;
        width: 50%;
        height: 50%;
        padding: 16px;
        border: 16px solid orange;
        background-color: white;
        z-index:1002;
        overflow: auto;
    }
</style>
</head>
<body>
<form action="javascript:void(0)"  method="POST" autocomplete="on">
Search: <input type="text" name="search"><br>
<!--  <p>This is the main content. To display a lightbox click <a href = "javascript:void(0)" onclick = "document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block'">here</a></p>
  -->
  <input onclick = "document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block'" type="submit" value="search">
</form>
<?php
if(isset($_POST("search")))
{
	header("document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block'");
}	
?>
    <div id="light" class="white_content">
   solr
   header("refresh: 0 ;concert.php?var=".$_SESSION["cid"]);
<a href = "javascript:void(0)" onclick = "document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'">Close</a>
	<br><a href="profile.php">Go to my profile</a>


<?php
//session_start();
//ob_start();
//echo $_POST['search'];
require_once( 'SolrPhpClient/Apache/Solr/Service.php' );
require('/usr/local/Cellar/solr/solr-4.3.1/concert/solr/vendor/solarium/solarium/examples/init.php');
  
  echo "solr search";
  // 
  // Try to connect to the named server, port, and url
  // 
  $solr = new Apache_Solr_Service( 'localhost', '8983', '/solr' );
  
  if ( ! $solr->ping() ) {
    echo 'Solr service not responding.';
    exit;
  }
  

  $offset = 0;
  $limit = 10;
  
  //echo $_POST['search'];
  
  $queries = array(
    'uname:'.$_POST['search'],
    'description:'.$_POST['search'],
    'band_name:'.$_POST['search']
    
 );
  foreach ( $queries as $query ) {
    $response = $solr->search( $query, $offset, $limit );
    
    if ( $response->getHttpStatus() == 200 ) { 
      // print_r( $response->getRawResponse() );
      
      if ( $response->response->numFound > 0 ) {
        //echo "$query <br />";

        foreach ( $response->response->docs as $doc ) { 
         // echo "user_name :$doc->uname <br/>"." desc: $doc->description <br />"."band_name:$doc->band_name";
             echo "<tr><a href=\"user.php?var=$doc->user_id\"   name=\"click\">$doc->uname</a><br/>";
             echo "<tr><a href=\"concert.php?var=$doc->cid\" name=\"click\">$doc->description</a><br>";
             echo "<tr><a href=\"bands.php?var=$doc->bid\" name=\"click\">$doc->band_name</a><br>";
        }
        
        echo '<br />';
      }
    }
    else {
      echo $response->getHttpStatusMessage();
    }
  }
?>
	
	</div>
    <div id="fade" class="black_overlay"></div>
</body>
</html>