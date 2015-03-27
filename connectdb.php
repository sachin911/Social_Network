<?php
$mysqli = new mysqli("localhost", "root", "sachin91", "concert_test");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

?>
