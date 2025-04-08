<?php
require_once("includes/header.php");

$preview = new PreviewProvider($con, $userLoggedIn);
echo $preview->createPreviewVideo(null);

include "NextWatch.php";
include "DontWatch.php";
include "ContinueWatch.php";
include "MostViewsEver.php";
include "MostViewsMovieEver.php";
include "RecentWatch.php";
include "YouHaveIn.php";





echo "<h1 style='color:#fff;padding-left:60px'>All Categories</h1>";
$containers = new CategoryContainers($con, $userLoggedIn);
echo $containers->showAllCategories();



