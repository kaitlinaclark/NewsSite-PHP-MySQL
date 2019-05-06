<?php
session_start();
session_unset(); 
session_destroy(); 
if(isset($_SESSION["username"])){
    echo "Session not destroyed. Username is ".$_SESSION["username"];
}
header ("Location: http://ec2-18-191-196-37.us-east-2.compute.amazonaws.com/~kaitlinaclark/news/index.php");
?>

