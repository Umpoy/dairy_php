<?php
    if(array_key_exist("submit", $_POST)){
        $error = "";
        if(!$_POST['email']){ // checks if email input is not filled in
            $error .= "An email address is required";
        }
        if(!$_POST['password']){ // check if password input is not filled in
            $error .= "A password is required";
        }
        if($error != ""){ // if error is not complete
            $error = "<p>There were error(s) in your form:</p>".$error;
        } else { // everything is okay

        }
    }
?>

<form method="post">
    <input type="email" name="email" placeholder="Your Email">
    <input type="password" name="password" placeholder="Password">
    <input type="checkbox" name="stayLoggedIn" value=1>
    <input type="submit" name="submit" value="Sign Up!">
</form>