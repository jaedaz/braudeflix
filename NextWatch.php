<?php
require_once("includes/config.php");
require_once("includes/classes/Entity.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$username = $_SESSION["userLoggedIn"];

$entityId1 = Entity::getMustCategoryView($con, $username);
$entity1 = new Entity($con, $entityId1);
$catId = $entity1->getCategoryId();

$query = $con->prepare("SELECT * FROM entities WHERE categoryId = :catId");
$query->bindValue(":catId", $catId, PDO::PARAM_INT);
$query->execute();

$stmt = $con->prepare("SELECT name FROM categories WHERE id=:id");
$stmt->bindValue(":id", $catId, PDO::PARAM_INT);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);
$nameCat = $result["name"];

$result = $query->fetchAll(PDO::FETCH_ASSOC);
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
    }

    .app {
        text-align: center;
        overflow-x: hidden; /* تم تغييرها إلى hidden لمنع ظهور شريط التمرير */
        padding-left: 60px;
    }

    .movie-list {
        display: flex;
        transition: transform 0.5s ease;
        overflow-x: scroll; /* إظهار شريط السكرول دائمًا */
        -ms-overflow-style: none;  
        scrollbar-width: thin; 
        scrollbar-color: #888 #f0f0f0;
    }

    .movie-list::-webkit-scrollbar {
        height: 12px;
    }

    .movie-list::-webkit-scrollbar-track {
        background: #f0f0f0;
        border-radius: 10px;
    }

    .movie-list::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .movie-list::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .movie-card {
        flex: 0 0 auto;
        width: 200px;
        margin: 10px; /* إضافة مسافة بين كل بطاقة فيديو */
        padding-bottom: 20px; /* إضافة مسافة بين البطاقات */
        border: 1px solid #ccc;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .movie-card img {
        width: 100%;
        height: 200px; 
        object-fit: cover; 
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

    .movie-card h3 {
        margin: 10px 0;
    }

    .movie-card:hover {
        transform: scale(1.05);
    }

    h1 {
        color: #fff;
        text-align: start;
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

    .total-entities {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f0f0f0;
        border-radius: 10px;
        padding: 20px;
        font-size: 24px;
        width: 150px;
        margin: 20px auto;
        transition: background-color 0.3s;
    }

    .total-entities:hover {
        background-color: #e0e0e0;
    }

    .total-entities i {
        margin-right: 10px;
    }
    </style>
</head>
<body>
    <div class="app">
        <h1>Your Next Watch..</h1>
        <h3 style="color:gray"><?php echo $nameCat; ?></h3>
        <div class="movie-list">
            <?php if (is_array($result) && count($result) > 0): ?>
                <?php foreach($result as $entity): ?>
                    <a href="entity.php?id=<?= $entity['id'] ?>">
                        <div class="movie-card">
                            <img src="<?= $entity['thumbnail'] ?>" alt="<?= $entity['name'] ?>">
                        </a>
                        <h3><?= $entity['name'] ?></h3>
                        <button onclick="WatchLater(<?php echo $entity['id']; ?>)"><i class="fas fa-plus"></i> Watch Later</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color:red;">You Don't Watch Yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
    document.querySelector('.movie-list').addEventListener('mouseleave', function() {
        this.style.transform = 'translateX(0)'; 
    });
    </script>
</body>
</html>
