<?php
require_once("../includes/config.php");

if(isset($_POST["username"])) {
    $query = $con->prepare("UPDATE users SET isSubscribed= 0
                            WHERE username=:username");
    $query->bindValue(":username", $_POST["username"]);


    $query->execute();
}
else {
    echo "No videoId or username passed into file";
}
