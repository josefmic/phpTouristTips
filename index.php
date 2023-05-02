<?php
    session_start();
    //Check if is logged in, if so, go to logged in page
    if (isset($_SESSION["username"])) {
        if (($_SESSION["username"]) != null) {
            header("Location: ./logged_index.php");
        }
    }
    //Create a csrf token
    $token = bin2hex(random_bytes(32));
    $_SESSION["csrf_token"] = $token;

    //Set proper session values
    $_SESSION["city"] = NULL;
    $_SESSION["is_city"] = False;
    $_SESSION["show_button"] = False;
    $_SESSION["author"] = False;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tourist tips</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="./scripts/validation.js"></script>
    <?php
    //Apply the colorblind skin based on a user cookie
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
            <form method="post" action="./scripts/login.php" class="menu_list">
                <?php
                //Make sure the form is filled after sending invalid form
                if (isset($_SESSION['login-value'])) {
                    echo "<input id='username' type='text' name='username' class='login_form username_form' 
                    placeholder='Username' aria-label='Username' required value=".htmlspecialchars($_SESSION['login-value']).">";
                }
                else {
                    echo "<input id='username' type='text' name='username' class='login_form username_form' 
                    placeholder='Username' aria-label='Username' required value=''>";
                }           
                ?>
                <?php
                //Print error message if it exists
                if (isset($_SESSION["doesnt_exist"])) {
                    echo "<p class='php-errors'>".$_SESSION["doesnt_exist"]."</p>";
                }
                ?>
                <p class="error username_error"></p>

                <input id="password" type="password" name="password" class="login_form" placeholder="Password" aria-label="Password" required>
                <?php
                    //Print incorrect password message
                    if (isset($_SESSION["incorrect_pass"])) {
                        echo "<p class='php-errors'>".$_SESSION["incorrect_pass"]."</p>";
                    }
                    ?>

                    <p class="error password_error"></p>

                <!-- Send the csrf token along with the page -->
                <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                <button type="submit" name = "submit" class="login_button">Login</button>
                <a class="register_button" href="./register.php">Register</a>
            </form>
            <!-- Call validation javascript function -->
            <script>login_init();</script>
        </div>
        <form method="post" action="./scripts/colorblind.php">
            <button name="colorblind" class="colorblind" id="colorblind"></button>
        </form>
    </header>

    <!-- City buttons -->
    <div class = "buttons">
        <form method="post" action="city.php?city=London">
            <button class="city_london city_buttons" name = "London">LONDON</button>
        </form>
        <form method="post" action="city.php?city=Paris" >
            <button class="city_paris city_buttons" name = "Paris">PARIS</button>
        </form>
        <form method="post" action="city.php?city=Istanbul" >
            <button class="city_istanbul city_buttons" name = "Istanbul">ISTANBUL</button>
        </form>
        <form method="post" action="city.php?city=Rome" >
            <button class="city_rome city_buttons" name = "Rome">ROME</button>
        </form>
        <form method="post" action="city.php?city=Amsterdam" >
            <button class="city_amsterdam city_buttons" name = "Amsterdam">AMSTERDAM</button>
        </form>
        <form method="post" action="city.php?city=Barcelona" >
            <button class="city_barcelona city_buttons" name = "Barcelona">BARCELONA</button>
        </form>
        <form method="post" action="city.php?city=Prague" >
            <button class="city_prague city_buttons" name = "Prague">PRAGUE</button>
        </form>
        <form method="post" action="city.php?city=Vienna" >
            <button class="city_vienna city_buttons" name = "Vienna">VIENNA</button>
        </form>
        <form method="post" action="city.php?city=Milan" >
            <button class="city_milan city_buttons" name = "Milan">MILAN</button>
        </form>
        <form method="post" action="city.php?city=Athens" >
            <button class="city_athens city_buttons" name = "Athens">ATHENS</button>
        </form>
        <form method="post" action="city.php?city=Berlin" >
            <button class="city_berlin city_buttons" name = "Berlin">BERLIN</button>
        </form>
        <form method="post" action="city.php?city=Moscow" >
            <button class="city_moscow city_buttons" name = "Moscow">MOSCOW</button>
        </form>
        <form method="post" action="city.php?city=Venice" >
            <button class="city_venice city_buttons" name = "Venice">VENICE</button>
        </form>
        <form method="post" action="city.php?city=Madrid" >
            <button class="city_madrid city_buttons" name = "Madrid">MADRID</button>
        </form>
        <form method="post" action="city.php?city=Dublin" >
            <button class="city_dublin city_buttons" name = "Dublin">DUBLIN</button>
        </form>
        <form method="post" action="city.php?city=Florence" >
            <button class="city_florence city_buttons" name = "Florence">FLORENCE</button>
        </form>
    </div>
</body>
<?php
//Again set the sessions to the correct values
$_SESSION["incorrect_pass"] = NULL;
$_SESSION["doesnt_exist"] = NULL;
$_SESSION["login-value"] = NULL;
?>
</html>