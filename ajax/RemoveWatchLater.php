<?php 
require_once("../includes/config.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_POST["entityId"])) {
    $entityId = $_POST["entityId"];
    
    // تحقق من قيمة entityId
    if (empty($entityId)) {
        echo "Entity ID is empty.";
        exit;
    }

    // تحقق من اتصال قاعدة البيانات
    if ($con == null) {
        echo "Database connection error.";
        exit;
    }

    try {
        $sql = $con->prepare("DELETE FROM  ContinueAndAdd WHERE entityId = :id");
        $sql->bindValue(":id", $entityId, PDO::PARAM_INT);
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
