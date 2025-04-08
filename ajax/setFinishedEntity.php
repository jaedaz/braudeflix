<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("../includes/config.php");
require_once("../includes/classes/Video.php");



if(isset($_POST["videoId"]) && isset($_POST["username"])) {
    $videoId = $_POST["videoId"];
    $username = $_POST["username"];

    

    try {
        $sql = $con->prepare("SELECT
                    COUNT(*) AS totalVideos, SUM(vp.finished) AS finishedVideos
                    FROM videos v
                LEFT JOIN videoProgress vp ON v.id = vp.videoId AND vp.username = :username 
            WHERE v.entityId = (
                SELECT entityId FROM videos WHERE id = :videoId 
            ) AND isMovie = 0
        ");
        $sql->bindValue(":username", $username, PDO::PARAM_STR);
        $sql->bindValue(":videoId", $videoId, PDO::PARAM_INT);
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_ASSOC);


        //get entityId
        $Found = $con->prepare("SELECT entityId FROM videos WHERE id = :id");
        $Found->bindValue(":id",$videoId);
        $Found->execute();
        $arr = $Found->fetch(PDO::FETCH_ASSOC);
        $entityId = $arr["entityId"];



        if ($result && $result['totalVideos'] > 0 && $result['totalVideos'] == $result['finishedVideos']) {
            

            $sqlFinish = $con->prepare("INSERT INTO HasFinish (entityId, username, has) VALUES (:entityId, :username, 1)
                                        ON DUPLICATE KEY UPDATE has = 1");
            $sqlFinish->bindValue(":entityId", $entityId, PDO::PARAM_INT);
            $sqlFinish->bindValue(":username", $username, PDO::PARAM_STR);
            $sqlFinish->execute();

            echo "Entity marked as finished.";
        } else {
            echo "Entity not marked as finished.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

} else {
    echo "No videoId or username passed into file";
}
?>
