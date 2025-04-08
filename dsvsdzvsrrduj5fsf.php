<?php

require_once("includes/headerAdmin.php");
require_once("includes/classes/PreviewProvider.php");



// $query = $con->prepare("SELECT entityId,SUM(views) AS total_views FROM videos GROUP BY  entityId ORDER BY total_views ASC");

// $query->execute();
// $result = $query->fetchAll(PDO::FETCH_ASSOC);






$preview = new PreviewProvider($con, $userLoggedIn);
echo $preview->createBestPreviewVideo();

// $containers = new CategoryContainers($con, $userLoggedIn);
// echo $containers->showBestCategories();


?>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper('.swiper-container', {
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    var swiperContainer = document.querySelector('.swiper-container');
    swiperContainer.addEventListener('mouseenter', function () {
        swiper.autoplay.stop();
    });


    swiperContainer.addEventListener('mouseleave', function () {
        swiper.autoplay.start();
    });
</script>











