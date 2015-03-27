<?php
session_start();
ob_start();
include "connectdb.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>

    <head> 
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
    <body >
	
<div id="container">
	<div id="header">
		<h1>
			CONCERT_ZONE
		</h1>
	</div>
<div id="navigation">
	<ul>
		<li ><a href="login.html" >Login</a></li>
		<li style="color:white"><CENTER>ERROR !! </li>
	</ul>
</div>
<center>
<div id="content-container1">
<div id="content-container2">
		<div id="content">
		<div id="pos_center">		
<?php
if(!isset($_SESSION["user_id"]))
{
	echo "You do not have rights to view these pages. Please Log In ";
	header("Refresh 3;Login.html");
}
else{



if(isset($_GET["var"]))
{
	$a=$_GET["var"];
	echo "<br><br>Error in ".$a."<br>";

if(isset($_GET["var1"]))
{
	$b=$_GET["var1"];
	echo "Error in ".$b."<br>";
if(isset($_GET["var2"]))
{
$c=$_GET["var2"];
	echo "Error  ".$c;
}
}
}
}
?>
</div>
		</div>
</div>
</div>
			<div id="footer">
				By- Sachin & Suhas
			</div>
</div>
</body>
</html>
