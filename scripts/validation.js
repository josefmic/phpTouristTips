//Function for printing register errors
function register_error(errorClass, errorMessage) {
    document.querySelector("."+errorClass).classList.add("display-error");
    document.querySelector("."+errorClass).innerHTML = errorMessage;
}
//Function to clear error messages
function clear_error() {
    let errors = document.querySelectorAll(".error");
    for (let error of errors) {
        error.classList.remove("display-error");
    }
}
//Init function to validate the register form
function register_init() {
    clear_error();
    const form = document.querySelector("form");
    form.addEventListener("submit", register_validate);
}

//Function to validate the form
function register_validate(event) {
    //Get the input values and save them into variables
    let name = document.querySelector("#name").value;
    let username = document.querySelector("#username").value;
    let password = document.querySelector("#password").value;
    let birth = document.querySelector("#birth").value;

    //Function to validate the name
    function check_name(name) {
        //Check length
        if (name.length < 5) {
            event.preventDefault();
            register_error("name_error", "Your name must be at least 5 characters!");
        }
        else if (name.length > 30) {
            event.preventDefault();
            register_error("name_error", "Your name is too long!");
        }

        //Check for special characters
        for (let i of name) {
            for (let j of "!#$%&'()*+,-./:;<=>?@[\]^_`{|}~") {
                if (i == j) {
                    event.preventDefault();
                    register_error("username_error", "Username cannot have special characters!");
                }
            }
        }
    }

    //Function to validate the username
    function check_username(username) {
        //Check length
        if (username.length < 5) {
            event.preventDefault();
            register_error("username_error", "Your username must be at least 5 characters!")
        }
        
        else if (username.length > 15) {
            event.preventDefault();
            register_error("username_error", "Your username is too long!");
        }

        //Check for special characters
        for (let i of username) {
            for (let j of "!#$%&'()*+,-./:;<=>?@[\]^_`{|}~ ") {
                if (i == j) {
                    event.preventDefault();
                    register_error("username_error", "Username cannot have special characters and spaces!");
                }
            }
        }
    }

    //Function to validate the password
    function check_password(password) {
        //Check for special characters
        for (let i of password) {
            for (let j of "!#$%&'()*+,-./:;<=>?@[\]^_`{|}~ ") {
                if (i == j) {
                    event.preventDefault();
                    register_error("password_error", "Password cannot have special characters!");
                }
            }
        }
    }

    //function to validate the date of birth
    function check_birth(birth) {
        let arr = birth.split("-");
        let year = arr[0];
        //Check the age
        if (year < 1900) {
            event.preventDefault();
            register_error("birth_error", "Enter a valid date of birth!");
        }
        else if (year > 2010) {
            event.preventDefault();
            register_error("birth_error", "You're not old enough to register!");
        }
    }

    //call the individual functions
    check_name(name);
    check_username(username);
    check_password(password);
    check_birth(birth);
}

//Init function to call the login validation function
function login_init() {
    clear_error();
    const form = document.querySelector("form")
    form.addEventListener("submit", login_validate)
}

//Login validation function
function login_validate(event) {
    //Save the input values into variables
    let username = document.querySelector("#username").value;
    let password = document.querySelector("#password").value;

    //Check username length
    if (username.length < 5) {
        event.preventDefault();
        register_error("username_error", "Your username must be at least 5 characters!")
    }
    else if (username.length > 15) {
        event.preventDefault();
        register_error("username_error", "Your username is too long!");
    }

    //Check for password special characters, length and terms checked with html
    for (let i of password) {
        for (let j of "!#$%&'()*+,-./:;<=>?@[\]^_`{|}~")
            if (i == j) {
                event.preventDefault();
                register_error("password_error", "Password cannot have special characters!");
            }
    }
}

//Init function to call the forum validation function
function forum_init() {
    clear_error();
    const form = document.querySelector("#forum-form");
    form.addEventListener("submit", forum_validate);
}

//Function to validate the forum
function forum_validate(event) {
    //Save the forum input values into variables
    let title = document.querySelector("#title").value;
    let text = document.querySelector("#tip-text").value;
    //Check length
    if (title.length > 30) {
        event.preventDefault();
        register_error("title-error", "Your title is too long!");
    }

    if (text.length > 310) {
        event.preventDefault();
        register_error("text-error", "Your post is too long!");
    }
} 