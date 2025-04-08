<div class="topBar">

    <div class="logoContainer">
        <a href="index.php">
            <img src="assets/images/logo.png" alt="Logo">
        </a>
    </div>

    <ul class="navLinks">
        <li><a href="index.php">Home</a></li>
        <li><a href="shows.php">TV Shows</a></li>
        <li><a href="movies.php">Movies</a></li>
        <li><a href="Message.php">AdminMessage</a></li>
        <li><a href="sendToAdmin.php">Send To Admin</a></li>
        <li><strong>Online:</strong> <span id="onlineUsersCount"></span></li>


    </ul>   
    
    
    <div class="rightItems">
        <a href="search.php">
            <i class="fas fa-search"></i>
        </a>

        <a href="profile.php">
            <i class="fas fa-user"></i>
        </a>

        <a href="logout.php">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>

</div>

<script>
function fetchOnlineUsers() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "ajax/get_online_users.php", true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("onlineUsersCount").innerText = xhr.responseText;
        }
    };
    xhr.send();
}

// Call the function every 10 seconds
setInterval(fetchOnlineUsers, 10000);

// Call the function once when the page loads
fetchOnlineUsers();
</script>

