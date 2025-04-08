<?php 
require_once("includes/config.php");
require_once("includes/classes/Entity.php");

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$username = $_SESSION["userLoggedIn"]; 

$sql = "SELECT e.id, e.name, e.thumbnail, e.preview, e.categoryId
        FROM entities e
        LEFT JOIN HasFinish ha ON ha.entityId = e.id AND ha.username = :username 
        WHERE EXISTS (
            SELECT 1
            FROM videos v
            JOIN videoProgress vp ON vp.videoId = v.id AND vp.username = :username
            WHERE v.entityId = e.id AND v.isMovie = 0
        )
        AND (ha.has IS NULL OR ha.has = 0)
        GROUP BY e.id";

$stmt = $con->prepare($sql);
$stmt->bindValue(':username', $username, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="app">
    <?php if (count($result) > 0): ?>
        <h1>Continue Watch Entities</h1>
        <div class="movie-list">
            <?php foreach ($result as $row): ?>
                <a href="entity.php?id=<?= $row['id'] ?>">
                    <div class="movie-card">
                        <img src="<?= $row["thumbnail"] ?>" alt="<?= $row["name"] ?>">
                        <h3><?= $row["name"] ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Show nothing if there are no entities to continue watching -->
    <?php endif; ?>
</div>
