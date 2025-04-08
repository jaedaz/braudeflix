<?php 
require_once("../includes/config.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_POST["videoId"])) {
    $videoId = $_POST["videoId"];
    

    if (empty($videoId)) {
        echo "Entity ID is empty.";
        exit;
    }

    // تحقق من اتصال قاعدة البيانات
    if ($con == null) {
        echo "Database connection error.";
        exit;
    }

    try {
        $sql = $con->prepare("DELETE FROM  MoviesAndVideos WHERE videoId = :id");
        $sql->bindValue(":id", $videoId, PDO::PARAM_INT);
        $sql->execute();

        if($sql->rowCount() > 0) {
            echo "Successes";
        } else {
            echo "No rows affected.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Entity ID not provided.";
}
?>
