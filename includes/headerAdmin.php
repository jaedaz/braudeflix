<?php
require_once("includes/config.php");
require_once("includes/classes/PreviewProvider.php");
require_once("includes/classes/CategoryContainers.php");
require_once("includes/classes/Entity.php");
require_once("includes/classes/EntityProvider.php");
require_once("includes/classes/ErrorMessage.php");
require_once("includes/classes/SeasonProvider.php");
require_once("includes/classes/Season.php");
require_once("includes/classes/Video.php");
require_once("includes/classes/VideoProvider.php");
require_once("includes/classes/User.php");

$account = new Account($con);

if(!isset($_SESSION["userLoggedIn"])) {
    header("Location: login.php");
}

$userLoggedIn = $_SESSION["userLoggedIn"];
if ($account->isAdmin($userLoggedIn) != 1) {
    header("Location: register.php");
    exit; // It's good practice to exit after a redirect
}

$isWhite = false; // تعريف قيمة افتراضية للمتغير


?>
<!DOCTYPE html>
<html>
    <head>

        <title>Braudeflix</title>
        <link rel="stylesheet" type="text/css" href="assets/style/style.css" />

        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/06a651c8da.js" crossorigin="anonymous"></script>
        <script src="assets/js/script.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



    </head>
    <body>
        <div class='wrapper'>
            
<?php
if (!isset($hideNav)) {
    include_once("includes/navAdmin.php");
} if ($isWhite == true) {
    echo "
    <style>
    .wrapper {
        min-width: 1050px;
        min-height: 100%;
        background-color: #fff;
    }
    .navLinks a {
        color: #000;
        font-size: 14px;
        margin-left: 20px;
        transition: 0.5s;
        text-decoration: none;
    }
    </style>
    ";
}
?>