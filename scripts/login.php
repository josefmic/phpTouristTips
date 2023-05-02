<?php
session_start();

//Save the GET value
if (isset($_GET["city"])) {
    $active_city = $_GET["city"];
    }

//Check the csrf token, if it's malicious, redirect the user
if (isset($_POST["csrf_token"]) and isset($_SESSION["csrf_token"])) {
    if ($_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
        if ($_SESSION["is_city"] == False) {
            header("Location: ../index.php");
        }
        else {
            header("Location: ../city.php?city=".$active_city);
        }
    }
}
//Set required session values
$_SESSION["incorrect_pass"] = NULL;
$_SESSION["doesnt_exist"] = NULL;
if (isset($_POST['submit'])) {
    //Save the existing users
    $users_get = json_decode(file_get_contents("users.json"));
    //If there aren't any users, you can't log in
    if (empty($users_get)) {
        $doesnt_exist = "User doesn't exist";
        $_SESSION["doesnt_exist"] = $doesnt_exist;
        if ($_SESSION["is_city"] == False) {
            header("Location: ../index.php");
        }
        else {
            header("Location: ../city.php?city=".$active_city);
        }
    }
    //Else if there are some users
    else {
        $does_exist = False;
        //For every user, if the username matches, save his info into variables 
        foreach ($users_get as $item) {
            if ($item->username == $_POST["username"]){
                $does_exist = True;
                $name = $item->name;
                $username = $item->username;
                $pass = $item->password;
                $isadmin = $item->isadmin;
                //Using a php built-in function, verify if the password is correct, if yes, save user's values into sessions
                $verify_pass = password_verify($_POST["password"], $pass);
                if ($verify_pass == True) {
                    $_SESSION["name"] = $name;
                    $_SESSION["username"] = $username;
                    $_SESSION["doesnt_exist"] = NULL;
                    $_SESSION["isadmin"] = $isadmin;
                    $_SESSION["author"] = true;
                    //Redirect the user after successful login
                    if ($_SESSION["is_city"] == False) {
                        header("Location: ../logged_index.php");
                        exit();
                    }
                    else {
                        header("Location: ../logged_city.php?city=".$active_city);
                        exit();
                    }
                }
                //Else generate incorrect password error and redirect
                else {
                    $incorrect_pass = "Incorrect password";
                    $_SESSION["incorrect_pass"] = $incorrect_pass;
                    $_SESSION["doesnt_exist"] = NULL;
                    $_SESSION["login-value"] = $_POST["username"];
                    if ($_SESSION["is_city"] == False) {
                        header("Location: ../index.php");
                        exit();
                    }
                    else {
                        header("Location: ../city.php?city=".$active_city);
                        exit();
                    }
                }
            }
        }
    }
    //If the cycle didn't find any user with that username, generate a username error and redirect
    if ($does_exist == False) {
        $doesnt_exist = "User doesn't exist";
        $_SESSION["doesnt_exist"] = $doesnt_exist;
        $_SESSION["login-value"] = $_POST["username"];
        if ($_SESSION["is_city"] == False) {
            header("Location: ../index.php");
            exit();
        }
        else {
            header("Location: ../city.php?city=".$active_city);
            exit();
        }
    }
}
//If a user has accessed this page without the "POST" request, redirect him
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