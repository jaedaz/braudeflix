<?php 
require_once("includes/config.php");
require_once("includes/classes/Entity.php");

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get the logged-in user's username
$username = $_SESSION["userLoggedIn"]; 

// SQL query to fetch recent watched videos
$sql = "SELECT v.id, v.title, v.description, v.season, v.episode, v.isMovie, v.filePath, e.name
        FROM videos v 
        INNER JOIN entities e ON e.id = v.entityId 
        INNER JOIN videoProgress vp ON vp.videoId = v.id
        WHERE vp.username = :username
        AND vp.dateModified >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
        ORDER BY vp.dateModified DESC
        LIMIT 25";

$stmt = $con->prepare($sql);
$stmt->bindValue(':username', $username, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="app">
    <h1>Your Recent Watching This Month</h1>
    <div class="movie-list">
        <?php
        if (count($result) > 0) {
            foreach($result as $row) {
                echo '<a href="watch.php?id=' . $row['id'] . '">';
                echo '<div class="movie-card">';
                echo '<video src="' . $row["filePath"] . '" muted></video>';
                echo '<h3>' . $row["name"] . '</h3>';
                if($row["isMovie"] == 1){
                    echo '<h3>Movie</h3>';
                } else {
                    echo '<h3>S:' . $row["season"] . ' E:' . $row["episode"] . '</h3>';
                }
                echo '</div>';
                echo '</a>';
            }
        } else {
            echo "No entities found.";
        }
        ?>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const videoCards = document.querySelectorAll(".movie-card");

    videoCards.forEach(card => {
        const video = card.querySelector("video");

        if (video) {
            card.addEventListener("mouseenter", function() {
                video.setAttribute('autoplay', 'true');
                video.play().catch(error => {
                    console.error("Video play error: ", error);
                });
            });

            card.addEventListener("mouseleave", function() {
                video.removeAttribute('autoplay');
                video.pause();
                video.currentTime = 0;
            });
        }
    });
});
</script>
