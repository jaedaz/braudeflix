<?php
class Entity {

    private $con, $sqlData;

    public function __construct($con, $input) {
        $this->con = $con;

        if(is_array($input)) {
            $this->sqlData = $input;
        }
        else {
            $query = $this->con->prepare("SELECT * FROM entities WHERE id=:id");
            $query->bindValue(":id", $input);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function getId() {
        return $this->sqlData["id"];
    }

    public function getName() {
        return $this->sqlData["name"];
    }

    public function getThumbnail() {
        return $this->sqlData["thumbnail"];
    }

    public function getPreview() {
        return $this->sqlData["preview"];
    }

    public function getCategoryId() {
        return $this->sqlData["categoryId"];
    }


    public function getCategoryName(){
        $stmt = $this->con->prepare("SELECT name FROM categories WHERE id = :id");
        $stmt->bindValue(":id", $this->getCategoryId());
        $stmt->execute();
        
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        

        if ($row) {
            return $row['name'];
        } else {
            return null;
        }
    }
    

    public static function getMustCategoryView($con,$username){
        $query = $con->prepare("SELECT entityId FROM videos INNER JOIN videoProgress ON videos.id = videoProgress.videoId WHERE videoProgress.username = :username GROUP BY entityId ORDER BY COUNT(*) DESC LIMIT 1;");
        $query->bindValue(":username",$username);
        $query->execute();
        if($query){
            $result = $query->fetch(PDO::FETCH_ASSOC);
            if($result){
                return $result["entityId"];
            }else{
                return false;
            }
        }
        return false;
        
    }

    

    public function getSeasons() {
        $query = $this->con->prepare("SELECT * FROM videos WHERE entityId=:id
                                    AND isMovie=0 ORDER BY season, episode ASC");
        $query->bindValue(":id", $this->getId());
        $query->execute();

        $seasons = array();
        $videos = array();
        $currentSeason = null;
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            
            if($currentSeason != null && $currentSeason != $row["season"]) {
                $seasons[] = new Season($currentSeason, $videos);
                $videos = array();
            }

            $currentSeason = $row["season"];
            $videos[] = new Video($this->con, $row);

        }

        if(sizeof($videos) != 0) {
            $seasons[] = new Season($currentSeason, $videos);
        }

        return $seasons;
    }





}
?>