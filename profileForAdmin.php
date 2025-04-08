<?php
require_once("includes/headerAdmin.php");
require_once("includes/paypalConfig.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Constants.php");
require_once("includes/classes/BillingDetails.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$user = new User($con, $userLoggedIn);

$detailsMessage = "";
$passwordMessage = "";
$subscriptionMessage = "";

if(isset($_POST["saveDetailsButton"])) {
    $account = new Account($con);

    $firstName = FormSanitizer::sanitizeFormString($_POST["firstName"]);
    $lastName = FormSanitizer::sanitizeFormString($_POST["lastName"]);
    $email = FormSanitizer::sanitizeFormEmail($_POST["email"]);

    if($account->updateDetails($firstName, $lastName, $email, $userLoggedIn)) {
        $detailsMessage = "<div class='alertSuccess'>
                                Details updated successfully!
                            </div>";
    }
    else {
        $errorMessage = $account->getFirstError();

        $detailsMessage = "<div class='alertError'>
                                $errorMessage
                            </div>";
    }
}

if(isset($_POST["savePasswordButton"])) {
    $account = new Account($con);

    $oldPassword = FormSanitizer::sanitizeFormPassword($_POST["oldPassword"]); 
    $newPassword = FormSanitizer::sanitizeFormPassword($_POST["newPassword"]);
    $newPassword2 = FormSanitizer::sanitizeFormPassword($_POST["newPassword2"]);

    if($account->updatePassword($oldPassword, $newPassword, $newPassword2, $userLoggedIn)) {
        $passwordMessage = "<div class='alertSuccess'>
                                Password updated successfully!
                            </div>";
    }
    else {
        $errorMessage = $account->getFirstError();

        $passwordMessage = "<div class='alertError'>
                                $errorMessage
                            </div>";
    }
}



?>

<?php

    $Bos = $con->prepare("SELECT * FROM users WHERE isBosAdmin = 1");
    $Bos->execute();
    $resultBos = $Bos->fetch(PDO::FETCH_ASSOC);

?>

<?php
if(isset($_POST["submitButton"])) {
    $firstName = FormSanitizer::sanitizeFormString($_POST["firstName"]);
    $lastName = FormSanitizer::sanitizeFormString($_POST["lastName"]);
    $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);
    $email =  FormSanitizer::sanitizeFormUsername($_POST["email"]);
    $email2 =  $email;
    $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);
    $password2 = FormSanitizer::sanitizeFormPassword($_POST["password2"]);

    $account->register($firstName,$lastName,$username,$email,$email2,$password,$password2);

    $setAdmin = $con->prepare("UPDATE users SET isAdmin = 1 WHERE username = :username");
    $setAdmin->bindValue(":username",$username);
    $setAdmin->execute();
}


?>

<div class="settingsContainer column">

    <div class="formSection">

        <form method="POST">

            <h2>User details</h2>
            
            <?php

            $firstName = isset($_POST["firstName"]) ? $_POST["firstName"] : $user->getFirstName();
            $lastName = isset($_POST["lastName"]) ? $_POST["lastName"] : $user->getLastName();
            $email = isset($_POST["email"]) ? $_POST["email"] : $user->getEmail();
            ?>

            <input type="text" name="firstName" placeholder="First name" value="<?php echo $firstName; ?>">
            <input type="text" name="lastName" placeholder="Last name" value="<?php echo $lastName; ?>">
            <input type="email" name="email" placeholder="Email" value="<?php echo $email; ?>">

            <div class="message">
                <?php echo $detailsMessage; ?>
            </div>
            
            <input type="submit" name="saveDetailsButton" value="Save">


        </form>

    </div>

    <div class="formSection">

        <form method="POST">

            <h2>Update password</h2>

            <input type="password" name="oldPassword" placeholder="Old password">
            <input type="password" name="newPassword" placeholder="New password">
            <input type="password" name="newPassword2" placeholder="Confirm new password">

            <div class="message">
                <?php echo $passwordMessage; ?>
            </div>

            <input type="submit" name="savePasswordButton" value="Save">


        </form>

    </div>

    <?php if ($resultBos && strtolower($resultBos["username"]) == strtolower($_SESSION["userLoggedIn"])) { ?>
    <div class="formSection">
        <form method="POST">
            <h2>Add Admin</h2>
            <input type="text" name="firstName" placeholder="First Name">
            <input type="text" name="lastName" placeholder="Last Name">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <input type="password" name="password2" placeholder="Confirm Password">
            <input type="email" name="email" placeholder="Email">
            <div class="message">
                <?php echo $passwordMessage; ?>
            </div>
            <input type="submit" name="submitButton" value="Add">
        </form>
    </div>
<?php } ?>



</div>


