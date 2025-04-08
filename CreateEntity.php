<?php require_once("includes/headerAdmin.php") ?>

<?php  
$sql = $con->prepare("SELECT name FROM categories");
$sql->execute();
$categories = $sql->fetchAll(PDO::FETCH_ASSOC);


$sql = $con->prepare("SELECT name FROM entities");
$sql->execute();
$entities = $sql->fetchAll(PDO::FETCH_ASSOC);

?>


<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(isset($_POST["createEntity"])){
    $name = $_POST["Name"];
    $category = $_POST["category"];

    // Fetching category ID...
    

    foreach($entities as $entity){
        if($entity['name'] === $name){
            echo ErrorMessage::show("This name already exists!!");
            exit;
        }
    }

    $categoryId = $con->prepare("SELECT id FROM categories WHERE name = :category");
    $categoryId->bindParam(':category', $category);
    $categoryId->execute();
    $result = $categoryId->fetch(PDO::FETCH_ASSOC);
    $id = $result['id'];

    // Handle thumbnail file upload
    $thumbnail_name = $_FILES['Thumbnail']['name'];
    $thumbnail_temp = $_FILES['Thumbnail']['tmp_name'];
    $target_thumbnail = "entities/thumbnails/" . $thumbnail_name;

    // Handle preview file upload
    $preview_name = $_FILES['Preview']['name'];
    $preview_temp = $_FILES['Preview']['tmp_name'];
    $target_preview = "entities/previews/" . $preview_name;

    move_uploaded_file($thumbnail_temp, $target_thumbnail) && move_uploaded_file($preview_temp, $target_preview);




    //Insert the Information you have in the entities table
    $query = $con->prepare("INSERT INTO entities (name,thumbnail,preview,categoryId) VALUES  (:name, :thumbnail, :preview, :categoryId)");
    $query->bindValue(":name",$name);
    $query->bindValue(":thumbnail",$target_thumbnail);
    $query->bindValue(":preview",$target_preview);
    $query->bindValue(":categoryId",$id);
    
    $query->execute();



}






?>


<div class="container pt-4">
    <div class="row justify-content-center align-items-center" style="height: 100vh;">
        <div class="col-md-6">
            <h3 style="color:#fff">Hello you have first to create the entity to your TV watch!! Then go to <a href="AddMovies.php">AddMovies</a> To Add your Movies</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="text" name="Name" class="form-control" aria-describedby="emailHelp" placeholder="Name Entity">
                    <h4 style="color:red">You must choose a different name of thees names </h4>
                    <select class="form-control" id="disabledSelect" readonly>
    <option value="">Select Name</option>
    <?php foreach ($entities as $entity): ?>
        <option value="<?php echo $entity['name']; ?>"><?php echo $entity['name']; ?></option>
    <?php endforeach; ?>
</select>
                </div>
                <hr style="background-color:#fff">
            <div class="form-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" id="customFile" name="Thumbnail" required>
        <label class="custom-file-label" for="customFile">Choose Thumbnail</label>
    </div>
</div>
<hr style="background-color:#fff">
<div class="form-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" id="customFile2" name="Preview" required>
        <label class="custom-file-label" for="customFile2">Choose Preview</label>
    </div>
        
    <hr style="background-color:#fff">
                <div class="form-group">
                    <select class="form-control" name="category" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary form-control" name="createEntity">Create</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>



