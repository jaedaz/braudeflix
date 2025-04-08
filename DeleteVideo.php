<?php
require_once("includes/headerAdmin.php");

if(!isset($_GET["id"])) {
    ErrorMessage::show("No ID passed into page");
}
$entityId = $_GET["id"];
$entity = new Entity($con, $entityId);

// $preview = new PreviewProvider($con, $userLoggedIn);
// echo $preview->createPreviewVideo($entity);

$seasonProvider = new SeasonProvider($con, $userLoggedIn);
echo $seasonProvider->createToDelete($entity);

$entityId1 = Entity::getMustCategoryView($con, $userLoggedIn);
$entity1 = new Entity($con, $entityId1);

?>
<script>function toggleDescription(element) {
    element.classList.toggle('scroll');
}
</script>

