<?php require_once("includes/headerAdmin.php") ?>

<?php  



$sql = $con->prepare("SELECT name FROM entities");
$sql->execute();
$entities = $sql->fetchAll(PDO::FETCH_ASSOC);

?>


<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if(isset($_POST["AddVideo"])){
    $title = $_POST["Title"];
    $Description = $_POST["Description"];
    $entity = $_POST["entity"];
    $isMovieValue = $_POST["isMovie"];
    $releaseDate = $_POST["releaseDate"];
    $duration = $_POST["duration"];
    $season = $_POST["season"];
    $episode = $_POST["episode"];



        // Handle preview file upload
        $Video_name = $_FILES['Video']['name'];
        $Video_temp = $_FILES['Video']['tmp_name'];
        $target_Video = "entities/videos/" . $Video_name;
    
        move_uploaded_file($Video_temp, $target_Video);



    $entityId = $con->prepare("SELECT id FROM entities WHERE name = :name");
    $entityId->bindParam(':name', $entity);
    $entityId->execute();
    $result = $entityId->fetch(PDO::FETCH_ASSOC);
    $id = $result['id'];





    //check if the movie is Movie
    if($isMovieValue == 1){
        
        $query = $con->prepare("SELECT title FROM videos WHERE title = :title");
        $query->bindValue(":title",$title);
        $query->execute();

        if($query->rowCount() == 0){
            $season = 1;
            $episode = 0;
            Insert($con,$title,$Description,$target_Video,$isMovieValue,$releaseDate,$duration, $season, $episode,$id);
        
        }else{
            $episode = 0;
            $qu = $con->prepare("SELECT MAX(season) AS highest_season FROM videos WHERE title = :title AND isMovie = 1 AND entityId = :entityId");
            $qu->bindValue(":title", $title);
            $qu->bindValue(":entityId", $id);
            $qu->execute();

            $row = $qu->fetch(PDO::FETCH_ASSOC);
            $highest_seasonMovie = $row["highest_season"];
            $highest_seasonMovie += 1;

            Insert($con,$title,$Description,$target_Video,$isMovieValue,$releaseDate,$duration, $highest_seasonMovie, $episode,$id);

        }

    }else{





    //find the season
    $qu = $con->prepare("SELECT season FROM videos WHERE season = :season AND entityId = :id");
    $qu->bindValue(":season", $season);
    $qu->bindValue(":id", $id);
    $qu->execute();

// Check if there are any rows returned by the query
if ($qu->rowCount() == 0) {
    // If no rows returned, it means the specified season doesn't exist
    // Retrieve the highest season number from the database
    $qu = $con->prepare("SELECT MAX(season) AS highest_season FROM videos WHERE season = :season AND entityId = :id");
    $qu->bindValue(":season",$season);
    $qu->bindValue(":id",$id);
    $qu->execute();
    $row = $qu->fetch(PDO::FETCH_ASSOC);
    $highest_season = $row['highest_season'];
    $highest_season += 1;
    $episode = 1;


    Insert($con,$title,$Description,$target_Video,$isMovieValue,$releaseDate,$duration, $highest_season, $episode,$id);


} else {
    $qu1 = $con->prepare("SELECT episode FROM videos WHERE season = :season AND episode = :episode AND entityId = :id");
    $qu1->bindValue(":season", $season);
    $qu1->bindValue(":episode", $episode);
    $qu1->bindValue(":id", $id);
    $qu1->execute();
    
    if ($qu1->rowCount() > 0) {
        $qu2 = $con->prepare("SELECT MAX(episode) AS highest_episode FROM videos WHERE season = :season AND entityId = :id");
        $qu2->bindValue(":season",$season);
        $qu2->bindValue(":id",$id);
        $qu2->execute();
        $row1 = $qu2->fetch(PDO::FETCH_ASSOC);
        $highest_episode = $row1["highest_episode"];
        $highest_episode += 1;
        $episode =  $highest_episode;
        Insert($con,$title,$Description,$target_Video,$isMovieValue,$releaseDate,$duration, $season,  $highest_episode,$id);


    }else{
        Insert($con,$title,$Description,$target_Video,$isMovieValue,$releaseDate,$duration, $season,  $episode,$id);
    }


}

}




}


