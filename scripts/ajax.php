<?php
session_start();
//If we're on a city page, generate data only for the city
if (isset($_SESSION["city"])) {
    $active_city = $_SESSION["city"];
    $city_array = array_reverse(json_decode(file_get_contents("../city_tips/city_tips.json"))->$active_city);
}
//Else if we're on the "my posts" page, generate all data for the user
else {
    $city_array = array();
    //GET POSTS FROM SPECIFIC CITY
    $existing_posts = json_decode(file_get_contents("../city_tips/city_tips.json"));
    foreach ($existing_posts as $city) {
        if (!empty($city)) {
            foreach ($city as $post)
            if ($post->author == $_SESSION["username"]) {
                array_push($city_array, $post);
            }
        }
    }
    $city_array = array_reverse($city_array);
}

//Apply filter
if (isset($_SESSION["filter"])) {
    if ($_SESSION["filter"] == "oldest") {
        $city_array = array_reverse($city_array);
    }
}

/**
 * Function to print 5 more posts
 * print_posts
 *
 * @param  mixed $city_array
 * @return void
 */
function print_posts($city_array) {
    if (filesize("../city_tips/city_tips.json") != 0) {

        //PRINT ANOTHER 5
        if (!empty($city_array)) {
            while (True) {
                //Save data from array index into a variable
                if (array_key_exists($_SESSION["index"], $city_array)) {
                    $title = $city_array[$_SESSION["index"]]->title;
                    $text = $city_array[$_SESSION["index"]]->text;
                    $author = $city_array[$_SESSION["index"]]->author;
                    $date = $city_array[$_SESSION["index"]]->date;
                    $id = $city_array[$_SESSION["index"]]->id;
            
                    //Create a variable that has the text that is going to be printed on the page, make sure it isn't malicious
                    $html_printing = 
                    "<div class='post-div'>
                    <p class='post-title'>".htmlspecialchars($title)."</p> 
                    <p class='post-text'>".htmlspecialchars($text)."</p> 
                    <p class='post-author'>User: ".htmlspecialchars($author)."</p>
                    <p class='post-date'>".htmlspecialchars($date)."</p>";

                    $active_city = $_SESSION['city'];
                
                    //If a user is an admin, place a "delete" button under each post
                    if (($_SESSION["isadmin"] == true)) {
                        $html_printing .= 
                        "<form method='post' action='./scripts/user_inputs.php?city=$active_city'>
                        <input type='hidden' name='id' value=".$id.">
                        <button class='delete-button' type='submit' name='delete_submit'>Delete</button>
                        </form>
                        </div>";
                    }
                    //Else if a post is owned by the current user, also place a "delete" button
                    elseif (isset($_SESSION["username"])) {
                        if ($_SESSION["username"] == $author) {
                            $html_printing .= 
                            "<form method='post' action='./scripts/user_inputs.php?city=$active_city'>
                            <input type='hidden' name='id' value=".$id.">
                            <button class='delete-button' type='submit' name='delete_submit'>Delete</button>
                            </form>
                            </div>";
                        }
                        else {
                            $html_printing .= "</div>";
                        }
                    }
                    else {
                        $html_printing .= "</div>";
                    }
                    //If 5 posts have been saved, check if any posts are left, send the data to the ajax script and break
                    if (($_SESSION["index"] % 5) == 0) {
                        $_SESSION["index"] +=1;
                        if (array_key_exists($_SESSION["index"], $city_array)) {
                            $_SESSION["show_button"] = True;
                            echo $html_printing;
                            break;
                        }
                        else {
                            $html_printing .= "<input type='hidden' id='no-button'>";
                            echo $html_printing;
                            break;
                        }
                    }
                    //Else add onto the index and send the data, and if there are no more posts left, break
                    else {
                        $_SESSION["index"] +=1;
                        if (array_key_exists($_SESSION["index"], $city_array)) {
                            echo $html_printing;
                        }
                        else {
                            $html_printing .= "<input type='hidden' id='no-button'>";
                            echo $html_printing;
                            break;
                        }
                    }
                }
                else {
                    break;
                }
            }
        }
    }
}
//Call the actual function
print_posts($city_array);
?>