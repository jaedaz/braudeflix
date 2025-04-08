<?php 
require_once("includes/config.php");
require_once("includes/classes/Entity.php");
require_once("includes/classes/PreviewProvider.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = $_SESSION["userLoggedIn"]; 

$preview = new PreviewProvider($con, $username);

$sql = $preview->getMostViewedMovies();
?>

<div class="app">
    <h1>Best Movies has Watched</h1>
    <div class="movie-list">
        <?php
        if (count($sql) > 0) {
            foreach($sql as $entity) {
                $id = $entity['id'];
                $name = $entity['title'];
                $thumbnail = $entity['thumbnail'];
                $categoryName = $entity['categoryName'];
                echo '<a href="watch.php?id=' . $id . '">';
                echo '<div class="movie-card">';
                echo '<img src="' . $thumbnail . '" alt="' . $name . '">';
                echo '</a>';
                echo '<h3>' . $name . '</h3>';
                echo '<h3>' . $categoryName . '</h3>';
                echo '<button onclick="WatchLaterVideo(' . $id . ')">
                        <i class="fas fa-plus"></i> Watch Later
                    </button>';          
                echo '</div>';            
            }
        } else {
            echo "No entities found.";
        }
        ?>
    </div>
</div>

