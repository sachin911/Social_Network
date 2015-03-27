<?php
session_start();
ob_start();
?>
<html>
<body>
<?php
include "connectdb.php";
if($stmt1 =$mysqli->prepare("update user set logout_time=now() where user_id=?")){
		  $stmt1->bind_param("s",$_SESSION["user_id"]);
		  $stmt1->execute();
session_destroy();
}
else echo " Error logging out";


header("Location:login.html");
ob_end_flush()
?>
</body>
</html>