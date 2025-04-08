
<?php
require_once("../includes/config.php");

if (isset($_POST['id'])) {
    $id = $_POST['id'];


    $query = $con->prepare("DELETE FROM videos WHERE id = :id");
    $query->bindValue(":id", $id);
    
    if ($query->execute()) {
        echo "Episode deleted successfully";
    } else {
        echo "Failed to delete episode";
    }
} else {
    echo "No ID received";
}
?>
