<?php
require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Constants.php");
require_once("includes/classes/Account.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$account = new Account($con);
$sqlInjectionAttempt = "";
if(isset($_POST["submitButton"])) {
    $firstName = FormSanitizer::sanitizeFormString($_POST["firstName"]);
    $lastName = FormSanitizer::sanitizeFormString($_POST["lastName"]);
    $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);
    $email = FormSanitizer::sanitizeFormEmail($_POST["email"]);
    $email2 = FormSanitizer::sanitizeFormEmail($_POST["email2"]);
    $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);
    $password2 = FormSanitizer::sanitizeFormPassword($_POST["password2"]);


    if(!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/', $password)) {
        $account->addError(Constants::$passwordNotStrong);
    }


    if (FormSanitizer::containsNonEnglishCharacters($_POST["firstName"]) ||
    FormSanitizer::containsNonEnglishCharacters($_POST["lastName"]) ||
    FormSanitizer::containsNonEnglishCharacters($_POST["username"]) ||
    FormSanitizer::containsNonEnglishCharacters($_POST["password"]) ||
    FormSanitizer::containsNonEnglishCharacters($_POST["password2"])) {
    $account->addError(Constants::$nonEnglishCharacters);
}


    if(FormSanitizer::containsSqlInjection($_POST)) {
        $account->addError(Constants::$sqlInjectionAttempt);
    }

    $success = $account->register($firstName, $lastName, $username, $email, $email2, $password, $password2);
    $account->setOnline($username); 
    if($success) {
        $_SESSION["userLoggedIn"] = $username;
        header("Location: login.php");
    }
}

function getInputValue($name) {
    if(isset($_POST[$name])) {
        echo $_POST[$name];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Braudeflix</title>
    <link rel="stylesheet" type="text/css" href="assets/style/style.css"/>
    <style>
        .error {
            border: 2px solid red;
        }
    </style>
</head>
<body>

<div class="signInContainer">
    <div class="column">
        <div class="header">
            <img src="assets/images/logo.png" title="Logo" alt="Site logo"/>
            <h3>Sign Up</h3>
            <span>to continue to Braudeflix</span>
        </div>

        <form method="POST">

            <?php echo $account->getError(Constants::$firstNameCharacters); ?>
            <?php if($sqlInjectionAttempt){
                echo  $account->getError(Constants::$sqlInjectionAttempt);
            } ?>
            <input type="text" name="firstName" placeholder="First name"
                value="<?php getInputValue("firstName"); ?>" required>


            <?php echo $account->getError(Constants::$lastNameCharacters); ?>
            <?php if($sqlInjectionAttempt){
                echo  $account->getError(Constants::$sqlInjectionAttempt);
            } ?>
            <input type="text" name="lastName" placeholder="Last name"
                value="<?php getInputValue("lastName"); ?>" required>

            <?php echo $account->getError(Constants::$usernameCharacters); ?>
            <?php echo $account->getError(Constants::$usernameTaken); ?>
            <?php if($sqlInjectionAttempt){
                echo  $account->getError(Constants::$sqlInjectionAttempt);
            } ?>
            <input type="text" name="username" placeholder="Username"
                value="<?php getInputValue("username"); ?>" required>


            <?php echo $account->getError(Constants::$emailsDontMatch); ?>
            <?php echo $account->getError(Constants::$emailInvalid); ?>
            <?php echo $account->getError(Constants::$emailTaken); ?>
            <?php if($sqlInjectionAttempt){
                echo  $account->getError(Constants::$sqlInjectionAttempt);
            } ?>
            <input type="email" name="email" placeholder="Email" value="<?php getInputValue("email"); ?>"
                required>

                <?php if($sqlInjectionAttempt){
                echo  $account->getError(Constants::$sqlInjectionAttempt);
            } ?>
            <input type="email" name="email2" placeholder="Confirm email"
                value="<?php getInputValue("email2"); ?>" required>

            <?php echo $account->getError(Constants::$passwordsDontMatch); ?>
            <?php echo $account->getError(Constants::$passwordLength); ?>
            <?php echo $account->getError(Constants::$passwordNotStrong); ?>
            <?php if($sqlInjectionAttempt){
                echo  $account->getError(Constants::$sqlInjectionAttempt);
            } ?>
            <input type="password" name="password" placeholder="Password" required
                class="<?php echo $account->getError(Constants::$passwordNotStrong) ? 'error' : ''; ?>">

            <input type="password" name="password2" placeholder="Confirm password" required>

            <input type="submit" name="submitButton" value="SUBMIT">

        </form>

        <a href="login.php" class="signInMessage">Already have an account? Sign in here!</a>

    </div>
</div>

</body>
</html>
