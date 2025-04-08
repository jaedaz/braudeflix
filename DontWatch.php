<?php 
require_once("includes/config.php");
require_once("includes/classes/Entity.php");


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = $_SESSION["userLoggedIn"]; 

$sql = "SELECT e.id, e.name, e.thumbnail, e.preview, e.categoryId
        FROM entities e
        WHERE NOT EXISTS (
            SELECT 1
            FROM videos v
            JOIN videoProgress vp ON vp.videoId = v.id AND vp.username = :username
            WHERE v.entityId = e.id
        )
        GROUP BY e.id";

$stmt = $con->prepare($sql);
$stmt->bindValue(':username', $username);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


    <div class="app">
    <h1>Entities you have not watched</h1>
    <div class="movie-list">
        <?php
        if (count($result) > 0) {
        foreach($result as $row) {
            echo '<a href="entity.php?id=' . $row['id'] . '">';
            echo '<div class="movie-card">';
            echo '<img src="' . $row["thumbnail"] . '" alt="' . $row["name"] . '">';
            echo '</a>';
            echo '<h3>' . $row["name"] . '</h3>';
            echo '<button onclick="WatchLater(' . $row["id"] . ')">
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


