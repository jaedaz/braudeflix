<?php

require_once("Account.php");

class PreviewProvider {


    private $con, $username,$user;

    public function __construct($con, $username) {
        $this->con = $con;
        $this->username = $username;
        $this->user = new Account($con);
    }

    public function createCategoryPreviewVideo($categoryId) {
        $entitiesArray = EntityProvider::getEntities($this->con, $categoryId, 1);

        if(sizeof($entitiesArray) == 0) {
            ErrorMessage::show("No TV shows to display");
        }

        return $this->createPreviewVideo($entitiesArray[0]);
    }

    public function createTVShowPreviewVideo() {
        $entitiesArray = EntityProvider::getTVShowEntities($this->con, null, 1);

        if(sizeof($entitiesArray) == 0) {
            ErrorMessage::show("No TV shows to display");
        }

        return $this->createPreviewVideo($entitiesArray[0]);
    }

    public function createMoviesPreviewVideo() {
        $entitiesArray = EntityProvider::getMoviesEntities($this->con, null, 1);

        if(sizeof($entitiesArray) == 0) {
            ErrorMessage::show("No movies to display");
        }

        return $this->createPreviewVideo($entitiesArray[0]);
    }

    public function createPreviewVideo($entity) {
        
        if($entity == null) {
            $entity = $this->getRandomEntity();
        }

        $id = $entity->getId();
        $name = $entity->getName();
        $preview = $entity->getPreview();
        $thumbnail = $entity->getThumbnail();

        $videoId = VideoProvider::getEntityVideoForUser($this->con, $id, $this->username);
        $video = new Video($this->con, $videoId);
        
        $inProgress = $video->isInProgress($this->username);
        $playButtonText = $inProgress ? "Continue watching" : "Play";

        $seasonEpisode = $video->getSeasonAndEpisode();
        $subHeading = $video->isMovie() ? "" : "<h4>$seasonEpisode</h4>";

        return "<div class='previewContainer'>

                    <img src='$thumbnail' class='previewImage' hidden>

                    <video autoplay muted class='previewVideo' onended='previewEnded()'>
                        <source src='$preview' type='video/mp4'>
                    </video>

                    <div class='previewOverlay'>
                        
                        <div class='mainDetails'>
                            <h3>$name</h3>
                            $subHeading
                            <div class='buttons'>
                                <button onclick='watchVideo($videoId)'><i class='fas fa-play'></i> $playButtonText</button>
                                <button onclick='volumeToggle(this)'><i class='fas fa-volume-mute'></i></button>
                            </div>

                        </div>

                    </div>
        
                </div>";

    }

    public function createEntityPreviewSquare($entity) {
        $id = $entity->getId();
        $thumbnail = $entity->getThumbnail();
        $name = $entity->getName();

        return "<a href='entity.php?id=$id'>
                    <div class='previewContainer small'>
                        <img src='$thumbnail' title='$name'>
                    </div>
                </a>";
    }

    private function getRandomEntity() {

        $entity = EntityProvider::getEntities($this->con, null, 1);
        return $entity[0];
    }



    public function getMostViewedEntities($limit = 10) {
        $query = $this->con->prepare("
            SELECT entityId, SUM(views) AS total_views
            FROM videos
            WHERE isMovie != 1
            AND views != 0
            GROUP BY entityId
            ORDER BY total_views DESC
            LIMIT :limit;
        ");
        $query->bindValue(":limit", $limit, PDO::PARAM_INT);
        $query->execute();
    
        $entities = [];
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $entities[] = new Entity($this->con, $row["entityId"]);
        }
    
        return $entities;
    }
    

    public function getMostViewedMovies() {
        $sql = "SELECT v.id, v.title, v.views, e.thumbnail, e.name as categoryName
                FROM videos v
                JOIN entities e ON v.entityId = e.id
                WHERE isMovie = 1 AND v.views != 0
                ORDER BY v.views DESC
                LIMIT 10";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $entities = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $entities;
    }
    





    


    // public function createBestPreviewVideo() {
    //     $entities = $this->getMostViewedEntities();
    
    //     if(empty($entities)) {
    //         return "<div class='previewContainer'>No popular series found.</div>";
    //     }
    
    //     $output = "<div class='swiper-container'>
    //     <h1 style='color:#fff'>There is most watched series</h1>
    //                     <div class='swiper-wrapper'>";
    
