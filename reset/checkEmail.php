<?php 
$con = new PDO("mysql:host=localhost; dbname=braudeflix", "root", "");
require_once("../includes/classes/Account.php");

// Define the variables with default values
$success = "";
$error = "";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST["send_link"])){
    $email = $_POST["email"];

    //Import PHPMailer classes into the global namespace
    require 'Mail/Exception.php';
    require 'Mail/PHPMailer.php';
    require 'Mail/SMTP.php';

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'braudeflixwatch@gmail.com';                     //SMTP username
        $mail->Password   = 'uhny mkpx nozf myjg';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('braudeflixwatch@gmail.com', 'BraudeFlix');
        $mail->addAddress($email);     //Add a recipient

        $code = Account::generateRandomString();

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Password Reset';
        $mail->Body = 'To reset your password, click <a href="http://localhost/braudeflix/reset/resetPassword.php?code='.urlencode($code).'&email='.urlencode($email).'">here</a>.<br>Reset your password within a day.';

        $query = $con->prepare("SELECT * FROM users WHERE email=:email");
        $query->bindValue(":email", $email);
        $query->execute();

        if($query->rowCount() == 1){
            $codeQuery = $con->prepare("UPDATE users SET code = :code, updated_time = NOW() WHERE email = :email");
            $codeQuery->bindValue(":email", $email);
            $codeQuery->bindValue(":code", $code);
            $codeQuery->execute();
            $mail->send();
            $success = '<span style="width:100%,padding:10px;background-color:lightgreen;border-radius:30px">Message has been sent, check your email!</span>';
        } else {
            $error = "<span style='width:100%;background:red;padding:10px;border-radius:30px'>Message could not be sent. Your email does not exist!</span>";
        }
    } catch (Exception $e) {
        $error = "<span style='width:100%;background:red;padding:10px;border-radius:30px'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</span>";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Forgot Password</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../assets/style/style.css" />
    </head>
    <body>
        <div class="signInContainer">
            <div class="column">
                <div class="header">
                    <img src="../assets/images/logo.png" title="Logo" alt="Site logo" />
                    <h3>Forgot Password</h3>
                    <span>Click Your Email</span>
                </div>

                <!-- Display success or error messages -->
                <?php if(!empty($success)): ?>
                    <div class="alert alert-success">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <?php if(!empty($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="submit" name="send_link" value="Send Link">
                </form>

                <a href="../login.php" class="signInMessage">Log In</a>
            </div>
        </div>
    </body>
</html>
