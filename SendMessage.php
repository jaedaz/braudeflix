<?php require_once("includes/config.php");
    require_once("includes/headerAdmin.php")
?>


<?php
    
    $query = $con->prepare("SELECT * FROM users");
    $query->execute();
    $users = $query->fetchAll(PDO::FETCH_ASSOC);
    array_unshift($users, array("id" => 0, "username" => "All"));

?>


<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(isset($_POST["Submit"])){
    $to = $_POST["to"];
    $message = $_POST["msg"];
    print_r($to);
    $flag = 0;
    if($to === "All"){
        foreach($users as $user){
            if($user["username"] === "All"){
                continue;
            }
            $sql = $con->prepare("INSERT INTO chat (username,msg) VALUES (:username,:msg)");
            $sql->bindValue(":username",$user["username"]);
            $sql->bindValue(":msg",$message);
            $sql->execute();
        }
    }else{
        foreach($users as $user){
            if($user["username"] === $to){
                $flag = 1;
                break;
            }
        }
        if($flag == 0){
            echo "<span style='color:red'>There is some thing wrong!</span>";
        }else{
            $sql = $con->prepare("INSERT INTO chat (username,msg) VALUES (:username,:msg)");
            $sql->bindValue(":username",$to);
            $sql->bindValue(":msg",$message);
            $sql->execute();
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="assets/style/styleChat.css" />

<body>
    

<div id="container">

<?php 

$qu = $con->prepare("SELECT * FROM chat ORDER BY date DESC");

    $qu->execute();
    while($row = $qu->fetch(PDO::FETCH_ASSOC)){

    
?>
    <div id="chatBox">
        <div id="chatData">
                <span>Hello :</span>
                <span><?php echo $row["username"];?> </span>
                <span><?php echo $row["msg"]?></span>
                <span> <?php echo $row["date"]?></span>
                <span>Message Id : <?php echo $row["id"]?></span>
        </div>
        <button onclick="DeleteMessage(<?php echo $row['id']?>)">Delete Message</button>
    </div>
        <?php } ?>
    <form id="messageForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <select class="form-control" name="to">
    <option>Select Name To Send</option>
    <?php foreach ($users as $user): ?>
        <option value="<?php echo $user['username']; ?>"><?php echo $user['username']; ?></option>

    <?php endforeach; ?>
</select>
    <textarea name="msg" type="text" placeholder="Click Your Message">
    </textarea>
    <input type="submit" name="Submit" value="Send">
    </form>

</div>



</body>
</html>