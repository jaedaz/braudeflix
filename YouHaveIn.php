<?php 
require_once("includes/config.php");
require_once("includes/classes/Entity.php");


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = $_SESSION["userLoggedIn"]; 

$sql = "SELECT COUNT(*) as totalEntities
        FROM entities e
        INNER JOIN ContinueAndAdd ca ON e.id = ca.entityId
        WHERE ca.username = :username";
        
$stmt = $con->prepare($sql);
$stmt->bindValue(":username", $username, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$totalEntities = $result['totalEntities'];
?>

<div class="app">
    <h1>Your List To Watch Later</h1>
    <div class="total-entities" onclick="location.href='WatchLaterList.php';" style="cursor: pointer;">
        <i class="fas fa-arrow-up">Archive</i>
        
    </div>
</div>
