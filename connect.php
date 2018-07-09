<?php
    $link = mysqli_connect("localhost", "root", "root", "dairy");
    if (mysqli_connect_error()) {
        die ("Database Connection Error");
    }
?>