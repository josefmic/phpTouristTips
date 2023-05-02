
//Function that is called from the .php file
function ajax_init() {
    //Find a button and add a listener to click
    const button = document.querySelector("#show-more");
    button.addEventListener("click", get_ajax);
}

//Function that calls the .php file asynchronously
function get_ajax() {
    //Create a new XMLHttpRequest
    let ourRequest = new XMLHttpRequest();
    //Check if request was successful
    ourRequest.onreadystatechange = function () {
        if (ourRequest.readyState == 4 && ourRequest.status == 200) {
            let sentData = ourRequest.responseText;
            //If there aren't any more posts, hide the "show more" button
            if (sentData.includes("no-button")) {
                document.querySelector("#show-more").style.display = "none";
            }
            //Take the server generated data and print them into the page
            document.querySelector("#more-posts").innerHTML += sentData;
        }
    }
    //Open and send the request
    ourRequest.open("GET", "scripts/ajax.php");
    ourRequest.send();
}