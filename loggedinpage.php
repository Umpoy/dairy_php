<?php
    session_start();
    $current_date = gmDate("Y-m-d"); 
    $diaryContent = "";
    $message = "";
    if(array_key_exists("id", $_COOKIE)){
        $_SESSION['id'] = $_COOKIE['id'];
    }
    if(array_key_exists("id", $_SESSION)){
        $logout = "<a href='index.php?logout=1'>Log out</a>";
        include("connect.php");
        $query = "SELECT username FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
        $row = mysqli_fetch_array(mysqli_query($link, $query));
        $usernameContent = $row['username'];
    } else {
        header("Location: index.php");
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['diaryentry'] != ""){
        if(!empty($_POST["diaryentry"])){ // happens when text is not empty
            $message .= "text sent";
            $query = "SELECT id FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
            $row = mysqli_fetch_array(mysqli_query($link, $query));
            $user_id = $row['id'];
            $query = "INSERT INTO `post`(`user_id`, `post`, `date`) VALUES ('".mysqli_real_escape_string($link, $user_id)."', '".mysqli_real_escape_string($link, $_POST['diaryentry'])."', '".mysqli_real_escape_string($link, $current_date)."')";
            mysqli_query($link, $query);
        } else {
            $message .= "text empty";
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
        <link rel="stylesheet" href="assets/css/main.css">
        <title>Hello, world!</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg">
            <div><?php echo  "Hello, ". $usernameContent ?></div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
            
                </ul>
                <!-- <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-success my-2 my-sm-0" type="submit">Search</button>
                    
                </form> -->
                <button class="btn btn-primary my-2 my-sm-0"> <?php echo $logout ?></button>
            </div>
        </nav>
        <div class="container">
                <form method="post">
                <textarea id="diary" name="diaryentry" maxlength="2000"></textarea>
                <input type="submit" name="submit" value="Enter Log">
                <div> <?php echo $message ?> </div>
                </form>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.1.1.min.js">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="assets/js/main.js"></script>
    </body>
</html>