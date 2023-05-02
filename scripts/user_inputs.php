<?php
session_start();
//Save the GET into a variable
if (isset($_GET["city"])) {
    $active_city = $_GET["city"];
}

//Check for a malicious csrf token, if it is, redirect the user
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
date_default_timezone_set("CET");
if (isset($_POST["submit"])) {
    if (isset($active_city) && isset($_SESSION["username"]) && $active_city != null) {
        $id = 0;
        $last = -1;
        $post_date = date("j. m. o \│ H:i");
        //Get existing posts
        $check_existing = json_decode(file_get_contents("../city_tips/city_tips.json"));
        $city_array = $check_existing->$active_city;
        //Get the new ID, that should be given to the new post, be itering through all the posts
        if (!empty($check_existing)) {
            foreach ($check_existing as $city) {
                foreach ($city as $post) {
                    $id += 1;
                }
            }
        }

        //Save POST data into a new post array
        if (isset($_POST["submit"])) {
            $new_post = array(
                "city" => $active_city,
                "author" => $_SESSION["username"],
                "title" => $_POST["tip-title"],
                "text" => $_POST["tip-text"],
                "date" => $post_date,
                "id" => $id
            );
            
            /**
             * Function to validate the title
             * check_title
             *
             * @param  mixed $active_city
             * @return void
             */
            function check_title($active_city){
                $title = $_POST["tip-title"];
                //Making sure the title isn't longer than 30
                if (strlen($title) > 30) {
                    $title_error = "Your title is too long!";
                    $_SESSION["title_error"] = $title_error;
                    $_SESSION["title-value"] = $_POST["tip-title"];
                    $_SESSION["text-value"] = $_POST["tip-text"];
                    header("Location: ../logged_city.php?city=" . $active_city);
                    exit();
                }
            }
                        
            /**
             * Function to validate the text
             * check_text
             *
             * @param  mixed $active_city
             * @return void
             */
            function check_text($active_city) {
                $text = $_POST["tip-text"];
                //Making sure the text isn't longer than 310
                if (strlen($text) > 310) {
                    $text_error = "Your post is too long!";
                    $_SESSION["text_error"] = $text_error;
                    $_SESSION["title-value"] = $_POST["tip-title"];
                    $_SESSION["text-value"] = $_POST["tip-text"];
                    header("Location: ../logged_city.php?city=" . $active_city);
                    exit();
                }
            }

            //Call the validation functions
            check_title($active_city);
            check_text($active_city);
            
            //If the array for the city is empty, put the new post as the only post
            if (empty($city_array)) {
                $first_post = array($new_post);
                $city_array = $first_post;
                $check_existing->$active_city = $city_array;
                $save_data = $check_existing;
                file_put_contents("../city_tips/city_tips.json", (json_encode($save_data, JSON_PRETTY_PRINT)));
            }

            //Else add the post to the other posts
            else {
                $prev_posts = $city_array;
                array_push($prev_posts, $new_post);
                $check_existing->$active_city = $prev_posts;
                $save_data = $check_existing;
            }
            //Put the json encoded variable into the file, if not succesfull, echo an error message
            if (!file_put_contents("../city_tips/city_tips.json", json_encode($save_data, JSON_PRETTY_PRINT), LOCK_EX)) {
                $error = "Error posting";
                echo $error;
                exit();
            }

            //Else redirect the user, the post was successful
            else {
                echo "<script type='text/javascript'>document.location.href='{'../logged_city.php?city=".$active_city."'}';</script>";
                echo '<META HTTP-EQUIV="refresh" content="0;URL=' . '../logged_city.php?city=' .$active_city. '">';
            }
        }
    }
}
//If a user isn't posting, but deleting, this will be run
elseif (isset($_POST["delete_submit"])) {
    //If we're deleting the posts from a city page
    if ($active_city != null) {
        $check_existing = json_decode(file_get_contents("../city_tips/city_tips.json"));
        $city = $check_existing->$active_city;
        $index = -1;
        //After generating the existing data, find the post with the ID we want to delete and remove the post from the array
        foreach ($city as $item) {
            $index += 1;
            if ($item->id == $_POST["id"]) {
                array_splice($city, $index, 1);
            }
        }
        //Put the array back into the big array
        $check_existing->$active_city = $city;
        
        //Put the json encoded variable into the file, if not succesfull, echo an error message
        if (!file_put_contents("../city_tips/city_tips.json", json_encode($check_existing, JSON_PRETTY_PRINT), LOCK_EX)) {
            $error = "Error posting";
            echo $error;
            exit();
        }
        //Else redirect the user, the deleting was successful
        else {
            echo "<script type='text/javascript'>document.location.href='{'../logged_city.php?city=".$active_city."'}';</script>";
            echo '<META HTTP-EQUIV="refresh" content="0;URL=' . '../logged_city.php?city=' .$active_city. '">';
        }
    }
    //Else if we're deleting the posts from the my_posts page
    else {
        $check_existing = json_decode(file_get_contents("../city_tips/city_tips.json"));
        foreach ($check_existing as $city) {
            $index = 0;
            //After generating the existing data, find the post with the ID we want to delete and remove the post from the array
            foreach ($city as $post) {
                if ($post->id == $_POST["id"]) {
                    $acity = $post->city;
                    array_splice($city, $index, 1);
                    $check_existing->$acity = $city;
                }
                $index += 1;
            }
        }
        
        //Put the json encoded variable into the file, if not succesfull, echo an error message
        if (!file_put_contents("../city_tips/city_tips.json", json_encode($check_existing, JSON_PRETTY_PRINT), LOCK_EX)) {
            $error = "Error posting";
            echo $error;
            exit();
        }
        //Else redirect the user, the deleting was successful
        else{
            echo "<script type='text/javascript'>document.location.href='{'../my_posts.php'}';</script>";
            echo '<META HTTP-EQUIV="refresh" content="0;URL=' . '../my_posts.php">';
        }
    }
}
//If a user tried to access this page without the "POST" request, redirect him
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