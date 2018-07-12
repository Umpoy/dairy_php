<?php
    session_start();
    $current_date = gmDate("Y-m-d"); 
    $diaryContent = "";
    $message = "";
    $user_id = "";
    if(array_key_exists("id", $_COOKIE)){
        $_SESSION['id'] = $_COOKIE['id'];
    }
    if(array_key_exists("id", $_SESSION)){ // used to render stuff to DOM
        $logout = "<a href='index.php?logout=1'>Log out</a>";
        include("connect.php");
        $query = "SELECT username FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
        $row = mysqli_fetch_array(mysqli_query($link, $query));
        $usernameContent = $row['username'];

        $query = "SELECT id FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
        $row = mysqli_fetch_array(mysqli_query($link, $query));
        $user_id = $row['id'];

        // $sql = "SELECT `post` FROM `post` WHERE user_id = $user_id";
        // $result = $link->query($sql);
        // if($result->num_rows > 0){
        //     while($row = $result->fetch_assoc()){
        //         $render = "<div>".$row['post']."</div>";
        //     }
        // }
        // $row = mysqli_fetch_array(mysqli_query($link, $sql));
        // print_r($row);
    } else {
        header("Location: index.php");
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['diaryentry'] != ""){
        if(!empty($_POST["diaryentry"])){ // happens when text is not empty
            $message .= "text sent";
            $query = "INSERT INTO `post`(`user_id`, `post`, `date`) VALUES ('".mysqli_real_escape_string($link, $user_id)."', '".mysqli_real_escape_string($link, $_POST['diaryentry'])."', '".mysqli_real_escape_string($link, $current_date)."')";
            mysqli_query($link, $query);
            header("Location: loggedinpage.php");
            unset($_POST);
        } else {
            $message .= "text empty";
        }
    }
?>

<?php include("header.php") ?>
        <nav class="navbar navbar-expand-lg">
            <div class="hello"><?php echo  "Hello, ". $usernameContent ?></div>
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
                <button class="btn btn-danger my-2 my-sm-0"> <?php echo $logout ?></button>
            </div>
        </nav>
        <div class="container">
                <form method="post">
                <textarea id="diary" name="diaryentry" maxlength="2000"></textarea>
                <input type="submit" name="submit" value="Enter Log">
                <div> <?php echo $message ?> </div>
                </form>
                <div class="entry_container">
                    <?php 
                    $query = "SELECT `post`, `date` FROM `post` WHERE user_id = $user_id";
                    $result = $link->query($query);
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            echo "<div class='post animated fadeInUp'><div><q>".$row['post']."</q></div>"."<p>".$row['date']."</p></div>";
                        }
                    }
                    ?>
                </div>
        </div>

<?php include("footer.php"); ?>