<?php
session_start();

//Check if user is logged, if not, redirect to index.php
if (isset($_SESSION["username"])) {
    if (($_SESSION["username"]) == null) {
        header("Location: ./index.php");
    }
}
else{
    header("Location: ./index.php");
}

//Set required session values
$_SESSION["city"] = NULL;
$_SESSION["show_button"] = False;
$_SESSION["author"] = True;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tourist tips</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
        //Apply colorblind skin based on a cookie
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
        <h2><a href="./logged_index.php" class="title">Tourist tips.</a></h2>
        <div class="menu_list">
            <form method="post" action="./my_posts.php" class="menu_list">
                <!-- Button for my posts -->
                <button class="register_button username_form">My posts</button>
            </form>
            <form method="post" action="./scripts/logout.php" class="menu_list">
                <!-- logout button -->
                <button class="register_button username_form">Log out</button>
                
                <!-- write out the users name and make sure it isn't malicious -->
                <p class="username">
                <?php echo htmlspecialchars($_SESSION["name"]); ?></p>
            </form>
        </div>
        <!-- colorblind button -->
        <form method="post" action="./scripts/colorblind.php">
            <button name="colorblind" class="colorblind" id="colorblind"></button>
        </form>
    </header>

    <!-- city buttons -->
    <div class = "buttons">
        <form method="post" action="logged_city.php?city=London">
            <button class="city_london city_buttons" name = "London">LONDON</button>
        </form>
        <form method="post" action="logged_city.php?city=Paris" >
            <button class="city_paris city_buttons" name = "Paris">PARIS</button>
        </form>
        <form method="post" action="logged_city.php?city=Istanbul" >
            <button class="city_istanbul city_buttons" name = "Istanbul">ISTANBUL</button>
        </form>
        <form method="post" action="logged_city.php?city=Rome" >
            <button class="city_rome city_buttons" name = "Rome">ROME</button>
        </form>
        <form method="post" action="logged_city.php?city=Amsterdam" >
            <button class="city_amsterdam city_buttons" name = "Amsterdam">AMSTERDAM</button>
        </form>
        <form method="post" action="logged_city.php?city=Barcelona" >
            <button class="city_barcelona city_buttons" name = "Barcelona">BARCELONA</button>
        </form>
        <form method="post" action="logged_city.php?city=Prague" >
            <button class="city_prague city_buttons" name = "Prague">PRAGUE</button>
        </form>
        <form method="post" action="logged_city.php?city=Vienna" >
            <button class="city_vienna city_buttons" name = "Vienna">VIENNA</button>
        </form>
        <form method="post" action="logged_city.php?city=Milan" >
            <button class="city_milan city_buttons" name = "Milan">MILAN</button>
        </form>
        <form method="post" action="logged_city.php?city=Athens" >
            <button class="city_athens city_buttons" name = "Athens">ATHENS</button>
        </form>
        <form method="post" action="logged_city.php?city=Berlin" >
            <button class="city_berlin city_buttons" name = "Berlin">BERLIN</button>
        </form>
        <form method="post" action="logged_city.php?city=Moscow" >
            <button class="city_moscow city_buttons" name = "Moscow">MOSCOW</button>
        </form>
        <form method="post" action="logged_city.php?city=Venice" >
            <button class="city_venice city_buttons" name = "Venice">VENICE</button>
        </form>
        <form method="post" action="logged_city.php?city=Madrid" >
            <button class="city_madrid city_buttons" name = "Madrid">MADRID</button>
        </form>
        <form method="post" action="logged_city.php?city=Dublin" >
            <button class="city_dublin city_buttons" name = "Dublin">DUBLIN</button>
        </form>
        <form method="post" action="logged_city.php?city=Florence" >
            <button class="city_florence city_buttons" name = "Florence">FLORENCE</button>
        </form>
    </div>
</body>

</html>