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
<title>Profile Edit</title>
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
		<!-- <li><a href="edit_profile.php" >Edit Profile</a></li> -->
	</ul>
</div> 
	
<div id="content-container1">
<div id="content-container2">
<div id="pos_mid">
<center>
<form action='save_profile.php' method='post'>
<?php
if ($stmt = $mysqli->prepare("select user_id,uname,address,phnum,email_addr from user where user_id = ?")) {
      $stmt->bind_param("s", $_SESSION["user_id"]);
      $stmt->execute();
     // $stmt->store_result();
      $stmt->bind_result($user_id, $uname,$address,$phnum,$email_addr);
	    //if there is a match set session variables and send user to homepage
        if ($stmt->fetch()) {
echo '
<table>	
<tr>
<td><label>Username:</label></td>
<td><label>'.$user_id.'</label></td>
</tr>
<tr>
<td><label>Name:</label></td>
<td><input type="text" name="name" maxlength="1000" value="'.$uname.'" /></td>
</tr>									
<tr>
<td><label>Location:</label></td>
<td><input type="text" name="address" maxlength="1000" value="'.$address.'" /></td>
</tr>
<tr>
<td><label>Phone Number:</label></td>
<td><input type="text" name="name" maxlength="1000" value="'.$phnum.'" /></td>
</tr>	
<tr>
<td><label>Email Address:</label></td>
<td><input type="text" name="name" maxlength="1000" value="'.$email_addr.'" /></td>
</tr>							
<tr>
<td>&nbsp;</td>
<td><input type="submit" value="Submit" /></td>
</tr>						
</table>';
}else {
echo "sorry it cant be updated";

}
}
else {
echo "no data";
}
?>
</form>
</div>
</div>
</div>
<div id="footer">
	By- Sachin & Suhas
</div>

</body>
</html>