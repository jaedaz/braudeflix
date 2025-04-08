<?php

require_once("../includes/config.php");




if(isset($_POST["id"])){
    $id = $_POST["id"];
    $qu = $con->prepare("DELETE FROM chat WHERE id = :id");
    $qu->bindValue(":id",$id);
    $qu->execute();
}
