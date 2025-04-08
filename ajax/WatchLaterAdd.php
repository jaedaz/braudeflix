<?php 
require_once("../includes/config.php");
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = $_SESSION["userLoggedIn"];

if(isset($_POST["entityId"])) {
    $entityId = $_POST["entityId"];
    

    if (empty($entityId)) {
        echo "Entity ID is empty.";
        exit;
    }

    if ($con == null) {
        echo "Database connection error.";
        exit;
    }

    try {

        $checkSql = $con->prepare("SELECT COUNT(*) AS count FROM ContinueAndAdd WHERE entityId = :entityId");
        $checkSql->bindValue(":entityId", $entityId, PDO::PARAM_INT);
        $checkSql->execute();
        $row = $checkSql->fetch(PDO::FETCH_ASSOC);

        if ($row['count'] == 0) {

            $insertSql = $con->prepare("INSERT INTO ContinueAndAdd (username, entityId) VALUES (:username, :entityId)");
            $insertSql->bindValue(":username", $username, PDO::PARAM_STR);
            $insertSql->bindValue(":entityId", $entityId, PDO::PARAM_INT);
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
