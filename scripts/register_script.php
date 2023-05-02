<?php
session_start();

//Check for a valid csrf token, in case of a malicious one, redirect the user
if (isset($_POST["csrf_token"]) and isset($_SESSION["csrf_token"])) {
    if ($_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
        header("Location: ../register.php");
    }
}
if(isset($_POST['submit'])) {
    $_SESSION["username_error"] = null;
    //Save the post data into an array
    $new_user = array(
        "name" => $_POST["name"],
        "username" => $_POST["username"],
        "email" => $_POST["email"],
        //Hash the password
        "password" => password_hash($_POST["password"], PASSWORD_DEFAULT),
        "birth" => $_POST["birth"],
        "isadmin" => false
    );

    //Set required session values to fill the form, if the form is going to be invalid
    $_SESSION["name-value"] = $_POST["name"];
    $_SESSION["username-value"] = $_POST["username"];
    $_SESSION["email-value"] = $_POST["email"];
    $_SESSION["birth-value"] = $_POST["birth"];
   
    /**
     * Function to validate the name 
     * check_name
     *
     * @return void
     */
    function check_name() {
        $name = $_POST["name"];
        $name_array = str_split($name);
        $special_chars = str_split("!#$%&'()*+,-./:;<=>?@[\]^_`{|}~");
        //Check for special characters
        foreach ($name_array as $item1) {
            foreach ($special_chars as $item2) {
                if ($item1 == $item2) {
                    $name_error = "Your name cannot use special characters!";
                    $_SESSION["name_error"] = $name_error;
                    header("Location: ../register.php");
                    exit();
                }
            }
        }
        //Check if name is shorter than 5
        if (strlen($name) < 5) {
            $name_error = "Name must be at least 6 characters long!";
            $_SESSION["name_error"] = $name_error;
            header("Location: ../register.php");
            exit();
        }
        //Check if the name is longer than 30
        elseif (strlen($name) > 30) {
            $name_error = "Your name is too long!";
            $_SESSION["name_error"] = $name_error;
            header("Location: ../register.php");
            exit();
        } 
    }
    
    /**
     * Function to check the username
     * check_username
     *
     * @return void
     */
    function check_username() {
        $username = $_POST["username"];
        $username_array = str_split($username);
        $special_chars = str_split("!#$%&'()*+,-./:;<=>?@[\]^_`{|}~ ");
        //Check to see if username has special characters or a space
        foreach ($username_array as $item1) {
            foreach ($special_chars as $item2) {
                if ($item1 == $item2) {
                    $username_error = "Username cannot have special characters and spaces!";
                    $_SESSION["username_error"] = $username_error;
                    header("Location: ../register.php");
                    exit();
                }
            }
        }
        $check_existing = json_decode(file_get_contents('users.json'));
        //Check if the username doesn't already exist
        foreach ($check_existing as $item) {
            if ($item->username == $_POST['username']) {
                $username_error = "Username already in use!";
                $_SESSION["username_error"] = $username_error;
                header("Location: ../register.php");
                exit();
            }
        }
        //Check if the username isnt shorter than 5 characters
        if (strlen($username) < 5) {
            $username_error = "Username must be at least 6 characters long!";
            $_SESSION["username_error"] = $username_error;
            header("Location: ../register.php");
            exit();
        }
        //Check if username has more than 15 characters
        elseif (strlen($username) > 15) {
            $username_error = "Username is too long!";
            $_SESSION["username_error"] = $username_error;
            header("Location: ../register.php");
            exit();
        }
    }
   
    /**
     * Function to validate a password 
     * check_password
     *
     * @return void
     */
    function check_password() {
        $password = $_POST["password"];
        $password_array = str_split($password);
        //Check for special characters, which the password cannot have
        $special_chars = str_split("!#$%&'()*+,-./:;<=>?@[\]^_`{|}~ ");
        foreach ($password_array as $item1) {
            foreach ($special_chars as $item2) {
                if ($item1 == $item2) {
                    $password_error = "Password cannot use special characters!";
                    $_SESSION["password_error"] = $password_error;
                    header("Location: ../register.php");
                    exit();
                }
            }
        }
        //Check if the password isn't shorter than 8
        if (strlen($password) < 8) {
            $password_error = "Password must be at least 8 characters long!";
            $_SESSION["password_error"] = $password_error;
            header("Location: ../register.php");
            exit();
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $password_error = "Password must contain an uppercase letter!";
            $_SESSION["password_error"] = $password_error;
            header("Location: ../register.php");
            exit();
        }

        if (!preg_match('/[a-z]/', $password)) {
            $password_error = "Password must contain a lowercase letter!";
            $_SESSION["password_error"] = $password_error;
            header("Location: ../register.php");
            exit();
        }
        if (!preg_match('/[0-9]/', $password)) {
            $password_error = "Password must contain a number!";
            $_SESSION["password_error"] = $password_error;
            header("Location: ../register.php");
            exit();
        }
    }
    /**
     * Function to check the birth date    
     * check_birth
     *
     * @return void
     */
    function check_birth() {
        $birth = $_POST["birth"];
        $birth_array = explode("-", $birth);
        //If the birth date is before 1900, the user is too old
        if ($birth_array[0] < 1900) {
            $birth_error = "Enter a valid date of birth!";
            $_SESSION["birth_error"] = $birth_error;
            header("Location: ../register.php");
            exit();
        }
        //Check if the user isn't too young
        elseif ($birth_array[0] > 2010) {
            $birth_error = "You're not old enough to register!";
            $_SESSION["birth_error"] = $birth_error;
            header("Location: ../register.php");
            exit();
        }
    }
    //Call the validation functions
    check_name();
    check_username();
    check_password();
    check_birth();
    //Get the existing user array and append the new user
    $old_users = json_decode(file_get_contents("users.json"));
    array_push($old_users, $new_user);
    $save_data = $old_users;

    //Put the data into the file, if there was an error, echo it
    if (!file_put_contents("users.json", json_encode($save_data, JSON_PRETTY_PRINT), LOCK_EX)) {
        $error = "Error registering, please try again";
        echo $error;
        exit();
    }

    //Else set the user's values as if they were logging in and redirect them
    else {
        $_SESSION["name"] = $_POST["name"];
        $_SESSION["username"] = $_POST["username"];
        $_SESSION["isadmin"] = $new_user["isadmin"];
        echo "<script type='text/javascript'>document.location.href='{'../logged_index.php'}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . '../logged_index.php' . '">';
    }
}
//If the user tried to access this page without the "POST" request, redirect them
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