function Insert($con,$title,$Description,$target_Video,$isMovieValue,$releaseDate, $duration,$season, $highest_episode,$id){

    $query = $con->prepare("INSERT INTO videos (title,description,filePath,isMovie,releaseDate,duration,season,episode,entityId) VALUES  (:title,:description,:filePath,:isMovie,:releaseDate,:duration,:season,:episode,:entityId)");
    $query->bindValue(":title", $title);
    $query->bindValue(":description", $Description);
    $query->bindValue(":filePath", $target_Video);
    $query->bindValue(":isMovie", $isMovieValue);
    $query->bindValue(":releaseDate", $releaseDate);
    $query->bindValue(":duration", $duration);
    $query->bindValue(":season", $season);
    $query->bindValue(":episode", $highest_episode);
    $query->bindValue(":entityId", $id);
    
    $query->execute();

}




?>


<div class="container pt-5 mt-10" style="height: 80vh; overflow-y: auto;">
    <div class="row justify-content-center align-items-center" style="height: 100vh;">
        <div class="col-md-6">
            <h3 style="color:#fff">Hello Here You Have To Add Your Videos Or Movies!! If You Need New Entity Then go to <a href="CreateEntity.php">CreateEntity</a> To Add your Entity</h3>
            <form method="POST" enctype="multipart/form-data">


            <div class="form-group">
                    <h4 style="color:red">You must choose witch entityId you need</h4>
                    <select class="form-control" name="entity">
    <option value="">Select Name</option>
    <?php foreach ($entities as $entity): ?>
        <option value="<?php echo $entity['name']; ?>"><?php echo $entity['name']; ?></option>
    <?php endforeach; ?>
</select>
                </div>      
    

                <hr style="background-color:#fff">



            <div class="form-group">
                    <input type="text" name="Title" class="form-control" aria-describedby="emailHelp" placeholder="Title Movie">
            </div>


            <hr style="background-color:#fff">
            <div class="form-group">
                    <input type="text" name="Description" class="form-control" aria-describedby="emailHelp" placeholder="Description Movie">
            </div>


            <hr style="background-color:#fff">
            <div class="form-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" id="customFile"  name="Video" required>
        <label class="custom-file-label" for="customFile">Upload You'r Movie</label>
    </div>
</div>



<hr style="background-color:#fff">
            <div class="form-group">
    <label style="color:#fff">Is Movie?</label><br>
    <div class="form-check form-check-inline">
        <input class="form-check-input" id="movieYes" type="radio" name="isMovie" value="1">
        <label style="color:#fff" class="form-check-label" for="movieYes">Yes</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="isMovie" id="movieNo"  value="0">
        <label style="color:#fff" class="form-check-label" for="movieNo">No</label>
    </div>
</div>


<hr style="background-color:#fff">
<div class="form-group">
    <label style="color:#fff" for="releaseDate">Release Date:</label>
    <input type="date" id="releaseDate" name="releaseDate" class="form-control">
</div>
<hr style="background-color:#fff">
<div class="form-group">
    <label style="color:#fff" for="duration">Duration (in minutes)</label>
    <input type="text" id="duration"  name="duration" class="form-control" min="0" step="1">
</div>



<hr style="background-color:#fff">
<div class="form-group">
    <label style="color:#fff" for="season">Season</label>
    <input type="number" id="season"  name="season" class="form-control" min="0" step="1">
</div>
<hr style="background-color:#fff">
<h5 style="color:#fff">If You Try To Add An Movie You Should't Have To Add Episode</h5>
<div class="form-group">
    <label style="color:#fff" for="episode">Episode</label>
    <input type="number" id="episode"  name="episode" class="form-control" min="0" step="1">
</div>

        
    <hr style="background-color:#fff">
                <button type="submit" class="btn btn-primary form-control" name="AddVideo">Add</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>



