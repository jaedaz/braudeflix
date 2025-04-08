<?php
require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Constants.php");
require_once("includes/classes/Account.php");
require_once("includes/headerAdmin.php");

$account = new Account($con);

$id = $_GET['id'];

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$stmt = $con->prepare("SELECT * FROM videos WHERE id = :id");
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if(isset($_POST["AddVideo"])){
    $title = $_POST["Title"];
    $description = $_POST["Description"];
    $isMovie = $_POST["isMovie"];
    $releaseDate = $_POST["releaseDate"];
    $duration = $_POST["duration"];
    $season = $_POST["season"];
    $episode = $_POST["episode"];

    // Handle video file upload
    $video_name = $_FILES['Video']['name'];
    $video_temp = $_FILES['Video']['tmp_name'];
    $target_video = "entities/videos/" . $video_name;

    if (move_uploaded_file($video_temp, $target_video)) {
        // ملف الفيديو تم تحميله بنجاح
    } else {
        echo "Error uploading video.";
    }

    try {
        $stmt = $con->prepare("UPDATE videos SET title = :title, description = :description, isMovie = :isMovie, releaseDate = :releaseDate, duration = :duration, season = :season, episode = :episode, filePath = :filePath WHERE id = :id");
        $stmt->bindValue(":title", $title, PDO::PARAM_STR);
        $stmt->bindValue(":description", $description, PDO::PARAM_STR);
        $stmt->bindValue(":isMovie", $isMovie, PDO::PARAM_INT);
        $stmt->bindValue(":releaseDate", $releaseDate, PDO::PARAM_STR);
        $stmt->bindValue(":duration", $duration, PDO::PARAM_INT);
        $stmt->bindValue(":season", $season, PDO::PARAM_INT);
        $stmt->bindValue(":episode", $episode, PDO::PARAM_INT);
        $stmt->bindValue(":filePath", $target_video, PDO::PARAM_STR);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Video updated successfully.";
        } else {
            echo "Error updating video.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<div class="container pt-5 mt-10" style="height: 80vh; overflow-y: auto;">
    <div class="row justify-content-center align-items-center" style="height: 100vh;">
        <div class="col-md-6">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="text" name="Title" class="form-control" aria-describedby="emailHelp" placeholder="Title Movie" value="<?php echo $result['title']; ?>">
                </div>

                <hr style="background-color:#fff">
                <div class="form-group">
                    <input type="text" name="Description" class="form-control" aria-describedby="emailHelp" placeholder="Description Movie" value="<?php echo $result['description']; ?>">
                </div>

                <hr style="background-color:#fff">
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFile" name="Video" required>
                        <label class="custom-file-label" for="customFile">Upload Your Movie</label>
                    </div>
                </div>

                <hr style="background-color:#fff">
                <div class="form-group">
                    <label style="color:#fff">Is Movie?</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" id="movieYes" type="radio" name="isMovie" value="1" <?php echo ($result['isMovie'] == 1) ? 'checked' : ''; ?>>
                        <label style="color:#fff" class="form-check-label" for="movieYes">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="isMovie" id="movieNo" value="0" <?php echo ($result['isMovie'] == 0) ? 'checked' : ''; ?>>
                        <label style="color:#fff" class="form-check-label" for="movieNo">No</label>
                    </div>
                </div>

                <hr style="background-color:#fff">
                <div class="form-group">
                    <label style="color:#fff" for="releaseDate">Release Date:</label>
                    <input type="date" id="releaseDate" name="releaseDate" class="form-control" value="<?php echo $result['releaseDate']; ?>">
                </div>

                <hr style="background-color:#fff">
                <div class="form-group">
                    <label style="color:#fff" for="duration">Duration (in minutes)</label>
                    <input type="text" id="duration" name="duration" class="form-control" min="0" step="1" value="<?php echo $result['duration']; ?>">
                </div>

                <hr style="background-color:#fff">
                <div class="form-group">
                    <label style="color:#fff" for="season">Season</label>
                    <input type="number" id="season" name="season" class="form-control" min="0" step="1" value="<?php echo $result['season']; ?>">
                </div>

                <hr style="background-color:#fff">
                <h5 style="color:#fff">If You Try To Add A Movie You Shouldn't Have To Add Episode</h5>
                <div class="form-group">
                    <label style="color:#fff" for="episode">Episode</label>
                    <input type="number" id="episode" name="episode" class="form-control" min="0" step="1" value="<?php echo $result['episode']; ?>">
                </div>

                <hr style="background-color:#fff">
                <button type="submit" class="btn btn-primary form-control" name="AddVideo">Update</button>
            </form>
        </div>
    </div>
</div>
