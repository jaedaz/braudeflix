<?php 
require_once("includes/config.php");
require_once("includes/classes/User.php");
include "includes/navBar.php";

$user = new User($con, $_SESSION["userLoggedIn"]);
$gmail = $user->getEmail();

$username = $_SESSION["userLoggedIn"];


$sqlLastMessage = $con->prepare("SELECT MAX(date) AS last_sent_date FROM MessageToAdmin WHERE username = :username");
$sqlLastMessage->bindValue(":username", $username);
$sqlLastMessage->execute();
$result = $sqlLastMessage->fetch(PDO::FETCH_ASSOC);

$lastSentDate = $result['last_sent_date'];
$currentDate = date('Y-m-d H:i:s');




$diff = strtotime($currentDate) - strtotime($lastSentDate);
$daysDifference = round($diff / (60 * 60 * 24));


if ($daysDifference >= 30) {
    $deletePreviousMessage = $con->prepare("DELETE FROM MessageToAdmin WHERE username = :username AND date = :lastSentDate");
    $deletePreviousMessage->bindValue(":username", $username);
    $deletePreviousMessage->bindValue(":lastSentDate", $lastSentDate);
    $deletePreviousMessage->execute();
}


if (isset($_POST["Send"])) {

    if ($daysDifference >= 30) {
        $subject = $_POST["subject"];
        $message = $_POST["message"];

        $stmt = $con->prepare("INSERT INTO MessageToAdmin (username, gmail, message, subject) VALUES (:username, :gmail, :message, :subject)");
        $stmt->bindValue(":username", $username);
        $stmt->bindValue(":gmail", $gmail);
        $stmt->bindValue(":subject", $subject);
        $stmt->bindValue(":message", $message);

        $stmt->execute();
    } else {

        echo "<script>alert('You can only send a message once a month.')</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #e3f2fd, #90caf9);
            padding: auto;
            margin: auto;
        }

        .topBar {
            background: linear-gradient(to right, #42a5f5, #2196f3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .topBar .logoContainer a img {
            height: 50px;
        }

        .topBar .navLinks {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        .topBar .navLinks li {
            margin: 0 10px;
        }

        .topBar .navLinks li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .topBar .navLinks li a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .topBar .rightItems a {
            color: white;
            text-decoration: none;
            font-size: 20px;
            margin-left: 15px;
            transition: color 0.3s;
        }

        .topBar .rightItems a:hover {
            color: #ffeb3b;
        }

        .wrapper {
            margin-top: 80px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .contact-form {
            background: #ffffff;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        .contact-form h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .contact-form .form-group {
            margin-bottom: 15px;
        }

        .contact-form .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .contact-form .form-group input, 
        .contact-form .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .contact-form .form-group textarea {
            resize: none;
            height: 150px;
        }

        .contact-form .form-group button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: linear-gradient(to right, #42a5f5, #2196f3);
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .contact-form .form-group button:hover {
            background: linear-gradient(to right, #1e88e5, #1976d2);
        }

        @media (max-width: 600px) {
            .contact-form {
                padding: 20px;
            }

            .contact-form h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<div class='wrapper'>
    <div class="contact-form">
        <h2>Call Admin</h2>
        <?php if ($daysDifference < 30): ?>
            <p style="color: red; text-align: center;">You can only send a message once a month. Please try again later.</p>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="name">Username</label>
                <input type="text" id="name" name="name" value="<?php echo $_SESSION["userLoggedIn"] ?>" readonly>
            </div>
            <div class="form-group">
                <label for="email">Gmail</label>
                <input type="email" id="email" name="email" value="<?php echo $gmail; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea name="message" id="message" rows="5" maxlength="400" required></textarea>
            </div>
            <div class="form-group">
                <?php if ($daysDifference >= 30): ?>
                    <button  style="color: greenyellow;" type="submit" name="Send">Send</button>
                <?php else: ?>
                    <button style="color: red;" type="button" disabled>Cant Now</button>
                <?php endif; ?>
            </div>
        </form>
        <strong>You can also send a message to this email: braudeflix@gmail.com</strong>
    </div>
</div>



</body>
</html>
