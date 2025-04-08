    <?php

    require_once("../includes/config.php");




    if(isset($_POST["id"])){
        $id = $_POST["id"];
        $qu = $con->prepare("DELETE FROM MessageToAdmin WHERE id = :id");
        $qu->bindValue(":id",$id);
        $qu->execute();
    }
