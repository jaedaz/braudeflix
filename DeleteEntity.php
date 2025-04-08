<?php require_once("includes/headerAdmin.php"); ?>

<?php  
$sql = $con->prepare("SELECT name FROM categories");
$sql->execute();
$categories = $sql->fetchAll(PDO::FETCH_ASSOC);

$sql = $con->prepare("SELECT id, name FROM entities");
$sql->execute();
$entities = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<?php 
if(isset($_POST["DeleteEntity"])){
    $entityToDelete = $_POST["entity"];

    $entityIdQuery = $con->prepare("SELECT id FROM entities WHERE name = :name");
    $entityIdQuery->bindValue(":name", $entityToDelete);
    $entityIdQuery->execute();
    $entityRow = $entityIdQuery->fetch(PDO::FETCH_ASSOC);
    $entityId = $entityRow['id'];

    $deleteVideos = $con->prepare("DELETE FROM videos WHERE entityId = :id");
    $deleteVideos->bindValue(":id", $entityId); 
    $deleteVideos->execute();

    $deleteStmt = $con->prepare("DELETE FROM entities WHERE id = :id");
    $deleteStmt->bindValue(":id", $entityId);
    $deleteStmt->execute();
}
?>

<!-- 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); -->

<div class="container pt-4">
    <div class="row justify-content-center align-items-center" style="height: 100vh;">
        <div class="col-md-6">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <select class="form-control" id="entitySelect" name="entity">
                        <option value="">Select Name</option>
                        <?php foreach ($entities as $entity): ?>
                            <option value="<?php echo htmlspecialchars($entity['name']); ?>" data-id="<?php echo $entity['id']; ?>"><?php echo htmlspecialchars($entity['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <hr style="background-color:#fff">
                <button type="submit" class="btn btn-primary form-control" name="DeleteEntity">Delete</button>
            </form>
            <button class="btn btn-danger form-control" id="deleteEpisodeBtn" style="margin-top: 10px;">Delete Episode</button>
        </div>
    </div>
</div>

<script>
    document.getElementById('deleteEpisodeBtn').addEventListener('click', function() {
        var entitySelect = document.getElementById('entitySelect');
        var selectedOption = entitySelect.options[entitySelect.selectedIndex];
        var entityId = selectedOption.getAttribute('data-id');
        if(entityId) {
            window.location.href = 'DeleteVideo.php?id=' + entityId;
        } else {
            alert('Please select an entity.');
        }
    });
</script>
</body>
</html>
