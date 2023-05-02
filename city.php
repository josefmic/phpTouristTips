<?php
session_start();
//Checking if a valid GET parameter exists and storing it to a variable
if (isset($_GET["city"]) and !empty($_GET["city"])) {
    $valid_city = False;
    $arraykeys = json_decode(file_get_contents("./city_tips/city_tips.json"), true);
    $arraykeys = (array_keys($arraykeys));
    foreach ($arraykeys as $item) {
        if ($_GET["city"] == $item) {
            $valid_city = True;
        }
    }
    if (!$valid_city) {
        if (isset($_SESSION["username"])) {
            if (($_SESSION["username"]) != null) {
                header("Location: ./logged_index.php");
            }
            else {
                header("Location: ./index.php");
            }
        }
        else {
            header("Location: ./index.php");
        }
    }
    $active_city = $_GET["city"];
}
//If not, user is sent to the index page
else {
    if (isset($_SESSION["username"])) {
        if (($_SESSION["username"]) != null) {
            header("Location: ./logged_index.php");
        }
        else {
            header("Location: ./index.php");
        }
    }
    else {
        header("Location: ./index.php");
    }
}
//Get parameter saved in a session
$_SESSION["city"] = $active_city;

//Creating a csrf token
$token = bin2hex(random_bytes(32));
$_SESSION["csrf_token"] = $token;

//Checking if a user tried to access this site while being logged in
//If he is, redirect him to logged_city.php
if (isset($_SESSION["username"])) {
    if ($_SESSION["username"] != NULL) {
        header("Location: ./logged_city.php?city=".$active_city);
    }
}

//Include php file for printing posts
include "./scripts/printing.php";

//Set session variables to required values
$_SESSION["is_city"] = True;
$_SESSION["isadmin"] = False;
$_SESSION["author"] = False;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tourist tips</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="./scripts/ajax.js"></script>
    <script src="./scripts/validation.js"></script>
    <?php
    //Check to see if there is a cookie "colorblind", if yes and its value is "true", use the colorblind css file
    if (isset($_COOKIE["colorblind"])) {
        if ($_COOKIE["colorblind"] == "true") {
            echo "<link rel='stylesheet' href='./styles/index_styles_colorblind.css' type='text/css'>";
        }
        else {
            echo "<link rel='stylesheet' href='./styles/index_styles.css' type='text/css'>";
        }
    }
    else {
        echo "<link rel='stylesheet' href='./styles/index_styles.css' type='text/css'>";
    }
    ?>
</head>
<body>
    <header class="sidebar">
        <h2><a href="./index.php" class="title">Tourist tips.</a></h2>
        <div class="menu_list">
            <?php 
            $adress = "./scripts/login.php?city=".$active_city;
            echo "<form method='post' class='menu_list' action='".$adress."'>";
            ?>
                <!-- Login form - username -->
                <?php
                    //making sure the username field stays filled after submit
                    if (isset($_SESSION["login-value"])) {
                        echo "<input id='username' type='text' name='username' class='login_form username_form' placeholder='Username' aria-label='Username' required value=".htmlspecialchars($_SESSION["login-value"]).">";
                    }
                    else {
                        echo "<input id='username' type='text' name='username' class='login_form username_form' placeholder='Username' aria-label='Username' required value=''>";
                    }
                ?>
                <?php
                    //printing an error message
                    if (isset($_SESSION["doesnt_exist"])) {
                        echo "<p class='php-errors'>".$_SESSION["doesnt_exist"]."</p>";
                    }
                ?>
                <p class="error username_error"></p>
                <!-- Login form - password -->
                <input id="password" type="password" name="password" class="login_form" placeholder="Password" aria-label="Password" required>
                <?php
                    //printing an error message
                    if (isset($_SESSION["incorrect_pass"])) {
                        echo "<p class='php-errors'>".$_SESSION["incorrect_pass"]."</p>";
                    }
                ?>
                <p class="error password_error"></p>
                
                <!-- Sending the csrf token along with the form -->
                <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">

                <button type="submit" name = "submit" class="login_button">Login</button>
    
                <a class="register_button" href="./register.php">Register</a>
            </form>
            <!-- Calling a javascript function to validate the login form -->
            <script>login_init();</script>
        </div>
    </header>
            
    <!-- Write the GET value of the city we're currently browsing -->
    <div class="forum">
        <span class="city-baby"><?php echo $active_city;?></span>
    </div>
    
    <?php
        $adress = "./city.php?city=".$active_city;
        echo "<form method='post' class='forum' id='sort' action='".$adress."'>";
    ?>
        <!-- Buttons to filter posts by time -->
        <p class="sort">Sort by:</p>
        <button name="oldest" id="oldest" class="sort-buttons">Oldest</button>
        <button name="newest" id="newest" class="sort-buttons">Newest</button>
    </form>

    <div class="posts">
        <?php
            //GET POSTS FROM SPECIFIC CITY
            $city_array = array_reverse(json_decode(file_get_contents("./city_tips/city_tips.json"))->$active_city);

            //APPLY FILTER
            if (isset($_POST["oldest"])) {
                $city_array = array_reverse($city_array);
                $_SESSION["filter"] = "oldest";
            }
            else {
                $_SESSION["filter"] = "newest";
            }
            
            //Call a function to print the post, passing the values of the array and of GET
            print_posts($active_city, $city_array);
        ?>
        <div id="more-posts"></div>
        <?php
        //If there are any more posts to show, keep the button on the screen
        if ($_SESSION["show_button"]) {
            echo "<button name ='show-more' type='submit' id='show-more' class='show-more' style='display: block'>Show more</button>
            <input type='hidden' id='city' value=".$active_city.">";
        }

        ?>
    </div>
    <script>
        //Call a javascript function that loads 5 more posts without reloading the page itself
        ajax_init();
    </script>

    <?php
    //Again, set the session variables to required values
    $_SESSION["incorrect_pass"] = NULL;
    $_SESSION["doesnt_exist"] = NULL;
    $_SESSION["login-value"] = NULL;
    ?>
</body>
</html>