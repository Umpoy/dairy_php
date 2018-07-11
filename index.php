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
    include("connect.php");
    if (!$_POST['email']) { // if email is not inputed
        $error .= "An email address is required<br>";
    } 
    if (!$_POST['password']) { // if password is not inputed 
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
                $query = "INSERT INTO `users` (`email`, `password`, `username`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."', '".mysqli_real_escape_string($link, $_POST['username'])."')";
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
                        if (isset($_POST['stayLoggedIn']) AND $_POST['stayLoggedIn'] == '1') {
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
<?php include("header.php"); ?>
        <div class="container">
            <div class="hero">
                <h1 class="animated fadeInDown">Secret Diary</h1>
                <div id="error"><?php echo $error; ?></div>

                <form method="post" id="logInForm" class="animated fadeInUp">
                    <fieldset class="form-group">
                    <input class="form-control" type="email" name="email" placeholder="Your Email" required>
                    </fieldset>
                    <fieldset class="form-group">
                    <input class="form-control" type="password" name="password" placeholder="Password" required>
                    </fieldset>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" type="hidden" name="stayLoggedIn" value=1 checked>
                        </label>
                    </div>
                    <fieldset class="form-group">
                    <input class="form-control" type="hidden" name="signUp" value="0">
                    <input class="btn" type="submit" name="submit" value="Log In!">
                    </fieldset>
                    <p>New User? <a class="toggleForms btn">Sign Up!</a></p>
                </form>

                <form method="post" id="signUpForm" class="animated fadeInUp">
                    <fieldset class="form-group">
                        <input class="form-control" type="email" name="email" placeholder="Your Email" required>
                    </fieldset>
                    <fieldset class="form-group">
                        <input class="form-control" type="password" name="password" placeholder="Password" required>
                    </fieldset>
                    <fieldset class="form-group">
                        <input class="form-control" type="name" name="username" placeholder="User Name (This is the name we will greet you with)" required>
                    </fieldset>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="stayLoggedIn" value=1 checked>
                        </label>
                    </div>
                    <fieldset class="form-group">
                        <input class="form-control" type="hidden" name="signUp" value="1">   
                        <input class="btn" type="submit" name="submit" value="Sign Up!">
                    </fieldset>
                    <p>Already Signed Up? <a class="toggleForms btn">Log In</a></p>
                </form>
            </div>
        </div>

<?php include("footer.php"); ?>
