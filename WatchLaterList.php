<?php 
$hideNav = true;
require_once("includes/header.php");

require_once("includes/config.php");
require_once("includes/classes/Entity.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = $_SESSION["userLoggedIn"]; 

$sql = "SELECT *
        FROM entities 
        WHERE EXISTS (SELECT * FROM ContinueAndAdd WHERE username = :username AND entityId = entities.id)";
        
$stmt = $con->prepare($sql);
$stmt->bindValue(":username", $username, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);


$sql1 = "SELECT *
        FROM videos 
        WHERE EXISTS (SELECT * FROM MoviesAndVideos WHERE username = :username AND videoId = videos.id)";
        
$stmt1 = $con->prepare($sql1);
$stmt1->bindValue(":username", $username, PDO::PARAM_STR);
$stmt1->execute();
$result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);


// Count the total number of entities
$totalVideoAndMovie = count($result1);
$totalEntities = count($result);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Braudeflix</title>
    <style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: #141414;
    }

    .app {
        text-align: center;
        padding-left: 60px;
    }

    .movie-list {
        padding: 100px;
        display: flex;
        flex-wrap: wrap; 
        justify-content: start;
    }

    .movie-card {
        flex: 0 0 200px;
        margin: 10px;
        border: 1px solid #ccc;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        background-color: #1c1c1c;
        color: white;
    }

    .movie-card img {
        width: 100%;
        height: 200px;
        object-fit: cover; 
    }

    .movie-card h3 {
        margin: 10px 0;
    }

    .movie-card:hover {
        transform: scale(1.05);
    }

    h1 {
        color: #fff;
        text-align: start;
        padding-left: 60px;
    }

    button {
        padding: 5px;
        margin-bottom: 10px;
        transition: all ease 0.6s;
        background: #fff;
        border: none;
        border-radius: 10px;
        color: red;
        font-weight: bold;
    }

    button:hover {
        letter-spacing: 1px;
        background: red;
        cursor: pointer;
        color: #fff;
    }

    .back-link {
        display: flex;
        align-items: center;
        color: #fff;
        text-decoration: none;
        font-size: 24px;
        padding-left: 60px;
        margin-top: 20px;
    }

    .back-link i {
        margin-right: 10px;
        transition: transform 0.3s ease;
        color: #fff;
    }

    .back-link:hover i {
        transform: translateX(-5px);
    }

    .movie-card video {
        width: 100%;
        height: auto;
        display: block;
        transition: opacity 0.3s;
    }

    .movie-card:hover video {
        opacity: 1;
    }
    </style>
</head>
<body>
<h1><a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i>Your List Series Saved(<?php echo $totalEntities; ?>)</a></h1>
<div class="app">
    <div class="movie-list">
        <?php
        if (count($result) > 0) {
            foreach($result as $row) {
                echo '<a href="entity.php?id=' . $row['id'] . '">';
                echo '<div class="movie-card">';
                echo '<img src="' . $row["thumbnail"] . '" alt="' . $row["name"] . '">';
                echo '</a>';
                echo '<h3>' . $row["name"] . '</h3>';
                echo '<button onclick="RemoveWatchLater(' . $row["id"] . ')">
                        <i class="fas fa-minus"></i> Remove From List
                    </button>';
                echo '</div>';
            }
        } else {
            echo "<h3 style='color:red;margin-left:50px'>You haven't added anything yet.</h3>";
        }
        ?>
    </div>
</div>

<div class="app">
    <h1>Your Movie And Videos Saved(<?php echo $totalVideoAndMovie ; ?>)</h1>
    <div class="movie-list">
        <?php
        if (count($result1) > 0) {
            foreach($result1 as $row) {
                echo '<a href="watch.php?id=' . $row['id'] . '">';
                echo '<div class="movie-card">';
                echo '<video muted src="' . $row["filePath"] . '" ></video>';
                echo '</a>';
                echo '<h3>' . $row["title"] . '</h3>';
                if($row["isMovie"] == 1){
                    echo '<h3>S:' . $row["season"] . '</h3>';
                } else {
                    echo '<h3>S:' . $row["season"] . '</h3>'.'<h3>E:' . $row["episode"] . '</h3>';
                }
                echo '<button onclick="RemoveWatchLaterVideo(' . $row["id"] . ')">
                        <i class="fas fa-minus"></i> Remove From List
                    </button>';
                echo '</div>';
            }
        } else {
            echo "<strong style='color:red;margin-left:55px;font-size:18px'>No Videos Or Movies found.</strong>";
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
                video.play();
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
</body>
</html>
