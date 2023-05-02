<?php
session_start();
//Set all user values to the logged out values
$_SESSION["isadmin"] = False;
$_SESSION["name"] = NULL;
$_SESSION["username"] = NULL;
$_SESSION["author"] = False;
$_SESSION["ismypost"] = False;
//Redirect the user
header("Location: ../index.php");
?>