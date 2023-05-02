<?php
session_start();

//Check if a user is logged in, if not, redirect to index.php
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
$_SESSION["is_city"] = False;
$_SESSION["show_button"] = False;

//Include the printing php script
include "./scripts/printing.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tourist tips</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="./scripts/ajax.js"></script>
    <?php
        //Apply colorblind filter based on a cookie
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
            <form method="post" action="./scripts/logout.php" class="menu_list">
                <!-- logout button -->
                <button class="register_button username_form">Log out</button>
                <!-- print the users name and make sure it isn't malicious -->
                <p class = "register_button">
                <?php echo htmlspecialchars($_SESSION["name"]); ?></p>
            </form>
        </div>
    </header>

    <div class="forum">
        <!-- button for my posts -->
        <p class="city-baby">My posts</p>
    </div>

    <div class="posts">
        <?php
            //Script to print the posts
            if (filesize("./city_tips/city_tips.json") != 0) {
                $city_array = array();
                
                //GET POSTS FROM SPECIFIC CITY
                $existing_posts = json_decode(file_get_contents("./city_tips/city_tips.json"));
                foreach ($existing_posts as $city) {
                    if (!empty($city)) {
                        foreach ($city as $post)
                        if ($post->author == $_SESSION["username"]) {
                            array_push($city_array, $post);
                        }
                    }
                }
                $city_array = array_reverse($city_array);
                $_SESSION["ismypost"] = True;
                $active_city = null;
                //APPLY FILTER
                $_SESSION["filter"] = "newest";

                //Call a function to print the posts
                print_posts($active_city, $city_array);
            }
        ?>
        <div id="more-posts"></div>
        <?php
        //If more posts left, show the "show more" button
        if ($_SESSION["show_button"]) {
            echo "<button name ='show-more' type='submit' id='show-more' class='show-more' style='display: block'>Show more</button>
            <input type='hidden' id='city' value=".$active_city.">";
        }
        ?>
    </div>

    <script>
    //Call the ajax function to load more posts without refreshing
    ajax_init();
    </script>

    <?php
    //Again set required session values
    $_SESSION["incorrect_pass"] = NULL;
    $_SESSION["doesnt_exist"] = NULL;
    $_SESSION["login-value"] = NULL;
    ?>
</body>

</html> 
