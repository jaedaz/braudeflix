<?php 
require_once("../includes/config.php");
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = $_SESSION["userLoggedIn"];

if(isset($_POST["videoId"])) {
    $videoId = $_POST["videoId"];
    

    if (empty($videoId)) {
        echo "Entity ID is empty.";
        exit;
    }

    if ($con == null) {
        echo "Database connection error.";
        exit;
    }

    try {

        $checkSql = $con->prepare("SELECT COUNT(*) AS count FROM MoviesAndVideos WHERE videoId = :videoId");
        $checkSql->bindValue(":videoId", $videoId, PDO::PARAM_INT);
        $checkSql->execute();
        $row = $checkSql->fetch(PDO::FETCH_ASSOC);

        if ($row['count'] == 0) {

            $insertSql = $con->prepare("INSERT INTO MoviesAndVideos (username, videoId) VALUES (:username, :videoId)");
            $insertSql->bindValue(":username", $username, PDO::PARAM_STR);
            $insertSql->bindValue(":videoId", $videoId, PDO::PARAM_INT);
            $insertSql->execute();

            if ($insertSql->rowCount() > 0) {
                echo "Success";
            } else {
                echo "No rows affected.";
            }
        } else {

            echo "Entity ID already exists in ContinueAndAdd table.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Entity ID not provided.";
}
?>
