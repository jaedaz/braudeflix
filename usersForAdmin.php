<?php

// اتصال بقاعدة البيانات
require_once("includes/config.php");

// استعلام SQL لاسترداد كل المستخدمين
$query = $con->prepare("SELECT * FROM users WHERE isAdmin=0");
$query->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/06a651c8da.js" crossorigin="anonymous"></script>
        <script src="assets/js/script.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>List Users</title>
    <link rel="stylesheet" type="text/css" href="assets/style/style.css"/>
</head>
<body>

<div class="table-container">
    <table>
        <tr>
            <th>Id</th>
            <th>FirstName</th>
            <th>LastName</th>
            <th>Username</th>
            <th>Email</th>
            <th>isSubscribe</th>
        </tr>
        <?php
        // عرض البيانات في الجدول
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['firstName'] . "</td>";
            echo "<td>" . $row['lastName'] . "</td>";
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['isSubscribed'] . "</td>";
            echo "<td>" . "<button  onclick='DeleteUser(\"{$row['username']}\")' class='DeleteUser'>Delete</button>";
            echo "<td>" . "<button  onclick='updateUserNotSub(\"{$row['username']}\")' class='DeleteUser'>!MakeSub</button>";
            echo "<td>" . "<button  onclick='updateUserSub(\"{$row['username']}\")' class='DeleteUser'>MakeSub</button>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

    <?php require_once("footerAdmin.php") ?>

</body>
</html>
