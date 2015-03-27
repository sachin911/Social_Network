<?php
session_start();
ob_start();
include "connectdb.php";
?>
<!DOCTYPE html>
<html>
<title>Concert Playlist</title>
<body>
<?php


//if the user is already logged in, redirect them back to homepage
if(isset($_SESSION["user_id"])) {


	$playlist_name=$_POST['playlist_name'];
	//echo 'pplay'.$playlist_name;
    //enter the playlist name into playlist table
    if ($stmt = $mysqli->prepare("insert into playlist(user_id,playlist_name,like_count,pdate) values(?,?,0,now())")) {
      $stmt->bind_param("ss",$_SESSION['user_id'],$_POST['playlist_name']);
      $stmt->execute();

		echo "The playlist ".$_POST['playlist_name']."is created. <br></table>";
		header("Location:add_concert.php");
	}
        else {
        printf("Errormessage: %s\n", $mysqli->error);
        }
        
    }
    else {
     echo "Please Login to add playlist";
    }    
	?>


</body>
</html>