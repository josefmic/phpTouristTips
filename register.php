<?php
session_start();
//Create a csrf token
$token = bin2hex(random_bytes(32));
$_SESSION["csrf_token"] = $token;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <meta charset = "utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="./scripts/validation.js"></script>
    <link rel="stylesheet" type="text/css" href="./styles/register.css">
</head>
<body>
    <form method="post" action="./scripts/register_script.php" class="registration-input">
        <div class="register-page">
            <label class="register-label">*Name:
            <?php
                //If the form submit was invalid, fill in the value
                if (isset($_SESSION["name-value"])) {
                    echo "<input id = 'name' name = 'name' class = 'register-form input' type = 'text'  placeholder = 'Name' required value=".htmlspecialchars($_SESSION["name-value"]).">";
                }
                else {
                    echo "<input id = 'name' name = 'name' class = 'register-form input' type = 'text'  placeholder = 'Name' required value=''>";
                }
                ?>
            <?php
                //Print out the name error
                if (isset($_SESSION["name_error"])) {
                    echo "<p class='php-errors'>".$_SESSION["name_error"]."</p>";
                }
            ?>
            </label>
            <p class="error name_error"></p>

            <label class="register-label">*Username:
            <?php
                //If the form submit was invalid, fill in the value
                if (isset($_SESSION["username-value"])) {
                    echo "<input id = 'username' name = 'username' class = 'register-form input' type = 'text'  placeholder = 'Username' required value=".htmlspecialchars($_SESSION["username-value"]).">";
                }
                else {
                    echo "<input id = 'username' name = 'username' class = 'register-form input' type = 'text'  placeholder = 'Username' required value=''>";
                }
            ?>
            <?php
                //Print username error
                if (isset($_SESSION["username_error"])) {
                    echo "<p class='php-errors'>".$_SESSION["username_error"]."</p>";
                }
            ?>
            </label>
            <p class="error username_error"></p>

            <label class="register-label">*Email:
            <?php
                //If the form submit was invalid, fill in the value
                if (isset($_SESSION["email-value"])) {
                    echo "<input id='email' name = 'email' class = 'register-form input' type = 'email'  placeholder = 'Email' required value=".htmlspecialchars($_SESSION["email-value"]).">";
                }
                else {
                    echo "<input id='email' name = 'email' class = 'register-form input' type = 'email'  placeholder = 'Email' required value=''>";
                }
            ?>
            <?php
                //Print email error
                if (isset($_SESSION["email_error"])) {
                    echo "<p class='php-errors'>".$_SESSION["email_error"]."</p>";
                }
            ?>
            </label>
            <p class="error email_error"></p>
            
            <label class="register-label">*Password: (At least 8 long, one uppercase, one lowercase)
            <input id="password" name = "password" class = "register-form input" type = "password"   placeholder = "Password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
            <?php
                //Print password error
                if (isset($_SESSION["password_error"])) {
                    echo "<p class='php-errors'>".$_SESSION["password_error"]."</p>";
                }
            ?>
            </label>
            <p class="error password_error"></p>

            <label class="register-label">*Date of birth
            <?php
                //If the form submit was invalid, fill in the value
                if (isset($_SESSION["birth-value"])) {
                    echo "<input id='birth' name = 'birth' class = 'birth-input input' type ='date' required value=".htmlspecialchars($_SESSION["birth-value"]).">";
                }
                else {
                    echo "<input id='birth' name = 'birth' class = 'birth-input input' type ='date' required value=''>";
                }
            ?>
            <?php
                //Print birth date error
                if (isset($_SESSION["birth_error"])) {
                    echo "<p class='php-errors'>".$_SESSION["birth_error"]."</p>";
                }
            ?>
            </label>
            <p class="error birth_error"></p>

            <!-- send the csrf token along with the form -->
            <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">

            <input type = "submit" name="submit" class = "register-form submit-button" value = "Register">
            <p class="error register-error"></p>
        </div>
    </form>
    <script>
    //Call a function to check the form using javascript on client side
    register_init();
    </script>
    <?php 
    //Set required session values
    $_SESSION["name_error"] = NULL;
    $_SESSION["username_error"] = NULL;
    $_SESSION["email_error"] = NULL;
    $_SESSION["password_error"] = NULL;
    $_SESSION["birth_error"] = NULL;
    $_SESSION["name-value"] = NULL;
    $_SESSION["username-value"] = NULL;
    $_SESSION["email-value"] = NULL;
    $_SESSION["birth-value"] = NULL;
    ?>
</body>
</html>
