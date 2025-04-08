<?php
class SeasonProvider {
    private $con, $username;

    public function __construct($con, $username) {
        $this->con = $con;
        $this->username = $username;
    }

    public function create($entity) {
        $seasons = $entity->getSeasons();

        if(sizeof($seasons) == 0) {
            return;
        }

        $seasonsHtml = "";
        foreach($seasons as $season) {
            $seasonNumber = $season->getSeasonNumber();

            $videosHtml = "";
            foreach($season->getVideos() as $video) {
                $videosHtml .= $this->createVideoSquare($video);
            }


            $seasonsHtml .= "<div class='season'>
                                    <h3>Season $seasonNumber</h3>
                                    <div class='videos'>
                                        $videosHtml
                                    </div>
                                </div>";
        }

        return $seasonsHtml;
    }


    public function createToDelete($entity) {
        $seasons = $entity->getSeasons();
        $i = 0;
        if (sizeof($seasons) == 0) {
            return;
        }
    
        $seasonsHtml = "";
        foreach ($seasons as $season) {
            $seasonNumber = $season->getSeasonNumber();
            $i = 0;
            $videosHtml = "";
            foreach ($season->getVideos() as $video) {
                $i++;
                $videosHtml .= "<div class='video-containers'>" . 
                            "<h3 style='color:#fff'>Episode $i</h3>" .
                            $this->createVideoSquare($video) . 
                            "<div class='button-container'>" .
                            "<button onclick='DeleteEpisode({$video->getId()})' class='delete-button'>Delete</button>" .
                            "<button onclick='UpdateEpisode({$video->getId()})' class='delete-button'>Update</button>" .
                            "</div></div>";
            }
    
            $seasonsHtml .= "<div class='seasons'>
                                <h3 style='color:#fff'>Season $seasonNumber</h3>
                                <div class='videoss'>
                                    $videosHtml
                                </div>
                            </div>";
        }
    
        return $seasonsHtml;
    }
    
    
    
    
    

    private function createVideoSquare($video) {
        $id = $video->getId();
        $thumbnail = $video->getThumbnail();
        $name = $video->getTitle();
        $description = $video->getDescription();
        $episodeNumber = $video->getEpisodeNumber();
        $hasSeen = $video->hasSeen($this->username) ? "<i class='fas fa-check-circle seen'></i>" : "";

        return "<a href='watch.php?id=$id'>
                    <div class='episodeContainer'>
                        <div class='contents'>

                            <img src='$thumbnail'>

                            <div class='videoInfo'>
                                <h4>$episodeNumber. $name</h4>
                                <span>$description</span>
                            </div>

                            $hasSeen

                        </div>
                    </div>
                </a>";
    }
}
?>