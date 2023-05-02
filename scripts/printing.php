<?php
/**
 * A function to print posts
 * print_posts
 *
 * @param  mixed $active_city
 * @param  mixed $city_array
 * @return void
 */
function print_posts($active_city, $city_array) {
    if (filesize("./city_tips/city_tips.json") != 0) {

        //PRINT FIRST 5 POSTS ON PAGE AND ADD REST TO ARRAY
        if (!empty($city_array)) {
            $_SESSION["index"] = 0;
            while (True) {
                //Save data from array into a variable
                if (array_key_exists($_SESSION["index"], $city_array)) {
                    $title = $city_array[$_SESSION["index"]]->title;
                    $text = $city_array[$_SESSION["index"]]->text;
                    $author = $city_array[$_SESSION["index"]]->author;
                    $date = $city_array[$_SESSION["index"]]->date;
                    $id = $city_array[$_SESSION["index"]]->id;
            
                    //Add the variables into a big html printing variable and make sure they aren't malicious
                    $html_printing = 
                    "<div class='post-div'>
                    <p class='post-title'>".htmlspecialchars($title)."</p> 
                    <p class='post-text'>".htmlspecialchars($text)."</p> 
                    <p class='post-author'>User: ".htmlspecialchars($author)."</p>
                    <p class='post-date'>".htmlspecialchars($date)."</p>";
                
                    //If a user is an admin, show a delete button under each post
                    if (($_SESSION["isadmin"] == true)) {
                        $html_printing .= 
                        "<form method='post' action='./scripts/user_inputs.php?city=$active_city'>
                        <input type='hidden' name='id' value=".$id.">
                        <button class='delete-button' type='submit' name='delete_submit'>Delete</button>
                        </form>
                        </div>";
                    }
                    //Or if the post is owned by the logged in user, show a delete button
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
                    //If 6 posts have been saved, print it on the page and break
                    if (($_SESSION["index"] == 5)) {
                        $_SESSION["index"] +=1;
                        if (array_key_exists($_SESSION["index"], $city_array)) {
                            $_SESSION["show_button"] = True;
                            echo $html_printing;
                            break;
                        }
                        else {
                            $_SESSION["show_button"] = False;
                            echo $html_printing;
                            break;
                        }
                    }
                    //Else print the post and go another cycle
                    else {
                        $_SESSION["index"] +=1;
                        echo $html_printing;
                    }
                }
                else {
                    $_SESSION["show_button"] = False;
                    break;
                }
            }
        }
    }
}



?>