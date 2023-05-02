<?php
if (isset($_POST["colorblind"])) {
    //Set cookie time to 30 days
    $expire_time = time() + (86400 * 30);
    //If there is a cookie and the button was pressed, change the value of the cookie
    if (isset($_COOKIE["colorblind"])) {
        if ($_COOKIE["colorblind"] == "true") {
            setcookie("colorblind", "false", $expire_time, "/");
        }
        else {
            setcookie("colorblind", "true", $expire_time, "/");
        }
    }
    else {
        setcookie("colorblind", "true", $expire_time, "/");
    }
    //Redirect back to index / logged_index
    if (isset($_SESSION["username"])) {
        if (($_SESSION["username"]) != null) {
            header("Location: ../logged_index.php");
        }
        else {
            header("Location: ../index.php");
        }
    }
    else {
        header("Location: ../index.php");
    }
}
//If a person tries to access this page without a "post" method, redirect him
else {
    if (isset($_SESSION["username"])) {
        if (($_SESSION["username"]) != null) {
            header("Location: ../logged_index.php");
        }
        else {
            header("Location: ../index.php");
        }
    }
    else {
        header("Location: ../index.php");
    }
}

?>