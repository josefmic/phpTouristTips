<?php
session_start();
//Let the server know that we're not on the my_posts page
$_SESSION["ismypost"] = False;

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

//Checking a non malicious user
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

//Creating a new csrf token
$token = bin2hex(random_bytes(32));
$_SESSION["csrf_token"] = $token;

//Include php file for printing posts
include "./scripts/printing.php";

//Set session variables to required values
$_SESSION["author"] = True;
$_SESSION["is_city"] = True;
$_SESSION["city"] = $active_city;


//Checking if a user tried to access this site while not being logged in
//If he isnt, redirect him to city.php
if (isset($_SESSION["username"])) {
    if ($_SESSION["username"] == NULL) {
        header("Location: ./city.php?city=".$active_city);
    }
}
else {
    header("Location: ./city.php?city=".$active_city);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tourist tips</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="./scripts/validation.js"></script>
    <script src="./scripts/ajax.js"></script>
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
        <h2><a href="./logged_index.php" class="title">Tourist tips.</a></h2>
        <div class="menu_list">
            <form method="post" action="./my_posts.php" class="menu_list">
                <!-- button for my posts -->
                <button class="register_button username_form">My posts</button>
                <p class = "register_button">
            </form>
            <form method="post" action="./scripts/logout.php" class="menu_list">
                <!-- logout button -->
                <button class="register_button username_form">Log out</button>
                <p class = "register_button">
                <!-- write out the users name and make sure it isn't malicious -->
                <?php echo htmlspecialchars($_SESSION["name"]); ?></p>
            </form>
        </div>
    </header>
    <div class="forum">
        <span class="city-baby"><?php echo $active_city ?></span>
    </div>
    <?php
        $adress = "./scripts/user_inputs.php?city=".$active_city;
        echo "<form method='post' class='forum' id='forum-form' action='".$adress."'>";
    ?>
        <label class="tip-label">Title:
            <?php
                //Make sure the title stays filled out after incorrect form submit
                if (isset($_SESSION["title-value"])) {
                    echo "<input type='text' class='tip-title' name='tip-title' id='title'
                    required value=".htmlspecialchars($_SESSION["title-value"]).">";
                }
                else {
                    echo "<input type='text' class='tip-title' name='tip-title' id='title' required value=''>";
                }
            ?>
        <br>
        </label>

        <label class="tip-label">Tip:
            <!-- make sure the textarea stays filled -->
            <textarea class = "tip-text" id="tip-text" name="tip-text" required
            ><?php if (isset($_SESSION["text-value"])) {echo htmlspecialchars($_SESSION["text-value"]);}?></textarea>
        <br>
        </label>
        <!-- Sending a csrf token along with the form -->
        <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
        <input class="post-tip" type="submit" name="submit" value="Post">
        <p class="error title-error"></p>
            <?php
                //Print out the title error
                if (isset($_SESSION["title_error"])) {
                echo "<p class='php-errors'>".$_SESSION["title_error"]."</p>";
                }
        ?>
        <p class="error text-error"></p>
            <?php
            //Print out the text error
            if (isset($_SESSION["text_error"])) {
            echo "<p class='php-errors'>".$_SESSION["text_error"]."</p>";
            }
        ?>

    </form>

    <?php
        $adress = "./logged_city.php?city=".$active_city;
        echo "<form method='post' class='forum' id='sort' action='".$adress."'>";
    ?>
        <!-- Buttons that sort the posts -->
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

            //Call a function that prints the posts
            print_posts($active_city, $city_array);
        ?>
        <div id="more-posts"></div>
        <?php
        //If any posts left, show the "show more" button
        if ($_SESSION["show_button"]) {
            echo "<button name ='show-more' type='submit' id='show-more' class='show-more' style='display: block'>Show more</button>
            <input type='hidden' id='city' value='".$active_city."'>";
        }

        ?>
    </div>
    <script>
        //Call the ajax function so that showing more posts doesn't refresh the page
        forum_init();
        ajax_init();
    </script>

    <?php
    //Set required session values
    $_SESSION["text_error"] = NULL;
    $_SESSION["title_error"] = NULL;
    $_SESSION["title-value"] = NULL;
    $_SESSION["text-value"] = NULL;
    ?>
</body>

</html>