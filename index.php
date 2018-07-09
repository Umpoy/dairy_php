<?php
session_start();
$error = "";
if(array_key_exists("logout", $_GET)){ // destroys session when logged out
    unset($_SESSION);
    setcookie("id", "". time() - 60*60); // seconds * minutes
    $_COOKIE["id"] = "";
} else if (array_key_exists("id", $_SESSION) OR array_key_exists("id", $_COOKIE)) { // if logged in redirects to log in page
    header("Location: loggedinpage.php");
}
if(array_key_exists("submit", $_POST)){
    $link = mysqli_connect("localhost", "root", "root", "dairy"); // connects to phpmyadmin
    if (mysqli_connect_error()) {
        die ("Database Connection Error");
    }
    if (!$_POST['email']) { // if email is not input class="form-control"ed
        $error .= "An email address is required<br>";
    } 
    if (!$_POST['password']) { // if password is not input class="form-control"ed
        $error .= "A password is required<br>";
    } 
    if ($error != "") { // if email or password is not are not filled in
        $error = "<p>There were error(s) in your form:</p>".$error;
    } else { // email and password was entered
        if ($_POST['signUp'] == '1') {
            $query = "SELECT id FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0) {
                $error = "That email address is taken.";
            } else {
                $query = "INSERT INTO `users` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";
                if (!mysqli_query($link, $query)) {
                    $error = "<p>Could not sign you up - please try again later.</p>";
                } else {
                    $query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";
                    mysqli_query($link, $query);
                    $_SESSION['id'] = mysqli_insert_id($link);       
                    if (array_key_exists("stayLoggedIn", $_POST)) {
                        setcookie("id", mysqli_insert_id($link), time() + 60*60*24*365);
                    } 
                    header("Location: loggedinpage.php");
                }
            } 
        } else {
                $query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";           
                $result = mysqli_query($link, $query);           
                $row = mysqli_fetch_array($result);           
                if (isset($row)) {                  
                    $hashedPassword = md5(md5($row['id']).$_POST['password']);                  
                    if ($hashedPassword == $row['password']) {                       
                        $_SESSION['id'] = $row['id'];                        
                        if (array_key_exists("stayLoggedIn", $_POST)) {
                            setcookie("id", $row['id'], time() + 60*60*24*365);
                        } 
                        header("Location: loggedinpage.php");                            
                    } else {                        
                        $error = "That email/password combination could not be found.";                        
                    }                    
                } else {                    
                    $error = "That email/password combination could not be found.";                    
                }                
            }        
    }  
}
?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <title>Hello, world!</title>
    </head>
    <body>
        <div class="container">
            <h1>Secret Diary</h1>
            <div id="error"><?php echo $error; ?></div>
    
            <form method="post">
                <fieldset class="form-group">
                    <input class="form-control" type="email" name="email" placeholder="Your Email">
                </fieldset>
                <fieldset class="form-group">
                    <input class="form-control" type="password" name="password" placeholder="Password">
                </fieldset>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="stayLoggedIn" value=1>
                            Stay logged in
                        </label>
                    </div>
                <fieldset class="form-group">
                    <input class="form-control" type="hidden" name="signUp" value="1">   
                    <input class="btn btn-success" type="submit" name="submit" value="Sign Up!">
                </fieldset>
            </form>

            <form method="post">
                <fieldset class="form-group">
                <input class="form-control" type="email" name="email" placeholder="Your Email">
                </fieldset>
                <fieldset class="form-group">
                <input class="form-control" type="password" name="password" placeholder="Password">
                </fieldset>
                <div class="checkbox">
                    <label>
                        <input class="form-control" type="checkbox" name="stayLoggedIn" value=1>
                        Stay logged in
                    </label>
                </div>
                
        
                <fieldset class="form-group">
                <input class="form-control" type="hidden" name="signUp" value="0">
                <input class="btn btn-success" type="submit" name="submit" value="Log In!">
                </fieldset>
            </form>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>

