$(document).scroll(function() {
    var isScrolled = $(this).scrollTop() > $(".topBar").height();
    $(".topBar").toggleClass("scrolled", isScrolled);
})

function volumeToggle(button) {
    var muted = $(".previewVideo").prop("muted");
    $(".previewVideo").prop("muted", !muted);

    $(button).find("i").toggleClass("fa-volume-mute");
    $(button).find("i").toggleClass("fa-volume-up");
}

function previewEnded() {
    $(".previewVideo").toggle();
    $(".previewImage").toggle();
}

function goBack() {
    sessionStorage.setItem('reloadPage', 'true');
    window.history.back();
}

if (sessionStorage.getItem('reloadPage')) {
    window.onload = function() {
        sessionStorage.removeItem('reloadPage');
        window.location.reload();
    };
}


function startHideTimer() {
    var timeout = null;
    
    $(document).on("mousemove", function() {
        clearTimeout(timeout);
        $(".watchNav").fadeIn();

        timeout = setTimeout(function() {
            $(".watchNav").fadeOut();
        }, 2000);
    })
}

function initVideo(videoId, username) {
    startHideTimer();
    setStartTime(videoId, username);
    updateProgressTimer(videoId, username);
}

function updateProgressTimer(videoId, username) {
    addDuration(videoId, username);

    var timer;

    $("video").on("playing", function(event) {
        window.clearInterval(timer);
        timer = window.setInterval(function() {
            updateProgress(videoId, username, event.target.currentTime);
        }, 1000);
    })
    .on("ended", function() {
        setFinished(videoId, username);
        SetFinishedEntity(videoId,username);
        window.clearInterval(timer);
    })
}

function addDuration(videoId, username) {
    $.post("ajax/addDuration.php", { videoId: videoId, username: username }, function(data) {
        if(data !== null && data !== "") {
            alert(data);
        }
    })
}

function updateProgress(videoId, username, progress) {
    $.post("ajax/updateDuration.php", { videoId: videoId, username: username, progress: progress }, function(data) {
        if(data !== null && data !== "") {
            alert(data);
        }
    })
}

function setFinished(videoId, username) {
    $.post("ajax/setFinished.php", { videoId: videoId, username: username }, function(data) {
        if(data !== null && data !== "") {
            alert(data);
        }
    })

}


function SetFinishedEntity(videoId,username){
    $.post("ajax/setFinishedEntity.php", { videoId:videoId,username:username }, function(data) {
        if(data !== null && data !== "") {
            // alert(data);
        }
    })
}

function setStartTime(videoId, username) {
    $.post("ajax/getProgress.php", { videoId: videoId, username: username }, function(data) {
        var progress = parseFloat(data); 
        if(isNaN(progress)) {
            alert("Error fetching progress: " + data);
            return;
        }

        $("video").on("canplay", function() {
            this.currentTime = progress;
            $("video").off("canplay");
        });
    });
}


function restartVideo() {
    $("video")[0].currentTime = 0;
    $("video")[0].play();
    $(".upNext").fadeOut();
}

function watchVideo(videoId) {
    window.location.href = "watch.php?id=" + videoId;
}

function showUpNext() {
    $(".upNext").fadeIn();
}

function Sure() {

    var confirmation = confirm("Are You Sure!! IT CANT BE RESORTED");


    if (confirmation) {
        window.location.href = "removeAccount.php";
    } else {

        return;
    }
}


function DeleteUser(username){
    $.post("ajax/DeleteUser.php",{username: username},function(data){
        alert(data)
        location.reload();
    

    })
}


function DeleteMessage(id){
    $.post("ajax/DeleteMessage.php",{id:id},function(){
        location.reload();
    });
}


function DeleteEpisode(id) {
    if (confirm("Are you sure you want to delete this episode?")) {
        $.post("ajax/DeleteEpisode.php", { id: id }, function(response) {
            alert(response); 
            location.reload();
        }).fail(function() {
            alert("Error: Could not reach server.");
        });
    }
}

function UpdateEpisode(id) {
    
    var destinationPage = "delete_episode.php?id=" + id; 

    window.location.href = destinationPage;
}

function updateUserSub(username){
    $.post("ajax/updateUserSub.php", { username: username }, function(data) {
    
            location.reload()
        
    })
}


function updateUserNotSub(username){
    $.post("ajax/updateUserNotSub.php", { username: username }, function(data) {
    
        location.reload()
    
})
}



function WatchLater(entityId) {
    $.post("ajax/WatchLaterAdd.php", { entityId: entityId }, function(data) {
        
    }).fail(function() {
        alert("An error occurred while making the request.");
    });


}

function RemoveWatchLater(entityId) {
    $.post("ajax/RemoveWatchLater.php", { entityId: entityId }, function(data) {
        location.reload();
        
        
    }).fail(function() {
        alert("An error occurred while making the request.");
    });
}




function DeleteMessageInAd(id){
    $.post("ajax/DeleteMessageInAd.php",{id:id},function(){
        location.reload();
    });
}

function WatchLaterVideo(videoId){
    $.post("ajax/WatchLaterVideoAndMovie.php",{videoId:videoId},function(){
        
    });
}

function RemoveWatchLaterVideo(videoId){
    $.post("ajax/RemoveWatchLaterVideo.php", { videoId: videoId }, function(data) {
        location.reload();
                
    }).fail(function() {
        alert("An error occurred while making the request.");
    });
}


// function updateEntityCount(change) {
//     var totalEntitiesCountElement = document.getElementById("totalEntitiesCount");
//     var currentCount = parseInt(totalEntitiesCountElement.innerText);
//     totalEntitiesCountElement.innerText = currentCount + change;
// }


