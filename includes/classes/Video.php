<?php

class Video {
    private $con, $sqlData, $entity;

    public function __construct($con, $input) {
        $this->con = $con;

        if(is_array($input)) {
            $this->sqlData = $input;
        }
        else {
            $query = $this->con->prepare("SELECT * FROM videos WHERE id=:id");
            $query->bindValue(":id", $input);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }

        $this->entity = new Entity($con, $this->sqlData["entityId"]);
    }

    public function getId() {
        return $this->sqlData["id"];
    }

    public function getTitle() {
        return $this->sqlData["title"];
    }

    public function getDescription() {
        return $this->sqlData["description"];
    }

    public function getFilePath() {
        return $this->sqlData["filePath"];
    }

    public function getThumbnail() {
        return $this->entity->getThumbnail();
    }

    public function getEpisodeNumber() {
        return $this->sqlData["episode"];
    }

    public function getSeasonNumber() {
        return $this->sqlData["season"];
    }

    public function getEntityId() {
        return $this->sqlData["entityId"];
    }

    public function incrementViews() {
        $query = $this->con->prepare("UPDATE videos SET views=views+1 WHERE id=:id");
        $query->bindValue(":id", $this->getId());
        $query->execute();
    }

    public function getSeasonAndEpisode() {
        if($this->isMovie()) {
            return;
        }

        $season = $this->getSeasonNumber();
        $episode = $this->getEpisodeNumber();

        return "Season $season, Episode $episode";
    }



    public function getHeightsSeasonAndEpisode($entityId) {
        if($this->isMovie()) {
            return "<h1>This Is Best Movie</h1>";
        }

        $season = $this->bestSeason($entityId);
        $episode = $this->bestEpisode($entityId);
        
        $bestEpisode = $episode["episode"];
        $bestSeason = $season["season"];
        $views = $episode["total_views"];

        return "<span style='color:red'>Popular</span> Season $bestSeason, <span style='color:red'>Popular</span> Episode $bestEpisode <br>Episode Has Views $views";
    }




    //for movies
    public function getHeightsSeasonAndMovie($entityId) {
    
        $season = $this->bestSeasonMovie($entityId);
            

        $bestSeason = $season["season"];
        $views = $season["total_views"];


        return "<span style='color:red'>Popular</span> Season $bestSeason, <br>Episode Has Views:  $views";
    }



    private function bestSeason($entityId) {
        $stmt = $this->con->prepare("
            SELECT season, SUM(views) AS total_views
            FROM videos
            WHERE entityId = :entityId AND
            isMovie != 1
            GROUP BY season
            ORDER BY total_views DESC
            LIMIT 1;
        ");
        $stmt->bindValue(":entityId", $entityId);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    

    private function bestSeasonMovie($entityId) {
        $stmt = $this->con->prepare("
            SELECT season, SUM(views) AS total_views
            FROM videos
            WHERE entityId = :entityId AND
            isMovie = 1
            GROUP BY season
            ORDER BY total_views DESC
            LIMIT 1;
        ");
        $stmt->bindValue(":entityId", $entityId);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    
    
    private function bestEpisode($entityId) {
        $stmt = $this->con->prepare("
            SELECT episode,views, SUM(views) AS total_views
            FROM videos
            WHERE entityId = :entityId AND
            isMovie != 1
            GROUP BY episode
            ORDER BY total_views DESC
            LIMIT 1;
        ");
        $stmt->bindValue(":entityId", $entityId);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    


    public function isMovie() {
        return $this->sqlData["isMovie"] == 1;
    }

    

    public function isInProgress($username) {
        $query = $this->con->prepare("SELECT * FROM videoProgress
                                    WHERE videoId=:videoId AND username=:username");

        $query->bindValue(":videoId", $this->getId());
        $query->bindValue(":username", $username);
        $query->execute();

        return $query->rowCount() != 0;
    }




    public function hasSeen($username) {
        $query = $this->con->prepare("SELECT * FROM videoProgress
                                    WHERE videoId=:videoId AND username=:username
                                    AND finished=1");

        $query->bindValue(":videoId", $this->getId());
        $query->bindValue(":username", $username);
        $query->execute();

        return $query->rowCount() != 0;
    }
}
?>