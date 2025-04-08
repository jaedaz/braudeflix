<?php

require_once("../includes/config.php");

if(isset($_POST['username'])){
    $username = $_POST['username'];
    $sql = $con->prepare("DELETE FROM users WHERE username=:username");
    $sql->bindValue(":username", $username);
    if ($sql->execute()) {
        echo "User delete successful!";
    } else {
        $errorInfo = $sql->errorInfo();
        echo "Error: " . $errorInfo[2];
    }
} else {
    echo "Something went wrong!!";
}