    //     foreach($entities as $entity) {
    //         $id = $entity->getId();
    //         $name = $entity->getName();
    //         $preview = $entity->getPreview();
    //         $thumbnail = $entity->getThumbnail();
    //         $categoryId = $entity->getCategoryId();
    
    //         $st = $this->con->prepare("SELECT name FROM categories WHERE id = :id");
    //         $st->bindValue(":id", $categoryId);
    //         $st->execute();
    //         $row = $st->fetch(PDO::FETCH_ASSOC);
    //         $catName = $row['name'];
    
    //         $videoId = VideoProvider::getEntityVideoForUser($this->con, $id, $this->username);
    //         $video = new Video($this->con, $videoId);
    
    //         $inProgress = $video->isInProgress($this->username);
    //         $playButtonText = $inProgress ? "Continue watching" : "Play";
    
    //         $seasonEpisode = $video->getHeightsSeasonAndEpisode($id);
    //         $subHeading = $video->isMovie() ? "" : "<h4 style='color:#fff'>$seasonEpisode</h4>";
    
    //         $output .= "<div class='swiper-slide'>
    //                         <div class='previewContainer1'>
    //                             <img style='width:100%; opacity: 0.5' src='$thumbnail'>
    //                             <div class='previewOverlay1'>
    //                                 <div class='mainDetails1'>
    //                                     <h3 style='color: #fff'>Most popular's entity: $name, EntityId: $id</h3>
    //                                     $subHeading
    //                                     <span style='color:#fff;font-size:30px'>Category Name: $catName</span>
    //                                     <br>";
    //         if(!$this->user->isAdmin($this->username)) {
                
    //             $output .= " <h1 style='color:red'>To watch Click beyond</h1> <br> <a href='entity.php?id=$id' style='color:red;font-size:24px'>Watch Series</a>";
    //         }else{
    //             $output .= "<a href='DeleteVideo.php?id=$id' style='color:yellow;font-size:24px'>Manage it</a>";
    //         }
    //         $output .= "</div>
    //                             </div>
    //                         </div>
    //                     </div>";
    //     }
    
    //     $output .= "</div>
    //                     <div class='swiper-button-next'></div>
    //                     <div class='swiper-button-prev'></div>
    //                     <div class='swiper-pagination'></div>
    //                 </div>";
    
    //     return $output;
    // }
    
    




 








    // public function createBestPreviewMovie() {
    //     $entities = $this->getMostViewedMovies();
    
    //     if (empty($entities)) {
    //         return "<div class='previewContainer1'>No popular series found.</div>";
    //     }
    
    //     $output = "<div class='swiper-container'>
    //                         <h1 style='color:#fff'>There is most watched movie</h1>
    //                 <div class='swiper-wrapper'> ";

    //     foreach ($entities as $entity) {
    //         $id = $entity['id'];
    //         $name = $entity['title'];
    //         $thumbnail = $entity['thumbnail'];
    //         $categoryName = $entity['categoryName'];
    
        
    //         $watchLink = "watch.php?id=$id"; 
    
    //         $output .= "<div class='swiper-slide'>
    //                         <h1 style='color:#fff'>There is the best movie has watched</h1>
    //                         <div class='previewContainer1'>
    //                             <img style='width:100%; opacity: 0.5' src='$thumbnail'>
    //                             <div class='previewOverlay1'>
    //                                 <div class='mainDetails1'>
    //                                     <h3 style='color: white'>Most popular entity: $name, VideoId: $id</h3>
    //                                     <span style='color:#fff;font-size:30px'>Entity Name: $categoryName</span>
    //                                     <br>";
    //         if (!$this->user->isAdmin($this->username) == true) {
    //             $output .= "<h1 style='color:red'>To watch Click beyond</h1> <br> <a href='$watchLink' style='color:red;font-size:24px'>Watch Movie</a>";
    //         }else{
    //             $output .= "<a href='DeleteVideo.php?id=$id' style='color:yellow;font-size:24px'>Manage it</a>";
    //         }
    //         $output .= "</div>
    //                             </div>
    //                         </div>
    //                     </div>";
    //     }
    
    //     $output .= "</div>
    //                 <div class='swiper-button-next'></div>
    //                 <div class='swiper-button-prev'></div>
    //                 <div class='swiper-pagination'></div>
    //             </div>";
    
    //     return $output;
    // }
    
}



