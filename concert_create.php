<?php
session_start();
ob_start();
include "connectdb.php";
echo'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
if(!isset($_SESSION["user_id"])) {
	echo "You do not have authority to view this page";
	header("refresh:5;Location:login.html");
}
?>
<html>

    <head> 

	<link rel="stylesheet" type="text/css" href="style.css">
    <title> Create Concert</title>
     <script src="//code.jquery.com/jquery-1.10.2.js"></script>
     <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
	 	  <script language="JavaScript"> 
function CheckForm_onclick()
{
   var myForm = document.reg;

   
  if (myForm.bname.value == "" || myForm.desc.value == "")
   {
      document.write("missing value!");
      if (myForm.bname.value == "")
      {
         myForm.bname.focus();
      }
      else
      {
         myForm.desc.focus();
      }
   }
   else
   {
      document.write(myForm.txtName.value);
   }
}
function txtAge_onblur()
{
   var txtAge = document.form1.txtAge;
   if (isNaN(txtAge.value) == true)
   {
      alert("Age must be a number.");
      txtAge.focus();
      txtAge.select();
   }
}
}</script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
    <script>
  $(function() {
    $( "#datepicker" ).datepicker();
  });
  </script>
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
		<li><a href="logout.php" style="float:right">Log Out</a></li>
		<form style="float:right;padding-top:3px" action="" method="post" autocomplete="on">
			<li><input type="text" name="search">
			<li><input type="submit" value="search"></form>
	</ul>
</div> 

<div id="content-container1">
<div id="content-container2">
<div id="aside">
<form>
<?php 

if(isset($_POST["search"]))	
{	
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
echo"</form></div>	";	

echo"<div id='pos_mid' >";
	echo"<form action='' method='POST' ><center><h2>Concert Details :</h2><br>
						  Concert Name<br> <input type='text' id=bname name='cname' required/><br /> 
     	  				  Concert Description <br> <input type='text' id='desc' name='desc' required />
						  <p> Date of concert <br><input type='text' name='vdate' id='datepicker' required/> </P>";						  
					      
				
								if ($stmt = $mysqli->prepare("select b.bid,b.band_name,f.fid from band b,fan_band bf,fan f,user u where b.bid=bf.bid and bf.fid=f.fid and f.user_id=u.user_id and u.user_id=?")) {
									$stmt->bind_param("s",$_SESSION["user_id"]);
									$stmt->execute();
									$stmt->store_result();
									$stmt->bind_result($bid,$bname,$fid);
									echo "Choose Band : <br><select id='band' name='band'>";
									while ($stmt->fetch()) {
											echo"<option value=$bid>$bname</option>";
										}
										echo "</select><br>";
								}
								else { echo "Error in the query";}
								if ($stmt2 = $mysqli->prepare("select vid,place,city from venue;")) {
									$stmt2->execute();
									$stmt2->store_result();
									$stmt2->bind_result($vid,$vplace,$vcity);
									//if there is a match set session variables and send user to homepage
									echo "<br>Choose Venue :<br> <select id='venue' name='venue'>";
									while ($stmt2->fetch()) {
											echo"<option value=\"".$vid; echo"\">$vplace,$vcity </option>";
										}
										echo "</select><br>";
								}
								echo "<br><input name='concert' id=\"concert\" type=\"submit\" value=\"Create Concert !\" /></form>";
								
								if(isset($_POST["concert"])) {
								//ALSO ADD TO BAND_CONCERT table;
									$vdate=$_POST["vdate"];
									$date=date("Y-m-d",strtotime($vdate));
		
									if ($stmt = $mysqli->prepare("insert into concert(vid,admin_id,description,vdate,created_Date) values(?,?,?,?,now());")) {
										
											$stmt->bind_param("iiss",$vid,$fid,$_POST["desc"],$date);
											$stmt->execute();
											$stmt->store_result();
											if ($stmt = $mysqli->prepare("select max(cid) from concert;")) {
											$stmt->bind_param("isss",$bid,$_SESSION["user_id"],$desc,$vdate);
											$stmt->execute();
											$stmt->store_result();
											$stmt->bind_result($cid);
											if($stmt->fetch()) 	
											{					
											if($stmt = $mysqli->prepare("insert into BAND_CONCERT(bid,cid) values(?,?);")) {
											$stmt->bind_param("ii",$bid,$cid);
											$stmt->execute();
											$stmt->store_result();
											$stmt->bind_result($cid);
												header("Location:concert.php?var=$cid");
											}
											else echo "fetch error";
											}
											
									//		$stmt->bind_result($gname,$subcat);
											
											}
											else printf("Errormessage inside: %s\n", $mysqli->error);
									}										
						//			else echo "query error";
									else	printf("Errormessage: %s\n", $mysqli->error);
								}
?>


</form> 


</div>
</div>
<div id="footer">
	By- Sachin & Suhas
</div>
</div> 				  
    </body>
</html>