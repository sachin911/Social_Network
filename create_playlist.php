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
	</ul>
</div> 
	
<div id="content-container1">
<div id="content-container2">
<div align="center">

<form  action="concert_playlist.php" method="POST" ">

	Enter the new playlist name: <br /><br />
Playlist name: <span/> <input  type="text" name="playlist_name" required/><br />
     <input id="nav" type="submit" value="Create"  />
     </form>


	
</body>
</html>
</div>
</div>
</div>	
<div id="footer">
	By- Sachin & Suhas
</div>
</div>
</body>
</html>