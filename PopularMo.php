<!-- <?php

// require_once("includes/header.php");
// require_once("includes/classes/PreviewProvider.php");


// $preview = new PreviewProvider($con, $userLoggedIn);
// echo $preview->createBestPreviewMovie();
        

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


 -->
