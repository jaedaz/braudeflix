<?php require_once("includes/config.php");
    require_once("includes/header.php");
    require_once("includes/classes/ErrorMessage.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="assets/style/styleChat.css" />
</head>
<body>
    

<div id="container">

<?php 

    $qu1 = $con->prepare("SELECT * FROM chat WHERE username = :username ORDER BY date DESC");
    $qu1->bindValue(":username",$_SESSION["userLoggedIn"]);
    $qu1->execute();
    if($qu1->rowCount() == 0){
        echo ErrorMessage::show("There is no message yet");
    }else{
    while($row1 = $qu1->fetch(PDO::FETCH_ASSOC)){

    
?>
    <div id="chatBox">
        <div id="chatData">
                <span>Hello :</span>
                <span><?php echo $row1["username"];?> </span>
                <span><?php echo $row1["msg"]?></span>
                <span> <?php echo $row1["date"]?></span>
                <span>Message Id : <?php echo $row1["id"]?></span>
        </div>
    </div>
    <?php }}?>
</div>


</body>
</html>